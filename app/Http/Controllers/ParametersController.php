<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\Level;
use App\Models\Location;
use App\Models\EventCategory;
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
        $eventCat = EventCategory::where('is_active', 1)->get();
        $eventCatId = DB::table('event_categories')->orderBy('id','desc')->first();;
        $locations = Location::where('is_active', 1)->get();
        $levels = Level::where('is_active', 1)->get();
        return view('pages.parameters.index',compact('eventCat','eventCatId','locations','levels'));
    }   
    /**
     * Remove the specified resource from storage.
     * @return Response
    */
 
    public function addEventCategory(Request $request)
    {
        try{
            if ($request->isMethod('post')){
                
                $categoryData = $request->all();

                for ($i = 1; $i < count($request->category_name); $i++) {
                    $answers[] = [
                        'school_id' => $categoryData['school_id'] ? $categoryData['school_id'] : 1,
                        'title' => $request->category_name[$i],
                        'invoiced_type' => $request->category_invoiced[$i]
                    ];
                }

                $eventCat = EventCategory::insert($answers);

                if($eventCat==1){
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
                $level='';
                $result=[];
                
                if(!empty($levelData['level_id'])){
                    for ($i = 1; $i < count($request->level_name); $i++) {
                        $level = DB::table('lavels')
                        ->where('id', $request->level_id[$i])
                        ->update([
                            'title' => $request->lavel_name[$i]
                            ]);
                    }
                }else{
                    foreach($levelData['level_name'] as $key => $value){
                        $names[] = [
                            'title' => $value,
                            'school_id' => $levelData['school_id'] ? $levelData['school_id'] : 1,
                        ];
                    }
                    $level = Level::insert($names);
                }

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
    public function removeEventCategory($id)
    {
        $eventCat = EventCategory::find($id)->delete();
        
        if($eventCat==1){
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
