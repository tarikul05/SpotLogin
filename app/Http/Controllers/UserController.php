<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use App\Models\Teacher;
use App\Models\SchoolStudent;
use App\Models\SchoolEmployee;
use App\Models\VerifyToken;
use App\Models\Currency;
use App\Models\EmailTemplate;
use App\Models\Country;
use App\Models\SchoolTeacher;
use App\Mail\SportloginEmail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\ActivationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use URL;
use Cookie;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.teachers.list');
    }



     /**
     * signup confirmation
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-02-03
     */
    public function create(RegistrationRequest $request)
    {
        $result = array(
            'status' => 0,
            'message' => __('failed to signup'),
        );
        DB::beginTransaction();
        try{
            $data = $request->all();
            $school_type=trim($data['school_type']);


            $roleType = ($school_type=='COACH') ? 'teachers_admin' : 'school_admin';
            $scType = ($school_type=='COACH') ? 'C' : 'S';
            $school_code = strtolower($data['username']);

            $schoolData = [
                'school_code' => $school_code,
                'school_name' => $data['fullname'],
                'incorporation_date'=> now(),
                'country_code' => $data['country_code'],
                'email'=>$data['email'],
                'sender_email'=>$data['email'],
                'school_type'=>$scType,
                'max_students'=>0,
                'max_teachers'=>0,
                'is_active'=>1,
                'timezone'=>$data['timezone']
            ];
            if (!empty($data['country_code'])) {
                $currencyExists = Currency::getCurrencyByCountry($data['country_code']);
                if ($currencyExists) {
                    $schoolData['default_currency_code'] = $currencyExists->currency_code;
                }
            }

            $school = School::create($schoolData);
            $school->save();

            $teacherData = [
                'lastname' => '',
                'middlename'=>'',
                'firstname'=>$data['fullname'],
                'email'=>$data['email'],
                'country_code'=>$data['country_code'],
                'is_active' =>1
            ];


            $teacher = Teacher::create($teacherData);
            $teacher->save();
            $teacher->schools()->attach($school->id, ['nickname' => $data['fullname'],'role_type'=>$roleType, 'has_user_account'=> 1, 'is_sent_invite'=>1 ]);

            $trialDays = 91;
            $trialEndsAt = now()->addDays($trialDays);

            $usersData = [
                'person_id' => $teacher->id,
                'person_type' =>'App\Models\Teacher',
                'school_id' => $school->id,
                'username' =>$data['username'],
                'lastname' => '',
                'middlename'=>'',
                'firstname'=>$data['fullname'],
                'email'=>$data['email'],
                'password'=>$data['password'],
                'is_mail_sent'=>0,
                'is_active'=>1,
                'is_firstlogin'=>0,
                'trial_ends_at' => $trialEndsAt,
            ];
            $strip_userdata = [
                'email'=> $data['email'],
                'name' => $data['fullname'],
            ];
            $user = User::create($usersData);
            $user->save();
            $user->createAsStripeCustomer($strip_userdata);
            $result = array(
                "status"     => 1,
                'message' => __('Successfully Registered')
            );
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }




        //sending activation email after successful signed up
        if (config('global.email_send') == 1) {

            try {
                $data = [];
                $data['email'] = $user->email;
                $data['country_code'] = $school->country_code;
                $data['name'] = $user->username;
                $data['school_name'] = $school->school_name;
                $verifyUser = [
                    'school_id' => $school->id,
                    'person_id' => $teacher->id,
                    'person_type' => 'App\Models\Teacher',
                    'token_type' => 'VERIFY_SIGNUP',
                    'token' => Str::random(10),
                    'expire_date' => Carbon::now()->addDays(config('global.token_validity'))->format("Y-m-d")
                ];


                $verifyUser = VerifyToken::create($verifyUser);

                $data['token'] = $verifyUser->token;
                $data['username'] = $user->username;

                // $data['subject']=__('www.sportogin.ch: Welcome! Activate account.');
                $data['url'] = route('verify.email',$data['token']);



                if ($this->emailSend($data,'school')) {
                    $user->is_mail_sent = 1;
                    $user->save();
                    $result = array(
                        'status' => 1,
                        'message' => __('We sent you an activation link. Check your email and click on the link to verify.'),
                    );
                }  else {
                    $result = array(
                        "status"     => 0,
                        'message' =>  __('Internal server error')
                    );
                }

                return response()->json($result);
            } catch (\Exception $e) {
                $result = array(
                    'status' => 1,
                    'message' => __('We sent you an activation code. Check your email and click on the link to verify.'),
                );
                $user->is_active = 1;
                $user->save();
                return response()->json($result);
            }
        } else {
            $user->is_active = 1;
            $user->save();
        }
        return response()->json($result);
    }



     /**
     * signup confirmation
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-23
     */
    public function create_verified_user(ActivationRequest $request)
    {
        $data = $request->all();
        try{
            // create trail user
            $trialDays = 91;
            $trialEndsAt = now()->addDays($trialDays);
            $full_name = $data['firstname'].''.$data['lastname'];
            $strip_userdata = [
                'email'=> $data['email'],
                'name' => $full_name,
            ];
            $request->merge(['is_firstlogin'=>0,'is_mail_sent'=> 1,'is_active'=> 1, 'trial_ends_at' => $trialEndsAt]);

            $user = User::create($request->except(['_token']));
            if($user){
                $user->createAsStripeCustomer($strip_userdata);
            }
            // return back()->withInput($request->all())->with('success', __('Successfully Registered!'));
           return redirect('/')->with('success', __('Successfully Registered!'));
        } catch (Exception $e) {
            //return error message\
           return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }

        //return error message
        return redirect()->back()->withInput($request->all())->with('error', __('failed to signup'));
    }


    /**
     * signup virification
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-02-11
     */
    public function verify_user($token)
    {
        try{
            Auth::logout();
            Session::flush();
    
            $cal_view_mode = Cookie::forget('cal_view_mode');
            $date_from = Cookie::forget('date_from');
            $view_mode = Cookie::forget('view_mode');
            $date_to = Cookie::forget('date_to');
            $to = Carbon::now()->format("Y-m-d");
            $verifyUser = VerifyToken::where([
                                        ['expire_date', '>=', $to],
                                        ['token', $token]
                                    ])->first();

            if(isset($verifyUser) ){
                $user = $verifyUser->user;
                if ($verifyUser->person_type =='App\Models\Student') {
                    $exist = SchoolStudent::where(['student_id'=>$verifyUser->person_id, 'school_id'=>$verifyUser->school_id])->first();

                }
                if ($verifyUser->person_type =='App\Models\Teacher') {
                    $exist = SchoolTeacher::where(['teacher_id'=>$verifyUser->person_id, 'school_id'=>$verifyUser->school_id])->first();

                }
                if ($exist) {
                    $exist->has_user_account = 1;
                    $exist->save();
                }
                if(!$user->is_active) {
                    $verifyUser->user->is_active = 1;
                    $verifyUser->user->save();
                    echo '<h1>Account Activated Successfully..please login into your account</h1>';
                    header( "refresh:2;url=/" );
                    //exit();
                    $status = "Your e-mail is verified. You can now login.";
                }else{
                    echo $status = "Your e-mail is already verified. You can now login.";
                    header( "refresh:2;url=/" );
                }
            }else{
                echo '<h1>Invalid activation Link.</h1>'; die;
            }
        } catch (Exception $e) {
            //return error message
            echo '<h1>Invalid activation Link.</h1>'; die;
        }
    }


    /**
     * after user add from admin verify it by user
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-22
     */
    public function verify_user_added($token)
    {

        try{
            Auth::logout();
            Session::flush();
    
            $cal_view_mode = Cookie::forget('cal_view_mode');
            $date_from = Cookie::forget('date_from');
            $view_mode = Cookie::forget('view_mode');
            $date_to = Cookie::forget('date_to');

            $to = Carbon::now()->format("Y-m-d");
            $verifyToken = VerifyToken::where([
                                        ['expire_date', '>=', $to],
                                        ['token', $token]
                                    ])->first();
            $timezones = config('global.timezones');
            if(isset($verifyToken) ){
                $user_data = $verifyToken->personable;
                $school = $verifyToken->school;


                $countries = Country::active()->get();
                $genders = config('global.gender');


                if(!$user_data->user) {
                    if ($verifyToken->person_type =='App\Models\Student') {
                        $user_data->email = $user_data->email;
                    }else{
                        $user_data->email = $user_data->email;
                    }
                    return view('pages.verify.add')->with(compact('school','timezones','countries','genders','user_data','verifyToken'));
                }else{
                    if ($verifyToken->person_type =='App\Models\Student') {
                        $exist = SchoolStudent::where(['student_id'=>$verifyToken->person_id, 'school_id'=>$verifyToken->school_id])->first();
    
                    }
                    if ($verifyToken->person_type =='App\Models\Teacher') {
                        $exist = SchoolTeacher::where(['teacher_id'=>$verifyToken->person_id, 'school_id'=>$verifyToken->school_id])->first();
    
                    }
                    if ($exist) {
                        $exist->has_user_account = 1;
                        $exist->save();
                    }
                    if($user_data->user->is_active==0) {

                        $user_data->user->is_active = 1;
                        $user_data->user->save();
                        echo $status = "User already added please login.";
                        header( "refresh:2;url=/" );
                        //return view('pages.verify.add')->with(compact('school','countries','genders','user_data','verifyToken'));
                    }
                    if($user_data->user->is_active ==3) {

                        return view('pages.verify.active_school_user')->with(compact('school','timezones','countries','genders','user_data','verifyToken'));

                    }
                    else{
                        if ($verifyToken->person_type =='App\Models\Student') {
                            $exist = SchoolStudent::where(['is_active'=> 0,'student_id'=>$user_data->id, 'school_id'=>$verifyToken->school_id])->first();

                        }
                        else {
                            $exist = SchoolTeacher::where(['is_active'=> 0,'teacher_id'=>$user_data->id, 'school_id'=>$verifyToken->school_id])->first();

                        }
                        if ($exist) {
                            return view('pages.verify.active_school_user')->with(compact('school','timezones','countries','genders','user_data','verifyToken'));
                        } else {
                            echo $status = "User already added please login.";
                            header( "refresh:2;url=/" );
                        }



                    }

                }
            }else{
                echo '<h1>Invalid activation Link.</h1>'; die;
            }
        } catch (Exception $e) {
            //return error message
            echo '<h1>Invalid activation Link.</h1>'; die;
        }
    }

     /**
     * after user add from admin verify it by user
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-22
     */
    public function active_school(Request $request)
    {
        $data = $request->all();
        
        try{
            $user = User::where(['id'=>$data['user_id']])->orderBy('id','desc')->first();
            $userName = !empty($user) && $user->username == $data['login_username'] ? $user->username : '';

            if (!Auth::attempt(['username' => $userName, 'password' => $data['login_password']], $request->filled('remember'))) {
                return redirect()->back()->withInput($request->all())->with('error', __('Login information not match'));
            }

            $user_data = [
                'is_active'=> 1
            ];
            User::where(['id'=>$data['user_id']])->update($user_data);
            $relationalData = [
                'is_active'=> 1,
                'has_user_account' => 1
            ];
            if ($data['person_type'] =='App\Models\Teacher') {
                SchoolTeacher::where(['teacher_id'=>$data['person_id'], 'school_id'=>$data['school_id']])->update($relationalData);
            }
            if ($data['person_type'] =='App\Models\Student') {
                SchoolStudent::where(['student_id'=>$data['person_id'], 'school_id'=>$data['school_id']])->update($relationalData);
            }

            return redirect()->route('check.permission');

        } catch (Exception $e) {
            //return error message\
           return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }

        //return error message
        return redirect()->back()->withInput($request->all())->with('error', __('failed to signup'));
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function welcome()
    {
        return view('welcome');
    }
}
