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
use Carbon\Carbon;

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
    public $schoolId = 0;
    public $timezone = 'UTC';

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
            //$schoolId ='';
            $data = $request->all();
            if (isset($data['school_id'])) {
                $this->schoolId =$data['school_id'];
            }
            if (Auth::check() && $this->isAuthorized()) {
                $user = Auth::user();
                $this->schoolId = $user->isSuperAdmin() ? $this->schoolId : $user->selectedSchoolId() ;
                $school = School::active()->find($this->schoolId);
                $this->timezone = !empty($school->timezone) ? $school->timezone : 'UTC';
                if ($user->isSuperAdmin()) {
                    $this->timezone = 'UTC';
                } 
                $this->AppUI = Auth::user();
            }
            $data = array(
                'controller' => $this->controller,
                'action' => $this->action,
                'CURRENT_URL' => $this->CURRENT_URL,
                'BASE_URL' => $this->BASE_URL,
                'AppUI' => $this->AppUI,
                'schoolId' => $this->schoolId,
                'timezone' => $this->timezone
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
            $lang = 'en'; 
            if (isset($data['p_lang'])) {
                $lang = $data['p_lang']; 
            }

            $emailTemplateExist = EmailTemplate::where([
                ['template_code', $template_code],
                ['language', $lang]
            ])->first(); 

            if ($emailTemplateExist) {
                $email_body= $emailTemplateExist->body_text;
                $data['subject'] = $emailTemplateExist->subject_text;
            }  else{
                $email_body='<p><strong><a href="[~~URL~~]">CONFIRM</a></strong></p>';
                if (isset($data['subject'])) {
                    $data['subject']=$data['subject'];
                } else {
                    $data['subject']=__('www.sportogin.ch: Welcome! Activate account.');
                }
                
            }  
            $data['body_text'] = $email_body;
            
            if (isset($data['url'])) {
                $data['url'] = $data['url']; 
            } else {
                if (isset($data['token'])) {
                    $data['url'] = route('add.verify.email',$data['token']); 
                }
            }
            \Mail::to($data['email'])->send(new SportloginEmail($data));
            return true;
        } catch (\Exception $e) {
            return false;
        } 
        
        
    }

     /**
     * Common function for email send
     *
     */
    public function emailSendWithoutTemplate($data=[], $to_email = null) {
        
        try {
            \Mail::to($to_email)->send(new SportloginEmail($data));
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



    public function sdateFormat($date){
        return $date = str_replace('/', '-', $date);
    }

    public function get_local_time(){

        $ip = file_get_contents("http://ipecho.net/plain");
     
        $url = 'http://ip-api.com/json/'.$ip;
     
        $tz = file_get_contents($url);
     
        $tz = json_decode($tz,true)['timezone'];
     
        return $tz;
     
     }
    /**
     * Return the formated date based on timezone selected from sidebar nav | Front-end user | Saved in Cookie | Sent $to
     *
     * @param Carbon $date
     * @param null $type (long | short)
     * @return Carbon date
     */
    public function formatDateTimeZone($date, $type = 'long', $from = null, $to = null)
    {
        if (!$from)
            $from = 'UTC';

        if (!$to){
            $to = $this->get_local_time();
        }
        $carbon = Carbon::createFromFormat('Y-m-d H:i:s', $date, $from); // specify UTC otherwise defaults to locale time zone as per ini setting
        $carbon->setTimezone($to)->format('Y-m-d H:i:s');
        if ($type == 'short')
        {
            $carbon = Carbon::createFromFormat('Y-m-d', $date, $from); // specify UTC otherwise defaults to locale time zone as per ini setting
            $carbon->setTimezone($to)->format('Y-m-d');
        }
        return $carbon->toDateTimeString();
    }

}
