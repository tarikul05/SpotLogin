<?php

namespace App\Http\Controllers;
use App\Models\VerifyToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\School;

class LessonsController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $schoolId = null)
    {
        // $user = Auth::user();
        // $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        // $school = School::active()->find($schoolId);
        // if (empty($school)) {
        //     return redirect()->route('schools')->with('error', __('School is not selected'));
        // }
        // $students = $school->students;    
        return view('pages.calendar.add_lesson')->with(compact('schoolId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentOff(Request $request, $schoolId = null)
    {
        // $user = Auth::user();
        // $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        // $school = School::active()->find($schoolId);
        // if (empty($school)) {
        //     return redirect()->route('schools')->with('error', __('School is not selected'));
        // }
        // $students = $school->students;    
        return view('pages.calendar.student_off')->with(compact('schoolId'));
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function coachOff(Request $request, $schoolId = null)
    {
        // $user = Auth::user();
        // $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        // $school = School::active()->find($schoolId);
        // if (empty($school)) {
        //     return redirect()->route('schools')->with('error', __('School is not selected'));
        // }
        // $students = $school->students;    
        return view('pages.calendar.coach_off')->with(compact('schoolId'));
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
