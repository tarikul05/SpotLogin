<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\Level;
use App\Models\Location;
use App\Models\Parameters;

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
                print_r($locationData);exit;
                $location = Location::create($locationData);
                $location->save();
                
                $result = array(
                    "status"     => 1,
                    'message' => __('Successfully Registered')
                );
            }
        }catch (Exception $e) {
            DB::rollBack();
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }   
    }

    public function addLevel(Request $request)
    {
        try{
            if ($request->isMethod('post')){
                $levelData = $request->all();
                $level = Level::create($levelData);
                $level->save();
                
                $result = array(
                    "status"     => 1,
                    'message' => __('Successfully Registered')
                );
            }
        }catch (Exception $e) {
            DB::rollBack();
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }   
    }
 
 
}
