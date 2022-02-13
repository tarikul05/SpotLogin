<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use URL;


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
     * Login UI and Login confirmation 
     * 
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-02-03
     */
    public function loginSubmit(Request $request)
    {
        $data = $request->all();
        $result = array(
            'status' => 1,
            'message' => __('failed to login'),
        );
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
                    else if (isset($user->coach)) {
                        $country_code = $user->coach['country_code'];
                    }
                    else if (isset($user->schooladmin)) {
                        $country_code = $user->schooladmin['country_code'];
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

            
            $time_zone = date_default_timezone_get();
            
        
            $user_name = $data['login_username'];
            $password = $data['login_password'];
            $user = User::getFirstLoginData_after_reset($user_name, $password);
            
            if (!$user) {
                $result = array(
                    'status' => 1,
                    'message' => __('user not exist'),
                );
                //return response()->json($result);
            }
            if (!Hash::check($password, $user->password)) {
                $result = array(
                    'status' => 1,
                    'message' => __('Login Fail, pls check password'),
                );
                //return response()->json($result);
            } 
            
            
            // $user->is_firstlogin = 0;
            // $user->save();
            $result = array(
                'status' => 0,
                'message' => __('first login'),
            );
            // print_r($result);
            // exit();
            
            
        }
        return response()->json($result);
        
    }


   
    /**
     * Login UI and Login confirmation 
     * 
     * @return redirect to login page
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-02-03
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    
}
