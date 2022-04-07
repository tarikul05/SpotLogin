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

        $eventData = Event::orderBy('id')->get()->toArray();
        
        //dd($eventData);
        $events = array();   
        foreach ($eventData as $key => $fetch) {
            $e = array();   
            $e['id'] = $fetch['id'];
            
            $e['title']=(substr($fetch['title'],0,1)==',') ? substr($fetch['title'],1) : substr($fetch['title'],0);
            $e['start'] = $fetch['date_start'];
            $e['end'] = $fetch['date_end'];
            $allday = ($fetch['fullday_flag'] == "true") ? true : false;
            $e['allDay'] = $allday;
            
            //$e['backgroundColor'] = $fetch->teacher['bg_color_agenda'];
            $e['event_type'] = ($fetch['event_type']);
            // $e['event_type_name'] = ($fetch['event_type_name']);
            // $e['cours_name'] = ($fetch['cours_name']);
            $e['teacher_id'] = ($fetch['teacher_id']);
            // $e['teacher_name'] = ($fetch['teacher_name']);
            $e['duration_minutes'] = $fetch['duration_minutes'];
            $e['no_of_students'] = $fetch['no_of_students'];
            $e['is_locked'] = $fetch['is_locked'];
            // $e['student_id_list'] = $fetch['student_id_list'];
            // $e['text_for_search']=strtolower($fetch['event_type_name'].$fetch['cours_name'].' '.$fetch['teacher_name'].' - '.$fetch['title']);
            // $e['tooltip']=$fetch['event_mode_desc'].$fetch['cours_name'].' Duration: '.$fetch['duration_minutes'].' '.$fetch['teacher_name'].' - '.$fetch['title'];
            // $e['event_auto_id'] = ($fetch['event_auto_id']);
            $e['event_mode'] = ($fetch['event_mode']);
            // $e['content'] = ($fetch['cours_name']);
            //$e['can_lock'] = ($fetch['can_lock']);
            $e['description'] = $e['title'];
            // $e['location'] = (is_null($fetch['location']) ? 0 : $fetch['location']) ;
            $e['category_id'] = (is_null($fetch['event_category']) ? 0 : $fetch['event_category']) ;
            // $e['event_category_name'] = $fetch['event_category_name'];
            // $e['created_user'] = $fetch['created_user'];
            
            // //$e['rendering'] = 'background';
            
            $page_name='../admin/events_entry.html?event_type=';
            if ($fetch['is_locked'] == 1){
                $action_type='view';
                $page_name='/{school}/add-event?event_type=';
            }
            else {
                $action_type='edit';
                $page_name='../admin/events_entry.html?event_type=';
                
                if ($user_role == 'student'){
                    $action_type='view';
                    $page_name='../admin/events_entry_view.html?event_type=';
                }
                if ($user_role == 'teacher'){
                    if (($person_id == $fetch['teacher_id']) || ($_SESSION['user_authorisation'] == 'ALL') ){
                        $action_type='edit';
                        $page_name='../admin/events_entry.html?event_type=';
                    }else{
                        $action_type='view';
                        $page_name='../admin/events_entry_view.html?event_type=';
                    }
                } 
                /* only own vacation entry can be edited by user - Teacher */
                if ($fetch['event_type'] == 50) {
                    if ( ($user->id == $fetch['created_by']) || ($user->id == $fetch['teacher_id']) || ($user->id == $fetch['teacher_id'] )) {
                        $action_type='edit';
                        $page_name='../admin/events_entry.html?event_type=';
                    } else {
                        $action_type='view';
                        $page_name='../admin/events_entry_view.html?event_type=';
        
                    }   
                }
                /* only own vacation entry can be edited by user - Student */
                if ($fetch['event_type'] == 51) {
                    if (($user->id == $fetch['created_by']) || ($user->id == $fetch['student_id']) || ($user->id == $fetch['student_id'] )) {
                        $action_type='edit';
                        $page_name='../admin/events_entry.html?event_type=';
                    } else {
                        $action_type='view';
                        $page_name='../admin/events_entry_view.html?event_type=';
        
                    }   
                    
                }
                
            };
            $e['url'] = $page_name.$fetch['event_type'].'&event_id='.$fetch['id'].'&action='.$action_type;
            
            $e['action_type'] = $action_type;

            array_push($events, $e);
        }
        $events =json_encode($events);
        return view('pages.agenda.index')->with(compact('user_role','students','teachers','locations','alllanguages','events','event_types'));

    }   

}
