<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Config;
use Illuminate\Support\Facades\URL;

class SportloginEmail extends Mailable
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
      $http_host=URL::to('')."/"; 
      $eol = "\r\n";        
      if (isset($data['body_text'])&& !empty($data['body_text'])) {
        
        $data['body_text'] = str_replace("[~~ HOSTNAME ~~][~~ USER_NAME ~~]/index.html",$http_host,$data['body_text']);
        
        $data['body_text'] = str_replace("[~~HOSTNAME~~][~~USER_NAME~~]/index.html",$http_host,$data['body_text']);
        if (isset($data['username'])) {
          $data['body_text'] = str_replace("[~~USER_NAME~~]",$data['username'],$data['body_text']);
          $data['body_text'] = str_replace("[~~ USER_NAME ~~]",$data['username'],$data['body_text']);
        
        }
        if (isset($data['url'])) {
          $data['body_text'] = str_replace("[~~URL~~]",$data['url'],$data['body_text']);
          $data['body_text'] = str_replace("[~~ URL ~~]",$data['url'],$data['body_text']);

          $data['body_text'] = str_replace("[~~RESET_PASSORD_URL~~]",$data['url'],$data['body_text']);
          $data['body_text'] = str_replace("[~~ RESET_PASSORD_URL ~~]",$data['url'],$data['body_text']);

        }
        if (isset($data['school_name'])) {
          $data['body_text'] = str_replace("[~~SCHOOL_NAME~~]",$data['school_name'],$data['body_text']);
          $data['body_text'] = str_replace("[~~ SCHOOL_NAME ~~]",$data['school_name'],$data['body_text']);
        }
        if (isset($data['password'])) {
          $data['body_text'] = str_replace("[~~PASSWORD~~]",$data['password'],$data['body_text']);
          $data['body_text'] = str_replace("[~~ PASSWORD ~~]",$data['password'],$data['body_text']);
        }

        if (isset($data['first_name'])) {
          $data['body_text'] = str_replace("[~~FIRST_NAME~~]",$data['first_name'],$data['body_text']);
          $data['body_text'] = str_replace("[~~ FIRST_NAME ~~]",$data['first_name'],$data['body_text']);
        }

        if (isset($data['last_name'])) {
          $data['body_text'] = str_replace("[~~LAST_NAME~~]",$data['last_name'],$data['body_text']);
          $data['body_text'] = str_replace("[~~ LAST_NAME ~~]",$data['last_name'],$data['body_text']);
        }
        $data['body_text'] = str_replace("[~~HOSTNAME~~]",$http_host,$data['body_text']);
        $data['body_text'] = str_replace("[~~ HOSTNAME ~~]",$http_host,$data['body_text']);

        $data['body_text'] = str_replace("[~~SCHOOL_CODE~~]",'',$data['body_text']);
        $data['body_text'] = str_replace("[~~ SCHOOL_CODE ~~]",'',$data['body_text']);
        //message body
        $data['body_text']=wordwrap(trim($data['body_text']), 70, $eol);
        $data['body_text']=str_replace('<<~>>','&',$data['body_text']);
      }

      if (isset($data['subject'])&& !empty($data['subject'])) {
        
        $data['subject'] = str_replace("[~~ HOSTNAME ~~][~~ USER_NAME ~~]/index.html",$http_host,$data['subject']);
        
        $data['subject'] = str_replace("[~~HOSTNAME~~][~~USER_NAME~~]/index.html",$http_host,$data['subject']);
        if (isset($data['username'])) {
          $data['subject'] = str_replace("[~~USER_NAME~~]",$data['username'],$data['subject']);
          $data['subject'] = str_replace("[~~ USER_NAME ~~]",$data['username'],$data['subject']);
        
        }
        if (isset($data['url'])) {
          $data['subject'] = str_replace("[~~URL~~]",$data['url'],$data['subject']);
          $data['subject'] = str_replace("[~~ URL ~~]",$data['url'],$data['subject']);

          $data['subject'] = str_replace("[~~RESET_PASSORD_URL~~]",$data['url'],$data['subject']);
          $data['subject'] = str_replace("[~~ RESET_PASSORD_URL ~~]",$data['url'],$data['subject']);

        }
        if (isset($data['school_name'])) {
          $data['subject'] = str_replace("[~~SCHOOL_NAME~~]",$data['school_name'],$data['subject']);
          $data['subject'] = str_replace("[~~ SCHOOL_NAME ~~]",$data['school_name'],$data['subject']);
        }

        if (isset($data['first_name'])) {
          $data['subject'] = str_replace("[~~FIRST_NAME~~]",$data['first_name'],$data['subject']);
          $data['subject'] = str_replace("[~~ FIRST_NAME ~~]",$data['first_name'],$data['subject']);
        }

        if (isset($data['last_name'])) {
          $data['subject'] = str_replace("[~~LAST_NAME~~]",$data['last_name'],$data['subject']);
          $data['subject'] = str_replace("[~~ LAST_NAME ~~]",$data['last_name'],$data['subject']);
        }
        $data['subject'] = str_replace("[~~HOSTNAME~~]",$http_host,$data['subject']);
        $data['subject'] = str_replace("[~~ HOSTNAME ~~]",$http_host,$data['subject']);

        $data['subject'] = str_replace("[~~SCHOOL_CODE~~]",'',$data['subject']);
        $data['subject'] = str_replace("[~~ SCHOOL_CODE ~~]",'',$data['subject']);
        
      }
      
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
      
      if (isset($this->data['admin_email_from'])) {
        $admin_email_from = $this->data['admin_email_from'];
      } else {
        $admin_email_from = config('global.mail_from_address');
      }
      if (isset($this->data['admin_email_from_name'])) {
        $admin_email_from_name = $this->data['admin_email_from_name'];
      } else {
        $admin_email_from_name = config('global.mail_from_name');
      }

      return $this->from($admin_email_from, $admin_email_from_name)
                ->subject($this->data['subject'])->markdown('emails.sportlogin');
    }
}
