<?php 
  
  return [
    'app_debug' => env('APP_DEBUG', false),
    'mail_from_address' => env('MAIL_FROM_ADDRESS'),
    'mail_from_name' => env('MAIL_FROM_NAME'),
    'email_send' =>  (env('APP_ENV') =='local') ? 0 : 1
    
  ];