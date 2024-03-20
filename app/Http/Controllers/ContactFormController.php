<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Models\SchoolStudent;
use App\Models\User;
use App\Models\ContactForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMailable;

class ContactFormController extends Controller
{

    public function index(Request $request)
    {
        $authUser = $request->user();
        $contactFormCount = ContactForm::count();
        $contactForm = ContactForm::whereIn('id', function($query) use ($authUser) {
            $query->select(DB::raw('MAX(id)'))
                  ->from('contact_forms')
                  ->where(function($q) use ($authUser) {
                      $q->where(['id_destinataire' => 0])
                      ->orWhere(['id_expediteur' => 0]);
                  })
                  ->groupBy('discussion_id');
        })
        ->get();

        return view('pages.admin.contacts', compact('contactFormCount', 'contactForm'));
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

        $messages = ContactForm::whereIn('id', function($query) use ($authUser) {
            $query->select(DB::raw('MAX(id)'))
                ->from('contact_forms')
                ->where(function($q) use ($authUser) {
                    $q->where('id_expediteur', $authUser->id)
                        ->where('id_destinataire', $authUser->id);
                })
                ->orWhere(function($q) use ($authUser) {
                    $q->where('id_destinataire', $authUser->id)
                        ->where('id_expediteur', $authUser->id);
                })
                ->groupBy('discussion_id');
        })
        ->get();

        return view('pages.contact.form', compact('students', 'messages'));
    }

    public function show(Request $request, $id) {
        $authUser = $request->user();
        /*$message = ContactForm::find($id);
        $messages = ContactForm::where(function($query) use ($authUser, $message) {
            $query->where(['id_expediteur' => $authUser->person_id, 'id_destinataire' => $message->id_expediteur])
                  ->orWhere(['id_expediteur' => $message->id_expediteur, 'id_destinataire' => $authUser->person_id]);
        })
        ->where('id', '!=', $id) 
        ->get();*/

        $message = ContactForm::where('discussion_id', $id)
        ->orderBy('id', 'desc')
        ->first();

        $messages = ContactForm::where(function($query) use ($id) {
            $query->where(['discussion_id' => $id]);
        })
        ->get();

        if($message->id_expediteur !== $authUser->id) {
            ContactForm::where('discussion_id', $message->discussion_id)->update(['read' => 1]);
        }
    
        return view('pages.admin.answer', compact('message', 'messages'));
    }

    public function showAnswerForm(Request $request, $id) {
        $authUser = $request->user();
        //$message = ContactForm::find($id);
        //get last message from discussion where discussion_id = $id
        $message = ContactForm::where('discussion_id', $id)
        ->orderBy('id', 'desc')
        ->first();
    

        $messages = ContactForm::where(function($query) use ($id) {
            $query->where(['discussion_id' => $id]);
        })
        ->get();

        if($message->id_expediteur !== $authUser->id) {
            $message->read = 1;
            $message->save();
        }
       
    
        return view('pages.contact.answer', compact('message', 'messages'));
    }

    public function showFormStaff(Request $request)
    {
        $authUser = $request->user();
        $messages = ContactForm::whereIn('id', function($query) use ($authUser) {
            $query->select(DB::raw('MAX(id)'))
                ->from('contact_forms')
                ->where(function($q) use ($authUser) {
                    $q->where('id_expediteur', $authUser->id)
                        ->where('id_destinataire', 0);
                })
                ->orWhere(function($q) use ($authUser) {
                    $q->where('id_destinataire', $authUser->id)
                        ->where('id_expediteur', 0);
                })
                ->groupBy('discussion_id');
        })
        ->get();

        return view('pages.contact.staff', compact('messages'));
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
        $sender_name = $authUser->firstname.' '. $authUser->lastname;
        if($emailTo !== "staff") {
            $contactForm->email_destinataire = $emailToConcatenated;
            $contactForm->id_destinataire = $person_id;
        } else {
            $contactForm->id_destinataire = 0;
            $language = $request->input('language');
            if($language == "english") {
                $contactForm->email_destinataire = config('services.mail.from_address_us');
            } else {
                $contactForm->email_destinataire = config('services.mail.from_address');
            }
        }

        $contactForm->id_expediteur = $authUser->id;
        $contactForm->message = $messageBody;
        $contactForm->read = 0;
        $contactForm->save();

        $contactFormId = $contactForm->id; 

        $newMessage = ContactForm::find($contactFormId);
        $newMessage->discussion_id = $contactFormId;
        $newMessage->save();

        if($emailTo !== "staff") {
            foreach ($data['emailTo'] as $recipient) {
                Mail::send(new ContactFormMailable($subject, $sender_name, $contactForm->email_expediteur, $recipient, $headerMessage, $messageBody, $contactFormId, $contactForm->id_destinataire));
            }
        } else {
            Mail::send(new ContactFormMailable($subject, $sender_name, $contactForm->email_expediteur, $contactForm->email_destinataire, $headerMessage, $messageBody, $contactFormId, $contactForm->id_destinataire));
        }


        if($data['emailTo'] === "staff") {
            return redirect()->route('contact.answer', $contactFormId)->with('success', 'Message sent successfully!');
        } else {
            return redirect()->route('contact.form')->with('success', 'Message sent successfully!');
        }
    }


    public function submitAnswer(Request $request)
    {

        $data = $request->all();
        $authUser = $request->user();
        $person_id = $request->input('person_id');
        $subject = $request->input('subject');
        $headerMessage = $request->input('headerMessage');
        $emailTo = $request->input('emailTo');
        $email_from = $request->input('email_from');
        $id_destinataire = $request->input('id_destinataire');
        $discussion_id = $request->input('discussion_id');

        $messageBody = $request->input('message');
        $contactForm = new ContactForm();
        $contactForm->sujet = $subject;
        $contactForm->email_expediteur = $email_from;
        $sender_name = $authUser->firstname.' '. $authUser->lastname;
        $contactForm->email_destinataire = $emailTo;
        $contactForm->id_destinataire = $person_id;
        $contactForm->id_expediteur = $id_destinataire;
        $contactForm->message = $messageBody;
        $contactForm->discussion_id = $discussion_id;
        $contactForm->save();

        $contactFormId = $contactForm->id; 

        Mail::send(new ContactFormMailable($contactForm->sujet, $sender_name, $contactForm->email_expediteur, $contactForm->email_destinataire, $headerMessage, $messageBody, $discussion_id, $contactForm->id_destinataire));

        if($person_id == 0) {
            return redirect()->route('contact.answer', $discussion_id)->with('success', 'Message sent successfully!');
        } else {
            if($authUser->isSuperAdmin()) {
            return redirect()->route('contacts.show', $discussion_id)->with('success', 'Message sent successfully!');
            } else {
                return redirect()->route('contact.answer', $discussion_id)->with('success', 'Message sent successfully!');
            }
        }
        
        
    }


}
