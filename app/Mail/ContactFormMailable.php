<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $emailTo;
    public $messageBody;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $emailTo, $headerMessage, $messageBody, $contactFormId, $contactFormIdDestinataire)
    {
        $this->subject = $subject;
        $this->emailTo = $emailTo;
        $this->headerMessage = $headerMessage;
        $this->messageBody = $messageBody;
        $this->contactFormId = $contactFormId;
        $this->contactFormIdDestinataire = $contactFormIdDestinataire;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $html = '<img src="https://sportlogin.app/img/logo-blue.png" width="80px" />';
        $html .= "<h3>{$this->headerMessage}</h3>";
        $html .= "<b>Subject</b>:<br>{$this->subject}<br><br>";
        $html .= "<b>Message</b>:<br>{$this->messageBody}";
        $html .= "<p><br></p>";
        if($this->contactFormIdDestinataire == 0) {
            $html .= "<a href='http://127.0.0.1:8000/admin/contacts/show/{$this->contactFormId}' style='padding:10px; text-decoration: none; font-size:25px; border-radius:10px; background-color:#0075bf; color:#ffffff;'>Answer</a>";
        } else {
            $html .= "<a href='http://127.0.0.1:8000/contact-answer/{$this->contactFormId}' style='padding:10px; text-decoration: none; font-size:25px; border-radius:10px; background-color:#0075bf; color:#ffffff;'>Answer</a>";
        }

        return $this->to($this->emailTo)
                    ->subject($this->subject)
                    ->html($html);
    }

}

