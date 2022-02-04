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
        $user = User::where([
                        ['email', $data['email']],
                        ['deleted_at', null],
                ])->first();
        if ($user) {
            if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
                
                Auth::login($user, true);
                if ($user->authority =='MASTER') {
                    return redirect(RouteServiceProvider::USER_LIST);
                }
                //in case intended url is available
                if (session()->has('url.intended')) {
                    $redirectTo = session()->get('url.intended');
                    session()->forget('url.intended');
                }

                // if($user->authority =='MASTER'){
                //     return redirect(RouteServiceProvider::HOME);
                // }

                $request->session()->regenerate();

                if (isset($redirectTo)) {
                    if ($redirectTo == $this->BASE_URL && $user->authority =='MASTER') {
                        return redirect(RouteServiceProvider::USER_LIST);
                    }
                    return redirect($redirectTo);
                }
                return redirect(RouteServiceProvider::HOME);
                
                
            }
        }
        
        return redirect()->back()->withInput()->with('error', 'Login failed, please try again!');
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
        return redirect('/login');
    }

    
}
