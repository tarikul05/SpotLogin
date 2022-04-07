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
                    'date_end' => date('Y-m-d H:i:s',strtotime($end_date)),
                    'price_currency' => $studentOffData['sprice_currency'],
                    'price_amount_buy' => $studentOffData['sprice_amount_buy'],
                    'price_amount_sell' => $studentOffData['sprice_amount_sell'],
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
        return view('pages.calendar.student_off')->with(compact('schoolId','students'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentOffAction(Request $request, $schoolId = null)
    {
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
        return view('pages.calendar.coach_off')->with(compact('schoolId','professors'));
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
