<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\Level;
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
    public function index()
    {   
        $userSchoolId = Auth::user()->selectedSchoolId();
        if (empty($userSchoolId)) {
            return redirect()->route('Home')->with('error', __('School is not selected'));
        }

        $levels = Level::where('is_active', 1)->get();
        $eventLastLevelId = DB::table('levels')->orderBy('id','desc')->first();
        return view('pages.event_level.index',compact('levels','eventLastLevelId'));
    }   

    /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function addLevel(Request $request)
    {
        try{
            if ($request->isMethod('post')){
                
                $userSchoolId = Auth::user()->selectedSchoolId();
                $levelData = $request->all();
                //echo '<>';print_r($levelData);exit;
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
