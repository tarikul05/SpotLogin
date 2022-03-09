<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\EventCategory;
use App\Models\Parameters;
use Illuminate\Support\Facades\Auth;

class EventCategoryController extends Controller
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

        return view('pages.event_category.index',compact('eventCat','eventLastCatId'));
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
 
}
