<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Location;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Event;
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
    public function index($schoolId = null)
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

        $event_types = config('global.event_type'); 

        $eventData = Event::active()->where('school_id', $schoolId)->get();
        
        //dd($eventData);
        $events = array();   
        foreach ($eventData as $key => $fetch) {
            $e = array();   
            $e['id'] = $fetch->id;
            
            $e['title']=(substr($fetch->title,0,1)==',') ? substr($fetch->title,1) : substr($fetch->title,0);
            $e['start'] = $fetch->date_start;
            $e['end'] = $fetch->date_end;
            $allday = ($fetch->fullday_flag == "true") ? true : false;
            $e['allDay'] = $allday;

            if (isset($fetch->teacher)) {
                $e['backgroundColor'] = $fetch->teacher['bg_color_agenda'];
                $e['teacher_name'] = $fetch->teacher['Kazi'];
            }
            $e['event_type'] = $fetch->event_type;
            $e['event_type_name'] = ($event_types[$e['event_type']]);
            if (isset($fetch->eventCategory)) {
                $e['event_category'] = $fetch->event_category;
                $e['event_category_name'] = $fetch->eventCategory['title'];
                $e['cours_name'] = $e['event_type_name'].'('.$e['event_category_name'].')';
                $e['text_for_search']=strtolower($e['event_type_name'].$e['cours_name'].' '.$e['teacher_name'].' - '.$e['title']);
                $e['tooltip']=$e['event_mode_desc'].$e['cours_name'].' Duration: '.$fetch->duration_minutes.' '.$e['teacher_name'].' - '.$e['title'];
                $e['content'] = ($e['cours_name']);
            }

            if($fetch->event_mode==0){
                $e['event_mode_desc'] = 'Draft';
            } else {
                $e['event_mode_desc'] = '';
            }
            $e['teacher_id'] = $fetch->teacher_id; 
            $e['duration_minutes'] = $fetch->duration_minutes;
            $e['no_of_students'] = $fetch->no_of_students;
            $e['is_locked'] = $fetch->is_locked;
            // $e['student_id_list'] = $fetch['student_id_list'];
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
        return view('pages.agenda.index')->with(compact('schoolId','user_role','students','teachers','locations','alllanguages','events','event_types'));

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

}
