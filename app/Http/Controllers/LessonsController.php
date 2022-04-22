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
use App\Models\SchoolTeacher;
use App\Models\SchoolStudent;
use App\Models\EventCategory;
use App\Models\Location;
use App\Models\LessonPrice;
use App\Models\Currency;
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
        $professors = SchoolTeacher::active()->where('school_id',$schoolId)->get();
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

                $studentOffData = $request->all();
                $start_date = str_replace('/', '-', $studentOffData['start_date']).' '.$studentOffData['start_time'];
                $end_date = str_replace('/', '-', $studentOffData['end_date']).' '.$studentOffData['end_time'];

                $data = [
                    'title' => $studentOffData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 100,
                    'date_start' => date('Y-m-d H:i:s',strtotime($start_date)),
                    'date_end' => date('Y-m-d H:i:s',strtotime($end_date)),
                    'price_currency' => $studentOffData['sprice_currency'],
                    'price_amount_buy' => $studentOffData['sprice_amount_buy'],
                    'price_amount_sell' => $studentOffData['sprice_amount_sell'],
                    'extra_charges' => $studentOffData['extra_charges'],
                    'fullday_flag' => !empty($studentOffData['fullday_flag']) ? $studentOffData['fullday_flag'] : null,
                    'description' => $studentOffData['description'],
                    'location_id' => $studentOffData['location'],
                    'teacher_id' => $studentOffData['teacher_select'],
                ];

                $event = Event::create($data);
                foreach($studentOffData['student'] as $std){
                    $dataDetails = [
                        'event_id'   => $event->id,
                        'teacher_id' => $studentOffData['teacher_select'],
                        'student_id' => $std,
                        'buy_price' => $studentOffData['sprice_amount_buy'],
                        'sell_price' => $studentOffData['sprice_amount_sell'],
                        'price_currency' => $studentOffData['sprice_currency']
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
        $professors = SchoolTeacher::active()->where('school_id',$schoolId)->get();
        $students = SchoolStudent::active()->where('school_id',$schoolId)->get();
        $lessonPrice = LessonPrice::active()->get();
        $currency = Currency::active()->ByCountry($school->country_code)->get();

        if (!empty($eventData)){
            return view('pages.calendar.edit_event')->with(compact('eventId','eventData','relationData','schoolId','eventCategory','locations','professors','students','lessonPrice','currency'));
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
                $studentOffData = $request->all();
                $start_date = str_replace('/', '-', $studentOffData['start_date']).' '.$studentOffData['start_time'];
                $end_date = str_replace('/', '-', $studentOffData['end_date']).' '.$studentOffData['end_time'];

                $data = [
                    'title' => $studentOffData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 100,
                    'date_start' => date('Y-m-d H:i:s',strtotime($start_date)),
                    'date_end' => date('Y-m-d H:i:s',strtotime($end_date)),
                    'price_currency' => !empty($studentOffData['sprice_currency']) ? $studentOffData['sprice_currency'] : null,
                    'price_amount_buy' => $studentOffData['sprice_amount_buy'],
                    'price_amount_sell' => $studentOffData['sprice_amount_sell'],
                    'extra_charges' => $studentOffData['extra_charges'],
                    'fullday_flag' => !empty($studentOffData['fullday_flag']) ? $studentOffData['fullday_flag'] : null,
                    'description' => $studentOffData['description'],
                    'location_id' => $studentOffData['location'],
                    'teacher_id' => $studentOffData['teacher_select'],
                ];

                $event = Event::where('id', $eventId)->update($data);

                foreach($studentOffData['student'] as $std){
                    $dataDetails = [
                        'event_id'   => $eventId,
                        'teacher_id' => $studentOffData['teacher_select'],
                        'student_id' => $std,
                        'buy_price' => $studentOffData['sprice_amount_buy'],
                        'sell_price' => $studentOffData['sprice_amount_sell'],
                        'price_currency' => !empty($studentOffData['sprice_currency']) ? $studentOffData['sprice_currency'] : null,
                    ];
                    $eventDetails = EventDetails::where('event_id', $eventId)->update($dataDetails);
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
        $studentOffList = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->leftJoin('school_student', 'school_student.id', '=', 'event_details.student_id')->where(['events.id'=>$eventId, 'event_type' => 100,'events.is_active' => 1])->get();
        $professors = DB::table('events')->select('school_teacher.nickname')->leftJoin('school_teacher', 'school_teacher.teacher_id', '=', 'events.teacher_id')->where(['events.id'=>$eventId, 'event_type' => 100,'events.is_active' => 1])->first();
        $eventCategory = DB::table('events')->select('event_categories.title')->leftJoin('event_categories', 'event_categories.id', '=', 'events.event_category')->where(['events.id'=>$eventId, 'event_type' => 100,'events.is_active' => 1])->first();
        $locations = DB::table('locations')->select('locations.title')->leftJoin('events', 'events.location_id', '=', 'locations.id')->where(['events.id'=>$eventId, 'event_type' => 100,'events.is_active' => 1,'locations.is_active' => 1])->first();
        $lessonPrice = LessonPrice::active()->get();
        return view('pages.calendar.view_event')->with(compact('eventData','schoolId','eventCategory','locations','professors','studentOffList','lessonPrice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addLesson(Request $request, $schoolId = null)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $eventCategory = EventCategory::active()->where('school_id',$schoolId)->get();
        $locations = Location::active()->where('school_id',$schoolId)->get();
        $professors = SchoolTeacher::active()->where('school_id',$schoolId)->get();
        $students = SchoolStudent::active()->where('school_id',$schoolId)->get();
        $lessonPrice = LessonPrice::active()->get();
        $currency = Currency::active()->ByCountry($school->country_code)->get();

        return view('pages.calendar.add_lesson')->with(compact('schoolId','eventCategory','locations','professors','students','lessonPrice','currency'));
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

                $studentOffData = $request->all();
                $start_date = str_replace('/', '-', $studentOffData['start_date']).' '.$studentOffData['start_time'];
                $end_date = str_replace('/', '-', $studentOffData['end_date']).' '.$studentOffData['end_time'];
                $stu_num = explode("_", $studentOffData['sevent_price']);


                $data = [
                    'title' => $studentOffData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 10,
                    'event_category' => $studentOffData['category_select'],
                    'teacher_id' => $studentOffData['teacher_select'],
                    'date_start' => date('Y-m-d H:i:s',strtotime($start_date)),
                    'date_end' => date('Y-m-d H:i:s',strtotime($end_date)),
                    'duration_minutes' => $studentOffData['duration'],
                    'price_currency' => isset($studentOffData['sprice_currency']) ? $studentOffData['sprice_currency'] : null,
                    'price_amount_buy' => $studentOffData['sprice_amount_buy'],
                    'price_amount_sell' => $studentOffData['sprice_amount_sell'],
                    'fullday_flag' => isset($studentOffData['fullday_flag']) ? $studentOffData['fullday_flag'] : null,
                    'no_of_students' => isset($stu_num) ? $stu_num[1] : null,
                    'description' => $studentOffData['description'],
                    'location_id' => $studentOffData['location']
                ];

                $event = Event::create($data);
                
                foreach($studentOffData['student'] as $std){
                    $dataDetails = [
                        'event_id'   => $event->id,
                        'teacher_id' => $studentOffData['teacher_select'],
                        'student_id' => $std,
                        'buy_price' => $studentOffData['sprice_amount_buy'],
                        'sell_price' => $studentOffData['sprice_amount_sell'],
                        'price_currency' => isset($studentOffData['sprice_currency']) ? $studentOffData['sprice_currency'] : null
                    ];
                    $eventDetails = EventDetails::create($dataDetails);
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
        }

        $lessonlId = $request->route('lesson'); 
        $lessonData = Event::active()->where(['id'=>$lessonlId, 'event_type' => 10])->first();
        $relationData = EventDetails::active()->where(['event_id'=>$lessonlId])->first();
        $eventCategory = EventCategory::active()->where('school_id',$schoolId)->get();
        $locations = Location::active()->where('school_id',$schoolId)->get();
        $professors = SchoolTeacher::active()->where('school_id',$schoolId)->get();
        $students = SchoolStudent::active()->where('school_id',$schoolId)->get();
        $lessonPrice = LessonPrice::active()->get();
        $currency = Currency::active()->ByCountry($school->country_code)->get();
        if (!empty($lessonData)){
            return view('pages.calendar.edit_lesson')->with(compact('lessonlId','lessonData','relationData','schoolId','eventCategory','locations','professors','students','lessonPrice','currency'));
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
                $studentOffData = $request->all();
                $start_date = str_replace('/', '-', $studentOffData['start_date']).' '.$studentOffData['start_time'];
                $end_date = str_replace('/', '-', $studentOffData['end_date']).' '.$studentOffData['end_time'];
                $stu_num = explode("_", $studentOffData['sevent_price']);

                $data = [
                    'title' => $studentOffData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 10,
                    'event_category' => $studentOffData['category_select'],
                    'teacher_id' => $studentOffData['teacher_select'],
                    'date_start' => date('Y-m-d H:i:s',strtotime($start_date)),
                    'date_end' => date('Y-m-d H:i:s',strtotime($end_date)),
                    'duration_minutes' => $studentOffData['duration'],
                    'price_currency' => isset($studentOffData['sprice_currency']) ? $studentOffData['sprice_currency'] : null,
                    'price_amount_buy' => $studentOffData['sprice_amount_buy'],
                    'price_amount_sell' => $studentOffData['sprice_amount_sell'],
                    'fullday_flag' => isset($studentOffData['fullday_flag']) ? $studentOffData['fullday_flag'] : null,
                    'no_of_students' => isset($stu_num) ? $stu_num[1] : null,
                    'description' => $studentOffData['description'],
                    'location_id' => $studentOffData['location']
                ];

                $event = Event::where('id', $lessonlId)->update($data);

                foreach($studentOffData['student'] as $std){
                    $dataDetails = [
                        'event_id'   => $lessonlId,
                        'teacher_id' => $studentOffData['teacher_select'],
                        'student_id' => $std,
                        'buy_price' => $studentOffData['sprice_amount_buy'],
                        'sell_price' => $studentOffData['sprice_amount_sell'],
                        'price_currency' => isset($studentOffData['sprice_currency']) ? $studentOffData['sprice_currency'] : null
                    ];
                    $eventDetails = EventDetails::where('event_id', $lessonlId)->update($dataDetails);
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
        $studentOffList = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->leftJoin('school_student', 'school_student.id', '=', 'event_details.student_id')->where(['events.id'=>$lessonlId, 'event_type' => 10,'events.is_active' => 1])->get();
        $professors = DB::table('events')->select('school_teacher.nickname')->leftJoin('school_teacher', 'school_teacher.teacher_id', '=', 'events.teacher_id')->where(['events.id'=>$lessonlId, 'event_type' => 10,'events.is_active' => 1])->first();
        $lessonCategory = DB::table('events')->select('event_categories.title')->leftJoin('event_categories', 'event_categories.id', '=', 'events.event_category')->where(['events.id'=>$lessonlId, 'event_type' => 10,'events.is_active' => 1])->first();
        $locations = DB::table('locations')->select('locations.title')->leftJoin('events', 'events.location_id', '=', 'locations.id')->where(['events.id'=>$lessonlId, 'event_type' => 10,'events.is_active' => 1,'locations.is_active' => 1])->first();
        $lessonPrice = LessonPrice::active()->get();
        return view('pages.calendar.view_lesson')->with(compact('lessonData','schoolId','lessonCategory','locations','professors','studentOffList','lessonPrice'));
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
                $end_date = str_replace('/', '-', $studentOffData['end_date']);

                $data = [
                    'title' => $studentOffData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 51,
                    'date_start' => date('Y-m-d H:i:s',strtotime($start_date)),
                    'date_end' =>date('Y-m-d H:i:s',strtotime($end_date)),
                    'fullday_flag' => isset($studentOffData['fullday_flag']) ? $studentOffData['fullday_flag'] : null,
                    'description' => $studentOffData['description']
                ];

                $event = Event::create($data);
                
                foreach($studentOffData['student'] as $std){
                    $dataDetails = [
                        'event_id'   => $event->id,
                        'teacher_id' => $studentOffData['teacher_select'],
                        'student_id' => $std,
                    ];
                    $eventDetails = EventDetails::create($dataDetails);
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
        if (!empty($studentOffData)){
            return view('pages.calendar.edit_student_off')->with(compact('studentOffId','studentOffData','schoolId','students'));
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
                $start_date = str_replace('/', '-', $studentOffData['start_date']);
                $end_date = str_replace('/', '-', $studentOffData['end_date']);
                $studoffId = $request->route('id'); 

                $data = [
                    'title' => $studentOffData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 51,
                    'date_start' => date('Y-m-d H:i:s',strtotime($start_date)),
                    'date_end' =>date('Y-m-d H:i:s',strtotime($end_date)),
                    'fullday_flag' => isset($studentOffData['fullday_flag']) ? $studentOffData['fullday_flag'] : null,
                    'description' => $studentOffData['description']
                ];

                $event = Event::where('id', $studoffId)->update($data);

                foreach($studentOffData['student'] as $std){
                    $dataDetails = [
                        'event_id'   => $studoffId,
                        'student_id' => $std,
                    ];
                    $eventDetails = EventDetails::where('event_id', $studoffId)->update($dataDetails);;
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
        return view('pages.calendar.view_student_off')->with(compact('studentOffData','studentOffList','schoolId'));
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
        $professors = SchoolTeacher::active()->where('school_id',$schoolId)->get(); 
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
                $end_date = str_replace('/', '-', $coachOffData['end_date']);

                $data = [
                    'title' => $coachOffData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 50,
                    'date_start' => date('Y-m-d H:i:s',strtotime($start_date)),
                    'date_end' =>date('Y-m-d H:i:s',strtotime($end_date)),
                    'fullday_flag' => isset($coachOffData['fullday_flag']) ? $coachOffData['fullday_flag'] : null,
                    'description' => $coachOffData['description']
                ];
                
                $event = Event::create($data);

                $dataDetails = [
                    'event_id' => $event->id,
                    'teacher_id' => $coachOffData['teacher_select'],
                ];
                
                $eventDetails = EventDetails::create($dataDetails);
                
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
        $professors = SchoolTeacher::active()->where('school_id',$schoolId)->get(); 
        if (!empty($coachoffData)){
            return view('pages.calendar.edit_coach_off')->with(compact('coachoffId','coachoffData','schoolId','professors'));    
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

                $start_date = str_replace('/', '-', $studentOffData['start_date']);
                $end_date = str_replace('/', '-', $studentOffData['end_date']);
                $coachoffId = $request->route('id'); 

                $data = [
                    'title' => $coachOffData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 50,
                    'date_start' => date('Y-m-d H:i:s',strtotime($start_date)),
                    'date_end' =>date('Y-m-d H:i:s',strtotime($end_date)),
                    'fullday_flag' => isset($coachOffData['fullday_flag']) ? $coachOffData['fullday_flag'] : null,
                    'description' => $coachOffData['description']
                ];
                
                $event = Event::where('id', $coachoffId)->update($data);

                $dataDetails = [
                    'event_id' => $event->id,
                    'teacher_id' => $coachOffData['teacher_select'],
                ];
                
                $eventDetails = EventDetails::where('event_id', $event->id)->update($dataDetails);
                
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
}
