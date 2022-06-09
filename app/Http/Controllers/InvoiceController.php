<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\School;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\EmailTemplate;
use App\Models\AttachedFile;
use App\Mail\SportloginEmail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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
    public function index(Request $request,$schoolId = null)
    {

        $user = $request->user();
        $this->schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($this->schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        // $school = School::active()->find($schoolId);
        // if (empty($school)) {
        //     $schoolId = 0;
        // }
        $invoice_type_all = config('global.invoice_type');
        $payment_status_all = config('global.payment_status');
        $invoice_status_all = config('global.invoice_status');
        $invoices = Invoice::active()->where('school_id',$this->schoolId)->get();
        //dd($invoices);
        return view('pages.invoices.list',compact('invoices','schoolId','invoice_type_all','payment_status_all','invoice_status_all'));
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
            $filtered_invoice = [0,1,9];
            $result_data->student_email = '';
            $result_data->father_email = '';
            $result_data->mother_email = '';
            
            if (in_array($result_data->invoice_type, $filtered_invoice)) {
                $result_data->class_name = 'student';
                $student = Student::find($result_data->client_id);
                //$result->client = $student;
                if ($student->student_notify ==1) {
                    $result_data->student_email = $student->email;
                }
                if ($student->father_notify ==1) {
                    $result_data->father_email = $student->father_email;
                }
                if ($student->mother_notify ==1) {
                    $result_data->mother_email = $student->mother_email;
                }
                $result_data->class_name = 'student';
                $result_data->primary_email = $student->email;
                $result_data->secondary_email = $student->email2;
            } else {
                $result_data->class_name = 'teacher';
                $teacher = Teacher::find($result->seller_id);
                $result_data->primary_email = $teacher->email;
                $result_data->secondary_email = $teacher->email2;
            }
            $data[]= $result_data;
            
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
            
            $p_template_code=trim($data['template_code']);
            $p_inv_auto_id=trim($data['p_inv_auto_id']);
            $p_inv_file=trim($data['p_inv_file']);
            $p_email=trim($data['p_email']);
            $p_school_id=trim($data['p_school_id']);
            $this->schoolId= $p_school_id;
            
            $res_data = array();
            $result_data = Invoice::active()->find($p_inv_auto_id);
            
            $emails = [];
            $target_user = [];
            $filtered_invoice = [0,1,9];
            
            if (in_array($result_data->invoice_type, $filtered_invoice)) {
                $target_user = $student = Student::find($result_data->client_id);
                
                //$result->client = $student;
                $emails[] = $student->email;
                $emails[] = $student->email2;
            } else {
                $target_user = $teacher = Teacher::find($result->seller_id);
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
            }else {
                $this->schoolId = $user->selectedSchoolId();
                $schoolName = $user->selectedSchoolName(); 
            }
            //$result_data->invoice_filename = $emails;

            $invoiceCurrency = InvoiceItem::active()->where('invoice_id',$p_inv_auto_id)->get()->pluck('price_currency')->join(',');
            $result_data->invoice_price = $invoiceCurrency.''.round($result_data->total_amount,2);
            
            
            $res_data[]= $result_data;
            
            //sending email for forgot password
            if (config('global.email_send') == 1) {
                       
                try {
                    $p_lang = 'en'; 
                    if (isset($data['p_lang'])) {
                        $p_lang = $data['p_lang']; 
                    }
                    $email_data = [];
                    $email_data['subject']='Pay reminder email';
                    
                    $email_data['p_lang'] = $p_lang; 
                    $email_data['name'] = $target_user->firstname.' '.$target_user->lastname;
                        
                    $email_data['username'] = $target_user->firstname; 
                    $email_data['school_name'] = $schoolName; 
                    //$this->schoolId =
                    if ($p_email !='') {
                        //dd($p_email);
                        $email_to=str_replace(',','|',$p_email);
                        $email_to=str_replace(';','|',$p_email);
                        
                        $email_to_arr = explode("|", $p_email);
                        
                        //$cnt=sizeof($email_to_arr);
                        foreach ($email_to_arr  as &$value) {
                            if ($value !="") {
                                //$result_data->emails[]=$p_email;
                                $email_data['email'] = $value;
                                //dd($p_email);   
                                if ($this->emailSend($email_data,$p_template_code)) {
                                
                                    $result = array(
                                        'status' => true,
                                        'message' => __('We sent you an activation link. Check your email and click on the link to verify.'),
                                    );
                                }  else {
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
                            
                            if ($this->emailSend($email_data,$p_template_code)) {
                           
                                $result = array(
                                    'status' => true,
                                    'message' => __('We sent you an activation link. Check your email and click on the link to verify.'),
                                );
                            }  else {
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
            } else{
                $result = array('status'=>true,'msg'=>__('email sent'));
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
    public function student_invoice_list(Request $request,$schoolId = null)
    {
        $user = $request->user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $invoice_type_all = config('global.invoice_type');
        $payment_status_all = config('global.payment_status');
        $invoice_status_all = config('global.invoice_status');


        $studentEvents = DB::table('events')
        ->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')
        ->leftJoin('school_student', 'school_student.student_id', '=', 'event_details.student_id')
        ->leftJoin('users', 'users.person_id', '=', 'event_details.student_id')
        ->select(
            'events.id as event_id',
            'event_details.student_id as person_id',
            'school_student.nickname as student_name',
            'users.profile_image_id as profile_image_id'
            )
        //->selectRaw('count(events.id) as invoice_items')
        ->where(
            [
                'events.school_id'=>$this->schoolId, 
                'event_details.billing_method' => "E",
                'events.is_active' => 1
            ]);
        
        $user_role = 'superadmin';
        if ($user->person_type == 'App\Models\Student') {
            $user_role = 'student';
        }
        if ($user->person_type == 'App\Models\Teacher') {
            $user_role = 'teacher';
            $studentEvents->where('events.teacher_id', $user->person_id);
        }

        $studentEvents->where(function ($query) {
            $query->where('event_details.is_sell_invoiced', '=', 0)
                ->orWhereNull('event_details.is_sell_invoiced');
            }
        );
        
        $dateS = Carbon::now()->startOfMonth()->subMonth(3)->format('Y-m-d');
        

        $studentEvents->where('events.date_start', '>=', $dateS);
        $studentEvents->distinct('events.id');
        $studentEvents->groupBy('events.id');
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
       
        $allStudentEvents=[];
        foreach ($allEvents as $key => $value) {
            $profile_image = !empty($value->profile_image_id) ? AttachedFile::find($value->profile_image_id) : null ;
            if (!empty($profile_image)) {
                $value->profile_image = $profile_image->path_name;
            }
            $value->student_full_name = "";
            if (!empty($value->person_id)) {
                $student = Student::find($value->person_id);
                $value->student_full_name = $student->full_name;
            }
            
            $allStudentEvents[] = $value;
        }
        return view('pages.invoices.student_list',compact('allStudentEvents','schoolId','invoice_type_all','payment_status_all','invoice_status_all'));
    }







    /**
     *  List of teacher invoices by school
     * 
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-06-02
     */
    public function teacher_invoice_list(Request $request,$schoolId = null)
    {
        $user = $request->user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $invoice_type_all = config('global.invoice_type');
        $payment_status_all = config('global.payment_status');
        $invoice_status_all = config('global.invoice_status');


        
        $studentEvents = DB::table('events')
        ->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')
        ->leftJoin('school_teacher', 'school_teacher.teacher_id', '=', 'event_details.teacher_id')
        ->leftJoin('users', 'users.person_id', '=', 'event_details.teacher_id')
        ->select(
            'events.id as event_id',
            'event_details.teacher_id as person_id',
            'school_teacher.nickname as teacher_name',
            'users.profile_image_id as profile_image_id'
            )
        //->selectRaw('count(events.id) as invoice_items')
        ->where(
            [
                'events.school_id'=>$this->schoolId, 
                //'event_details.billing_method' => "E",
                'events.is_active' => 1
            ]);
        
        $user_role = 'superadmin';
        if ($user->person_type == 'App\Models\Student') {
            $user_role = 'student';
        }
        if ($user->person_type == 'App\Models\Teacher') {
            $user_role = 'teacher';
            //$studentEvents->where('events.teacher_id', $user->person_id);
        }
        $studentEvents->where('event_details.is_buy_invoiced', '=', 0);

        // $studentEvents->where(function ($query) {
        //     $query->where('event_details.is_buy_invoiced', '=', 0)
        //         ->orWhereNull('event_details.is_sell_invoiced');
        //     }
        // );
        
        $dateS = Carbon::now()->startOfMonth()->subMonth(1)->format('Y-m-d');
        $dateEnd = Carbon::now()->format('Y-m-d');
        

        $studentEvents->where('events.date_start', '>=', $dateS);
        $studentEvents->where('events.date_end', '<=', $dateEnd);
        $studentEvents->distinct('events.id');
        $studentEvents->groupBy('events.id');

        $allEvents = DB::table(DB::raw('(' . $studentEvents->toSql() . ') as custom_table'))
            ->select(
                'custom_table.person_id as person_id',
                'custom_table.teacher_name as teacher_name',
                'custom_table.profile_image_id as profile_image_id'
            )
            ->selectRaw('count(custom_table.event_id) as invoice_items')
            ->mergeBindings($studentEvents)
            ->groupBy('custom_table.person_id')
            
            ->get();
       
        //dd($allEvents);
       
        $allTeacherEvents=[];
        foreach ($allEvents as $key => $value) {
            $profile_image = !empty($value->profile_image_id) ? AttachedFile::find($value->profile_image_id) : null ;
            if (!empty($profile_image)) {
                $value->profile_image = $profile_image->path_name;
            }
            $value->teacher_full_name = "";
            if (!empty($value->person_id)) {
                $teacher = Teacher::find($value->person_id);
                $value->teacher_full_name = $teacher->full_name;
            }

            
            
            $allTeacherEvents[] = $value;
        }
        return view('pages.invoices.teacher_list',compact('allTeacherEvents','schoolId','invoice_type_all','payment_status_all','invoice_status_all'));
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
            $p_person_id=trim($data['p_person_id']);
            $p_billing_period_start_date=trim($data['p_billing_period_start_date']);
            $p_billing_period_end_date=trim($data['p_billing_period_end_date']);
            //exit();


            $studentEvents = DB::table('events')
            ->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')
            ->leftJoin('school_student', 'school_student.student_id', '=', 'event_details.student_id')
            ->leftJoin('school_teacher', 'school_teacher.teacher_id', '=', 'event_details.teacher_id')
            ->leftJoin('users', 'users.person_id', '=', 'event_details.student_id')
            ->select(
                'events.id as event_id',
                //'DATE_FORMAT(str_to_date(concat('01/',month(events.date_start),'/',year(events.date_start)),'%d/%m/%Y'),"%d/%m/%Y") as FirstDay', 
                //'date_format(first_day(events.date_start),"%d/%m/%Y") as FirstDay',
				//'date_format(last_day(events.date_start),"%d/%m/%Y") as Lastday',
                // 'concat("Semaine ",week(events.date_start,5)) as week_name', 
                // 'week(events.date_start,5) week_no',
                // 'DATE_FORMAT(events.date_start,"%d/%m/%Y") date_start', 
                // 'DATE_FORMAT(events.date_start,"%H:%i") time_start',
                
                'events.duration_minutes as duration_minutes',
                'event_details.buy_total as buy_total',
                'event_details.sell_total as sell_total',
                'school_student.nickname as student_name',
                'school_teacher.nickname as teacher_name',
                'events.title as title',
                'events.event_type as event_type',
                'events.event_category as category_id',
                //'events.event_category as category_name',
                'events.is_paying as is_paying',
                //'events.price_id as price_id',
                'event_details.is_locked as ready_flag',
                'event_details.participation_id as participation_id',
                'event_details.is_buy_invoiced as is_buy_invoiced',
                'event_details.is_sell_invoiced as is_sell_invoiced',
                'event_details.price_currency as price_currency',
                'event_details.costs_1 as costs_1',
                'event_details.costs_2 as costs_2'
                //'events.is_locked as ready_flag'
                
                // 'users.profile_image_id as profile_image_id'
                )
            ->selectRaw('DATE_FORMAT(str_to_date(concat("01/",month(events.date_start),"/",year(events.date_start)),"%d/%m/%Y"),"%d/%m/%Y") as FirstDay')
            ->selectRaw('DATE_FORMAT(str_to_date(concat("30/",month(events.date_start),"/",year(events.date_start)),"%d/%m/%Y"),"%d/%m/%Y") as Lastday')
            ->selectRaw('DATE_FORMAT(events.date_start,"%H:%i") time_start')
            ->selectRaw('DATE_FORMAT(events.date_start,"%d/%m/%Y") date_start')
            ->selectRaw('week(events.date_start,5) week_no')
            ->selectRaw('concat("Semaine ",week(events.date_start,5)) as week_name')
            
            //->selectRaw('count(events.id) as invoice_items')
            ->where(
                [
                    //date(date_start) between str_to_date(p_billing_period_start_date,'%d/%m/%Y')
                    //and str_to_date(p_billing_period_end_date,'%d/%m/%Y')
                    //'event_details.teacher_id'=>$this->schoolId, 
                    'event_details.student_id'=>$p_person_id, 
                    'event_details.billing_method' => "E",
                    'events.is_active' => 1
                ]);
            
            $user_role = 'superadmin';
           // dd($user);
            if ($user->person_type == 'App\Models\Student') {
                $user_role = 'student';
            }
            if ($user->person_type == 'App\Models\Teacher') {
                $user_role = 'teacher';
                $studentEvents->where('events.teacher_id', $user->person_id);
            }

            $studentEvents->where(function ($query) {
                $query->where('event_details.is_sell_invoiced', '=', 0)
                    ->orWhereNull('event_details.is_sell_invoiced');
                }
            );
            
            $dateS = Carbon::now()->startOfMonth()->subMonth(3)->format('Y-m-d');
            

            // and date(date_start) between str_to_date(p_billing_period_start_date,'%d/%m/%Y') 
		    // and str_to_date(p_billing_period_end_date,'%d/%m/%Y')


            
            $studentEvents->whereBetween('events.date_start', [$p_billing_period_start_date, $p_billing_period_end_date]);
                

            //$studentEvents->where('events.date_start', '>=', $dateS);
            $studentEvents->orderBy('events.date_start','desc');
            //By
            $studentEvents->distinct('events.id');
            $data = $studentEvents->get();
            //$studentEvents->groupBy('events.id');
            //dd($studentEvents->toSql());
            //dd($data);

            $result = array(
                'status' => true,
                'message' => __('We got a list of invoice'),
                'data' => $data,
                //'no_of_teachers' =>$no_of_teachers
            );

            //$p_auto_id = trim($data['p_auto_id']);
            return response()->json($result);
        }
        catch (Exception $e) {
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
            $user = $request->user();
            $p_person_id=trim($data['p_person_id']);
            $p_billing_period_start_date=trim($data['p_billing_period_start_date']);
            $p_billing_period_end_date=trim($data['p_billing_period_end_date']);
            //exit();


            $studentEvents = DB::table('events')
            ->join('event_details', 'events.id', '=', 'event_details.event_id')
            ->leftJoin('event_categories', 'event_categories.id', '=', 'events.event_category')
            ->leftJoin('teachers', 'teachers.id', '=', 'events.teacher_id')
            ->leftJoin('students', 'students.id', '=', 'event_details.student_id')
            
            ->leftJoin('users', 'users.person_id', '=', 'events.teacher_id')
            ->leftJoin('schools', 'schools.id', '=', 'events.school_id')
            
            ->select(
                'events.id as event_id',
                //'DATE_FORMAT(str_to_date(concat('01/',month(events.date_start),'/',year(events.date_start)),'%d/%m/%Y'),"%d/%m/%Y") as FirstDay', 
                //'date_format(first_day(events.date_start),"%d/%m/%Y") as FirstDay',
				//'date_format(last_day(events.date_start),"%d/%m/%Y") as Lastday',
                // 'concat("Semaine ",week(events.date_start,5)) as week_name', 
                // 'week(events.date_start,5) week_no',
                // 'DATE_FORMAT(events.date_start,"%d/%m/%Y") date_start', 
                // 'DATE_FORMAT(events.date_start,"%H:%i") time_start',
                
                'events.duration_minutes as duration_minutes',
                'event_details.buy_total as buy_total',
                'event_details.sell_total as sell_total',
                //'students.nickname as student_name',
                //'teachers.nickname as teacher_name',
                'events.title as title',
                'events.event_type as event_type',
                'events.event_category as category_id',
                //'events.event_category as category_name',
                'events.is_paying as is_paying',
                //'events.price_id as price_id',
                'event_details.is_locked as ready_flag',
                'event_details.participation_id as participation_id',
                'event_details.is_buy_invoiced as is_buy_invoiced',
                'event_details.is_sell_invoiced as is_sell_invoiced',
                'event_details.price_currency as price_currency',
                'event_details.costs_1 as costs_1',
                'event_details.costs_2 as costs_2'
                //'events.is_locked as ready_flag'
                
                // 'users.profile_image_id as profile_image_id'
                )
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
                    //date(date_start) between str_to_date(p_billing_period_start_date,'%d/%m/%Y')
                    //and str_to_date(p_billing_period_end_date,'%d/%m/%Y')
                    //'event_details.teacher_id'=>$this->schoolId, 
                    'events.teacher_id'=>$p_person_id, 
                    'event_categories.invoiced_type' => "S",
                    //'event_details.billing_method' => "E",
                    'events.is_active' => 1
                ]);
            $studentEvents->where('events.is_paying','>', 0);
            $studentEvents->where('event_details.visibility_id','>', 0);
            $studentEvents->whereNotIn('events.event_type',[100]);
            $studentEvents->whereNotNull('events.date_start');

            
            
            $user_role = 'superadmin';
           // dd($user);
           

            // $studentEvents->where(function ($query) {
            //     $query->where('event_details.is_sell_invoiced', '=', 0)
            //         ->orWhereNull('event_details.is_sell_invoiced');
            //     }
            // );
            
            

            // and date(date_start) between str_to_date(p_billing_period_start_date,'%d/%m/%Y') 
		    // and str_to_date(p_billing_period_end_date,'%d/%m/%Y')

            
            // $p_billing_period_start_date ='2022-05-01';
            // $p_billing_period_end_date = '2022-06-09';
            //->whereRaw('email like "%a%"')
            $qq = "DATE_FORMAT(STR_TO_DATE(events.date_start,'%Y-%m-%d'),'%d/%m/%Y') BETWEEN '".$p_billing_period_start_date."' AND '".$p_billing_period_end_date."'";
            // exit();
            //$studentEvents->whereRaw("( DATE_FORMAT(STR_TO_DATE(events.date_start,'%Y-%m-%d'),'d%/%m/%Y') BETWEEN '".$p_billing_period_start_date."' AND '".$p_billing_period_end_date."' )");
            $studentEvents->whereRaw($qq);

            //$studentEvents->whereBetween('events.date_start', [$p_billing_period_start_date, $p_billing_period_end_date]);
                

            //$studentEvents->where('events.date_start', '>=', $dateS);
            $studentEvents->orderBy('events.date_start','desc');
            //By
            $studentEvents->distinct('events.id');
            
            //$studentEvents->groupBy('events.id');
            //dd($studentEvents->toSql());
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
        }
        catch (Exception $e) {
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
        $provinces = config('global.provinces'); 
        $invoice->invoice_type_name = $invoice_type_all[$invoice->invoice_type];
        $invoice->invoice_status_name = $invoice_status_all[$invoice->invoice_status];
        

        if ($invoice->invoice_type==1) {
            $invoice->person_id = $invoice->client_id;
        } else{
            $invoice->person_id = $invoice->seller_id;
        }

        // $invoiceCurrency = InvoiceItem::active()->where('invoice_id',$invoice->id)->get()->pluck('price_currency')->join(',');
        $invoice->invoice_items = InvoiceItem::active()->where('invoice_id',$invoice->id)->get();
        // $result_data->invoice_price = $invoiceCurrency.''.round($result_data->total_amount,2);
            
        // if ($invoice->amount_discount_1  > 0) {
        //     $invoice->disc_text = '1, disc1, disc1_amt, 0';
        //     # code...
        // }
        $genders = config('global.gender');
        $countries = Country::active()->get();
        return view('pages.invoices.add', [
            'title' => 'Invoice',
            'pageInfo'=>['siteTitle'=>'']
        ])->with(compact('genders','countries','provinces'));
    } 
        
    /**
     *  AJAX action to send email for pay reminder
     * 
     * @return json
     * @author Tarikul 90
     * @version 0.1 written in 2022-05-27
     */

    public function manualInvoice()
    {
        $genders = config('global.gender');
        $provinces = config('global.provinces'); 
        $countries = Country::active()->get();
        return view('pages.invoices.manual_invoice', [
            'title' => 'Invoice',
            'pageInfo'=>['siteTitle'=>'']
        ])->with(compact('genders','countries','provinces'));
    } 
}