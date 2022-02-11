<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Config;

class NewRegistration extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
      $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      // Grab config
      $admin_email_from = config('global.mail_from_address');
      $admin_email_from_name = config('global.mail_from_name');
      return $this->from($admin_email_from, $admin_email_from_name)
                ->subject('www.sportogin.ch: Welcome! Activate account.')->markdown('emails.verifyUser');
    }
}
