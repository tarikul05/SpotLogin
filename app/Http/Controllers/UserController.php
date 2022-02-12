<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use App\Models\Teacher;
use App\Models\SchoolEmployee;
use App\Models\VerifyToken;
use App\Models\Currency;
use App\Mail\NewRegistration;
use Illuminate\Support\Str;
use Carbon\Carbon;


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
    public function create(Request $request)
    {
        $data = $request->all();


        // $verifyUser = [
        //     'user_id' => 1,
        //     'token' => Str::random(5),
        //     'expire_date' => Carbon::now()->addDays(2)->format("Y-m-d")
        // ];

        // $data['email'] = $data['email'];
        // $data['name'] =$data['fullname'];

        // $data['token'] = $verifyUser['token']; 


        // $email_body ='<table border="0" cellpadding="0" cellspacing="0" width="100%">
        //                         <tbody>
        //                     <tr>
        //                         <td style="background-color:#0e2245; height:100px; text-align:center"><a href="[~~HOSTNAME~~][~~USER_NAME~~]/index.html"><img alt="SPORTLOGIN" src="http://sportlogin.ch/img/banner-sport-login.jpg" style="height:100%; width:100%" /></a></td>
        //                     </tr>
        //                 </tbody>
        //             </table>
        //             <!-- BEGIN BODY -->
                    
        //             <table align="center" cellpadding="15" style="width:100%">
        //                 <tbody>
        //                     <tr>
        //                         <td style="text-align:center">
        //                         <h2><span style="color:#2980b9"><strong>Welcome to Sportlogin!</strong></span></h2>
                    
        //                         <p><span style="color:#2980b9">Please confirm your account by clicking on</span></p>
                    
        //                         <p><strong><a href="[~~URL~~]">CONFIRM</a></strong></p>
                    
        //                         <p>[~~HOSTNAME~~][~~USER_NAME~~]/index.html</p>
        //                         </td>
        //                     </tr>
        //                 </tbody>
        //             </table>
                    
        //             <table cellpadding="0" cellspacing="0" style="width:100%">
        //             </table>';
        // $eol = "\r\n"; 
        
        // $url = route('verify.email',$data['token']); 
        // $http_host=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/" ;
        // $email_body = str_replace("[~~HOSTNAME~~]",$http_host,$email_body);
        // $email_body = str_replace("[~~USER_NAME~~]/index.html",'',$email_body);
        // $email_body = str_replace("[~~URL~~]",$url,$email_body);
        // //message body
        // $body_text=wordwrap(trim($email_body), 70, $eol);
        // $data['body_text']=str_replace('<<~>>','&',$body_text);

        // //print_r($email_body);die;
        // //$email_subject="www.sportogin.ch: Welcome! Activate account.";

        // //$mail_status=SendGenericMail($username,'p_from_email',$email,'','',$email_subject,$email_body);           

        // //$return_data = array('status'=>true,'data'=>$row);

       
        
        // \Mail::to($data['email'])->send(new NewRegistration($data));
        // print_r($data);
        // exit();

        //$verifyUser = VerifyToken::create($verifyUser);

        // print_r($verifyUser);
        // exit();
        $result = array(
            'status' => 1,
            'message' => __('failed to signup'),
        );
        
        $school_type=trim($data['school_type']);
        $default_currency_code = '';
        if (!empty($data['country_code'])) {
            $currencyExists = Currency::where([
                ['country_code', $data['country_code']],
                ['deleted_at', null],
                ['is_active', 1],
              ])->first();       

            if ($currencyExists) {
                $default_currency_code = $currencyExists->currency_code;
            } 
        }

        if ($school_type=='SCHOOL') {
            $school_code = strtolower($data['username']);

            $schoolData = [
                'default_currency_code' => $default_currency_code,
                'school_code' => $school_code,
                'school_name' => $data['fullname'],
                'incorporation_date'=> now(),
                'country_code' => $data['country_code'],
                'email'=>$data['email'],
                'sender_email'=>$data['email'],
                'max_students'=>0,
                'max_teachers'=>0,
                'is_active'=>1
            ];
            
            $school = School::create($schoolData);
            $school->save();

            

            $schoolAdminData = [
                'school_id' => $school->id,
                'lastname' => '',
                'middlename'=>'',
                'firstname'=>$data['fullname'],
                'email'=>$data['email'],
                'country_code'=>$data['country_code'],
                'has_user_account'=>1,
                'is_active' =>0
            ];

            $schoolAdmin = SchoolEmployee::create($schoolAdminData);
            $schoolAdmin->save();
            $usersData = [
                'person_id' => $schoolAdmin->id,
                'person_type' =>'SCHOOL_ADMIN',
                'school_id' => $school->id,
                'username' =>$data['username'],
                'lastname' => '',
                'middlename'=>'',
                'firstname'=>$data['fullname'],
                'email'=>$data['email'],
                'password'=>$data['password'],
                'is_mail_sent'=>0,
                'is_active'=>0
            ];

            $user = User::create($usersData);
            $user->save();
            $result = array(
                "status"     => 0,
                'message' => __('Successfully Registered')
            );
        }
        else if($school_type=='COACH'){
            //'max_students'=>1,
            $coachData = [
                'lastname' => '',
                'middlename'=>'',
                'firstname'=>$data['fullname'],
                'email'=>$data['email'],
                'country_code'=>$data['country_code'],
                'type'=>2,//1=teacher 2=coach
                'has_user_account'=>1,
                'display_home_flag'=>1,
                'is_active' =>0
            ];

            $coach = Teacher::create($coachData);
            $coach->save();
            $usersData = [
                'person_id' => $coach->id,
                'person_type' =>'COACH',
                'username' =>$data['username'],
                'lastname' => '',
                'middlename'=>'',
                'firstname'=>$data['fullname'],
                'email'=>$data['email'],
                'password'=>$data['password'],
                'is_mail_sent'=>0,
                'is_active'=>0
            ];

            $user = User::create($usersData);
            $user->save();
            $result = array(
                "status"     => 0,
                'message' => __('Successfully Registered')
            );


            
        }


        

        //sending activation email after successful signed up
        if (config('global.email_send') == 1) {
            try {
                $data = [];
                $data['email'] = $user->email;
                $data['name'] = $user->firstname;
                $verifyUser = VerifyToken::create([
                    'user_id' => $user->id,
                    'token' => Str::random(5),
                    'expire_date' => Carbon::now()->addDays(2)->timestamp
                ]);
        
                $verifyUser = [
                    'user_id' => $user->id,
                    'token' => Str::random(5),
                    'expire_date' => Carbon::now()->addDays(2)->format("Y-m-d")
                ];
        
                $verifyUser = VerifyToken::create($verifyUser);
        
                // print_r($verifyUser);
                // exit();
        
                $data['token'] = $verifyUser->token; 
                $data['username'] = $user->username; 
                $email_body='<p><strong><a href="[~~URL~~]">CONFIRM</a></strong></p>';
                
                    
                $url = route('verify.email',$data['token']); 
                $data['body_text'] = str_replace("[~~URL~~]",$url,$email_body);

                
                
                \Mail::to($user->email)->send(new NewRegistration($data));
                
                $result = array(
                    'status' => 0,
                    'message' => __('We sent you an activation link. Check your email and click on the link to verify.'),
                );
                
                return response()->json($result);
            } catch (\Exception $e) {
                $result = array(
                    'status' => 0,
                    'message' => __('We sent you an activation code. Check your email and click on the link to verify.'),
                );
                $user->is_active = 1;
                $user->save();
                return response()->json($result);
            }
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
    public function verifyUser($token)
    {
        $verifyUser = VerifyToken::where('token', $token)->first();
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
                die;
            }
        }else{
            //return redirect('/login')->with('warning', "Sorry your email cannot be identified.");
        

            echo '<h1>Invalid activation Link.</h1>'; die;
        }

        //return redirect('/login')->with('status', $status);
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
}
