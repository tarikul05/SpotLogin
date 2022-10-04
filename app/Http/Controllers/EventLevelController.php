<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\Level;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

class EventLevelController extends Controller
{
    
    /**
     * Create a new controller instance
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
     /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function index($schoolId = null)
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

        $levels = Level::active()->where('school_id', $schoolId)->get();
        $eventLastLevelId = DB::table('levels')->orderBy('id','desc')->first();
        return view('pages.event_level.index',compact('levels','eventLastLevelId','schoolId'));
    }   

    /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function addLevel(Request $request)
    {
        try{
            if ($request->isMethod('post')){
                
                $levelData = $request->all();
                $user = Auth::user();
                if ($user->isSuperAdmin()) {
                    $userSchoolId = $levelData['school_id']; 
                }else {
                    $userSchoolId = $user->selectedSchoolId();
                }

                foreach($levelData['level'] as $level){
                    if(isset($level['id']) && !empty($level['id'])){
                        $answers = [
                            'school_id' => $userSchoolId,
                            'title' => $level['name']
                        ];
                        $eventLevel = Level::where('id', $level['id'])->update($answers);
                    }else{
                        $answers = [
                            'school_id' => $userSchoolId,
                            'title' => $level['name']
                        ];
                        $eventLevel = Level::create($answers);
                    }
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeLevel($id)
    {
        $location = level::find($id)->delete();
        
        if($location==1){
            return $result = array(
                "status"     => 1,
                'message' => __('Successfully Deleted')
            );
        }
    }
 
 
}
