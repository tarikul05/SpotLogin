<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\EmailTemplate;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Teacher;
use App\Models\SchoolTeacher;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use Illuminate\Support\Facades\URL;
use App\Models\AttachedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as InterventionImageManager;
use App\Mail\SportloginEmail;
use App\Http\Requests\ProfilePhotoUpdateRequest;

class SchoolsController extends Controller
{


    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->img_config = [
            'target_path' => [
              'SchoolLogo' => 'photo/school_photo'
            ],
            'target_url' => [
              'SchoolLogo' => URL::to('').'/uploads/photo/school_photo/'
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
    }

    public function __processImg($file,$type,$school,$shouldCrop=false,$shouldResize=false) {
    
        $imageNewName = 'school_'.$school->id.'_dp.'.$file->getClientOriginalExtension();
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, School $school)
    {
        try{
            $result = [];
            $params = $request->all();
            $query = $school->filter($params);
            $schools = $query->get();
            return view('pages.schools.list')
            ->with(compact('schools'));
        } catch(\Exception $e){
            echo $e->getMessage(); exit;
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, School $school)
    {
       
        $response = [];
        $authUser = $request->user();
        if ($authUser->person_type != 'SUPER_ADMIN') {
            if (!empty($authUser->getRelatedSchoolAttribute())) {
                $p_school_id = $authUser->getRelatedSchoolAttribute()['id'];
                $role_type = $authUser->getRoleTypeAttribute();
                $school = School::find($p_school_id);
                if ($role_type=='school_admin') {
                    $school_admin = $authUser;
                } else {
                    try{
                        $teacher = SchoolTeacher::where([
                            ['school_id', $school->id],
                            ['is_active', 1],
                            ['role_type', 'school_admin'],
                            ['has_user_account', 1]
                        ])->first();
                        $school_admin = User::where([
                            ['person_id', $teacher->id],
                            ['is_active', 1],
                            ['person_type', 'App\Models\Teacher']
                        ])->first();
                    } catch (\Exception $e) {
                        //return error message
                        return redirect()->route('schools')->with('error', __('School admin not exist'));
                    }
                }
            } 
        } else {
            $role_type = $authUser->person_type;
            
            try{
                $teacher = SchoolTeacher::where([
                    ['school_id', $school->id],
                    ['is_active', 1],
                    ['role_type', 'school_admin'],
                    ['has_user_account', 1]
                ])->first();
                $school_admin = User::where([
                    ['person_id', $teacher->id],
                    ['is_active', 1],
                    ['person_type', 'App\Models\Teacher']
                ])->first();
            } catch (\Exception $e) {
                //return error message
                return redirect()->route('schools')->with('error', __('School admin not exist'));
            }
            
        }
        
            
        $lanCode = 'en';
        if (Session::has('locale')) {
            $lanCode = Session::get('locale');
        }
        $currency = Currency::all();  
        $country = Country::all();  
        $legal_status = config('global.legal_status');
        
        
        $emailTemplate = EmailTemplate::where([
            ['template_code', 'school'],
            ['language', $lanCode]
        ])->first(); 
        if ($emailTemplate) {
            $http_host=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/" ;
            if (!empty($emailTemplate->body_text)) {
                $emailTemplate->body_text = str_replace("[~~ HOSTNAME ~~]",$http_host,$emailTemplate->body_text);
                $emailTemplate->body_text = str_replace("[~~HOSTNAME~~]",$http_host,$emailTemplate->body_text);
            }
        } 
        
        if($school->incorporation_date != null){
            
            $school->incorporation_date = str_replace('-', '/', $school->incorporation_date);
            //$school->incorporation_date = Carbon::createFromFormat('Y/m/d', $school->incorporation_date);
        } 
        return view('pages.schools.edit')
        ->with(compact('legal_status','currency','school','emailTemplate','country','role_type','school_admin'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  School $school
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, School $school)
    {
        $params = $request->all();
        try{
            $school->update($request->except(['_token']));
            return back()->withInput($request->all())->with('success', __('School updated successfully!'));
        } catch (\Exception $e) {
            //return error message
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  School $school
     * @return \Illuminate\Http\Response
     */
    public function userUpdate(Request $request, School $school)
    {
        $params = $request->all();
        try{
            $request->merge([
                'email'=> $params['admin_email'],
                'is_active'=> $params['admin_is_active']
            ]);

            $user = User::find($params['user_id']);
            if ($user) {
                $user->update($request->except(['_token']));

                if (!empty($params['admin_password'])) {
                    $user->password = $params['admin_password'];
                    $user->save();
                }
            } else{
                return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
            }

            return back()->withInput($request->all())->with('success', __('School updated successfully!'));
        } catch (\Exception $e) {
            //return error message
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
    }


    /**
     * Update the specified resource in storage.
    *
    * @param  \App\Http\Requests\ProfilePhotoUpdateRequest  $request
    * @return \Illuminate\Http\Response
    */
    public function logoUpdate(ProfilePhotoUpdateRequest $request)
    {
        $data = $request->all();
        $result = array(
        'status' => 0,
        "file_id" => '0',
        "image_file" => '',   
        'message' => __('failed to change image'),
        );
        try{
            $school = School::find($data['school_id']); 
            if($request->file('profile_image_file'))
            {
                
                $image = $request->file('profile_image_file');
                $mime_type = $image->getMimeType();
                $extension = $image->getClientOriginalExtension();
                if($image->getSize()>0)
                { 
                    list($path, $imageNewName) = $this->__processImg($image,'SchoolLogo',$school); 
                    
                    if (!empty($path)) {
                        $fileData = [
                            'visibility' => 1,
                            'file_type' =>'image',
                            'title' => $school->school_name,
                            'path_name' =>$path,
                            'file_name' => $imageNewName,
                            'extension'=>$extension,
                            'mime_type'=>$mime_type
                        ];
                        
                        $attachedImage = AttachedFile::create($fileData);
                        
                        $data['logo_image_id'] = $attachedImage->id;
                        
                    }
                }
            }
            
            if ($school->update($data)) {
                $result = array(
                "status"     => 1,
                "file_id" => $school->logo_image_id,
                "image_file" => $path,   
                'message' => __('Successfully Changed Profile image')
                );
            }
        
        } catch (\Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
        }
        return response()->json($result);
    }


    public function logoDelete(Request $request)
    {
        $data = $request->all();
        $school = School::find($data['school_id']); 
        $result = array(
            'status' => 'failed',   
            'message' => __('failed to remove image'),
        );
        try{
            $path_name =  $school->logoImage->path_name;
            $file = str_replace(URL::to('').'/uploads/','',$path_name);

            $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
            if(file_exists($storagePath.$file)) unlink($storagePath.$file);
            AttachedFile::find($school->logoImage->id)->delete();
            $data['logo_image_id'] ='';
            if ($school->update($data)) {
                $result = array(
                    "status"     => 'success',
                    'message' => __('Successfully Changed School logo image')
                );
            }
        }
        catch (\Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
        }
        return response()->json($result);
    }




    /**
     * school email send from admin
     * 
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-09
     */
    public function schoolEmailSend(Request $request)
    {
        $result = array(
            'status' => false,
            'message' => __('failed to send email'),
        );
        try {
            $data = $request->all();
            
            $user = User::find($data['user_id']); 
            if ($user) {
                //sending email for forgot password
                if (config('global.email_send') == 0) {
                    
                    try {
                        $data['email'] = $data['email_to_id'];
                        $data['name'] = $user->username;
                        $data['username'] = $user->username; 
                        if (!empty($data['admin_password'])) {
                            $data['password'] = $data['admin_password']; 
                        } else {
                            $data['password'] = config('global.user_default_password');
                        }
                        $data['subject'] = $data['subject_text'];
                        $data['body_text'] = $data['email_body'];
                        \Mail::to($user->email)->send(new SportloginEmail($data));
                        $result = array(
                            'status' => true,
                            'message' => __('We sent you an activation link. Check your email and click on the link to verify.'),
                        );
                        
                        return response()->json($result);
                    } catch (\Exception $e) {
                        $result = array(
                            'status' => true,
                            'message' => __('We sent you an activation code. Check your email and click on the link to verify.'),
                        );
                        return response()->json($result);
                    }
                } else{
                    $result = array('status'=>true,'msg'=>__('email sent'));
                }
            }   else {
                $result = array('status'=>false,'msg'=>__('Username not exist'));
            }

            return response()->json($result);

        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
        
    }


    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
