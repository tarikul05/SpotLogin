<?php
namespace App\Helpers;

use App\Http\Controllers\Controller;
use App\Models\School;

class ReminderEmail
{
    /**
     * Send reminder email
     *
     * @param object $request
     * @param object $result_data
     *
     * @return array result
     */
    public function sendReminderEmail($request, $result_data)
    {
        $data = $request->all();
        $user = $request->user();
        try {
            $p_template_code = trim($data['template_code']);
            $p_lang = 'en';
            if (isset($data['p_lang'])) {
                $p_lang = $data['p_lang'];
            }
            $p_email = trim($data['p_email']);
            $p_school_id = trim($data['p_school_id']);
            $school = School::active()->find($p_school_id);
            if ($user->isSuperAdmin()) {
                if (empty($school)) {
                    return redirect()->route('schools')->with('error', __('School is not selected'));
                }
                $schoolName = $school->school_name;
            } else {
                $schoolName = $school->school_name;
            }
            $client_name = $result_data->client_name;
            $invoice_filename = $result_data->invoice_filename;
            $target_user = $result_data->target_user;

            $email_data = [];
            $email_data['subject'] = 'Pay reminder email';
            $email_data['admin_email_from'] = $school->email;
            $email_data['admin_email_from_name']=$school->school_name;
            $email_data['p_lang'] = $p_lang;
            $email_data['name'] = $target_user->firstname . ' ' . $target_user->lastname;

            //if (isset($result_data['admin_email_from'])) {
             //   $email_data['admin_email_from']=$result_data['admin_email_from'];
        //}

           /* if (isset($result_data['admin_email_from_name'])) {
                $email_data['admin_email_from_name']=$result_data['admin_email_from_name'];
            }*/

            $email_data['username'] = $target_user->firstname;
            $email_data['school_name'] = $schoolName;
            $email_data['client_name'] = $client_name;
            $email_data['p_attachment'] = $invoice_filename;
            $emailSend = new Controller();
            if ($p_email != '') {
                $email_to = str_replace(',', '|', $p_email);
                $email_to = str_replace(';', '|', $p_email);

                $email_to_arr = explode("|", $p_email);

                foreach ($email_to_arr  as &$value) {
                    if ($value != "") {
                        $email_data['email'] = $value;
                        if ($emailSend->emailSend($email_data, $p_template_code)) {

                            $result = array(
                                'status' => true,
                                'message' => __('We sent you an activation link. Check your email and click on the link to verify.'),
                            );
                        } else {
                            $result = array(
                                "status"     => false,
                                'message' =>  __('Internal server error')
                            );
                        }
                    }
                }
            } else {
                foreach ($result_data->emails as $key => $value) {
                    $email_data['email'] = $value;
                    $email_data['client_name'] = $client_name;
                    $email_data['p_attachment'] = $invoice_filename;

                    if ($emailSend->emailSend($email_data, $p_template_code)) {

                        $result = array(
                            'status' => true,
                            'message' => __('We sent you an activation link. Check your email and click on the link to verify.'),
                        );
                    } else {
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

            return $result;
        }
    }
}
