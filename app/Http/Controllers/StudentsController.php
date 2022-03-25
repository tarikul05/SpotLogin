<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\Student;
use App\Models\School;
use App\Models\SchoolStudent;
use App\Models\User;
use App\Models\Country;
use App\Models\EmailTemplate;
use App\Models\SchoolTeacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use App\Http\Requests\ProfilePhotoUpdateRequest;
use Illuminate\Support\Facades\URL;
use App\Models\AttachedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as InterventionImageManager;
use App\Mail\SportloginEmail;



class StudentsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->middleware('permission:students-list|students-create|students-update|students-users-update|students-delete', ['only' => ['index']]);
        // $this->middleware('permission:students-create', ['only' => ['create','AddTeacher']]);
        // $this->middleware('permission:students-update', ['only' => ['edit','update']]);
        // $this->middleware('permission:students-users-update', ['only' => ['teacherEmailSend','userUpdate']]);
        // $this->middleware('permission:students-delete', ['only' => ['destroy']]);


        $this->img_config = [
          'target_path' => [
            'StudentImage' => 'photo/student_photo'
          ],
          'target_url' => [
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($schoolId = null)
    {

        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return redirect()->route('schools')->with('error', __('School is not selected'));
            }
            $students = $school->students; 
        }else {
            $students = $user->getSelectedSchoolAttribute()->students;
        }
        dd($students);
        // $students = Student::where('is_active', 1)->get();
        return view('pages.students.list',compact('students','schoolId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $schoolId = null)
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

        $countries = Country::active()->get();
        $genders = config('global.gender'); 
        $exTeacher = $searchEmail = null;
        if ($request->isMethod('post')){
            $searchEmail = $request->email;
            $exTeacher = User::where(['email'=> $searchEmail, 'person_type' =>'App\Models\Teacher' ])->first();
        }
        return view('pages.students.add')->with(compact('countries','genders','exTeacher','searchEmail','schoolId'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function AddStudent(Request $request, $schoolId = null)
    { 
      
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return [
                    'status' => 1,
                    'message' =>  __('School not selected')
                ];
            }
            $schoolId = $school->id; 
        }else {
            $schoolId = $user->selectedSchoolId();
        }
        
        DB::beginTransaction(); 
        try{

            $authUser = $request->user();
            if ($request->isMethod('post')){
                $alldata = $request->all();
                $studentData = [
                    'is_active' => $alldata['is_active'],
                    'gender_id' => $alldata['gender_id'],
                    'lastname' => $alldata['lastname'],
                    'firstname' => $alldata['firstname'],
                    'birth_date' => date('Y-m-d H:i:s',strtotime($alldata['birth_date'])),
                    'street' => $alldata['street'],
                    'street_number' => $alldata['street_number'],
                    'zip_code' => $alldata['zip_code'],
                    'place' => $alldata['place'],
                    'country_code' => $alldata['country_code'],
                    'province_id' => $alldata['province_id'],
                    'billing_street' => $alldata['billing_street'],
                    'billing_street2' => $alldata['billing_street2'],
                    'billing_street_number' => $alldata['billing_street_number'],
                    'billing_zip_code' => $alldata['billing_zip_code'],
                    'billing_place' => $alldata['billing_place'],
                    'billing_country_code' => $alldata['billing_country_code'],
                    'billing_province_id' => $alldata['billing_province_id'],
                    'phone' => $alldata['phone'],
                    'phone2' => $alldata['phone2'],
                    'mobile' => $alldata['mobile'],
                    'email2' => $alldata['email2'],
                    'student_email' => $alldata['student_email']
                ];
                
                if($request->file('profile_image_file'))
                {
                  $image = $request->file('profile_image_file');
                  $mime_type = $image->getMimeType();
                  $extension = $image->getClientOriginalExtension();
                  if($image->getSize()>0)
                  { 
                    list($path, $imageNewName) = $this->__processImg($image,'StudentImage',$authUser); 
                    
                    if (!empty($path)) {
                      $fileData = [
                        'visibility' => 1,
                        'file_type' =>'image',
                        'title' => $authUser->username,
                        'path_name' =>$path,
                        'file_name' => $imageNewName,
                        'extension'=>$extension,
                        'mime_type'=>$mime_type
                      ];
                      
                      $attachedImage = AttachedFile::create($fileData);
                      $studentData['profile_image_id'] = $attachedImage->id;
                      
                    }
                  }
                }
                
                $student = Student::create($studentData);
                $student->save();
                
                $schoolStudent = [
                    'student_id' => $student->id,  
                    'school_id' => $schoolId,
                    'has_user_account' => !empty($alldata['has_user_account']) ? $alldata['has_user_account'] : null,
                    'nickname' => $alldata['nickname'],
                    'email' => $alldata['email'],
                    'billing_method' => $alldata['billing_method'],
                    'level_id' => $alldata['level_id'],
                    'level_date_arp' => date('Y-m-d H:i:s',strtotime($alldata['level_date_arp'])),
                    'licence_arp' => $alldata['licence_arp'],
                    'licence_usp' => $alldata['licence_usp'],
                    'level_skating_usp' => $alldata['level_skating_usp'],
                    'level_date_usp' => date('Y-m-d H:i:s',strtotime($alldata['level_date_usp'])),
                    'comment' => $alldata['comment'],
                ];

                $schoolStudentData = SchoolStudent::create($schoolStudent);
                $schoolStudentData->save();
            }
            DB::commit();
            return back()->withInput($request->all())->with('success', __('Student added successfully!'));
        }catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }   

        
        return $result;
    }

         /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editStudentAction(Request $request, Student $student)
    { 
      
        // $user = Auth::user();
        // if ($user->isSuperAdmin()) {
        //     $school = School::active()->find($schoolId);
        //     if (empty($school)) {
        //         return [
        //             'status' => 1,
        //             'message' =>  __('School not selected')
        //         ];
        //     }
        //     $schoolId = $school->id; 
        // }else {
        //     $schoolId = $user->selectedSchoolId();
        // }
        
        DB::beginTransaction(); 
        try{

            $authUser = $request->user();
            if ($request->isMethod('post')){
                $alldata = $request->all();
                $studentData = [
                    'is_active' => $alldata['is_active'],
                    'gender_id' => $alldata['gender_id'],
                    'lastname' => $alldata['lastname'],
                    'firstname' => $alldata['firstname'],
                    'birth_date' => date('Y-m-d H:i:s',strtotime($alldata['birth_date'])),
                    'street' => $alldata['street'],
                    'street_number' => $alldata['street_number'],
                    'zip_code' => $alldata['zip_code'],
                    'place' => $alldata['place'],
                    'country_code' => $alldata['country_code'],
                    'province_id' => $alldata['province_id'],
                    'billing_street' => $alldata['billing_street'],
                    'billing_street2' => $alldata['billing_street2'],
                    'billing_street_number' => $alldata['billing_street_number'],
                    'billing_zip_code' => $alldata['billing_zip_code'],
                    'billing_place' => $alldata['billing_place'],
                    'billing_country_code' => $alldata['billing_country_code'],
                    'billing_province_id' => $alldata['billing_province_id'],
                    'phone' => $alldata['phone'],
                    'phone2' => $alldata['phone2'],
                    'mobile' => $alldata['mobile'],
                    'email2' => $alldata['email2'],
                    'student_email' => $alldata['student_email']
                ];
                
                if($request->file('profile_image_file'))
                {
                  $image = $request->file('profile_image_file');
                  $mime_type = $image->getMimeType();
                  $extension = $image->getClientOriginalExtension();
                  if($image->getSize()>0)
                  { 
                    list($path, $imageNewName) = $this->__processImg($image,'StudentImage',$authUser); 
                    
                    if (!empty($path)) {
                      $fileData = [
                        'visibility' => 1,
                        'file_type' =>'image',
                        'title' => $authUser->username,
                        'path_name' =>$path,
                        'file_name' => $imageNewName,
                        'extension'=>$extension,
                        'mime_type'=>$mime_type
                      ];
                      
                      $attachedImage = AttachedFile::create($fileData);
                      $studentData['profile_image_id'] = $attachedImage->id;
                      
                    }
                  }
                }
                
                Student::where('id', $student->id)->update($studentData);
                
                $schoolStudent = [
                    'student_id' => $student->id,  
                    'school_id' => $alldata['school_id'],
                    'has_user_account' => !empty($alldata['has_user_account']) ? $alldata['has_user_account'] : null,
                    'nickname' => $alldata['nickname'],
                    'email' => $alldata['email'],
                    'billing_method' => $alldata['billing_method'],
                    'level_id' => $alldata['level_id'],
                    'level_date_arp' => date('Y-m-d H:i:s',strtotime($alldata['level_date_arp'])),
                    'licence_arp' => $alldata['licence_arp'],
                    'licence_usp' => $alldata['licence_usp'],
                    'level_skating_usp' => $alldata['level_skating_usp'],
                    'level_date_usp' => date('Y-m-d H:i:s',strtotime($alldata['level_date_usp'])),
                    'comment' => $alldata['comment'],
                ];

                SchoolStudent::where(['student_id'=>$student->id, 'school_id'=>$alldata['school_id']])->update($schoolStudent);
            }
            DB::commit();
            return back()->withInput($request->all())->with('success', __('Student added successfully!'));
        }catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }   

        
        return $result;
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
    public function edit(Request $request)
    {
        $user = Auth::user();
        $alldata = $request->all();
        $schoolId = $request->route('school'); 
        $studentId = $request->route('student');
        
        $student = Student::find($studentId);

        if ($user->isSuperAdmin()) {
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return redirect()->route('schools')->with('error', __('School is not selected'));
            }
            $schoolId = $school->id;
            $schoolName = $school->school_name; 
        }else {
            $schoolId = $user->selectedSchoolId();
            $schoolName = $user->selectedSchoolName(); 
        }

        $relationalData = SchoolStudent::where([
            ['student_id',$studentId]
        ])->first();
        
        $profile_image = !empty($student->profile_image_id) ? AttachedFile::find($student->profile_image_id) : null ;
        $countries = Country::active()->get();
        $genders = config('global.gender');
        return view('pages.students.edit')->with(compact('countries','genders','student','relationalData','profile_image','schoolId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
       
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


    /**
    *  AJAX action image delete and unlink
    * 
    * @return json
    * @author Mamun <lemonpstu09@gmail.com>
    * @version 0.1 written in 2022-03-10
    */
    public function profilePhotoDelete(Request $request)
    {
        $authUser = $request->user();
        $data = $request->all();
        $user = User::find($data['user_id']); 
        $result = array(
          'status' => 'failed',   
          'message' => __('failed to remove image'),
        );
        try{
          $path_name =  $user->profileImage->path_name;
          $file = str_replace(URL::to('').'/uploads/','',$path_name);

          $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
          if(file_exists($storagePath.$file)) unlink($storagePath.$file);
          AttachedFile::find($user->profileImage->id)->delete();
          $data['profile_image_id'] =null;
          if ($user->update($data)) {
            $result = array(
              "status"     => 'success',
              'message' => __('Successfully Changed Profile image')
            );
          }
        }
        catch (\Exception $e) {
          //return error message
          $result['message'] = __('Internal server error');
        }
        return response()->json($result);
    }
}
