<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolStudent;
use App\Models\User;
use App\Models\ContactForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMailable;

class ContactFormController extends Controller
{

    public function index()
    {
        $ContactFormCount = ContactForm::count();
        $contactForm = ContactForm::all();
        return view('pages.admin.contacts', compact('ContactFormCount', 'contactForm'));
    }

    public function countAlerts()
    {
        $alertCount = ContactForm::count();
        return view('pages.admin.alerts', compact('alertCount'));
    }

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
        $person_id = $request->input('person_id');
        $subject = $request->input('subject');
        $headerMessage = $request->input('headerMessage');
        $emailTo = $data['emailTo'];
        $isStaff = false;

        if($emailTo === "staff") {
           // $emailTo = config('services.mail.from_address');
        } else {
            $emailToConcatenated = implode(',', $data['emailTo']);
        }

        $messageBody = $request->input('message');
        $contactForm = new ContactForm();
        $contactForm->sujet = $subject;
        $contactForm->email_expediteur = $authUser->email;
        if($emailTo !== "staff") {
            $contactForm->email_destinataire = $emailToConcatenated;
        } else {
            $contactForm->email_destinataire = config('services.mail.from_address');
        }
        $contactForm->id_expediteur = $authUser->id;
        $contactForm->id_destinataire = $person_id;
        $contactForm->message = $messageBody;
        $contactForm->save();

        if($emailTo !== "staff") {
            foreach ($data['emailTo'] as $recipient) {
                Mail::send(new ContactFormMailable($subject, $recipient, $headerMessage, $messageBody));
            }
        } else {
            Mail::send(new ContactFormMailable($subject, config('services.mail.from_address'), $headerMessage, $messageBody));
        }

        if($data['emailTo'] === "staff") {
            return redirect()->route('contact.staff')->with('success', 'Message sent successfully!');
        } else {
            return redirect()->route('contact.form')->with('success', 'Message sent successfully!');
        }
    }
}
