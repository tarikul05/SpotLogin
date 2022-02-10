<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use App\Models\Teacher;
use App\Models\Schooladmin;
use App\Models\Currency;


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
        print_r($data);
        exit();
        $school_type=trim($data['school_type']);
        $default_currency_code = '';
        if (!empty($data['country_id'])) {
            $currencyExists = Currency::where([
                ['country_id', $data['country_id']],
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
                'country_id' => $data['country_id'],
                'email'=>$data['email'],
                'sender_email'=>$data['email'],
                'max_students'=>0,
                'max_teachers'=>0,
                'school_type'=>$school_type
            ];
            
            $school = School::create($schoolData);
            $school->save();

            

            $schoolAdminData = [
                'school_id' => $school->id,
                'lastname' => '',
                'middlename'=>'',
                'firstname'=>$data['fullname'],
                'email'=>$data['email'],
                'country_id'=>$data['country_id'],
                'type'=>'SCHOOL_ADMIN',
                'has_user_account'=>1,
                'is_active' =>1
            ];

            $schoolAdmin = Schooladmin::create($schoolAdminData);
            $schoolAdmin->save();
            $usersData = [
                'person_id' => $schoolAdmin->id,
                'person_type' =>$schoolAdmin->type,
                'school_id' => $school->id,
                'username' =>$data['username'],
                'lastname' => '',
                'middlename'=>'',
                'firstname'=>$data['fullname'],
                'email'=>$data['email'],
                'password'=>$data['password'],
                'is_mail_sent'=>0,
                'is_active'=>1
            ];

            $user = User::create($usersData);
            $user->save();
        }
        else if($school_type=='COACH'){
            //'max_students'=>1,
            $coachData = [

            ];
            $teacher = Teacher::create($coachData);
            $teacher->save();
        }
        
        $result = array(
            'status' => 1,
            'message' => __('failed to login'),
        );

        

        
        //print_r($row);die;
        // if(empty($row)){

        //     $return_data = array('status'=>false,'data'=>'');
            
        // } else {
            
        //     $user_no=$row['user_no'];
        //     $user_id=$row['user_id'];
            
        //     if (trim($_SERVER['SERVER_NAME']) == 'sportlogin.ch'){
        //         $p_base_code="teamvg";  
        //      } else {
        //         $p_base_code="teamvg";
        //      }
             
        //      $softlink="ln -s /var/www/html/$p_base_code /var/www/html/$username";
        //      $status=exec($softlink);
 
        //      $softlink="mkdir -p /var/www/html/$p_base_code/medias/users/$username/thumb";
        //      $status=exec($softlink);
 
        //      $softlink="mkdir -p /var/www/html/$p_base_code/medias/schools/$username/thumb";
        //      $status=exec($softlink);
 
        //      $softlink="mkdir -p /var/www/html/$p_base_code/medias/schools/$username/pdf";
        //      $status=exec($softlink);
             
        //      // set permission
        //      $softlink="setfacl -Rm g:mescours:rwx /var/www/html/$p_base_code/medias/users";
        //      $status=exec($softlink);
             
        //      $softlink="setfacl -Rm g:mescours:rwx /var/www/html/$p_base_code/medias/schools";
        //      $status=exec($softlink);
            
        //     //sending activation email after successful signed up
            
        //     //$urls = explode("/",$_SERVER['REQUEST_URI']);
        //     //$http_host=$_SERVER['SERVER_NAME']."/".$urls[1];
        //     $http_host=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/" ;
            
        //     $url=$http_host.$username."/new_login_data.php?action=activate_account&username=".urlencode(base64_encode($username))."&hxunid=".urlencode(base64_encode($user_no));
 
        //     $qry="select body_text FROM email_template WHERE template_code='sign_up_confirmation_email' and language='en';";
        //     $email_body=fetch_single_query_value($qry);
            
		// 	$email_body = str_replace("[~~HOSTNAME~~]",$http_host,$email_body);
        //     $email_body = str_replace("[~~USER_NAME~~]",$username,$email_body);
        //     $email_body = str_replace("[~~URL~~]",$url,$email_body);
			
        //     //print_r($email_body);die;
        //     $email_subject="www.sportogin.ch: Welcome! Activate account.";
            
        //     $mail_status=SendGenericMail($username,'p_from_email',$email,'','',$email_subject,$email_body);           
            
        //     $return_data = array('status'=>true,'data'=>$row);

        // }
        
          
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
