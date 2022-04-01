<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Models\VerifyToken;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Mail\SportloginEmail;
use URL;
use Spatie\Permission\Models\Role;


class AuthController extends Controller
{

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::ROOT;

    /**
     * Create a new controller instance
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Login UI and Login confirmation 
     * 
     * @return void
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-02-03
     */
    public function index(){

        if (Auth::check()) {
            $user = Auth::user();
            return redirect(RouteServiceProvider::HOME);
        }
        return view('pages.top', ['title' => 'User Login','pageInfo'=>['siteTitle'=>'']]);
    }

    /**
     * Login UI and Login confirmation 
     * 
     * @return void
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-02-03
     */
    public function login(){
        if (Auth::check()) {
            $user = Auth::user();
            return redirect(RouteServiceProvider::HOME);
        }
        return view('pages.auth.login', ['title' => 'User Login','pageInfo'=>['siteTitle'=>'']]);
    }


    
    /**
     * AJAX login submit method
     * 
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-02-03
     */
    public function changeFirstPassword(ChangePasswordRequest $request)
    {
        $result = array(
            'status' => 1,
            'message' => __('Failed to login'),
        );
        try {
            $data = $request->all();
            $user_name = trim($_POST['reset_username']);
            $old_password = trim($_POST['old_password']);
            $new_password = trim($_POST['new_password']);
            sleep(3);
            $result = User::change_password($user_name, $old_password,$new_password);
            return response()->json($result);

        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
    }

     

    /**
     * AJAX login submit method
     * 
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-02-03
     */
    public function loginSubmit(Request $request)
    {
        $result = array(
            'status' => 1,
            'message' => __('Failed to login'),
        );
        try {
            $data = $request->all();
            
            if ($data['type'] === "validate_username") {
                $p_username=trim($_POST['p_username']);
                $field = 'username';
                $user = User::getUserData($field, $p_username);
                $result['status']=0;
                $result['cnt']=0;
                $result['message']=__('username is ok!');
                if ($user) {
                    $result['cnt']=1;
                    $result['message']=__('username already exist');
                }
                return response()->json($result);
            }
            else if ($data['type'] === 'login_submit') { 

                $username = $data['login_username'];
                $field = 'username';
                $user = User::getUserData($field, $username);
            
                if ($user) {
                    if(Auth::attempt(['username' => $data['login_username'], 'password' => $data['login_password']], $request->filled('remember'))){
                    
                        // Auth::login($user);
                        $user = Auth::user();
                        $country_code = null;
                        if (isset($user->teacher)) {
                            $country_code = $user->teacher['country_code'];
                        }
                        else if (isset($user->student)) {
                            $country_code = $user->student['country_code'];
                        }
                        else if (isset($user->parent)) {
                            $country_code = $user->parent['country_code'];
                        }

                        $result = array(
                            "status"     => 0,
                            'message' => __('Successfully logged in'),
                            "user_id"  => $user['id'],
                            "user_name" => $user['username'],
                            "user_role"  => $user['person_type'],
                            "email"  => $user['email'],
                            "country_code"  => $country_code,
                            "person_id"  => $user['person_id']
                        );
                        return response()->json($result);
                    }
                }
                
            }
            else if ($data['type'] === "check_first_login") {
                
            
                $user_name = $data['login_username'];
                $password = $data['login_password'];
                $user = User::getFirstLoginData_after_reset($user_name, $password);
                $result = array(
                    'status' => 0,
                    'message' => __('First login'),
                );
                if (!$user) {
                    $result = array(
                        'status' => 1,
                        'message' => __('user not exist'),
                    );
                } else {
                    if (!Hash::check($password, $user->password)) {
                        $result = array(
                            'status' => 1,
                            'message' => __('Login Fail, pls check password'),
                        );
                    } 
                }
                
            }

            return response()->json($result);

        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
        
    }


    /**
     * forgot password send and reset
     * 
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-02-15
     */
    public function forgotPassword(Request $request)
    {
        $result = array(
            'status' => false,
            'message' => __('failed to send email'),
        );
        try {
            $data = $request->all();
            if ($data['type'] === "forgot_password_submit") {

            
                $username=trim($_POST['forgot_password_username']);
                $p_lang=trim($_POST['p_lang']);

                $user = User::where([
                    ['username', $username],
                    ['is_active', 1],
                    ['deleted_at', null],
                ])->first();  
                if ($user) {
                    //sending email for forgot password
                    if (config('global.email_send') == 1) {
                        
                        try {
                            $data = [];
                            $data['email'] = $user->email;
                            $data['name'] = $user->username;
                            $verifyUser = [
                                'user_id' => $user->id,
                                'token' => Str::random(5),
                                'expire_date' => Carbon::now()->addDays(config('global.token_validity'))->format("Y-m-d")
                            ];
                            
                    
                            $verifyUser = VerifyToken::create($verifyUser);
                    
                            $data['token'] = $verifyUser->token; 
                            $data['username'] = $user->username; 
                            $data['subject']='Reset Password';
                            $data['url'] = route('reset_password.email',$data['token']); 
                            
                            $data['p_lang'] = $p_lang; 
                            

                            if ($this->emailSend($data,'forgot_password_email')) {
                                $user->is_mail_sent = 1;
                                $user->save();
                                $result = array(
                                    'status' => true,
                                    'message' => __('We sent you an activation link. Check your email and click on the link to verify.'),
                                );
                            }  else {
                                $result = array(
                                    "status"     => false,
                                    'message' =>  __('Internal server error')
                                );
                            }
                            return response()->json($result);
                        } catch (\Exception $e) {
                            $result = array(
                                'status' => true,
                                'message' => __('We sent you an activation code. Check your email and click on the link to verify.'),
                            );
                            $user->is_active = 1;
                            $user->save();
                            return response()->json($result);
                        }
                    } else{
                        $result = array('status'=>true,'msg'=>__('email sent'));
                    }
                }   else {
                    $result = array('status'=>false,'msg'=>__('Username not exist'));
                }
                
            
            
            }

            return response()->json($result);

        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
        
    }



     /**
     * signup virification 
     * 
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-02-11
     */
    public function resetPasswordEmail($token)
    {
        
        try {
            $to = Carbon::now()->format("Y-m-d");
            $verifyUser = VerifyToken::where([
                                        ['expire_date', '>=', $to],
                                        ['token', $token]
                                    ])->first();
            
            if(isset($verifyUser) ){
                $user = $verifyUser->user;
                if($user) {
                    return view('pages.auth.reset_password', ['title' => 'User Login','user'=>$user]);
                }else{
                    echo '<h1>Invalid reset password Link.</h1>'; die;
                }
            }else{
                echo '<h1>Invalid reset password Link.</h1>'; die;
            }
        } catch (Exception $e) {
            //return error message
            echo '<h1>Internal server error.</h1>'; die;
        }
    }


     /**
     * reset password change 
     * 
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-02-11
     */
    public function resetPasswordSubmit(ResetPasswordRequest $request)
    {
        try {
            $data = $request->all();
            $user = User::where([
                            ['id', $data['reset_password_user_id']],
                            ['deleted_at', null],
                    ])->first();
            if ($user) {
                $user->password = $data['reset_password_pass'];
                $user->save();
                return back()->with('status', "Password changed successfully!");
            }
        } catch (Exception $e) {
            //return error message
            return redirect()->back()->withInput()->with('error', __('Internal server error'));
        }
    }

    /**
     * checked permission
     * 
     * @return json
     * @author Tarikul
     * @version 0.1 written in 2022-02-11
     */
    public function permission_check(Request $request)
    {
        $user = Auth::user();
        // dd($user->getSelectedSchoolAttribute());
        // if (!empty($request->session()->get('selected_role'))) {
        //     return redirect()->route('Home');
        // }
        if ($user->person_type == 'App\Models\Student') {
            $user->syncRoles(['student']);
            $request->session()->put('selected_role','student');
            // $request->session()->put('selected_school', $school);
            return redirect()->route('Home');

        }elseif ($user->person_type == 'App\Models\Teacher' && count($user->schools()) == 1 ) {
            $tRoleType = $user->schools()[0]->pivot->role_type;
            $user->syncRoles([$tRoleType]);
            $request->session()->put('selected_school', $user->schools()[0]);
            $request->session()->put('selected_role',$tRoleType);
            return redirect()->route('Home');
        }

        if ($request->isMethod('post')){
            $params = $request->all();
            foreach ($user->schools() as $key => $school) {
                if ($school->id == $params['sch']) {
                    $request->session()->put('selected_school', $school);
                    $request->session()->put('selected_role',$school->pivot->role_type);
                    $user->syncRoles([$school->pivot->role_type]);
                    return redirect()->route('Home');
                }
            }
        }

        return view('pages.auth.permission_check', [
            'schools' => $user->schools(),
            'user' => $user,
            'selected_role' => $request->session()->get('selected_role'),
            'pageInfo'=>['siteTitle'=>'']
        ]);

    }

   
    /**
     * logout action
     * 
     * @return redirect to login page
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-02-03
     */
    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        
        return redirect('/');
    }

    
}
