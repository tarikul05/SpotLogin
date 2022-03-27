<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use View;
use Route;
use Illuminate\Support\Facades\URL;
use App\Mail\SportloginEmail;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as InterventionImageManager;


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


        
        $this->img_config = [
            'target_path' => [
                'SchoolLogo' => 'photo/school_photo',
                'UserImage' => 'photo/user_photo',
                'StudentImage' => 'photo/student_photo'
            ],
            'target_url' => [
                'SchoolLogo' => URL::to('').'/uploads/photo/school_photo/',
                'UserImage' => URL::to('').'/uploads/photo/user_photo/',
                'StudentImage' => URL::to('').'/uploads/photo/student_photo/'
            ],
        ];
          
        // create the folder if it does not exist
        foreach ($this->img_config['target_path'] as $img_dir) {
            if (!is_dir($img_dir)) {
              if (!mkdir($img_dir, 0777, true)) {
                die('Failed to create folders...');
              }
            }
        }

        

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



    /**
   * image process and upload
   * 
   * @return json
   * @author Mamun <lemonpstu09@gmail.com>
   * @version 0.1 written in 2022-03-10
   */
    public function __processImg($file,$type,$authUser,$shouldCrop=false,$shouldResize=false) {
        
        $imageNewName = 'user_'.$authUser->id.'_dp.'.$file->getClientOriginalExtension();
        $uploadedPath = $this->img_config['target_path'][$type];
        
        $filePath = $uploadedPath.'/'.date('Y/m/d') . '/'. $imageNewName;
        $fileContent = null;
        
        if ($shouldCrop) {
            $interventionImage = InterventionImageManager::make($file->getRealPath());
            
            if (!$width && !$height) {
                $height = $interventionImage->height();
                $width = $interventionImage->width();
                
                if ($height <= $width) {
                    $width = $height;
                } else {
                    $height = $width;
                }
            } else {
                if (!$width) {
                    $width = $interventionImage->width();
                }
                
                if (!$height) {
                    $height = $interventionImage->height();
                }
            }
            
            $croppedImage = $interventionImage->fit($width, $height);
            // $croppedImage = $interventionImage->fit($width, $height, function ($constraint) {
            //     $constraint->upsize();
            // });
            $croppedImageStream = $croppedImage->stream();
            
            $fileContent = $croppedImageStream->__toString();
        } else if($shouldResize){
            $interventionImage = InterventionImageManager::make($file->getRealPath());
            if (!isset($width) && !isset($height)) {
                $height = $interventionImage->height();
                $width = $interventionImage->width();
                
                if ($height <= $width) {
                    $width = $height;
                } else {
                    $height = $width;
                }
            } else {
                if (!isset($width)) {
                    $width = $interventionImage->width();
                }
                
                if (!isset($height)) {
                    $height = $interventionImage->height();
                }
            }
            $resizedImage = $interventionImage->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $resizedImageStream = $resizedImage->stream();
            $fileContent = $resizedImageStream->__toString();
        } else {
            $fileContent = file_get_contents($file);
        }
        $result = Storage::disk('local')->put($filePath, $fileContent);
        if (!$result) {
            throw new HttpResponseException(response()->error('Image could not be uploaded', Response::HTTP_BAD_REQUEST));
        }
        return [$this->img_config['target_url'][$type] .date('Y/m/d') . '/'. $imageNewName, $imageNewName];
    }

}
