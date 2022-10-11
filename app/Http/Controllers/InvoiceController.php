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
use App\Models\Teacher;
use App\Models\Province;
use App\Models\EventDetails;
use App\Models\SchoolTeacher;
use App\Models\SchoolStudent;
use App\Models\EmailTemplate;
use App\Models\AttachedFile;
use App\Mail\SportloginEmail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Redirect;
use DB;

class InvoiceController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $schoolId = null)
    {
        $user = $request->user();
        $this->schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
        $school = School::active()->find($this->schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $invoice_type_all = config('global.invoice_type');
        $payment_status_all = config('global.payment_status');
        $invoice_status_all = config('global.invoice_status');
        $invoices = Invoice::active()->where('school_id', $this->schoolId)->get();
        //dd($invoices);
        return view('pages.invoices.list', compact('invoices', 'schoolId', 'invoice_type_all', 'payment_status_all', 'invoice_status_all'));
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
                //$result->client = $student;
                if ($student->student_notify == 1) {
                    $result_data->student_email = $student->email;
                }
                if ($student->father_notify == 1) {
                    $result_data->father_email = $student->father_email;
                }
                if ($student->mother_notify == 1) {
                    $result_data->mother_email = $student->mother_email;
                }
                $result_data->class_name = 'student';
                $result_data->primary_email = $student->email;
                $result_data->secondary_email = $student->email2;
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
            $user = $request->user();

            $p_template_code = trim($data['template_code']);
            $p_inv_auto_id = trim($data['p_inv_auto_id']);
            $p_inv_file = trim($data['p_inv_file']);
            $p_email = trim($data['p_email']);
            $p_school_id = trim($data['p_school_id']);
            $this->schoolId = $p_school_id;

            $res_data = array();
            $result_data = Invoice::active()->find($p_inv_auto_id);

            $emails = [];
            $target_user = [];
            $filtered_invoice = [0, 1, 9];

            if (in_array($result_data->invoice_type, $filtered_invoice)) {
                $target_user = $student = Student::find($result_data->client_id);

                //$result->client = $student;
                $emails[] = $student->email;
                $emails[] = $student->email2;
            } else {
                $target_user = $teacher = Teacher::find($result_data->seller_id);
                $emails[] = $teacher->email;
                $emails[] = $teacher->email2;
            }
            $result_data->emails = $emails;
            $result_data->target_user = $target_user;

            if ($user->isSuperAdmin()) {

                $school = School::active()->find($this->schoolId);
                //dd($school);
                if (empty($school)) {
                    return redirect()->route('schools')->with('error', __('School is not selected'));
                }
                $this->schoolId = $school->id;
                $schoolName = $school->school_name;
            } else {
                $this->schoolId = $user->selectedSchoolId();
                $schoolName = $user->selectedSchoolName();
            }
            //$result_data->invoice_filename = $emails;

            $invoiceCurrency = InvoiceItem::active()->where('invoice_id', $p_inv_auto_id)->get()->pluck('price_currency')->join(',');
            $result_data->invoice_price = $invoiceCurrency . '' . round($result_data->total_amount, 2);


            $res_data[] = $result_data;

            //sending email for forgot password
            if (config('global.email_send') == 1) {

                try {
                    $p_lang = 'en';
                    if (isset($data['p_lang'])) {
                        $p_lang = $data['p_lang'];
                    }
                    $email_data = [];
                    $email_data['subject'] = 'Pay reminder email';

                    $email_data['p_lang'] = $p_lang;
                    $email_data['name'] = $target_user->firstname . ' ' . $target_user->lastname;

                    $email_data['username'] = $target_user->firstname;
                    $email_data['school_name'] = $schoolName;
                    //$this->schoolId =
                    if ($p_email != '') {
                        //dd($p_email);
                        $email_to = str_replace(',', '|', $p_email);
                        $email_to = str_replace(';', '|', $p_email);

                        $email_to_arr = explode("|", $p_email);

                        //$cnt=sizeof($email_to_arr);
                        foreach ($email_to_arr  as &$value) {
                            if ($value != "") {
                                //$result_data->emails[]=$p_email;
                                $email_data['email'] = $value;
                                //dd($p_email);   
                                if ($this->emailSend($email_data, $p_template_code)) {

                                    $result = array(
                                        'status' => true,
                                        'message' => __('We sent you an activation link. Check your email and click on the link to verify.'),
                                    );
                                } else {
                                    $result = array(
                                        "status"     => false,
                                        'message' =>  __('Internal server error')
                                    );
                                }
                                //$mail->addAddress($value, $value);    
                            }
                            //unset($value);
                        }
                        //dd($email_to_arr);


                    } else {
                        //dd($email_data);
                        foreach ($result_data->emails as $key => $value) {
                            $email_data['email'] = $value;

                            if ($this->emailSend($email_data, $p_template_code)) {

                                $result = array(
                                    'status' => true,
                                    'message' => __('We sent you an activation link. Check your email and click on the link to verify.'),
                                );
                            } else {
                                $result = array(
                                    "status"     => false,
                                    'message' =>  __('Internal server error')
                                );
                            }
                        }
                    }

                    return response()->json($result);
                } catch (\Exception $e) {
                    $result = array(
                        'status' => true,
                        'message' => __('We sent you an activation code. Check your email and click on the link to verify.'),
                    );

                    return response()->json($result);
                }
            } else {
                $result = array('status' => true, 'msg' => __('email sent'));
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
    public function student_invoice_list(Request $request, $schoolId = null)
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

        $studentEvents = DB::table('events')
            ->join('event_details', 'events.id', '=', 'event_details.event_id')
            ->leftJoin('event_categories', 'event_categories.id', '=', 'events.event_category')
            ->leftJoin('school_student', 'school_student.student_id', '=', 'event_details.student_id')
            ->leftJoin('users', 'users.person_id', '=', 'event_details.student_id')
            ->select(
                'events.id as event_id',
                'event_details.student_id as person_id',
                'school_student.nickname as student_name',
                'users.profile_image_id as profile_image_id'
            )
            ->where(
                [
                    'events.school_id' => $schoolId,
                    'event_details.billing_method' => "E",
                    'events.is_active' => 1
                ]
            );
        $user_role = 'superadmin';
        if ($user->person_type == 'App\Models\Student') {
            $user_role = 'student';
        }
        if ($user->person_type == 'App\Models\Teacher') {
            $user_role = 'teacher';
        }
        $coach_user = '';
        if ($user->isSchoolAdmin() || $user->isTeacherAdmin()) {
            $user_role = 'admin_teacher';
            if ($user->isTeacherAdmin()) {
                $coach_user = 'coach_user';
            }
        }
        if ($user->isTeacherAll()) {
            $user_role = 'teacher_all';
        }
        if ($user->isTeacherMedium() || $user->isTeacherMinimum() || $user_role == 'teacher') {
            $user_role = 'teacher';
        }

        // dd($user);
        if ($user_role == 'admin_teacher' || $user_role == 'coach_user') {
            $invoice_type = 'S';
            $studentEvents->where('event_categories.invoiced_type', $invoice_type);
        } else if ($user_role == 'teacher_all') {
            $invoice_type = 'T';
            $studentEvents->where('event_categories.invoiced_type', $invoice_type);
        } else if ($user_role == 'teacher') {
            $invoice_type = 'T';
            $studentEvents->where('event_categories.invoiced_type', $invoice_type);
            $studentEvents->where('events.teacher_id', $user->person_id);
        } else {
        }
        $studentEvents->where('event_details.is_sell_invoiced', '=', 0);
        $studentEvents->whereNull('event_details.sell_invoice_id');

        $dateS = Carbon::now()->startOfMonth()->subMonth(1)->format('Y-m-d');
        $studentEvents->where('events.date_start', '>=', $dateS);
        $studentEvents->distinct('events.id');
        //$studentEvents->groupBy('event_details.student_id');
        //dd($studentEvents->toSql());

        $allEvents = DB::table(DB::raw('(' . $studentEvents->toSql() . ') as custom_table'))
            ->select(
                'custom_table.person_id as person_id',
                'custom_table.student_name as student_name',
                'custom_table.profile_image_id as profile_image_id'
            )
            ->selectRaw('count(custom_table.event_id) as invoice_items')
            ->mergeBindings($studentEvents)
            ->groupBy('custom_table.person_id')
            ->get();

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
        //dd($allStudentEvents);
        return view('pages.invoices.student_list', compact('allStudentEvents', 'schoolId', 'invoice_type_all', 'payment_status_all', 'invoice_status_all'));
    }







    /**
     *  List of teacher invoices by school
     * 
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-06-02
     */
    public function teacher_invoice_list(Request $request, $schoolId = null)
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



        $teacherEvents = DB::table('events')
            ->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')
            ->leftJoin('event_categories', 'event_categories.id', '=', 'events.event_category')
            ->leftJoin('school_teacher', 'school_teacher.teacher_id', '=', 'event_details.teacher_id')
            ->leftJoin('users', 'users.person_id', '=', 'event_details.teacher_id')
            ->select(
                //'event_details.event_id as event_id',
                'event_details.event_id as event_id',
                'event_details.student_id as student_id',
                'event_details.teacher_id as person_id',
                'school_teacher.nickname as teacher_name',
                'users.profile_image_id as profile_image_id'
            )
            //->selectRaw('count(events.id) as invoice_items')
            ->where(
                [
                    'events.school_id' => $schoolId,
                    'event_details.billing_method' => "E",
                    'events.is_active' => 1
                ]
            );
        $user_role = 'superadmin';
        if ($user->person_type == 'App\Models\Student') {
            $user_role = 'student';
        }
        if ($user->person_type == 'App\Models\Teacher') {
            $user_role = 'teacher';
        }
        $coach_user = '';
        if ($user->isSchoolAdmin() || $user->isTeacherAdmin()) {
            $user_role = 'admin_teacher';
            if ($user->isTeacherAdmin()) {
                $coach_user = 'coach_user';
            }
        }
        if ($user->isTeacherAll()) {
            $user_role = 'teacher_all';
        }
        if ($user->isTeacherMedium() || $user->isTeacherMinimum() || $user_role == 'teacher') {
            $user_role = 'teacher';
        }

        // dd($user);
        if ($user_role == 'admin_teacher' || $user_role == 'coach_user') {
            $invoice_type = 'S';
            $teacherEvents->where('event_categories.invoiced_type', $invoice_type);
        } else if ($user_role == 'teacher_all') {
            $invoice_type = 'T';
            $teacherEvents->where('event_categories.invoiced_type', $invoice_type);
        } else if ($user_role == 'teacher') {
            $invoice_type = 'T';
            $teacherEvents->where('event_categories.invoiced_type', $invoice_type);
            $teacherEvents->where('events.teacher_id', $user->person_id);
        } else {
        }
        $teacherEvents->where('event_details.visibility_id', '>', 0);
        //$teacherEvents->whereNotIn('events.event_type', [100]);
            
        $teacherEvents->where('event_details.is_buy_invoiced', '=', 0);
        $teacherEvents->whereNull('event_details.buy_invoice_id');


        $dateS = Carbon::now()->startOfMonth()->subMonth(1)->format('Y-m-d');
        $dateEnd = Carbon::now()->endOfMonth()->subMonth(1)->format('Y-m-d');
        //exit();


        $teacherEvents->where('events.date_start', '>=', $dateS);
        $teacherEvents->where('events.date_end', '<=', $dateEnd);
        //$teacherEvents->distinct('events.id');
        //$teacherEvents->groupBy('event_details.event_id');
        //$teacherEvents->groupBy('event.id');

        //dd($teacherEvents->toSql());
        //dd($data);
        $allEvents = DB::table(DB::raw('(' . $teacherEvents->toSql() . ') as custom_table'))
            ->select(
                'custom_table.person_id as person_id',
                'custom_table.teacher_name as teacher_name',
                'custom_table.profile_image_id as profile_image_id'
            )
            ->selectRaw('count(custom_table.event_id) as invoice_items')
            ->mergeBindings($teacherEvents)
            ->distinct('custom_table.event_id')
            ->groupBy('custom_table.person_id');
        //dd($allEvents->toSql());

        $allEventData =  $allEvents->get();

        //dd($allEventData);

        $allTeacherEvents = [];
        foreach ($allEventData as $key => $value) {
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
        return view('pages.invoices.teacher_list', compact('allTeacherEvents', 'schoolId', 'invoice_type_all', 'payment_status_all', 'invoice_status_all'));
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
            $p_pending_only=trim($data['p_pending_only']);
            $assData = DB::table('events')
                ->join('event_details', 'events.id', '=', 'event_details.event_id')
                ->leftJoin('event_categories', 'event_categories.id', '=', 'events.event_category')
                ->leftJoin('teachers', 'teachers.id', '=', 'event_details.teacher_id')
                ->select(
                    'events.event_type as event_type',
                    'events.event_category as category_id',
                    'teachers.id as teacher_id',
                )
                ->where(
                    [
                        'event_details.student_id' => $p_person_id,
                        'event_details.billing_method' => "E",
                        'events.is_active' => 1,
                        'events.school_id' => $p_school_id,
                    ]
                )->distinct('events.id')->get();

            $studentEvents = DB::table('events')
                ->join('event_details', 'events.id', '=', 'event_details.event_id')
                ->leftJoin('event_categories', 'event_categories.id', '=', 'events.event_category')
                ->leftJoin('teachers', 'teachers.id', '=', 'event_details.teacher_id')
                ->leftJoin('students', 'students.id', '=', 'event_details.student_id')
                ->leftJoin('users', 'users.person_id', '=', 'event_details.teacher_id')
                ->leftJoin('schools', 'schools.id', '=', 'events.school_id')
                ->leftJoin('lesson_price_teachers', 'lesson_price_teachers.lesson_price_id', '=', 'events.no_of_students')
                ->select(
                    'events.id as event_id',
                    'event_details.buy_total as buy_total',
                    'event_details.sell_total as sell_total',
                    'event_details.buy_price as buy_price',
                    'event_details.sell_price as sell_price',
                    'events.title as title',
                    'events.event_type as event_type',
                    'events.event_category as category_id',
                    'event_categories.title as category_name',
                    'events.is_paying as is_paying',
                    'events.event_price as price_id',
                    'event_details.is_locked as ready_flag',
                    'event_details.participation_id as participation_id',
                    'event_details.is_buy_invoiced as is_buy_invoiced',
                    'event_details.is_sell_invoiced as is_sell_invoiced',
                    //'event_details.price_currency as price_currency',
                    'event_details.costs_1 as costs_1',
                    'event_details.costs_2 as costs_2',
                    'teachers.id as teacher_id',
                    'lesson_price_teachers.price_buy as price_buy',
                    'lesson_price_teachers.price_sell as price_sell'
                    //'events.is_locked as ready_flag'

                    // 'users.profile_image_id as profile_image_id'
                )
                ->selectRaw("ifnull(events.duration_minutes,0) AS duration_minutes")
                ->selectRaw("ifnull(event_details.price_currency,'CAD') AS price_currency")
                ->selectRaw("if((events.event_type = 100),'Event','Lesson') AS price_name")
                ->selectRaw("CONCAT_WS('', students.firstname, students.middlename, students.lastname) AS student_name")
                ->selectRaw("CONCAT_WS('', teachers.firstname, teachers.middlename, teachers.lastname) AS teacher_name")
                ->selectRaw('DATE_FORMAT(str_to_date(concat("01/",month(events.date_start),"/",year(events.date_start)),"%d/%m/%Y"),"%d/%m/%Y") as FirstDay')
                ->selectRaw('DATE_FORMAT(str_to_date(concat("30/",month(events.date_start),"/",year(events.date_start)),"%d/%m/%Y"),"%d/%m/%Y") as Lastday')
                ->selectRaw('DATE_FORMAT(events.date_start,"%H:%i") time_start')
                ->selectRaw('DATE_FORMAT(events.date_start,"%d/%m/%Y") date_start')
                ->selectRaw('week(events.date_start,5) week_no')
                ->selectRaw('concat("Semaine ",week(events.date_start,5)) as week_name')
                //->selectRaw('count(events.id) as invoice_items')
                ->where(
                    [
                        'event_details.student_id' => $p_person_id,
                        'event_details.billing_method' => "E",
                        'events.is_active' => 1,
                        'events.school_id' => $p_school_id,
                        //'lesson_price_teachers.event_category_id' => $assData[0]->category_id,
                        //'lesson_price_teachers.teacher_id' => $assData[0]->teacher_id,
                    ]
                );


            $user_role = 'superadmin';
            if ($user->person_type == 'App\Models\Student') {
                $user_role = 'student';
            }
            if ($user->person_type == 'App\Models\Teacher') {
                $user_role = 'teacher';
            }
            $coach_user = '';
            if ($user->isSchoolAdmin() || $user->isTeacherAdmin()) {
                $user_role = 'admin_teacher';
                if ($user->isTeacherAdmin()) {
                    $coach_user = 'coach_user';
                }
            }
            if ($user->isTeacherAll()) {
                $user_role = 'teacher_all';
            }
            if ($user->isTeacherMedium() || $user->isTeacherMinimum() || $user_role == 'teacher') {
                $user_role = 'teacher';
            }

            // dd($user);
            if ($user_role == 'admin_teacher' || $user_role == 'coach_user') {
                $invoice_type = 'S';
                $studentEvents->where('event_categories.invoiced_type', $invoice_type);
            } else if ($user_role == 'teacher_all') {
                $invoice_type = 'T';
                $studentEvents->where('event_categories.invoiced_type', $invoice_type);
            } else if ($user_role == 'teacher') {
                $invoice_type = 'T';
                $studentEvents->where('event_categories.invoiced_type', $invoice_type);
                $studentEvents->where('events.teacher_id', $user->person_id);
            } else {
            }
            if (!empty($p_pending_only)) {
                $studentEvents->where(
                    function ($query) {
                        $query->where('event_details.is_sell_invoiced', '=', 0)
                            ->orWhereNull('event_details.is_sell_invoiced');
                    }
                );
            }
            

            $qq = "events.date_start BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', $p_billing_period_start_date))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', $p_billing_period_end_date))) . "'";
            $studentEvents->whereRaw($qq);


            //$studentEvents->where('events.date_start', '>=', $dateS);
            $studentEvents->orderBy('events.date_start', 'desc');
            //By
            $studentEvents->distinct('events.id');

            $studentEvents->groupBy('events.id');
            $data = $studentEvents->get();
            //dd($data);

            $result = array(
                'status' => true,
                'message' => __('We got a list of invoice'),
                'data' => $data,
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

            $teacherEvents = DB::table('events')
                ->join('event_details', 'events.id', '=', 'event_details.event_id')
                ->leftJoin('event_categories', 'event_categories.id', '=', 'events.event_category')
                ->leftJoin('school_teacher', 'school_teacher.teacher_id', '=', 'event_details.teacher_id')
                ->leftJoin('teachers', 'teachers.id', '=', 'event_details.teacher_id')
                ->leftJoin('students', 'students.id', '=', 'event_details.student_id')
                ->leftJoin('users', 'users.person_id', '=', 'event_details.teacher_id')
                //->leftJoin('schools', 'schools.id', '=', 'events.school_id')
                //->leftJoin('lesson_prices', 'lesson_prices.event_type', '=', 'events.event_type')
                ->select(
                    'events.id as event_id',
                    'event_details.id as event_id1',
                    'event_details.buy_price as buy_price',
                    'event_details.sell_price as sell_price',
                    'events.price_amount_buy as price_amount_buy',
                    'event_details.buy_total as buy_total',
                    'event_details.sell_total as sell_total',
                    'events.title as title',
                    'events.event_type as event_type',
                    'events.event_category as category_id',
                    'event_categories.title as category_name',
                    'events.is_paying as is_paying',
                    'events.event_price as price_id',
                    'event_details.is_locked as ready_flag',
                    'event_details.participation_id as participation_id',
                    'event_details.is_buy_invoiced as is_buy_invoiced',
                    'event_details.is_sell_invoiced as is_sell_invoiced',
                    //'event_details.price_currency as price_currency',
                    'event_details.costs_1 as costs_1',
                    'event_details.costs_2 as costs_2'
                )
                ->selectRaw("ifnull(events.duration_minutes,0) AS duration_minutes")
                ->selectRaw("ifnull(event_details.price_currency,'CAD') AS price_currency")
                ->selectRaw("if((events.event_type = 100),'Event','Lesson') AS price_name")
                ->selectRaw("CONCAT_WS('', students.firstname, students.middlename, students.lastname) AS student_name")
                ->selectRaw("CONCAT_WS('', teachers.firstname, teachers.middlename, teachers.lastname) AS teacher_name")
                ->selectRaw('DATE_FORMAT(str_to_date(concat("01/",month(events.date_start),"/",year(events.date_start)),"%d/%m/%Y"),"%d/%m/%Y") as FirstDay')
                ->selectRaw('DATE_FORMAT(str_to_date(concat("30/",month(events.date_start),"/",year(events.date_start)),"%d/%m/%Y"),"%d/%m/%Y") as Lastday')
                ->selectRaw('DATE_FORMAT(events.date_start,"%H:%i") time_start')
                ->selectRaw('DATE_FORMAT(events.date_start,"%d/%m/%Y") date_start')
                ->selectRaw('week(events.date_start,5) week_no')
                ->selectRaw('concat("Semaine ",week(events.date_start,5)) as week_name')
                //->selectRaw('count(events.id) as invoice_items')
                ->where(
                    [
                        'event_details.teacher_id' => $p_person_id,
                        'event_details.billing_method' => "E",
                        //'event_categories.invoiced_type' => "S",
                        'events.is_active' => 1,
                        'events.school_id' => $p_school_id
                    ]
                );
            $user_role = 'superadmin';
            if ($user->person_type == 'App\Models\Student') {
                $user_role = 'student';
            }
            if ($user->person_type == 'App\Models\Teacher') {
                $user_role = 'teacher';
            }
            $coach_user = '';
            if ($user->isSchoolAdmin() || $user->isTeacherAdmin()) {
                $user_role = 'admin_teacher';
                if ($user->isTeacherAdmin()) {
                    $coach_user = 'coach_user';
                }
            }
            if ($user->isTeacherAll()) {
                $user_role = 'teacher_all';
            }
            if ($user->isTeacherMedium() || $user->isTeacherMinimum() || $user_role == 'teacher') {
                $user_role = 'teacher';
            }

            // dd($user);
            if ($user_role == 'admin_teacher') {
                $invoice_type = 'S';
                $teacherEvents->where('event_categories.invoiced_type', $invoice_type);
            } else if ($user_role == 'teacher_all') {
                $invoice_type = 'T';
                $teacherEvents->where('event_categories.invoiced_type', $invoice_type);
            } else if ($user_role == 'teacher') {
                $invoice_type = 'T';
                $teacherEvents->where('event_categories.invoiced_type', $invoice_type);
                $teacherEvents->where('events.teacher_id', $user->person_id);
            } else {
            }
            //$studentEvents->where('events.is_paying', '>', 0);
            $teacherEvents->where('event_details.visibility_id', '>', 0);
            //$studentEvents->whereNotIn('events.event_type', [100]);
            $teacherEvents->whereNotNull('events.date_start');

            $teacherEvents->where('event_details.is_buy_invoiced', '=', 0);
            $teacherEvents->whereNull('event_details.buy_invoice_id');

            $qq = "events.date_start BETWEEN '" . date('Y-m-d', strtotime(str_replace('/', '-', $p_billing_period_start_date))) . "' AND '" . date('Y-m-d', strtotime(str_replace('/', '-', $p_billing_period_end_date))) . "'";
            $teacherEvents->whereRaw($qq);

            // $qq = "DATE_FORMAT(STR_TO_DATE(events.date_start,'%Y-%m-%d'),'%d/%m/%Y') BETWEEN '" . $p_billing_period_start_date . "' AND '" . $p_billing_period_end_date . "'";
            // $studentEvents->whereRaw($qq);
            //$studentEvents->whereBetween('events.date_start', [$p_billing_period_start_date, $p_billing_period_end_date]);

            $teacherEvents->orderBy('events.date_start', 'desc');
            //By
            $teacherEvents->distinct('events.id');
            $teacherEvents->groupBy('events.id');
            //dd($studentEvents->toSql());
            $data = $teacherEvents->get();
            //dd($data);

            $result = array(
                'status' => true,
                'message' => __('We got a list of invoice'),
                'data' => $data,
                //'no_of_teachers' =>$no_of_teachers
            );
            return response()->json($result);
        } catch (Exception $e) {
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
            $p_person_id = trim($data['p_person_id']);
            $p_school_id = trim($data['school_id']);
            $p_billing_period_start_date = trim($data['p_billing_period_start_date']);
            $p_billing_period_end_date = trim($data['p_billing_period_end_date']);

            $p_invoice_id=trim($data['p_invoice_id']);
            $p_discount_perc=trim($data['p_discount_perc']);
            
            // $query="call generate_new_teacher_invoice_new('$p_lang_id','$p_app_id','$p_school_id','$p_invoice_id','$p_person_id','$p_billing_period_start_date','$p_billing_period_end_date','$p_discount_perc');";
            // //echo "<script>alert($query);</script>";exit;
            // $result = mysql_query($query)  or die( $return = 'Error:-3> ' . mysql_error());
            // while($row = mysql_fetch_assoc($result))
            // {
            //     $data[]=$row;
            // }
            // echo json_encode($data);

            $result = array(
                'status' => true,
                'message' => __('We got a list of invoice'),
                'data' => $data,
                //'no_of_teachers' =>$no_of_teachers
            );
            return response()->json($result);
        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
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
            //dd($data);
            $p_person_id = trim($data['p_person_id']);
            $p_student_id = trim($data['p_person_id']);
            $p_school_id = trim($data['school_id']);
            $p_from_date = trim($data['p_from_date']);
            $p_to_date = trim($data['p_to_date']);
            $dateS = date('Y-m-d', strtotime(str_replace('.', '-', $p_from_date)));
            $dateEnd = date('Y-m-d', strtotime(str_replace('.', '-', $p_to_date)));

            $p_invoice_id=trim($data['p_invoice_id']);
            
            $schoolId = $p_school_id;
            $user = $request->user();
            $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return redirect()->route('schools')->with('error', __('School is not selected'));
            }
            $user_role = 'superadmin';
            if ($user->isSchoolAdmin() || $user->isTeacherAdmin()) {
                $user_role = 'admin_teacher';
                
            }
            if ($user->isTeacherAll()) {
                $user_role = 'teacher_all';
            }
            if ($user->isTeacherMedium() || $user->isTeacherMinimum() || $user_role == 'teacher') {
                $user_role = 'teacher';
            }
            if ($user_role == 'admin_teacher' || $user_role == 'coach_user') {
                $invoice_type = 'S';
            } else if ($school->school_type == 'C' || $user_role == 'teacher_all') {
                $invoice_type = 'T';
            } else if ($user_role == 'teacher') {
                $invoice_type = 'T';
            } else {
                $invoice_type = 'S';
            }

            

            //$v_inv_name = 'Facture '.$month_name.' '.year(v_period_starts);
            
            if($p_invoice_id=''){
	            $v_invoice_id='';
            }
            else {
                $v_invoice_id=$p_invoice_id;
            }
            $invoiceData['school_id'] = $schoolId;
            $invoiceData['invoice_no'] = $v_invoice_id;
            $invoiceData['invoice_type'] = 1;
            $invoiceData['invoice_status'] = 1;
            $invoiceData['date_invoice'] = Carbon::now()->format('Y-m-d H:i:s');
            $invoiceData['period_starts'] = $dateS;
            $invoiceData['period_ends'] = $dateEnd;
            $invoiceData['invoice_name'] = 'Invoice '.Carbon::now()->format('F').' '.Carbon::now()->year;
            //$date_invoice = Carbon::createFromFormat('Y-m-d H:i:s', $invoiceData['date_invoice'], 'UTC'); // specify UTC otherwise defaults to locale time zone as per ini setting
        
            //$invoiceData['invoice_header'] = $invoiceData['invoice_name'].'-'.$invoiceData['client_name'].' du '.$date_invoice;
            
           
            
            if ($invoice_type == 'T') {
                $professors = SchoolTeacher::active()->where('school_id',$schoolId);
                $professors->where('teacher_id',$user->person_id);
                $professors = $professors->first();
                $teacher = Teacher::find($professors->teacher_id);

                $invoiceData['seller_id'] = $teacher->id;
                $invoiceData['seller_name'] = $teacher->firstname.' '.$teacher->lastname;
                $invoiceData['seller_street'] = $teacher->street;
                $invoiceData['seller_street_number'] = $teacher->street_number;
                $invoiceData['seller_street2'] = $teacher->street2;
                $invoiceData['seller_zip_code'] = $teacher->zip_code;
                $invoiceData['seller_place'] = $teacher->place;
                $invoiceData['seller_country_id'] = $teacher->country_code;
                $invoiceData['seller_phone'] = $teacher->phone;
                $invoiceData['seller_mobile'] = $teacher->mobile;
                $invoiceData['seller_email'] = $teacher->email;
                $invoiceData['seller_gender_id'] = $teacher->gender_id;
                $invoiceData['seller_lastname'] = $teacher->firstname;
                $invoiceData['seller_firstname'] = $teacher->lastname;
            } else {
                $invoiceData['seller_id'] = $schoolId;
                $invoiceData['seller_name'] = $school->name;
                $invoiceData['seller_street'] = $school->street;
                $invoiceData['seller_street_number'] = $school->street_number;
                $invoiceData['seller_street2'] = $school->street2;
                $invoiceData['seller_zip_code'] = $school->zip_code;
                $invoiceData['seller_place'] = $school->place;
                $invoiceData['seller_country_id'] = $school->country_code;
                $invoiceData['seller_phone'] = $school->phone;
                $invoiceData['seller_mobile'] = $school->mobile;
                $invoiceData['seller_email'] = $school->email;
                $invoiceData['seller_gender_id'] = $school->contact_gender_id;
                $invoiceData['seller_lastname'] = $school->contact_lastname;
                $invoiceData['seller_firstname'] = $school->contact_firstname;
            }
            
            
            if (!empty($p_student_id)) {
                $Sstudent = SchoolStudent::active()->where('school_id',$schoolId);
                $Sstudent->where('student_id',$p_student_id);
                $studentSchool = $Sstudent->first();
                $student = Student::find($studentSchool->student_id);
                $invoiceData['client_id'] = $student->id;
                $invoiceData['client_name'] = $student->firstname.'N '.$student->lastname;
                $invoiceData['client_street'] = $student->street;
                $invoiceData['client_street_number'] = $student->street_number;
                $invoiceData['client_street2'] = $student->street2;
                $invoiceData['client_zip_code'] = $student->zip_code;
                $invoiceData['client_place'] = $student->place;
                $invoiceData['client_country_id'] = $student->country_code;
                // $invoiceData['client_phone'] = $student->phone;
                // $invoiceData['client_mobile'] = $student->mobile;
                // $invoiceData['client_email'] = $student->email;
                $invoiceData['client_gender_id'] = $student->gender_id;
                $invoiceData['client_lastname'] = $student->lastname;
                $invoiceData['client_firstname'] = $student->firstname;
            }

            //dd($invoiceData);
           
            
            
            


            $invoiceData['payment_bank_iban'] = $school->bank_iban;
            
            $invoiceData['payment_bank_account_name'] = $school->bank_account_holder;
            $invoiceData['payment_bank_account'] = $school->bank_account;
            $invoiceData['payment_bank_swift'] = $school->bank_swift;
            $invoiceData['payment_bank_name'] = $school->bank_name;
            $invoiceData['payment_bank_address'] = $school->bank_address;
            $invoiceData['payment_bank_zipcode'] = $school->bank_zipcode;
            $invoiceData['payment_bank_place'] = $school->bank_place;
            $invoiceData['payment_bank_country_id'] = $school->bank_country_code;
            

            $invoiceData['discount_percent_1'] = $school->discount_percent_1;
            $invoiceData['discount_percent_2'] = $school->discount_percent_2;
            $invoiceData['discount_percent_3'] = $school->discount_percent_3;
            $invoiceData['discount_percent_4'] = $school->discount_percent_4;
            $invoiceData['discount_percent_5'] = $school->discount_percent_5;
            $invoiceData['discount_percent_6'] = $school->discount_percent_6;
            
            //$invoiceData['invoice_currency'] = $data->price_currency;
            $invoiceData['category_invoiced_type'] = $invoice_type;
            $invoiceData['created_by'] = $user->id;
            $invoiceData['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
            
            
            

            $invoiceData = Invoice::create($invoiceData);
            



            $studentEvents = DB::table('events')
                ->select(
                    'events.id as event_id',
                    'events.teacher_id as teacher_id',
                    'event_details.student_id as student_id',
                    
                    'events.duration_minutes as duration_minutes',
                    'events.event_type as event_type',
                    'events.title as title',
                    'event_details.costs_1 as costs_1',
                    'event_details.costs_2 as costs_2',
                    'event_details.participation_id as participation_id',

                    'event_details.id as detail_id',
                    'event_details.is_locked as is_locked',
                    'events.event_price as event_price',
                    'events.date_start as date_start',
                    
                    
                    'event_details.buy_total as buy_total',
                    'event_details.sell_total as sell_total',
                    'event_details.buy_price as buy_price',
                    'event_details.sell_price as sell_price'
                )
                //->selectRaw("ifnull(events.duration_minutes,0) AS duration_minutes")
                ->selectRaw("ifnull(event_details.price_currency,'CAD') AS price_currency")
                ->join('event_details', 'events.id', '=', 'event_details.event_id')
                ->leftJoin('event_categories', 'event_categories.id', '=', 'events.event_category')
                ->where(
                    [
                        'event_details.student_id' => $p_person_id,
                        'events.is_active' => 1,
                        'event_details.is_sell_invoiced' => 0,
                        'events.school_id' => $schoolId,
                    ]
                );
            $studentEvents->where('event_details.participation_id', '>', 198);
            $studentEvents->where('events.date_start', '>=', $dateS);
            $studentEvents->where('events.date_end', '<=', $dateEnd);
            //dd($dateS);
            if ($user_role != 'superadmin') {
                if ($user_role == 'teacher') {
                    $studentEvents->where('events.teacher_id', $user->person_id);
                } else {
                    $studentEvents->where('event_categories.invoiced_type', $invoice_type);
                }
            }
            
            
            //dd($dateS);

            

            
            
            //$studentEvents->where('events.date_start', '>=', $dateS);
            $studentEvents->orderBy('events.date_start', 'desc');
            //By
            $studentEvents->distinct('events.id');

            //$studentEvents->groupBy('events.id');
            
            //dd($studentEvents->toSql());
            $data = $studentEvents->get()->toArray();
            //dd($invoiceData->id);
            foreach ($data as $key => $value) {
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
                $invoiceItemData['price'] = $value->sell_price+$value->costs_1+$value->costs_2;
                $invoiceItemData['price_unit'] = $value->sell_price;
                $invoiceItemData['price_currency'] = $value->price_currency;
                $invoiceItemData['event_extra_expenses'] = $value->costs_1+$value->costs_2;            
                $invoiceItemData['publication_mode'] = 'N,admin';
                // if ($event_type == 10) {
                //    $invoiceItemData['item_type'] = 1;
                // }
                // else {
                //    $invoiceItemData['item_type'] = 2;
                // }
                $invoiceItemData['event_id'] = $value->event_id;
                $invoiceItemData['teacher_id'] = $value->teacher_id;
                $invoiceItemData['student_id'] = $p_person_id;
                $invoiceItemData['participation_id'] = $value->participation_id;
                $invoiceItemData['price_type_id'] = $value->event_price;
                $invoiceItemData['is_locked'] = $value->is_locked;
                $invoiceItemData['date'] = $value->date_start;
                // if ($value->event_type == 10) {
                //     $invoiceItemData['caption'] = 'Lesson:'.$value->title;
                //     if ($value->cost_1>0) {
                //         $invoiceItemData['caption'] .='<br>Extra '.$value->cost_1;
                //     }
                // }
                // else if ($value->event_type == 13) {
                //     $invoiceItemData['caption'] = 'Event:'.$value->title;
                //     if ($value->cost_1>0) {
                //         $invoiceItemData['caption'] .='<br>Extra '.$value->cost_1;
                //     }
                // } else if ($value->event_type == 100) {
                //    $invoiceItemData['caption'] = 'Event:'.$value->title;
                //    if ($value->cost_1>0) {
                //         $invoiceItemData['caption'] .='<br>Extra '.$value->cost_1;
                //    }
                // } else if (!empty($value->title)) {
                //     if ($value->participation_id==199) {
                //         $invoiceItemData['caption'] ='du '.$value->title;
                //     } else{
                //         $invoiceItemData['caption'] =$value->title.'avec '.$value->teacher_id.'('.$value->category_name.')';
                //     }
                //     if ($value->event_type == 10) {
                //         $invoiceItemData['caption'] = 'price_name';
                //         if ($value->cost_1>0) {
                //             $invoiceItemData['caption'] .='<br>Extra '.$value->cost_1;
                //         }
                //     }
                // }
                

                //dd($invoiceItemData);
                if (!empty($value->event_id)) {
                    $eventUpdate = [
                        'sell_invoice_id' => $invoiceData->id,
                        'is_sell_invoiced' => 1
                    ];
                    $eventData = EventDetails::where('student_id', $value->student_id)
                    //->where('school_id', $schoolId)
                    ->where('event_id', $value->event_id)
                    ->where('participation_id', '>', 198)
                    ->update($eventUpdate);
                }
                

                $invoiceItemData = InvoiceItem::create($invoiceItemData);

                //$query="call generate_new_student_invoice('$p_lang_id','$p_app_id','$p_school_id','$p_invoice_id','$p_person_id','$p_from_date','$p_to_date','$p_event_ids','$p_discount_percent_1','$p_discount_percent_2','$p_discount_percent_3','$p_discount_percent_4','$p_discount_percent_5','$p_discount_percent_6','$by_user_id');";
                // //echo "<script>alert($query);</script>";exit;
                // $result = mysql_query($query)  or die( $return = 'Error:-3> ' . mysql_error());
                // while($row = mysql_fetch_assoc($result))
                // {
                //     $data[]=$row;
                // }
                // echo json_encode($data);
                
            }
            

            

            $result = array(
                'status' => true,
                'message' => __('We got a list of invoice'),
                'data' => $data,
                //'no_of_teachers' =>$no_of_teachers
            );
            return response()->json($result);
        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
    }








    public function view(Request $request, Invoice $invoice)
    {
        $user = Auth::user();
        //$invoiceId = $request->route('invoice'); 
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
        
        $teachers = SchoolTeacher::active()->onlyTeacher()->where('school_id',$schoolId)->get();
        $students = DB::table('school_student')
                    ->join('students','school_student.student_id','=','students.id')
                    ->where(['school_id' => $schoolId, 'school_student.is_active' => 1])
                    ->get();

        return view('pages.invoices.manual_invoice', [
            'title' => 'Invoice',
            'pageInfo' => ['siteTitle' => '']
        ])->with(compact('genders','schoolId','countries', 'provinces','students','teachers'));
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
        
        $teachers = SchoolTeacher::active()->onlyTeacher()->where('school_id',$schoolId)->get();
        $students = DB::table('school_student')
                    ->join('students','school_student.student_id','=','students.id')
                    ->where(['school_id' => $schoolId, 'school_student.is_active' => 1])
                    ->get();

        $invoiceData = Invoice::active()->where(['id'=>$invoiceId])->first();
        $InvoicesTaxData = InvoicesTaxes::active()->where(['invoice_id'=>$invoiceId])->get()->toArray();
        $InvoicesExpData = InvoicesExpenses::active()->where(['invoice_id'=>$invoiceId])->get()->toArray();       

        return view('pages.invoices.update_manual_invoice', [
            'title' => 'Invoice',
            'pageInfo' => ['siteTitle' => '']
        ])->with(compact('genders','schoolId','countries', 'provinces','students','teachers','invoiceData','InvoicesTaxData','InvoicesExpData'));
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

            $data = [
                'school_id' => $schoolId,
                'invoice_type' => $dataParam['p_invoice_type'],
                'invoice_name' => $dataParam['p_invoice_name'],
                'date_invoice' => date("Y-m-d H:i:s", strtotime($dataParam['p_date_invoice'])),
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
                //'detail_rows' => $dataParam['p_detail_rows'],
                'client_province_id' => $dataParam['p_client_province_id'],
                'seller_province_id' => $dataParam['p_seller_province_id'],
                'bank_province_id' => $dataParam['p_bank_province_id'],
                'total_amount' => $dataParam['p_total_amount'],
            ];

            $Invoice = Invoice::create($data);
            
            $myArray = explode('|', $dataParam['p_detail_rows']);
            print_r($myArray);
            echo count($myArray);
            exit;

            if (!empty($dataParam['tax_name'])) {
                for($i=0; $i < count($dataParam['tax_name']);$i++){
                    $taxData = [
                        'invoice_id'   => $Invoice->id,
                        'tax_name' => $dataParam['tax_name'][$i],
                        'tax_percentage' => $dataParam['tax_percentage'][$i],
                        'tax_number' => $dataParam['tax_number'][$i],
                        'tax_amount' => $dataParam['tax_amount'][$i],
                    ];
                    $InvoiceTax = InvoicesTaxes::create($taxData);
                }
            }


            if (!empty($dataParam['tax_name'])) {
                for($i=0; $i < count($dataParam['tax_name']);$i++){
                    $taxData = [
                        'invoice_id'   => $Invoice->id,
                        'tax_name' => $dataParam['tax_name'][$i],
                        'tax_percentage' => $dataParam['tax_percentage'][$i],
                        'tax_number' => $dataParam['tax_number'][$i],
                        'tax_amount' => $dataParam['tax_amount'][$i],
                    ];
                    $InvoiceTax = InvoicesTaxes::create($taxData);
                }
            }

            if (!empty($dataParam['expense_name'])) {
                for($i=0; $i < count($dataParam['expense_name']);$i++){
                    $expenseData = [
                        'invoice_id'   => $Invoice->id,
                        'expense_name' => $dataParam['expense_name'][$i],
                        'expense_amount' => $dataParam['expense_amount'][$i]
                    ];
                    $InvoiceExpense = InvoicesExpenses::create($expenseData);
                }
            }
                
            DB::commit();

            return [
                'id' => $Invoice->id,
                'status' => 1,
                'message' =>  __('Successfully Registered')
            ];
        }catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->all())->with('error', __('Internal server error'));
        }   

        return $result;        
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


            $data = [
                'school_id' => $schoolId,
                'invoice_type' => $dataParam['p_invoice_type'],
                'invoice_name' => $dataParam['p_invoice_name'],
                'date_invoice' => date("Y-m-d H:i:s", strtotime($dataParam['p_date_invoice'])),
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
                'client_province_id' => $dataParam['p_client_province_id'],
                'seller_province_id' => $dataParam['p_seller_province_id'],
                'bank_province_id' => $dataParam['p_bank_province_id'],
                'total_amount' => $dataParam['p_total_amount'],
            ];

            $Invoice = Invoice::where('id', $id)->update($data);
            InvoicesTaxes::where('invoice_id',$id)->forceDelete();
            InvoicesExpenses::where('invoice_id',$id)->forceDelete();

            if (!empty($dataParam['tax_name'])) {
                for($i=0; $i < count($dataParam['tax_name']);$i++){
                    $taxData = [
                        'invoice_id'   => $id,
                        'tax_name' => $dataParam['tax_name'][$i],
                        'tax_percentage' => $dataParam['tax_percentage'][$i],
                        'tax_number' => $dataParam['tax_number'][$i],
                        'tax_amount' => $dataParam['tax_amount'][$i],
                    ];
                    $InvoiceTax = InvoicesTaxes::create($taxData);
                }
            }            

            if (!empty($dataParam['expense_name'])) {
                for($i=0; $i < count($dataParam['expense_name']);$i++){
                    $expenseData = [
                        'invoice_id'   => $id,
                        'expense_name' => $dataParam['expense_name'][$i],
                        'expense_amount' => $dataParam['expense_amount'][$i]
                    ];
                    $InvoiceExpense = InvoicesExpenses::create($expenseData);
                }
            }
                
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

        return $result;        
    }
}