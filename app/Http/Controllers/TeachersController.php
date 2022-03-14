<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\EventCategory;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::where('is_active', 1)->get();
        return view('pages.teachers.list',compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        
        return view('pages.teachers.add');
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function AddTeacher(Request $request)
    { 

        DB::beginTransaction();  
        try{
            if ($request->isMethod('post')){
                $alldata = $request->all();

                $birthDate=date('Y-m-d H:i:s',strtotime($alldata['birth_date']));
                $teacherData = [
                    'availability_select' => $alldata['availability_select'],
                    'gender_id' => $alldata['gender_id'],
                    'lastname' => $alldata['lastname'],
                    'firstname' => $alldata['firstname'],
                    'birth_date' => $birthDate,
                    'licence_js' => $alldata['licence_js'],
                    'email' => $alldata['email'],
                    'street' => $alldata['street'],
                    'street_number' => $alldata['street_number'],
                    'zip_code' => $alldata['zip_code'],
                    'place' => $alldata['place'],
                    'country_code' => $alldata['country_code'],
                    'phone' => $alldata['phone'],
                    'mobile' => $alldata['mobile'],
                ];
                // dd($alldata);
                $schoolId = 1;
                $teacher = Teacher::create($teacherData);
                $relationalData = [
                    'role_type'=>$alldata['role_type'], 
                    'has_user_account'=> isset($alldata['has_user_account'])? $alldata['has_user_account'] : null ,
                    'comment'=> $alldata['comment'],
                    'nickname'=> $alldata['nickname'],
                ];
                $teacher->save();
                $teacher->schools()->attach($schoolId,$relationalData);

                $usersData = [
                    'person_id' => $teacher->id,
                    'person_type' =>'App\Models\Teacher',
                    'username' =>Str::random(10),
                    'lastname' => $alldata['lastname'],
                    'middlename'=>'',
                    'firstname'=>$alldata['firstname'],
                    'email'=>$alldata['email'],
                    // 'password'=>$alldata['password'],
                    'password'=>Str::random(10),
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
            }
            DB::commit();
        }catch (Exception $e) {
            DB::rollBack();
            $result= [
                'status' => 0,
                'message' =>  __('Internal server error')
            ];
        }   

        
        return $result;
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
