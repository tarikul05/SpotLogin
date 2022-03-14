<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\EventCategory;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;



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
                $teacherData = $request->all();
                // dd($teacherData);
                $birthDate=date('Y-m-d H:i:s',strtotime($teacherData['birth_date']));
                $teacherData['birth_date'] = $birthDate;
                $teacher = Teacher::create($teacherData);

                // $roleType = $teacherData == 
                // $teacher->save();
                // $teacher->schools()->attach(1, ['nickname' => 'this is nickname','role_type'=>$roleType, 'has_user_account'=> 1]);

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
