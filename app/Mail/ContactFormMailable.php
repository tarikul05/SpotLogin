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
    public function __construct($subject, $emailTo, $headerMessage, $messageBody)
    {
        $this->subject = $subject;
        $this->emailTo = $emailTo;
        $this->headerMessage = $headerMessage;
        $this->messageBody = $messageBody;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $html = "<p><i>{$this->headerMessage}</i><p>";
        $html .= "Subject: <b>{$this->subject}</b>";
        $html .= "<p>{$this->messageBody}</p>";

        return $this->to($this->emailTo)
                    ->subject($this->subject)
                    ->html($html);
    }


}

