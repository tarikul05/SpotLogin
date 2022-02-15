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

        else if ($action == "change_first_password") {

           
            $user_name = trim($_POST['reset_username']);
            $old_password = trim($_POST['old_password']);
            $new_password = trim($_POST['new_password']);

            $result = User::reset_password($user_name, $old_password,$new_password);
            

           

            				
            echo json_encode($result);	
        }
        return response()->json($result);
        
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
        $data = $request->all();
        $result = array(
            'status' => false,
            'message' => __('failed to login'),
        );
        if ($action == "forgot_password_submit") {

		
            // $username=trim($_POST['forgot_password_username']);
            // $p_school_code=trim($_POST['p_school_code']);
            // $p_lang=trim($_POST['p_lang']);
            
            // $query="SELECT u.user_no,u.user_id,u.email  FROM users u inner join objects_schools s on u.school_id=s.school_id WHERE username = '".$username."' and s.school_code='$p_school_code'";
            
            
            // echo "<script>alert(".$query.");</script>";die;exit;
            
            // $result = mysql_query($query) or die( $return = 'Error:-3> ' . mysql_error());
            // $row = mysql_fetch_assoc($result);
            // //print_r($row);die;
            
            // if(empty($row)){
    
            //     $return_data = array('status'=>false,'msg'=>'Username not exist');
                
            // } else {
                
            //     $user_no=$row['user_no'];
            //     $user_id=$row['user_id'];
            //     $email = $row['email'];
            //     $firstname = $row['firstname'];
                
            //     $update_query="UPDATE users u SET otp = '".$user_no."' WHERE user_no='".$user_no."' and exists (select 1 from objects_schools s where s.school_code='$p_school_code' and s.school_id=u.school_id)";
            //     $update_result = mysql_query($update_query) or die( $return = 'Error:-4> ' . mysql_error());
                
            //     //sending forgot password email after successful signed up
                
            //     //$urls = explode("/",$_SERVER['REQUEST_URI']);
            //     //$http_host=$_SERVER['SERVER_NAME']."/".$urls[1];
                
            //     $http_host=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/" ;
                
            //     $url=$http_host.$p_school_code."/forgot_password.php?action=reset_password&username=".urlencode(base64_encode($username))."&hxunid=".urlencode(base64_encode($user_no))."&hxschid=".urlencode(base64_encode($p_school_code));
     
            //     $qry="select ifnull(b.body_text,a.body_text ) body_text 	
            //     FROM email_template_default a left outer join email_template b 
            //     on a.template_code=b.template_code and a.language=b.language left outer join objects_schools s
            //     on b.school_id=s.school_id and s.school_code='$p_school_code'
            //     WHERE a.template_code='forgot_password_email' and a.language='$p_lang';";
            //     //echo $qry;die;
            //     $email_body=fetch_single_query_value($qry);
                
            //     $email_body = str_replace("[~~USER_NAME~~]",$firstname,$email_body);
            //     $email_body = str_replace("[~~URL~~]",$url,$email_body);
            //     $email_body = str_replace("[~~SCHOOL_CODE~~]",$p_school_code,$email_body);
            //     $email_body = str_replace("[~~HOSTNAME~~]",$http_host,$email_body);
            //     //print_r($email_body);die;
            //     $email_subject="Reset Password";
                
            //     $mail_status=SendGenericMail($username,'p_from_email',$email,'','',$email_subject,$email_body);           
                
            //     $return_data = array('status'=>true,'data'=>$row);
    
            // }

            $return = array('status'=>true,'data'=>$data);
            
            echo json_encode($return);  
        
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
