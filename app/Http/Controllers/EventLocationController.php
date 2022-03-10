<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class EventLocationController extends Controller
{
    
    /**
     * Create a new controller instance
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:parameters-list|parameters-create-udpate|parameters-delete', ['only' => ['index']]);
        $this->middleware('permission:parameters-create-udpate', ['only' => ['addEventCategory']]);
        $this->middleware('permission:parameters-delete', ['only' => ['removeEventCategory']]);
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
        $locations = Location::where('is_active', 1)->get();
        $eventLastLocaId = DB::table('locations')->orderBy('id','desc')->first();

        return view('pages.event_location.index',compact('locations','eventLastLocaId'));
    }   
   
    /**
     * Remove the specified resource from storage.
     * @return Response
    */
 
    public function addLocation(Request $request)
    {
        try{
            if ($request->isMethod('post')){
                
                $locationData = $request->all();
                $userSchoolId = Auth::user()->selectedSchoolId();
                
                foreach($locationData['location'] as $location){
                    if(isset($location['id']) && !empty($location['id'])){
                        $answers = [
                            'school_id' => $userSchoolId,
                            'title' => $location['name']
                        ];
                        $eventLocation = Location::where('id', $location['id'])->update($answers);
                    }else{
                        $answers = [
                            'school_id' => $userSchoolId,
                            'title' => $location['name']
                        ];
                        $eventLocation = Location::create($answers);
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
    public function removeLocation($id)
    {
        $location = Location::find($id)->delete();
        
        if($location==1){
            return $result = array(
                "status"     => 1,
                'message' => __('Successfully Deleted')
            );
        }
    }
 
 
}
