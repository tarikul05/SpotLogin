<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Config;

class SpotloginEmail extends Mailable
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
      $http_host=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/" ;   
      $eol = "\r\n";        
      if (isset($data['body_text'])&& !empty($data['body_text'])) {
        $data['body_text'] = str_replace("[~~HOSTNAME~~][~~USER_NAME~~]/index.html",$http_host,$data['body_text']);
        if ($data['username']) {
          $data['body_text'] = str_replace("[~~USER_NAME~~]",$data['username'],$data['body_text']);
        
        }
        if ($data['url']) {
          $data['body_text'] = str_replace("[~~URL~~]",$data['url'],$data['body_text']);

        }
        
        
        
        // //message body
        $data['body_text']=wordwrap(trim($data['body_text']), 70, $eol);
        $data['body_text']=str_replace('<<~>>','&',$data['body_text']);
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
      $admin_email_from = config('global.mail_from_address');
      $admin_email_from_name = config('global.mail_from_name');
      return $this->from($admin_email_from, $admin_email_from_name)
                ->subject($this->data['subject'])->markdown('emails.spotLogin');
    }
}
