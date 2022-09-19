<?php

namespace App\Http\Controllers;
use App\Models\VerifyToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventDetails;
use App\Models\School;
use App\Models\Teacher;
use App\Models\SchoolTeacher;
use App\Models\SchoolStudent;
use App\Models\EventCategory;
use App\Models\Location;
use App\Models\LessonPrice;
use App\Models\LessonPriceTeacher;
use App\Models\Currency;
use Redirect;
use DB;

class LessonsController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addEvent(Request $request, $schoolId = null)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $eventCategory = EventCategory::active()->where('school_id',$schoolId)->get();
        $locations = Location::active()->where('school_id',$schoolId)->get();
        $professors = SchoolTeacher::active()->onlyTeacher()->where('school_id',$schoolId)->get();
        $students = SchoolStudent::active()->where('school_id',$schoolId)->get();
        $lessonPrice = LessonPrice::active()->get();

        $currency = Currency::active()->ByCountry($school->country_code)->get();

        return view('pages.calendar.add_event')->with(compact('schoolId','eventCategory','locations','professors','students','lessonPrice','currency'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addEventAction(Request $request, $schoolId = null)
    {
        DB::beginTransaction();
        try{
            if ($request->isMethod('post')){
                $user = Auth::user();
                $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
                $school = School::active()->find($schoolId);
                if (empty($school)) {
                    return redirect()->route('schools')->with('error', __('School is not selected'));
                }

                $eventData = $request->all();
                $start_date = str_replace('/', '-', $eventData['start_date']).' '.$eventData['start_time'];
                $end_date = str_replace('/', '-', $eventData['end_date']).' '.$eventData['end_time'];
                $start_date = date('Y-m-d H:i:s',strtotime($start_date));
                $end_date = date('Y-m-d H:i:s',strtotime($end_date));
                $start_date = $this->formatDateTimeZone($start_date, 'long', $eventData['zone'],'UTC');
                $end_date = $this->formatDateTimeZone($end_date, 'long', $eventData['zone'],'UTC');
                $stu_num = count($eventData['student']);
                    
                $data = [
                    'title' => $eventData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 100,
                    'date_start' => $start_date,
                    'date_end' => $end_date,
                    'price_currency' => isset($eventData['sprice_currency']) ? $eventData['sprice_currency'] : null,
                    'price_amount_buy' => $eventData['sprice_amount_buy'],
                    'price_amount_sell' => $eventData['sprice_amount_sell'],
                    'extra_charges' => $eventData['extra_charges'],
                    'fullday_flag' => !empty($eventData['fullday_flag']) ? $eventData['fullday_flag'] : null,
                    'description' => $eventData['description'],
                    'location_id' => isset($eventData['location']) ? $eventData['location'] : null,
                    'teacher_id' => $eventData['teacher_select'],
                    'no_of_students' => !empty($eventData['student']) ? count($eventData['student']) : null,
                ];

                $event = Event::create($data);
                if (!empty($eventData['student'])) {
                    foreach($eventData['student'] as $std){
                        $dataDetails = [
                            'event_id'   => $event->id,
                            'teacher_id' => $eventData['teacher_select'],
                            'student_id' => $std,
                            'buy_price' => (($eventData['sprice_amount_buy'])/($stu_num)),
                            'sell_price' => $eventData['sprice_amount_sell'],
                            'price_currency' => !empty($eventData['fullday_flag']) ? $eventData['fullday_flag'] : null
                        ];
                        $eventDetails = EventDetails::create($dataDetails);
                    }
                }

                DB::commit();
                 
                 if($eventData['save_btn_more'] == 1){
                    return [
                        'status' => 1,
                        'message' =>  __('Successfully Registered')
                    ];
                }else{
                    return [
                        'status' => 2,
                        'message' =>  __('Successfully Registered')
                    ];
                }
                
            }  
        }catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->all())->with('error', __('Internal server error'));
        }   

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editEvent(Request $request, $schoolId = null)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $eventId = $request->route('event'); 
        $eventData = Event::active()->where(['id'=>$eventId, 'event_type' => 100])->first();
        $relationData = EventDetails::active()->where(['event_id'=>$eventId])->first();
        $eventCategory = EventCategory::active()->where('school_id',$schoolId)->get();
        $locations = Location::active()->where('school_id',$schoolId)->get();
        $professors = SchoolTeacher::active()->onlyTeacher()->where('school_id',$schoolId)->get();
        $students = SchoolStudent::active()->where('school_id',$schoolId)->get();
        $studentOffList = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->leftJoin('school_student', 'school_student.student_id', '=', 'event_details.student_id')->where(['events.id'=>$eventId, 'event_type' => 100,'events.is_active' => 1])->get();
        $lessonPrice = LessonPrice::active()->get();
        $currency = Currency::active()->ByCountry($school->country_code)->get();

        if (!empty($eventData)){
            return view('pages.calendar.edit_event')->with(compact('eventId','eventData','relationData','schoolId','eventCategory','locations','professors','studentOffList','students','lessonPrice','currency'));
        }else{
            return redirect()->route('agenda',['school'=> $schoolId]);
        }
        
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editEventAction(Request $request, $schoolId = null)
    {
        DB::beginTransaction();
        try{
            if ($request->isMethod('post')){
                $user = Auth::user();
                $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
                $school = School::active()->find($schoolId);
                if (empty($school)) {
                    return redirect()->route('schools')->with('error', __('School is not selected'));
                }

                $eventId = $request->route('event'); 
                $eventData = $request->all();
                $start_date = str_replace('/', '-', $eventData['start_date']).' '.$eventData['start_time'];
                $end_date = str_replace('/', '-', $eventData['end_date']).' '.$eventData['end_time'];
                $start_date = date('Y-m-d H:i:s',strtotime($start_date));
                $end_date = date('Y-m-d H:i:s',strtotime($end_date));
                $start_date = $this->formatDateTimeZone($start_date, 'long', $eventData['zone'],'UTC');
                $end_date = $this->formatDateTimeZone($end_date, 'long', $eventData['zone'],'UTC');
                $stu_num = count($eventData['student']);

                $data = [
                    'title' => $eventData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 100,
                    'date_start' => $start_date,
                    'date_end' => $end_date,
                    'price_currency' => !empty($eventData['sprice_currency']) ? $eventData['sprice_currency'] : null,
                    'price_amount_buy' => $eventData['sprice_amount_buy'],
                    'price_amount_sell' => $eventData['sprice_amount_sell'],
                    'extra_charges' => $eventData['extra_charges'],
                    'fullday_flag' => !empty($eventData['fullday_flag']) ? $eventData['fullday_flag'] : null,
                    'description' => $eventData['description'],
                    'location_id' => isset($eventData['location']) ? $eventData['location'] : null,
                    'teacher_id' => $eventData['teacher_select'],
                    'no_of_students' => !empty($stu_num) ? $stu_num : null,
                ];

                $event = Event::where('id', $eventId)->update($data);
                EventDetails::where('event_id',$eventId)->forceDelete();
                foreach($eventData['student'] as $std){
                    $dataDetails = [
                        'event_id'   => $eventId,
                        'teacher_id' => $eventData['teacher_select'],
                        'student_id' => $std,
                        'buy_price' => (($eventData['sprice_amount_buy'])/($stu_num)),
                        'sell_price' => $eventData['sprice_amount_sell'],
                        'price_currency' => !empty($eventData['sprice_currency']) ? $eventData['sprice_currency'] : null,
                        'participation_id' => !empty($eventData['attnValue'][$std]) ? $eventData['attnValue'][$std] : 0,
                    ];
                    $eventDetails = EventDetails::create($dataDetails);
                }

                DB::commit();
                 
                 // return back()->withInput($request->all())->with('success', __('Successfully Registered'));
                 return back()->with('success', __('Successfully Registered'));
                
            }  
        }catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->all())->with('error', __('Internal server error'));
        }   

        return $result;
    }
   

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewEvent(Request $request, $schoolId = null)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $eventId = $request->route('event'); 
        $eventData = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->where(['events.id'=>$eventId, 'event_type' => 100,'events.is_active' => 1])->first();
        $studentOffList = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->leftJoin('school_student', 'school_student.student_id', '=', 'event_details.student_id')->where(['events.id'=>$eventId, 'event_type' => 100,'events.is_active' => 1])->get();
        $professors = DB::table('events')->select('school_teacher.nickname')->leftJoin('school_teacher', 'school_teacher.teacher_id', '=', 'events.teacher_id')->where(['events.id'=>$eventId, 'event_type' => 100,'events.is_active' => 1])->first();
        $eventCategory = DB::table('events')->select('event_categories.title')->leftJoin('event_categories', 'event_categories.id', '=', 'events.event_category')->where(['events.id'=>$eventId, 'event_type' => 100,'events.is_active' => 1])->first();
        $locations = DB::table('locations')->select('locations.title')->leftJoin('events', 'events.location_id', '=', 'locations.id')->where(['events.id'=>$eventId, 'event_type' => 100,'events.is_active' => 1,'locations.is_active' => 1])->first();
        $lessonPrice = LessonPrice::active()->get();
        return view('pages.calendar.view_event')->with(compact('eventData','schoolId','eventCategory','locations','professors','studentOffList','lessonPrice','eventId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addLesson(Request $request, $schoolId = null)
    {   
        $lessonlId= $_GET['id'];
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $studentOffList = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->leftJoin('school_student', 'school_student.student_id', '=', 'event_details.student_id')->where(['events.id'=>$lessonlId, 'event_type' => 10,'events.is_active' => 1])->get();
        $lessonData = Event::active()->where(['id'=>$lessonlId, 'event_type' => 10])->first();
        $relationData = EventDetails::active()->where(['event_id'=>$lessonlId])->first();
        $eventCategory = EventCategory::active()->where('school_id',$schoolId)->get();
        $locations = Location::active()->where('school_id',$schoolId)->get();
        $professors = SchoolTeacher::active()->onlyTeacher()->where('school_id',$schoolId)->get();
        $students = SchoolStudent::active()->where('school_id',$schoolId)->get();
        $lessonPrice = LessonPrice::active()->get();
        $currency = Currency::active()->ByCountry($school->country_code)->get();

        return view('pages.calendar.add_lesson')->with(compact('lessonData','relationData','schoolId','eventCategory','locations','professors','students','lessonPrice','currency','studentOffList'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addLessonAction(Request $request, $schoolId = null)
    {
        DB::beginTransaction();
        try{
            if ($request->isMethod('post')){
                $user = Auth::user();
                $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
                $school = School::active()->find($schoolId);
                if (empty($school)) {
                    return redirect()->route('schools')->with('error', __('School is not selected'));
                }

                $lessonData = $request->all();
                $start_date = str_replace('/', '-', $lessonData['start_date']).' '.$lessonData['start_time'];
                $end_date = str_replace('/', '-', $lessonData['end_date']).' '.$lessonData['end_time'];
                $start_date = date('Y-m-d H:i:s',strtotime($start_date));
                $end_date = date('Y-m-d H:i:s',strtotime($end_date));
                $start_date = $this->formatDateTimeZone($start_date, 'long', $lessonData['zone'],'UTC');
                $end_date = $this->formatDateTimeZone($end_date, 'long', $lessonData['zone'],'UTC');
                $stu_num = explode("_", $lessonData['sevent_price']);

                $lessonPriceTeacher = LessonPriceTeacher::active()->where(['event_category_id'=>$lessonData['category_select'],'lesson_price_id'=>$stu_num[1],'teacher_id'=>$lessonData['teacher_select']])->first();

                if($lessonData['sis_paying'] == 1){
                    $attendBuyPrice = (($lessonPriceTeacher['price_buy'])*($stu_num[1])*($lessonData['duration']))/60 ;
                    $attendSellPrice = (($lessonPriceTeacher['price_sell'])*($stu_num[1])*($lessonData['duration']))/60 ;
                }elseif($lessonData['sis_paying'] == 2){
                    $attendBuyPrice = $lessonData['sprice_amount_buy'];
                    $attendSellPrice = $lessonData['sprice_amount_sell'];
                }else{
                    $attendBuyPrice = 0;
                    $attendSellPrice = 0;
                }

                $data = [
                    'title' => $lessonData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 10,
                    'event_category' => $lessonData['category_select'],
                    'teacher_id' => $lessonData['teacher_select'],
                    'date_start' => $start_date,
                    'date_end' => $end_date,
                    'duration_minutes' => $lessonData['duration'],
                    'price_currency' => isset($lessonData['sprice_currency']) ? $lessonData['sprice_currency'] : null,
                    'price_amount_buy' => $lessonData['sprice_amount_buy'],
                    'price_amount_sell' => $lessonData['sprice_amount_sell'],
                    'fullday_flag' => isset($lessonData['fullday_flag']) ? $lessonData['fullday_flag'] : null,
                    'no_of_students' => isset($stu_num) ? $stu_num[1] : null,
                    'description' => $lessonData['description'],
                    'location_id' => isset($lessonData['location']) ? $lessonData['location'] : null,
                    'sis_paying' => $lessonData['sis_paying']
                ];

                $event = Event::create($data);
                if (!empty($lessonData['student'])) {
                   foreach($lessonData['student'] as $std){
                        $dataDetails = [
                            'event_id'   => $event->id,
                            'teacher_id' => $lessonData['teacher_select'],
                            'student_id' => $std,
                            'buy_price' => $attendBuyPrice,
                            'sell_price' => $attendSellPrice,
                            'price_currency' => isset($lessonData['sprice_currency']) ? $lessonData['sprice_currency'] : null
                        ];
                        $eventDetails = EventDetails::create($dataDetails);
                    }
                }
                    
                DB::commit();

                if($lessonData['save_btn_more'] == 1){
                    return [
                        'status' => 1,
                        'message' =>  __('Successfully Registered')
                    ];
                }else if($lessonData['save_btn_more'] == 2){
                    return Redirect::to($schoolId.'/add-lesson?id='.$event->id)->withInput($request->all())->with('success', __('Successfully Registered'));
                }else if($lessonData['save_btn_more'] == 3){
                    return Redirect::to('/agenda');
                }else{
                     return [
                        'status' => 2,
                        'message' =>  __('Successfully Registered')
                    ];
                }     
               
            }  
        }catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->all())->with('error', __('Internal server error'));
        }   

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editLesson(Request $request, $schoolId = null)
    {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return redirect()->route('schools')->with('error', __('School is not selected'));
            }
            $schoolId = $school->id;
        }else {
            $schoolId = $user->selectedSchoolId();
            $school = School::active()->find($schoolId);
        }

        $lessonlId = $request->route('lesson'); 
        $lessonData = Event::active()->where(['id'=>$lessonlId, 'event_type' => 10])->first();
        $relationData = EventDetails::active()->where(['event_id'=>$lessonlId])->first();
        $eventCategory = EventCategory::active()->where('school_id',$schoolId)->get();
        $locations = Location::active()->where('school_id',$schoolId)->get();
        $professors = SchoolTeacher::active()->onlyTeacher()->where('school_id',$schoolId)->get();
        $students = SchoolStudent::active()->where('school_id',$schoolId)->get();
        $studentOffList = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->leftJoin('school_student', 'school_student.student_id', '=', 'event_details.student_id')->where(['events.id'=>$lessonlId, 'event_type' => 10,'events.is_active' => 1])->get();
        $lessonPrice = LessonPrice::active()->get();
        $currency = Currency::active()->ByCountry($school->country_code)->get();
        $lessonPriceTeacher = LessonPriceTeacher::active()->where(['event_category_id'=>$lessonData->event_category,'lesson_price_id'=>$lessonData->no_of_students,'teacher_id'=>$lessonData->teacher_id])->first();

        if (!empty($lessonData)){
            return view('pages.calendar.edit_lesson')->with(compact('lessonlId','lessonData','relationData','schoolId','eventCategory','locations','professors','studentOffList','students','lessonPrice','lessonPriceTeacher','currency'));
        }else{
            return redirect()->route('agenda',['school'=> $schoolId]);
        }
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editLessonAction(Request $request, $schoolId = null)
    {
        DB::beginTransaction();
        try{
            if ($request->isMethod('post')){
                $user = Auth::user();
                $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
                $school = School::active()->find($schoolId);
                if (empty($school)) {
                    return redirect()->route('schools')->with('error', __('School is not selected'));
                }

                $lessonlId = $request->route('lesson');
                $lessonData = $request->all();
                $start_date = str_replace('/', '-', $lessonData['start_date']).' '.$lessonData['start_time'];
                $end_date = str_replace('/', '-', $lessonData['end_date']).' '.$lessonData['end_time'];
                $start_date = date('Y-m-d H:i:s',strtotime($start_date));
                $end_date = date('Y-m-d H:i:s',strtotime($end_date));
                $start_date = $this->formatDateTimeZone($start_date, 'long', $lessonData['zone'],'UTC');
                $end_date = $this->formatDateTimeZone($end_date, 'long', $lessonData['zone'],'UTC');
                $stu_num = explode("_", $lessonData['sevent_price']);

                $lessonPriceTeacher = LessonPriceTeacher::active()->where(['event_category_id'=>$lessonData['category_select'],'lesson_price_id'=>$stu_num[1],'teacher_id'=>$lessonData['teacher_select']])->first();

                if($lessonData['sis_paying'] == 1){
                    $attendBuyPrice = (($lessonPriceTeacher['price_buy'])*($stu_num[1])*($lessonData['duration']))/60 ;
                    $attendSellPrice = (($lessonPriceTeacher['price_sell'])*($stu_num[1])*($lessonData['duration']))/60 ;
                }elseif($lessonData['sis_paying'] == 2){
                    $attendBuyPrice = $lessonData['sprice_amount_buy'];
                    $attendSellPrice = $lessonData['sprice_amount_sell'];
                }else{
                    $attendBuyPrice = 0;
                    $attendSellPrice = 0;
                }

                $data = [
                    'title' => $lessonData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 10,
                    'event_category' => $lessonData['category_select'],
                    'teacher_id' => !empty($lessonData['teacher_select']) ? $lessonData['teacher_select'] : null,
                    'date_start' => $start_date,
                    'date_end' => $end_date,
                    'duration_minutes' => $lessonData['duration'],
                    'price_currency' => isset($lessonData['sprice_currency']) ? $lessonData['sprice_currency'] : null,
                    'price_amount_buy' => $lessonData['sprice_amount_buy'],
                    'price_amount_sell' => $lessonData['sprice_amount_sell'],
                    'fullday_flag' => isset($lessonData['fullday_flag']) ? $lessonData['fullday_flag'] : null,
                    'no_of_students' => isset($stu_num) ? $stu_num[1] : null,
                    'description' => $lessonData['description'],
                    'location_id' => isset($lessonData['location']) ? $lessonData['location'] : null,
                    'is_paying' => $lessonData['sis_paying']
                ];

                $event = Event::where('id', $lessonlId)->update($data);
                EventDetails::where('event_id',$lessonlId)->forceDelete();
                if (!empty($lessonData['student'])) {
                    foreach($lessonData['student'] as $std){
                        $dataDetails = [
                            'event_id' => $lessonlId,
                            'teacher_id' => !empty($lessonData['teacher_select']) ? $lessonData['teacher_select'] : null,
                            'student_id' => $std,
                            'buy_price' => $attendBuyPrice,
                            'sell_price' => $attendSellPrice,
                            'price_currency' => isset($lessonData['sprice_currency']) ? $lessonData['sprice_currency'] : null,
                            'participation_id' => !empty($lessonData['attnValue'][$std]) ? $lessonData['attnValue'][$std] : 0,
                        ];
                        $eventDetails = EventDetails::create($dataDetails);
                    }
                }
                DB::commit();
            
                if($lessonData['save_btn_more'] == 1){
                    return Redirect::to($schoolId.'/add-lesson?id='.$lessonlId);
                }else{
                    return back()->with('success', __('Successfully Registered'));
                } 
        }  
        }catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->all())->with('error', __('Internal server error'));
        }   

        return $result;
    }


        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewLesson(Request $request, $schoolId = null)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $lessonlId = $request->route('lesson'); 
        $lessonData = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->where(['events.id'=>$lessonlId, 'event_type' => 10,'events.is_active' => 1])->first();
        $studentOffList = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->leftJoin('school_student', 'school_student.student_id', '=', 'event_details.student_id')->where(['events.id'=>$lessonlId, 'event_type' => 10,'events.is_active' => 1])->get();
        $professors = DB::table('events')->select('school_teacher.nickname','school_teacher.teacher_id')->leftJoin('school_teacher', 'school_teacher.teacher_id', '=', 'events.teacher_id')->where(['events.id'=>$lessonlId, 'event_type' => 10,'events.is_active' => 1])->first();
        $professors->full_name = "";
        if (!empty($professors->teacher_id)) {
            $teacher = Teacher::find($professors->teacher_id);
            $professors->full_name = $teacher->full_name;
        }
        $lessonCategory = DB::table('events')->select('event_categories.title')->leftJoin('event_categories', 'event_categories.id', '=', 'events.event_category')->where(['events.id'=>$lessonlId, 'event_type' => 10,'events.is_active' => 1])->first();
        $locations = DB::table('locations')->select('locations.title')->leftJoin('events', 'events.location_id', '=', 'locations.id')->where(['events.id'=>$lessonlId, 'event_type' => 10,'events.is_active' => 1,'locations.is_active' => 1])->first();
        $lessonPrice = LessonPrice::active()->get();
        return view('pages.calendar.view_lesson')->with(compact('lessonData','schoolId','lessonCategory','locations','professors','studentOffList','lessonPrice','lessonlId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentOff(Request $request, $schoolId = null)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $students = SchoolStudent::active()->where('school_id',$schoolId)->get();
        return view('pages.calendar.add_student_off')->with(compact('schoolId','students'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentOffAction(Request $request, $schoolId = null)
    {
        DB::beginTransaction();
        try{
            if ($request->isMethod('post')){
                $user = Auth::user();
                $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
                $school = School::active()->find($schoolId);
                if (empty($school)) {
                    return redirect()->route('schools')->with('error', __('School is not selected'));
                }

                $studentOffData = $request->all();
                $start_date = str_replace('/', '-', $studentOffData['start_date']);
                $end_date = str_replace('/', '-', $studentOffData['end_date']).' 23:59:59';                
                $start_date = date('Y-m-d H:i:s',strtotime($start_date));
                $end_date = date('Y-m-d H:i:s',strtotime($end_date));
                $start_date = $this->formatDateTimeZone($start_date, 'long', $studentOffData['zone'],'UTC');
                $end_date = $this->formatDateTimeZone($end_date, 'long', $studentOffData['zone'],'UTC');
                $data = [
                    'title' => $studentOffData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 51,
                    'date_start' => $start_date,
                    'date_end' =>$end_date,
                    'fullday_flag' => isset($studentOffData['fullday_flag']) ? $studentOffData['fullday_flag'] : 'Y',
                    'description' => $studentOffData['description']
                ];

                $event = Event::create($data);
                
                foreach($studentOffData['student'] as $std){
                    $dataDetails = [
                        'event_id'   => $event->id,
                        'student_id' => $std,
                    ];
                    $eventDetails = EventDetails::create($dataDetails);
                }
                DB::commit();
                
                if($studentOffData['save_btn_more'] == 1){
                    return [
                        'status' => 1,
                        'message' =>  __('Successfully Registered')
                    ];
                }else{
                    return [
                        'status' => 2,
                        'message' =>  __('Successfully Registered')
                    ];
                }
                 
            }  
        }catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->all())->with('error', __('Internal server error'));
        }   

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editStudentOff(Request $request, $schoolId = null)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }

        $studentOffId = $request->route('id'); 
        $studentOffData = Event::active()->where(['id'=>$studentOffId, 'event_type' => 51])->first();
        $students = SchoolStudent::active()->where('school_id',$schoolId)->get();
        $studentOffList = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->leftJoin('school_student', 'school_student.id', '=', 'event_details.student_id')->where(['events.id'=>$studentOffId, 'event_type' => 51,'events.is_active' => 1])->get();
        if (!empty($studentOffData)){
            return view('pages.calendar.edit_student_off')->with(compact('studentOffList','studentOffId','studentOffData','schoolId','students'));
        }else{
            return redirect()->route('agenda',['school'=> $schoolId]);
        }
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editStudentOffAction(Request $request, $schoolId = null)
    {
        DB::beginTransaction();
        try{
            if ($request->isMethod('post')){
                $user = Auth::user();
                $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
                $school = School::active()->find($schoolId);
                if (empty($school)) {
                    return redirect()->route('schools')->with('error', __('School is not selected'));
                }

                $studentOffData = $request->all();
                $start_date = str_replace('/', '-', $studentOffData['start_date']).' 00:00:00';
                $end_date = str_replace('/', '-', $studentOffData['end_date']).' 23:59:59';
                $start_date = date('Y-m-d H:i:s',strtotime($start_date));
                $end_date = date('Y-m-d H:i:s',strtotime($end_date));
                $start_date = $this->formatDateTimeZone($start_date, 'long', $studentOffData['zone'],'UTC');
                $end_date = $this->formatDateTimeZone($end_date, 'long', $studentOffData['zone'],'UTC');
                $studoffId = $request->route('id'); 

                $data = [
                    'title' => $studentOffData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 51,
                    'date_start' => $start_date,
                    'date_end' =>$end_date,
                    'fullday_flag' => isset($studentOffData['fullday_flag']) ? $studentOffData['fullday_flag'] : 'Y',
                    'description' => $studentOffData['description']
                ];

                $event = Event::where('id', $studoffId)->update($data);
                EventDetails::where('event_id',$studoffId)->forceDelete();
                
                foreach($studentOffData['student'] as $std){
                    $dataDetails = [
                        'event_id'   => $studoffId,
                        'student_id' => $std,
                    ];
                    $eventDetails = EventDetails::create($dataDetails);;
                }
                
                DB::commit();
                return back()->with('success', __('Successfully Registered'));
            }  
        }catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->all())->with('error', __('Internal server error'));
        }   

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewStudentOff(Request $request, $schoolId = null)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }

        $studoffId = $request->route('id'); 
        $studentOffData = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->where(['events.id'=>$studoffId, 'event_type' => 51,'events.is_active' => 1])->first();
        $studentOffList = DB::table('events')->select('school_student.nickname')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->leftJoin('school_student', 'school_student.id', '=', 'event_details.student_id')->where(['events.id'=>$studoffId, 'event_type' => 51,'events.is_active' => 1])->get();
        return view('pages.calendar.view_student_off')->with(compact('studentOffData','studentOffList','schoolId','studoffId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function coachOff(Request $request, $schoolId = null)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $professors = SchoolTeacher::active()->onlyTeacher()->where('school_id',$schoolId)->get(); 
        return view('pages.calendar.add_coach_off')->with(compact('schoolId','professors'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function coachOffAction(Request $request, $schoolId = null)
    {
        try{
            if ($request->isMethod('post')){
                $user = Auth::user();
                $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
                $school = School::active()->find($schoolId);
                if (empty($school)) {
                    return redirect()->route('schools')->with('error', __('School is not selected'));
                }

                $coachOffData = $request->all();

                $start_date = str_replace('/', '-', $coachOffData['start_date']);
                $end_date = str_replace('/', '-', $coachOffData['end_date']).' 23:59:59';
                $start_date = date('Y-m-d H:i:s',strtotime($start_date));
                $end_date = date('Y-m-d H:i:s',strtotime($end_date));
                $start_date = $this->formatDateTimeZone($start_date, 'long', $coachOffData['zone'],'UTC');
                $end_date = $this->formatDateTimeZone($end_date, 'long', $coachOffData['zone'],'UTC');
                $data = [
                    'title' => $coachOffData['title'],
                    'school_id' => $schoolId,
                    'teacher_id' => $coachOffData['teacher_select'],
                    'event_type' => 50,
                    'date_start' => $start_date,
                    'date_end' =>$end_date,
                    'fullday_flag' => isset($coachOffData['fullday_flag']) ? $coachOffData['fullday_flag'] : 'Y',
                    'description' => $coachOffData['description']
                ];
                
                $event = Event::create($data);

                $dataDetails = [
                    'event_id' => $event->id,
                    'teacher_id' => $coachOffData['teacher_select'],
                ];
                
                $eventDetails = EventDetails::create($dataDetails);
                
                DB::commit();
                
                if($coachOffData['save_btn_more'] == 1){
                    return [
                        'status' => 1,
                        'message' =>  __('Successfully Registered')
                    ];
                }else{
                    return [
                        'status' => 2,
                        'message' =>  __('Successfully Registered')
                    ];
                }
            }  
        }catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->all())->with('error', __('Internal server error'));
        }   

        return $result;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editCoachOff(Request $request, $schoolId = null)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $coachoffId = $request->route('id'); 
        $coachoffData = Event::active()->where(['id'=>$coachoffId, 'event_type' => 50])->first();
        $professors = SchoolTeacher::active()->onlyTeacher()->where('school_id',$schoolId)->get(); 
        if (!empty($coachoffData)){
            return view('pages.calendar.edit_coach_off')->with(compact('coachoffId','coachoffData','schoolId','professors','coachoffId'));    
        }else{
            return redirect()->route('agenda',['school'=> $schoolId]);
        }
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editCoachOffAction(Request $request, $schoolId = null)
    {
        try{
            if ($request->isMethod('post')){
                $user = Auth::user();
                $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
                $school = School::active()->find($schoolId);
                if (empty($school)) {
                    return redirect()->route('schools')->with('error', __('School is not selected'));
                }

                $coachOffData = $request->all();

                $start_date = str_replace('/', '-', $coachOffData['start_date']);
                $end_date = str_replace('/', '-', $coachOffData['end_date']).' 23:59:59';
                $start_date = date('Y-m-d H:i:s',strtotime($start_date));
                $end_date = date('Y-m-d H:i:s',strtotime($end_date));
                $start_date = $this->formatDateTimeZone($start_date, 'long', $coachOffData['zone'],'UTC');
                $end_date = $this->formatDateTimeZone($end_date, 'long', $coachOffData['zone'],'UTC');
                $coachoffId = $request->route('id'); 

                $data = [
                    'title' => $coachOffData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 50,
                    'date_start' => $start_date,
                    'date_end' =>$end_date,
                    'fullday_flag' => isset($coachOffData['fullday_flag']) ? $coachOffData['fullday_flag'] : 'Y',
                    'description' => $coachOffData['description'],
                    'teacher_id' => $coachOffData['teacher_select']
                ];
                
                $event = Event::where('id', $coachoffId)->update($data);

                $dataDetails = [
                    'event_id' => $coachoffId,
                    'teacher_id' => $coachOffData['teacher_select'],
                ];
                
                $eventDetails = EventDetails::where('event_id', $coachoffId)->update($dataDetails);
                
                DB::commit();
                return back()->with('success', __('Successfully Registered'));
            }  
        }catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->all())->with('error', __('Internal server error'));
        }   

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewCoachOff(Request $request, $schoolId = null)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $coachoffId = $request->route('id'); 
        $coachoffData = DB::table('events')->leftJoin('school_teacher', 'school_teacher.teacher_id', '=', 'events.teacher_id')->where(['events.id'=>$coachoffId, 'event_type' => 50,'events.is_active' => 1])->first();
        return view('pages.calendar.view_coach_off')->with(compact('coachoffData','schoolId'));
    }

    public function StudentAttendAction(Request $request, $schoolId = null)
    {
        DB::beginTransaction();
        try{
            if ($request->isMethod('post')){
                $user = Auth::user();
                $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
                $school = School::active()->find($schoolId);
                if (empty($school)) {
                    return redirect()->route('schools')->with('error', __('School is not selected'));
                }

                $lessonlId = $request->route('id');
                $lessonData = $request->all();
                $type = $lessonData['type'];
              
                if($type == 1){
                    $dataDetails = [
                        'participation_id' => $lessonData['typeId']
                    ];
                    $eventDetails = EventDetails::where(['event_id'=> $lessonlId,'student_id'=> $lessonData['stuId']])->update($dataDetails);
                }else if($type == 2){
                    foreach($lessonData['data'] as $value){
                        $dataDetails = [
                            'participation_id' => $value['typeId']
                        ];
                        $eventDetails = EventDetails::where(['event_id'=> $lessonlId,'student_id'=> $value['stuId']])->update($dataDetails);
                    }    
                }
                DB::commit();
                 
                 return back()->with('success', __('Successfully Registered'));
            }  
        }catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->all())->with('error', __('Internal server error'));
        }   

        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * check if price exist for student 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lessonPriceCheck(Request $request)
    {   
        if ($request->isMethod('post')){
            $lessonData = $request->all();
            $stu_num = explode("_", $lessonData['sevent_price']);    
            $lessonPriceTeacher = LessonPriceTeacher::active()->where(['event_category_id'=>$lessonData['category_select'],'lesson_price_id'=>$stu_num[1],'teacher_id'=>$lessonData['teacher_select']])->first();
            if (!empty($lessonPriceTeacher)) {
                return [
                    'status' => 1,
                    'message' =>  __('Successfully get price for this teacher')
                ];
            }else{
                return [
                    'status' => 0,
                    'message' =>  __('No price for this teacher')
                ];
            }
        }

    }
}
