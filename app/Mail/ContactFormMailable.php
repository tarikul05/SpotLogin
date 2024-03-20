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
    public function __construct($subject, $sender_name, $emailFrom, $emailTo, $headerMessage, $messageBody, $contactFormId, $contactFormIdDestinataire)
    {
        $this->subject = $subject;
        $this->sender_name = $sender_name;
        $this->emailFrom = $emailFrom;
        $this->emailTo = $emailTo;
        $this->headerMessage = $headerMessage;
        $this->messageBody = $messageBody;
        $this->contactFormId = $contactFormId;
        $this->contactFormIdDestinataire = $contactFormIdDestinataire;
        $this->senderName = 'SportLogin - ' . $sender_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $html = '<div class="wrapper" style="margin: 0; padding: 0; width: 100%; background-color: #fefefe;">
        <div class="container_email" style="background-color: #ffffff; border-radius: 10px; padding: 20px; box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1); max-width: 600px; margin: 0 auto;">';
        $html .= '<div style="width: 100%; text-align:center; border-bottom:1px solid #EEE;">';
        $html .= '<img src="https://sportlogin.app/img/logo-blue.png" width="60px" /><br><b style="color:#0075bf; font-size:22px;">SPORTLOGIN</b><br><br>';
        $html .= '</div>';
        $html .= "<br><br><b style='color:#0075bf;'>{$this->headerMessage}</b><br><br>";
        $html .= "<b>Subject</b>: {$this->subject}<br><br>";
        $html .= "<b>Message</b>:<br>{$this->messageBody}";
        $html .= '<br><br><br><div style="margin:0 auto; width:100%; max-width:500px; text-align:center; border-top:1px solid #EEE; color:#BBB;"><p>You can reply to this email directly or use the "Answer" button below to reply from your Sportlogin account and follow the message history.</p>';
        if($this->contactFormIdDestinataire == 0) {
            $html .= "<a href='https://sportlogin.app/admin/contacts/show/{$this->contactFormId}' style='padding:5px; text-decoration: none; font-size:20px; border-radius:10px; background-color:#0075bf; color:#ffffff;'>Answer</a>";
        } else {
            $html .= "<a href='https://sportlogin.app/contact-answer/{$this->contactFormId}' style='padding:5px; text-decoration: none; font-size:20px; border-radius:10px; background-color:#0075bf; color:#ffffff;'>Answer</a>";
        }
        $html .= "<br></div></div></div>";
        return $this->to($this->emailTo)
                    ->from($this->emailFrom, $this->senderName)
                    ->replyTo($this->emailFrom, $this->senderName)
                    ->subject($this->subject)
                    ->html($html);
    }

}

