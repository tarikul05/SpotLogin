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
use App\Models\User;
use App\Models\CalendarSetting;
use App\Models\EventDetails;
use App\Models\ParentStudent;
use App\Models\InvoiceItem;
use App\Models\EventCategory;
use App\Models\Availability;
use App\Models\LessonPrice;
use App\Models\LessonPriceTeacher;
use Illuminate\Support\Facades\Auth;
use App\Models\Currency;
use App\Models\School;
use Carbon\Carbon;
use App\Models\AgendaImport as AgendaImportModel;

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

        //$studentsbySchool = SchoolStudent::active()->where('school_id',$schoolId)->get();

        $students = SchoolStudent::active()
        ->where('school_id', $schoolId)
        ->get();

        $studentsbySchool = [];


        foreach ($students as $student) {
            $futureEventIds = EventDetails::where('student_id', $student->student_id)
                ->pluck('event_id');

                $availabilities = Availability::where('student_id', $student->student_id)->get();
                $student->availabilities = $availabilities;

            // Récupérer les détails de l'événement futur le plus proche de l'étudiant
            $futureEvent = Event::whereIn('id', $futureEventIds)
                ->where('event_type', 51)
                ->where(function ($query) {
                    $query->where('date_start', '>', now()) // Date de début future
                        ->orWhere(function ($subQuery) {
                            $subQuery->where('date_start', '<', now()) // Date de début passée
                                ->where('date_end', '>', now()); // Date de fin future
                        })->first();
                })
                ->orderBy('date_start', 'asc')
                ->get(); // Obtenir le premier résultat

            $student->dates = $futureEvent; // Stockez le premier événement futur ou null si aucun n'est trouvé
            array_push($studentsbySchool, $student);
        }



        $lessonPrice = LessonPrice::active()->get();
        $currency = [];
        // end the part
        $user_role = 'superadmin';
        $schools = School::orderBy('id')->get();

        if($user->person_id !=0 && $user->person_type !='SUPER_ADMIN' && $user->person_type !== 'App\Models\Parents'){
            $schools = $user->schools();
        }



        $alllanguages = Language::orderBy('sort_order')->get();
        $locations = Location::where('school_id', $schoolId)->orderBy('id')->get();

        $students = $school->students;
        $teachers = Teacher::orderBy('id')->get();

        $eventCategories = EventCategory::active()->where('school_id', $schoolId)->orderBy('id')->get();

        //if($user->isSuperAdmin()) {
            $eventCategory = new EventCategory();
            $eventCategory->id = 0; // Définir l'ID souhaité
            $eventCategory->title = "Temp"; // Définir le titre souhaité
            $eventCategory->school_id = $schoolId; // Assurez-vous de définir l'ID de l'école appropriée
            $eventCategories->push($eventCategory);
        //}


        $event_types_all = config('global.event_type');
        $event_types = [];

        foreach ($event_types_all as $key => $value) {

            if ($key == 10) {
              $count_cat = 0;
                if (!empty($eventCategories)) {
                    foreach ($eventCategories as $cat => $eventCat) {
                        $event_types[$key.'-'.$eventCat->id] = trim($value.' : '.$eventCat->title);
                        $count_cat++;
                      }
                }
                if ($count_cat==0) {
                    $event_types[$key]= $value;
                }


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
        if ($user->person_type == 'App\Models\Parents') {
            $user_role = 'parent';
        }
        $coach_user ='';
        if ($user->isSchoolAdmin() || $user->isTeacherAdmin() || $user->isTeacherSchoolAdmin()) {
            $user_role = 'admin_teacher';
            if ($user->isTeacherAdmin()) {
                $coach_user = 'coach_user';
            }
            if ($user->isTeacherSchoolAdmin()) {
                $user_role = 'school_admin_teacher';
            }
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

        $myCurrentTimeZone = $user->isSuperAdmin() || $user->isStudent() ? date_default_timezone_get() : $school->timezone;

        $settingUser = CalendarSetting::where('user_id', $user->id)->first();
        if(!empty($settingUser->timezone)){
            $myCurrentTimeZone = $settingUser->timezone;
        }

        $dataImported = AgendaImportModel::where('teacher_id', $user->person_id)->where('imported', false)->get();
    
        if (count($dataImported) > 0) {
            $counterDataImported = count($dataImported);
        } else {
            $counterDataImported = 0;
        }

        $events = json_encode($events);
        //unset($event_types[10]);
        return view('pages.agenda.index')->with(compact('settingUser', 'counterDataImported', 'schools','school','schoolId','user_role','coach_user','students','teachers','locations','alllanguages','events','event_types','event_types_all','eventCategoryList','professors','studentsbySchool','lessonPrice','currency', 'myCurrentTimeZone'));

    }







    public function calendar(Request $request) {
        $user = Auth::user();
        $data = $request->all();
        return view('pages.agenda.calendar');
    }









    public function getAbsentStudent(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();
        $event_date = $request->input('startDate');
        $date = \Carbon\Carbon::createFromFormat('d/m/Y',  $event_date);
        $formattedDate = $date->format('Y-m-d');

        $schoolId = $user->selectedSchoolId() ;
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            $schoolId = 0;
        }
        // This part is copied from add lesson
        //$eventCategoryList = EventCategory::active()->where('school_id',$schoolId)->get();
        //$professors = SchoolTeacher::active()->where('school_id',$schoolId)->get();

        //$studentsbySchool = SchoolStudent::active()->where('school_id',$schoolId)->get();
        $students = SchoolStudent::active()
        ->where('school_id', $schoolId)
        ->get();

    $studentsbySchool = [];

    foreach ($students as $student) {
        $futureEventIds = EventDetails::where('student_id', $student->student_id)
            ->pluck('event_id');

            $availabilities = Availability::where('student_id', $student->student_id)->get();
            $student->availabilities = $availabilities;


        // Récupérer le détail du premier événement futur
        $futureEvent = Event::whereIn('id', $futureEventIds)
        ->where('event_type', 51)
        ->where(function ($query) use ($formattedDate) {
            $query->where('date_start', '<=', $formattedDate) // Date de début inférieure ou égale
                ->where('date_end', '>=', $formattedDate); // Date de fin supérieure ou égale
        })
        ->orderBy('date_start', 'asc')
        ->first();

        if (!empty($futureEvent)) {
            $student->dates = $futureEvent;
            array_push($studentsbySchool, $student);
        }
    }

    return $studentsbySchool;

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

            $locStatus = isset($data['unlock']) ? 0 : 1 ;


            $p_event_auto_id = $data['p_event_auto_id'];
            $eventInit = new Event();
            $dta = $eventInit->validate(['event_id'=>$p_event_auto_id], $locStatus);
            if ($dta) {
                $result = array(
                    "status"     => 'success',
                    'message' => __('Confirmed'),
                );
            }
            // $eventUpdate = [
            //     'is_locked' => 1
            // ];
            // if (isset($data['unlock'])) {
            //     $eventUpdate = [
            //         'is_locked' => 0
            //     ];
            // }
            // $eventData = Event::where('id', $p_event_auto_id)->update($eventUpdate);


            // $eventDetail = [
            //     'is_locked' => 1,
            // ];
            // if (isset($data['unlock'])) {
            //     $eventDetail = [
            //         'is_locked' => 0
            //     ];
            // }
            // $eventdetails = EventDetails::where('event_id', $p_event_auto_id)->get();
            // if (!empty($eventdetails)) {
            //     foreach ($eventdetails as $key => $eventdetail) {
            //         $eventDetailPresent = [
            //             'is_locked' => 1,
            //             'participation_id' => 200,
            //         ];
            //         if (isset($data['unlock'])) {
            //             $eventDetailPresent = [
            //                 'is_locked' => 0,
            //                 'participation_id' => 200,
            //             ];
            //         }
            //         $eventDetailAbsent = [
            //             'is_locked' => 1,
            //             // 'participation_id' => 199,
            //         ];
            //         if (isset($data['unlock'])) {
            //             $eventDetailAbsent = [
            //                 'is_locked' => 0,
            //                 'participation_id' => 200,
            //             ];
            //         }


            //         if ($eventdetail->participation_id !== 199) {
            //             $eventdetail = $eventdetail->update($eventDetailPresent);
            //         } else {
            //             $eventdetail = $eventdetail->update($eventDetailAbsent);
            //         }


            //         if ($eventdetail)
            //         {
            //             $result = array(
            //                 "status"     => 'success',
            //                 'message' => __('Confirmed'),
            //             );
            //         }
            //     }
            // }else {
            //     $result = array(
            //         "status"     => 'success',
            //         'message' => __('Confirmed without student'),
            //     );
            // }

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

            $timeZone = $data['zone'];

            // $p_app_id=$_SESSION['global_app_id'];
            // $p_school_id=$_SESSION['global_school_id'];
            // $p_lang_id=$_SESSION['Language'];


            $data['location_id'] = trim($data['location_id']);
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
                if (in_array($fetch->event_type, [50,51,100])) continue;

                $fetch->date_start = $this->formatDateTimeZone($fetch->date_start, 'long', 'UTC',$timeZone);
                $fetch->date_end = $this->formatDateTimeZone($fetch->date_end, 'long', 'UTC',$timeZone);
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
                $date_start = $this->formatDateTimeZone($date_start, 'long', $timeZone,'UTC',);
                $date_end = $this->formatDateTimeZone($date_end, 'long',$timeZone,'UTC');
                $data = [
                    'title' => $fetch->title,
                    'school_id' => $fetch->school_id,
                    'event_type' => $fetch->event_type,
                    'event_category' =>$fetch->event_category,
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
                    'event_price' => $fetch->event_price,
                    'no_of_students' => $fetch->no_of_students,
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
    public function getEvent(Request $request, Event $event)
    {
        $data = $request->all();

        $user = Auth::user();
         $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
         $school = School::active()->find($schoolId);
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
        if ($user->person_type == 'App\Models\Parents') {
            $user_role = 'parent';
        }
        if ($user->isSchoolAdmin() || $user->isTeacherSchoolAdmin() || $user->isTeacherAdmin()) {
            $user_role = 'admin_teacher';
        }
        // if ($user->isTeacherAll()) {
        //     $user_role = 'teacher_all';
        // }
         if ($user->isTeacherMedium() || $user_role =='teacher' ) {
            $user_role = 'teacher';
        }

        if ($user->isTeacherMinimum()) {
            $user_role = 'teacher_minimum';
        }

        //$eventData = Event::active()->where('school_id', $schoolId)->get();

        $data['user_role'] = $user_role;
        $data['person_id'] = $user->person_id;
        $data['school_id'] = $schoolId;
        $data['schools'] = [$schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId()];

        if ($user->person_type == 'App\Models\Parents') {
            $parents = ParentStudent::where('parent_id', $user->person_id)->get();
            $listStudentId = [];
            foreach ($parents as $parent) {
                $studentIds = $parent->student_id;
                $listStudentId[] = $studentIds;
            }
            $data['list_student_id'] = implode('|', $listStudentId);
        }

        $eventData = $event->filter($data);
        $eventData = $eventData->get();

        $eventData2 = $event->filterTeacher($data);
        $eventData2 = $eventData2->get();

        $eventData = $eventData2->merge($eventData);

        $events = array();
        foreach ($eventData as $key => $fetch) {
            $fetch->date_start = $this->formatDateTimeZone($fetch->date_start, 'long', 'UTC',$data['zone']);
            $fetch->date_end = $this->formatDateTimeZone($fetch->date_end, 'long', 'UTC',$data['zone']);
            $e = array();
            $e['id'] = $fetch->id;

            $e['title']=(substr($fetch->title,0,1)==',') ? substr($fetch->title,1) : substr($fetch->title,0);
			$e['start'] = $fetch->date_start;
            $e['end'] = $fetch->date_end;
            // print_r($e['start']);
            // exit();
            // if (isset($data['zone'])) {
            //     $e['start'] = $fetch->date_start.$data['zone'];
            //     $e['end'] = $fetch->date_end.$data['zone'];

            // }
            $start_date = date('Y-m-d', strtotime($fetch->date_start));
            $end_date = date('Y-m-d', strtotime($fetch->date_end));
            $allday = ($fetch->fullday_flag == "Y") ? true : false;
            $e['allDay'] = $allday;
            if ($allday == true) {
                if ($start_date != $end_date) {
                    $e['end'] = date('Y-m-d H:i:s', strtotime($fetch->date_end . ' +1 day'));
                }
            }
            $e['teacher_name'] = null;
            if (isset($fetch->teacher)) {
                $e['teacher_name'] = $fetch->teacher['firstname'];
                $schoolTeacher = SchoolTeacher::active()->where('teacher_id',$fetch->teacher_id)->where('school_id',$fetch->school_id)->first();
                if (!empty($schoolTeacher)) {
                    $e['backgroundColor'] = $schoolTeacher->bg_color_agenda;
                    $e['teacher_name'] = $schoolTeacher->nickname;
                }
            }
            $e['event_category_name'] = '';
            $eventCategory = EventCategory::find($fetch->event_category);
            if(empty($eventCategory)) {
                $e['event_category'] = '(deleted)';
                $e['event_category_name'] = 'Temp';
                $e['event_category_type'] = '(deleted)';

                $eventCategory = new EventCategory;
                $eventCategory->title = 'Temp';
                $eventCategory->bg_color_agenda = '#AAAAAA';
                $eventCategory->invoiced_type = 'T';

            }

            if (!empty($eventCategory)) {
                $e['event_category'] = $fetch->event_category;
                $e['event_category_name'] = trim($eventCategory->title);
                    if (!$user->isTeacherSchoolAdmin() && !$user->isSchoolAdmin()) {
                        $e['backgroundColor'] = trim($eventCategory->bg_color_agenda);
                    }
                $e['event_category_type'] = ($eventCategory->invoiced_type == 'S') ? 'School ' : 'Teacher';
            }
            $e['event_type'] = $fetch->event_type;
            $e['event_location'] = $fetch->location_id;

            $event_type_name = $event_types[$e['event_type']];
            if ($e['event_type'] == 10) {
                $event_type_name = $event_types[$e['event_type']].' : '.$e['event_category_name'];
            }
            $e['event_type_name'] = $event_type_name;
            $e['event_school_id'] = (is_null($fetch->school_id) ? 0 : $fetch->school_id) ;
            $e['event_teacher_id'] = (is_null($fetch->teacher_id) ? 0 : $fetch->teacher_id) ;
            $e['event_school_name'] = $fetch->school['school_name'];

           // $e['event_category_name'] ='';
            $e['cours_name'] = '';
            $e['text_for_search']='';
            $e['tooltip']='';
            $e['content'] ='';
            if($fetch->event_mode==0){
                $e['event_mode_desc'] = 'Draft';
            } else {
                $e['event_mode_desc'] = '';
            }


            $e['cours_name'] = $e['event_type_name'].''; /*('.$e['event_category_name'].')*/
            $e['text_for_search']=strtolower($e['event_type_name'].$e['cours_name'].' '.$e['teacher_name'].' - '.$e['title']);
            //$e['tooltip']=$e['event_mode_desc'].$e['cours_name'].' Duration: '.$fetch->duration_minutes.' '.$e['teacher_name'].' - '.$e['title'];

            $eventDetailsStudentId = EventDetails::active()->where('event_id', $fetch->id)->get()->toArray();
            $student_name ='';
            $first_student_name ='';
            $i=0;
            $studentListOfLessonOrEvent = [];
            foreach($eventDetailsStudentId as $std){
                // $student = Student::find($std['student_id']);
                $schoolStudent = $schoolTeacher = SchoolStudent::where('student_id',$std['student_id'])->where('school_id',$fetch->school_id)->first();
                $e['search_by_nickname'] = $schoolStudent->nickname;
                $theStudent =  Student::active()->find($std['student_id']);
                if ($schoolStudent) {
                    if ($i!=0) {
                        if ($i==count($eventDetailsStudentId)) {
                            $student_name .='';
                        } else {
                            $student_name .=', ';
                        }
                    }else{
                        $first_student_name = $theStudent->firstname . ' ' . $theStudent->lastname;
                    }
                    // $student_name .= $student->firstname;
                    $student_name .= $theStudent->firstname . ' ' . $theStudent->lastname . ' (' . $schoolStudent->nickname.')';
                    $studentListOfLessonOrEvent[] = $theStudent->firstname . ' ' . $theStudent->lastname;
                    $i++;
                } else {
                    if ($i!=0) {
                        if ($i==count($eventDetailsStudentId)) {
                            $student_name .='';
                        } else {
                            $student_name .=', ';
                        }
                    }else{
                        $first_student_name = 'Student not found (deleted)';
                    }
                    // $student_name .= $student->firstname;
                    $student_name .= 'Student not found (deleted)';
                    $i++;
                }
            }


        //$e['title'] .= ' <i class="fa-solid fa-circle-info"></i> attention';

			$format_title = '';
            $e['title_event'] = $e['title'];
            if (in_array($fetch->event_type, [50,51])) { // coach off an student off
                $evntTypeNm = empty($e['title']) ? $e['event_type_name'] : $e['event_type_name'].'<br/>'.$e['title'];
                $evntTypetitle = empty($e['title']) ? '' : $e['title'].'<br/>';
                $e['backgroundColor'] = '#d4daf0';
                if ($fetch->event_type==50) { //coach time off
                    if ($user->isTeacherAdmin()) {
                        $e['tooltip']=$evntTypeNm;
                        $e['title'] = $e['event_type_name'];
                    } else {
                        $e['tooltip']=$evntTypeNm.' <br /> Teacher: '.$e['teacher_name'];
                        $e['title']=$e['event_type_name'].' '.$e['teacher_name'];
                    }
                    $e['title_for_modal']=$evntTypetitle.' Teacher: '.$e['teacher_name'];
                }elseif ($fetch->event_type==51) { //student time off
                    $e['title']= $e['event_type_name'].' '.$student_name;
                    $e['tooltip']=$evntTypeNm.' <br /> Students: '.$student_name;
                    $e['title_for_modal']=$evntTypetitle.' Students: '.$student_name;
                }

            }else{ // lession and event type

                if ($fetch->event_type==100) {
                    $e['title']= $event_types[$e['event_type']].' '.$e['title'];
                } else {
                    $e['title']= $e['title'];
                }


                if ($user->isTeacherAdmin()) {
                    if ($fetch->event_type==100) {
                    //$e['tooltip']=$e['event_type_name'].' <br/>  Students: '.$student_name.' <br /> Duration: '.$fetch->duration_minutes . ' Mn.';
                       // $e['tooltip']='Students: '.$student_name;
                    } else {
                        $e['tooltip']='Students: '.$student_name.' <br /> Duration: '.$fetch->duration_minutes . ' Mn.';
                        //$e['tooltip']='Duration: '.$fetch->duration_minutes . ' Mn.';
                    }
                } else {
                    if ($fetch->event_type==100) {
                    //$e['tooltip']=$e['event_type_name'].' <br/>  Students: '.$student_name.' <br /> Teacher: '.$e['teacher_name'].' <br /> Duration: '.$fetch->duration_minutes . ' Mn.';
                        $e['tooltip']='Students: '.$student_name.' <br /> Teacher: '.$e['teacher_name'];
                    } else {
                        $e['tooltip']='Students: '.$student_name.' <br /> Teacher: '.$e['teacher_name'].' <br /> Duration: '.$fetch->duration_minutes . ' Mn.';
                    }
                }

                // For add invopice type with tooltip
                if ($user->isSchoolAdmin()) {
                    if ($fetch->event_type==100) { //if event
                        $invoType =  ($fetch->event_invoice_type == 'S') ? 'School' : 'Teacher';
                        $e['tooltip'] .= '<br/ > Invoice Type : '.  $invoType ;
                    }elseif( $fetch->event_type==10 && !empty($e['event_category_type'])){
                        $e['tooltip'] .= '<br/ > Invoice Type : '.  $e['event_category_type'] ;
                    }
                }

                if ($fetch->duration_minutes > 5) {
                    if ($user->isTeacherAdmin()) {

                        //$e['title_extend']= '<br/>'.$e['event_type_name'].' <br/> Students: '.$student_name.' <br /> Duration: '.$fetch->duration_minutes;
                       // $e['title_extend']= '<br/>Students: '.$student_name.' <br /> Duration: '.$fetch->duration_minutes . ' Mn.';
                        $e['title_extend'] = '<br/><span style="font-size:11px; padding:3px;"><i class="fa-regular fa-clock"></i> Duration: '.$fetch->duration_minutes . ' Mn.</span>';
                    } else {

                        //$e['title_extend']= '<br/>'.$e['event_type_name'].' <br/> Students: '.$student_name.' <br /> Teacher: '.$e['teacher_name'].' <br /> Duration: '.$fetch->duration_minutes;
                        $e['title_extend']= '<br/>Students: '.$student_name.' <br /> Teacher: '.$e['teacher_name'].' <br /> Duration: '.$fetch->duration_minutes . ' Mn.';
                    }
                    $e['title'] = $e['title'].' '.$student_name. '';
                }
                elseif($fetch->duration_minutes > 5){
                    $e['title']= $e['title'].' '.$student_name. '';
                }
                if($fetch->event_type != 100) {


                    $student_name = implode(', ', $studentListOfLessonOrEvent);

                    if ($fetch->no_of_students === 1) {
                        $student_html = $student_name;
                    } else {
                        $student_html = '<div class="dropdown">' .
                                    '<span class="student-name dropdown-toggle" data-toggle="dropdown" style="cursor:pointer">' . reset($studentListOfLessonOrEvent) . ' (' . $fetch->no_of_students . ') <i class="fa fa-caret-down"></i></span>' .
                                        '<ul class="dropdown-menu" style="padding:5px; font-size:13px; width:150px;">';
                        foreach ($studentListOfLessonOrEvent as $student) {
                            $student_html .= "<li><i class='fa fa-regular fa-user'></i> {$student}</li>";
                        }
                        $student_html .= '</ul>' .
                                        '</div>';
                    }

                    $e['title_for_modal'] = '<tr><td width="130" class="vertical-align"><i class="fa fa-users"></i> Students :</td><td class="light-blue-txt gilroy-bold">' . $student_html . '</td></tr>' .
                    '<tr><td><i class="fa fa-user"></i> Teacher :</td><td class="light-blue-txt gilroy-bold">' . $e['teacher_name'] . '</td></tr>' .
                    '<tr><td><i class="fa fa-arrow-right"></i> Duration:</td><td class="light-blue-txt gilroy-bold">' . $fetch->duration_minutes . ' Mn.</td></tr>';
                    //$e['title_for_modal']='<tr><td width="130" class="vertical-align"><i class="fa fa-users"></i> Students :</td><td class="light-blue-txt gilroy-bold">'.$fetch->no_of_students . ' '.$student_name.'</td></tr><tr><td><i class="fa fa-user"></i> Teacher :</td><td class="light-blue-txt gilroy-bold">'.$e['teacher_name'].'</td></tr><tr><td><i class="fa fa-arrow-right"></i> Duration:</td><td class="light-blue-txt gilroy-bold">'.$fetch->duration_minutes . ' Mn.</td></tr>';
                } else {
                    $e['title_for_modal']='<tr><td width="130" class="vertical-align"><i class="fa fa-users"></i> Students :</td><td class="light-blue-txt gilroy-bold">'.$student_name.'</td></tr><tr><td><i class="fa fa-user"></i> Teacher :</td><td class="light-blue-txt gilroy-bold">'.$e['teacher_name'].'</td></tr><tr><td><i class="fa fa-arrow-right"></i> Duration:</td><td class="light-blue-txt gilroy-bold">Entire Day(s)</td></tr>';
                }


                $eventDetailsStudentId = EventDetails::active()->where('event_id', $fetch->id)->get()->pluck('student_id')->join(',');
                $eventDetailsStudentIdArray = explode(',', $eventDetailsStudentId);

                $studentsbySchool = [];
                $futureEventIdsByStudent = [];

                // Créez un tableau associatif pour stocker les futurs événements par étudiant
                foreach ($eventDetailsStudentIdArray as $studentId) {
                    $futureEventIdsByStudent[$studentId] = [];
                }

        // Récupérez les futurs événements en une seule requête
        $futureEvents = Event::whereIn('event_details.student_id', $eventDetailsStudentIdArray)
            ->where('event_type', 51)
            ->where(function ($query) use ($fetch) {
                $query->where('date_start', '=', $fetch->date_start)
                    ->orWhere(function ($subQuery) use ($fetch) {
                        $subQuery->where('date_start', '<', $fetch->date_start)
                            ->where('date_end', '>', $fetch->date_start);
                    });
            })
            ->orderBy('date_start', 'asc')
            ->join('event_details', 'events.id', '=', 'event_details.event_id')
            ->get(['event_details.student_id', 'events.*']);

            // Associez les futurs événements aux étudiants dans le tableau associatif
            foreach ($futureEvents as $futureEvent) {
                $studentId = $futureEvent->student_id;
                $futureEventIdsByStudent[$studentId][] = $futureEvent->id;
            }

            // Mettez à jour les dates des étudiants avec leurs futurs événements
            foreach ($eventDetailsStudentIdArray as $studentId) {
                $student = Student::find($studentId);

                if ($student) {
                    $futureEventIds = $futureEventIdsByStudent[$studentId];

                    if (!empty($futureEventIds)) {
                        // Utilisez la méthode whereIn pour récupérer les futurs événements
                        $futureEvent = $futureEvents->whereIn('id', $futureEventIds)->first();

                        $student->dates = $futureEvent;
                        if ($futureEvent) {
                            $e['tooltip'] .= '<br><span class="badge bg-warning"><i class="fa-solid fa-circle-info text-white" style="color:orange;"></i> ' . $student->firstname . ' is away</span>';
                        }
                        array_push($studentsbySchool, $student);
                    }
                }
            }


               // $now = Carbon::now($fetch->school->timezone);
               // if ($now > $fetch->date_start) {




            }



            //Alert message for No Category Lesson
            if($fetch->event_type == 10){
                if($e['event_category_name'] === "Temp") {
                    if (!$user->isStudent()) {
                        if ($user->isSchoolAdmin() || $user->isTeacherSchoolAdmin())  {
                            $e['tooltip'] .= '<br><span class="badge bg-warning"><i class="fa-solid fa-triangle-exclamation text-white" style="color:orange;"></i> No category</span>';
                        } else {
                            $e['tooltip'] .= '<br><span class="badge bg-warning"><i class="fa-solid fa-triangle-exclamation text-white" style="color:orange;"></i> Select a category</span>';
                        }
                    }
                }
            }



            $e['content'] = ($e['cours_name']);

            if($fetch->event_type == 10){
               $e['invoice_type'] = $eventCategory->invoiced_type;
            }elseif($fetch->event_type == 100){
               $e['invoice_type'] = $fetch->event_invoice_type;
            }

            $e['teacher_id'] = $fetch->teacher_id;
            $e['duration_minutes'] = $fetch->duration_minutes;
            $e['no_of_students'] = $fetch->no_of_students;
            $e['is_locked'] = $fetch->is_locked;
            $eventDetailsStudentId = EventDetails::active()->where('event_id', $fetch->id)->get()->pluck('student_id')->join(',');
            $e['student_id_list'] = $eventDetailsStudentId;

            $eventDetailsStudentIdArray = explode(',', $eventDetailsStudentId);

            $studentsbySchool = [];
            $futureEventIdsByStudent = [];

            // Créez un tableau associatif pour stocker les futurs événements par étudiant
            foreach ($eventDetailsStudentIdArray as $studentId) {
                $futureEventIdsByStudent[$studentId] = [];
            }

            // Récupérez les futurs événements en une seule requête
            $futureEvents = Event::whereIn('event_details.student_id', $eventDetailsStudentIdArray)
                ->where('event_type', 51)
                ->where(function ($query) use ($fetch) {
                    $query->where('date_start', '=', $fetch->date_start)
                        ->orWhere(function ($subQuery) use ($fetch) {
                            $subQuery->where('date_start', '<', $fetch->date_start)
                                ->where('date_end', '>', $fetch->date_start);
                        });
                })
                ->orderBy('date_start', 'asc')
                ->join('event_details', 'events.id', '=', 'event_details.event_id')
                ->get(['event_details.student_id', 'events.*']);

            // Associez les futurs événements aux étudiants dans le tableau associatif
            foreach ($futureEvents as $futureEvent) {
                $studentId = $futureEvent->student_id;
                $futureEventIdsByStudent[$studentId][] = $futureEvent->id;
            }

            // Mettez à jour les dates des étudiants avec leurs futurs événements
            foreach ($eventDetailsStudentIdArray as $studentId) {
                $student = Student::find($studentId);

                if ($student) {
                    $futureEventIds = $futureEventIdsByStudent[$studentId];

                    if (!empty($futureEventIds)) {
                        // Utilisez la méthode whereIn pour récupérer les futurs événements
                        $futureEvent = $futureEvents->whereIn('id', $futureEventIds)->first();

                        $student->dates = $futureEvent;
                        array_push($studentsbySchool, $student);
                    }
                }
            }


            if ($fetch->is_locked == 1) {
                $invoiceItemsCount = InvoiceItem::where('event_id', $fetch->id)->count();
                $e['isInvoice'] = ($invoiceItemsCount > 0);
            }


            $e['studentsbySchool'] = $studentsbySchool;


            $e['event_auto_id'] = ($fetch->id);
            $e['event_mode'] = $fetch->event_mode;

            $now = Carbon::now($fetch->school->timezone);
            if ($now > $fetch->date_start && $fetch->event_category !== 0) {
            if($fetch->event_invoice_type == 'T' && $fetch->created_by !== Auth::user()->id){

            } else { $e['can_lock'] = 'Y'; }
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

                if ($user_role == 'student' || $user_role == 'parent'){
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
                if ( ($user_role == 'teacher') || ($user_role == 'teacher_minimum') ){
                    if (($user->person_id == $fetch->teacher_id)){
                        if($user->can('self-edit-events')){
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
                        }
                    }else{
                        if($user->can('others-edit-events')){
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
                }
                /* only own vacation entry can be edited by user - Teacher */
                if ($fetch->event_type == 50) {
                    if ( ($user->id == $fetch->created_by) || ($user->id == $fetch->teacher_id) || ($user->id == $fetch->teacher_id )) {
                        if($user->can('self-edit-events')){
                            $action_type='edit';
                            $page_name='/'.$fetch->school_id.'/edit-coach-off/'.$fetch->id;
                        }
                    } else {
                        if($user->can('others-edit-events')){
                            $action_type='edit';
                            $page_name='/'.$fetch->school_id.'/edit-coach-off/'.$fetch->id;
                        }else{
                            $action_type='view';
                            $page_name='/'.$fetch->school_id.'/view-coach-off/'.$fetch->id;
                        }
                    }
                }
                /* only own vacation entry can be edited by user - Student */
                if ($fetch->event_type == 51) {
                    if (($user->id == $fetch->created_by) || ($user->id == $fetch->student_id) || ($user->id == $fetch->student_id )) {
                        if($user->can('self-edit-events')){
                            $action_type='edit';
                            $page_name='/'.$fetch->school_id.'/edit-student-off/'.$fetch->id;
                        }
                    } else {
                        if($user->can('others-edit-events')){
                            $action_type='edit';
                            $page_name='/'.$fetch->school_id.'/edit-student-off/'.$fetch->id;
                        }else{
                            $action_type='view';
                            $page_name='/'.$fetch->school_id.'/view-student-off/'.$fetch->id;
                        }
                    }

                }

            };
            $e['url'] = $page_name;

            $e['action_type'] = $action_type;

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

        $students = SchoolStudent::active()->where('school_id', $schoolId);

        if ($user_role =='student' ) {
        $students->where('student_id', $user->person_id);
        }

        $students = $students->get();

        $studentIds = $students->pluck('student_id')->toArray();
        $studentInfo = Student::whereIn('id', $studentIds)->get(['id', 'firstname', 'lastname'])->keyBy('id');

        $students = $students->map(function ($student) use ($studentInfo) {
            $availabilities = Availability::where('student_id', $student->student_id)->get();
            $student->availabilities = $availabilities;
            $student->firstname = $studentInfo[$student->student_id]->firstname;
            $student->lastname = $studentInfo[$student->student_id]->lastname;
            return $student;
        });

        return $locations = json_encode($students);
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

        if (!$user->isSchoolAdmin() && !$user->isTeacherSchoolAdmin()) {
            $eventCat = EventCategory::active()->where('school_id', $schoolId)->where('created_by', $user->id)->get();
        }else{
            $eventCat = EventCategory::active()->where('school_id', $schoolId)->where('created_by', $user->id)->get();
        }

        //check if each category have LessonPrice
        foreach ($eventCat as $key => $value) {
            $lessonPrice = LessonPriceTeacher::where('event_category_id', $value->id)->first();
           if ($lessonPrice) {
            $value->prices = $lessonPrice;
           }else{
               $value->prices = [];
           }
        }

        return $eventCategory = json_encode($eventCat);

    }


    public function getEventCategoryByType(Request $request) {
        $data = $request->all();
        $type = $data['type'];
        $teacher = $data['teacher'];
        $user = Auth::user();
        $schoolId = $data['school_id'];
        $teacherDetail = User::where('person_id',$teacher)->first();

        if ($type == 'T') {
            $eventCat = EventCategory::TeacherInvoiced()->where('school_id', $schoolId)->where('created_by', $teacherDetail->id)->get();
        }else{
            $eventCat = EventCategory::active()->where('school_id', $schoolId)->where('invoiced_type', $type)->get(); //where('created_by', $user->id)
        }

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
            $currency = Currency::getCurrencyByCountry($school->country_code);
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
        if ($user->isSchoolAdmin() || $user->isTeacherSchoolAdmin() || $user->isTeacherAdmin()) {
            $user_role = 'admin_teacher';
        }
        if ($user->isTeacherAll()) {
            $user_role = 'teacher_all';
        }
        $professorsQuery = SchoolTeacher::active()->onlyTeacher()->where('school_id', $schoolId);

        if ($user->isTeacherMedium() || $user->isTeacherMinimum() || $user_role == 'teacher') {
            $professorsQuery->where('teacher_id', $user->person_id);
        }

        $professors = $professorsQuery->get();
        //$students = SchoolStudent::active()->where('school_id', $schoolId)->get();
        //$locations = Teacher::active()->where('school_id', $schoolId)->orderBy('id')->get();

        $mergedList = [];

        foreach ($professors as $professor) {
            $lesson_price_teachers = LessonPriceTeacher::where('teacher_id', $professor->teacher_id)->get();
            $professorData = $professor->toArray();
            $professorData['lesson_price_teachers'] = $lesson_price_teachers->toArray();
            $mergedList[] = $professorData;
        }

        $mergedListJSON = json_encode($mergedList);

        return $mergedListJSON;

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
            'message' => __('Failed to delete'),
        );

        try {
            $dataParam = $request->all();
            $eventIds = explode(',', $dataParam['p_event_school_id']);

            $deletedCount = $event->whereIn('id', $eventIds)->delete();

            if ($deletedCount > 0) {
                $result = array(
                    'status' => 'success',
                    'message' => __('Confirmed'),
                );
            }

            return response()->json($result);
        } catch (Exception $e) {
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }



        /*$result = array(
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


            $data['location_id']= trim($dataParam['location_id']);
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
*/
    }

     /**
     *  AJAX delete event
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-07-26
     */
	public function deleteEvent(Request $request, Event $event)
	{
		$result = array(
			'status' => 'failed',
			'message' => __('failed to delete'),
		);
		try {
			$dataParam = $request->all();
			$id= trim($dataParam['event_id']);
			$eventData = Event::find($id)->delete();
			if ($eventData == 1)
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
            'message' => __('Failed to validate'),
        );

        try {
            $data = $request->all();
            $eventIds = explode(',', $data['p_event_school_id']);

            foreach ($eventIds as $eventId) {
                $event = Event::find($eventId);

                // Vérifier si l'événement existe et est de type 10
                if ($event && $event->event_type == 10) {
                    // Valider l'événement
                    Event::validate(['event_id' => $eventId]);
                }
            }

            // Si la validation réussit pour au moins un événement de type 10, retourner un succès
            $result = array(
                'status' => 'success',
                'message' => __('Confirmed'),
            );

            return response()->json($result);
        } catch (Exception $e) {
            // Retourner un message d'erreur en cas d'erreur interne du serveur
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }


        /*$result = array(
            'status' => 'failed',
            'message' => __('failed to validate'),
        );
        try {
            $data = $request->all();
            $param = [];
            $param['p_from_date']= trim($data['p_from_date']);
            $param['p_to_date']= trim($data['p_to_date']);
            $param['location_id']= trim($data['location_id']);

            $param['school_id']= trim($data['p_event_school_id']);
            $param['event_type']= trim($data['p_event_type_id']);
            //$param['teacher_id']= trim($data['p_teacher_id']);
            $param['student_id']= trim($data['p_student_id']);
            //$p_user_id=Auth::user()->id;

            if (isset($param['p_from_date'])) {
                //$query = new Event;

                $eventUpdate = [
                    'is_locked' => 1
                ];
                $eventData = $event->multiValidate($param)->get();
                foreach ($eventData as $key => $p_event_auto_id) {

                    // $eventUpdate = [
                    //     'is_locked' => 1
                    // ];
                    // $eventDataUpdated = Event::where('id', $p_event_auto_id->id)->update($eventUpdate);


                    // $eventDetailPresent = [
                    //     'is_locked' => 1,
                    //     'participation_id' => 200,
                    // ];
                    // $eventDetailAbsent = [
                    //     'is_locked' => 1
                    //     //'participation_id' => 199,
                    // ];
                    // $eventdetails = EventDetails::where('event_id', $p_event_auto_id->id)->get();
                    // if (!empty($eventdetails)) {
                    //     foreach ($eventdetails as $key => $eventdetail) {
                    //         if ($eventdetail->participation_id != 199) {
                    //             $eventdetail = $eventdetail->update($eventDetailPresent);
                    //         } else {
                    //             $eventdetail = $eventdetail->update($eventDetailAbsent);
                    //         }
                    //     }
                    //}

                    $school = School::active()->find($p_event_auto_id->school_id);
                    $now = Carbon::now($school->timezone);
                    $dateStart = Carbon::createFromFormat('Y-m-d H:i:s', $p_event_auto_id->date_start);

                    if ($p_event_auto_id->event_type == 10) {
                        if ($now->greaterThan($dateStart)) {
                            // Event::updateLatestPrice($p_event_auto_id->id);
                            Event::validate(['event_id'=>$p_event_auto_id->id]);
                        }
                    }
                }

            }
            //dd($eventData);
            // if ($eventDataUpdated)
            // {
                $result = array(
                    "status"     => 'success',
                    'message' => __('Confirmed'),
                );
            // }

            return response()->json($result);

        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
*/
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
