<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\EmailTemplate;
use App\Http\Requests\FetchTemplateRequest;


class EmailTemplateController extends Controller
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
 
    public function addUpdate(Request $request, EmailTemplate $emailTemplate)
    {
        $params = $request->all();
        
        if ($request->isMethod('post')){
            
            try{
                $this->validate($request, [
                    'template_code' => 'required',
                    'subject_text' => 'required',
                    'body_text' => 'required',
                    'language_id' => 'required',
                ]);
                $request->merge(['language'=> $params['language_id'],'is_active'=> 'Y']);
                
                $template = EmailTemplate::where([
                    ['template_code', $params['template_code']],
                    ['language', $params['language_id']],
                    ['is_active', 'Y']
                ])->first(); 
                if (empty($template)) {
                    EmailTemplate::create($request->except(['_token']));
                    $this->generateLanFile($lanCode);
                    return back()->with('success', __('Email Template added successfully!'));
                }else{
                    $template->update($request->except(['_token']));
                    return back()->with('success', __('Email Template updated successfully!'));
                }
            } catch (\Exception $e) {
                //return error message
                return redirect()->back()->with('error', __('Internal server error'));

            }
        }
        $language = Language::orderBy('sort_order')->get();
        
        $email_template_code = config('global.email_template');
        
        return view('pages.emails.add', [
            'alllanguages' => $language,
            'email_template' => $email_template_code,
            'title' => 'Email Template',
            'pageInfo'=>['siteTitle'=>'']
        ]);
    }

    
    public function templateVariables(Request $request)
    {
        $result =[];
        if (config('global.template_variables')) {
            $result = config('global.template_variables');
        }
        return response()->json($result);
    }

    

    public function getEmailTemplate(FetchTemplateRequest $request, EmailTemplate $emailTemplate)
    {
        $params = $request->all();
        $result = array(
            'status' => 0,
            'message' => __('Failed to get email template'),
        );
        try {
            if ($request->isMethod('post')){
                $template = EmailTemplate::where([
                    ['template_code', $params['template_code']],
                    ['language', $params['language_id']],
                    ['is_active', 'Y'],
                    ['deleted_at', null],
                ])->first(); 
                if ($template) {
                    $result = [
                        'status'=>1,
                        'message'=>__('email template found'),
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
