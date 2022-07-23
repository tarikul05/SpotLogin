<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\EventCategory;
use App\Models\Teacher;
use App\Models\School;
use App\Models\User;
use App\Models\Country;
use App\Models\EmailTemplate;
use App\Models\LessonPrice;
use App\Models\LessonPriceTeacher;
use App\Models\SchoolTeacher;
use App\Models\VerifyToken;
use App\Models\Location;
use App\Models\Level;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use App\Http\Requests\ProfilePhotoUpdateRequest;
use Illuminate\Support\Facades\URL;
use App\Models\AttachedFile;
use Illuminate\Support\Facades\Storage;
use App\Mail\SportloginEmail;



class TeachersController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:teachers-list|teachers-create|teachers-update|teachers-view|teachers-users-update|teachers-delete', ['only' => ['index']]);
        $this->middleware('permission:teachers-create', ['only' => ['create','AddTeacher']]);
        $this->middleware('permission:teachers-view|teachers-update', ['only' => ['edit']]);
        $this->middleware('permission:teachers-update', ['only' => ['update']]);
        $this->middleware('permission:teachers-users-update', ['only' => ['teacherEmailSend','userUpdate']]);
        $this->middleware('permission:teachers-delete', ['only' => ['destroy']]);


    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($schoolId = null)
    {

        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();

        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $teachers = $school->teachers;

        return view('pages.teachers.list',compact('teachers','schoolId'));
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
        $genders = config('global.gender');
        $provinces = config('global.provinces');
        $exTeacher = $exUser = $searchEmail = null;
        if ($request->isMethod('post')){
            $searchEmail = $request->email;
            $exUser = User::where(['email'=> $searchEmail, 'person_type' =>'App\Models\Teacher' ])->first();
            $exTeacher = !empty($exUser) ? $exUser->personable : null;
        }

        $eventCategory = EventCategory::schoolInvoiced()->where('school_id',$schoolId)->get();
        $lessonPrices = LessonPrice::active()->orderBy('divider')->get();

        return view('pages.teachers.add')->with(compact('countries','genders','exTeacher','exUser','searchEmail','schoolId','eventCategory','lessonPrices','provinces','school'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function AddTeacher(Request $request, $schoolId = null)
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
            if ($request->isMethod('post')){
                $alldata = $request->all();
                if (!empty($alldata['user_id'])) {
                    
                    $user = User::find($alldata['user_id']);
                    $teacher = $user->personable;
                    $exist = SchoolTeacher::where(['school_id' => $schoolId, 'teacher_id' => $teacher->id ])->first();
                    // dd($exist);
                    if (!$exist) {
                        $relationalData = [
                            'role_type'=>$alldata['role_type'],
                            'has_user_account'=> 1 ,
                            'comment' => isset($alldata['comment']) ? $alldata['comment'] : '',
                            'nickname'=> $alldata['nickname'],
                            // 'is_active'=> 0,
                            'is_teacher'=> 1,
                            'bg_color_agenda'=> $alldata['bg_color_agenda'],
                        ];
                        $teacher->schools()->attach($schoolId,$relationalData);
                        // notify user by email about new Teacher role
                        if (config('global.email_send') == 1) {
                            $data = [];
                            $data['email'] = $user->email;
                            $data['username'] = $data['name'] = $user->username;

                            $verifyUser = [
                                'school_id' => $schoolId,
                                'person_id' => $teacher->id,
                                'person_type' => 'App\Models\Teacher',
                                'token' => Str::random(10),
                                'token_type' => 'VERIFY_SIGNUP',
                                'expire_date' => Carbon::now()->addDays(config('global.token_validity'))->format("Y-m-d")
                            ];
                            $verifyUser = VerifyToken::create($verifyUser);
                            $data['token'] = $verifyUser->token;
                            $data['school_name'] = $schoolName;
                            $data['url'] = route('add.verify.email',$data['token']); 
                            if (!$this->emailSend($data,'sign_up_confirmation_email_exist')) {
                                return $result = array(
                                    "status"     => 0,
                                    'message' =>  __('Internal server error')
                                );
                            }
                        }
                        $msg = 'Successfully Registered';

                    }else {
                        $msg = 'This teacher already exist with your school';
                    }




                }else{
                    $birthDate=date('Y-m-d H:i:s',strtotime($alldata['birth_date']));
                    $teacherData = [
                        // 'availability_select' => $alldata['availability_select'],
                        'gender_id' => $alldata['gender_id'],
                        'lastname' => $alldata['lastname'],
                        'firstname' => $alldata['firstname'],
                        'birth_date' => $birthDate,
                        'licence_js' => $alldata['licence_js'],
                        'email' => $alldata['email'],
                        'street' => $alldata['street'],
                        'street_number' => $alldata['street_number'],
                        'zip_code' => $alldata['zip_code'],
                        'place' => $alldata['place'],
                        'country_code' => $alldata['country_code'],
                        'province_id' => $alldata['province_id'],
                        'phone' => $alldata['phone'],
                        'mobile' => $alldata['mobile'],
                    ];
                    $teacher = Teacher::create($teacherData);
                    // $schoolTeacherData =SchoolStudent::where(['teacher_id'=>$teacher->id, 'school_id'=>$schoolId])->first();
                
                    $relationalData = [
                        'role_type'=>$alldata['role_type'],
                        'has_user_account'=> isset($alldata['has_user_account'])? $alldata['has_user_account'] : null ,
                        'comment' => isset($alldata['comment']) ? $alldata['comment'] : '',
                        'nickname'=> $alldata['nickname'],
                        'is_teacher'=> 1,
                        'bg_color_agenda'=> $alldata['bg_color_agenda'],
                    ];
                    $teacher->save();
                    $teacher->schools()->attach($schoolId,$relationalData);


                    //sending activation email after successful signed up
                    if (config('global.email_send') == 1) {
                        $data = [];
                        $data['email'] = $alldata['email'];
                        $data['username'] = $data['name'] = $alldata['firstname'];
                        $data['school_name'] = $schoolName;

                        $verifyUser = [
                            'school_id' => $schoolId,
                            'person_id' => $teacher->id,
                            'person_type' => 'App\Models\Teacher',
                            'token' => Str::random(10),
                            'token_type' => 'VERIFY_SIGNUP',
                            'expire_date' => Carbon::now()->addDays(config('global.token_validity'))->format("Y-m-d")
                        ];
                        $verifyUser = VerifyToken::create($verifyUser);
                        $data['token'] = $verifyUser->token;
                        $data['url'] = route('add.verify.email',$data['token']); 

                        if ($this->emailSend($data,'sign_up_confirmation_email')) {
                            $msg = __('We sent you an activation link. Check your email and click on the link to verify.');
                        }  else {
                            return $result = array(
                                "status"     => 0,
                                'message' =>  __('Internal server error')
                            );
                        }
                    } else {
                        $usersData = [
                            'person_id' => $teacher->id,
                            'person_type' =>'App\Models\Teacher',
                            'username' =>Str::random(10),
                            'lastname' => $alldata['lastname'],
                            'middlename'=>'',
                            'firstname'=>$alldata['firstname'],
                            'email'=>$alldata['email'],
                            // 'password'=>$alldata['password'],
                            'password'=>Str::random(10),
                            'is_mail_sent'=>0,
                            'is_active' => isset($alldata['availability_select']) ? $alldata['availability_select'] : $teacher->availability_select,
                            'is_firstlogin'=>0
                        ];
                        $user = User::create($usersData);
                        $user->save();
                    }
                    $msg = 'Successfully Registered';
                }


                // foreach ($alldata['data'] as $key => $catPrices) {
                //    foreach ($catPrices as $pkey => $price) {
                //      $dataprice = [
                //           'event_category_id' => $key,
                //           'teacher_id' => $teacher->id,
                //           'lesson_price_student' => $price['lesson_price_student'],
                //           'lesson_price_id' => $price['lesson_price_id'],
                //           // 'price_buy' => $price['price_buy'],
                //           'price_buy' => $price['price_sell'],
                //           'price_sell' => $price['price_sell'],
                //       ];

                //      if (empty($price['id'])) {
                //         $updatedPrice = LessonPriceTeacher::create($dataprice);
                //      }else{
                //         $updatedPrice = LessonPriceTeacher::where('id', $price['id'])->update($dataprice);
                //      }
                //    }
                //  }


                $result = array(
                    "status"     => 1,
                    'message' => __($msg)
                );
            }
            DB::commit();
        }catch (Exception $e) {
            DB::rollBack();
            $result= [
                'status' => 0,
                'message' =>  __('Internal server error')
            ];
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
    // public function edit(Request $request, $schoolId = null, Teacher $teacher)
    public function edit(Request $request)
    {
        $user = Auth::user();
        $schoolId = $request->route('school');
        $teacherId = $request->route('teacher');
        $provinces = config('global.provinces');
        $teacher = Teacher::find($teacherId);

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
            $school = School::active()->find($schoolId);
        }


        $relationalData = SchoolTeacher::where([
            ['teacher_id',$teacher->id],
            ['school_id',$schoolId]
        ])->first();
        $lanCode = 'en';
        if (Session::has('locale')) {
            $lanCode = Session::get('locale');
        }
        $emailTemplate = EmailTemplate::where([
            ['template_code', 'teacher'],
            ['language', $lanCode]
        ])->first();
        if ($emailTemplate) {
            $http_host=$this->BASE_URL."/";
            if (!empty($emailTemplate->body_text)) {
                $emailTemplate->body_text = str_replace("[~~ HOSTNAME ~~]",$http_host,$emailTemplate->body_text);
                $emailTemplate->body_text = str_replace("[~~HOSTNAME~~]",$http_host,$emailTemplate->body_text);
            }
        }

        $eventCategory = EventCategory::schoolInvoiced()->where('school_id',$schoolId)->get();
        $lessonPrices = LessonPrice::active()->orderBy('divider')->get();
        $lessonPriceTeachers = LessonPriceTeacher::active()
                              ->where(['teacher_id' => $teacher->id])
                              ->whereIn('event_category_id',$eventCategory->pluck('id'))
                              ->get();
        $ltprice =[];
        foreach ($lessonPriceTeachers as $lkey => $lpt) {
          $ltprice[$lpt->event_category_id][$lpt->lesson_price_student] = $lpt->toArray();
        }
        // dd($lessionPriceTeacher);

        $countries = Country::active()->get();
        $genders = config('global.gender');
        // dd($relationalData);

        return view('pages.teachers.edit')->with(compact('teacher','emailTemplate','relationalData','countries','genders','schoolId','schoolName','eventCategory','lessonPrices','ltprice','provinces','school'));
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
        $user = Auth::user();
        $alldata = $request->all();

        if ($user->isSuperAdmin()) {
            $schoolId = $alldata['school_id'];
        }else {
            $schoolId = $user->selectedSchoolId();
        }


        // dd($schoolId);
        DB::beginTransaction();
        try{
            $birthDate=date('Y-m-d H:i:s',strtotime($alldata['birth_date']));
            $teacherData = [
                // 'availability_select' => isset($alldata['availability_select']) ? $alldata['availability_select'] : $teacher->availability_select,
                'gender_id' => $alldata['gender_id'],
                'lastname' => $alldata['lastname'],
                'firstname' => $alldata['firstname'],
                'birth_date' => $birthDate,
                'licence_js' => $alldata['licence_js'],
                'email' => $alldata['email'],
                'street' => $alldata['street'],
                'street_number' => $alldata['street_number'],
                'zip_code' => $alldata['zip_code'],
                'place' => $alldata['place'],
                'country_code' => $alldata['country_code'],
                'province_id' => $alldata['province_id'],
                'phone' => $alldata['phone'],
                'mobile' => $alldata['mobile'],
            ];
            Teacher::where('id', $teacher->id)->update($teacherData);
            $schoolTeacherData =SchoolTeacher::where(['teacher_id'=>$teacher->id, 'school_id'=>$schoolId])->first();
                
            $relationalData = [
                'role_type'=>$alldata['role_type'],
                // 'has_user_account'=> isset($alldata['has_user_account'])? $alldata['has_user_account'] : null ,
                'comment' => isset($alldata['comment']) ? $alldata['comment'] : $schoolTeacherData->comment,
                'nickname'=> $alldata['nickname'],
                'bg_color_agenda'=> $alldata['bg_color_agenda'],
                'is_active' => isset($alldata['is_active']) ? $alldata['is_active'] : $schoolTeacherData->is_active,
            ];
            SchoolTeacher::where(['teacher_id'=>$teacher->id, 'school_id'=>$schoolId])->update($relationalData);
            DB::commit();
            return back()->withInput($request->all())->with('success', __('Teacher updated successfully!'));
        }catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            //return error message
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
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
        $teacherId = $request->route('teacher');

        $teacher = Teacher::find($user->person_id);
        $schoolId = $user->selectedSchoolId();
        $schoolName = $user->selectedSchoolName();


        $relationalData = SchoolTeacher::where([
            ['teacher_id',$teacher->id],
            ['school_id',$schoolId]
        ])->first();
        $lanCode = 'en';
        if (Session::has('locale')) {
            $lanCode = Session::get('locale');
        }

        $eventCategory = EventCategory::teacherInvoiced()->where('school_id',$schoolId)->get();
        $lessonPrices = LessonPrice::active()->orderBy('divider')->get();
        $lessonPriceTeachers = LessonPriceTeacher::active()
                              ->where(['teacher_id' => $teacher->id])
                              ->whereIn('event_category_id',$eventCategory->pluck('id'))
                              ->get();
        $ltprice =[];
        foreach ($lessonPriceTeachers as $lkey => $lpt) {
          $ltprice[$lpt->event_category_id][$lpt->lesson_price_student] = $lpt->toArray();
        }
        // dd($lessionPriceTeacher);

        $countries = Country::active()->get();
        $genders = config('global.gender');

        $eventCat = EventCategory::active()->where('school_id', $schoolId)->get();
        $eventLastCatId = DB::table('event_categories')->orderBy('id','desc')->first();
        $locations = Location::active()->where('school_id', $schoolId)->get();
        $eventLastLocaId = DB::table('locations')->orderBy('id','desc')->first();
        $levels = Level::active()->where('school_id', $schoolId)->get();
        $eventLastLevelId = DB::table('levels')->orderBy('id','desc')->first();


        // dd($relationalData);
        return view('pages.teachers.self_edit')->with(compact('levels',
        'eventLastLevelId',
        'locations',
        'eventLastLocaId',
        'eventCat',
        'eventLastCatId','teacher','relationalData','countries','genders','schoolId','schoolName','eventCategory','lessonPrices','ltprice'));
    }


     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function self_update(Request $request, Teacher $teacher)
    {
        $user = Auth::user();
        $alldata = $request->all();

        $teacher = Teacher::find($user->person_id);
        $schoolId = $user->selectedSchoolId();

        DB::beginTransaction();
        try{
            $birthDate=date('Y-m-d H:i:s',strtotime($alldata['birth_date']));
            $teacherData = [
                'gender_id' => $alldata['gender_id'],
                'lastname' => $alldata['lastname'],
                'firstname' => $alldata['firstname'],
                'birth_date' => $birthDate,
                'licence_js' => $alldata['licence_js'],
                'email' => $alldata['email'],
                'street' => $alldata['street'],
                'street_number' => $alldata['street_number'],
                'zip_code' => $alldata['zip_code'],
                'place' => $alldata['place'],
                'country_code' => $alldata['country_code'],
                'phone' => $alldata['phone'],
                'mobile' => $alldata['mobile'],
            ];
            Teacher::where('id', $teacher->id)->update($teacherData);
            $schoolTeacherData =SchoolTeacher::where(['teacher_id'=>$teacher->id, 'school_id'=>$schoolId])->first();
            $relationalData = [
                // 'role_type'=>$alldata['role_type'],
                // 'has_user_account'=> isset($alldata['has_user_account'])? $alldata['has_user_account'] : null ,
                // 'comment' => isset($alldata['comment']) ? $alldata['comment'] : $schoolTeacherData->comment,
                'nickname'=> $alldata['nickname'],
                // 'bg_color_agenda'=> $alldata['bg_color_agenda'],
            ];
            SchoolTeacher::where(['teacher_id'=>$teacher->id, 'school_id'=>$schoolId])->update($relationalData);
            DB::commit();
            return back()->withInput($request->all())->with('success', __('Teacher updated successfully!'));
        }catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            //return error message
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
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
        $teacherId = $request->route('teacher');
        SchoolTeacher::where(['school_id'=>$schoolId, 'teacher_id'=>$teacherId])->delete();
        return redirect()->back()
            ->with('success', 'Deleted successfully');
    }

    /**
     * Check users .
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function priceUpdate(Request $request, Teacher $teacher)
    {
      $alldata = $request->all();

      // dd($alldata);
      DB::beginTransaction();
        try{
             foreach ($alldata['data'] as $key => $catPrices) {
              // dd($catPrices);
               foreach ($catPrices as $pkey => $price) {
                 // dd($price);
                 $dataprice = [
                      'event_category_id' => $key,
                      'teacher_id' => $teacher->id,
                      'lesson_price_student' => $price['lesson_price_student'],
                      'lesson_price_id' => $price['lesson_price_id'],
                      'price_buy' => $price['price_buy'],
                      'price_sell' => $price['price_sell'],
                  ];

                 if (empty($price['id'])) {
                    $updatedPrice = LessonPriceTeacher::create($dataprice);
                 }else{
                    $updatedPrice = LessonPriceTeacher::where('id', $price['id'])->update($dataprice);
                 }
               }
             }
            DB::commit();
            return back()->withInput($request->all())->with('success', __('Teacher Lesson Price updated successfully!'));
        }catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            //return error message
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }


    }


    /**
     * Check users .
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function selfPriceUpdate(Request $request)
    {
        $user = Auth::user();
        $alldata = $request->all();

        $teacher = Teacher::find($user->person_id);

      // dd($alldata);
      DB::beginTransaction();
        try{
             foreach ($alldata['data'] as $key => $catPrices) {
              // dd($catPrices);
               foreach ($catPrices as $pkey => $price) {
                 // dd($price);
                 $dataprice = [
                      'event_category_id' => $key,
                      'teacher_id' => $teacher->id,
                      'lesson_price_student' => $price['lesson_price_student'],
                      'lesson_price_id' => $price['lesson_price_id'],
                      'price_buy' => $price['price_sell'],
                      'price_sell' => $price['price_sell'],
                  ];

                 if (empty($price['id'])) {
                    $updatedPrice = LessonPriceTeacher::create($dataprice);
                 }else{
                    $updatedPrice = LessonPriceTeacher::where('id', $price['id'])->update($dataprice);
                 }
               }
             }
            DB::commit();
            return back()->withInput($request->all())->with('success', __('Lesson Price updated successfully!'));
        }catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
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
    public function teacherEmailSend(Request $request)
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
     * Update the school admin account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  School $school
     * @return \Illuminate\Http\Response
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-10
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

            return back()->withInput($request->all())->with('success', __('Teacher account updated successfully!'));
        } catch (\Exception $e) {
            //return error message
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
    }


    /**
    * AJAX Action Update the specified resource in storage.
    *
    * @param  \App\Http\Requests\ProfilePhotoUpdateRequest  $request
    * @return \Illuminate\Http\Response
    */
    public function profilePhotoUpdate(ProfilePhotoUpdateRequest $request)
    {
        $data = $request->all();
        $result = array(
          'status' => 0,
          "file_id" => '0',
          "image_file" => '',
          'message' => __('failed to change image'),
        );

        try{
            $user = User::find($data['user_id']);
            if($request->file('profile_image_file'))
            {

                $image = $request->file('profile_image_file');
                $mime_type = $image->getMimeType();
                $extension = $image->getClientOriginalExtension();
                if($image->getSize()>0)
                {
                  list($path, $imageNewName) = $this->__processImg($image,'UserImage',$user);

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

                    $data['profile_image_id'] = $attachedImage->id;

                  }
                }
            }

            if ($user->update($data)) {
                $result = array(
                  "status"     => 1,
                  "file_id" => $user->profile_image_id,
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
     * Update teacher discount prec
     *
     * @param  \Illuminate\Http\Request  $request
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-06-08
    */
    public function updateDiscountPerc(Request $request, User $user)
    {
        $authUser = $request->user();
        $data = $request->all();
        $user = User::find($data['user_id']);
        $result = array(
          'status' => 'failed',
          'message' => __('failed to remove image'),
        );
        try{

            $p_disc1 = trim($data['p_disc1']);
            $p_person_id = trim($data['p_person_id']);
            if  ($p_disc1 == '') {
                 $p_disc1=0;
            }

            $teacherData = [
                'tax_perc' => $p_disc1,
            ];
            Teacher::where('id', $p_person_id)->update($teacherData);

            $result = array(
                "status"     => 'success',
                'message' => __('Successfully Changed Profile image')
            );
        }
        catch (\Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
        }
        return response()->json($result);
    }
}
