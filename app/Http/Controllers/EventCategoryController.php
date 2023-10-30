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
            $newCategoryAdded = false;
            $newCategories = [];
            if ($request->isMethod('post')){

                $categoryData = $request->all();
                $user = Auth::user();
                if ($user->isSuperAdmin()) {
                    $userSchoolId = $categoryData['school_id'];
                }else {
                    $userSchoolId = $user->selectedSchoolId();
                }

                //dd($categoryData['category']);

                foreach($categoryData['category'] as $cat){
                    $invoicedType = ($user->isTeacherAdmin() || $user->isTeacherSchoolAdmin() || $user->isSchoolAdmin() ) ? $cat['invoice'] : 'T';
                        $s_thr_pay_type = !empty($cat['s_thr_pay_type']) ? $cat['s_thr_pay_type'] : 0;
                        $s_std_pay_type = !empty($cat['s_std_pay_type']) ? $cat['s_std_pay_type'] : 0;
                        $t_std_pay_type = !empty($cat['t_std_pay_type']) ? $cat['t_std_pay_type'] : 0;
                        if ($invoicedType == 'T') {
                            $s_thr_pay_type = $s_std_pay_type = 0;
                        }else{
                            $t_std_pay_type = 0;
                        }

                        if(isset($cat['id']) && !empty($cat['id'])){
                        $answers = [
                            'school_id' => $userSchoolId,
                            'title' => $cat['name'],
                            'bg_color_agenda' => $cat['bg_color_agenda'],
                            'invoiced_type' => $invoicedType,
                                'package_invoice' => (($invoicedType =='S') && (!empty($cat['package_invoice']))) ? 1 : 0,
                                's_thr_pay_type' => $s_thr_pay_type,
                                's_std_pay_type' => $s_std_pay_type,
                                't_std_pay_type' => $t_std_pay_type
                        ];
                        $eventCat = EventCategory::where('id', $cat['id'])->update($answers);
                    }else{
                        $answers = [
                            'school_id' => $userSchoolId,
                            'title' => $cat['name'],
                            'bg_color_agenda' => $cat['bg_color_agenda'],
                            'invoiced_type' => $invoicedType,
                                'package_invoice' => (($invoicedType =='S') && (!empty($cat['package_invoice']))) ? 1 : 0,
                                's_thr_pay_type' => $s_thr_pay_type,
                                's_std_pay_type' => $s_std_pay_type,
                                't_std_pay_type' => $t_std_pay_type
                        ];
                        $eventCat = EventCategory::create($answers);
                    }
                    if (!isset($cat['id']) || empty($cat['id'])) {
                        $newCategoryAdded = true;
                    }
                }

                if ($newCategoryAdded) {
                    foreach ($categoryData['category'] as $cat) {
                        if (!isset($cat['id']) || empty($cat['id'])) {
                            $newCategories[] = $cat;
                        }
                    }
                    session(['newCategoryAdded' => $newCategories]);
                }

                $result = array(
                    "status"     => 1,
                    'message' => __('Successfully Registered')
                );

                if ($newCategoryAdded) {
                    return redirect()->route('calendar.settings')->with('success', 'Paramètres des catégories enregistrés avec succès.')->with('success_new_cat', 'true');
                }
                return redirect()->route('calendar.settings')->with('success', 'Paramètres des catégories enregistrés avec succès.');

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
