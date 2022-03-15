<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\EventCategory;
use App\Models\Teacher;
use App\Models\School;
use App\Models\User;
use App\Models\Country;
use App\Models\SchoolTeacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class TeachersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($schoolId = null)
    {

        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return redirect()->route('schools')->with('error', __('School is not selected'));
            }
            $teachers = $school->teachers; 
        }else {
            $teachers = $user->getSelectedSchoolAttribute()->teachers;
        }
        // $teachers = Teacher::where('is_active', 1)->get();
        return view('pages.teachers.list',compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {  
        $countries = Country::active()->get();
        $genders = config('global.gender'); 
        $exTeacher = $searchEmail = null;
        if ($request->isMethod('post')){
            $searchEmail = $request->email;
            $exTeacher = User::where(['email'=> $searchEmail, 'person_type' =>'App\Models\Teacher' ])->first();
        }
        return view('pages.teachers.add')->with(compact('countries','genders','exTeacher','searchEmail'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function AddTeacher(Request $request)
    { 
        $schoolId = 1;
        DB::beginTransaction(); 
        try{
            if ($request->isMethod('post')){
                $alldata = $request->all();
                if (!empty($alldata['user_id'])) {
                    $relationalData = [
                        'role_type'=>$alldata['role_type'], 
                        'has_user_account'=> 1 ,
                        'comment'=> $alldata['comment'],
                        'nickname'=> $alldata['nickname'],
                    ];
                    $user = User::find($alldata['user_id']);
                    $teacher = $user->personable;
                    $exist = SchoolTeacher::where(['school_id' => $schoolId, 'teacher_id' => $teacher->id ])->first();
                    if (!$exist) {
                        $teacher->schools()->attach($schoolId,$relationalData);
                        $msg = 'Successfully Registered';
                    }else {
                        $msg = 'This teacher already exist with your school';
                    }
                    

                    // notify user by email about new Teacher role


                }else{
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

                    $msg = 'Successfully Registered';
                }

                    
               

                $result = array(
                    "status"     => 1,
                    'message' => __($msg)
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
    public function edit(Request $request, Teacher $teacher)
    {
        $relationalData = SchoolTeacher::where([
            ['teacher_id',$teacher->id],
            ['school_id',1]
        ])->first();

        
        $countries = Country::active()->get();
        $genders = config('global.gender');
        // dd($relationalData);
        return view('pages.teachers.edit')->with(compact('teacher','relationalData','countries','genders'));
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

    /**
     * Check users .
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkTeacherAccess(School $school)
    {
        // $user = Auth::user();

        // if ($user->isSuperAdmin() && empty(Teacher::find($schoolId))) {
            
        // }
    }
}
