<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\EventCategory;
use App\Models\School;
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
        $this->middleware('permission:parameters-list|parameters-create-udpate|parameters-delete', ['only' => ['index']]);
        $this->middleware('permission:parameters-create-udpate', ['only' => ['addEventCategory']]);
        $this->middleware('permission:parameters-delete', ['only' => ['removeEventCategory']]);
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


        $eventCat = EventCategory::active()->where('school_id', $schoolId)->get();
        $eventLastCatId = DB::table('event_categories')->orderBy('id','desc')->first();

        return view('pages.event_category.index',compact('eventCat','eventLastCatId','schoolId'));
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
                $user = Auth::user();
                if ($user->isSuperAdmin()) {
                    $userSchoolId = $categoryData['school_id']; 
                }else {
                    $userSchoolId = $user->selectedSchoolId();
                }
                
                foreach($categoryData['category'] as $cat){
                    $invoicedType = $user->isTeacher() ? 'T' : $cat['invoice'];
                    if(isset($cat['id']) && !empty($cat['id'])){
                        $answers = [
                            'school_id' => $userSchoolId,
                            'title' => $cat['name'],
                            'invoiced_type' => $invoicedType
                        ];
                        $eventCat = EventCategory::where('id', $cat['id'])->update($answers);
                    }else{
                        $answers = [
                            'school_id' => $userSchoolId,
                            'title' => $cat['name'],
                            'invoiced_type' => $invoicedType
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
