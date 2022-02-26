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
        return view('pages.parameters.index');
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
 
 
}
