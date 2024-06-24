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
use App\Models\ParentStudent;
use App\Models\Student;
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

        $currencyInit = new currency();
        $currency = $currencyInit->getCurrencyByCountry($school->country_code,true);

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
                $start_date = str_replace('/', '-', $eventData['start_date']);
                $end_date = str_replace('/', '-', $eventData['end_date']);
                // dd($start_date, $end_date);
                $start_date = date('Y-m-d H:i:s',strtotime($start_date));
                $end_date = date('Y-m-d',strtotime($end_date)).' 23:59:59';

                $start_date = $this->formatDateTimeZone($start_date, 'long', $eventData['zone'],'UTC');
                $end_date = $this->formatDateTimeZone($end_date, 'long', $eventData['zone'],'UTC');
                $stu_num = count($eventData['student']);

                $teacher_id = $eventData['teacher_select'];
                $studentCount = !empty($eventData['student']) ? count($eventData['student']) : 0 ;
                if(empty($eventData['category_select'])) {
                    $eventData['category_select'] = 0;
                }
                $eventInit = new Event;
                $eventPrice = $eventInit->priceCalculations(['event_category_id'=>$eventData['category_select'],'teacher_id'=>$teacher_id,'student_count'=>$studentCount]);

                if($user->isTeacher()){
                    $attendBuyPrice =  isset($eventData['sprice_amount_sell']) ? $eventData['sprice_amount_sell'] : null;
                    $indBuyPrice = isset($eventData['sprice_amount_sell']) ? (($eventData['sprice_amount_sell'])/($stu_num)) : null;
                    $attendSellPrice = isset($eventData['sprice_amount_sell']) ? $eventData['sprice_amount_sell'] : null;
                }else{
                    $attendBuyPrice = isset($eventData['sprice_amount_buy']) ? $eventData['sprice_amount_buy'] : null;
                    $indBuyPrice = isset($eventData['sprice_amount_buy']) ? (($eventData['sprice_amount_buy'])/($stu_num)) : null;
                    $attendSellPrice = isset($eventData['sprice_amount_sell']) ? $eventData['sprice_amount_sell'] : null;
                }

                $data = [
                    'title' => $eventData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 100,
                    'date_start' => $start_date,
                    'date_end' => $end_date,
                    'price_currency' => isset($eventData['sprice_currency']) ? $eventData['sprice_currency'] : null,
                    'price_amount_buy' => $attendBuyPrice,
                    'price_amount_sell' => $attendSellPrice,
                    'extra_charges' => isset($eventData['extra_charges']) ? $eventData['extra_charges']: null,
                    'fullday_flag' => 'Y',
                    'description' => $eventData['description'],
                    'location_id' => isset($eventData['location']) ? $eventData['location'] : null,
                    'teacher_id' => $eventData['teacher_select'],
                    'no_of_students' => $studentCount,
                    'event_invoice_type' => isset($eventData['event_invoice_type']) ? $eventData['event_invoice_type'] : 'T',
                ];

                $event = Event::create($data);
                if (!empty($eventData['student'])) {
                    foreach($eventData['student'] as $std){
                        $dataDetails = [
                            'event_id'   => $event->id,
                            'teacher_id' => $eventData['teacher_select'],
                            'student_id' => $std,
                            'buy_total' => $attendBuyPrice,
                            'buy_price' => $indBuyPrice,
                            'sell_price' => $attendSellPrice,
                            'sell_total' => $attendSellPrice,
                            'costs_1' => isset($eventData['extra_charges']) ? $eventData['extra_charges']: null,
                            'price_currency' => isset($eventData['sprice_currency']) ? $eventData['sprice_currency'] : null,
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
        if ($eventData->is_locked) {
            return Redirect::to($schoolId.'/view-event/'.$eventId);
        }
        // dd($eventData);
        $relationData = EventDetails::active()->where(['event_id'=>$eventId])->first();
        $eventCategory = EventCategory::active()->where('school_id',$schoolId)->get();
        $locations = Location::active()->where('school_id',$schoolId)->get();
        $professors = SchoolTeacher::active()->onlyTeacher()->where('school_id',$schoolId)->get();
        $students = SchoolStudent::active()->where('school_id',$schoolId)->get();
        $studentOffList = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->leftJoin('school_student', 'school_student.student_id', '=', 'event_details.student_id')->where(['events.id'=>$eventId, 'event_type' => 100,'events.is_active' => 1])->groupBy('school_student.student_id')->get();
        $lessonPrice = LessonPrice::active()->get();
        $initCurrency = new Currency();
        $currency = $initCurrency->getCurrencyByCountry($school->country_code,true);
        $reqData = $request->all();
        $redirect_url = '';
        if (!empty($reqData['redirect_url'])) {
            $redirect_url = $reqData['redirect_url'].'&tab=tab_3';

        }
        if (!empty($eventData)){
            return view('pages.calendar.edit_event')->with(compact('eventId','eventData','relationData','schoolId','eventCategory','locations','professors','studentOffList','students','lessonPrice','currency','redirect_url'));
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
                $redirectUrl = $request->input('redirect_url');
                $eventId = $request->route('event');
                $eventData = $request->all();
                $start_date = str_replace('/', '-', $eventData['start_date']);
                $end_date = str_replace('/', '-', $eventData['end_date']);
                $start_date = date('Y-m-d H:i:s',strtotime($start_date));
                $end_date = date('Y-m-d',strtotime($end_date)).' 23:59:59';
                $start_date = $this->formatDateTimeZone($start_date, 'long', $eventData['zone'],'UTC');
                $end_date = $this->formatDateTimeZone($end_date, 'long', $eventData['zone'],'UTC');
                $stu_num = count($eventData['student']);

                if($user->isTeacher()){
                    $attendBuyPrice =  isset($eventData['sprice_amount_sell']) ? $eventData['sprice_amount_sell'] : null;
                    $indBuyPrice = isset($eventData['sprice_amount_sell']) ? (($eventData['sprice_amount_sell'])/($stu_num)) : null;
                    $attendSellPrice = isset($eventData['sprice_amount_sell']) ? $eventData['sprice_amount_sell'] : null;
                }else{
                    $attendBuyPrice = isset($eventData['sprice_amount_buy']) ? $eventData['sprice_amount_buy'] : null;
                    $indBuyPrice = isset($eventData['sprice_amount_buy']) ? (($eventData['sprice_amount_buy'])/($stu_num)) : null;
                    $attendSellPrice = isset($eventData['sprice_amount_sell']) ? $eventData['sprice_amount_sell'] : null;
                }

                $data = [
                    'title' => $eventData['title'],
                    'school_id' => $schoolId,
                    'event_type' => 100,
                    'date_start' => $start_date,
                    'date_end' => $end_date,
                    'price_currency' => !empty($eventData['sprice_currency']) ? $eventData['sprice_currency'] : null,
                    'price_amount_buy' => $attendBuyPrice,
                    'price_amount_sell' => $attendSellPrice,
                    'extra_charges' => isset($eventData['extra_charges']) ? $eventData['extra_charges']: null,
                    'fullday_flag' => 'Y',
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
                        'buy_total' => $attendBuyPrice,
                        'buy_price' => $indBuyPrice,
                        'sell_price' => $attendSellPrice,
                        'sell_total' => $attendSellPrice,
                        'costs_1' => isset($eventData['extra_charges']) ? $eventData['extra_charges']: null,
                        'price_currency' => isset($eventData['sprice_currency']) ? $eventData['sprice_currency'] : null,
                        'participation_id' => !empty($eventData['attnValue'][$std]) ? $eventData['attnValue'][$std] : 0,
                    ];
                    $eventDetails = EventDetails::create($dataDetails);
                }

                DB::commit();

                if(isset($eventData['validate']) && !empty($eventData['validate'])){
                    Event::validate(['event_id'=>$eventId],1);

                    if (!empty($redirectUrl)) {
                        return redirect($redirectUrl);
                    } else {
                        return Redirect::to($schoolId.'/view-event/'.$eventId);
                    }
                }else{
                 return redirect()->route('agenda',['school'=> $schoolId])->with('success', __('Successfully Updated !'));
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
        $studentOffList = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->leftJoin('school_student', 'school_student.student_id', '=', 'event_details.student_id')->where(['events.id'=>$eventId, 'event_type' => 100,'events.is_active' => 1])->groupBy('school_student.student_id')->get();

        //echo '<pre>';print_r($eventData);exit;

        $professors = SchoolTeacher::where(['teacher_id'=>$eventData->teacher_id, 'school_id' => $schoolId])->first();

        $locations = DB::table('locations')->select('locations.title')->leftJoin('events', 'events.location_id', '=', 'locations.id')->where(['events.id'=>$eventId, 'event_type' => 100,'events.is_active' => 1,'locations.is_active' => 1])->first();
        $lessonPrice = LessonPrice::active()->get();
        return view('pages.calendar.view_event')->with(compact('eventData','schoolId','locations','professors','studentOffList','lessonPrice','eventId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addLesson(Request $request, $schoolId = null)
    {
        $lessonlId = $request->input('id');
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

        $currencyInit = new Currency();
        $currency = $currencyInit->getCurrencyByCountry($school->country_code);

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
        
                $lessonData['sprice_amount_buy'] = isset($lessonData['sprice_amount_buy']) ? $lessonData['sprice_amount_buy'] : 0;
                $lessonData['sprice_amount_sell'] = isset($lessonData['sprice_amount_sell']) ? $lessonData['sprice_amount_sell'] : 0;

                $start_date = str_replace('/', '-', $lessonData['start_date']).' '.$lessonData['start_time'];
                $end_date = str_replace('/', '-', $lessonData['end_date']).' '.$lessonData['end_time'];
                $start_date = date('Y-m-d H:i:s',strtotime($start_date));
                $end_date = date('Y-m-d H:i:s',strtotime($end_date));
                $start_date = $this->formatDateTimeZone($start_date, 'long', $lessonData['zone'],'UTC');
                $end_date = $this->formatDateTimeZone($end_date, 'long', $lessonData['zone'],'UTC');
                $stu_num = !empty($lessonData['sevent_price']) ? explode("_", $lessonData['sevent_price']) : 0;
                $eventCategory = EventCategory::active()->where('id',$lessonData['category_select'])->first();

                $teacher_id = $lessonData['teacher_select'];
                $studentCount = !empty($lessonData['student']) ? count($lessonData['student']) : 1 ;
                if($lessonData['category_select'] === "") {
                    $lessonData['category_select'] = 0;
                }
                $eventInit = new Event();
                if($user->isSchoolAdmin() || $user->isTeacherSchoolAdmin()) {
                    $eventPrice = $eventInit->priceCalculationsSchool(['event_category_id'=>$lessonData['category_select'],'teacher_id'=>$teacher_id,'student_count'=>$studentCount]);
                } else {
                    $eventPrice = $eventInit->priceCalculations(['event_category_id'=>$lessonData['category_select'],'teacher_id'=>$teacher_id,'student_count'=>$studentCount]);
                }

                if(!empty($studentCount)){
                    $buyPriceCal = ($eventPrice['price_buy']*($lessonData['duration']/60))/$studentCount;
                }else{
                    $buyPriceCal = ($eventPrice['price_buy']*($lessonData['duration']/60));
                }

                $sellPriceCal = ($eventPrice['price_sell']*($lessonData['duration']/60));



                if($lessonData['sis_paying'] == 1 && $lessonData['student_sis_paying'] == 1 ){
                   $attendBuyPrice = ($lessonData['sprice_amount_buy']*($lessonData['duration']/60))/$studentCount;
                   $attendSellPrice = $lessonData['sprice_amount_sell'];
                }else{
                    $attendBuyPrice = $buyPriceCal;
                    $attendSellPrice = $sellPriceCal;
                }

                if ($lessonData['student_sis_paying'] == 1) {
                    $attendSellPrice = ($eventPrice['price_sell']*($lessonData['duration']/60));
                }

                if (isset($eventCategory->t_std_pay_type) && $eventCategory->t_std_pay_type == 1) {
                     $attendSellPrice = $lessonData['sprice_amount_sell']*($lessonData['duration']/60);
                }


                if($user->isSchoolAdmin() || $user->isTeacherSchoolAdmin()) {
                    $attendBuyPrice = $eventPrice['price_buy']*($lessonData['duration']/60);
                }



                // fix 2 decimal value
                $attendSellPrice = round($attendSellPrice,2);
                $attendBuyPrice = round($attendBuyPrice,2);

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
                    'price_amount_buy' => $attendBuyPrice, //$lessonData['sprice_amount_buy'],
                    'price_amount_sell' => $attendSellPrice, //$lessonData['sprice_amount_sell'],
                    'fullday_flag' => isset($lessonData['fullday_flag']) ? $lessonData['fullday_flag'] : null,
                    'no_of_students' => $studentCount,
                    'description' => $lessonData['description'],
                    'location_id' => isset($lessonData['location']) ? $lessonData['location'] : null,
                    'is_paying' => $lessonData['sis_paying'],
                    'student_is_paying' => $lessonData['student_sis_paying'],
                ];

                //dd($data);

                $event = Event::create($data);
                if (!empty($lessonData['student'])) {
                   foreach($lessonData['student'] as $std){
                        $dataDetails = [
                            'event_id'   => $event->id,
                            'teacher_id' => $lessonData['teacher_select'],
                            'student_id' => $std,
                            'buy_total' => $attendBuyPrice,
                            'sell_total' => $attendSellPrice,
                            'buy_price' => $attendBuyPrice,
                            'sell_price' => $attendSellPrice,
                            'price_currency' => isset($lessonData['sprice_currency']) ? $lessonData['sprice_currency'] : null
                        ];
                        $eventDetails = EventDetails::create($dataDetails);
                    }
                }else{
                    $dataDetails = [
                        'event_id'   => $event->id,
                        'teacher_id' => $lessonData['teacher_select'],
                        'buy_total' => $attendBuyPrice,
                        'sell_total' => $attendSellPrice,
                        'buy_price' => $attendBuyPrice,
                        'sell_price' => $attendSellPrice,
                        'price_currency' => isset($lessonData['sprice_currency']) ? $lessonData['sprice_currency'] : null
                    ];
                    $eventDetails = EventDetails::create($dataDetails);
                }

                DB::commit();

                //create session "last_cat"
                Session::put('last_cat', $lessonData['category_select']);

                if($lessonData['save_btn_more'] == 1){
                    return [
                        'status' => 1,
                        'message' =>  __('Successfully Registered')
                    ];
                }else if($lessonData['save_btn_more'] == 2){
                    return Redirect::to($schoolId.'/add-lesson?id='.$event->id.'&isnew=1')->withInput($request->all())->with('success', __('Successfully Registered'));
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

    public function addImportedLesson($lessonData, $students, $category, $location = null, $schoolId = null)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
            $school = School::active()->find($schoolId);
            $timezone = $school->timezone;

            if (empty($school)) {
                return redirect()->route('schools')->with('error', __('School is not selected'));
            }

            $lessonData = [
                'title' => "",
                'school_id' => $schoolId,
                'event_type' => 10,
                'event_category' => $category,
                'teacher_id' => $user->person_id,
                'date_start' => $lessonData['date'] . ' ' . $lessonData['start_time'],
                'date_end' => $lessonData['date'] . ' ' . $lessonData['end_time'],
                'duration_minutes' => $lessonData['duration'],
                'price_currency' => null,
                'price_amount_buy' => 0,
                'price_amount_sell' => 0,
                'fullday_flag' => null,
                'no_of_students' => count($students),
                'location_id' => $location,
                'is_paying' => 0,
                'student_is_paying' => 0,
            ];

            $start_date = date('Y-m-d H:i:s', strtotime($lessonData['date_start']));
            $end_date = date('Y-m-d H:i:s', strtotime($lessonData['date_end']));
            $start_date = $this->formatDateTimeZone($start_date, 'long', $timezone, 'UTC');
            $end_date = $this->formatDateTimeZone($end_date, 'long', $timezone, 'UTC');

            $lessonData['date_start'] = $start_date;
            $lessonData['date_end'] = $end_date;

            $eventInit = new Event();
            $eventPrice = $eventInit->priceCalculations(['event_category_id' => $category, 'teacher_id' => $lessonData['teacher_id'], 'student_count' => count($students)]);
            
            $buyPriceCal = ($eventPrice['price_buy'] * ($lessonData['duration_minutes'] / 60)) / count($students);
            $sellPriceCal = ($eventPrice['price_sell'] * ($lessonData['duration_minutes'] / 60));

            $lessonData['price_amount_buy'] = round($buyPriceCal, 2);
            $lessonData['price_amount_sell'] = round($sellPriceCal, 2);

            $event = Event::create($lessonData);

            foreach ($students as $studentId) {
                $dataDetails = [
                    'event_id' => $event->id,
                    'teacher_id' => $lessonData['teacher_id'],
                    'student_id' => $studentId,
                    'buy_total' => $lessonData['price_amount_buy'],
                    'sell_total' => $lessonData['price_amount_sell'],
                    'buy_price' => $lessonData['price_amount_buy'],
                    'sell_price' => $lessonData['price_amount_sell'],
                    'price_currency' => null
                ];
                EventDetails::create($dataDetails);
            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
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
        if ($lessonData->is_locked) {
            return Redirect::to($schoolId.'/view-lesson/'.$lessonlId);
        }
        // dd($lessonData);
        $relationData = EventDetails::active()->where(['event_id'=>$lessonlId])->first();

        if($lessonData->eventcategory && $lessonData->eventcategory->invoiced_type == 'S') {
            if($user->isSchoolAdmin() || $user->isTeacherSchoolAdmin()) {
                $eventCategory = EventCategory::active()->where('school_id',$schoolId)->get();
            } else {
                if($user->isTeacherAdmin()) {
                    $eventCategory = EventCategory::active()->where('school_id',$schoolId)->where('created_by',$user->id)->get();
                } else {
                    $eventCategory = EventCategory::find($lessonData->eventcategory);
                }
            }
        } else {
            if($user->isSchoolAdmin() || $user->isTeacherSchoolAdmin()) {
                $eventCategory = EventCategory::active()->where('school_id',$schoolId)->get();
            } else {
                $eventCategory = EventCategory::active()->where('school_id',$schoolId)->where('created_by',$user->id)->get();
            }
        }

        $locations = Location::active()->where('school_id',$schoolId)->get();
        $professors = SchoolTeacher::active()->onlyTeacher()->where('school_id',$schoolId)->get();
        $students = SchoolStudent::active()->where('school_id',$schoolId)->get();
        $studentOffList = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->Join('school_student', 'school_student.student_id', '=', 'event_details.student_id')->where(['events.id'=>$lessonlId, 'event_type' => 10,'events.is_active' => 1])->groupBy('school_student.student_id')->get();

        $lessonPrice = LessonPrice::active()->get();
        $currencyInit = new Currency();
        $currency = $currencyInit->getCurrencyByCountry($school->country_code,true);

        // $lessonPriceTeacher = LessonPriceTeacher::active()->where(['event_category_id'=>$lessonData->event_category,'lesson_price_id'=>$lessonData->no_of_students,'teacher_id'=>$lessonData->teacher_id])->first();

        $reqData = $request->all();
        $redirect_url = '';
        if (!empty($reqData['redirect_url'])) {
            $redirect_url = $reqData['redirect_url'].'&tab=tab_3';
        }
        if (!empty($lessonData)){
            return view('pages.calendar.edit_lesson')->with(compact('lessonlId','lessonData','relationData','schoolId','eventCategory','locations','professors','studentOffList','students','lessonPrice','currency','redirect_url'));
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
                $redirectUrl = $request->input('redirect_url');
                $lessonlId = $request->route('lesson');
                $lessonData = $request->all();
                // dd($lessonData['validate']);
                $start_date = str_replace('/', '-', $lessonData['start_date']).' '.$lessonData['start_time'];
                $end_date = str_replace('/', '-', $lessonData['end_date']).' '.$lessonData['end_time'];
                $start_date = date('Y-m-d H:i:s',strtotime($start_date));
                $end_date = date('Y-m-d H:i:s',strtotime($end_date));
                $start_date = $this->formatDateTimeZone($start_date, 'long', $lessonData['zone'],'UTC');
                $end_date = $this->formatDateTimeZone($end_date, 'long', $lessonData['zone'],'UTC');
                $stu_num = !empty($lessonData['sevent_price']) ? explode("_", $lessonData['sevent_price']) : 0;
                $eventCategory = EventCategory::active()->where('id',$lessonData['category_select'])->first();

                $teacher_id = $user->isTeacher() ? $user->person_id : $lessonData['teacher_select'];
                $lessonData['teacher_select'] = $teacher_id;
                $studentCount = !empty($lessonData['student']) ? count($lessonData['student']) : 0 ;


                $eventInit = new Event();

                if($user->isSchoolAdmin() || $user->isTeacherSchoolAdmin()) {
                    $eventPrice = $eventInit->priceCalculationsSchool(['event_category_id'=>$lessonData['category_select'],'teacher_id'=>$teacher_id,'student_count'=>$studentCount]);
                } else {
                    $eventPrice = $eventInit->priceCalculations(['event_category_id'=>$lessonData['category_select'],'teacher_id'=>$teacher_id,'student_count'=>$studentCount]);
                }

                $lessonData['sprice_amount_buy'] =  $eventPrice['price_buy']; //isset($lessonData['sprice_amount_buy']) ? $lessonData['sprice_amount_buy'] : 0;
                $lessonData['sprice_amount_sell'] = $eventPrice['price_sell']; //isset($lessonData['sprice_amount_sell']) ? $lessonData['sprice_amount_sell'] : 0;


                if(!empty($studentCount)){
                    $buyPriceCal = ($eventPrice['price_buy']*($lessonData['duration']/60))/$studentCount;
                }else{
                    $buyPriceCal = ($eventPrice['price_buy']*($lessonData['duration']/60));
                }
                $sellPriceCal = ($eventPrice['price_sell']*($lessonData['duration']/60));

                if($lessonData['sis_paying'] == 1 && $lessonData['student_sis_paying'] == 1 ){
                   $attendBuyPrice = ($lessonData['sprice_amount_buy']*($lessonData['duration']/60))/$studentCount;
                   $attendSellPrice = $lessonData['sprice_amount_sell'];
                }else{
                    $attendBuyPrice = $buyPriceCal;
                    $attendSellPrice = $sellPriceCal;
                }
                if ($lessonData['student_sis_paying'] == 1) {
                    $attendSellPrice = ($eventPrice['price_sell']*($lessonData['duration']/60));
                }
                if (isset($eventCategory->t_std_pay_type) && $eventCategory->t_std_pay_type == 1) {
                     $attendSellPrice = $lessonData['sprice_amount_sell']*($lessonData['duration']/60);
                }

                // fix 2 decimal value
                $attendSellPrice = round($attendSellPrice,2);
                $attendBuyPrice = round($attendBuyPrice,2);

// dd($attendSellPrice, $attendBuyPrice);

                if($user->isSchoolAdmin() || $user->isTeacherSchoolAdmin()) {
                    $attendSellPrice = $eventPrice['price_sell']*($lessonData['duration']/60)/$studentCount;
                    $attendBuyPrice = $eventPrice['price_buy']*($lessonData['duration']/60);
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
                    'price_amount_buy' => $attendBuyPrice, //$lessonData['sprice_amount_buy'],
                    'price_amount_sell' => $attendSellPrice, //$lessonData['sprice_amount_sell'],
                    'fullday_flag' => isset($lessonData['fullday_flag']) ? $lessonData['fullday_flag'] : null,
                    'no_of_students' => $studentCount,
                    'description' => $lessonData['description'],
                    'location_id' => isset($lessonData['location']) ? $lessonData['location'] : null,
                    'is_paying' => $lessonData['sis_paying'],
                    'student_is_paying' => $lessonData['student_sis_paying'],
                ];

                $event = Event::where('id', $lessonlId)->update($data);
                EventDetails::where('event_id',$lessonlId)->forceDelete();
                if (!empty($lessonData['student'])) {
                    foreach($lessonData['student'] as $std){
                        $dataDetails = [
                            'event_id' => $lessonlId,
                            'teacher_id' => !empty($lessonData['teacher_select']) ? $lessonData['teacher_select'] : null,
                            'student_id' => $std,
                            'buy_total' => $attendBuyPrice,
                            'sell_total' => $attendSellPrice,
                            'buy_price' => $attendBuyPrice,
                            'sell_price' => $attendSellPrice,
                            'price_currency' => isset($lessonData['sprice_currency']) ? $lessonData['sprice_currency'] : null,
                            'participation_id' => !empty($lessonData['attnValue'][$std]) ? $lessonData['attnValue'][$std] : 0,
                        ];
                        $eventDetails = EventDetails::create($dataDetails);
                    }
                }else{
                    $dataDetails = [
                            'event_id' => $lessonlId,
                            'teacher_id' => !empty($lessonData['teacher_select']) ? $lessonData['teacher_select'] : null,
                            'buy_total' => $attendBuyPrice,
                            'sell_total' => $attendSellPrice,
                            'buy_price' => $attendBuyPrice,
                            'sell_price' => $attendSellPrice,
                            'price_currency' => isset($lessonData['sprice_currency']) ? $lessonData['sprice_currency'] : null,
                        ];
                        $eventDetails = EventDetails::create($dataDetails);
                }
                DB::commit();

                if($lessonData['save_btn_more'] == 1){
                    return Redirect::to($schoolId.'/add-lesson?id='.$lessonlId);
                }else if(isset($lessonData['validate']) && !empty($lessonData['validate'])){
                    Event::validate(['event_id'=>$lessonlId],1);

                    if (!empty($redirectUrl)) {
                        // Si redirect_url n'est pas vide, effectuez une redirection vers cette URL
                        return redirect($redirectUrl);
                    } else {
                        return redirect()->route('agenda',['school'=> $schoolId])->with('success', __('Successfully Updated !'));
                        //return Redirect::to($schoolId.'/view-lesson/'.$lessonlId);
                    }


                }else{
                    //return back()->with('success', __('Successfully Registered'));
                    return redirect()->route('agenda',['school'=> $schoolId])->with('success', __('Successfully Updated !'));
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
        $lessonData = Event::active()->find($lessonlId);
        $studentOffList = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->leftJoin('school_student', 'school_student.student_id', '=', 'event_details.student_id')->where(['events.id'=>$lessonlId, 'event_type' => 10,'events.is_active' => 1])->groupBy('school_student.student_id')->get();
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

        if($user->person_type === 'App\Models\Parents'){
            $allOthersMembers = ParentStudent::where('parent_id', $user->person_id)->get();
            $parentMembers = [];
            foreach ($allOthersMembers as $member) {
            $student2 = SchoolStudent::where('student_id', $member->student_id)->first();
            $parentMembers[] = $student2;
            }
            $students = $parentMembers;
        }

        $theStudent = Student::active()->where('id', $user->person_id)->where('is_active', 1)->first();

        if ($theStudent) {
            $futurEvents = $theStudent->tomorrowEventDetails;
        } else {
            $futurEvents = [];
        }
        $futursevents = Event::active()->where('school_id', $schoolId)->where('event_type', 10)->where('is_active', 1)->get();

        return view('pages.calendar.add_student_off')->with(compact('schoolId','students', 'futurEvents'));
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
                if($user->isStudent() || $user->isParent()){
                    $studentOffData['fullday_flag'] ='Y';
                }
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
                if($user->isStudent()){
                    $dataDetails = [
                        'event_id'   => $event->id,
                        'student_id' => $user->person_id,
                    ];
                    $eventDetails = EventDetails::create($dataDetails);

                } else {
                    foreach($studentOffData['student'] as $std){
                        $dataDetails = [
                            'event_id'   => $event->id,
                            'student_id' => $std,
                        ];
                        $eventDetails = EventDetails::create($dataDetails);
                    }
                }

                DB::commit();

                if($studentOffData['save_btn_more'] == 1){
                    return [
                        'status' => 1,
                        'message' =>  __('Successfully Registered')
                    ];
                }else if($studentOffData['save_btn_more'] == 3){
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
        $studentOffList = DB::table('events')->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')->leftJoin('school_student', 'school_student.id', '=', 'event_details.student_id')->where(['events.id'=>$studentOffId, 'event_type' => 51,'events.is_active' => 1])->groupBy('school_student.student_id')->get();
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
                    'date_end' => $end_date,
                    'fullday_flag' => isset($studentOffData['fullday_flag']) ? $studentOffData['fullday_flag'] : 'Y',
                    'description' => $studentOffData['description']
                ];

                $event = Event::where('id', $studoffId)->update($data);
                EventDetails::where('event_id',$studoffId)->forceDelete();

                if($user->isStudent() || $user->isParent()){
                    $dataDetails = [
                        'event_id'   => $studoffId,
                        'student_id' => $user->person_id,
                    ];
                    $eventDetails = EventDetails::create($dataDetails);

                } else {
                    foreach($studentOffData['student'] as $std){
                        $dataDetails = [
                            'event_id'   => $studoffId,
                            'student_id' => $std,
                        ];
                        $eventDetails = EventDetails::create($dataDetails);
                    }
                }

                DB::commit();
                return Redirect::to('/agenda')->with('success', __('Successfully Registered'));
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
        $studentOffList = DB::table('events')
        ->select('users.firstname', 'users.lastname')
        ->leftJoin('event_details', 'events.id', '=', 'event_details.event_id')
        ->leftJoin('users', 'event_details.student_id', '=', 'users.person_id')
        ->where(['events.id' => $studoffId, 'events.event_type' => 51, 'events.is_active' => 1])
        ->groupBy('users.firstname', 'users.lastname', 'event_details.student_id')
        ->get();
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
        return view('pages.calendar.view_coach_off')->with(compact('coachoffData','coachoffId','schoolId'));
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

                return response()->json(['status' => 1], 200);
            }
        }catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'error' => __('Internal server error')], 500);
        }

        return response()->json(['status' => 0], 400);
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
            $studentCount = $lessonData['no_of_students'] || 0;
            $sellPriceCal = 0;

            $initEventPrice = new Event();
            $user = Auth::user();
            if($user->isSchoolAdmin() || $user->isTeacherSchoolAdmin()) {
                $eventPrice = $initEventPrice->priceCalculationsSchool(['event_category_id'=>$lessonData['event_category_id'],'teacher_id'=>$lessonData['teacher_select'],'student_count'=>$lessonData['no_of_students']]);
                $lessonPriceTeacher = LessonPriceTeacher::active()->where(['event_category_id'=>$lessonData['event_category_id'], 'teacher_id'=>$lessonData['teacher_select']])->first();
            } else {
                $eventPrice = $initEventPrice->priceCalculations(['event_category_id'=>$lessonData['event_category_id'],'teacher_id'=>$lessonData['teacher_select'],'student_count'=>$lessonData['no_of_students']]);
                $lessonPriceTeacher = LessonPriceTeacher::active()->where(['event_category_id'=>$lessonData['event_category_id'],'teacher_id'=>$lessonData['teacher_select']])->first();
            }
            
            
            $duration = $lessonData['duration'];
            $eventCat = [];

        if($user->isSchoolAdmin() || $user->isTeacherSchoolAdmin()) {

            if(!empty($studentCount)){
                if($lessonPriceTeacher && $lessonPriceTeacher['lesson_price_student'] === "price_fix") {
                    $buyPriceCal = ($lessonPriceTeacher['price_buy']*($duration/60));
                    $sellPriceCal = ($lessonPriceTeacher['price_buy']*($duration/60)/$lessonData['no_of_students']);
                } else {
                    $buyPriceCal = ($eventPrice['price_buy']*($duration/60));
                    $sellPriceCal = ($eventPrice['price_buy']*($duration/60));
                }
            }else{
                $buyPriceCal = ($eventPrice['price_buy']*($duration/60));
                $sellPriceCal = ($eventPrice['price_buy']*($duration/60));
            }

        } else {

              if(!empty($studentCount)){
                if($lessonPriceTeacher['lesson_price_student'] === "price_fix") {
                    $buyPriceCal = ($lessonPriceTeacher['price_buy']*($duration/60));
                } else {
                    $buyPriceCal = ($eventPrice['price_buy']*($duration/60));
                }
            }else{
                $buyPriceCal = ($eventPrice['price_buy']*($duration/60));
            }

        }

            if (!empty($lessonPriceTeacher)) {
                return [
                    'status' => 1,
                    'eventPrice' => $eventPrice,
                    'lessonPriceTeacher' => $lessonPriceTeacher,
                    'newPrice' => $buyPriceCal,
                    'buyPriceCal' => $buyPriceCal,
                    'sellPriceCal' => $sellPriceCal,
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


        /**
     * check if price exist for student
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lessonFixedPrice(Request $request)
    {
        if ($request->isMethod('post')){
            $lessonData = $request->all();

            try {
                $priceKey = isset($lessonData['no_of_students']) && !empty($lessonData['no_of_students']) ? ( $lessonData['no_of_students'] > 10 ? 'price_su' : 'price_'.$lessonData['no_of_students'] ) : '' ;
                $evCategory = EventCategory::find($lessonData['event_category_id']);
                if ($evCategory->t_std_pay_type == 1) {
                    $priceKey = 'price_fix';
                }
                $buyPrice = $sellPrice = 0;
                if ($evCategory->s_thr_pay_type == 1 || $evCategory->s_std_pay_type == 1 ) {
                    $lessonPriceTeacher = LessonPriceTeacher::active()->where(['event_category_id'=>$lessonData['event_category_id'],'teacher_id'=>$lessonData['teacher_select'],'lesson_price_student'=>'price_fix'])->first();
                    $buyPrice = isset($lessonPriceTeacher->price_buy)? $lessonPriceTeacher->price_buy : 0;
                    $sellPrice = isset($lessonPriceTeacher->price_sell)? $lessonPriceTeacher->price_sell : 0;
                }

                $prices = LessonPriceTeacher::active()->where(['event_category_id'=>$lessonData['event_category_id'],'teacher_id'=>$lessonData['teacher_select'],'lesson_price_student'=>$priceKey])->first();

                if ($evCategory->s_thr_pay_type == 0) {
                    $buyPrice = isset($prices->price_buy)? $prices->price_buy : 0;
                }
                if ($evCategory->s_std_pay_type == 0) {
                    $sellPrice = isset($prices->price_sell)? $prices->price_sell : 0;
                }elseif ($evCategory->s_std_pay_type == 2) {
                    $sellPrice = 0;
                }

                return [
                    'status' => 1,
                    'data' => ['price_buy'=> $buyPrice, 'price_sell'=>$sellPrice],
                    'message' =>  __('Successfully get price for this teacher')
                ];

            } catch (Exception $e) {
               return [
                    'status' => 0,
                    'message' =>  __('No price for this teacher')
                ];
            }

        }

    }
}
