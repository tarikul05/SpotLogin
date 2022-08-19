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
use App\Models\Province;

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
        $provinces = Province::active()->get()->toArray();
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
                    $birthDate=date('Y-m-d H:i:s',strtotime($this->sdateFormat($alldata['birth_date'])));
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
        $provinces = Province::active()->get()->toArray();
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
            $birthDate=date('Y-m-d H:i:s',strtotime($this->sdateFormat($alldata['birth_date'])));
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
            // $birthDate=date('Y-m-d H:i:s',strtotime($this->sdateFormat($alldata['birth_date'])));
            $teacherData = [
                // 'gender_id' => $alldata['gender_id'],
                'lastname' => $alldata['lastname'],
                'firstname' => $alldata['firstname'],
                // 'birth_date' => $birthDate,
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
            // $schoolTeacherData =SchoolTeacher::where(['teacher_id'=>$teacher->id, 'school_id'=>$schoolId])->first();
            $relationalData = [
                // 'role_type'=>$alldata['role_type'],
                // 'has_user_account'=> isset($alldata['has_user_account'])? $alldata['has_user_account'] : null ,
                // 'comment' => isset($alldata['comment']) ? $alldata['comment'] : $schoolTeacherData->comment,
                // 'nickname'=> $alldata['nickname'],
                // 'bg_color_agenda'=> $alldata['bg_color_agenda'],
            ];
            // SchoolTeacher::where(['teacher_id'=>$teacher->id, 'school_id'=>$schoolId])->update($relationalData);
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
     * change status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $schoolId = $request->route('school');
        $teacherId = $request->route('teacher');
        $alldata = $request->all();
        $status = isset($alldata['status']) && ($alldata['status'] == 1 ) ? 0 : 1 ;
        // dd($schoolId,$teacherId);
        SchoolTeacher::where(['school_id'=>$schoolId, 'teacher_id'=>$teacherId])->update(['is_active'=>$status]);
        return redirect()->back()
            ->with('success', 'status updated successfully');
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

    /**
     * export teacher school wise
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function export($schoolId = null, Request $request, Teacher $teacher)
    {
        $filename = date('Ymd_His') . '.csv';
        header('Content-Encoding: UTF-8');
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $header = "ID,Email,username,Family Name,Firstname,Nickname,Gender,Licence,role_type,background color,Comment,Status,Send Email,Birth date,Street,Street No,Postal Code,City,Country,Province,phone,mobile\x0A";
        echo mb_convert_encoding($header, 'sjis-win', 'utf-8');
        $output = fopen('php://output', 'w');
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
        $school = School::active()->find($schoolId);
        $teachers = $school->teachers;
        foreach ($teachers as $teacher) {
            $row = array();
            if ($teacher->pivot->role_type == 'school_admin') continue;
            $teacher_user = User::where(['person_id' => $teacher->id, 'person_type' => 'App\Models\Teacher'])->first();
            $schoolTeacher = SchoolTeacher::where(['teacher_id' => $teacher->id, 'school_id' => $schoolId])->first();
            $row[] = $teacher->id;
            $row[] = $teacher->email;
            if ($teacher_user) {
                $row[] = isset($teacher_user->username) && !empty($teacher_user->username) ? $teacher_user->username : '';
            } else {
                $row[] = '';    
            }
            $row[] = isset($teacher->lastname) && !empty($teacher->lastname) ? $teacher->lastname : '';
            $row[] = isset($teacher->firstname) && !empty($teacher->firstname) ? $teacher->firstname : '';
            if ($schoolTeacher) {
                $row[] = isset($schoolTeacher->nickname) && !empty($schoolTeacher->nickname) ? $schoolTeacher->nickname : '';
            } else {
                $row[] = '';
            }

            if ($teacher->gender_id == 1) {
                $row[] = 'Male';
            } else if ($teacher->gender_id == 2) {
                $row[] = 'Female';
            } else if ($teacher->gender_id == 3) {
                $row[] = 'Not specified';
            } else {
                $row[] = '';
            }
            $row[] = isset($teacher->licence_js) && !empty($teacher->licence_js) ? $teacher->licence_js : '';
            if ($schoolTeacher) {
              $row[] = isset($schoolTeacher->role_type) && !empty($schoolTeacher->role_type) ? $schoolTeacher->role_type : '';
            } else {
                $row[] = '';
            }
            $row[] = isset($schoolTeacher->bg_color_agenda) && !empty($schoolTeacher->bg_color_agenda) ? $schoolTeacher->bg_color_agenda : '';
            $row[] = isset($schoolTeacher->comment) && !empty($schoolTeacher->comment) ? $schoolTeacher->comment : '';
            $row[] = isset($teacher->is_active) && !empty($teacher->is_active) ? $teacher->is_active : '';
            $row[] = isset($schoolTeacher->is_sent_invite) && !empty($schoolTeacher->is_sent_invite) ? $schoolTeacher->is_sent_invite : 0;
            $row[] = isset($teacher->birth_date) && !empty($teacher->birth_date) ? $teacher->birth_date : '';
            $row[] = isset($teacher->street) && !empty($teacher->street) ? $teacher->street : '';
            $row[] = isset($teacher->street_number) && !empty($teacher->street_number) ? $teacher->street_number : '';
            $row[] = isset($teacher->zip_code) && !empty($teacher->zip_code) ? $teacher->zip_code : '';
            $row[] = isset($teacher->place) && !empty($teacher->place) ? $teacher->place : '';
            $row[] = isset($teacher->country_code) && !empty($teacher->country_code) ? $teacher->country_code : '';
            $row[] = isset($teacher->province_id) && !empty($teacher->province_id) ? $teacher->province_id : '';
            $row[] = isset($teacher->phone) && !empty($teacher->phone) ? $teacher->phone : '';
            $row[] = isset($teacher->mobile) && !empty($teacher->mobile) ? $teacher->mobile : '';

            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }

    /**
     * export teacher school wise
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function import($schoolId = null, Request $request, Teacher $teacher)
    {
        try {
            $alldata = $request->all();
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
            } else {
                $schoolId = $user->selectedSchoolId();
                $school = School::active()->find($schoolId);
                $schoolId = $school->id;
            }
            if ($request->file('csvFile')) {
                try {
                    $csvFile = $request->file('csvFile');
                    if ($csvFile->getSize() > 0) {
                        $mime_type = $csvFile->getMimeType();
                        $extension = $csvFile->getClientOriginalExtension();
                        // Create list name
                        $name = time() . '-' . $csvFile->getClientOriginalName();
                        $real_path = $csvFile->getRealPath();
                        if ($extension != 'csv') {
                            return redirect()->back();
                        }
                        $csvArr = $this->csvToArray($schoolId, $csvFile);
                        //dd($csvArr);
                        return back()->with('success', __('Teacher updated successfully!'));
                    }
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', __('Internal server error'));
                }
            }
        } catch (\Exception $e) {
            //return error message
            return redirect()->back()->with('error', __('Internal server error'));
        }
    }


    public function csvToArray($schoolId, $filename = '', $delimiter = ',')
    {
        try {
            if (!file_exists($filename) || !is_readable($filename))
                return redirect()->back()->with('error', __('Internal server error'));

            $header = null;
            $data = array();
            if (($handle = fopen($filename, 'r')) !== false) {
                while (($row = fgetcsv($handle, 10240, $delimiter)) !== false) {
                    if (empty($headers))
                        $headers = $row;
                    else if (is_array($row)) {
                        array_splice($row, count($headers));
                        $row = $row;
                        $teacher_id = $row[0];
                        $email = $row[1];
                        $username = $row[2];
                        $lastname = $row[3];
                        $firstname = $row[4];
                        $nickname = $row[5];
                        $gender_id = $row[6];
                        $licence_js = $row[7];
                        $role_type = $row[8];
                        $bg_color_agenda = $row[9];
                        $comment = $row[10];
                        $is_active = $row[11];
                        $is_sent_invite = $row[12];
                        if ($gender_id == 'Male') {
                            $gender_id = 1;
                        } else if ($gender_id == 'Female') {
                            $gender_id = 2;
                        } else if ($gender_id == 'Not specified') {
                            $gender_id = 3;
                        } else {
                            $gender_id = '';
                        }
                        $data = [
                            'email' => $email,
                            'username' => $username,
                            'lastname' => $lastname,
                            'firstname' => $firstname,
                            'nickname' => $nickname,
                            'gender_id' => $gender_id,
                            'role_type' => $role_type,
                            'licence_js' => $licence_js,
                            'bg_color_agenda'=>$bg_color_agenda,
                            'comment' => $comment,
                            'is_active' => isset($is_active) ? $is_active : 0,
                            'is_sent_invite' => isset($is_sent_invite) && !empty($is_sent_invite) ? 1 : 0,
                            'birth_date'=>isset($row[13]) && !empty($row[13]) ? date('Y-m-d H:i:s',strtotime($this->sdateFormat($row['13']))) : '',
                            'street'=>isset($row[14]) && !empty($row[14]) ? $row[14] : '',
                            'street_number'=>isset($row[15]) && !empty($row[15]) ? $row[15] : '',
                            'zip_code'=>isset($row[16]) && !empty($row[16]) ? $row[16] : '',
                            'place'=>isset($row[17]) && !empty($row[17]) ? $row[17] : '',
                            'country_code'=>isset($row[18]) && !empty($row[18]) ? $row[18] : '',
                            'province_id'=>isset($row[19]) && !empty($row[19]) ? $row[19] : '',
                            'phone'=>isset($row[20]) && !empty($row[20]) ? $row[20] : '',
                            'mobile'=>isset($row[21]) && !empty($row[21]) ? $row[21] : ''
                        ];

                        if (isset($teacher_id) && !empty($teacher_id)) {
                            //dd($data);
                            DB::beginTransaction();
                            try {
                                $teacher = Teacher::find($teacher_id);
                                if ($teacher) {
                                    $this->teacherUpdate($schoolId, $data, $teacher);
                                } else {
                                    continue;
                                }
                                DB::commit();
                            } catch (Exception $e) {
                                // dd($e);
                                DB::rollBack();
                            }
                        } else {
                            DB::beginTransaction();

                            try {
                                $user = User::where(['email' => $data['email'], 'person_type' => 'App\Models\Teacher', 'school_id' => $schoolId])->first();

                                if ($user) {
                                    $teacher = $user->personable;
                                    if ($teacher) {
                                        $this->teacherUpdate($schoolId, $data, $teacher);
                                    } else{
                                        continue;
                                    }
                                } else {
                                    $exTeacher = Teacher::where(['email'=> $data['email']])->first();
                                    if ($exTeacher) {
                                        $alreadyFlag = SchoolTeacher::where(['school_id' => $schoolId, 'teacher_id' => $exTeacher->id ])->first();
                                        if ($alreadyFlag) {
                                            $this->teacherUpdate($schoolId,$data,$exTeacher);
                                        } else{
                                            $this->schoolTeacherData($schoolId,$data,$exTeacher,'create',0,$data['is_sent_invite']);
                                        }
                                    }
                                    else {
                                        $teacherData = $data;
                                        unset($teacherData['comment']);
                                        $teacher = Teacher::create($teacherData);
                                        $teacher->save();
                                        $this->schoolTeacherData($schoolId, $data, $teacher,'create',0,$data['is_sent_invite']);
                                    }

                                    
                                }
                                DB::commit();
                            } catch (\Exception $e) {
                                //dd($e);
                                DB::rollBack();
                            }
                        }
                    }
                }
                fclose($handle);
            }
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function teacherUpdate($schoolId, $data, $teacher)
    {
        $teacher_id = $teacher->id;
        $user = User::where(['person_id' => $teacher_id, 'person_type' => 'App\Models\Teacher'])->first();
        //$teacher = $user->personable;
        $teacherData = $data;
        
        unset($teacherData['comment']);
        unset($teacherData['username']);
        unset($teacherData['nickname']);
        unset($teacherData['role_type']);
        unset($teacherData['bg_color_agenda']);
        unset($teacherData['is_sent_invite']);
        
        Teacher::where('id', $teacher->id)->update($teacherData);
        $teacher = Teacher::find($teacher_id);
        if ($user) {
            $this->schoolTeacherData($schoolId, $data, $teacher, 'update', 1);
        } else {
            $this->schoolTeacherData($schoolId, $data, $teacher, 'update',0,$data['is_sent_invite']);
        }
        return true;
    }

    public function schoolTeacherData($schoolId, $alldata, $teacher, $status = 'create', $has_user_account = 0,$is_sent_invite=0)
    {
        $schoolTeacher = [
            'teacher_id' => $teacher->id,
            'school_id' => $schoolId,
            'has_user_account' => $has_user_account,
            'nickname' => $alldata['nickname'],
            'bg_color_agenda' => $alldata['bg_color_agenda'],
            'role_type' => isset($alldata['role_type']) && !empty($alldata['level_id']) ? $alldata['level_id'] : null,
            'licence_js' => $alldata['licence_js'],
            'comment' => isset($alldata['comment']) ? $alldata['comment'] : '',
            'is_active' => isset($alldata['is_active']) ? $alldata['is_active'] : '',
            'is_sent_invite' => isset($alldata['is_sent_invite']) ? $alldata['is_sent_invite'] : 0
        ];

        $schoolTeacherExist = SchoolTeacher::where(['teacher_id' => $teacher->id, 'school_id' => $schoolId])->first();
        if (!empty($schoolTeacherExist)) {
            $status = 'update';
        }
        
        try {

            if ($status == 'create') {

                $schoolTeacherData = SchoolTeacher::create($schoolTeacher);
                $schoolTeacherData->save();
                if (!empty($alldata['email']) && $is_sent_invite ==1) {
                    //sending activation email after successful signed up
                    $inviteUpdate = [
                        'is_sent_invite' => $is_sent_invite
                    ];
                    $schoolTeacherData = $schoolTeacherData->update($inviteUpdate);
                }
            } else {
                //$schoolStudentData = SchoolStudent::where(['student_id' => $student->id, 'school_id' => $schoolId])->first();
                SchoolTeacher::where(['teacher_id' => $teacher->id, 'school_id' => $schoolId])->update($schoolTeacher);
                $schoolTeacherData = SchoolTeacher::where(['teacher_id' => $teacher->id, 'school_id' => $schoolId])->first();
        
                if ($has_user_account == 0) {
                    if (!empty($alldata['email']) && $is_sent_invite ==1) {
                        //sending activation email after successful signed up
                        $inviteUpdate = [
                            'is_sent_invite' => $is_sent_invite
                        ];
                        $schoolTeacherData = $schoolTeacherData->update($inviteUpdate);
                    }
                }
                return true;
            }
        } catch (\Exception $e) {
            return true;
            //dd($e);
            //return redirect()->back()->with('error', __('Internal server error'));
        }
    }
}
