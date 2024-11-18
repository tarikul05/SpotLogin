<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $paymentData;
    public $isCoach;

    public function __construct($paymentData, $isCoach = false)
    {
        $this->paymentData = $paymentData;
        $this->isCoach = $isCoach;
    }

    public function build()
    {
        $view = $this->isCoach ? 'emails.coach-payment-confirmation' : 'emails.payment-confirmation';
        $subject = $this->isCoach 
            ? 'Payment Received from ' . $this->paymentData['student_name']
            : 'Payment Confirmation from ' . $this->paymentData['coach_name'];

        return $this->view($view)
        ->from("no-reply@sportlogin.app", $this->isCoach ? $this->paymentData['student_name'] : $this->paymentData['coach_name'])
        ->replyTo("no-reply@sportlogin.app", "no-reply@sportlogin.app")
        ->subject($subject);
    }
}