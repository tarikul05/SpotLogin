<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\Level;
use App\Models\Location;
use App\Models\Parameters;
use Illuminate\Support\Facades\Auth;

class ParametersController extends Controller
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
        $locations = Location::where('is_active', 1)->get();
        $levels = Level::where('is_active', 1)->get();
        return view('pages.parameters.index',compact('locations','levels'));
    }   
    /**
     * Remove the specified resource from storage.
     * @return Response
    */
 
    public function addCategory(Request $request)
    {
        try{
            if ($request->isMethod('post')){
                
                $locationData = $request->all();
                foreach($locationData['location_name'] as $key => $value){
                    $names[] = [
                        'title' => $value,
                        'school_id' => $locationData['school_id'] ? $locationData['school_id'] : 1,
                    ];
                }

                $location = Location::insert($names);
                if($location==1){
                    $result = array(
                        "status"     => 1,
                        'message' => __('Successfully Registered')
                    );
                }
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
     * @return Response
    */
 
    public function addLocation(Request $request)
    {
        try{
            if ($request->isMethod('post')){
                
                $locationData = $request->all();
                foreach($locationData['location_name'] as $key => $value){
                    $names[] = [
                        'title' => $value,
                        'school_id' => $locationData['school_id'] ? $locationData['school_id'] : 1,
                    ];
                }

                $location = Location::insert($names);
                if($location==1){
                    $result = array(
                        "status"     => 1,
                        'message' => __('Successfully Registered')
                    );
                }
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

    public function addLevel(Request $request)
    {
        try{
            if ($request->isMethod('post')){
                
                $levelData = $request->all();

                foreach($levelData['level_name'] as $key => $value){
                    $names[] = [
                        'title' => $value,
                        'school_id' => $levelData['school_id'] ? $levelData['school_id'] : 1,
                    ];
                }

                $level = Level::insert($names);
                if($level==1){
                    $result = array(
                        "status"     => 1,
                        'message' => __('Successfully Registered')
                    );
                }
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
