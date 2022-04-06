<?php 
  
  return [
    'app_debug' => env('APP_DEBUG', false),
    'mail_from_address' => env('MAIL_FROM_ADDRESS'),
    'mail_from_name' => env('MAIL_FROM_NAME'),
    'email_send' =>  (env('APP_ENV') =='local') ? 0 : 1,
    'template_variables' => array(
      array(
        'value'     => '[~~HOSTNAME~~]',
        'drop_text'     => 'Hostname',
        'drop_label'     => 'Hostname',
      ),
      array(
        'value'     => '[~~SCHOOL_CODE~~]',
        'drop_text'     => 'School Code',
        'drop_label'     => 'School Code',
      ),
      array(
        'value'     => '[~~SCHOOL_NAME~~]',
        'drop_text'     => 'School Name',
        'drop_label'     => 'School Name',
      ),
      array(
        'value'     => '[~~USER_NAME~~]',
        'drop_text'     => 'User Name',
        'drop_label'     => 'User Name',
      ),
      array(
        'value'     => '[~~RESET_PASSORD_URL~~]',
        'drop_text'     => 'Reset Password UR',
        'drop_label'     => 'Reset Password URL',
      ),
      array(
        'value'     => '[~~FIRST_NAME~~]',
        'drop_text'     => 'First Name',
        'drop_label'     => 'First Name',
      ),
      array(
        'value'     => '[~~LAST_NAME~~]',
        'drop_text'     => 'Last Name',
        'drop_label'     => 'Last Name',
      ),
      array(
        'value'     => '[~~CLIENT_NAME~~]',
        'drop_text'     => 'Client Name',
        'drop_label'     => 'Client Name',
      ),
      array(
        'value'     => '[~~PASSWORD~~]',
        'drop_text'     => 'Password',
        'drop_label'     => 'Password',
      )
    ),
    'email_template' => array(
      'forgot_password_email'     => 'forgot_password_email',
      'reminder_email_unpaid' => 'reminder_email_unpaid',
      'reset_pass_email' => 'reset_pass_email',
      'school' => 'school',
      'send_approve_pdf_invoice' => 'send_approve_pdf_invoice',
      'sign_up_confirmation_email' => 'sign_up_confirmation_email',
      'student' => 'student',
      'student_activation_email' => 'student_activation_email',
      'teacher' => 'teacher',
    ),
    'legal_status' => array(
      array(
        'code'     => 11,
        'drop_text' => 'Company (individual reason)'
      ),
      array(
        'code'     => 10,
        'drop_text'     => 'Society'
      ),
      array(
        'code'     => 20,
        'drop_text'     => 'Association'
      ),
      array(
        'code'     => 30,
        'drop_text'     => 'Foundation'
      ),
     
    ),
    'user_default_password' => env('USER_DEFAULT_PASSWORD', 12345678),
    'gender'=>[
      1 => 'Male',
      2 => 'Femail',
      3 => 'Not specified',
    ],
    
    'token_validity' => 2, //days

    'event_type' =>[
      10  => 'lesson',
      100 =>'Event',
      50  => 'Coach time off',
      51  => 'Student time off',
    ],
    
  ];