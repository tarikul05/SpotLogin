<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use View;
use Route;
use Illuminate\Support\Facades\URL;
use App\Mail\SportloginEmail;
use App\Models\EmailTemplate;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $BASE_URL = '';
    public $CURRENT_URL = '';
    /** @var object $AppUI Session infomation of user logged. */
    public $AppUI = null;
    /** @var object $controller Controller name. */
    public $controller = null;
    public $action = null;
    public $all_authority = null;

    public function __construct()
    {
        $currentAction = Route::currentRouteAction();
        list($controller, $method) = explode('@', $currentAction);

        $controller = preg_replace('/.*\\\/', '', $controller);

    	$this->controller = strtolower($controller);
        $this->action = strtolower($method);
        $this->CURRENT_URL = url()->full();
        $this->BASE_URL = URL::to('');

        

        $this->middleware(function ($request, $next) {

            if (Auth::check() && $this->isAuthorized()) {
                $user = Auth::user();

                $this->AppUI = Auth::user();
            }
            $data = array(
                'controller' => $this->controller,
                'action' => $this->action,
                'CURRENT_URL' => $this->CURRENT_URL,
                'BASE_URL' => $this->BASE_URL,
                'AppUI' => $this->AppUI
            );
            View::share($data);
            return $next($request);
        });
    } 
    
     /**
     * Commont function check user is Authorized..
     *
     *
     * @param object $user Session user logged.
     * @return boolean  If true is authorize, and false is unauthorize.
     */
    public function isAuthorized($user = null) {
        if (Auth::check()) {
            if (empty($user)) {
                $user = Auth::user();
            }
            if (!empty($user)) {
                $this->AppUI = $user;
                return true;
            }
            return false;
        } else {
            return false;
        }
        
        
    }



     /**
     * Common function for email send
     *
     */
    public function emailSend($data=[], $template_code=null) {
        
        try {
            $emailTemplateExist = EmailTemplate::where([
                ['template_code', $template_code],
                ['language', 'en']
            ])->first(); 

            if ($emailTemplateExist) {
                $email_body= $emailTemplateExist->body_text;
                $data['subject'] = $emailTemplateExist->subject_text;
            }  else{
                $email_body='<p><strong><a href="[~~URL~~]">CONFIRM</a></strong></p>';
                $data['subject']=__('www.sportogin.ch: Welcome! Activate account.');
            }  
            $data['body_text'] = $email_body;
            $data['url'] = route('add.verify.email',$data['token']); 
            \Mail::to($data['email'])->send(new SportloginEmail($data));
            return true;
        } catch (\Exception $e) {
            return false;
        } 
        
        
    }
}
