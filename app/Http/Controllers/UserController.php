<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use App\Models\Teacher;
use App\Models\Schooladmin;

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

        $school_type=trim($data['school_type']);

        if ($school_type=='SCHOOL') {
            $schoolData = [

            ];
            
            $school = School::create($schoolData);
            $school->save();

            $schoolAdminData = [

            ];

            $school = Schooladmin::create($schoolAdminData);
            $school->save();
        }
        else if($school_type=='COACH'){
            $coachData = [

            ];
            $teacher = Teacher::create($coachData);
            $teacher->save();
        }
        print_r($data);
        exit();
        $result = array(
            'status' => 1,
            'message' => __('failed to login'),
        );

        $user = User::create($data);
        $user->save();


        $userDataOrg = [
            'display' => $userData['attribute'],
            'email' => $userData['email'],
            'password' => $userData['password'],
            'profile_photo' => $newProfilePhotoName,
            'nickname' => $userData['nickname'],
            'self_introduction' => $userData['selfIntro'],
            'region_basic' => $userData['region'],
            'gender' => $userData['gender'],
            'birthday' => date($userData['birthYear'] .'-'. $userData['birthMonth'] .'-'. $userData['birthDay']),
            'company_name' => $userData['companyName'],
            'business_content' => $userData['businessContent'],
            'zipcode' => $userData['zipcode'],
            'prefectures' => $userData['prefecture'],
            'company_city' => $userData['city'],
            'company_address' => $userData['address'],
            'company_building_name' => $userData['buildingName'],
            'company_web_url' => $userData['companyURL'],       
        ];


        
		$fullname=trim($_POST['fullname']);
		$username=strtolower(trim($_POST['username']));
		$email=trim($_POST['email']);
		$country_id=trim($_POST['country_id']);
		$password=md5(trim($_POST['password']));
        $client_ip=$_SERVER['REMOTE_ADDR'];
        $client_device=$_SERVER['HTTP_USER_AGENT'];
        $client_location=trim($_POST['client_location']);
        
        
        $query = "CALL sign_up_proc('".$school_type."','".$fullname."','".$username."','".$email."','".$password."','".$country_id."','".$client_ip."','".$client_device."','".$client_location."')";
        $result = mysql_query($query) or die( $return = 'Error:-3> ' . mysql_error());
        $row = mysql_fetch_assoc($result);
        //print_r($row);die;
        if(empty($row)){

            $return_data = array('status'=>false,'data'=>'');
            
        } else {
            
            $user_no=$row['user_no'];
            $user_id=$row['user_id'];
            
            if (trim($_SERVER['SERVER_NAME']) == 'sportlogin.ch'){
                $p_base_code="teamvg";  
             } else {
                $p_base_code="teamvg";
             }
             
             $softlink="ln -s /var/www/html/$p_base_code /var/www/html/$username";
             $status=exec($softlink);
 
             $softlink="mkdir -p /var/www/html/$p_base_code/medias/users/$username/thumb";
             $status=exec($softlink);
 
             $softlink="mkdir -p /var/www/html/$p_base_code/medias/schools/$username/thumb";
             $status=exec($softlink);
 
             $softlink="mkdir -p /var/www/html/$p_base_code/medias/schools/$username/pdf";
             $status=exec($softlink);
             
             // set permission
             $softlink="setfacl -Rm g:mescours:rwx /var/www/html/$p_base_code/medias/users";
             $status=exec($softlink);
             
             $softlink="setfacl -Rm g:mescours:rwx /var/www/html/$p_base_code/medias/schools";
             $status=exec($softlink);
            
            //sending activation email after successful signed up
            
            //$urls = explode("/",$_SERVER['REQUEST_URI']);
            //$http_host=$_SERVER['SERVER_NAME']."/".$urls[1];
            $http_host=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/" ;
            
            $url=$http_host.$username."/new_login_data.php?action=activate_account&username=".urlencode(base64_encode($username))."&hxunid=".urlencode(base64_encode($user_no));
 
            $qry="select body_text FROM email_template WHERE template_code='sign_up_confirmation_email' and language='en';";
            $email_body=fetch_single_query_value($qry);
            
			$email_body = str_replace("[~~HOSTNAME~~]",$http_host,$email_body);
            $email_body = str_replace("[~~USER_NAME~~]",$username,$email_body);
            $email_body = str_replace("[~~URL~~]",$url,$email_body);
			
            //print_r($email_body);die;
            $email_subject="www.sportogin.ch: Welcome! Activate account.";
            
            $mail_status=SendGenericMail($username,'p_from_email',$email,'','',$email_subject,$email_body);           
            
            $return_data = array('status'=>true,'data'=>$row);

        }
        
        echo json_encode($return_data);  
        if ($data['type'] === "validate_username") {
            $p_username=trim($_POST['p_username']);
            $field = 'username';
            $user = User::getUserData($field, $p_username);
            $user_exist = 0;
            $result['message']=__('username is ok!');
            if ($user) {
                $user_exist = 1;
                $result['cnt']=1;
                $result['message']=__('username already exist');
            }
            return response()->json($result);
        }
        else if ($data['type'] === 'login_submit') { 

            $username = $data['login_username'];
            $field = 'username';
            $user = User::getUserData($field, $username);
            //         $result = array(
            //             "status"     => 0,
            //             'message' => _('Successfully logged in'),
            //             "user_id"  => $user['id'],
            //             "user_name" => $user['username'],
            //             "user_role"  => $user['person_type'],
            //             "school_code"  => isset($user['related_school']) ? $user['related_school']['school_code'] : null,                                
            //             "email"  => $user['email'],
            //             "school_id"  => isset($user['related_school']) ? $user['related_school']['id'] : null,  
            //             "v_t_cnt"  => isset($user['related_school']) ? $user['related_school']['max_teachers'] : null,  
            //             "v_s_cnt"  =>isset($user['related_school']) ? $user['related_school']['max_students'] : null,
            //             //"tc_accepted_flag"  => $row['tc_accepted_flag'],
            //             "country_id"  => isset($user['teacher']) ? $user['teacher']['country_id'] : null,
            //             "person_id"  => $user['person_id'],
            //             "http_host" => $http_host
            //         );
            // $user = User::getUserDataDetails($field, $username);
            // print_r($user);
            // exit();
            
            if ($user) {
                if(Auth::attempt(['username' => $data['login_username'], 'password' => $data['login_password']], $request->filled('remember'))){
                
                    // Auth::login($user);
                    $user = Auth::user();
                    $country_id = null;
                    if (isset($user->teacher)) {
                        $country_id = $user->teacher['country_id'];
                    }
                    else if (isset($user->student)) {
                        $country_id = $user->student['country_id'];
                    }
                    else if (isset($user->parent)) {
                        $country_id = $user->parent['country_id'];
                    }
                    else if (isset($user->coach)) {
                        $country_id = $user->coach['country_id'];
                    }
                    else if (isset($user->schooladmin)) {
                        $country_id = $user->schooladmin['country_id'];
                    }

                    $result = array(
                        "status"     => 0,
                        'message' => __('Successfully logged in'),
                        "user_id"  => $user['id'],
                        "user_name" => $user['username'],
                        "user_role"  => $user['person_type'],
                        "email"  => $user['email'],
                        "country_id"  => $country_id,
                        "person_id"  => $user['person_id']
                    );
                    return response()->json($result);
                }
            }
            
        }
        return response()->json($result);
        
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
