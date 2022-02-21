<?php 
  
  return [
    'app_debug' => env('APP_DEBUG', false),
    'mail_from_address' => env('MAIL_FROM_ADDRESS'),
    'mail_from_name' => env('MAIL_FROM_NAME'),
    'email_send' =>  (env('APP_ENV') =='local') ? 0 : 1,
    // 'email_template' => array(
    //   'forgot_password_email'     => __('forgot_password_email'),
    //   'reminder_email_unpaid' => __('reminder_email_unpaid'),
    //   'reset_pass_email' => __('reset_pass_email'),
    //   'school' => __('school'),
    //   'send_approve_pdf_invoice' => __('send_approve_pdf_invoice'),
    //   'sign_up_confirmation_email' => __('sign_up_confirmation_email'),
    //   'student' => __('student'),
    //   'student_activation_email' => __('student_activation_email'),
    //   'teacher' => __('teacher'),
    // ),
    
  ];