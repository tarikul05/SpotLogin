<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use App\Models\Teacher;
use App\Models\SchoolEmployee;
use App\Models\VerifyToken;
use App\Models\Currency;
use App\Models\EmailTemplate;
use App\Models\Country;
use App\Mail\SportloginEmail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RegistrationRequest;


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
                'is_active'=>1
            ];
            if (!empty($data['country_code'])) {
                $currencyExists = Currency::byCountry($data['country_code'])->active()->first();  
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
            $teacher->schools()->attach($school->id, ['nickname' => 'this is nickname','role_type'=>$roleType, 'has_user_account'=> 1]);
            
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
                'is_firstlogin'=>0
            ];

            $user = User::create($usersData);
            $user->save();
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
                $data['name'] = $user->username;
                $verifyUser = [
                    'user_id' => $user->id,
                    'token' => Str::random(10),
                    'expire_date' => Carbon::now()->addDays(2)->format("Y-m-d")
                ];
                
        
                $verifyUser = VerifyToken::create($verifyUser);
        
                $data['token'] = $verifyUser->token; 
                $data['username'] = $user->username; 
                $emailTemplateExist = EmailTemplate::where([
                    ['template_code', 'sign_up_confirmation_email'],
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
                $data['url'] = route('verify.email',$data['token']); 
                \Mail::to($user->email)->send(new SportloginEmail($data));
                
                $user->is_mail_sent = 1;
                $user->save();
                $result = array(
                    'status' => 1,
                    'message' => __('We sent you an activation link. Check your email and click on the link to verify.'),
                );
                
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
     * signup virification 
     * 
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-02-11
     */
    public function verify_user($token)
    {
       
        try{
            $to = Carbon::now()->format("Y-m-d");
            $verifyUser = VerifyToken::where([
                                        ['expire_date', '>=', $to],
                                        ['token', $token]
                                    ])->first();
            
            if(isset($verifyUser) ){
                $user = $verifyUser->user;
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
            $to = Carbon::now()->format("Y-m-d");
            $verifyToken = VerifyToken::where([
                                        ['expire_date', '>=', $to],
                                        ['token', $token]
                                    ])->first();
            
            if(isset($verifyToken) ){
                $user_data = $verifyToken->personable;
                //dd($user_data);

                $countries = Country::active()->get();
                $genders = config('global.gender'); 
                $exTeacher = $searchEmail = null;
                
                return view('pages.verify.add')->with(compact('countries','genders','user_data','verifyToken'));

                // if(!$user->is_active) {
                //     $verifyUser->user->is_active = 1;
                //     $verifyUser->user->save();
                //     echo '<h1>Account Activated Successfully..please login into your account</h1>';
                //     header( "refresh:2;url=/" );
                //     //exit();
                //     $status = "Your e-mail is verified. You can now login.";
                // }else{
                //     echo $status = "Your e-mail is already verified. You can now login.";
                //     header( "refresh:2;url=/" );
                // }
            }else{
                echo '<h1>Invalid activation Link.</h1>'; die;
            }
        } catch (Exception $e) {
            //return error message
            echo '<h1>Invalid activation Link.</h1>'; die;
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
