<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\TermCondition;
use App\Models\TermConditionLang;
use App\Http\Requests\FetchTermCondCMSRequest;



class TermCondController extends Controller
{
     /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function index()
    {

    }   
    /**
     * Remove the specified resource from storage.
     * @return Response
    */
 
    public function addUpdateCMS(Request $request, TermCondition $emailTemplate)
    {
        $params = $request->all();
        
        if ($request->isMethod('post')){
            
            try{
                $this->validate($request, [
                    'tc_template_id' => 'required',
                    'language_id' => 'required',
                    'tc_text' => 'required',
                    'spp_text' => 'required',
                ]);
                
                $template = TermConditionLang::where([
                    ['tc_template_id', $params['tc_template_id']],
                    ['language_id', $params['language_id']],
                    ['is_active', 1]
                ])->first(); 
                
                if (empty($template)) {
                    
                    $request->merge(['type'=>'A','active_flag'=> 'Y']);
                
                    $template = TermCondition::create($request->except(['_token']));
                    $request->merge(['tc_template_id'=> $template->id]);
                
                    TermConditionLang::create($request->except(['_token']));
                    return back()->with('success', __('Term Condition Template added successfully!'));
                }else{
                    $template->update($request->except(['_token']));
                    return back()->with('success', __('Term Condition Template updated successfully!'));
                }
            } catch (\Exception $e) {
                //return error message
                return redirect()->back()->with('error', __('Internal server error'));

            }
        }
        $language = Language::orderBy('sort_order')->get();
        
        return view('pages.term_cond.addcms', [
            'alllanguages' => $language,
            'title' => 'Term Condition Template',
            'pageInfo'=>['siteTitle'=>'']
        ]);
    }

    



    
    

    public function getTcTemplate(FetchTermCondCMSRequest $request)
    {
        $params = $request->all();
        $result = array(
            'status' => 0,
            'message' => __('Failed to get Term and Condition template'),
        );
        try {
            if ($request->isMethod('post')){
                if ($params['tc_template_id'] !=0) {
                    $template = TermConditionLang::where([
                        ['tc_template_id', $params['tc_template_id']],
                        ['language_id', $params['language_id']],
                        ['is_active', 1]
                    ])->first();
                } else {
                    $template = TermConditionLang::where([
                        ['language_id', $params['language_id']],
                        ['is_active', 1]
                    ])->orderBy('id', 'desc')->first();
                    
                }
                 
                if ($template) {
                    $result = [
                        'status'=>1,
                        'message'=>__('Term Condition template found'),
                        'data'=>$template
                    ];
                } 
                
            }
            return response()->json($result);
        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
    }

    
 
 
}
