<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Location;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Event;
use App\Models\EventDetails;

use App\Models\School;
use Illuminate\Support\Facades\Auth;
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
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $user_role = 'superadmin';
        if ($user->person_type == 'App\Models\Student') {
            $user_role = 'student';
        }
        if ($user->person_type == 'App\Models\Teacher') {
            $user_role = 'teacher';
        }

        $alllanguages = Language::orderBy('sort_order')->get();
        $locations = Location::orderBy('id')->get();
        $students = Student::orderBy('id')->get();
        $teachers = Teacher::orderBy('id')->get();
        $schools = School::orderBy('id')->get();

        $event_types = config('global.event_type'); 

        //$eventData = Event::active()->where('school_id', $schoolId)->get();
        $eventData = Event::active()->get();
        $data = $request->all();
        if (isset($data['start_date'])) {
            $query = $eventData->filter($data);
            $eventData = $query->get();
            
        }

        //dd($eventData);
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
            
            
            $allday = ($fetch->fullday_flag == "true") ? true : false;
            $e['allDay'] = $allday;

            if (isset($fetch->teacher)) {
                $e['backgroundColor'] = $fetch->teacher['bg_color_agenda'];
                $e['teacher_name'] = $fetch->teacher['Kazi'];
            }
            $e['event_type'] = $fetch->event_type;
            $e['event_type_name'] = ($event_types[$e['event_type']]);
            $e['event_school_id'] = (is_null($fetch->school_id) ? 0 : $fetch->school_id) ;
            $e['event_school_name'] = $fetch->school['school_name'];
            $e['event_category'] ='';
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

            if (isset($fetch->eventCategory)) {
                $e['event_category'] = $fetch->event_category;
                $e['event_category_name'] = $fetch->eventCategory['title'];
                
            }
            $e['cours_name'] = $e['event_type_name'].'('.$e['event_category_name'].')';
            $e['text_for_search']=strtolower($e['event_type_name'].$e['cours_name'].' '.$e['teacher_name'].' - '.$e['title']);
            $e['tooltip']=$e['event_mode_desc'].$e['cours_name'].' Duration: '.$fetch->duration_minutes.' '.$e['teacher_name'].' - '.$e['title'];
            $e['content'] = ($e['cours_name']);

            
            $e['teacher_id'] = $fetch->teacher_id; 
            $e['duration_minutes'] = $fetch->duration_minutes;
            $e['no_of_students'] = $fetch->no_of_students;
            $e['is_locked'] = $fetch->is_locked;
            $eventDetailsStudentId = EventDetails::active()->where('event_id', $fetch->id)->get()->pluck('student_id')->join(',');
            $e['student_id_list'] = $eventDetailsStudentId;
            $e['event_auto_id'] = ($fetch->id);
            $e['event_mode'] = $fetch->event_mode;
            

            // if (now()>$fetch->date_end) {
            //     $e['can_lock'] = 'Y';
            // } else{
            //     $e['can_lock'] = 'N';
            // }
            $e['description'] = $e['title'];
            $e['location'] = (is_null($fetch->location_id) ? 0 : $fetch->location_id) ;
            $e['category_id'] = (is_null($fetch->event_category) ? 0 : $fetch->event_category) ;
            $e['created_user'] = $fetch->created_by;
            
            $page_name='/'.$schoolId.'/add-event?event_type=';
            if ($fetch->is_locked == 1){
                $action_type='view';
                $page_name='/'.$schoolId.'/add-event?event_type=';
            }
            else {
                $action_type='edit';
                $page_name='/'.$schoolId.'/edit-event?event_type=';
                
                if ($user_role == 'student'){
                    $action_type='view';
                    $page_name='/'.$schoolId.'/view-event?event_type=';
                }
                if ($user_role == 'teacher'){
                    if (($user->id == $fetch->teacher_id)){
                        $action_type='edit';
                        $page_name='/'.$schoolId.'/view-event?event_type=';
                    }else{
                        $action_type='view';
                        $page_name='/'.$schoolId.'/view-event?event_type=';
                    }
                } 
                /* only own vacation entry can be edited by user - Teacher */
                if ($fetch->event_type == 50) {
                    if ( ($user->id == $fetch->created_by) || ($user->id == $fetch->teacher_id) || ($user->id == $fetch->teacher_id )) {
                        $action_type='edit';
                        $page_name='/'.$schoolId.'/edit-event?event_type=';
                    } else {
                        $action_type='view';
                        $page_name='/'.$schoolId.'/view-event?event_type=';
        
                    }   
                }
                /* only own vacation entry can be edited by user - Student */
                if ($fetch->event_type == 51) {
                    if (($user->id == $fetch->created_by) || ($user->id == $fetch->student_id) || ($user->id == $fetch->student_id )) {
                        $action_type='edit';
                        $page_name='/'.$schoolId.'/edit-event?event_type=';
                    } else {
                        $action_type='view';
                        $page_name='/'.$schoolId.'/view-event?event_type=';
        
                    }   
                    
                }
                
            };
            $e['url'] = $page_name.$fetch->event_type.'&event_id='.$fetch->id.'&action='.$action_type;
            
            $e['action_type'] = $action_type;

            array_push($events, $e);
        }
        //dd($events);
        $events =json_encode($events);
        return view('pages.agenda.index')->with(compact('schools','school','schoolId','user_role','students','teachers','locations','alllanguages','events','event_types'));

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
            'message' => __('failed to send email'),
        );
        try {
            $data = $request->all();


            $p_event_auto_id = $data['p_event_auto_id'];
            // $data['school_id']
            // $p_user_id = Auth::user()->id;

            $event = [
                'is_locked' => 1
            ];
            $event = Event::where('id', $p_event_auto_id)->update($event);


            $eventDetail = [
                'is_locked' => 1,
            ];
            $eventdetail = EventDetails::where('event_id', $p_event_auto_id)->update($eventDetail);
            
            $eventDetail = [
                'participation_id' => ($eventdetail->participation_id == 0 || $eventdetail->participation_id == 100) ? 200 : $eventdetail->participation_id
            ];
            $eventdetail = $eventdetail->update($eventDetail);

            if ($eventdetail)
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
     *  AJAX copy event
     * 
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-04-14
     */
    public function copyPasteEvent(Request $request,$schoolId = null)
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
            $query = new Event;
            $eventData = $query->filter_for_copy($data);
            
            $eventData = $query->get();
            
            

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
                    'description' => $fetch->description,
                    'location_id' => $fetch->location_id,
                    'teacher_id' => $fetch->teacher_id,
                    'event_price' => $fetch->event_price,
                    'event_price' => $fetch->event_price
                ];
                $event = Event::create($data);

                $eventDetailsStudentId = EventDetails::active()->where('event_id', $fetch->id)->get()->toArray();
                

                foreach($eventDetailsStudentId as $std){
                    $dataDetails = [
                        'event_id'   => $event->id,
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
    public function getEvent(Request $request,$schoolId = null)
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
        //$eventData = Event::active()->where('school_id', $schoolId)->get();
        
        //$data['school_id'] = $schoolId;
        //dd($data);

        $query = new Event;
        $eventData = $query->filter($data);
        $eventData = $query->get();

       
        
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
            
            
            $allday = ($fetch->fullday_flag == "true") ? true : false;
            $e['allDay'] = $allday;

            if (isset($fetch->teacher)) {
                $e['backgroundColor'] = $fetch->teacher['bg_color_agenda'];
                $e['teacher_name'] = $fetch->teacher['Kazi'];
            }
            $e['event_type'] = $fetch->event_type;
            $e['event_type_name'] = ($event_types[$e['event_type']]);
            $e['event_school_id'] = (is_null($fetch->school_id) ? 0 : $fetch->school_id) ;
            $e['event_school_name'] = $fetch->school['school_name'];
            $e['event_category'] ='';
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

            if (isset($fetch->eventCategory)) {
                $e['event_category'] = $fetch->event_category;
                $e['event_category_name'] = $fetch->eventCategory['title'];
                
            }
            $e['cours_name'] = $e['event_type_name'].'('.$e['event_category_name'].')';
            $e['text_for_search']=strtolower($e['event_type_name'].$e['cours_name'].' '.$e['teacher_name'].' - '.$e['title']);
            $e['tooltip']=$e['event_mode_desc'].$e['cours_name'].' Duration: '.$fetch->duration_minutes.' '.$e['teacher_name'].' - '.$e['title'];
            $e['content'] = ($e['cours_name']);

            
            $e['teacher_id'] = $fetch->teacher_id; 
            $e['duration_minutes'] = $fetch->duration_minutes;
            $e['no_of_students'] = $fetch->no_of_students;
            $e['is_locked'] = $fetch->is_locked;
            $eventDetailsStudentId = EventDetails::active()->where('event_id', $fetch->id)->get()->pluck('student_id')->join(',');
            $e['student_id_list'] = $eventDetailsStudentId;
            $e['event_auto_id'] = ($fetch->id);
            $e['event_mode'] = $fetch->event_mode;
            

            // if (now()>$fetch->date_end) {
            //     $e['can_lock'] = 'Y';
            // } else{
            //     $e['can_lock'] = 'N';
            // }
            $e['description'] = $e['title'];
            $e['location'] = (is_null($fetch->location_id) ? 0 : $fetch->location_id) ;
            $e['category_id'] = (is_null($fetch->event_category) ? 0 : $fetch->event_category) ;
            $e['created_user'] = $fetch->created_by;
            
            $page_name='/'.$schoolId.'/add-event?event_type=';
            if ($fetch->is_locked == 1){
                $action_type='view';
                $page_name='/'.$schoolId.'/add-event?event_type=';
            }
            else {
                $action_type='edit';
                $page_name='/'.$schoolId.'/edit-event?event_type=';
                
                if ($user_role == 'student'){
                    $action_type='view';
                    $page_name='/'.$schoolId.'/view-event?event_type=';
                }
                if ($user_role == 'teacher'){
                    if (($user->id == $fetch->teacher_id)){
                        $action_type='edit';
                        $page_name='/'.$schoolId.'/view-event?event_type=';
                    }else{
                        $action_type='view';
                        $page_name='/'.$schoolId.'/view-event?event_type=';
                    }
                } 
                /* only own vacation entry can be edited by user - Teacher */
                if ($fetch->event_type == 50) {
                    if ( ($user->id == $fetch->created_by) || ($user->id == $fetch->teacher_id) || ($user->id == $fetch->teacher_id )) {
                        $action_type='edit';
                        $page_name='/'.$schoolId.'/edit-event?event_type=';
                    } else {
                        $action_type='view';
                        $page_name='/'.$schoolId.'/view-event?event_type=';
        
                    }   
                }
                /* only own vacation entry can be edited by user - Student */
                if ($fetch->event_type == 51) {
                    if (($user->id == $fetch->created_by) || ($user->id == $fetch->student_id) || ($user->id == $fetch->student_id )) {
                        $action_type='edit';
                        $page_name='/'.$schoolId.'/edit-event?event_type=';
                    } else {
                        $action_type='view';
                        $page_name='/'.$schoolId.'/view-event?event_type=';
        
                    }   
                    
                }
                
            };
            $e['url'] = $page_name.$fetch->event_type.'&event_id='.$fetch->id.'&action='.$action_type;
            
            $e['action_type'] = $action_type;

            array_push($events, $e);
        }
        //dd($data);
        $events =json_encode($events);
        
        return response()->json($events);
        
    } 

     /**
     *  AJAX delete multiple event
     * 
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-04-10
     */
    public function deleteMultipleEvent(Request $request)
    {
        $result = array(
            'status' => 'failed',
            'message' => __('failed to send email'),
        );
        try {
            $data = $request->all();
            $p_from_date= trim($data['p_from_date']);
            $p_to_date= trim($data['p_to_date']);
        
            $data['school_id']= trim($data['p_event_school_id']);
            $data['event_type']= trim($data['p_event_type_id']);
            $data['teacher_id']= trim($data['p_teacher_id']);
            $data['student_id']= trim($data['p_student_id']);
            $p_user_id=Auth::user()->id;

            
            if (isset($data['p_from_date'])) {
                $query = new Event;
                $eventData = $query->multiDelete($data)->delete();
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
    

}
