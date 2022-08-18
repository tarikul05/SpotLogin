<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\School;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolStudent;
use App\Models\SchoolTeacher;
use App\Models\VerifyToken;
use App\Models\EmailTemplate;
use \Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Route;
use App\Mail\SportloginEmail;

class SendEmailInvitation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emailinvitation:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It sends email notification user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info(get_class($this) . ': Start process');
        $from = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        $to = now();
        $schools = School::active()->orderBy('id')->get();
        if (!empty($schools)) {
            foreach ($schools as $school) {
                $schoolId = $school->id;
                $schoolStudentData = SchoolStudent::where(['send_email' => 1, 'school_id' => $schoolId])->get();
                if (!empty($schoolStudentData)) {
                    foreach ($schoolStudentData as $schoolStudent) {
                        $studentId = $schoolStudent->student_id;
                        $student = Student::find($studentId);
                        if ($student) {
                            $this->emailSet($school, $schoolStudent, $student, 'App\Models\Student');
                        } else {
                            continue;
                        }
                    }
                }
                $schoolTeacherData = SchoolTeacher::where(['send_email' => 1, 'school_id' => $schoolId])->get();
                if (!empty($schoolTeacherData)) {
                    foreach ($schoolTeacherData as $schoolTeacher) {
                        $teacherId = $schoolTeacher->teacher_id;
                        $teacher = Teacher::find($teacherId);
                        if ($teacher) {
                            $this->emailSet($school, $schoolTeacher, $teacher, 'App\Models\Teacher');
                        } else {
                            continue;
                        }
                    }
                }
            }
        }
        \Log::info(get_class($this) . ': End process');
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
                $data['school_name'] = $school->name;

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

                if ($this->emailSend($data, 'sign_up_confirmation_email')) {
                    $data = [];
                    $data['send_email'] = 0;
                    $alldata->update($data);

                    //$msg = __('We sent you an activation link. Check your email and click on the link to verify.');
                } else {
                    //return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
                }
                return true;
            }
        } catch (\Exception $e) {
            $message = sprintf(
                "Exception\n"
                    . " - Message : %s\n"
                    . " - Code : %s\n"
                    . " - File : %s\n"
                    . " - Line : %d\n"
                    . " - Stack trace : \n"
                    . "%s",
                $e->getMessage(),
                $e->getCode(),
                $e->getFile(),
                $e->getLine(),
                $e->getTraceAsString()
            );

            \Log::error($message);
        }
    }


    /**
     * Common function for email send
     *
     */
    public function emailSend($data = [], $template_code = null)
    {

        try {
            $lang = 'en';
            if (isset($data['p_lang'])) {
                $lang = $data['p_lang'];
            }

            $emailTemplateExist = EmailTemplate::where([
                ['template_code', $template_code],
                ['language', $lang]
            ])->first();

            if ($emailTemplateExist) {
                $email_body = $emailTemplateExist->body_text;
                $data['subject'] = $emailTemplateExist->subject_text;
            } else {
                $email_body = '<p><strong><a href="[~~URL~~]">CONFIRM</a></strong></p>';
                if (isset($data['subject'])) {
                    $data['subject'] = $data['subject'];
                } else {
                    $data['subject'] = __('www.sportogin.ch: Welcome! Activate account.');
                }
            }
            $data['body_text'] = $email_body;

            if (isset($data['url'])) {
                $data['url'] = $data['url'];
            } else {
                if (isset($data['token'])) {
                    $data['url'] = route('add.verify.email', $data['token']);
                }
            }
            Mail::to($data['email'])->send(new SportloginEmail($data));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
