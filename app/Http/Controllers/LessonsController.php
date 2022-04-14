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
        return view('pages.calendar.add_event')->with(compact('schoolId','eventCategory','locations','professors','students','lessonPrice'));
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
                $start_date = str_replace('/', '-', $studentOffData['start_date']);
                $end_date = str_replace('/', '-', $studentOffData['end_date']);

                $data = [
                    'title' => $studentOffData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 100,
                    'date_start' => date('Y-m-d H:i:s',strtotime($start_date)),
                    'date_end' => date('Y-m-d H:i:s',strtotime($end_date)),
                    'duration_minutes' => $studentOffData['duration'],
                    'price_currency' => $studentOffData['sprice_currency'],
                    'price_amount_buy' => $studentOffData['sprice_amount_buy'],
                    'price_amount_sell' => $studentOffData['sprice_amount_sell'],
                    'fullday_flag' => isset($studentOffData['fullday_flag']) ? $studentOffData['fullday_flag'] : null,
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
                        'sell_price' => $studentOffData['sprice_amount_sell']
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
        $eventlId = $request->route('event'); 
        $eventData = Event::active()->where(['id'=>$eventlId, 'event_type' => 100])->first();
        $relationData = EventDetails::active()->where(['event_id'=>$eventlId])->first();
        $eventCategory = EventCategory::active()->where('school_id',$schoolId)->get();
        $locations = Location::active()->where('school_id',$schoolId)->get();
        $professors = SchoolTeacher::active()->where('school_id',$schoolId)->get();
        $students = SchoolStudent::active()->where('school_id',$schoolId)->get();
        $lessonPrice = LessonPrice::active()->get();
        return view('pages.calendar.edit_event')->with(compact('eventData','relationData','schoolId','eventCategory','locations','professors','students','lessonPrice'));
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

                $studentOffData = $request->all();
                $start_date = str_replace('/', '-', $studentOffData['start_date']);
                $end_date = str_replace('/', '-', $studentOffData['end_date']);

                $data = [
                    'title' => $studentOffData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 100,
                    'date_start' => date('Y-m-d H:i:s',strtotime($start_date)),
                    'date_end' => date('Y-m-d H:i:s',strtotime($end_date)),
                    'duration_minutes' => $studentOffData['duration'],
                    'price_currency' => $studentOffData['sprice_currency'],
                    'price_amount_buy' => $studentOffData['sprice_amount_buy'],
                    'price_amount_sell' => $studentOffData['sprice_amount_sell'],
                    'fullday_flag' => isset($studentOffData['fullday_flag']) ? $studentOffData['fullday_flag'] : null,
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
                        'sell_price' => $studentOffData['sprice_amount_sell']
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
        return view('pages.calendar.add_lesson')->with(compact('schoolId','eventCategory','locations','professors','students','lessonPrice'));
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
                $start_date = str_replace('/', '-', $studentOffData['start_date']);
                $end_date = str_replace('/', '-', $studentOffData['end_date']);
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
                        'sell_price' => $studentOffData['sprice_amount_sell']
                    ];
                    $eventDetails = EventDetails::create($dataDetails);
                }
                DB::commit();
                 
                $result = array(
                    "status"     => 1,
                    'message' => __('Successfully Registered')
                );
            }  
        }catch (Exception $e) {
            DB::rollBack();
            $result= [
                'status' => 0,
                'message' =>  __('Internal server error')
            ];
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
        return view('pages.calendar.edit_lesson')->with(compact('lessonlId','lessonData','relationData','schoolId','eventCategory','locations','professors','students','lessonPrice'));
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
                $start_date = str_replace('/', '-', $studentOffData['start_date']);
                $end_date = str_replace('/', '-', $studentOffData['end_date']);
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
                        'event_id'   => $event->id,
                        'teacher_id' => $studentOffData['teacher_select'],
                        'student_id' => $std,
                        'buy_price' => $studentOffData['sprice_amount_buy'],
                        'sell_price' => $studentOffData['sprice_amount_sell']
                    ];
                    $eventDetails = EventDetails::where('event_id', $event->id)->update($dataDetails);
                }
                DB::commit();
                 
                $result = array(
                    "status"     => 1,
                    'message' => __('Successfully Registered')
                );
            }  
        }catch (Exception $e) {
            DB::rollBack();
            $result= [
                'status' => 0,
                'message' =>  __('Internal server error')
            ];
        }   

        return $result;
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

                $dataDetails = [
                    'event_id' => $event->id,
                    'school_id' => $schoolId,
                    'teacher_id' => $schoolId,
                ];
                
                $eventDetails = EventDetails::create($dataDetails);
                DB::commit();
                 
                $result = array(
                    "status"     => 1,
                    'message' => __('Successfully Registered')
                );
            }  
        }catch (Exception $e) {
            DB::rollBack();
            $result= [
                'status' => 0,
                'message' =>  __('Internal server error')
            ];
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

        $studoffId = $request->route('id'); 
        $studentOffData = Event::active()->where(['id'=>$studoffId, 'event_type' => 51])->first();
        $students = SchoolStudent::active()->where('school_id',$schoolId)->get();
        return view('pages.calendar.edit_student_off')->with(compact('studentOffData','schoolId','students'));
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

                $dataDetails = [
                    'event_id' => $event->id,
                    'school_id' => $schoolId,
                    'teacher_id' => $schoolId,
                ];
                
                $eventDetails = EventDetails::where('event_id', $event->id)->update($dataDetails);;
                DB::commit();
                 
                $result = array(
                    "status"     => 1,
                    'message' => __('Successfully Registered')
                );
            }  
        }catch (Exception $e) {
            DB::rollBack();
            $result= [
                'status' => 0,
                'message' =>  __('Internal server error')
            ];
        }   

        return $result;
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
                $result = array(
                    "status"     => 1,
                    'message' => __('Successfully Registered')
                );
            }  
        }catch (Exception $e) {
            DB::rollBack();
            $result= [
                'status' => 0,
                'message' =>  __('Internal server error')
            ];
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
        return view('pages.calendar.edit_coach_off')->with(compact('coachoffData','schoolId','professors'));
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
                $end_date = str_replace('/', '-', $coachOffData['end_date']);
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
                $result = array(
                    "status"     => 1,
                    'message' => __('Successfully Registered')
                );
            }  
        }catch (Exception $e) {
            DB::rollBack();
            $result= [
                'status' => 0,
                'message' =>  __('Internal server error')
            ];
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
}
