<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Location;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolTeacher;
use App\Models\SchoolStudent;
use App\Models\Event;
use App\Models\EventDetails;
use App\Models\EventCategory;
use App\Models\LessonPrice;
use App\Models\Currency;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class AgendaController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }

     /**
     * Agenda calendar
     * @return Response
    */
    public function index(Request $request,$schoolId = null)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ;
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            $schoolId = 0;
        }
        // This part is copied from add lesson
        $eventCategoryList = EventCategory::active()->where('school_id',$schoolId)->get();
        $professors = SchoolTeacher::active()->where('school_id',$schoolId)->get();
        $studentsbySchool = SchoolStudent::active()->where('school_id',$schoolId)->get();
        $lessonPrice = LessonPrice::active()->get();
        // $currency = Currency::active()->ByCountry($school->country_code)->get();
        $currency = [];
        // end the part
        $user_role = 'superadmin';
        $schools = School::orderBy('id')->get();
        if ($user->person_type == 'App\Models\Student') {
            $user_role = 'student';
            $schools = $user->schools();
        }
        if ($user->person_type == 'App\Models\Teacher') {
            $user_role = 'teacher';
            $schools = $user->schools();
            if ($user->isSchoolAdmin() || $user->isTeacherAdmin()) {
                $user_role = 'admin_teacher';
            }
            // if ($user->isTeacherAdmin()) {
            //     $user_role = 'admin_teacher_coach';
            // }
        }


        $alllanguages = Language::orderBy('sort_order')->get();
        $locations = Location::orderBy('id')->get();
        $students = Student::orderBy('id')->get();
        $teachers = Teacher::orderBy('id')->get();

        $eventCategories = EventCategory::active()->where('school_id', $schoolId)->orderBy('id')->get();


        $event_types_all = config('global.event_type');
        $event_types = [];

        foreach ($event_types_all as $key => $value) {

            if ($key == 10) {
                if ($eventCategories) {
                    foreach ($eventCategories as $cat => $eventCat) {
                        $event_types[$key.'-'.$eventCat->id] = trim($value.' : '.$eventCat->title);
                     }
                }
                $event_types[$key]= $value;


            } else{
                $event_types[$key]= $value;
            }
        }
        //dd($event_types);

        //$eventData = Event::active()->where('school_id', $schoolId)->get();
        $eventData = Event::active()->get();
        $data = $request->all();

        $user_role = 'superadmin';
        if ($user->person_type == 'App\Models\Student') {
            $user_role = 'student';
        }
        if ($user->person_type == 'App\Models\Teacher') {
            $user_role = 'teacher';
        }
        if ($user->isSchoolAdmin() || $user->isTeacherAdmin()) {
            $user_role = 'admin_teacher';
        }
        if ($user->isTeacherAll()) {
            $user_role = 'teacher_all';
        }
        if ($user->isTeacherMedium() || $user->isTeacherMinimum() || $user_role =='teacher' ) { 
            $user_role = 'teacher';
        }
        //$eventData = Event::active()->where('school_id', $schoolId)->get();

        $data['user_role'] = $user_role;
        $data['person_id'] = $user->person_id;
        

        //dd($eventData);
        $events = array();
       
        //dd($events);
        $events =json_encode($events);
        //unset($event_types[10]);
        return view('pages.agenda.index')->with(compact('schools','school','schoolId','user_role','students','teachers','locations','alllanguages','events','event_types','event_types_all','eventCategoryList','professors','studentsbySchool','lessonPrice','currency'));

    }



    /**
     *  AJAX confirm event
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-04-09
     */
    public function confirmEvent(Request $request)
    {
        $result = array(
            'status' => 'failed',
            'message' => __('failed to validate'),
        );
        try {
            $data = $request->all();


            $p_event_auto_id = $data['p_event_auto_id'];
            $eventUpdate = [
                'is_locked' => 1
            ];
            if (isset($data['unlock'])) {
                $eventUpdate = [
                    'is_locked' => 0
                ];
            }
            $eventData = Event::where('id', $p_event_auto_id)->update($eventUpdate);


            $eventDetail = [
                'is_locked' => 1,
            ];
            if (isset($data['unlock'])) {
                $eventDetail = [
                    'is_locked' => 0
                ];
            }
            $eventdetails = EventDetails::where('event_id', $p_event_auto_id)->get();
            foreach ($eventdetails as $key => $eventdetail) {
                $eventDetailPresent = [
                    'is_locked' => 1,
                    'participation_id' => 200,
                ];
                if (isset($data['unlock'])) {
                    $eventDetailPresent = [
                        'is_locked' => 0,
                        'participation_id' => 200,
                    ];
                }
                $eventDetailAbsent = [
                    'is_locked' => 1,
                    'participation_id' => 199,
                ];
                if (isset($data['unlock'])) {
                    $eventDetailAbsent = [
                        'is_locked' => 0,
                        'participation_id' => 200,
                    ];
                }

                $eventdetail = $eventdetail->update($eventDetailPresent);
                // if ($eventdetail->participation_id == 0) {

                // } else {
                //     $eventdetail = $eventdetail->update($eventDetailAbsent);
                // }

                if ($eventdetail)
                {
                    $result = array(
                        "status"     => 'success',
                        'message' => __('Confirmed'),
                    );
                }
            }


            return response()->json($result);

        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }

    }

    /**
     *  AJAX copy event
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-04-14
     */
    public function copyPasteEvent(Request $request,$schoolId = null, Event $event)
    {
        $user = Auth::user();
        $data = $request->all();
        // $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ;
        // $school = School::active()->find($schoolId);
        // if (empty($school)) {
        //     return redirect()->route('schools')->with('error', __('School is not selected'));
        // }

        $result = array(
            "status"     => 1,
            'message' => __('failed to send email'),
        );
        try {



            // $p_app_id=$_SESSION['global_app_id'];
            // $p_school_id=$_SESSION['global_school_id'];
            // $p_lang_id=$_SESSION['Language'];



            $data['school_id'] = trim($data['school_id']);
            $data['event_type']= trim($data['event_type']);
            $data['teacher_id']= trim($data['teacher_id']);
            $data['student_id']= trim($data['student_id']);
            $view_mode= trim($data['view_mode']);

            //dd($data);
            //$query = new Event;
            $eventData = $event->filter_for_copy($data);

            $eventData = $eventData->get();



            $target_start_date= trim($data['target_start_date']);
            // exit();
            // $target_end_date= trim($data['target_end_date']);

            $target_start_date = str_replace('/', '-', $target_start_date);
            // exit();
            // $target_end_date = str_replace('/', '-', $target_end_date);

            $source_start_date= trim($data['source_start_date']);
            $source_end_date= trim($data['source_end_date']);

            if ($view_mode =='AGENDADAY') {
                $day_diff = 0;


            } else {
                $now = strtotime($target_start_date);
                $your_date = strtotime($source_start_date);
                $datediff = $now - $your_date;
                $day_diff = round($datediff / (60 * 60 * 24));

                //$day_diff = $target_start_date-$source_start_date; //= 10
            }



            // $zone= trim($data['zone']);



            // $events = array();
            foreach ($eventData as $key => $fetch) {

                //echo $fetch->date_start;
                if ($day_diff ==0) {
                    $date_start = strtotime($fetch->date_start);
                    $date_start =$target_start_date.' '.date('H:i:s', $date_start);

                    $date_end = strtotime($fetch->date_end);
                    $date_end =$target_start_date.' '.date('H:i:s', $date_end);
                }
                else { // $day_diff add

                    //$myDate = "2014-01-16";
                    $nDays = $day_diff;
                    $date_start = strtotime($fetch->date_start . '+ '.$nDays.'days');
                    $date_start = date('Y-m-d H:i:s', $date_start); //format new date
                    $nDays = $day_diff;
                    $date_end = strtotime($fetch->date_end . '+ '.$nDays.'days');
                    $date_end = date('Y-m-d H:i:s', $date_end); //format new date


                    // $date_start = strtotime($fetch->date_start);
                    // $date_start =$target_start_date.' '.date('H:i:s', $date_start);

                    // $date_end = strtotime($fetch->date_end);
                    // $date_end =$target_start_date.' '.date('H:i:s', $date_end);
                }

                //exit();
                $data = [
                    'title' => $fetch->title,
                    'school_id' => $fetch->school_id,
                    'event_type' => $fetch->event_type,
                    'date_start' => $date_start,
                    'date_end' => $date_end,
                    'duration_minutes' => $fetch->duration_minutes,
                    'price_currency' => $fetch->price_currency,
                    'price_amount_buy' => $fetch->price_amount_buy,
                    'price_amount_sell' => $fetch->price_amount_sell,
                    'fullday_flag' => $fetch->fullday_flag,
                    'allDay' => ($fetch->fullday_flag == "Y") ? true : false,
                    'description' => $fetch->description,
                    'location_id' => $fetch->location_id,
                    'teacher_id' => $fetch->teacher_id,
                    'event_price' => $fetch->event_price,
                    'event_price' => $fetch->event_price
                ];
                $eventData = Event::create($data);

                $eventDetailsStudentId = EventDetails::active()->where('event_id', $fetch->id)->get()->toArray();


                foreach($eventDetailsStudentId as $std){
                    $dataDetails = [
                        'event_id'   => $eventData->id,
                        'teacher_id' => $fetch->teacher_id,
                        'student_id' => $std['student_id'],
                        'buy_price' => $fetch->price_amount_buy,
                        'sell_price' => $fetch->price_amount_sell,
                    ];
                    $eventDetails = EventDetails::create($dataDetails);
                }


            }
            //dd($eventData);







            // $event = Event::create($data);



            //$p_event_auto_id = $data['p_event_auto_id'];
            // $data['school_id']
            // $p_user_id = Auth::user()->id;




                $result = array(
                    "status"     => 0,
                    'message' => __('Confirmed'),
                );


            return response()->json($result);

        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }

    }


    /**
     *  AJAX confirm event
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-04-09
     */
    public function getEvent(Request $request,$schoolId = null, Event $event)
    {
        $data = $request->all();

        $user = Auth::user();
        // $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ;
        // $school = School::active()->find($schoolId);
        // if (empty($school)) {
        //     return redirect()->route('schools')->with('error', __('School is not selected'));
        // }
        $event_types = config('global.event_type');
        $user_role = 'superadmin';
        if ($user->person_type == 'App\Models\Student') {
            $user_role = 'student';
        }
        if ($user->person_type == 'App\Models\Teacher') {
            $user_role = 'teacher';
        }
        if ($user->isSchoolAdmin() || $user->isTeacherAdmin()) {
            $user_role = 'admin_teacher';
        }
        if ($user->isTeacherAll()) {
            $user_role = 'teacher_all';
        }
        if ($user->isTeacherMedium() || $user->isTeacherMinimum() || $user_role =='teacher' ) { 
            $user_role = 'teacher';
        }
        //$eventData = Event::active()->where('school_id', $schoolId)->get();

        $data['user_role'] = $user_role;
        $data['person_id'] = $user->person_id;
        //dd($data);

        //$query1 = new Event;
        $eventData = $event->filter($data);
        //dd($eventData->count());
        $eventData = $eventData->get();



        $events = array();
        foreach ($eventData as $key => $fetch) {
            $e = array();
            $e['id'] = $fetch->id;

            $e['title']=(substr($fetch->title,0,1)==',') ? substr($fetch->title,1) : substr($fetch->title,0);
            $e['start'] = $fetch->date_start;
            $e['end'] = $fetch->date_end;
            if (isset($data['zone'])) {
                $e['start'] = $fetch->date_start.$data['zone'];
                $e['end'] = $fetch->date_end.$data['zone'];

            }


            $allday = ($fetch->fullday_flag == "Y") ? true : false;
            $e['allDay'] = $allday;
            $e['teacher_name'] = null;
            if (isset($fetch->teacher)) {
                $e['teacher_name'] = $fetch->teacher['firstname'];
                $schoolTeacher = SchoolTeacher::active()->where('teacher_id',$fetch->teacher_id)->where('school_id',$fetch->school_id)->first();
                if (!empty($schoolTeacher)) {
                    $e['backgroundColor'] = $schoolTeacher->bg_color_agenda;
                }
            }
            $e['event_category_name'] = '';
            $eventCategory = EventCategory::find($fetch->event_category);

            if (!empty($eventCategory)) {
                $e['event_category'] = $fetch->event_category;
                $e['event_category_name'] = trim($eventCategory->title);

            }
            $e['event_type'] = $fetch->event_type;
            $e['event_location'] = $fetch->location_id;

            $event_type_name = $event_types[$e['event_type']];
            if ($e['event_type'] == 10) {
                $event_type_name = $event_types[$e['event_type']].' : '.$e['event_category_name'];
            }
            $e['event_type_name'] = $event_type_name;
            $e['event_school_id'] = (is_null($fetch->school_id) ? 0 : $fetch->school_id) ;
            $e['event_school_name'] = $fetch->school['school_name'];

            $e['event_category_name'] ='';
            $e['cours_name'] = '';
            $e['text_for_search']='';
            $e['tooltip']='';
            $e['content'] ='';
            if($fetch->event_mode==0){
                $e['event_mode_desc'] = 'Draft';
            } else {
                $e['event_mode_desc'] = '';
            }


            $e['cours_name'] = $e['event_type_name'].'('.$e['event_category_name'].')';
            $e['text_for_search']=strtolower($e['event_type_name'].$e['cours_name'].' '.$e['teacher_name'].' - '.$e['title']);
            //$e['tooltip']=$e['event_mode_desc'].$e['cours_name'].' Duration: '.$fetch->duration_minutes.' '.$e['teacher_name'].' - '.$e['title'];


            $eventDetailsStudentId = EventDetails::active()->where('event_id', $fetch->id)->get()->toArray();
            $student_name ='';
            $i=0;
            foreach($eventDetailsStudentId as $std){
                $student = Student::find($std['student_id']);
                if ($student) {
                    $student_name .= $student->firstname;
                    if ($i!=0 && $i!=count($eventDetailsStudentId)) {
                        $student_name .=',';
                    }
                    $i++;
                }
            }
            $e['tooltip']=$e['event_type_name'].':'.$e['title'].' <br /> Teacher: '.$e['teacher_name'].' <br /> Students: '.$student_name.' <br /> Duration: '.$fetch->duration_minutes;

            $e['content'] = ($e['cours_name']);


            $e['teacher_id'] = $fetch->teacher_id;
            $e['duration_minutes'] = $fetch->duration_minutes;
            $e['no_of_students'] = $fetch->no_of_students;
            $e['is_locked'] = $fetch->is_locked;
            $eventDetailsStudentId = EventDetails::active()->where('event_id', $fetch->id)->get()->pluck('student_id')->join(',');
            $e['student_id_list'] = $eventDetailsStudentId;
            $e['event_auto_id'] = ($fetch->id);
            $e['event_mode'] = $fetch->event_mode;


            if (now()>$fetch->date_end) {
                $e['can_lock'] = 'Y';
            } else{
                $e['can_lock'] = 'N';
            }
            $e['description'] = $e['title'];
            $e['location'] = (is_null($fetch->location_id) ? 0 : $fetch->location_id) ;
            $e['category_id'] = (is_null($fetch->event_category) ? 0 : $fetch->event_category) ;
            $e['created_user'] = $fetch->created_by;

            $page_name='';
            if ($fetch->is_locked == 1){
                $action_type='view';
                if ($fetch->event_type==10) { //lesson
                    $page_name='/'.$fetch->school_id.'/view-lesson/'.$fetch->id;
                }
                if ($fetch->event_type==100) { //event
                    $page_name='/'.$fetch->school_id.'/view-event/'.$fetch->id;
                }
                if ($fetch->event_type==50) { //coach time off
                    $page_name='/'.$fetch->school_id.'/view-coach-off/'.$fetch->id;
                }
                if ($fetch->event_type==51) { //student time off
                    $page_name='/'.$fetch->school_id.'/view-student-off/'.$fetch->id;
                }
            }
            else {
                $action_type='edit';
                if ($fetch->event_type==10) { //lesson
                    $page_name='/'.$fetch->school_id.'/edit-lesson/'.$fetch->id;
                }
                if ($fetch->event_type==100) { //event
                    $page_name='/'.$fetch->school_id.'/edit-event/'.$fetch->id;
                }
                if ($fetch->event_type==50) { //coach time off
                    $page_name='/'.$fetch->school_id.'/edit-coach-off/'.$fetch->id;
                }
                if ($fetch->event_type==51) { //student time off
                    $page_name='/'.$fetch->school_id.'/edit-student-off/'.$fetch->id;
                }

                if ($user_role == 'student'){
                    $action_type='view';
                    if ($fetch->event_type==10) { //lesson
                        $page_name='/'.$fetch->school_id.'/view-lesson/'.$fetch->id;
                    }
                    if ($fetch->event_type==100) { //event
                        $page_name='/'.$fetch->school_id.'/view-event/'.$fetch->id;
                    }
                    if ($fetch->event_type==50) { //coach time off
                        $page_name='/'.$fetch->school_id.'/view-coach-off/'.$fetch->id;
                    }
                    if ($fetch->event_type==51) { //student time off
                        $page_name='/'.$fetch->school_id.'/view-student-off/'.$fetch->id;
                    }
                }
                if ($user_role == 'teacher'){
                    if (($user->id == $fetch->teacher_id)){
                        $action_type='edit';
                        if ($fetch->event_type==10) { //lesson
                            $page_name='/'.$fetch->school_id.'/edit-lesson/'.$fetch->id;
                        }
                        if ($fetch->event_type==100) { //event
                            $page_name='/'.$fetch->school_id.'/edit-event/'.$fetch->id;
                        }
                        if ($fetch->event_type==50) { //coach time off
                            $page_name='/'.$fetch->school_id.'/edit-coach-off/'.$fetch->id;
                        }
                        if ($fetch->event_type==51) { //student time off
                            $page_name='/'.$fetch->school_id.'/edit-student-off/'.$fetch->id;
                        }
                    }else{
                        $action_type='view';
                        if ($fetch->event_type==10) { //lesson
                            $page_name='/'.$fetch->school_id.'/view-lesson/'.$fetch->id;
                        }
                        if ($fetch->event_type==100) { //event
                            $page_name='/'.$fetch->school_id.'/view-event/'.$fetch->id;
                        }
                        if ($fetch->event_type==50) { //coach time off
                            $page_name='/'.$fetch->school_id.'/view-coach-off/'.$fetch->id;
                        }
                        if ($fetch->event_type==51) { //student time off
                            $page_name='/'.$fetch->school_id.'/view-student-off/'.$fetch->id;
                        }
                    }
                }
                /* only own vacation entry can be edited by user - Teacher */
                if ($fetch->event_type == 50) {
                    if ( ($user->id == $fetch->created_by) || ($user->id == $fetch->teacher_id) || ($user->id == $fetch->teacher_id )) {
                        $action_type='edit';

                            $page_name='/'.$fetch->school_id.'/edit-coach-off/'.$fetch->id;

                    } else {
                        $action_type='view';

                            $page_name='/'.$fetch->school_id.'/view-coach-off/'.$fetch->id;


                    }
                }
                /* only own vacation entry can be edited by user - Student */
                if ($fetch->event_type == 51) {
                    if (($user->id == $fetch->created_by) || ($user->id == $fetch->student_id) || ($user->id == $fetch->student_id )) {
                        $action_type='edit';

                            $page_name='/'.$fetch->school_id.'/edit-student-off/'.$fetch->id;

                    } else {
                        $action_type='view';

                            $page_name='/'.$fetch->school_id.'/view-student-off/'.$fetch->id;


                    }

                }

            };
            $e['url'] = $page_name;

            $e['action_type'] = $action_type;
            // $e['duration_minutes'] = 90;

            //$e['title']="dsadasdasd";
            $e['title_extend']=$e['event_type_name'].':'.$e['title'].' <br /> Teacher: '.$e['teacher_name'].' <br /> Students: '.$student_name.' <br /> Duration: '.$fetch->duration_minutes;
            // $e['start'] = "2022-07-05 06:30:00";
            // $e['end'] = "2022-07-05 07:30:00";
            array_push($events, $e);
        }

        $events =json_encode($events);

        return response()->json($events);

    }


    /**
     *  AJAX get locations
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-04-09
     */
    public function getLocations(Request $request)
    {
        $data = $request->all();

        $user = Auth::user();
        $schoolId = $data['school_id'];

        $user_role = 'superadmin';
        if ($user->person_type == 'App\Models\Student') {
            $user_role = 'student';
        }
        if ($user->person_type == 'App\Models\Teacher') {
            $user_role = 'teacher';
        }
        $locations = Location::active()->where('school_id', $schoolId)->orderBy('id')->get();
        return $locations =json_encode($locations);

    }

    /**
     *  AJAX get students
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-04-09
     */
    public function getStudents(Request $request)
    {
        $data = $request->all();

        $user = Auth::user();
        $schoolId = $data['school_id'];

        $user_role = 'superadmin';
        if ($user->person_type == 'App\Models\Student') {
            $user_role = 'student';
        }
        if ($user->person_type == 'App\Models\Teacher') {
            $user_role = 'teacher';
        }
        $students = SchoolStudent::active()->where('school_id',$schoolId);
     
        if ($user_role =='student' ) { 
           $students->where('student_id',$user->person_id);
        }
        $students = $students->get();
        //$locations = Student::active()->where('school_id', $schoolId)->orderBy('id')->get();
        return $locations =json_encode($students);

    }

    /**
     *  AJAX confirm category
     *
     * @return json
     */
    public function getEventCategory(Request $request)
    {
        $data = $request->all();

        $user = Auth::user();
        $schoolId = $data['school_id'];

        $eventCat = EventCategory::active()->where('school_id', $schoolId)->get();

        return $eventCategory =json_encode($eventCat);

    }


    /**
     *  AJAX school currency
     *
     * @return json
     */
    public function getSchoolCurrency(Request $request)
    {
        $data = $request->all();

        $user = Auth::user();
        $schoolId = $data['school_id'];
        $school = School::active()->find($schoolId);
        if (!empty($school->country_code)) {
            $currency = Currency::active()->ByCountry($school->country_code)->get();
        }else{
            $currency = Currency::active()->get();
        }

        return $schoolCurrency =json_encode($currency);

    }

    /**
     *  AJAX get teachers
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-04-09
     */
    public function getTeachers(Request $request)
    {
        $data = $request->all();

        $user = Auth::user();
        $schoolId = $data['school_id'];

        $user_role = 'superadmin';
        if ($user->person_type == 'App\Models\Student') {
            $user_role = 'student';
        }
        if ($user->person_type == 'App\Models\Teacher') {
            $user_role = 'teacher';
        }
        if ($user->isSchoolAdmin() || $user->isTeacherAdmin()) {
            $user_role = 'admin_teacher';
        }
        if ($user->isTeacherAll()) {
            $user_role = 'teacher_all';
        }
        $professors = SchoolTeacher::active()->onlyTeacher()->where('school_id',$schoolId);
        if ($user->isTeacherMedium() || $user->isTeacherMinimum() || $user_role =='teacher' ) { 
           $professors->where('teacher_id',$user->person_id);
        }
        $professors = $professors->get();
        //$students = SchoolStudent::active()->where('school_id',$schoolId)->get();
        //$locations = Teacher::active()->where('school_id', $schoolId)->orderBy('id')->get();
        return $professors =json_encode($professors);

    }


     /**
     *  AJAX delete multiple event
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-04-10
     */
    public function deleteMultipleEvent(Request $request, Event $event)
    {
        $result = array(
            'status' => 'failed',
            'message' => __('failed to delete'),
        );
        try {
            $dataParam = $request->all();
            $data['p_from_date']= trim($dataParam['p_from_date']);
            $data['p_to_date']= trim($dataParam['p_to_date']);

            $data['school_id']= trim($dataParam['p_event_school_id']);
            $data['event_type']= trim($dataParam['p_event_type_id']);
           // $data['teacher_id']= trim($dataParam['p_teacher_id']);
            $data['student_id']= trim($dataParam['p_student_id']);
            $p_user_id=Auth::user()->id;
            $data['is_locked']=0;
            if (isset($data['p_from_date'])) {

                $query = $event->multiDelete($data);
                $eventData = $query->delete();
            }

            if ($eventData)
            {
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



     /**
     *  AJAX validate multiple event
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-05-06
     */
    public function validateMultipleEvent(Request $request, Event $event)
    {
        $result = array(
            'status' => 'failed',
            'message' => __('failed to validate'),
        );
        try {
            $data = $request->all();
            $param = [];
            $param['p_from_date']= trim($data['p_from_date']);
            $param['p_to_date']= trim($data['p_to_date']);

            //$param['school_id']= trim($data['p_event_school_id']);
            //$param['event_type']= trim($data['p_event_type_id']);
            //$param['teacher_id']= trim($data['p_teacher_id']);
            //$param['student_id']= trim($data['p_student_id']);
            //$p_user_id=Auth::user()->id;


            if (isset($param['p_from_date'])) {
                //$query = new Event;

                $eventUpdate = [
                    'is_locked' => 1
                ];
                $eventData = $event->multiValidate($param)->get();

                foreach ($eventData as $key => $p_event_auto_id) {

                    $eventUpdate = [
                        'is_locked' => 1
                    ];
                    $eventData = Event::where('id', $p_event_auto_id->id)->update($eventUpdate);


                    $eventDetailPresent = [
                        'is_locked' => 1,
                        'participation_id' => 200,
                    ];
                    $eventDetailAbsent = [
                        'is_locked' => 1
                        //'participation_id' => 199,
                    ];
                    $eventdetails = EventDetails::where('event_id', $p_event_auto_id->id)->get();
                    foreach ($eventdetails as $key => $eventdetail) {
                        if ($eventdetail->participation_id != 199) {
                            $eventdetail = $eventdetail->update($eventDetailPresent);
                        } else {
                            $eventdetail = $eventdetail->update($eventDetailAbsent);
                        }
                    }

                }


                // $eventDetail = [
                //     'participation_id' => ($eventdetail->participation_id == 0 || $eventdetail->participation_id == 100) ? 200 : $eventdetail->participation_id
                // ];
                // $eventdetail = $eventdetail->update($eventDetail);
            }
            //dd($eventData);
            if ($eventData)
            {
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



      /**
     *  icalendar export
     * @return Response
    */
    public function icalPersonalEvents(Request $request, $schoolId = null,Event $event)
    {
        $data = $request->all();

        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ;
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            $schoolId = 0;
        }
        $event_types = config('global.event_type');
        $user_role = 'superadmin';
        if ($user->person_type == 'App\Models\Student') {
            $user_role = 'student';
        }
        if ($user->person_type == 'App\Models\Teacher') {
            $user_role = 'teacher';
        }
        $data['school_id'] = $schoolId;
        $data['user_role'] = $user_role;
        $data['person_id'] = $user->person_id;
        $data['v_start_date']=Carbon::now()->format('Y-m-d');
        $data['v_end_date'] = Carbon::now()->addDays(365)->format('Y-m-d');


        $query = $event->filter_for_iCal($data);
        //dd($eventData->count());
        $result = $query->get();
        $dt_format='Ymd\THis\Z';


        //$v_end_date=date_add(v_start_date,interval 365 day);
        // the iCal date format. Note the Z on the end indicates a UTC timestamp.
        define('DATE_ICAL', $dt_format);  //soumen enabled timezone

        $file_name="PersonnelEvents";

		// if ($p_calendar_type == "disponibilites") {
		// 	$file_name="IcetimeEvents";
		// }
		// else {
		// 	$file_name=$p_calendar_type;
		// }

        $class="PUBLIC";
        $output = "BEGIN:VCALENDAR\nMETHOD:PUBLISH\nVERSION:2.0\nPRODID:-//www.sportlogin.ch//APGVApp//FR\n";


        $output .= "BEGIN:VTIMEZONE
        TZID:UTC
        BEGIN:STANDARD
        DTSTART:16010101T000000Z
        X-WR-TIMEZONE:UTC
        TZOFFSETFROM:+0000
        TZOFFSETTO:+0000
        END:STANDARD
        END:VTIMEZONE\n";

        /*
        $output .= "BEGIN:VTIMEZONE
        TZID:Europe/Zurich
        BEGIN:STANDARD
        DTSTART:16010101T000000
        X-WR-TIMEZONE:Europe/Zurich
        TZOFFSETFROM:+0100
        TZOFFSETTO:+0200
        END:STANDARD
        END:VTIMEZONE\n";
        */
        /*
        $output .= "BEGIN:VTIMEZONE
        TZID:Europe/London
        X-LIC-LOCATION:Europe/Zurich
        BEGIN:DAYLIGHT
        TZOFFSETFROM:+0100
        TZOFFSETTO:+0100
        TZNAME:BST
        DTSTART:19700329T010000
        RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU
        END:DAYLIGHT
        END:VTIMEZONE\n";
        */
        $event_type_all = config('global.event_type');
        $payment_status_all = config('global.payment_status');
        $invoice_status_all = config('global.invoice_status');
        $provinces = config('global.provinces');


        $seq=0;
        foreach ($result as $key => $row) {
        // while($row = mysql_fetch_array($result))
        // {
            $seq=$seq+1;
            $row->event_type_name = $event_type_all[$row->event_type];
            $row->subject_sumamry = $row->event_title.' '.$row->event_type_name.' '.$row->event_category_name;
            $subject_summary=$row->subject_sumamry;
            $id=$row->id;
            $uid=$row->id;    //uniqid();
            $status='CONFIRMED';
            $start=$row->start_datetime;
            $end=$row->end_datetime;
            $location_name=$row->location_name;
            //$teacher = Teacher::find($result->seller_id);
            if ($row->event_type == 51) {
               $parti = '';
            } else{
                $parti = ': Participant(s):'.$row->teacher_name;
            }
            $row->desc_text = $row->subject_sumamry.' '.$parti;
            $description=$row->desc_text;

                /* uncomment to enable timezone Z for UTC

                $output .="BEGIN:VEVENT\nDTSTART:".date("Ymd\THis\Z",strtotime($start))."
                DTEND:".date("Ymd\THis\Z",strtotime($end))."
                DTSTAMP:".date("Ymd\THis\Z")."

                DTSTART;TZID="Europe/Zurich":'.date($dt_format,strtotime($start)).'
                DTEND;TZID="Europe/Zurich":'.date($dt_format,strtotime($end)).'

                DTSTART:'.date($dt_format,strtotime($start)).'
                DTEND:'.date($dt_format,strtotime($end)).'
                */


            if ($row->fullday_flag == 'Y' ) {
            $output .='BEGIN:VEVENT
            DTSTART;VALUE=DATE:'.$start.'
            DTEND;VALUE=DATE:'.$end;
            } else {
            $output .='BEGIN:VEVENT
            DTSTART:'.$start.'
            DTEND:'.$end;
            }

            /*
            $output .='BEGIN:VEVENT
            DTSTART:'.$start.'
            DTEND:'.$end;
            */

            $output .='
            LOCATION:'.$location_name.'
            TRANSP: OPAQUE
            SEQUENCE:'.$seq.'
            UID:'.$uid.'
            DTSTAMP:'.date($dt_format).'
            SUMMARY:'.$subject_summary.'
            DESCRIPTION:'.$description.'
            PRIORITY:3
            CLASS:'.$class.'
            BEGIN:VALARM
            TRIGGER:-PT30M
            ACTION:DISPLAY
            DESCRIPTION:Reminder
            END:VALARM
            END:VEVENT
            ';

		}
        $output .="END:VCALENDAR";

        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$file_name.'.ics"');
        Header('Content-Length: '.strlen($output));
        //Header('Connection: close');
        echo $output;

        //echo json_encode(array('status'=>'success'));
    }


}
