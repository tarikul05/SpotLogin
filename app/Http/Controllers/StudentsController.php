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
use App\Models\Level;
use App\Models\EmailTemplate;
use App\Models\SchoolTeacher;
use App\Models\VerifyToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use App\Http\Requests\ProfilePhotoUpdateRequest;
use Illuminate\Support\Facades\URL;
use App\Models\AttachedFile;
use Illuminate\Support\Facades\Storage;
use App\Mail\SportloginEmail;



class StudentsController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('permission:students-list|students-create|students-update|students-view|students-users-update|students-delete', ['only' => ['index']]);
        $this->middleware('permission:students-create', ['only' => ['create','AddTeacher']]);
        $this->middleware('permission:students-view|students-update', ['only' => ['edit']]);
        $this->middleware('permission:students-update', ['only' => ['update']]);
        $this->middleware('permission:students-users-update', ['only' => ['teacherEmailSend','userUpdate']]);
        $this->middleware('permission:students-delete', ['only' => ['destroy']]);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($schoolId = null)
    {

        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ;
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $students = $school->students;
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
            $school = School::active()->find($schoolId);
        }

        $countries = Country::active()->get();
        $levels = Level::active()->where('school_id',$schoolId)->get();
        $genders = config('global.gender');
        $provinces = config('global.provinces');
        $exStudent = $exUser = $searchEmail = null;
        if ($request->isMethod('post')){
            $searchEmail = $request->email;
            $exUser = User::where(['email'=> $searchEmail, 'person_type' =>'App\Models\Student' ])->first();
            // $exStudent = !empty($exUser) ? $exUser->personable : null;
            $exStudent = Student::where(['email'=> $searchEmail])->first();;
            $alreadyFlag =null;
            if ($exStudent) {
                $alreadyFlag = SchoolStudent::where(['school_id' => $schoolId, 'student_id' => $exStudent->id ])->first();
                if ($alreadyFlag) {
                    return back()->with('warning', __('This user already have in your school with "'.$searchEmail.'" email'));
                }
            }
            // echo "<pre>";
            // print_r($exStudent); exit;
        }

        return view('pages.students.add')->with(compact('countries','genders','exUser','exStudent','searchEmail','schoolId','levels','provinces','school'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function AddStudent(Request $request, $schoolId = null)
    {

        $user = Auth::user();
        $alldata = $request->all();

        if ($user->isSuperAdmin()) {
            $schoolId = $alldata['school_id'];
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return [
                    'status' => 1,
                    'message' =>  __('School not selected')
                ];
            }
            $schoolId = $school->id;
            $schoolName = $school->school_name;
        }else {
            $schoolId = $user->selectedSchoolId();
            $schoolName = $user->selectedSchoolName();
            $school = School::active()->find($schoolId);
            $schoolId = $school->id;
            $schoolName = $school->school_name;
        }
        DB::beginTransaction();
        try{
            $authUser = $request->user();
            if ($request->isMethod('post')){
                if (!empty($alldata['user_id'])) {
                    $relationalData = [
                        'has_user_account'=> 1 ,
                        'comment'=> $alldata['comment'],
                        'nickname'=> $alldata['nickname'],
                        'is_active'=> 0,
                    ];
                    $user = User::find($alldata['user_id']);
                    $student = $user->personable;
                    $exist = SchoolStudent::where(['school_id' => $schoolId, 'student_id' => $student->id ])->first();
                    // dd($exist);
                    if (!$exist) {
                        $student->schools()->attach($schoolId,$relationalData);
                        // notify user by email about new Teacher role
                        if (config('global.email_send') == 1) {
                            $data = [];
                            $data['email'] = $user->email;
                            $data['username'] = $data['name'] = $user->username;
                            $data['school_name'] = $schoolName;
                            $verifyUser = [
                                'school_id' => $schoolId,
                                'person_id' => $student->id,
                                'person_type' => 'App\Models\Student',
                                'token' => Str::random(10),
                                'token_type' => 'VERIFY_SIGNUP',
                                'expire_date' => Carbon::now()->addDays(config('global.token_validity'))->format("Y-m-d")
                            ];
                            $verifyUser = VerifyToken::create($verifyUser);
                            $data['token'] = $verifyUser->token;

                            if (!$this->emailSend($data,'sign_up_confirmation_email')) {
                                return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
                            }
                        }
                        $msg = 'Successfully Registered';

                    } else {
                        $msg = 'This teacher already exist with your school';
                    }




                }else {
                    $studentData = [
                        'is_active' => $alldata['is_active'],
                        'gender_id' => $alldata['gender_id'],
                        'lastname' => $alldata['lastname'],
                        'firstname' => $alldata['firstname'],
                        'birth_date' => date('Y-m-d H:i:s',strtotime($alldata['birth_date'])),
                        'street' => $alldata['street'],
                        'street_number' => $alldata['street_number'],
                        'street2' => $alldata['street2'],
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
                        // 'phone' => $alldata['phone'],
                        'father_phone' => isset($alldata['father_phone']) ? $alldata['father_phone'] : '',
                        'father_email' => isset($alldata['father_email']) ? $alldata['father_email'] : '',
                        'father_notify' => isset($alldata['father_notify']) && !empty($alldata['father_notify']) ? 1 : 0 ,
                        'mother_phone' => isset($alldata['mother_phone']) ? $alldata['mother_phone'] : '',
                        'mother_email' => isset($alldata['mother_email']) ? $alldata['mother_email'] : '',
                        'mother_notify' => isset($alldata['mother_notify']) && !empty($alldata['mother_notify']) ? 1 : 0 ,
                        'mobile' => $alldata['mobile'],
                        'email' => $alldata['email'],
                        'email2' => $alldata['email2'],
                        'student_notify' => isset($alldata['student_notify']) && !empty($alldata['student_notify']) ? 1 : 0 ,
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
                  // print_r($studentData); exit;
                    $student = Student::create($studentData);
                    $student->save();

                    $schoolStudent = [
                        'student_id' => $student->id,
                        'school_id' => $schoolId,
                        'has_user_account' => isset($alldata['has_user_account']) && !empty($alldata['has_user_account']) ? $alldata['has_user_account'] : null,
                        'nickname' => $alldata['nickname'],
                        'email' => $alldata['email'],
                        'billing_method' => $alldata['billing_method'],
                        'level_id' => isset($alldata['level_id']) && !empty($alldata['level_id']) ? $alldata['level_id'] : null ,
                        'level_date_arp' => isset($alldata['level_date_arp']) && !empty($alldata['level_date_arp']) ? date('Y-m-d H:i:s',strtotime($alldata['level_date_arp'])) : null ,
                        'licence_arp' => isset($alldata['licence_arp']) && !empty($alldata['licence_arp']) ? $alldata['licence_arp'] : null ,
                        'licence_usp' => $alldata['licence_usp'],
                        'level_skating_usp' => isset($alldata['level_skating_usp']) && !empty($alldata['level_skating_usp']) ? $alldata['level_skating_usp'] : null ,
                        'level_date_usp' => isset($alldata['level_date_usp']) && !empty($alldata['level_date_usp']) ? date('Y-m-d H:i:s',strtotime($alldata['level_date_usp'])) : null ,
                        'comment' => $alldata['comment'],
                    ];

                    $schoolStudentData = SchoolStudent::create($schoolStudent);
                    $schoolStudentData->save();

                    if (!empty($alldata['email'])) {

                        //sending activation email after successful signed up
                        if (config('global.email_send') == 1) {
                            $data = [];
                            $data['email'] = $alldata['email'];
                            $data['username'] = $data['name'] = $alldata['nickname'];
                            $data['school_name'] = $schoolName;

                            $verifyUser = [
                                'school_id' => $schoolId,
                                'person_id' => $student->id,
                                'person_type' => 'App\Models\Student',
                                'token' => Str::random(10),
                                'token_type' => 'VERIFY_SIGNUP',
                                'expire_date' => Carbon::now()->addDays(config('global.token_validity'))->format("Y-m-d")
                            ];
                            $verifyUser = VerifyToken::create($verifyUser);
                            $data['token'] = $verifyUser->token;

                            if ($this->emailSend($data,'sign_up_confirmation_email_exist')) {
                                $msg = __('We sent you an activation link. Check your email and click on the link to verify.');
                            }  else {
                                return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
                            }
                        } else {
                            $usersData = [
                                'person_id' => $student->id,
                                'person_type' =>'App\Models\Student',
                                'username' =>Str::random(10),
                                'lastname' => $alldata['lastname'],
                                'middlename'=>'',
                                'firstname'=>$alldata['firstname'],
                                'email'=>$alldata['email'],
                                // 'password'=>$alldata['password'],
                                'password'=>Str::random(10),
                                'is_mail_sent'=>0,
                                'is_active'=>1,
                                'is_firstlogin'=>0
                            ];
                            $user = User::create($usersData);
                            $user->save();
                        }
                    }
                }
            }
            DB::commit();
            return back()->withInput($request->all())->with('success', __('Student added successfully!'));
        }catch (Exception $e) {
            // dd($e);
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

        $authUser = $user = Auth::user();
        // $authUser = $request->user();
        $alldata = $request->all();

        if ($user->isSuperAdmin()) {
            $schoolId = $alldata['school_id'];
        }else {
            $schoolId = $user->selectedSchoolId();
        }

        DB::beginTransaction();
        try{
            if ($request->isMethod('post')){
                $studentData = [
                    'is_active' => $alldata['is_active'],
                    'gender_id' => $alldata['gender_id'],
                    'lastname' => $alldata['lastname'],
                    'firstname' => $alldata['firstname'],
                    'birth_date' => date('Y-m-d H:i:s',strtotime($alldata['birth_date'])),
                    'street' => $alldata['street'],
                    'street_number' => $alldata['street_number'],
                    'street2' => $alldata['street2'],
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
                    'father_phone' => $alldata['father_phone'],
                    'father_email' => $alldata['father_email'],
                    'father_notify' => isset($alldata['father_notify']) && !empty($alldata['father_notify']) ? 1 : 0 ,
                    'mother_phone' => $alldata['mother_phone'],
                    'mother_email' => $alldata['mother_email'],
                    'mother_notify' => isset($alldata['mother_notify']) && !empty($alldata['mother_notify']) ? 1 : 0 ,
                    'mobile' => $alldata['mobile'],
                    'email' => $alldata['email'],
                    'email2' => $alldata['email2'],
                    'student_notify' => isset($alldata['student_notify']) && !empty($alldata['student_notify']) ? 1 : 0 ,
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
                $user = User::where(['person_id'=>$student->id])->first();

                $exist = SchoolStudent::where(['student_id'=>$student->id, 'school_id'=>$alldata['school_id']])->first();
                if (!empty($alldata['email'])) {
                    if (!$user) {
                        $usersData = [
                            'person_id' => $student->id,
                            'person_type' =>'App\Models\Student',
                            'username' =>$alldata['nickname'],
                            'lastname' => $alldata['lastname'],
                            'middlename'=>'',
                            'firstname'=>$alldata['firstname'],
                            'email'=>$alldata['email'],
                            // 'password'=>$alldata['password'],
                            'password'=>Str::random(10),
                            'is_mail_sent'=>0,
                            'is_active'=>1,
                            'is_firstlogin'=>0
                        ];
                        $user = User::create($usersData);
                        $user->save();
                        // notify user by email about new Teacher role
                        if (config('global.email_send') == 1) {
                            $data = [];
                            $data['email'] = $user->email;
                            $data['username'] = $data['name'] = $user->username;

                            $verifyUser = [
                                'school_id' => $alldata['school_id'],
                                'person_id' => $student->id,
                                'person_type' => 'App\Models\Student',
                                'token' => Str::random(10),
                                'token_type' => 'VERIFY_SIGNUP',
                                'expire_date' => Carbon::now()->addDays(config('global.token_validity'))->format("Y-m-d")
                            ];
                            $verifyUser = VerifyToken::create($verifyUser);
                            $data['token'] = $verifyUser->token;

                            if (!$this->emailSend($data,'sign_up_confirmation_email')) {
                                return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
                            }
                        }
                    }else {
                        if ($exist->email != $alldata['email']) {
                            // notify user by email about new Teacher role
                            if (config('global.email_send') == 1) {
                                $data = [];
                                $data['email'] = $alldata['email'];
                                $user->update($data);
                                $data['username'] = $data['name'] = $user->username;

                                $verifyUser = [
                                    'school_id' => $alldata['school_id'],
                                    'person_id' => $student->id,
                                    'person_type' => 'App\Models\Student',
                                    'token' => Str::random(10),
                                    'token_type' => 'VERIFY_SIGNUP',
                                    'expire_date' => Carbon::now()->addDays(config('global.token_validity'))->format("Y-m-d")
                                ];
                                $verifyUser = VerifyToken::create($verifyUser);
                                $data['token'] = $verifyUser->token;

                                if (!$this->emailSend($data,'sign_up_confirmation_email')) {
                                    return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
                                }
                            }
                        }
                    }
                }
                Student::where('id', $student->id)->update($studentData);

                $schoolStudent = [
                    'student_id' => $student->id,
                    'school_id' => $schoolId,
                    'has_user_account' => !empty($alldata['has_user_account']) ? $alldata['has_user_account'] : null,
                    'nickname' => $alldata['nickname'],
                    'email' => $alldata['email'],
                    'billing_method' => $alldata['billing_method'],
                    'level_id' => $alldata['level_id'],
                    'level_date_arp' => isset($alldata['level_date_arp']) && !empty($alldata['level_date_arp']) ? date('Y-m-d H:i:s',strtotime($alldata['level_date_arp'])) : null ,
                    'licence_arp' => isset($alldata['licence_arp']) && !empty($alldata['licence_arp']) ? $alldata['licence_arp'] : null ,
                    'licence_usp' => $alldata['licence_usp'],
                    'level_skating_usp' => isset($alldata['level_skating_usp']) && !empty($alldata['level_skating_usp']) ? $alldata['level_skating_usp'] : null ,
                    'level_date_usp' => isset($alldata['level_date_usp']) && !empty($alldata['level_date_usp']) ? date('Y-m-d H:i:s',strtotime($alldata['level_date_usp'])) : null ,
                    'comment' => $alldata['comment'],
                ];

                SchoolStudent::where(['student_id'=>$student->id, 'school_id'=>$alldata['school_id']])->update($schoolStudent);
            }
            DB::commit();
            return back()->withInput($request->all())->with('success', __('Student updated successfully!'));
        }catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
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
        $provinces = config('global.provinces');

        $student = Student::find($studentId);

        if ($user->isSuperAdmin()) {
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return redirect()->route('schools')->with('error', __('School is not selected'));
            }
            // $schoolId = $school->id;
            // $schoolName = $school->school_name;
        }else {
            $school = $user->getSelectedSchoolAttribute();
            // $schoolId = $user->selectedSchoolId();
            // $schoolName = $user->selectedSchoolName();
        }

        $schoolId = $school->id;
        $schoolName = $school->school_name;

        $relationalData = SchoolStudent::where([
            ['student_id',$studentId],
            ['school_id',$schoolId]
        ])->first();
        //dd($relationalData);

        $lanCode = 'en';
        if (Session::has('locale')) {
            $lanCode = Session::get('locale');
        }
        $emailTemplate = EmailTemplate::where([
            ['template_code', 'student'],
            ['language', $lanCode]
        ])->first();

        if ($emailTemplate) {
            $http_host=$this->BASE_URL."/";
            if (!empty($emailTemplate->body_text)) {
                $emailTemplate->body_text = str_replace("[~~ HOSTNAME ~~]",$http_host,$emailTemplate->body_text);
                $emailTemplate->body_text = str_replace("[~~HOSTNAME~~]",$http_host,$emailTemplate->body_text);
            }
        }

        $profile_image = !empty($student->profile_image_id) ? AttachedFile::find($student->profile_image_id) : null ;
        $countries = Country::active()->get();
        $levels = Level::active()->where('school_id',$schoolId)->get();
        $genders = config('global.gender');

        return view('pages.students.edit')->with(compact('emailTemplate','countries','genders','student','relationalData','profile_image','schoolId','levels','schoolName','provinces','school'));
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
    public function destroy(Request $request)
    {
        $schoolId = $request->route('school');
        $studentId = $request->route('student');
        SchoolStudent::where(['school_id'=>$schoolId, 'student_id'=>$studentId])->delete();
        return redirect()->back()
            ->with('success', 'Deleted successfully');
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



      /**
     * Update the student user account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  School $school
     * @return \Illuminate\Http\Response
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-28
    */
    public function userUpdate(Request $request, User $user)
    {
        $params = $request->all();
        try{
            $request->merge([
                'email'=> $params['admin_email'],
                'is_active'=> $params['admin_is_active']
            ]);


            $user = User::find($params['user_id']);
            if ($user) {
                $request->merge(['username'=> $params['admin_username']]);
                $user->update($request->except(['_token']));

                if (!empty($params['admin_password'])) {
                    $user->password = $params['admin_password'];
                    $user->save();
                }
            } else{
                return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
            }

            return back()->withInput($request->all())->with('success', __('Student account updated successfully!'));
        } catch (\Exception $e) {
            //return error message
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
    }


    /**
     *  AJAX action to send email to school admin
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-10
     */
    public function studentEmailSend(Request $request)
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
                if (config('global.email_send') == 1) {
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
                        if ($this->emailSendWithoutTemplate($data,$user->email)) {
                            $result = array(
                                'status' => true,
                                'message' => __('We sent an email.'),
                            );
                        }  else {
                            return $result = array(
                                "status"     => false,
                                'message' =>  __('Internal server error')
                            );
                        }
                        return response()->json($result);
                    } catch (\Exception $e) {
                        $result = array(
                            'status' => true,
                            'message' => __('We sent an email.'),
                        );
                        return response()->json($result);
                    }
                } else{
                    $result = array('status'=>true,'msg'=>__('We sent an email.'));
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit(Request $request, $schoolId = null, Teacher $teacher)
    public function self_edit(Request $request)
    {
        $user = Auth::user();
        $schoolId = $request->route('school');

        $student = Student::find($user->person_id);
        $schoolId = $user->selectedSchoolId();
        $schoolName = $user->selectedSchoolName();
        $school = School::active()->find($schoolId);

        $provinces = config('global.provinces');
        $countries = Country::active()->get();
        $genders = config('global.gender');


        $relationalData = SchoolStudent::where([
            ['student_id',$student->id],
            ['school_id',$schoolId]
        ])->first();
        $lanCode = 'en';
        if (Session::has('locale')) {
            $lanCode = Session::get('locale');
        }
        $emailTemplate = EmailTemplate::where([
            ['template_code', 'student'],
            ['language', $lanCode]
        ])->first();
        if ($emailTemplate) {
            $http_host=$this->BASE_URL."/";
            if (!empty($emailTemplate->body_text)) {
                $emailTemplate->body_text = str_replace("[~~ HOSTNAME ~~]",$http_host,$emailTemplate->body_text);
                $emailTemplate->body_text = str_replace("[~~HOSTNAME~~]",$http_host,$emailTemplate->body_text);
            }
        }
        $profile_image = !empty($student->profile_image_id) ? AttachedFile::find($student->profile_image_id) : null ;
        $countries = Country::active()->get();
        $levels = Level::active()->where('school_id',$schoolId)->get();
        $genders = config('global.gender');

        // dd($relationalData);
        return view('pages.students.self_edit')->with(compact('levels',
        'emailTemplate','countries','genders','student','relationalData','profile_image','schoolId','schoolName','provinces','school'));
    }


     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function self_update(Request $request, Student $student)
    {
        $user = Auth::user();
        $alldata = $request->all();

        $student = Student::find($user->person_id);
        $schoolId = $user->selectedSchoolId();

        DB::beginTransaction();
        try{
            $birthDate=date('Y-m-d H:i:s',strtotime($alldata['birth_date']));
            $studentData = [
                'is_active' => $alldata['is_active'],
                    'gender_id' => $alldata['gender_id'],
                    'lastname' => $alldata['lastname'],
                    'firstname' => $alldata['firstname'],
                    'birth_date' => date('Y-m-d H:i:s',strtotime($alldata['birth_date'])),
                    'street' => $alldata['street'],
                    'street_number' => $alldata['street_number'],
                    'street2' => $alldata['street2'],
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
                    'father_phone' => $alldata['father_phone'],
                    'father_email' => $alldata['father_email'],
                    'father_notify' => isset($alldata['father_notify']) && !empty($alldata['father_notify']) ? 1 : 0 ,
                    'mother_phone' => $alldata['mother_phone'],
                    'mother_email' => $alldata['mother_email'],
                    'mother_notify' => isset($alldata['mother_notify']) && !empty($alldata['mother_notify']) ? 1 : 0 ,
                    'mobile' => $alldata['mobile'],
                    'email' => $alldata['email'],
                    'email2' => $alldata['email2'],
                    'student_notify' => isset($alldata['student_notify']) && !empty($alldata['student_notify']) ? 1 : 0 ,
            ];
            if($request->file('profile_image_file'))
            {
                $image = $request->file('profile_image_file');
                $mime_type = $image->getMimeType();
                $extension = $image->getClientOriginalExtension();
                if($image->getSize()>0)
                {
                    list($path, $imageNewName) = $this->__processImg($image,'StudentImage',$user);

                    if (!empty($path)) {
                        $fileData = [
                            'visibility' => 1,
                            'file_type' =>'image',
                            'title' => $user->username,
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
            $relationalData = [
                'nickname'=> $alldata['nickname'],
            ];
            $exist = SchoolStudent::where(['student_id'=>$student->id, 'school_id'=>$schoolId])->first();
            if (!empty($alldata['email'])) {
                if ($exist->email != $alldata['email']) {
                    // notify user by email about new Teacher role
                    if (config('global.email_send') == 1) {
                        $data = [];
                        $data['email'] = $alldata['email'];
                        $user->update($data);
                        $data['username'] = $data['name'] = $user->username;

                        $verifyUser = [
                            'school_id' => $alldata['school_id'],
                            'person_id' => $student->id,
                            'person_type' => 'App\Models\Student',
                            'token' => Str::random(10),
                            'token_type' => 'VERIFY_SIGNUP',
                            'expire_date' => Carbon::now()->addDays(config('global.token_validity'))->format("Y-m-d")
                        ];
                        $verifyUser = VerifyToken::create($verifyUser);
                        $data['token'] = $verifyUser->token;

                        if (!$this->emailSend($data,'sign_up_confirmation_email')) {
                            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
                        }
                    }
                }
            }
            Student::where('id', $student->id)->update($studentData);
            $schoolStudent = [
                'student_id' => $student->id,
                'school_id' => $schoolId,
                'has_user_account' => !empty($alldata['has_user_account']) ? $alldata['has_user_account'] : null,
                'nickname' => $alldata['nickname'],
                'email' => $alldata['email'],
                'billing_method' => $alldata['billing_method'],
                // 'level_id' => $alldata['level_id'],
                'level_date_arp' => isset($alldata['level_date_arp']) && !empty($alldata['level_date_arp']) ? date('Y-m-d H:i:s',strtotime($alldata['level_date_arp'])) : null ,
                'licence_arp' => isset($alldata['licence_arp']) && !empty($alldata['licence_arp']) ? $alldata['licence_arp'] : null ,
                'licence_usp' => $alldata['licence_usp'],
                'level_skating_usp' => isset($alldata['level_skating_usp']) && !empty($alldata['level_skating_usp']) ? $alldata['level_skating_usp'] : null ,
                'level_date_usp' => isset($alldata['level_date_usp']) && !empty($alldata['level_date_usp']) ? date('Y-m-d H:i:s',strtotime($alldata['level_date_usp'])) : null ,
                'comment' => $alldata['comment'],
            ];

            SchoolStudent::where(['student_id'=>$student->id, 'school_id'=>$alldata['school_id']])->update($schoolStudent);
            DB::commit();
            return back()->withInput($request->all())->with('success', __('Student updated successfully!'));
        }catch (\Exception $e) {
            DB::rollBack();
            //return error message
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
    }
}
