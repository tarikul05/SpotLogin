<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\EmailTemplate;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Teacher;
use App\Models\SchoolTeacher;
use App\Models\User;
use App\Models\MonthlyInvoiceRun;
use App\Models\EventCategory;
use App\Models\Location;
use App\Models\Level;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Illuminate\Support\Facades\URL;
use App\Models\AttachedFile;
use Illuminate\Support\Facades\Storage;
use App\Mail\SportloginEmail;
use App\Http\Requests\ProfilePhotoUpdateRequest;
use App\Http\Requests\SchoolUpdateRequest;
use DB;

class SchoolsController extends Controller
{


    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:schools-list|schools-udpate|schools-user-udpate|schools-delete', ['only' => ['index']]);
        $this->middleware('permission:schools-udpate', ['only' => ['edit','update','logoUpdate','logoDelete']]);
        $this->middleware('permission:schools-user-udpate', ['only' => ['schoolEmailSend','userUpdate']]);
        $this->middleware('permission:schools-delete', ['only' => ['destroy']]);
        $this->middleware('permission:parameters-create-udpate', ['only' => ['addParameters']]);

    }


    /**
     *  Display a listing of the resource.
     *
     * @return view
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-10
    */
    public function index(Request $request, School $school)
    {
        try{
            $result = [];
            $params = $request->all();
            $query = $school->filter($params);
            $schools = $query->get();
            return view('pages.schools.list')
            ->with(compact('schools'));
        } catch(\Exception $e){
            echo $e->getMessage(); exit;
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
     * @param  int  $school
     * @return \Illuminate\Http\Response
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-10
    */
    public function edit(Request $request, School $school)
    {

        $response = [];
        $authUser = $request->user();
        if ($authUser->person_type != 'SUPER_ADMIN') {
            if (!empty($authUser->selectedSchoolId())) {
                $p_school_id = $authUser->selectedSchoolId();
                $role_type = $authUser->getRoleTypeAttribute();
                $school = School::find($p_school_id);
                if ($role_type=='school_admin') {
                    $school_admin = $authUser;
                } else {
                    try{
                        $teacher = SchoolTeacher::where([
                            ['school_id', $school->id],
                            ['is_active', 1],
                            ['role_type', 'school_admin'],
                            ['has_user_account', 1]
                        ])->first();
                        $school_admin = User::where([
                            ['person_id', $teacher->id],
                            ['is_active', 1],
                            ['person_type', 'App\Models\Teacher']
                        ])->first();
                    } catch (\Exception $e) {
                        //return error message
                        return redirect()->route('schools')->with('error', __('School admin not exist'));
                    }
                }
            }
        } else {
            $role_type = $authUser->person_type;

            // try{
            //     $teacher = SchoolTeacher::where([
            //         ['school_id', $school->id],
            //         ['is_active', 1],
            //         ['role_type', 'school_admin'],
            //         ['has_user_account', 1]
            //     ])->first();
            //     $school_admin = User::where([
            //         ['person_id', $teacher->id],
            //         ['is_active', 1],
            //         ['person_type', 'App\Models\Teacher']
            //     ])->first();
            // } catch (\Exception $e) {
            //     //return error message
            //     return redirect()->route('schools')->with('error', __('School admin not exist'));
            // }

            $school_admin = null;
        }


        $lanCode = 'en';
        if (Session::has('locale')) {
            $lanCode = Session::get('locale');
        }
        $currency = Currency::all();
        $country = Country::active()->get();
        $legal_status = config('global.legal_status');


        $emailTemplate = EmailTemplate::where([
            ['template_code', 'school'],
            ['language', $lanCode]
        ])->first();
        if ($emailTemplate) {
            $http_host=$this->BASE_URL."/";
            if (!empty($emailTemplate->body_text)) {
                $emailTemplate->body_text = str_replace("[~~ HOSTNAME ~~]",$http_host,$emailTemplate->body_text);
                $emailTemplate->body_text = str_replace("[~~HOSTNAME~~]",$http_host,$emailTemplate->body_text);
            }
        }

        if($school->incorporation_date != null){

            $school->incorporation_date = str_replace('-', '/', $school->incorporation_date);
            //$school->incorporation_date = Carbon::createFromFormat('Y/m/d', $school->incorporation_date);
        }


        $monthly_issue = MonthlyInvoiceRun::where([
            ['school_id', $school->id],
            ['active_flag', 1]
        ])->first();
        if (!empty($monthly_issue)) {
            $monthly_issue = $monthly_issue->day_no;
        } else {
            $monthly_issue = 0;
        }

        $eventCat = EventCategory::active()->where('school_id', $school->id)->get();
        $eventLastCatId = DB::table('event_categories')->orderBy('id','desc')->first();
        $schoolId = $school->id;
        $locations = Location::active()->where('school_id', $schoolId)->get();
        $eventLastLocaId = DB::table('locations')->orderBy('id','desc')->first();
        $levels = Level::active()->where('school_id', $schoolId)->get();
        $eventLastLevelId = DB::table('levels')->orderBy('id','desc')->first();

        return view('pages.schools.edit')
        ->with(compact(
            'levels',
            'eventLastLevelId',
            'locations',
            'eventLastLocaId',
            'eventCat',
            'eventLastCatId',
            'legal_status',
            'currency',
            'school',
            'emailTemplate',
            'country',
            'role_type',
            'school_admin'
        ));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  School $school
     * @return \Illuminate\Http\Response
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-10
    */
    public function update(SchoolUpdateRequest $request, School $school)
    {
        $params = $request->all();
        try{
            $school->update($request->except(['_token']));
            if (!empty($params['monthly_job_day'])) {

                $monthly_issue = MonthlyInvoiceRun::updateOrCreate([
                    'school_id' => $school->id,
                    'active_flag' =>1
                ],[
                    'day_no'=>$params['monthly_job_day']
                ]);
            }
            return back()->withInput($request->all())->with('success', __('School updated successfully!'));
        } catch (\Exception $e) {
            //return error message
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
    }

     /**
     * Update the school admin account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  School $school
     * @return \Illuminate\Http\Response
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-10
    */
    public function userUpdate(Request $request, School $school)
    {
        $params = $request->all();
        try{
            $request->merge([
                'email'=> $params['admin_email'],
                'is_active'=> $params['admin_is_active']
            ]);

            $user = User::find($params['user_id']);
            if ($user) {
                $user->update($request->except(['_token']));

                if (!empty($params['admin_password'])) {
                    $user->password = $params['admin_password'];
                    $user->save();
                }
            } else{
                return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
            }

            return back()->withInput($request->all())->with('success', __('School admin account updated successfully!'));
        } catch (\Exception $e) {
            //return error message
            return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
        }
    }


    /**
     *  AJAX action to update logo
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-10
     */
    public function logoUpdate(ProfilePhotoUpdateRequest $request)
    {
        $data = $request->all();
        $result = array(
        'status' => 0,
        "file_id" => '0',
        "image_file" => '',
        'message' => __('failed to change image'),
        );
        try{
            $school = School::find($data['school_id']);
            if($request->file('profile_image_file'))
            {

                $image = $request->file('profile_image_file');
                $mime_type = $image->getMimeType();
                $extension = $image->getClientOriginalExtension();
                if($image->getSize()>0)
                {
                    list($path, $imageNewName) = $this->__processImg($image,'SchoolLogo',$school);

                    if (!empty($path)) {
                        $fileData = [
                            'visibility' => 1,
                            'file_type' =>'image',
                            'title' => $school->school_name,
                            'path_name' =>$path,
                            'file_name' => $imageNewName,
                            'extension'=>$extension,
                            'mime_type'=>$mime_type
                        ];

                        $attachedImage = AttachedFile::create($fileData);

                        $data['logo_image_id'] = $attachedImage->id;

                    }
                }
            }

            if ($school->update($data)) {
                $result = array(
                "status"     => 1,
                "file_id" => $school->logo_image_id,
                "image_file" => $path,
                'message' => __('Successfully Changed School Logo')
                );
            }

        } catch (\Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
        }
        return response()->json($result);
    }

    /**
     *  AJAX action to delete logo and unlink
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-10
     */
    public function logoDelete(Request $request)
    {
        $data = $request->all();
        $school = School::find($data['school_id']);
        $result = array(
            'status' => 'failed',
            'message' => __('failed to remove image'),
        );
        try{
            $path_name =  $school->logoImage->path_name;
            $file = str_replace(URL::to('').'/uploads/','',$path_name);

            $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
            if(file_exists($storagePath.$file)) unlink($storagePath.$file);
            AttachedFile::find($school->logoImage->id)->delete();
            $data['logo_image_id'] =null;
            if ($school->update($data)) {
                $result = array(
                    "status"     => 'success',
                    'message' => __('Successfully Removed School Logo')
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
     *  AJAX action to send email to school admin
     *
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-03-10
     */
    public function schoolEmailSend(Request $request)
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
     *
     * @return Response
    */

    public function addParameters(Request $request)
    {
        try{
            if ($request->isMethod('post')){
                $data = $request->all();
                $user = Auth::user();
                if ($user->isSuperAdmin()) {
                    $userSchoolId = $data['school_id'];
                }else {
                    $userSchoolId = $user->selectedSchoolId();
                }

                if (isset($data['level']) && !empty($data['level'])) {
                    foreach($data['level'] as $level){
                        if(isset($level['id']) && !empty($level['id'])){
                            $answers = [
                                'school_id' => $userSchoolId,
                                'title' => $level['name']
                            ];
                            $eventLevel = Level::where('id', $level['id'])->update($answers);
                        }else{
                            $answers = [
                                'school_id' => $userSchoolId,
                                'title' => $level['name']
                            ];
                            $eventLevel = Level::create($answers);
                        }
                    }
                }
                if (isset($data['location']) && !empty($data['location'])) {

                    foreach($data['location'] as $location){
                        if(isset($location['id']) && !empty($location['id'])){
                            $answers = [
                                'school_id' => $userSchoolId,
                                'title' => $location['name']
                            ];
                            $eventLocation = Location::where('id', $location['id'])->update($answers);
                        }else{
                            $answers = [
                                'school_id' => $userSchoolId,
                                'title' => $location['name']
                            ];
                            $eventLocation = Location::create($answers);
                        }
                    }
                }
                if (isset($data['category']) && !empty($data['category'])) {

                    foreach($data['category'] as $cat){
                        $invoicedType = $user->isTeacher() ? 'T' : $cat['invoice'];
                        if(isset($cat['id']) && !empty($cat['id'])){
                            $answers = [
                                'school_id' => $userSchoolId,
                                'title' => $cat['name'],
                                'invoiced_type' => $invoicedType
                            ];
                            $eventCat = EventCategory::where('id', $cat['id'])->update($answers);
                        }else{
                            $answers = [
                                'school_id' => $userSchoolId,
                                'title' => $cat['name'],
                                'invoiced_type' => $invoicedType
                            ];
                            $eventCat = EventCategory::create($answers);
                        }
                    }
                }

                $result = array(
                    "status"     => 1,
                    'message' => __('Successfully Registered')
                );
            }
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
