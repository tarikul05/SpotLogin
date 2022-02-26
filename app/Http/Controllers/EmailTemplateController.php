<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\EmailTemplate;
use App\Http\Requests\FetchTemplateRequest;
use App\Http\Requests\EmailTemplateRequest;
use File;


class EmailTemplateController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:email-template-list|email-template-add-udpate', ['only' => ['index','templateVariables']]);
        $this->middleware('permission:email-template-add-udpate', ['only' => ['addUpdate']]);
    }
     /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function index()
    {
        $language = Language::orderBy('sort_order')->get();
        
        $email_template_code = config('global.email_template');
        
        return view('pages.emails.add', [
            'alllanguages' => $language,
            'email_template' => $email_template_code,
            'title' => 'Email Template',
            'pageInfo'=>['siteTitle'=>'']
        ]);

    }   
    /**
     * Remove the specified resource from storage.
     * @return Response
    */
 
    public function addUpdate(EmailTemplateRequest $request)
    {
        $params = $request->all();  
        try{
            $request->merge(['language'=> $params['language_id'],'is_active'=> 'Y']);
            $template = EmailTemplate::where([
                ['template_code', $params['template_code']],
                ['language', $params['language_id']],
                ['is_active', 'Y']
            ])->first(); 
            if (empty($template)) {
                EmailTemplate::create($request->except(['_token']));
                return back()->withInput($request->all())->with('success', __('Email Template added successfully!'));
            }else{
                $template->update($request->except(['_token']));
                return back()->withInput($request->all())->with('success', __('Email Template updated successfully!'));
            }
        } catch (\Exception $e) {
            //return error message
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));

        }
        
    }

    
    public function templateVariables(Request $request)
    {
        $result =[];
        if (config('global.template_variables')) {
            $result = config('global.template_variables');
        }
        return response()->json($result);
    }


   

     /**
     * Open Translation File
     * @return Response
    */
 
    private function openJSONFile($code){
        $jsonString = [];
        if(File::exists(base_path('resources/lang/'.$code.'.json'))){
            $jsonString = file_get_contents(base_path('resources/lang/'.$code.'.json'));
            $jsonString = json_decode($jsonString, true);
        }
        $email_template_code = config('global.email_template');
        $email_template_code = array_keys($email_template_code);
        return $filtered = array_filter(
            $jsonString,
            fn ($key) => in_array($key, $email_template_code),
            ARRAY_FILTER_USE_KEY
        );
        
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
                $lngdata = $this->openJSONFile($params['language_id']);
                // app()->setLocale($params['language_id']);
                // session()->put('locale', $params['language_id']);
                $template = EmailTemplate::where([
                    ['template_code', $params['template_code']],
                    ['language', $params['language_id']],
                    ['is_active', 'Y']
                ])->first(); 
                if ($template) {
                    $result = [
                        'status'=>1,
                        'message'=>__('email template found'),
                        'data'=>$template,
                        'lngdata'=>$lngdata
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
