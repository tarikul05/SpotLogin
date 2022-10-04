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
        $eventLastCatId = DB::table('event_categories')->orderBy('id','desc')->first();
        $locations = Location::where('is_active', 1)->get();
        $eventLastLocaId = DB::table('locations')->orderBy('id','desc')->first();
        $levels = Level::where('is_active', 1)->get();
        $eventLastLevelId = DB::table('levels')->orderBy('id','desc')->first();
        return view('pages.parameters.index',compact('eventCat','eventLastCatId','locations','eventLastLocaId','levels','eventLastLevelId'));
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

                foreach($categoryData['category'] as $cat){
                    if(isset($cat['id']) && !empty($cat['id'])){
                        $answers = [
                            'school_id' => 1,
                            'title' => $cat['name'],
                            'invoiced_type' => $cat['invoice']
                        ];
                        $eventCat = EventCategory::where('id', $cat['id'])->update($answers);
                    }else{
                        $answers = [
                            'school_id' => 1,
                            'title' => $cat['name'],
                            'invoiced_type' => $cat['invoice']
                        ];
                        $eventCat = EventCategory::create($answers);
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
     * @return Response
    */
 
    public function addLocation(Request $request)
    {
        try{
            if ($request->isMethod('post')){
                
                $locationData = $request->all();
                foreach($locationData['location'] as $location){
                    if(isset($location['id']) && !empty($location['id'])){
                        $answers = [
                            'school_id' => 1,
                            'title' => $location['name']
                        ];
                        $eventLocation = Location::where('id', $location['id'])->update($answers);
                    }else{
                        $answers = [
                            'school_id' => 1,
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

    public function addLevel(Request $request)
    {
        try{
            if ($request->isMethod('post')){
                
                $levelData = $request->all();
                foreach($levelData['level'] as $level){
                    if(isset($level['id']) && !empty($level['id'])){
                        $answers = [
                            'school_id' => 1,
                            'title' => $level['name']
                        ];
                        $eventLevel = Level::where('id', $level['id'])->update($answers);
                    }else{
                        $answers = [
                            'school_id' => 1,
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
