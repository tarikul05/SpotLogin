<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolStudent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMailable;

class ContactFormController extends Controller
{
    public function showForm(Request $request)
    {
        $authUser = $request->user();
        if ($authUser->isSchoolAdmin() || $authUser->isTeacherSchoolAdmin() || $authUser->isTeacherAdmin()) {
            $students = SchoolStudent::where(['school_id' => $authUser->school_id, 'is_active' => 1])
            ->with('student')
            ->get();
        } else {
        $students = User::where(['school_id' => $authUser->school_id, 'person_type' => 'App\Models\Teacher', 'is_active' => 1])->get();
        }
        return view('pages.contact.form', compact('students'));
    }

    public function showFormStaff(Request $request)
    {
        return view('pages.contact.staff');
    }

    public function submitForm(Request $request)
    {

        $data = $request->all();
        $authUser = $request->user();
        $subject = $request->input('subject');
        $headerMessage = $request->input('headerMessage');
        $emailTo = $data['emailTo'];
        if($emailTo === "staff") {
            $emailTo = config('services.mail.from_address');
        }
        $messageBody = $request->input('message');
        Mail::send(new ContactFormMailable($subject, $emailTo, $headerMessage, $messageBody));
        if($data['emailTo'] === "staff") {
            return redirect()->route('contact.staff')->with('success', 'Message sent successfully!');
        } else {
            return redirect()->route('contact.form')->with('success', 'Message sent successfully!');
        }
    }
}
