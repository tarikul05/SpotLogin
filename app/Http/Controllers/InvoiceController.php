<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\School;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\EmailTemplate;
use App\Mail\SportloginEmail;

class InvoiceController extends Controller
{

    public function __construct()
    {
        parent::__construct();    
    }

   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$schoolId = null)
    {

        $user = $request->user();
        $this->schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($this->schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        // $school = School::active()->find($schoolId);
        // if (empty($school)) {
        //     $schoolId = 0;
        // }
        $invoice_type_all = config('global.invoice_type');
        $payment_status_all = config('global.payment_status');
        $invoice_status_all = config('global.invoice_status');
        $invoices = Invoice::active()->where('school_id',$this->schoolId)->get();
        //dd($invoices);
        return view('pages.invoices.list',compact('invoices','schoolId','invoice_type_all','payment_status_all','invoice_status_all'));
    }


    /**
     *  AJAX action to get email for pay reminder
     * 
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-05-27
     */
    public function payReminderEmailFetch(Request $request)
    {
        $result = array(
            'status' => false,
            'message' => __('failed to send email'),
        );
        try {
            $data = $request->all();


            $p_auto_id = trim($data['p_auto_id']);
          
            $data = array();
            $result_data = Invoice::active()->find($p_auto_id);
            $filtered_invoice = [0,1,9];
            $result_data->student_email = '';
            $result_data->father_email = '';
            $result_data->mother_email = '';
            
            if (in_array($result_data->invoice_type, $filtered_invoice)) {
                $result_data->class_name = 'student';
                $student = Student::find($result_data->client_id);
                //$result->client = $student;
                if ($student->student_notify ==1) {
                    $result_data->student_email = $student->email;
                }
                if ($student->father_notify ==1) {
                    $result_data->father_email = $student->father_email;
                }
                if ($student->mother_notify ==1) {
                    $result_data->mother_email = $student->mother_email;
                }
                $result_data->class_name = 'student';
                $result_data->primary_email = $student->email;
                $result_data->secondary_email = $student->email2;
            } else {
                $result_data->class_name = 'teacher';
                $teacher = Teacher::find($result->seller_id);
                $result_data->primary_email = $teacher->email;
                $result_data->secondary_email = $teacher->email2;
            }
            $data[]= $result_data;
            
            $result = array(
                'status' => true,
                'message' => __('We sent an email.'),
                'data' => $data
            );

            return response()->json($result);

        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
        
    }


    /**
     *  AJAX action to send email for pay reminder
     * 
     * @return json
     * @author Mamun <lemonpstu09@gmail.com>
     * @version 0.1 written in 2022-05-27
     */
    public function payReminderEmailSend(Request $request)
    {
        $result = array(
            'status' => false,
            'message' => __('failed to send email'),
        );
        try {
            $data = $request->all();
            $user = $request->user();
            
            $p_template_code=trim($data['template_code']);
            $p_inv_auto_id=trim($data['p_inv_auto_id']);
            $p_inv_file=trim($data['p_inv_file']);
            $p_email=trim($data['p_email']);
            $p_school_id=trim($data['p_school_id']);
            $this->schoolId= $p_school_id;
            
            $res_data = array();
            $result_data = Invoice::active()->find($p_inv_auto_id);
            
            $emails = [];
            $target_user = [];
            $filtered_invoice = [0,1,9];
            
            if (in_array($result_data->invoice_type, $filtered_invoice)) {
                $target_user = $student = Student::find($result_data->client_id);
                
                //$result->client = $student;
                $emails[] = $student->email;
                $emails[] = $student->email2;
            } else {
                $target_user = $teacher = Teacher::find($result->seller_id);
                $emails[] = $teacher->email;
                $emails[] = $teacher->email2;
            }
            $result_data->emails = $emails;
            $result_data->target_user = $target_user;
            
            if ($user->isSuperAdmin()) {
                
                $school = School::active()->find($this->schoolId);
                //dd($school);
                if (empty($school)) {
                    return redirect()->route('schools')->with('error', __('School is not selected'));
                }
                $this->schoolId = $school->id;
                $schoolName = $school->school_name; 
            }else {
                $this->schoolId = $user->selectedSchoolId();
                $schoolName = $user->selectedSchoolName(); 
            }
            //$result_data->invoice_filename = $emails;

            $invoiceCurrency = InvoiceItem::active()->where('invoice_id',$p_inv_auto_id)->get()->pluck('price_currency')->join(',');
            $result_data->invoice_price = $invoiceCurrency.''.round($result_data->total_amount,2);
            
            
            $res_data[]= $result_data;
            
            //sending email for forgot password
            if (config('global.email_send') == 1) {
                       
                try {
                    $p_lang = 'en'; 
                    if (isset($data['p_lang'])) {
                        $p_lang = $data['p_lang']; 
                    }
                    $email_data = [];
                    $email_data['subject']='Pay reminder email';
                    
                    $email_data['p_lang'] = $p_lang; 
                    $email_data['name'] = $target_user->firstname.' '.$target_user->lastname;
                        
                    $email_data['username'] = $target_user->firstname; 
                    $email_data['school_name'] = $schoolName; 
                    //$this->schoolId =
                    if ($p_email !='') {
                        //dd($p_email);
                        $email_to=str_replace(',','|',$p_email);
                        $email_to=str_replace(';','|',$p_email);
                        
                        $email_to_arr = explode("|", $p_email);
                        
                        //$cnt=sizeof($email_to_arr);
                        foreach ($email_to_arr  as &$value) {
                            if ($value !="") {
                                //$result_data->emails[]=$p_email;
                                $email_data['email'] = $value;
                                //dd($p_email);   
                                if ($this->emailSend($email_data,$p_template_code)) {
                                
                                    $result = array(
                                        'status' => true,
                                        'message' => __('We sent you an activation link. Check your email and click on the link to verify.'),
                                    );
                                }  else {
                                    $result = array(
                                        "status"     => false,
                                        'message' =>  __('Internal server error')
                                    );
                                }
                                //$mail->addAddress($value, $value);    
                            }
                            //unset($value);
                        }
                        //dd($email_to_arr);
                        
                        
                    } else {
                        //dd($email_data);
                        foreach ($result_data->emails as $key => $value) {
                            $email_data['email'] = $value;
                            
                            if ($this->emailSend($email_data,$p_template_code)) {
                           
                                $result = array(
                                    'status' => true,
                                    'message' => __('We sent you an activation link. Check your email and click on the link to verify.'),
                                );
                            }  else {
                                $result = array(
                                    "status"     => false,
                                    'message' =>  __('Internal server error')
                                );
                            }
                        }
                    }
                    
                    return response()->json($result);
                } catch (\Exception $e) {
                    $result = array(
                        'status' => true,
                        'message' => __('We sent you an activation code. Check your email and click on the link to verify.'),
                    );
                   
                    return response()->json($result);
                }
            } else{
                $result = array('status'=>true,'msg'=>__('email sent'));
            }
            

            return response()->json($result);

        } catch (Exception $e) {
            //return error message
            $result['message'] = __('Internal server error');
            return response()->json($result);
        }
        
    }

    /**
     *  AJAX action to send email for pay reminder
     * 
     * @return json
     * @author Tarikul 90
     * @version 0.1 written in 2022-05-27
     */

    public function add()
    {
        $genders = config('global.gender');
        $countries = Country::active()->get();
        return view('pages.invoices.add', [
            'title' => 'Invoice',
            'pageInfo'=>['siteTitle'=>'']
        ])->with(compact('genders','countries'));
    } 
        
    /**
     *  AJAX action to send email for pay reminder
     * 
     * @return json
     * @author Tarikul 90
     * @version 0.1 written in 2022-05-27
     */

    public function manualInvoice()
    {
        $genders = config('global.gender');
        $countries = Country::active()->get();
        return view('pages.invoices.manual_invoice', [
            'title' => 'Invoice',
            'pageInfo'=>['siteTitle'=>'']
        ])->with(compact('genders','countries'));
     
    } 
}