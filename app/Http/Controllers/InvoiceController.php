<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\School;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoicesTaxes;
use App\Models\InvoicesExpenses;
use App\Models\Student;
use App\Models\Event;
use App\Models\Teacher;
use App\Models\Province;
use App\Models\InvoiceSended;
use App\Models\Currency;
use App\Models\EventDetails;
use App\Models\SchoolTeacher;
use App\Models\ParentStudent;
use App\Models\User;
use App\Models\SchoolStudent;
use App\Models\AttachedFile;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Redirect;
use DB;
use Exception;
use PDF;
use DateTimeZone;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Traits\UserRoleTrait;
use App\Helpers\ReminderEmail;
use App\Helpers\InvoiceDataMapper;

class InvoiceController extends Controller
{
    use UserRoleTrait;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $schoolId = null,$type = null)
    {
        $user = $request->user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $timeZone = 'UTC';
        if (!empty($school->timezone)) {
            $timeZone = $school->timezone;
        }
        $invoice_type_all = config('global.invoice_type');
        $payment_status_all = config('global.payment_status');

        list($user_role, $invoice_type) = $this->getUserRoleInvoiceType($user, $school);

        $invoices = Invoice::active()
                    ->where('school_id', $schoolId);

        if ($user_role != 'superadmin') {
            if ($user->isTeacherSchoolAdmin()) {
                $invoice_type = ($type == 'school') ? 'S' : 'T';
                $invoices->where('category_invoiced_type', $invoice_type);
            }else if ($user_role == 'teacher') {
                $invoice_type = ($type == 'school') ? 'S' : 'T';
                $invoices->where('category_invoiced_type', $invoice_type);
                $invoices->where('seller_id', $user->person_id);
            }else if ($user_role == 'student') {
                $invoice_type = 'S';
                $invoices->where('category_invoiced_type', $invoice_type);
                $invoices->where('client_id', $user->person_id);
                $invoices->where('invoice_status', 10);
            }else if ($user_role == 'parent') {
                $allOthersMembers = ParentStudent::where('parent_id', $user->person_id)->get();
                $parentMembers = [];
                foreach ($allOthersMembers as $member) {
                $student2 = SchoolStudent::where('student_id', $member->student_id)->first();
                $parentMembers[] = $student2->student_id;
                }
                $students = $parentMembers;
                $invoice_type = 'S';
                $invoices->where('category_invoiced_type', $invoice_type);
                $invoices->whereIn('client_id', $students);
                $invoices->where('invoice_status', 10);
            } else {
                $invoices->where('category_invoiced_type', $invoice_type);
            }

        }

        $invoices->orderBy('date_invoice', 'asc');
        //$invoices->orderBy('id', 'desc');
        //$invoices = Invoice::orderByRaw('DATE_FORMAT(date_invoice, "%Y-%m-%d %H:%i:%s") DESC');
        $results =  $invoices = $invoices->where('school_id', $schoolId)->get();


    return view('pages.invoices.list', compact('timeZone','school', 'invoices', 'schoolId', 'invoice_type_all', 'payment_status_all'));
    }


    public function report(Request $request, $schoolId = null,$type = null)
    {
        $user = $request->user();
        $school = School::active()->find($schoolId);
        $currency = $school->default_currency_code;
        return view('pages.invoices.report', compact('user','currency'));
    }


    /**
     *  AJAX action to get invoice report
     *
     * @return json
     * @version 0.1 written in 2023-09-26
     */
    public function getReport(Request $request)
    {
        try {

            $data = $request->all();
            $user = $request->user();
            $this->schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
            $school = School::active()->find($this->schoolId);
            $startDate = $request->input('billing_period_start_date');
            $endDate = $request->input('billing_period_end_date');

            $invoices = Invoice::active()
            ->with('invoice_items')
            ->where('school_id', $school->id)
            ->whereBetween('date_invoice', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('total_amount', '>', 0)
            ->orderBy('date_invoice', 'desc')
            ->get();

        // Loop through invoices and load the eventDetail for each invoice_item
        $invoices->each(function ($invoice) {
            $invoice->invoice_items->each(function ($item) {
                $eventDetail = DB::table('event_details')
                    ->where('event_id', $item->event_id)
                    ->first();
                $item->eventDetail = $eventDetail;
            });
        });

        $invoices->each(function ($invoice) {

                $invoiceTaxes = DB::table('invoices_taxes')
                    ->where('invoice_id', $invoice->id)
                    ->get();
                $invoice->invoiceTaxes = $invoiceTaxes;

        });

        return response()->json($invoices);

        } catch (Exception $e) {

            $result = array(
                'status' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            );
            return response()->json($result);
        }
    }

    /**
     *  AJAX action to get email for pay reminder
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-05-27
     */
    public function payReminderEmailFetch(Request $request)
    {
        $result = array(
            'status' => false,
            'message' => __('failed to send email'),
        );
        try {
            $data = $request->all();
            $p_auto_id = trim($data['p_auto_id']);
            $data = array();
            $result_data = Invoice::active()->find($p_auto_id);
            $filtered_invoice = [0, 1, 9];
            $result_data->student_email = '';
            $result_data->father_email = '';
            $result_data->mother_email = '';

            if (in_array($result_data->invoice_type, $filtered_invoice)) {
                $result_data->class_name = 'student';
                $student = Student::find($result_data->client_id);
                if ($student) {
                    if (isset($student->student_notify) && $student->student_notify == 1) {
                        //$result_data->student_email = $student->email2;
                        $result_data->secondary_email = $student->email2;
                    }
                    if (isset($student->father_notify) && $student->father_notify == 1) {
                        $result_data->father_email = $student->father_email;
                    }
                    if (isset($student->mother_notify) && $student->mother_notify == 1) {
                        $result_data->mother_email = $student->mother_email;
                    }
                    $result_data->class_name = 'student';
                    $result_data->primary_email = $student->email;
                    $result_data->student_email = $student->email;
                    //$result_data->secondary_email = $student->email2;
                }

            } else {
                $result_data->class_name = 'teacher';
                $teacher = Teacher::find($result_data->seller_id);
                $result_data->primary_email = $teacher->email;
                $result_data->secondary_email = $teacher->email2;
            }
            $data[] = $result_data;
            $result = array(
                'status' => true,
                'message' => __('We sent an email.'),
                'data' => $data
            );

            return response()->json($result);
        } catch (Exception $e) {
            //echo $e->getMessage();
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
    }


    /**
     *  AJAX action to send email for pay reminder
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-05-27
     */
    public function payReminderEmailSend(Request $request)
    {
        $result = array(
            'status' => false,
            'message' => __('failed to send email'),
        );
        try {
            $data = $request->all();
            $p_inv_auto_id = trim($data['p_inv_auto_id']);

            $result_data = Invoice::active()->find($p_inv_auto_id);

            $emails = [];
            $filtered_invoice = [0, 1, 9];
            if (in_array($result_data->invoice_type, $filtered_invoice)) {
                $result_data->target_user = $student = Student::find($result_data->client_id);
                $emails[] = $student->email;
                $emails[] = $student->email2;
            } else {
                $result_data->target_user = $teacher = Teacher::find($result_data->seller_id);
                $emails[] = $teacher->email;
                $emails[] = $teacher->email2;
            }


            $result_data->emails = $emails;

            $invoiceCurrency = InvoiceItem::active()->where('invoice_id', $p_inv_auto_id)->get()->pluck('price_currency')->join(',');
            $result_data->invoice_price = $invoiceCurrency . '' . round($result_data->total_amount, 2);
            //sending email for payment reminder
            if (config('global.email_send') == 1) {
                $payReminderEmail = new ReminderEmail();
                $result = $payReminderEmail->sendReminderEmail($request,$result_data);
            } else {
                $result = array('status' => true, 'msg' => __('email sent'));
            }

            $newInvoiceSended = new InvoiceSended();
            $newInvoiceSended->invoice_id = $p_inv_auto_id;
            $newInvoiceSended->user_id = $result_data->seller_id;
            $newInvoiceSended->student_id = $result_data->client_id;
            $newInvoiceSended->save();

            return response()->json($result);
        } catch (Exception $e) {
            // echo $e->getMessage();
            // exit();
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
    }

    /**
     *  AJAX action for payment update
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-10-18
     */
    public function updatePaymentStatus(Request $request)
    {
        $result = array(
            'status' => false,
            'payment_status' => 0,
            'invoice_status' => 0,
            'message' => __('failed to send email'),
        );
        // $result = array(
        //     'status' => 'success',
        //     'payment_status' => 0,
        //     'invoice_status' =>1,
        //     'message' => __('failed to send email'),
        // );
        // return response()->json($result);
        try {
            $data = $request->all();
            $user = $request->user();
            $p_payment_status = 0;
            $invoice_status =0;
            //dd($data);

            if (isset($data['p_payment_status'])) {
                $p_payment_status = trim($data['p_payment_status']);
                $updateInvoice['payment_status'] = $p_payment_status;
            }
            if (isset($data['invoice_status'])) {
                $invoice_status = trim($data['invoice_status']);
                $updateInvoice['invoice_status'] = $invoice_status;
            }
            if (isset($data['approved_flag'])) {
                $approved_flag = trim($data['approved_flag']);
                $updateInvoice['approved_flag'] = $approved_flag;
            }

            $p_auto_id = trim($data['p_auto_id']);
            $invoiceData = Invoice::where('id', $p_auto_id)->update($updateInvoice);
            if($invoiceData){

                $invoice_data = Invoice::with(['school' => function ($q) {
                    $q->select('id','tax_number');
                }])->where('id', $p_auto_id)->first();

                $invoice_data = Invoice::with(['school' => function ($q) {
                    $q->select('id','tax_number');
                }])->where('id', $p_auto_id)->first();

                $InvoicesTaxData = InvoicesTaxes::active()->where(['invoice_id'=> $invoice_data->id])->get();
                $InvoicesExpData = InvoicesExpenses::active()->where(['invoice_id'=> $invoice_data->id])->get();

                $invoice_items = DB::table('invoice_items')
                                ->leftJoin('events', 'events.id', '=', 'invoice_items.event_id')
                                ->where('invoice_items.invoice_id', $p_auto_id)
                                ->orderBy('events.event_type','ASC')
                                ->orderBy('invoice_items.item_date','ASC')->get();
                $items = [];
                foreach($invoice_items as $key=>$d){
                    if(!isset($items[$d->event_type])){
                        $items[$d->event_type] = array();
                    }
                    $items[$d->event_type][] = $d;
                }
                $schoolId = $user->selectedSchoolId();
                $school = School::active()->find($schoolId);
                $invoice_items = $items;
                $date_from = strtolower(date('F.Y', strtotime($invoice_data->date_invoice)));
                $invoice_name = 'invoice-'.$invoice_data->id.'-'.strtolower($invoice_data->client_firstname).'.'.strtolower($invoice_data->client_lastname).'.'.$date_from.'.pdf';
                $pdf = PDF::loadView('pages.invoices.invoice_pdf_view', ['school' => $school, 'invoice_data'=> $invoice_data,'invoice_items'=> $invoice_items, 'invoice_name' => $invoice_name, 'InvoicesTaxData' => $InvoicesTaxData, 'InvoicesExpData' => $InvoicesExpData]);
                $pdf->set_option('isHtml5ParserEnabled', true);
                $pdf->set_option('isRemoteEnabled', true);
                $pdf->set_option('DOMPDF_ENABLE_CSS_FLOAT', true);
                $pdf->setHttpContext(
                    stream_context_create([
                        'ssl' => [
                            'allow_self_signed'=> TRUE,
                            'verify_peer' => FALSE,
                            'verify_peer_name' => FALSE,
                        ]
                    ])
                  );
                $pdf->render();
                // save invoice name if invoice_filename is empty
                $file_upload = Storage::put('pdf/'. $invoice_name, $pdf->output());
                if($file_upload){
                    $invoice_pdf_path = URL::to("").'/uploads/pdf/'.$invoice_name;
                    $invoice_data->invoice_filename = $invoice_pdf_path;
                    $invoice_data->save();
                }
            }
            $invoiceData = Invoice::where('id', $p_auto_id)->update($updateInvoice);
            $result = array(
                'status' => 'success',
                'message' => __('We got a list of invoice'),
                'payment_status' => $p_payment_status,
                'invoice_status' =>$invoice_status
            );
            return response()->json($result);
        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
    }

    /**
     *  AJAX action for invoice discount
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-10-28
     */
    public function updateInvoiceDiscount(Request $request)
    {
        $result = array(
            'status' => false,
            'payment_status' => 0,
            'invoice_status' =>0,
            'message' => __('failed to send email'),
        );
        // $result = array(
        //     'status' => 'success',
        //     'payment_status' => 0,
        //     'invoice_status' =>1,
        //     'message' => __('failed to send email'),
        // );
        //return response()->json($result);
        try {

            $data = $request->all();
            $user = $request->user();
            $p_invoice_id = trim($data['p_invoice_id']);
            $updateInvoice['discount_percent_1'] = $p_disc1 = trim($data['p_disc1']);
            $updateInvoice['amount_discount_1'] = $p_amt1 = trim($data['p_amt1']);
            $updateInvoice['extra_expenses'] = $p_extra_expenses = trim($data['p_extra_expenses']);
            $updateInvoice['total_amount_with_discount']  = $total_amount_with_discount = trim($data['total_amount_with_discount']);
            $updateInvoice['total_amount']  = $p_total_amount = trim($data['p_total_amount']);
            $v_total_amount_discount = $p_amt1;
            $updateInvoice['total_amount_discount']  = $v_total_amount_discount;
            //$updateInvoice['tax_amount'] = $p_tax_amount = trim($data['p_tax_amount']);
            $updateInvoice['modified_by'] = $user->id;

            $invoiceData = Invoice::where('id', $p_invoice_id)->update($updateInvoice);
            // print_r($invoiceData);
            // exit();
            if($invoiceData==1){
                $result = array(
                    'status' => 'success',
                    'message' => __('We got a list of invoice')
                );
            }

            return response()->json($result);
        } catch (Exception $e) {
            //return error message
            $result['message'] = $e->getMessage();
            //$result['message'] = __('Internal server error');
            return response()->json($result);
        }
    }


    /**
     *  AJAX action for invoice info update
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-10-30
     */
    public function updateInvoiceInfo(Request $request)
    {
        $result = array(
            'status' => false,
            'payment_status' => 0,
            'invoice_status' =>0,
            'message' => __('failed to send email'),
        );
        // $result = array(
        //     'status' => 'success',
        //     'payment_status' => 0,
        //     'invoice_status' =>1,
        //     'message' => __('failed to send email'),
        // );
        // return response()->json($result);
        try {
            $data = $request->all();
            $user = $request->user();
            $p_invoice_id = trim($data['p_invoice_id']);

            $updateInvoice['date_invoice'] = Carbon::parse(trim($data['date_invoice']))->format('Y-m-d H:i:s');
            $updateInvoice['date_due'] = Carbon::parse(trim($data['date_invoice_due']))->format('Y-m-d H:i:s');
            $updateInvoice['invoice_name']=trim($data['invoice_name']);
            $updateInvoice['invoice_header']=trim($data['invoice_header']);
            $updateInvoice['invoice_footer']=trim($data['invoice_footer']);
            $updateInvoice['client_name']=trim($data['client_name']);
            $updateInvoice['client_gender_id']=trim($data['client_gender_id']);
            $updateInvoice['client_lastname']=trim($data['client_lastname']);
            $updateInvoice['client_firstname']=trim($data['client_firstname']);
            $updateInvoice['client_street']=trim($data['client_street']);
            $updateInvoice['client_street_number']=trim($data['client_street_number']);
            $updateInvoice['client_street2']=trim($data['client_street2']);
            $updateInvoice['client_zip_code']=trim($data['client_zip_code']);
            $updateInvoice['client_place']=trim($data['client_place']);
            $updateInvoice['client_country_code']=trim($data['client_country_id']);

            $updateInvoice['seller_name']=trim($data['seller_name']);
            $updateInvoice['seller_gender_id']=trim($data['seller_gender_id']);
            $updateInvoice['seller_lastname']=trim($data['seller_lastname']);
            $updateInvoice['seller_firstname']=trim($data['seller_firstname']);
            $updateInvoice['seller_street']=trim($data['seller_street']);
            $updateInvoice['seller_street_number']=trim($data['seller_street_number']);
            $updateInvoice['seller_street2']=trim($data['seller_street2']);
            $updateInvoice['seller_zip_code']=trim($data['seller_zip_code']);
            $updateInvoice['seller_place']=trim($data['seller_place']);
            $updateInvoice['seller_country_code']=trim($data['seller_country_id']);
            $updateInvoice['seller_phone']=trim($data['seller_phone']);
            $updateInvoice['seller_mobile']=trim($data['seller_mobile']);
            $updateInvoice['seller_email']=trim($data['seller_email']);
            $updateInvoice['seller_eid']=trim($data['seller_eid']);

            $updateInvoice['payment_bank_account_name']=trim($data['spayment_bank_account_name']);
            $updateInvoice['payment_bank_iban']=trim($data['spayment_bank_iban']);
            $updateInvoice['payment_bank_account']=trim($data['spayment_bank_account']);
            $updateInvoice['payment_bank_swift']=trim($data['spayment_bank_swift']);
            $updateInvoice['payment_bank_name']=trim($data['spayment_bank_name']);
            $updateInvoice['payment_bank_address']=trim($data['spayment_bank_address']);
            $updateInvoice['payment_bank_zipcode']=trim($data['spayment_bank_zipcode']);
            $updateInvoice['payment_bank_place']=trim($data['spayment_bank_place']);
            $updateInvoice['payment_bank_country_code']=trim($data['spayment_bank_country_id']);
            $updateInvoice['etransfer_acc']=trim($data['etransfer_acc']);
            $updateInvoice['cheque_payee']=trim($data['cheque_payee']);

            $invoiceData = Invoice::where('id', $p_invoice_id)->update($updateInvoice);
            if($invoiceData){

            }
            $result = array(
                'status' => 'success',
                'message' => __('We got a list of invoice')
            );
            return response()->json($result);
        } catch (Exception $e) {
            //return error message
            $result['message'] = $e->getMessage();
            return response()->json($result);
        }
    }



    /**
     *  AJAX action for invoice unlock
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-10-28
     */
    public function unlockInvoice(Request $request)
    {
        $result = array(
            'status' => false,
            'payment_status' => 0,
            'invoice_status' =>0,
            'message' => __('failed to send email'),
        );
        // $result = array(
        //     'status' => 'success',
        //     'payment_status' => 0,
        //     'invoice_status' =>1,
        //     'message' => __('failed to send email'),
        // );
        // return response()->json($result);
        try {
            $data = $request->all();
            $user = $request->user();
            $p_invoice_id = trim($data['p_invoice_id']);
            $updateInvoice['invoice_status'] = 1;
            $updateInvoice['approved_flag'] = 0;
            $updateInvoice['payment_status'] = 0;
            $invoiceData = Invoice::where('id', $p_invoice_id)->update($updateInvoice);
            if($invoiceData){
                $result = array(
                    'status' => 'success',
                    'message' => __('We got a list of invoice')
                );
            }
            return response()->json($result);
        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
    }





    /**
     *  List of student invoices by school
     *
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-05-28
     */
    public function student_invoice_list(Request $request, $schoolId = null, $type = null)
    {
        $user = $request->user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $invoice_type_all = config('global.invoice_type');
        $payment_status_all = config('global.payment_status');
        $invoice_status_all = config('global.invoice_status');

        $timezoneInvoiceList = $school->timezone;

        list($user_role, $invoice_type) = $this->getUserRoleInvoiceType($user, $school);
        if ($user->isTeacherSchoolAdmin()) {
            $invoice_type = ($type == 'school') ? 'S' : 'T';
        }
        $query = new Invoice;
        $allEvents = $query->getStudentInvoiceList($user,$schoolId,$user_role,$invoice_type,$timezoneInvoiceList);
        //dd($allEvents->toSql());
        //dd($allEvents);
        $allStudentEvents = [];
        foreach ($allEvents as $key => $value) {
            $profile_image = !empty($value->profile_image_id) ? AttachedFile::find($value->profile_image_id) : null;
            if (!empty($profile_image)) {
                $value->profile_image = $profile_image->path_name;
            }
            $value->student_full_name = "";
            if (!empty($value->person_id)) {
                $value->student_full_name = $value->student_name;
            } else {
                continue;
            }
            $allStudentEvents[] = $value;
        }
        return view('pages.invoices.student_list', compact('allStudentEvents', 'schoolId', 'invoice_type_all', 'payment_status_all', 'invoice_status_all','type'));
    }



    /**
     *  List of teacher invoices by school
     *
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-06-02
     */
    public function teacher_invoice_list(Request $request, $schoolId = null, $type = null)
    {
        $user = $request->user();
        // dd($type);
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $invoice_type_all = config('global.invoice_type');
        $payment_status_all = config('global.payment_status');
        $invoice_status_all = config('global.invoice_status');

        list($user_role, $invoice_type) = $this->getUserRoleInvoiceType($user, $school);
        $query = new Invoice;
        if ($user->isTeacherSchoolAdmin()) {
            $invoice_type = ($type == 'school') ? 'S' : 'T';
        }
        $allEvents = $query->getTeacherInvoiceList($user,$schoolId,$user_role,$invoice_type);


        $allTeacherEvents = [];
        foreach ($allEvents as $key => $value) {
            $profile_image = !empty($value->profile_image_id) ? AttachedFile::find($value->profile_image_id) : null;
            if (!empty($profile_image)) {
                $value->profile_image = $profile_image->path_name;
            }
            $value->teacher_full_name = "";
            if (!empty($value->person_id)) {
                $value->teacher_full_name = $value->teacher_name;
            }
            $allTeacherEvents[] = $value;
        }
        return view('pages.invoices.teacher_list', compact('allTeacherEvents', 'schoolId', 'invoice_type_all', 'payment_status_all', 'invoice_status_all', 'type'));
    }





    /**
     *  AJAX action to get student lessons
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-06-07
     */
    public function getStudentLessons(Request $request)
    {
        $user = $request->user();
        $result = array(
            'status' => false,
            'message' => __('failed to get lesson data'),
        );
        //$no_of_teachers = $school->max_teachers;
        try {
            $data = $request->all();
            $user = $request->user();
            $p_person_id = trim($data['p_person_id']);
            $p_school_id = trim($data['school_id']);
            $p_billing_period_start_date = trim($data['p_billing_period_start_date']);
            $p_billing_period_end_date = trim($data['p_billing_period_end_date']);
            $inv_type = trim($data['inv_type']);

            $school = School::active()->find($p_school_id);
            $timeZone = 'UTC';
            if (!empty($school->timezone)) {
                $timeZone = $school->timezone;
            }
            $p_billing_period_start_date = $this->formatDateTimeZone($p_billing_period_start_date, 'short', $timeZone,'UTC',1);
            $p_billing_period_end_date = $this->formatDateTimeZone($p_billing_period_end_date, 'short',$timeZone,'UTC',1);

            $p_pending_only=trim($data['p_pending_only']);

            list($user_role, $invoice_type) = $this->getUserRoleInvoiceType($user);
            if ($user->isTeacherSchoolAdmin()) {
                $invoice_type = ($inv_type == 'school') ? 'S' : 'T';
            }
// dd($inv_type);
            $query = new Invoice;
            $studentEvents = $query->getStudentEventLessonList($user,$p_person_id,$p_school_id,$user_role,$invoice_type,$p_billing_period_start_date,$p_billing_period_end_date);
            if (!empty($p_pending_only)) {
                $studentEvents->where(
                    function ($query) {
                        $query->where('event_details.is_sell_invoiced', '=', 0)
                              ->orWhereNull('event_details.is_sell_invoiced');
                    }
                )->orderBy('event_details.id', 'desc');
            }
            //dd($studentEvents->toSql());
            $dataFetched = $studentEvents->get();



            foreach ($dataFetched as $key => $value) {
	            $dateStart = $value->date_start = $this->formatDateTimeZone($value->date_start, 'long','UTC',$timeZone);
                $dateStart = Carbon::parse($dateStart);
                $value->week_no = (int) $dateStart->format('W');

                $old_date_timestamp = strtotime($value->date_start);
                $value->date_start = date('d/m/Y', $old_date_timestamp);
                $value->time_start = date('H:i', $old_date_timestamp);
            }

            $result = array(
                'status' => true,
                'message' => __('We got a list of invoice'),
                'data' => $dataFetched,
                //'no_of_teachers' =>$no_of_teachers
            );

            //$p_auto_id = trim($data['p_auto_id']);
            return response()->json($result);
        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
    }


    /**
     *  AJAX action to get student lessons
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-06-07
     */
    public function getTeacherLessons(Request $request)
    {
        $user = $request->user();
        $result = array(
            'status' => false,
            'message' => __('failed to get lesson data'),
        );
        //$no_of_teachers = $school->max_teachers;
        try {
            $data = $request->all();
            $p_person_id = trim($data['p_person_id']);
            $p_school_id = trim($data['school_id']);
            $p_billing_period_start_date = trim($data['p_billing_period_start_date']);
            $p_billing_period_end_date = trim($data['p_billing_period_end_date']);
            $inv_type = trim($data['inv_type']);

            $school = School::active()->find($p_school_id);
            $timeZone = 'UTC';
            if (!empty($school->timezone)) {
                $timeZone = $school->timezone;
            }

            $p_billing_period_start_date = $this->formatDateTimeZone($p_billing_period_start_date, 'short', $timeZone,'UTC',1);
            $p_billing_period_end_date = $this->formatDateTimeZone($p_billing_period_end_date, 'short',$timeZone,'UTC',1);


            list($user_role, $invoice_type) = $this->getUserRoleInvoiceType($user);
            if ($user->isTeacherSchoolAdmin()) {
                $invoice_type = ($inv_type == 'school') ? 'S' : 'T';
            }

            $query = new Invoice;
            $teacherEvents = $query->getTeacherEventLessonList($user,$p_person_id,$p_school_id,$user_role,$invoice_type,$p_billing_period_start_date,$p_billing_period_end_date);

            //dd($studentEvents->toSql());
            $dataFetched = $teacherEvents->get();
            //dd($data);
            foreach ($dataFetched as $key => $value) {
                $dateStart = $value->date_start = $this->formatDateTimeZone($value->date_start, 'long','UTC',$timeZone);
                $dateStart = Carbon::parse($dateStart);
                $value->week_no = (int) $dateStart->format('W');
                $old_date_timestamp = strtotime($value->date_start);
                $value->date_start = date('d/m/Y', $old_date_timestamp);
                $value->time_start = date('H:i', $old_date_timestamp);
            }

            $result = array(
                'status' => true,
                'message' => __('We got a list of invoice'),
                'data' => $dataFetched,
                //'no_of_teachers' =>$no_of_teachers
            );
            return response()->json($result);
        } catch (Exception $e) {
            echo $e->getMessage();
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
    }

    /**
     *  AJAX action to generate teacher invoice
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-09-27
     */
    public function generateTeacherInvoice(Request $request)
    {
        $user = $request->user();
        $result = array(
            'status' => false,
            'message' => __('failed to get lesson data'),
        );
        try {
            $data = $request->all();
            $p_teacher_id = $p_person_id = trim($data['p_person_id']);
            $schoolId = $p_school_id = trim($data['school_id']);
            $p_billing_period_start_date = trim($data['p_billing_period_start_date']);
            $p_billing_period_end_date = trim($data['p_billing_period_end_date']);
            $inv_type = trim($data['inv_type']);

            $school = School::active()->find($p_school_id);
            $timeZone = 'UTC';
            if (!empty($school->timezone)) {
                $timeZone = $school->timezone;
            }
            $p_billing_period_start_date = $this->formatDateTimeZone($p_billing_period_start_date, 'short', $timeZone,'UTC',1);
            $p_billing_period_end_date = $this->formatDateTimeZone($p_billing_period_end_date, 'short',$timeZone,'UTC',1);

            $dateS = $p_billing_period_start_date = date('Y-m-d', strtotime(str_replace('.', '-', $p_billing_period_start_date)));
            $dateEnd = $p_billing_period_end_date = date('Y-m-d', strtotime(str_replace('.', '-', $p_billing_period_end_date)));
            $p_event_ids=trim($data['p_event_ids']);

            $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return redirect()->route('schools')->with('error', __('School is not selected'));
            }

            list($user_role, $invoice_type) = $this->getUserRoleInvoiceType($user, $school);
            if ($user->isTeacherSchoolAdmin()) {
                $invoice_type = ($inv_type == 'school') ? 'S' : 'T';
            }
            $data['event_discount_description'] = "Discount";
            $dataMap = new InvoiceDataMapper();
            $invoiceData = $dataMap->setInvoiceData($data,$school,$invoice_type,$user,2);
            $invoiceData['invoice_header'] = 'From '.$invoiceData['period_starts'].' to '.$invoiceData['period_ends'].' - '.$invoiceData['seller_name'].' "'.$school->school_name.'" from '.$invoiceData['date_invoice'];
            $invoiceData['created_by'] = $user->id;

            $invoiceData = Invoice::create($invoiceData);

            $query = new Invoice;
            $teacherEvents = $query->generateTeacherEvent($user,$p_person_id,$schoolId,$user_role,$invoice_type,$p_billing_period_start_date,$p_billing_period_end_date,$p_event_ids);

            $dataFetched = $teacherEvents->get();
            $subtotal_amount_all = 0;
            $subtotal_amount_with_discount = 0;
            $subtotal_amount_no_discount = 0;
            $amount_discount_1 = 0;
            $amount_discount_2 = 0;
            $amount_discount_3 =0;
            $amount_discount_4 =0;
            $amount_discount_5 = 0;
            $amount_discount_6 =0;
            $total_amount_discount = 0;
            $total_amount_no_discount = 0;
            $total_amount_with_discount = 0;
            $total_amount = 0;
            $tax_desc = 0;
            $tax_perc =0;
            $tax_amount = 0;
            $etransfer_acc = 0;
            $cheque_payee = 0;
            $extra_expenses = 0;
            $total_amount_extra = 0;
            $teacher_fullname = '';
            $price_currency = '';


            foreach ($dataFetched as $key => $value) {

                try {
                    $disc1_amt = 0;
                    $invoiceItemData['total_item'] = $value->buy_total+$value->costs_1;



                    $invoiceItemData['invoice_id'] = $invoiceData->id;
                    $invoiceItemData['school_id'] = $schoolId;
                    $invoiceItemData['is_locked'] = 0;


                    $invoiceItemData['unit'] = $value->duration_minutes;
                    $invoiceItemData['unit_type'] = 'minutes';
                    $invoiceItemData['price'] = $value->buy_total+$value->costs_1;
                    $invoiceItemData['price_unit'] = $value->buy_total+$value->costs_1;
                    $price_currency = $invoiceItemData['price_currency'] = $value->price_currency;
                    $extra_expenses += $invoiceItemData['event_extra_expenses'] = 0;
                    $invoiceItemData['publication_mode'] = 'N,admin';
                    if ($value->event_type == 10) {
                        $invoiceItemData['item_type'] = 1;
                    }
                    else {
                        $invoiceItemData['item_type'] = 2;
                    }
                    $invoiceItemData['event_id'] = $value->event_id;
                    $invoiceItemData['event_detail_id'] = $value->detail_id;
                    $invoiceItemData['teacher_id'] = $p_person_id;
                    $invoiceItemData['student_id'] = $value->student_id;
                    $invoiceItemData['participation_id'] = 200;
                    $invoiceItemData['price_type_id'] = $value->event_price;
                    $invoiceItemData['is_locked'] = $value->is_locked;
                    $invoiceItemData['item_date'] = $value->date_start;
                    //if ($value->buy_total == 0) {
                        //$value->buy_total = $value->buy_price;
                    //}

                    $invoiceItemData['subtotal_amount_no_discount'] = 0;
                    // if ($value->event_type == 10) {
                    //     $invoiceItemData['subtotal_amount_with_discount'] = $invoiceItemData['total_item'];
                    // } else {
                    //     $invoiceItemData['subtotal_amount_no_discount'] = $invoiceItemData['total_item'];
                    // }
                    $invoiceItemData['subtotal_amount_with_discount'] =$invoiceItemData['total_item'];

                    if (!empty($data['p_discount_perc'])) {
                        $disc1_amt =(($invoiceItemData['total_item']*$data['p_discount_perc'])/100);
                    }
                    $invoiceItemData['subtotal_amount_no_discount'] = 0;

                    //$v_subtotal_amount_all = $invoiceItemData['total_item'];
                    $v_subtotal_amount_all = $invoiceItemData['subtotal_amount_with_discount'] + $invoiceItemData['subtotal_amount_no_discount'];
                    $amt_for_disc = 0;
                    $v_amount_discount_1 = 0;
                    $v_amount_discount_2 = 0;
                    $v_amount_discount_3 = 0;
                    $v_amount_discount_4 = 0;
                    $v_amount_discount_5 = 0;
                    $v_amount_discount_6 = 0;
                    $v_total_amount_discount = 0;
                    $v_total_amount = 0;
                    $tax_desc = $school->tax_desc;
                    $tax_perc = $school->tax_perc;

                    if ($invoiceData->invoice_type ==2) {

                        $v_amount_discount_1 = $disc1_amt;
                        $v_total_amount_discount = $v_amount_discount_1 + $v_amount_discount_2 +$v_amount_discount_3 +$v_amount_discount_4 +$v_amount_discount_5 +$v_amount_discount_6;
                        $v_total_amount_with_discount = $v_subtotal_amount_all - $v_total_amount_discount;
                        $v_total_amount_no_discount = $invoiceItemData['subtotal_amount_no_discount'];
                        $v_subtotal_amount_all = $v_total_amount_no_discount+$v_total_amount_with_discount;
                    }
                    $subtotal_amount_all += $v_subtotal_amount_all;
                    $subtotal_amount_with_discount += $invoiceItemData['subtotal_amount_with_discount'];
                    $subtotal_amount_no_discount += $invoiceItemData['subtotal_amount_no_discount'];
                    $amount_discount_1 += $v_amount_discount_1;
                    $amount_discount_2 += $v_amount_discount_2;
                    $amount_discount_3 += $v_amount_discount_3;
                    $amount_discount_4 += $v_amount_discount_4;
                    $amount_discount_5 += $v_amount_discount_5;
                    $amount_discount_6 += $v_amount_discount_6;
                    $total_amount_discount += $v_total_amount_discount;
                    $total_amount_no_discount += $v_total_amount_no_discount;
                    $total_amount_with_discount += $v_total_amount_with_discount;
                    $total_amount_extra += $v_subtotal_amount_all;


                    $teacher = Teacher::find($value->teacher_id);
                    $teacher_fullname = $teacher->firstname.' '.$teacher->lastname;
                    if ($value->event_type == 10) { // lesson
                        if ($value->invoiced_type == 'S') {
                            //$invoiceItemData['caption'] = 'test school invoice package with 2 student(s)';
                            $invoiceItemData['caption'] = $value->category_name.' with '.$value->count_name.' Student(s)';

                        } else{
                            $invoiceItemData['caption'] = 'Teacher ';
                            $invoiceItemData['caption'] .= ' ('.$value->category_name.','.$value->price_name.') , Number of Students'.$value->count_name;

                        }
                        if ($value->extra_charges>0) {
                            $invoiceItemData['caption'] .='<br>Extra charges '.$value->extra_charges*$value->count_name;
                        }
                    }
                    else if ($value->event_type == 100) { // event
                        if ($value->invoiced_type == 'S') {
                            //$invoiceItemData['caption'] = 'test school invoice package with 2 student(s)';
                            $invoiceItemData['caption'] = $value->title.' with '.$value->count_name.' Student(s)';

                        } else{
                            $invoiceItemData['caption'] = 'Event : '.$value->title;
                            $invoiceItemData['caption'] .= ' ('.$value->category_name.') , Number of Students'.$value->count_name;
                        }
                        if ($value->extra_charges>0) {
                            $invoiceItemData['caption'] .='<br>Extra charges '.$value->extra_charges*$value->count_name;
                        }
                    }


                    if (!empty($value->detail_id)) {
                        $query = new EventDetails;
                        $eventData = $query->updateEventDetail($value->detail_id,$invoiceData->id,'buy_invoice_id');
                    }


                    $invoiceItemDataI = InvoiceItem::create($invoiceItemData);
                } catch (Exception $e) {
                    echo $e->getMessage();

                }



            }
            //exit();
            try {
                $tax_amount=($total_amount *($tax_perc/100));

                $updateInvoiceCalculation = [
                'subtotal_amount_all' => $subtotal_amount_all,
                'subtotal_amount_with_discount'=> $subtotal_amount_with_discount,
                'subtotal_amount_no_discount'=> $subtotal_amount_no_discount,
                'subtotal_amount_no_discount'=> $subtotal_amount_no_discount,
                'amount_discount_1'=> $amount_discount_1,
                'amount_discount_2'=> $amount_discount_2,
                'amount_discount_3'=> $amount_discount_3,
                'amount_discount_4'=> $amount_discount_4 ,
                'amount_discount_5'=> $amount_discount_5,
                'amount_discount_6'=> $amount_discount_6,
                'total_amount_discount'=>$total_amount_discount,
                'total_amount_no_discount'=> $total_amount_no_discount,
                'total_amount_with_discount'=> $total_amount_with_discount,
                'total_amount'=> $total_amount_extra,
                'tax_desc'=> $tax_desc,
                'tax_perc'=> $tax_perc,
                'tax_amount'=> $tax_amount,
                'etransfer_acc'=>$school->etransfer_acc,
                'cheque_payee' =>$school->cheque_payee,
                'extra_expenses' => $extra_expenses

                ];
                if (!empty($data['p_discount_perc'])) {
                    $updateInvoiceCalculation['discount_percent_1'] =$data['p_discount_perc'];
                }

                if (!empty($price_currency)) {
                    $updateInvoiceCalculation['invoice_currency'] = $price_currency;
                }
                $updateInvoiceCalculation['invoice_header'] = 'From '.$invoiceData->period_starts.' to '.$invoiceData->period_ends.' - '.$teacher_fullname.' "'.$school->school_name.'" from '.$invoiceData->date_invoice;

                // print_r($updateInvoiceCalculation);
                // exit();


                $invoiceDataUpdate = Invoice::where('id', $invoiceData->id)->update($updateInvoiceCalculation);
            } catch (Exception $e) {
                echo $e->getMessage();

            }
            //dd($invoiceData);



            // $query="call generate_new_teacher_invoice_new('$p_lang_id','$p_app_id','$p_school_id','$p_invoice_id','$p_person_id','$p_billing_period_start_date','$p_billing_period_end_date','$p_discount_perc');";
            // //echo "<script>alert($query);</script>";exit;
            // $result = mysql_query($query)  or die( $return = 'Error:-3> ' . mysql_error());
            // while($row = mysql_fetch_assoc($result))
            // {
            //     $data[]=$row;
            // }
            // echo json_encode($data);

            $result = array(
                'status' => 'success',
                'message' => __('We got a list of invoice'),
                'data' => $invoiceData,
                'auto_id' => $invoiceData->id
            );
            return response()->json($result);
        } catch (Exception $e) {
            //return error message
            $result['message'] = $e->getMessage();
            return response()->json($result);
        }
    }

    /**
     *  AJAX action to generate student invoice
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-09-27
     */
    public function generateStudentInvoice(Request $request)
    {
        $user = $request->user();
        $result = array(
            'status' => false,
            'message' => __('failed to get lesson data'),
        );

        //echo Carbon::now()->format('F'); exit();
        try {
            $data = $request->all();

            $extra_1 = floatval(trim($data['extra_1']));
            $extra_1_description = trim($data['extra_1_description']);

            $extra_2 = floatval(trim($data['extra_2']));
            $extra_2_description = trim($data['extra_2_description']);

            //dd($data);
            $tax_ids = $data['selectedTaxIds'];
            $discountPercentage = floatval(trim($data['discountPercentage']));
            if ($discountPercentage > 100) {
                return false;
            }
            $discountPercentage2 = floatval(trim($data['discountPercentage2']));
            if($discountPercentage2 > 100) {
                return false;
            }
            $amountDiscount_2 = intval(trim($data['discountAmountage2']));
            $totalAllTaxesAmount = intval(trim($data['finaltotaltaxes']));
            $totalAmountGetInit = trim(trim($data['totalAmountGet']));
            $totalAmountGet = floatval($totalAmountGetInit);

            /*$result = array(
                'status' => "success",
                'truc' => $totalAmountGet,
                'message' => $data,
            );
            return response()->json($result);*/


            $p_person_id = trim($data['p_person_id']);
            $p_student_id = trim($data['p_person_id']);
            $schoolId = $p_school_id = trim($data['school_id']);
            $data['p_billing_period_start_date'] = trim($data['p_from_date']);
            $data['p_billing_period_end_date'] = trim($data['p_to_date']);
            $school = School::active()->find($p_school_id);
            $timeZone = 'UTC';
            if (!empty($school->timezone)) {
                $timeZone = $school->timezone;
            }
            $data['p_billing_period_start_date'] = $this->formatDateTimeZone($data['p_billing_period_start_date'], 'short', $timeZone,'UTC',1);
            $data['p_billing_period_end_date'] = $this->formatDateTimeZone($data['p_billing_period_end_date'], 'short',$timeZone,'UTC',1);

            $dateS = date('Y-m-d', strtotime(str_replace('.', '-', $data['p_billing_period_start_date'])));
            $dateEnd = date('Y-m-d', strtotime(str_replace('.', '-', $data['p_billing_period_end_date'])));

            $p_invoice_id=trim($data['p_invoice_id']);
            $p_event_ids=trim($data['p_event_ids']);
            $inv_type = trim($data['inv_type']);
            $data['p_discount_perc'] = number_format($discountPercentage, 2);
            $data['lesson_discount_description'] = trim($data['lesson_discount_description']);
            $data['event_discount_description'] = trim($data['event_discount_description']);


            $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return redirect()->route('schools')->with('error', __('School is not selected'));
            }
            list($user_role, $invoice_type) = $this->getUserRoleInvoiceType($user, $school);
            if ($user->isTeacherSchoolAdmin()) {
                $invoice_type = ($inv_type == 'school') ? 'S' : 'T';
            }

            $data['currency'] = $school->default_currency_code;


            $dataMap = new InvoiceDataMapper();
            $invoiceData = $dataMap->setInvoiceData($data,$school,$invoice_type,$user);
            $invoiceData['created_by'] = $user->id;

            $invoiceData = Invoice::create($invoiceData);
            //$invoiceDataGet = Invoice::active()->find($invoiceData->id);


            $query = new Invoice;
            $studentEvents = $query->generateStudentEvent($user,$p_person_id,$schoolId,$user_role,$invoice_type,$dateS,$dateEnd,$p_event_ids);

            //$studentEvents->groupBy('events.id');

            //dd($studentEvents->toSql());
            $data = $studentEvents->get();
            //print_r($data);
            $count = count($data);
            $subtotal_amount_all = 0;
            $subtotal_amount_with_discount = 0;
            $subtotal_amount_no_discount = 0;
            $amount_discount_1 = 0;
            $amount_discount_2 = 0;
            $amount_discount_3 =0;
            $amount_discount_4 =0;
            $amount_discount_5 = 0;
            $amount_discount_6 =0;
            $total_amount_discount = 0;
            $total_amount_no_discount = 0;
            $total_amount_with_discount = 0;
            $total_amount = 0;
            $tax_desc = 0;
            $tax_perc =0;
            $tax_amount = 0;
            $etransfer_acc = 0;
            $cheque_payee = 0;
            $extra_expenses = 0;
            $total_amount_extra = 0;
            $teacher_fullname = '';
            $price_currency = '';
            $totalsubwithdiscountwithoutextra=0;
            $totalExtras=0;

            foreach ($data as $key => $value) {
                try{
                    $month_name = 'January';


                    // $event->where('event_details.sell_invoice_id' = $v_invoice_id);
                    // update
                    // {
                    //     $studentEvents->where('event_details.is_sell_invoiced', '=', 0);
                    //     $studentEvents->whereNull('event_details.sell_invoice_id');
                    // }
                    $invoiceItemData['invoice_id'] = $invoiceData->id;
                    $invoiceItemData['school_id'] = $schoolId;
                    $invoiceItemData['is_locked'] = 0;


                    $invoiceItemData['unit'] = $value->duration_minutes;
                    $invoiceItemData['unit_type'] = 'minutes';
                    $invoiceItemData['price'] = $value->sell_price+$value->costs_1;
                    $invoiceItemData['price_unit'] = $value->sell_price+$value->costs_1;
                    $price_currency = $invoiceItemData['price_currency'] = $value->price_currency;
                    $extra_expenses += $invoiceItemData['event_extra_expenses'] = 0;
                    $invoiceItemData['publication_mode'] = 'N,admin';
                    if ($value->event_type == 10) {
                        $invoiceItemData['item_type'] = 1;
                    }
                    else {
                        $invoiceItemData['item_type'] = 2;
                    }
                    $invoiceItemData['event_id'] = $value->event_id;
                    $invoiceItemData['teacher_id'] = $value->teacher_id;
                    $invoiceItemData['student_id'] = $p_person_id;
                    $invoiceItemData['participation_id'] = $value->participation_id;
                    $invoiceItemData['price_type_id'] = $value->event_price;
                    $invoiceItemData['is_locked'] = $value->is_locked;
                    $invoiceItemData['item_date'] = $value->date_start;
                    if ($value->sell_total == 0) {
                        $value->sell_total = $value->sell_price;
                    }
                    $invoiceItemData['total_item'] = $value->sell_total+$value->costs_1;

                    $totalsubwithdiscountwithoutextra = $totalsubwithdiscountwithoutextra + $value->sell_total+$value->costs_1;
                    $totalExtras = $totalExtras + $value->costs_1;

                    //$invoiceItemData['subtotal_amount_with_discount'] = $invoiceItemData['total_item'];
                    //$invoiceItemData['subtotal_amount_with_discount'] =0;
                    //$invoiceItemData['subtotal_amount_no_discount'] = $invoiceItemData['total_item'];
                    $invoiceItemData['subtotal_amount_with_discount'] = $invoiceItemData['total_item'] - (($invoiceItemData['total_item'] * $discountPercentage) / 100);
                    $invoiceItemData['subtotal_amount_no_discount'] = $invoiceItemData['total_item'];


                    $v_subtotal_amount_all = $invoiceItemData['total_item'];



                    $amt_for_disc = 0;
                    $v_amount_discount_1 = 0;
                    $v_amount_discount_2 = 0;
                    $v_amount_discount_3 = 0;
                    $v_amount_discount_4 = 0;
                    $v_amount_discount_5 = 0;
                    $v_amount_discount_6 = 0;
                    $v_total_amount_discount = 0;
                    $v_total_amount = 0;
                    $tax_desc = $school->tax_desc;
                    $tax_perc = $school->tax_perc;
                    if ($invoiceData->invoice_type ==1) {
                        $tax_desc = '';
                        $tax_perc = 0;
                        $v_total_amount_discount = $v_amount_discount_1 + $v_amount_discount_2 +$v_amount_discount_3 +$v_amount_discount_4 +$v_amount_discount_5 +$v_amount_discount_6;
                        $v_total_amount_no_discount = $invoiceItemData['subtotal_amount_no_discount'];
                        $v_total_amount_with_discount = $invoiceItemData['subtotal_amount_with_discount'];
                        $v_total_amount = $invoiceItemData['total_item'] + $v_total_amount_no_discount+$v_total_amount_with_discount;
                    }

                    $subtotal_amount_all += $v_subtotal_amount_all;
                    $subtotal_amount_with_discount += $invoiceItemData['subtotal_amount_with_discount'];
                    $subtotal_amount_no_discount += $invoiceItemData['subtotal_amount_no_discount'];
                    $amount_discount_1 += $v_amount_discount_1;
                    $amount_discount_2 += $v_amount_discount_2;
                    $amount_discount_3 += $v_amount_discount_3;
                    $amount_discount_4 += $v_amount_discount_4;
                    $amount_discount_5 += $v_amount_discount_5;
                    $amount_discount_6 += $v_amount_discount_6;
                    $total_amount_discount += $v_total_amount_discount;
                    $total_amount_no_discount += $v_total_amount_no_discount;
                    $total_amount_with_discount += $v_total_amount_with_discount;
                    //$total_amount += $v_total_amount;
                    $total_amount_extra += $v_subtotal_amount_all;
                    $student = Student::find($value->student_id);
                    $student_fullname = $student->firstname.' '.$student->lastname;
                    if ($value->event_type == 10) { //lesson

                        $invoiceItemData['caption'] = $student->firstname.' '.$student->lastname;
                        $invoiceItemData['caption'] .= ' ('.$value->category_name.','.$value->price_name.')';
                        if ($value->extra_charges>0) {
                            $invoiceItemData['caption'] .='<br>Extra charges '.$value->extra_charges;
                        }
                    }
                    else if ($value->event_type == 100) { // event
                        $invoiceItemData['caption'] = 'Event : '.$value->price_name.''.$value->title;
                        if ($value->extra_charges>0) {
                            $invoiceItemData['caption'] .='<br>Extra charges '.$value->extra_charges;
                        }
                    }

                    if (!empty($value->detail_id)) {
                        $query = new EventDetails;
                        $eventData = $query->updateEventDetail($value->detail_id,$invoiceData->id,'sell_invoice_id',$value->student_id);
                    }




                    $invoiceItemDataI = InvoiceItem::create($invoiceItemData);

                } catch (Exception $e) {
                    echo $e->getMessage();
                    $result['status'] = false;
                    $result['message'] = $e->getMessage();
                    return response()->json($result);
                }

            }


            $total_tax_perc = 0;
            $total_tax_amount = 0;
            $detail_id =  explode(',',$tax_ids);
            $mytaxes = InvoicesTaxes::whereIn('id', $detail_id)->get();
            $tructotal = number_format($totalsubwithdiscountwithoutextra * $discountPercentage /100, 2);
            $totaltruc = number_format(floatval($totalsubwithdiscountwithoutextra) - floatval($tructotal), 2);
            if (!empty($mytaxes)) {
                foreach ($mytaxes as $tax) {
                    $taxData = [
                        'invoice_id' => $invoiceItemData['invoice_id'],
                        'tax_name' => $tax->tax_name,
                        'tax_percentage' => $tax->tax_percentage,
                        'tax_number' => $tax->tax_number,
                        'tax_amount' => number_format(floatval($totaltruc) * floatval($tax['tax_percentage']) / 100, 2),
                    ];

                    $total_tax_perc += $tax->tax_percentage;
                    $total_tax_amount += floatval($v_subtotal_amount_all) * floatval($tax['tax_percentage']) / 100;
                    $InvoiceTax = InvoicesTaxes::create($taxData);
                }

            }

            $tax_amount=($total_amount *($tax_perc/100));

            $updateInvoiceCalculation = [
               'discount_percent_1' => $discountPercentage,
               'discount_percent_2' => number_format($discountPercentage2, 2),
               'amount_discount_1' => number_format(($total_amount_extra*$discountPercentage)/100,2),
               'subtotal_amount_all' => $subtotal_amount_all,
               'subtotal_amount_with_discount'=> $subtotal_amount_with_discount,
               'subtotal_amount_no_discount'=> $subtotal_amount_no_discount,
               'subtotal_amount_no_discount'=> $subtotal_amount_no_discount,
               'amount_discount_2'=> $amountDiscount_2,
               'amount_discount_3'=> $amount_discount_3,
               'amount_discount_4'=> $amount_discount_4,
               'amount_discount_5'=> $amount_discount_5,
               'amount_discount_6'=> $amount_discount_6,
               'total_amount_discount'=>$total_amount_discount,
               'total_amount_no_discount'=> $total_amount_no_discount,
               'total_amount_with_discount'=> $total_amount_with_discount,
               'total_amount'=> $totalAmountGet, //number_format($total_amount_with_discount+$totalAllTaxesAmount,2),
               'tax_desc'=> $tax_desc,
               'tax_perc'=> $tax_perc,
               'tax_amount'=> number_format($totalAllTaxesAmount,2),
               'etransfer_acc'=>$school->etransfer_acc,
               'cheque_payee' =>$school->cheque_payee,
               'extra_expenses' => $invoiceData->invoice_type == 1 ? $totalExtras : $extra_expenses,
               'extra_1' => $extra_1,
               'extra_1_description' => $extra_1_description,
               'extra_2' => $extra_2,
               'extra_2_description' => $extra_2_description

            ];
            if (!empty($price_currency)) {
                $updateInvoiceCalculation['invoice_currency'] = $price_currency;
            }
            $updateInvoiceCalculation['invoice_header'] = 'From '.$invoiceData->period_starts.' to '.$invoiceData->period_ends.' - '.$teacher_fullname.' "'.$school->school_name.'" from '.$invoiceData->date_invoice;

            // print_r($invoiceData->id);
             // update invoice tax info

             $updateInvoiceCalculation['tax_perc'] = $total_tax_perc;
             $updateInvoiceCalculation['tax_amount'] = number_format($totalAllTaxesAmount,2);

            $invoiceDataUpdate = Invoice::where('id', $invoiceData->id)->update($updateInvoiceCalculation);

            $result = array(
                'status' => 'success',
                'message' => __('We got a list of invoice'),
                'data' => $invoiceData,
                'auto_id' =>$invoiceData->id
            );
            return response()->json($result);
        } catch (Exception $e) {
            echo $e->getMessage();
            //return error message
            $result['status'] = false;
            $result['message'] = $e->getMessage();
            $result['line'] = $e->getLine();
            return response()->json($result);
        }
    }

    public function view(Request $request, Invoice $invoice)
    {
        $user = Auth::user();
        //$invoiceId = $request->route('invoice');


        $invoice_type_all = config('global.invoice_type');
        $payment_status_all = config('global.payment_status');
        $invoice_status_all = config('global.invoice_status');
        $provinces = Province::active()->get()->toArray();
        $invoice->invoice_type_name = $invoice_type_all[$invoice->invoice_type];
        $invoice->invoice_status_name = $invoice_status_all[$invoice->invoice_status];


        if ($invoice->invoice_type == 1) {
            $invoice->person_id = $invoice->client_id;
        } else {
            $invoice->person_id = $invoice->seller_id;
        }

        // $invoiceCurrency = InvoiceItem::active()->where('invoice_id',$invoice->id)->get()->pluck('price_currency')->join(',');
        $invoice->invoice_items = InvoiceItem::active()->where('invoice_id', $invoice->id)->get();
        // $result_data->invoice_price = $invoiceCurrency.''.round($result_data->total_amount,2);

        // if ($invoice->amount_discount_1  > 0) {
        //     $invoice->disc_text = '1, disc1, disc1_amt, 0';
        //     # code...
        // }
        $genders = config('global.gender');
        $countries = Country::active()->get();
        return view('pages.invoices.add', [
            'title' => 'Invoice',
            'pageInfo' => ['siteTitle' => '']
        ])->with(compact('genders', 'countries', 'provinces'));
    }

    /**
     *  AJAX action to send email for pay reminder
     *
     * @return json
     * @author Tarikul Islm
     * @version 0.1 written in 2022-10-15
     */

    public function modificationInvoice(Request $request, $schoolId = null, $invoice = null)
    {
        $user = $request->user();
        $this->schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
        $school = School::active()->find($this->schoolId);
        if (empty($school)) {
            if($user->isSuperAdmin()) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
            } else {
                return back()->with('error', 'School is not selected. Please reconnect to your account.');
            }
        }
        $timeZone = 'UTC';
        if (!empty($school->timezone)) {
            $timeZone = $school->timezone;
        }
        $invoice = Invoice::active()->find($invoice);
        if (empty($invoice)) {
            //return redirect()->route('schools')->with('error', __('School is not selected'));
            return back()->with('error', 'This invoice has been deleted');
        }
        //dd($invoice);
        $invoice_type_all = config('global.invoice_type');
        $payment_status_all = config('global.payment_status');
        $invoice_status_all = config('global.invoice_status');
        $provinces = Province::active()->get()->toArray();
        $invoice->invoice_type_name = $invoice_type_all[$invoice->invoice_type];
        $invoice->invoice_status_name = $invoice_status_all[$invoice->invoice_status];


        if ($invoice->invoice_type == 1) {
            $invoice->person_id = $invoice->client_id;
        } else {
            $invoice->person_id = $invoice->seller_id;
        }

        // $invoiceCurrency = InvoiceItem::active()->where('invoice_id',$invoice->id)->get()->pluck('price_currency')->join(',');
        $invoice_items = DB::table('invoice_items')
        ->leftJoin('events', 'events.id', '=', 'invoice_items.event_id')
        ->where('invoice_items.invoice_id', $invoice->id)
        //->where('invoice_items.invoice_id', $invoice->id)
        ->orderBy('events.event_type','ASC')
        ->orderBy('invoice_items.item_date','ASC')->get();

        $items = array();
        $invoice_items->each(function($d) use(&$items){
            if(!isset($items[$d->event_type])){
                $items[$d->event_type] = array();
            }
            $items[$d->event_type][] = $d;
        });

        $invoice->invoice_items = $items;
        // $result_data->invoice_price = $invoiceCurrency.''.round($result_data->total_amount,2);

        // if ($invoice->amount_discount_1  > 0) {
        //     $invoice->disc_text = '1, disc1, disc1_amt, 0';
        //     # code...
        // }
        //dd($invoice->invoice_items);

        //$RegisterTaxData = InvoicesTaxes::active()->where(['invoice_id'=> null, 'created_by' => $user->id])->get();
        $RegisterTaxData = InvoicesTaxes::active()->where(['invoice_id'=> $invoice->id])->get();

        $genders = config('global.gender');
        $countries = Country::active()->get();
        return view('pages.invoices.invoice_modification', [
            'title' => 'Invoice',
            'pageInfo' => ['siteTitle' => '']
        ])->with(compact('timeZone','genders','invoice_status_all', 'invoice_type_all','countries', 'provinces','invoice', 'RegisterTaxData'));
    }

    /**
     *  AJAX action to send email for pay reminder
     *
     * @return json
     * @author Tarikul 90
     * @version 0.1 written in 2022-05-27
     */

    public function manualInvoice(Request $request, $schoolId = null)
    {
        $invoiceId = !empty($_GET['auto_id']) ? $_GET['auto_id'] : '';
        $user = $request->user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $genders = config('global.gender');
        $provinces = Province::active()->get()->toArray();
        $countries = Country::active()->get();
        $currency = Currency::active()->get();
        $school_currency = $school->default_currency_code;
        $timezone = $school->timezone;
        $europeanTimezones = DateTimeZone::listIdentifiers(DateTimeZone::EUROPE);
        $isInEurope = in_array($timezone, $europeanTimezones);

        $RegisterTaxData = InvoicesTaxes::active()->where(['invoice_id'=> null, 'created_by' => $user->id])->get();

        $teachers = SchoolTeacher::active()->onlyTeacher()->where('school_id',$schoolId)->get();
        $students = DB::table('school_student')
                    ->join('students','school_student.student_id','=','students.id')
                    ->where(['school_id' => $schoolId, 'school_student.is_active' => 1])
                    ->get();

        return view('pages.invoices.manual_invoice', [
            'title' => 'Invoice',
            'pageInfo' => ['siteTitle' => '']
        ])->with(compact('genders','school','schoolId','countries', 'provinces','students','teachers','currency','school_currency', 'RegisterTaxData', 'isInEurope'));
    }

    /**
     *  AJAX action to send email for pay reminder
     *
     * @return json
     * @author Tarikul 90
     * @version 0.1 written in 2022-05-27
     */

    public function updatemanualInvoice(Request $request, $schoolId = null, $invoiceId = null)
    {
        $user = $request->user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $genders = config('global.gender');
        $provinces = Province::active()->get()->toArray();
        $countries = Country::active()->get();
        $currency = Currency::active()->get();
        $teachers = SchoolTeacher::active()->onlyTeacher()->where('school_id',$schoolId)->get();
        $students = DB::table('school_student')
                    ->join('students','school_student.student_id','=','students.id')
                    ->where(['school_id' => $schoolId, 'school_student.is_active' => 1])
                    ->get();

        $timezone = $school->timezone;
        $europeanTimezones = DateTimeZone::listIdentifiers(DateTimeZone::EUROPE);
        $isInEurope = in_array($timezone, $europeanTimezones);

        $invoiceData = Invoice::active()->where(['id'=>$invoiceId])->first();
        $InvoicesTaxData = InvoicesTaxes::active()->where(['invoice_id'=>$invoiceId])->get()->toArray();
        $InvoicesExpData = InvoicesExpenses::active()->where(['invoice_id'=>$invoiceId])->get()->toArray();
        $InvoicesItemData = InvoiceItem::active()->where(['invoice_id'=>$invoiceId])->get()->toArray();

        $RegisterTaxData = InvoicesTaxes::active()->where(['invoice_id'=> null, 'created_by' => $user->id])->get();

        return view('pages.invoices.update_manual_invoice', [
            'title' => 'Invoice',
            'pageInfo' => ['siteTitle' => '']
        ])->with(compact('genders','currency','schoolId','school','countries', 'provinces','students','teachers','invoiceData','InvoicesTaxData','InvoicesExpData','InvoicesItemData', 'RegisterTaxData', 'isInEurope'));
    }

    /**
     *  AJAX action to send email for pay reminder
     *
     * @return json
     * @author Tarikul 90
     * @version 0.1 written in 2022-05-27
     */

    public function invoiceData(Request $request)
    {
        $user = $request->user();
        $dataParam = $request->all();

        $id= trim($dataParam['p_code']);
        $type= trim($dataParam['p_type']);

        if($type == 'student'){
            $userData = DB::table('students')
                    ->where(['id' => $id, 'is_active' => 1])
                    ->get();
        }elseif($type == 'teacher'){
            $userData = DB::table('teachers')
                    ->where(['id' => $id, 'is_active' => 1])
                    ->get();
        }elseif($type == 'school'){
            $userData = DB::table('schools')
                    ->where(['id' => $id, 'is_active' => 1])
                    ->get();
        }
        return response()->json($userData);
    }



    /**
     *  AJAX action to send email for pay reminder
     *
     * @return json
     * @author Tarikul 90
     * @version 0.1 written in 2022-05-27
     */

    public function invoiceDataSave(Request $request, $schoolId = null)
    {

        DB::beginTransaction();
        try{
            $user = Auth::user();
            $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return redirect()->route('schools')->with('error', __('School is not selected'));
            }

            $dataParam = $request->all();

            $school = School::active()->find($schoolId);
            $timeZone = 'UTC';
            if (!empty($school->timezone)) {
                $timeZone = $school->timezone;
            }
            $dataParam['p_date_invoice'] = $this->formatDateTimeZone($dataParam['p_date_invoice'], 'long', $timeZone,'UTC',);


            $data = [
                'school_id' => $schoolId,
                'invoice_type' => $dataParam['p_invoice_type'],
                'invoice_name' => $dataParam['p_invoice_name'],
                'date_invoice' => date("Y-m-d H:i:s", strtotime($dataParam['p_date_invoice'])),
                'period_starts' => date("Y-m-d H:i:s", strtotime($dataParam['p_date_invoice'])),
                'period_ends' => date("Y-m-d H:i:s", strtotime($dataParam['p_date_invoice'])),
                'client_id' => $dataParam['p_client_id'],
                'client_name' => $dataParam['p_client_name'],
                'client_firstname' => isset($dataParam['p_client_firstname']) ? $dataParam['p_client_firstname'] : null,
                'client_lastname' => isset($dataParam['p_client_lastname']) ? $dataParam['p_client_lastname'] : null,
                'client_street_number' => $dataParam['p_client_street_number'],
                'client_street' => $dataParam['p_client_street'],
                'client_street2' => $dataParam['p_client_street2'],
                'client_country_code' => $dataParam['p_client_country_id'],
                'client_zip_code' => $dataParam['p_client_zip_code'],
                'client_place' => $dataParam['p_client_place'],
                'seller_id' => $dataParam['p_seller_id'],
                'seller_name' => $dataParam['p_seller_name'],
                'seller_firstname' => $dataParam['p_seller_firstname'],
                'seller_lastname' => $dataParam['p_seller_lastname'],
                'seller_street_number' => $dataParam['p_seller_street_number'],
                'seller_street' => $dataParam['p_seller_street'],
                'seller_street2' => $dataParam['p_seller_street2'],
                'seller_country_code' => $dataParam['p_seller_country_id'],
                'seller_zip_code' => $dataParam['p_seller_zip_code'],
                'seller_phone' => $dataParam['p_seller_phone'],
                'seller_mobile' => $dataParam['p_seller_mobile'],
                'seller_place' => $dataParam['p_seller_place'],
                'seller_email' => $dataParam['p_seller_email'],
                'payment_bank_account_name' => $dataParam['p_payment_bank_account_name'],
                'payment_bank_name' => $dataParam['p_payment_bank_name'],
                'payment_bank_address' => $dataParam['p_payment_bank_address'],
                'payment_bank_country_code' => $dataParam['p_payment_bank_country_id'],
                'payment_bank_zipcode' => $dataParam['p_payment_bank_zipcode'],
                'payment_bank_place' => $dataParam['p_payment_bank_place'],
                'payment_bank_iban' => $dataParam['p_payment_bank_iban'],
                'payment_bank_account' => $dataParam['p_payment_bank_account'],
                'payment_bank_swift' => $dataParam['p_payment_bank_swift'],
                'invoice_currency' => $dataParam['p_price_currency'],
                'client_province_id' => isset($dataParam['p_client_province_id']) ? $dataParam['p_client_province_id'] : null,
                'seller_province_id' => isset($dataParam['p_seller_province_id']) ? $dataParam['p_seller_province_id'] : null,
                'bank_province_id' => isset($dataParam['p_bank_province_id']) ? $dataParam['p_bank_province_id'] : null,
                'total_amount' => $dataParam['p_total_amount'],
                'e_transfer_email' => $dataParam['p_e_transfer_email'],
                'payment_phone' => $dataParam['p_payment_phone'],
                'invoice_creation_type' => 'Y'
            ];

            if($dataParam['p_invoice_type'] == 0) {
                $data['invoice_status'] = 1;
            }

            $Invoice = Invoice::create($data);
            $total_tax_perc = 0;
            $total_tax_amount = 0;
            if (!empty($dataParam['tax_name'])) {
                for($i=0; $i < count($dataParam['tax_name']);$i++){
                    $taxData = [
                        'invoice_id'   => $Invoice->id,
                        'tax_name' => $dataParam['tax_name'][$i],
                        'tax_percentage' => $dataParam['tax_percentage'][$i],
                        'tax_number' => $dataParam['tax_number'][$i],
                        'tax_amount' => $dataParam['tax_amount'][$i],
                    ];
                    $total_tax_perc +=$dataParam['tax_percentage'][$i];
                    $total_tax_amount +=$dataParam['tax_amount'][$i];
                    $InvoiceTax = InvoicesTaxes::create($taxData);
                }
            }


            $total_expense_amount = 0;
            if (!empty($dataParam['expense_name'])) {
                for($i=0; $i < count($dataParam['expense_name']);$i++){
                    $expenseData = [
                        'invoice_id'   => $Invoice->id,
                        'expense_name' => $dataParam['expense_name'][$i],
                        'expense_amount' => $dataParam['expense_amount'][$i]
                    ];
                    $total_expense_amount += $dataParam['expense_amount'][$i];
                    $InvoiceExpense = InvoicesExpenses::create($expenseData);
                }
            }

            if (!empty($dataParam['item_total'])) {
                for($i=0; $i < count($dataParam['item_total']); $i++){
                    $dataParam['item_date'][$i] = $this->formatDateTimeZone($dataParam['item_date'][$i], 'long', $timeZone,'UTC',);

                    $itemData = [
                        'invoice_id'   => $Invoice->id,
                        'school_id' => $schoolId,
                        'caption' => $dataParam['item_caption'][$i],
                        'total_item' => $dataParam['item_total'][$i],
                        'price_unit' => $dataParam['item_total'][$i],
                        'price' => $dataParam['item_total'][$i],
                        'item_date' => date("Y-m-d H:i:s", strtotime($dataParam['item_date'][$i])),
                        'price_currency' => $dataParam['p_price_currency']
                    ];
                    $InvoiceItem = InvoiceItem::create($itemData);
                }
            }

            // update invoice tax info
            $taxData = [
                'tax_perc' => $total_tax_perc,
                'tax_amount' => $total_tax_amount,
                'extra_expenses'=> $total_expense_amount,
            ];
            Invoice::where('id', $Invoice->id)->update($taxData);

            DB::commit();

            return [
                'id' => $Invoice->id,
                'status' => 1,
                'schoolId' => $schoolId,
                'message' =>  __('Successfully Registered')
            ];
        }catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->all())->with('error', __('Internal server error'));
        }
    }

    /**
     *  AJAX action to send email for pay reminder
     *
     * @return json
     * @author Tarikul 90
     * @version 0.1 written in 2022-05-27
     */

    public function invoiceDataUpdate(Request $request, $schoolId = null)
    {
        DB::beginTransaction();
        try{
            $user = Auth::user();
            $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return redirect()->route('schools')->with('error', __('School is not selected'));
            }

            $dataParam = $request->all();
            $id = $dataParam['p_auto_id'];
            $school = School::active()->find($schoolId);
            $timeZone = 'UTC';
            if (!empty($school->timezone)) {
                $timeZone = $school->timezone;
            }
            $dataParam['p_date_invoice'] = $this->formatDateTimeZone($dataParam['p_date_invoice'], 'long', $timeZone,'UTC',);


            $data = [
                'school_id' => $schoolId,
                'invoice_type' => $dataParam['p_invoice_type'],
                'invoice_name' => $dataParam['p_invoice_name'],
                'date_invoice' => date("Y-m-d H:i:s", strtotime($dataParam['p_date_invoice'])),
                'date_due' => date("Y-m-d H:i:s", strtotime($dataParam['p_date_due'])),
                'period_starts' => date("Y-m-d H:i:s", strtotime($dataParam['p_date_invoice'])),
                'period_ends' => date("Y-m-d H:i:s", strtotime($dataParam['p_date_invoice'])),
                'client_id' => $dataParam['p_client_id'],
                'client_name' => $dataParam['p_client_name'],
                'client_firstname' => isset($dataParam['p_client_firstname']) ? $dataParam['p_client_firstname'] : null,
                'client_lastname' => isset($dataParam['p_client_lastname']) ? $dataParam['p_client_lastname'] : null,
                'client_street_number' => $dataParam['p_client_street_number'],
                'client_street' => $dataParam['p_client_street'],
                'client_street2' => $dataParam['p_client_street2'],
                'client_country_code' => $dataParam['p_client_country_id'],
                'client_zip_code' => $dataParam['p_client_zip_code'],
                'client_place' => $dataParam['p_client_place'],
                'seller_id' => $dataParam['p_seller_id'],
                'seller_name' => $dataParam['p_seller_name'],
                'seller_firstname' => $dataParam['p_seller_firstname'],
                'seller_lastname' => $dataParam['p_seller_lastname'],
                'seller_street_number' => $dataParam['p_seller_street_number'],
                'seller_street' => $dataParam['p_seller_street'],
                'seller_street2' => $dataParam['p_seller_street2'],
                'seller_country_code' => $dataParam['p_seller_country_id'],
                'seller_zip_code' => $dataParam['p_seller_zip_code'],
                'seller_phone' => $dataParam['p_seller_phone'],
                'seller_mobile' => $dataParam['p_seller_mobile'],
                'seller_place' => $dataParam['p_seller_place'],
                'seller_email' => $dataParam['p_seller_email'],
                'payment_bank_account_name' => $dataParam['p_payment_bank_account_name'],
                'payment_bank_name' => $dataParam['p_payment_bank_name'],
                'payment_bank_address' => $dataParam['p_payment_bank_address'],
                'payment_bank_country_code' => $dataParam['p_payment_bank_country_id'],
                'payment_bank_zipcode' => $dataParam['p_payment_bank_zipcode'],
                'payment_bank_place' => $dataParam['p_payment_bank_place'],
                'payment_bank_iban' => $dataParam['p_payment_bank_iban'],
                'payment_bank_account' => $dataParam['p_payment_bank_account'],
                'payment_bank_swift' => $dataParam['p_payment_bank_swift'],
                'invoice_currency' => $dataParam['p_price_currency'],
                'client_province_id' => isset($dataParam['p_client_province_id']) ? $dataParam['p_client_province_id'] : null,
                'seller_province_id' => isset($dataParam['p_seller_province_id']) ? $dataParam['p_seller_province_id'] : null,
                'bank_province_id' => isset($dataParam['p_bank_province_id']) ? $dataParam['p_bank_province_id'] : null,
                'total_amount' => $dataParam['p_total_amount'],
                'e_transfer_email' => $dataParam['p_e_transfer_email'],
                'payment_phone' => $dataParam['p_payment_phone'],
                'invoice_creation_type' => 'Y'
            ];

            $Invoice = Invoice::where('id', $id)->update($data);
            InvoicesTaxes::where('invoice_id',$id)->forceDelete();
            InvoicesExpenses::where('invoice_id',$id)->forceDelete();
            InvoiceItem::where('invoice_id',$id)->forceDelete();

            $total_tax_perc = 0;
            $total_tax_amount = 0;
            if (!empty($dataParam['tax_name'])) {
                for($i=0; $i < count($dataParam['tax_name']);$i++){
                    $taxData = [
                        'invoice_id'   => $id,
                        'tax_name' => $dataParam['tax_name'][$i],
                        'tax_percentage' => $dataParam['tax_percentage'][$i],
                        'tax_number' => $dataParam['tax_number'][$i],
                        'tax_amount' => $dataParam['tax_amount'][$i],
                    ];
                    $total_tax_perc += $dataParam['tax_percentage'][$i];
                    $total_tax_amount += $dataParam['tax_amount'][$i];
                    $InvoiceTax = InvoicesTaxes::create($taxData);
                }
            }

            $total_expense_amount = 0;
            if (!empty($dataParam['expense_name'])) {
                for($i=0; $i < count($dataParam['expense_name']);$i++){
                    $expenseData = [
                        'invoice_id'   => $id,
                        'expense_name' => $dataParam['expense_name'][$i],
                        'expense_amount' => $dataParam['expense_amount'][$i]
                    ];
                    $total_expense_amount += $dataParam['expense_amount'][$i];
                    $InvoiceExpense = InvoicesExpenses::create($expenseData);
                }
            }

            if (!empty($dataParam['item_total'])) {
                for($i=0; $i < count($dataParam['item_total']); $i++){
                    $dataParam['item_date'][$i] = $this->formatDateTimeZone($dataParam['item_date'][$i], 'long', $timeZone,'UTC',);

                    $itemData = [
                        'invoice_id' => $id,
                        'school_id' => $schoolId,
                        'caption' => $dataParam['item_caption'][$i],
                        'total_item' => $dataParam['item_total'][$i],
                        'price_unit' => $dataParam['item_total'][$i],
                        'price' => $dataParam['item_total'][$i],
                        'item_date' => date("Y-m-d H:i:s", strtotime($dataParam['item_date'][$i])),
                        'price_currency' => $dataParam['p_price_currency']
                    ];
                    $InvoiceItem = InvoiceItem::create($itemData);
                }
            }

            $taxData = [
                'tax_perc' => $total_tax_perc,
                'tax_amount' => $total_tax_amount,
                'extra_expenses' => $total_expense_amount
            ];

            Invoice::where('id', $id)->update($taxData);

            DB::commit();

            return [
                'id' => $id,
                'status' => 1,
                'message' =>  __('Successfully Registered')
            ];
        }catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->all())->with('error', __('Internal server error'));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateInvoicePDF(Request $request, $type = 'stream')
    {
        try{
            $reqData = $request->all();
            $type = $request->type ? $request->type : $type;
            $invoice_id = $reqData['invoice_id'];
            $invoice_data = Invoice::with(['school' => function ($q) {
                                $q->select('id','tax_number');
                            }])->where('id', $invoice_id)->first();

            $invoice_items = DB::table('invoice_items')
                            ->leftJoin('events', 'events.id', '=', 'invoice_items.event_id')
                            ->where('invoice_items.invoice_id', $invoice_id)
                            ->orderBy('events.event_type','ASC')
                            ->orderBy('invoice_items.item_date','ASC')->get();
            $items = [];
            foreach($invoice_items as $key=>$d){
                if(!isset($items[$d->event_type])){
                    $items[$d->event_type] = array();
                }
                $items[$d->event_type][] = $d;
            }

            $userIS = User::where('person_id', $invoice_data->seller_id)->first();
            //Add user photo as Logo to Invoice
            if (!empty($userIS->profileImage->path_name)) {
                $path_name =  $userIS->profileImage->path_name;
                $file = str_replace(URL::to('').'/uploads/','',$path_name);
                $invoice_data['logo'] = 'uploads/'.$file;
                //$logo_url = AttachedFile::where('created_by', $userIS->id)->latest()->first();
                //$invoice_data['logo'] = $logo_url->path_name;
            } else {
                $invoice_data['logo'] = null;
            }

            //dd($invoice_data['logo_url']);

            $InvoicesTaxData = InvoicesTaxes::active()->where(['invoice_id'=> $invoice_data->id])->get();
            $InvoicesExpData = InvoicesExpenses::active()->where(['invoice_id'=> $invoice_data->id])->get();

            $schoolId = $invoice_data->school_id;
            $school = School::active()->find($schoolId);

            $invoice_items = $items;
            $date_from = strtolower(date('F.Y', strtotime($invoice_data->date_invoice)));
            $invoice_name = 'invoice-'.$invoice_data->id.'-'.strtolower($invoice_data->client_firstname).'.'.strtolower($invoice_data->client_lastname).'.'.$date_from.'.pdf';
            $pdf = PDF::loadView('pages.invoices.invoice_pdf_view', ['school' => $school, 'invoice_data'=> $invoice_data,'invoice_items'=> $invoice_items, 'invoice_name' => $invoice_name, 'InvoicesTaxData' => $InvoicesTaxData, 'InvoicesExpData' => $InvoicesExpData]);
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('DOMPDF_ENABLE_CSS_FLOAT', true);
            $pdf->setHttpContext(
                stream_context_create([
                    'ssl' => [
                        'allow_self_signed'=> TRUE,
                        'verify_peer' => FALSE,
                        'verify_peer_name' => FALSE,
                    ]
                ])
              );
            $pdf->render();
            // print and save data
            if ($type == 'stream') {
                // save invoice name if invoice_filename is empty
                $file_upload = Storage::put('pdf/'. $invoice_name, $pdf->output());
                if($file_upload){
                    $invoice_pdf_path = URL::to("").'/uploads/pdf/'.$invoice_name;
                    $invoice_data->invoice_filename = $invoice_pdf_path;
                    $invoice_data->save();
                }
                return $pdf->stream( $invoice_name );
            }
            // only print view without save invoice and upload
            if ($type == 'print_view') {
                return $pdf->stream( $invoice_name );
            }
        }catch( Exception $e){
            // throw error
        }
    }


    public function generateReportPDF(Request $request, $type = 'stream')
    {
        try{
            $reqData = $request->all();

            $invoice_name = 'invoice-report.pdf';
            $pdf = PDF::loadView('pages.invoices.report');
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->set_option('DOMPDF_ENABLE_CSS_FLOAT', true);
            // print and save data
            if ($type == 'stream') {
                // save invoice name if invoice_filename is empty
                $file_upload = Storage::put('pdf/'. $invoice_name, $pdf->output());
                if($file_upload){
                    $invoice_pdf_path = URL::to("").'/uploads/pdf/'.$invoice_name;
                    $invoice_data->invoice_filename = $invoice_pdf_path;
                    $invoice_data->save();
                }
                return $pdf->stream( $invoice_name );
            }
            // only print view without save invoice and upload
            if ($type == 'print_view') {
                return $pdf->stream( $invoice_name );
            }
        }catch( Exception $e){
            // throw error
        }
    }

     /**
     *  AJAX delete Invoice
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-10-10
     */
    public function deleteInvoice(Request $request, Invoice $invoice)
    {
        $result = array(
            'status' => 'failed',
            'message' => __('failed to delete'),
        );
        try {
            $dataParam = $request->all();
            $id = trim($dataParam['p_invoice_id']);
            $invoiceData = Invoice::find($id);


            if ($invoiceData) {
                if ($invoiceData->invoice_type ==2) {
                    $invoiceItems= InvoiceItem::where('invoice_id', $invoiceData->id)->get();
                    foreach ($invoiceItems as $key => $invoiceData) {
                        $detail_id =  explode(',',$invoiceData->event_detail_id);
                        $eventUpdate = [
                            'buy_invoice_id' => null,
                            'is_buy_invoiced' => 0
                        ];
                        if (empty($invoiceData->event_detail_id)) {

                            $event_id =  $invoiceData->event_id;
                            $eventData = EventDetails::where('event_id', $event_id)
                            ->update($eventUpdate);
                        } else {
                            $detail_id =  explode(',',$invoiceData->event_detail_id);

                            $eventData = EventDetails::whereIn('id', $detail_id)
                            ->update($eventUpdate);
                        }
                        $eventData = EventDetails::whereIn('id', $detail_id)
                        ->update($eventUpdate);
                        $invoiceData->delete();
                    }
                    //$invoiceItems->delete();
                }
                if ($invoiceData->invoice_type ==1) {

                    $invoiceItems= InvoiceItem::where('invoice_id', $invoiceData->id)->get();

                    foreach ($invoiceItems as $key => $invoiceData) {
                        $event_id =  $invoiceData->event_id;


                        $eventUpdate = [
                            'sell_invoice_id' => null,
                            'is_sell_invoiced' => 0
                        ];



                        if (empty($invoiceData->event_detail_id)) {

                            $event_id =  $invoiceData->event_id;
                            $eventData = EventDetails::where('event_id', $event_id)
                            ->update($eventUpdate);

                        } else {
                            $detail_id =  explode(',',$invoiceData->event_detail_id);

                            $eventUpdate = [
                                'sell_invoice_id' => null,
                                'is_sell_invoiced' => 0
                            ];
                            $eventData = EventDetails::whereIn('id', $detail_id)
                            ->update($eventUpdate);
                        }
                        $invoiceData->delete();

                    }
                }
                $invoiceDelete = Invoice::find($id)->delete();


                $result = array(
                    "status"     => 'success',
                    'message' => __('Confirmed'),
                );
            }
            return response()->json($result);
        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
    }


}
