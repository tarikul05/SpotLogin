<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\TermCondition;
use App\Models\TermConditionLang;
use App\Http\Requests\FetchTermCondCMSRequest;
use App\Http\Requests\TermCondRequest;


class TermCondController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:terms-condition-list|terms-condition-add-udpate', ['only' => ['index']]);
        $this->middleware('permission:terms-condition-add-udpate', ['only' => ['addUpdate']]);
         // $this->middleware('permission:language-delete', ['only' => ['destroy']]);
    }

     /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function index()
    {
        $language = Language::orderBy('sort_order')->get();

        return view('pages.term_cond.addcms', [
            'alllanguages' => $language,
            'title' => 'Term Condition Template',
            'pageInfo'=>['siteTitle'=>'']
        ]);
    }
    /**
     * Remove the specified resource from storage.
     * @return Response
    */

    public function addUpdate(TermCondRequest $request, TermCondition $emailTemplate)
    {
        $params = $request->all();
        try{
            $template = TermConditionLang::where([
                ['tc_template_id', $params['tc_template_id']],
                ['language_id', $params['language_id']],
                ['is_active', 1]
            ])->first();
            if (isset($params['request_all']) && !empty($params['request_all'])) {
                $request->merge(['type'=>'A','active_flag'=> 'Y']);
                if (empty($template)) {



                    $template = TermCondition::create($request->except(['_token']));
                    $request->merge(['tc_template_id'=> $template->id]);

                    TermConditionLang::create($request->except(['_token']));
                    return back()->withInput($request->all())->with('success', __('Term Condition Template added successfully!'));
                }else{
                    $template->delete();
                    $tc = TermCondition::find($params['tc_template_id']);
                    $tc->delete();

                    $template = TermCondition::create($request->except(['_token']));
                    $request->merge(['tc_template_id'=> $template->id]);

                    TermConditionLang::create($request->except(['_token']));
                    return back()->withInput($request->all())->with('success', __('Term Condition Template added successfully!'));
                }
            } else {
                if (empty($template)) {

                    $request->merge(['type'=>'A','active_flag'=> 'Y']);

                    $template = TermCondition::create($request->except(['_token']));
                    $request->merge(['tc_template_id'=> $template->id]);

                    TermConditionLang::create($request->except(['_token']));
                    return back()->withInput($request->all())->with('success', __('Term Condition Template added successfully!'));
                }else{
                    $template->update($request->except(['_token']));
                    return back()->withInput($request->all())->with('success', __('Term Condition Template updated successfully!'));
                }
            }

        } catch (\Exception $e) {
            //return error message
            return redirect()->withInput($request->all())->back()->with('error', __('Internal server error'));

        }

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
