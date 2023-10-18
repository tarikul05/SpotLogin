<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\Student;
use App\Models\School;
use App\Models\SchoolStudent;
use App\Models\User;
use App\Models\Country;
use App\Models\Level;
use App\Models\InvoicesTaxes;
use App\Models\Invoice;
use App\Models\Province;
use App\Models\EmailTemplate;
use App\Models\SchoolTeacher;
use App\Models\VerifyToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use App\Http\Requests\ProfilePhotoUpdateRequest;
use Illuminate\Support\Facades\URL;
use App\Models\AttachedFile;
use Illuminate\Support\Facades\Storage;
use App\Mail\SportloginEmail;


use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;



class StudentsController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('permission:students-list|students-create|students-update|students-view|students-users-update|students-delete', ['only' => ['index']]);
        $this->middleware('permission:students-create', ['only' => ['create','AddTeacher']]);
        $this->middleware('permission:students-view|students-update', ['only' => ['edit']]);
        $this->middleware('permission:students-update', ['only' => ['update']]);
        $this->middleware('permission:students-users-update', ['only' => ['teacherEmailSend','userUpdate']]);
        $this->middleware('permission:students-delete', ['only' => ['destroy']]);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($schoolId = null)
    {

        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ;
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $students = $school->students;
        return view('pages.students.list',compact('students','schoolId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $schoolId = null)
    {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return redirect()->route('schools')->with('error', __('School is not selected'));
            }
            $schoolId = $school->id;
        }else {
            $schoolId = $user->selectedSchoolId();
            $school = School::active()->find($schoolId);
        }

        $countries = Country::active()->get();
        $levels = Level::active()->where('school_id',$schoolId)->get();
        $genders = config('global.gender');
        $provinces = Province::active()->get()->toArray();
        $exStudent = $exUser = $searchEmail = null;
        if ($request->isMethod('post')){
            $searchEmail = $request->email;
            $exUser = User::where(['email'=> $searchEmail, 'person_type' =>'App\Models\Student' ])->first();
            // $exStudent = !empty($exUser) ? $exUser->personable : null;
            $exStudent = Student::where(['email'=> $searchEmail])->first();
            $alreadyFlag =null;
            if ($exStudent) {
                $alreadyFlag = SchoolStudent::where(['school_id' => $schoolId, 'student_id' => $exStudent->id ])->first();
                if ($alreadyFlag) {
                    return back()->with('warning', __('This user already have in your school with "'.$searchEmail.'" email'));
                }
            }
            // echo "<pre>";
            // print_r($exStudent); exit;
        }

        return view('pages.students.add')->with(compact('countries','genders','exUser','exStudent','searchEmail','schoolId','levels','provinces','school'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function AddStudent(Request $request, $schoolId = null)
    {

        $user = Auth::user();
        $alldata = $request->all();

        $schoolId = $alldata['school_id'];
        $school = School::active()->find($schoolId);
        if ($user->isSuperAdmin()) {
            if (empty($school)) {
                return [
                    'status' => 1,
                    'message' =>  __('School not selected')
                ];
            }
            $schoolId = $school->id;
            $schoolName = $school->school_name;
            $schoolEmail = $school->email;
        }else {
            $schoolId = $user->selectedSchoolId();
            $schoolName = $user->selectedSchoolName();
            $schoolEmail = $school->email;
            $school = School::active()->find($schoolId);
            $schoolId = $school->id;
            $schoolName = $school->school_name;
        }
        DB::beginTransaction();
        try{
            //dd($alldata);
            if (!empty($alldata['email'])) {
                $student = Student::where('email', $alldata['email'])
                    ->where('firstname', $alldata['firstname'])
                    ->where('lastname', $alldata['lastname'])
                    ->first();
            }else {
                $student = false;
            }

            $sentInvite = isset($alldata['is_sent_invite']) ? $alldata['is_sent_invite'] : 0 ;

            $authUser = $request->user();
            if ($request->isMethod('post')){

                $studentData = [
                    'gender_id' => $alldata['gender_id'],
                    'lastname' => $alldata['lastname'],
                    'firstname' => $alldata['firstname'],
                    'birth_date' => date('Y-m-d H:i:s',strtotime($this->sdateFormat($alldata['birth_date']))),
                    'street' => $alldata['street'],
                    'street_number' => $alldata['street_number'],
                    // 'street2' => $alldata['street2'],
                    'zip_code' => $alldata['zip_code'],
                    'place' => $alldata['place'],
                    'country_code' => $alldata['country_code'],
                    'province_id' => isset($alldata['province_id']) ? $alldata['province_id'] : null,
                    'billing_street' => $alldata['billing_street'],
                    // 'billing_street2' => $alldata['billing_street2'],
                    'billing_street_number' => $alldata['billing_street_number'],
                    'billing_zip_code' => $alldata['billing_zip_code'],
                    'billing_place' => $alldata['billing_place'],
                    'billing_country_code' => $alldata['billing_country_code'],
                    'billing_province_id' => isset($alldata['billing_province_id']) ? $alldata['billing_province_id']: null,
                    // 'phone' => $alldata['phone'],
                    'father_phone' => isset($alldata['father_phone']) ? $alldata['father_phone'] : '',
                    'father_email' => isset($alldata['father_email']) ? $alldata['father_email'] : '',
                    'father_notify' => isset($alldata['father_notify']) && !empty($alldata['father_notify']) ? 1 : 0 ,
                    'mother_phone' => isset($alldata['mother_phone']) ? $alldata['mother_phone'] : '',
                    'mother_email' => isset($alldata['mother_email']) ? $alldata['mother_email'] : '',
                    'mother_notify' => isset($alldata['mother_notify']) && !empty($alldata['mother_notify']) ? 1 : 0 ,
                    'mobile' => $alldata['mobile'],
                    'email' => $alldata['email'],
                    'email2' => $alldata['email2'],
                    'student_notify' => isset($alldata['student_notify']) && !empty($alldata['student_notify']) ? 1 : 0 ,
                ];

                $schoolStudent = [
                    'school_id' => $schoolId,
                    'has_user_account' => isset($alldata['has_user_account']) && !empty($alldata['has_user_account']) ? $alldata['has_user_account'] : null,
                    'nickname' => $alldata['nickname'],
                    'email' => $alldata['email'],
                    'is_sent_invite' => $sentInvite,
                    'billing_method' => $alldata['billing_method'],
                    'level_id' => isset($alldata['level_id']) && !empty($alldata['level_id']) ? $alldata['level_id'] : null ,
                    'level_date_arp' => isset($alldata['level_date_arp']) && !empty($alldata['level_date_arp']) ? date('Y-m-d H:i:s',strtotime($alldata['level_date_arp'])) : null ,
                    'licence_arp' => isset($alldata['licence_arp']) && !empty($alldata['licence_arp']) ? $alldata['licence_arp'] : null ,
                    'licence_usp' => $alldata['licence_usp'],
                    'level_skating_usp' => isset($alldata['level_skating_usp']) && !empty($alldata['level_skating_usp']) ? $alldata['level_skating_usp'] : null ,
                    'level_date_usp' => isset($alldata['level_date_usp']) && !empty($alldata['level_date_usp']) ? date('Y-m-d H:i:s',strtotime($alldata['level_date_usp'])) : null ,
                    'comment' => isset($alldata['comment']) ? $alldata['comment'] : '',
                ];

                if (!empty($student)) { // Student already exist in student table but not in this school
                    $exist = SchoolStudent::where(['school_id' => $schoolId, 'student_id' => $student->id ])->first();

                    if (!$exist) {

                        $student->schools()->attach($schoolId,$schoolStudent);
                        $msg = 'Successfully Registered';
                    } else {
                        return redirect()->back()->withInput($request->all())->with('error', __('This Student already exist with your school'));
                    }

                }else{ // studne and school_student data not exist

                    if($request->file('profile_image_file')){
                        try {
                            $image = $request->file('profile_image_file');
                            if($image->getSize()>0)
                            {
                                $mime_type = $image->getMimeType();
                                $extension = $image->getClientOriginalExtension();
                                list($path, $imageNewName) = $this->__processImg($image,'StudentImage',$authUser);
                                if (!empty($path)) {
                                    $fileData = [
                                        'visibility' => 1,
                                        'file_type' =>'image',
                                        'title' => $authUser->username,
                                        'path_name' =>$path,
                                        'file_name' => $imageNewName,
                                        'extension'=>$extension,
                                        'mime_type'=>$mime_type
                                    ];
                                    $attachedImage = AttachedFile::create($fileData);
                                    $studentData['profile_image_id'] = $attachedImage->id;
                                }
                            }
                        } catch (\Exception $e) {
                            $studentData['profile_image_id'] =null;
                        }
                    }
                    $student = Student::create($studentData);

                    $student->schools()->attach($schoolId,$schoolStudent);

                }

                // sent email for new studnet
                if (config('global.email_send') == 1 && ($sentInvite == "1")) {
                    $data = [];
                    $data['email'] = $alldata['email'];
                    $data['username'] = $data['name'] = $alldata['nickname'];
                    $data['school_name'] = $schoolName;
                    $data['admin_email_from'] = $schoolEmail;
                    $data['admin_email_from_name'] = $schoolName;
                    $verifyUser = [
                        'school_id' => $schoolId,
                        'person_id' => $student->id,
                        'person_type' => 'App\Models\Student',
                        'token' => $alldata['_token'],
                        'token_type' => 'VERIFY_SIGNUP',
                        'expire_date' => Carbon::now()->addDays(config('global.token_validity'))->format("Y-m-d")
                    ];
                    $verifyUser = VerifyToken::create($verifyUser);
                    $data['token'] = $verifyUser->token;
                    $data['url'] = route('add.verify.email',$data['token']);

                    if ($this->emailSend($data,'sign_up_confirmation_email')) {
                        $msg = __('We sent you an activation link. Check your email and click on the link to verify.');
                    }  else {
                        return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
                    }
                }


            }
            DB::commit();
            #return back()->withInput($request->all())->with('success', __('Student added successfully!'));
            if ($user->isSuperAdmin()) {
                return redirect(route('adminStudents',$schoolId))->with('success', __('Student added successfully!'));
            }else{
                //return redirect(route('studentHome'))->with('success', __('Student added successfully!'));
                return redirect()->route('editStudent',['student' => $student->id])->with('success', __('Student added successfully!'));
            }

        }catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }


        return $result;
    }

         /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editStudentAction(Request $request, Student $student)
    {

        $authUser = $user = Auth::user();
        // $authUser = $request->user();
        $alldata = $request->all();
        // dd($student);

        if ($user->isSuperAdmin()) {
            $schoolId = $alldata['school_id'];
        }else {
            $schoolId = $user->selectedSchoolId();
        }

        DB::beginTransaction();
        try{
            if ($request->isMethod('post')){
                $studentData = [
                    // 'is_active' => isset($alldata['is_active']) ? $alldata['is_active'] : $student->is_active,
                    'gender_id' => $alldata['gender_id'],
                    'lastname' => $alldata['lastname'],
                    'firstname' => $alldata['firstname'],
                    'birth_date' => date('Y-m-d H:i:s',strtotime($this->sdateFormat($alldata['birth_date']))),
                    'street' => $alldata['street'],
                    'street_number' => $alldata['street_number'],
                    // 'street2' => $alldata['street2'],
                    'zip_code' => $alldata['zip_code'],
                    'place' => $alldata['place'],
                    'country_code' => $alldata['country_code'],
                    'province_id' => isset($alldata['province_id']) ? $alldata['province_id']: null,
                    'billing_street' => $alldata['billing_street'],
                    // 'billing_street2' => $alldata['billing_street2'],
                    'billing_street_number' => $alldata['billing_street_number'],
                    'billing_zip_code' => $alldata['billing_zip_code'],
                    'billing_place' => $alldata['billing_place'],
                    'parent_name_1' => $alldata['parent_name_1'],
                    'parent_name_2' => $alldata['parent_name_2'],
                    'billing_country_code' => $alldata['billing_country_code'],
                    'billing_province_id' => isset($alldata['billing_province_id']) ? $alldata['billing_province_id']: null,
                    'father_phone' => $alldata['father_phone'],
                    'father_email' => $alldata['father_email'],
                    'father_notify' => isset($alldata['father_notify']) && !empty($alldata['father_notify']) ? 1 : 0 ,
                    'mother_phone' => $alldata['mother_phone'],
                    'mother_email' => $alldata['mother_email'],
                    'mother_notify' => isset($alldata['mother_notify']) && !empty($alldata['mother_notify']) ? 1 : 0 ,
                    'mobile' => $alldata['mobile'],
                    'email' => $alldata['email'],
                    'email2' => $alldata['email2'],
                    'student_notify' => isset($alldata['student_notify']) && !empty($alldata['student_notify']) ? 1 : 0 ,
                ];
                if($request->file('profile_image_file'))
                {
                    try {
                        $image = $request->file('profile_image_file');

                        if($image->getSize()>0)
                        {
                            $mime_type = $image->getMimeType();
                            $extension = $image->getClientOriginalExtension();
                            list($path, $imageNewName) = $this->__processImg($image,'StudentImage',$authUser);

                            if (!empty($path)) {
                            $fileData = [
                                'visibility' => 1,
                                'file_type' =>'image',
                                'title' => $authUser->username,
                                'path_name' =>$path,
                                'file_name' => $imageNewName,
                                'extension'=>$extension,
                                'mime_type'=>$mime_type
                            ];

                            $attachedImage = AttachedFile::create($fileData);
                            $studentData['profile_image_id'] = $attachedImage->id;

                            }
                        }
                    } catch (\Exception $e) {
                        $studentData['profile_image_id'] =null;
                    }
                }

                Student::where('id', $student->id)->update($studentData);
                $schoolStudentData =SchoolStudent::where(['student_id'=>$student->id, 'school_id'=>$schoolId])->first();
                $schoolStudent = [
                    'student_id' => $student->id,
                    'school_id' => $schoolId,
                    // 'has_user_account' => !empty($alldata['has_user_account']) ? $alldata['has_user_account'] : null,
                    'nickname' => $alldata['nickname'],
                    'email' => $alldata['email'],
                    'billing_method' => $alldata['billing_method'],
                    'level_id' => $alldata['level_id'],
                    'level_date_arp' => isset($alldata['level_date_arp']) && !empty($alldata['level_date_arp']) ? date('Y-m-d H:i:s',strtotime($alldata['level_date_arp'])) : null ,
                    'licence_arp' => isset($alldata['licence_arp']) && !empty($alldata['licence_arp']) ? $alldata['licence_arp'] : null ,
                    'licence_usp' => $alldata['licence_usp'],
                    'level_skating_usp' => isset($alldata['level_skating_usp']) && !empty($alldata['level_skating_usp']) ? $alldata['level_skating_usp'] : null ,
                    'level_date_usp' => isset($alldata['level_date_usp']) && !empty($alldata['level_date_usp']) ? date('Y-m-d H:i:s',strtotime($alldata['level_date_usp'])) : null ,
                    'comment' => isset($alldata['comment']) ? $alldata['comment'] : $schoolStudentData->comment,
                    'is_active' => isset($alldata['is_active']) ? $alldata['is_active'] : $schoolStudentData->is_active,
                ];

                SchoolStudent::where(['student_id'=>$student->id, 'school_id'=>$schoolId])->update($schoolStudent);
            }
            DB::commit();
            return redirect()->back()->with('vtab', isset($alldata['active_tab']) && !empty($alldata['active_tab']) ? $alldata['active_tab'] : 'tab_1')->with('success', __('Student updated successfully!'));
        }catch (Exception $e) {
            // dd($e);
            DB::rollBack();
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
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
    public function edit(Request $request)
    {
        $user = Auth::user();
        $alldata = $request->all();
        $schoolId = $request->route('school');
        $studentId = $request->route('student');
        $provinces = Province::active()->get()->toArray();

        $student = Student::find($studentId);

        if ($user->isSuperAdmin()) {
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return redirect()->route('schools')->with('error', __('School is not selected'));
            }
            // $schoolId = $school->id;
            // $schoolName = $school->school_name;
        }else {
            $school = $user->getSelectedSchoolAttribute();
            // $schoolId = $user->selectedSchoolId();
            // $schoolName = $user->selectedSchoolName();
        }

        $schoolId = $school->id;
        $schoolName = $school->school_name;

        $relationalData = SchoolStudent::where([
            ['student_id',$studentId],
            ['school_id',$schoolId]
        ])->first();
        //dd($relationalData);

        $lanCode = 'en';
        if (Session::has('locale')) {
            $lanCode = Session::get('locale');
        }
        $emailTemplate = EmailTemplate::where([
            ['template_code', 'student'],
            ['language', $lanCode]
        ])->first();

        if ($emailTemplate) {
            $http_host=$this->BASE_URL."/";
            if (!empty($emailTemplate->body_text)) {
                $emailTemplate->body_text = str_replace("[~~ HOSTNAME ~~]",$http_host,$emailTemplate->body_text);
                $emailTemplate->body_text = str_replace("[~~HOSTNAME~~]",$http_host,$emailTemplate->body_text);
            }
        }

        $profile_image = !empty($student->profile_image_id) ? AttachedFile::find($student->profile_image_id) : null ;
        $countries = Country::active()->get();
        $levels = Level::active()->where('school_id',$schoolId)->get();
        $genders = config('global.gender');

        $RegisterTaxData = InvoicesTaxes::active()->where(['invoice_id'=> null, 'created_by' => $user->id])->get();

        return view('pages.students.edit')->with(compact('emailTemplate','countries','genders','student','relationalData','profile_image','schoolId','levels','schoolName','provinces','school', 'RegisterTaxData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $schoolId = $request->route('school');
        $studentId = $request->route('student');
        SchoolStudent::where(['school_id'=>$schoolId, 'student_id'=>$studentId])->delete();
        return redirect()->back()
            ->with('success', 'Deleted successfully');
    }


    /**
     *  AJAX action to send email to school admin
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-10
     */
    public function destroyStudent(Request $request)
    {
        $user = $request->user();
        $schoolId = $request->route('school');
        $studentId = $request->route('student');

        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $invoice_type_all = config('global.invoice_type');
        $payment_status_all = config('global.payment_status');
        $invoice_status_all = config('global.invoice_status');

        $timezoneInvoiceList = $school->timezone;

        $query = new Invoice;
        $allEvents = $query->getStudentInvoiceList($user,$schoolId,'teacher','S',$timezoneInvoiceList);

        $isFuturInvoice = false;
        foreach ($allEvents as $key => $value) {
            if($value->person_id == $studentId) {
                $isFuturInvoice = true;
            }
        }

        if(!$isFuturInvoice) {
            SchoolStudent::where(['school_id'=>$schoolId, 'student_id'=>$studentId])->delete();
            $result = array(
                "status"     => 'success',
                'message' => __('Successfully deleted student')
              );
        } else {
            $result = array(
                "status"     => 'error',
                "isFuturInvoice" => $isFuturInvoice,
                'message' => __('Successfully deleted student')
              );
        }

          return response()->json($result);
    }


    public function delete(Request $request)
    {
        $user = $request->user();
        $schoolId = $request->input('schoolId');

        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        $invoice_type_all = config('global.invoice_type');
        $payment_status_all = config('global.payment_status');
        $invoice_status_all = config('global.invoice_status');

        $timezoneInvoiceList = $school->timezone;

        $query = new Invoice;
        $allEvents = $query->getStudentInvoiceList($user,$schoolId,'teacher','S',$timezoneInvoiceList);

        $selectedStudents = $request->input('selected_students', []);

        if (empty($selectedStudents)) {
            return redirect()->back()->with('error', 'Please select at least 1 student to delete.');
        }

        $someStudentLocked = false;
        $lockedStudent = [];

        foreach ($selectedStudents as $studentId) {
            $filteredEvents = $allEvents->filter(function ($event) use ($studentId) {
                return $event->person_id == $studentId;
            });
            if ($filteredEvents->isNotEmpty()) {
                $someStudentLocked = true;
                $lockedStudent[] = $studentId;
                $selectedStudents = array_diff($selectedStudents, [$studentId]);
            }
        }

        //dd($someStudentLocked);

        try {
            DB::beginTransaction();

            SchoolStudent::whereIn('student_id', $selectedStudents)->delete();

            Student::whereIn('id', $selectedStudents)->delete();

            DB::commit();
            if ($someStudentLocked) {
                $redirect = redirect()->back()->with('warning', 'Some students have been deleted but one or more students are locked with lessons to be invoiced.');
                return $redirect->with('locked', true)->with('lockedStudent', $lockedStudent);
            } else {
                $redirect = redirect()->back()->with('success', 'The selected students have been deleted.');
                return $redirect->with('locked', false)->with('lockedStudent', null);
            }

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'An error occurred while deleting students.');
        }
    }

    /**
     * change status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $schoolId = $request->route('school');
        $studentId = $request->route('student');
        $alldata = $request->all();
        $status = isset($alldata['status']) && ($alldata['status'] == 1 ) ? 0 : 1 ;
        // dd($schoolId,$studentId);
        SchoolStudent::where(['school_id'=>$schoolId, 'student_id'=>$studentId])->update(['is_active'=>$status]);
        return redirect()->back()
            ->with('success', 'status updated successfully');
    }

    /**
     * send invitation.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function studentInvitation(Request $request)
    {
        $schoolId = $request->route('school');
        $studentId = $request->route('student');
        try {
            $schoolStudent = SchoolStudent::where(['school_id'=>$schoolId, 'student_id'=>$studentId])->first();
            //->update(['is_sent_invite'=>$is_sent_invite]);

            $school = School::find($schoolId);
            $student = Student::find($studentId);
            if ($student && !empty($student->email)) {
                $this->emailSet($school, $schoolStudent, $student, 'App\Models\Student');
                return redirect()->back()->with('success', 'Invitation sent successfully');
            }else{
                return redirect()->back()->with('error', __('Email not found'));
            }

        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
    }

    /**
     * send invitation.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function studentInvitationGet(Request $request)
    {
        $schoolId = $request->route('school');
        $studentId = $request->route('student');
        try {
            $schoolStudent = SchoolStudent::where(['school_id'=>$schoolId, 'student_id'=>$studentId])->first();
            //->update(['is_sent_invite'=>$is_sent_invite]);

            $school = School::find($schoolId);
            $student = Student::find($studentId);
            if ($student && !empty($student->email)) {
                $this->emailSet($school, $schoolStudent, $student, 'App\Models\Student');
                return redirect()->back()->with('success', 'Invitation sent successfully');
            }else{
                return redirect()->back()->with('error', __('Email not found'));
            }

        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
    }

    /**
     * send invitation.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function studentPasswordGet(Request $request)
    {
        $schoolId = $request->route('school');
        $studentId = $request->route('student');
        try {
            $schoolStudent = SchoolStudent::where(['school_id'=>$schoolId, 'student_id'=>$studentId])->first();
            //->update(['is_sent_invite'=>$is_sent_invite]);

            $school = School::find($schoolId);
            $student = Student::find($studentId);
            if ($student && !empty($student->email)) {
                $this->passwordSet($school, $schoolStudent, $student, 'App\Models\Student');
                return redirect()->back()->with('success', 'Invitation sent successfully');
            }else{
                return redirect()->back()->with('error', __('Email not found'));
            }

        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
    }



    public function emailSet($school, $alldata, $person, $type = 'App\Models\Student')
    {
        //sending activation email after successful signed up
        try {
            $schoolId = $school->id;
            if (config('global.email_send') == 1) {
                $data = [];
                $data['email'] = $person->email;
                $data['username'] = $alldata->nickname;
                $data['school_name'] = $school->school_name;
                $verifyUser = [
                    'school_id' => $schoolId,
                    'person_id' => $person->id,
                    'person_type' => $type,
                    'token' => Str::random(10),
                    'token_type' => 'VERIFY_SIGNUP',
                    'expire_date' => Carbon::now()->addDays(config('global.token_validity'))->format("Y-m-d")
                ];
                $verifyUser = VerifyToken::create($verifyUser);
                $data['token'] = $verifyUser->token;
                $data['url'] = route('add.verify.email', $data['token']);
                $data['admin_email_from'] = $school->email;
                $data['admin_email_from_name'] = $school->school_name;

                if ($this->emailSend($data, 'sign_up_confirmation_email')) {
                    $data = [];
                    $data['is_sent_invite'] = 1;
                    $alldata->update($data);

                    //$msg = __('We sent you an activation link. Check your email and click on the link to verify.');
                } else {
                    return false;
                }
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function passwordSet($school, $alldata, $person, $type = 'App\Models\Student')
    {
        //sending activation email after successful signed up
        try {
            $schoolId = $school->id;
            if (config('global.email_send') == 1) {
                $data = [];
                $data['email'] = $person->email;
                $data['username'] = $alldata->nickname;
                $data['school_name'] = $school->school_name;
                $verifyUser = [
                    'school_id' => $schoolId,
                    'person_id' => $person->id,
                    'person_type' => $type,
                    'token' => Str::random(10),
                    'token_type' => 'VERIFY_RESET_PASSWORD',
                    'expire_date' => Carbon::now()->addDays(config('global.token_validity'))->format("Y-m-d")
                ];
                $verifyUser = VerifyToken::create($verifyUser);
                $data['token'] = $verifyUser->token;
                $data['url'] = route('reset_password.email', $data['token']);

                if ($this->emailSend($data, 'forgot_password_email')) {
                    $data = [];
                    $data['is_sent_invite'] = 1;
                    $alldata->update($data);

                    //$msg = __('We sent you an activation link. Check your email and click on the link to verify.');
                } else {
                    return false;
                }
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
    *  AJAX action image delete and unlink
    *
    * @return json
    * @author Mamun <lemonpstu09@gmail.com>
    * @version 0.1 written in 2022-03-10
    */
    public function profilePhotoDelete(Request $request)
    {
        $authUser = $request->user();
        $data = $request->all();
        $user = User::find($data['user_id']);
        $result = array(
          'status' => 'failed',
          'message' => __('failed to remove image'),
        );
        try{
          $path_name =  $user->profileImage->path_name;
          $file = str_replace(URL::to('').'/uploads/','',$path_name);

          $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
          if(file_exists($storagePath.$file)) unlink($storagePath.$file);
          AttachedFile::find($user->profileImage->id)->delete();
          $data['profile_image_id'] =null;
          if ($user->update($data)) {
            $result = array(
              "status"     => 'success',
              'message' => __('Successfully Changed Profile image')
            );
          }
        }
        catch (\Exception $e) {
          //return error message
          $result['message'] = __('Internal server error');
        }
        return response()->json($result);
    }



      /**
     * Update the student user account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  School $school
     * @return \Illuminate\Http\Response
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-28
    */
    public function userUpdate(Request $request, User $user)
    {
        $params = $request->all();
        try{
            $request->merge([
                'email'=> $params['admin_email'],
                'is_active'=> $params['admin_is_active']
            ]);


            $user = User::find($params['user_id']);
            if ($user) {
                $request->merge(['username'=> $params['admin_username']]);
                $user->update($request->except(['_token']));

                if (!empty($params['admin_password'])) {
                    $user->password = $params['admin_password'];
                    $user->save();
                }
            } else{
                return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
            }

            return back()->withInput($request->all())->with('success', __('Student account updated successfully!'));
        } catch (\Exception $e) {
            //return error message
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
    }


    /**
     *  AJAX action to send email to school admin
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-10
     */
    public function studentEmailSend(Request $request)
    {
        $result = array(
            'status' => false,
            'message' => __('failed to send email'),
        );
        try {
            $data = $request->all();
            $user = User::find($data['user_id']);
            if ($user) {
                //sending email for forgot password
                if (config('global.email_send') == 1) {
                    try {
                        $data['email'] = $data['email_to_id'];
                        $data['name'] = $user->username;
                        $data['username'] = $user->username;
                        if (!empty($data['admin_password'])) {
                            $data['password'] = $data['admin_password'];
                        } else {
                            $data['password'] = config('global.user_default_password');
                        }
                        $data['subject'] = $data['subject_text'];
                        $data['body_text'] = $data['email_body'];
                        if ($this->emailSendWithoutTemplate($data,$user->email)) {
                            $result = array(
                                'status' => true,
                                'message' => __('We sent an email.'),
                            );
                        }  else {
                            return $result = array(
                                "status"     => false,
                                'message' =>  __('Internal server error')
                            );
                        }
                        return response()->json($result);
                    } catch (\Exception $e) {
                        $result = array(
                            'status' => true,
                            'message' => __('We sent an email.'),
                        );
                        return response()->json($result);
                    }
                } else{
                    $result = array('status'=>true,'msg'=>__('We sent an email.'));
                }
            }   else {
                $result = array('status'=>false,'msg'=>__('Username not exist'));
            }

            return response()->json($result);

        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit(Request $request, $schoolId = null, Teacher $teacher)
    public function self_edit(Request $request)
    {
        $user = Auth::user();
        $schoolId = $request->route('school');

        $student = Student::find($user->person_id);
        $schoolId = $user->selectedSchoolId();
        $schoolName = $user->selectedSchoolName();
        $school = School::active()->find($schoolId);

        $provinces = Province::active()->get()->toArray();
        $countries = Country::active()->get();
        $genders = config('global.gender');


        $relationalData = SchoolStudent::where([
            ['student_id',$student->id],
            ['school_id',$schoolId]
        ])->first();
        $lanCode = 'en';
        if (Session::has('locale')) {
            $lanCode = Session::get('locale');
        }
        $emailTemplate = EmailTemplate::where([
            ['template_code', 'student'],
            ['language', $lanCode]
        ])->first();
        if ($emailTemplate) {
            $http_host=$this->BASE_URL."/";
            if (!empty($emailTemplate->body_text)) {
                $emailTemplate->body_text = str_replace("[~~ HOSTNAME ~~]",$http_host,$emailTemplate->body_text);
                $emailTemplate->body_text = str_replace("[~~HOSTNAME~~]",$http_host,$emailTemplate->body_text);
            }
        }
        $profile_image = !empty($student->profile_image_id) ? AttachedFile::find($student->profile_image_id) : null ;
        $countries = Country::active()->get();
        $levels = Level::active()->where('school_id',$schoolId)->get();
        $genders = config('global.gender');

        // dd($student);
        return view('pages.students.self_edit')->with(compact('levels',
        'emailTemplate','countries','genders','student','relationalData','profile_image','schoolId','schoolName','provinces','school'));
    }


     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function self_update(Request $request, Student $student)
    {
        $user = Auth::user();
        $alldata = $request->all();

        $student = Student::find($user->person_id);
        $schoolId = $user->selectedSchoolId();

        DB::beginTransaction();
        try{

            $birthDate=date('Y-m-d H:i:s',strtotime($alldata['birth_date']));
            $studentData = [
                    // 'gender_id' => $alldata['gender_id'],
                    'lastname' => $alldata['lastname'],
                    'firstname' => $alldata['firstname'],
                    'birth_date' => date('Y-m-d H:i:s',strtotime($this->sdateFormat($alldata['birth_date']))),
                    'street' => $alldata['street'],
                    'street_number' => $alldata['street_number'],
                    // 'street2' => $alldata['street2'],
                    'zip_code' => $alldata['zip_code'],
                    'place' => $alldata['place'],
                    'country_code' => $alldata['country_code'],
                    'province_id' => isset($alldata['province_id']) ? $alldata['province_id'] : null,
                    'billing_street' => $alldata['billing_street'],
                    // 'billing_street2' => $alldata['billing_street2'],
                    'billing_street_number' => $alldata['billing_street_number'],
                    'billing_zip_code' => $alldata['billing_zip_code'],
                    'billing_place' => $alldata['billing_place'],
                    'billing_country_code' => $alldata['billing_country_code'],
                    'billing_province_id' => isset($alldata['billing_province_id']) ? $alldata['billing_province_id'] : null,
                    'father_phone' => $alldata['father_phone'],
                    'father_email' => $alldata['father_email'],
                    'father_notify' => isset($alldata['father_notify']) && !empty($alldata['father_notify']) ? 1 : 0 ,
                    'mother_phone' => $alldata['mother_phone'],
                    'mother_email' => $alldata['mother_email'],
                    'mother_notify' => isset($alldata['mother_notify']) && !empty($alldata['mother_notify']) ? 1 : 0 ,
                    'mobile' => $alldata['mobile'],
                    'email' => $alldata['email'],
                    'email2' => $alldata['email2'],
                    'student_notify' => isset($alldata['student_notify']) && !empty($alldata['student_notify']) ? 1 : 0 ,
            ];

            if($request->file('profile_image_file'))
            {
                $image = $request->file('profile_image_file');
                $mime_type = $image->getMimeType();
                $extension = $image->getClientOriginalExtension();
                if($image->getSize()>0)
                {
                    list($path, $imageNewName) = $this->__processImg($image,'StudentImage',$user);

                    if (!empty($path)) {
                        $fileData = [
                            'visibility' => 1,
                            'file_type' =>'image',
                            'title' => $user->username,
                            'path_name' =>$path,
                            'file_name' => $imageNewName,
                            'extension'=>$extension,
                            'mime_type'=>$mime_type
                        ];

                        $attachedImage = AttachedFile::create($fileData);
                        $studentData['profile_image_id'] = $attachedImage->id;

                    }
                }
            }

            $exist = SchoolStudent::where(['student_id'=>$student->id, 'school_id'=>$schoolId])->first();
            if (!empty($alldata['email'])) {
                if ($exist->email != $alldata['email']) {
                    // notify user by email about new Teacher role
                    if (config('global.email_send') == 1) {
                        $data = [];
                        $data['email'] = $alldata['email'];
                        $user->update($data);
                        $data['username'] = $data['name'] = $user->username;

                        $verifyUser = [
                            'school_id' => $alldata['school_id'],
                            'person_id' => $student->id,
                            'person_type' => 'App\Models\Student',
                            'token' => Str::random(10),
                            'token_type' => 'VERIFY_SIGNUP',
                            'expire_date' => Carbon::now()->addDays(config('global.token_validity'))->format("Y-m-d")
                        ];
                        $verifyUser = VerifyToken::create($verifyUser);
                        $data['token'] = $verifyUser->token;
                        $data['url'] = route('add.verify.email',$data['token']);
                        if (!$this->emailSend($data,'sign_up_confirmation_email')) {
                            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
                        }
                    }
                }
            }

            Student::where('id', $student->id)->update($studentData);
            $schoolStudent = [
                'student_id' => $student->id,
                'school_id' => $schoolId,
                'has_user_account' => !empty($alldata['has_user_account']) ? $alldata['has_user_account'] : null,
                // 'nickname' => $alldata['nickname'],
                'email' => $alldata['email'],
                'level_date_arp' => isset($alldata['level_date_arp']) && !empty($alldata['level_date_arp']) ? date('Y-m-d H:i:s',strtotime($alldata['level_date_arp'])) : null ,
                'licence_arp' => isset($alldata['licence_arp']) && !empty($alldata['licence_arp']) ? $alldata['licence_arp'] : null ,
                'licence_usp' => $alldata['licence_usp'],
                'level_skating_usp' => isset($alldata['level_skating_usp']) && !empty($alldata['level_skating_usp']) ? $alldata['level_skating_usp'] : null ,
                'level_date_usp' => isset($alldata['level_date_usp']) && !empty($alldata['level_date_usp']) ? date('Y-m-d H:i:s',strtotime($alldata['level_date_usp'])) : null ,
            ];

            SchoolStudent::where(['student_id'=>$student->id, 'school_id'=>$alldata['school_id']])->update($schoolStudent);
            DB::commit();
            return back()->withInput($request->all())->with('vtab', isset($alldata['active_tab']) && !empty($alldata['active_tab']) ? $alldata['active_tab'] : 'tab_1')->with('success', __('Student updated successfully!'));
        }catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            //return error message
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
    }

    /**
     * export student school wise
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exportExcel($schoolId = null, Request $request, Student $student)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId();
        return Excel::download(new StudentsExport($schoolId), 'students_'.$schoolId.'_'.date('YmdHis').'.xlsx');
    }

         /**
     * export student school wise
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function importExcel($schoolId = null, Request $request, Student $student)
    {
        $alldata = $request->all();
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $school = School::active()->find($schoolId);
            if (empty($school)) {
                return [
                    'status' => 1,
                    'message' =>  __('School not selected')
                ];
            }
            $schoolId = $school->id;
        } else {
            $schoolId = $user->selectedSchoolId();
            $school = School::active()->find($schoolId);
            $schoolId = $school->id;
        }

        try {
            $userImport = new StudentsImport($schoolId);
            $dataArry = Excel::import($userImport, $request->file('csvFile'));
            $msg = $userImport->getMessage();
            return back()->with('success', __($msg));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            //  $failures = $e->failures();
            //  $errMsg = '';
            //  foreach ($failures as $failure) {
            //      $failure->row(); // row that went wrong
            //      $failure->attribute(); // either heading key (if using heading row concern) or column index
            //      $failure->errors(); // Actual error messages from Laravel validator
            //      $failure->values(); // The values of the row that has failed.
            //      $errMsg .= "Column {$failure->attribute()} value {$failure->values()} error: $failure->errors()<br/> ";
            //  }
            // return redirect()->back()->with('error', __($errMsg));
        }
        return redirect()->back()->with('error', __('Internal server error'));
    }

}
