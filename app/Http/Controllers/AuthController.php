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
     * @return redirect to root
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-02-03
     */
    public function loginSubmit(LoginRequest $request)
    {
        $data = $request->all();

      

        $result = array(
            'status' => 1,
            'message' => _('failed to login'),
        );


        if ($data['type'] === 'login_submit') { 

            $username = $data['login_username'];
            $field = 'username';
            $user = User::getUserData($field, $username);
            $http_host=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/" ;
            //         $result = array(
            //             "status"     => 0,
            //             'message' => _('Successfully logged in'),
            //             "user_id"  => $user['id'],
            //             "user_name" => $user['username'],
            //             "user_role"  => $user['person_type'],
            //             "school_code"  => $user['related_school']['school_code'],                                
            //             "email"  => $user['email'],
            //             "school_id"  => $user['related_school']['id'],
            //             "v_t_cnt"  => $user['related_school']['max_teachers'],
            //             "v_s_cnt"  =>$user['related_school']['max_students'],
            //             //"tc_accepted_flag"  => $row['tc_accepted_flag'],
            //             "country_id"  => $user['teacher']['country_id'],
            //             "person_id"  => $user['person_id'],
            //             "http_host" => $http_host
            //         );
            // print_r($result);
            // exit();
            
            if ($user) {
                if(Auth::attempt(['username' => $data['login_username'], 'password' => $data['login_password']], $request->filled('remember'))){
                
            

                    //if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
                    
                    Auth::login($user, true);

                    $result = array(
                        "status"     => 0,
                        'message' => _('Successfully logged in'),
                        "user_id"  => $user['id'],
                        "user_name" => $user['username'],
                        "user_role"  => $user['person_type'],
                        "school_code"  => $user['related_school']['school_code'],                                
                        "email"  => $user['email'],
                        "school_id"  => $user['related_school']['id'],
                        "v_t_cnt"  => $user['related_school']['max_teachers'],
                        "v_s_cnt"  =>$user['related_school']['max_students'],
                        //"tc_accepted_flag"  => $row['tc_accepted_flag'],
                        "country_id"  => $user['teacher']['country_id'],
                        "person_id"  => $user['person_id'],
                        "http_host" => $http_host
                    );
            
                    
                    return response()->json($result);
                    
                    
                    
                }
            }
            
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
