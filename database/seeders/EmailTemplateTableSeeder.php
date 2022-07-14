<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EmailTemplateTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('email_template')->delete();
        
        \DB::table('email_template')->insert(array (
            0 => 
            array (
                'id' => 1,
                'template_code' => 'forgot_password_email',
                'template_name' => 'Passwort-E-Mail vergessen',
                'subject_text' => 'www.sportogin.ch: Passwort zurücksetzen',
                'body_text' => '<h3>Hi<strong> [~~USER_NAME~~], </strong></h3>

<h3>Setzen Sie Ihr Passwort zurück</h3>

<h3>Bitte klicken Sie auf den untenstehenden Link, um Ihr Passwort zurückzusetzen.</h3>

<h2><a href="[~~URL~~]">Passwort zurücksetzen</a></h2>

<p>&nbsp;</p>

<p>Danke</p>

<p>Die Login-Seite ist über den folgenden Link erreichbar: <strong>[~~HOSTNAME~~][~~SCHOOL_CODE~~]/ </strong>
</p>',
                'language' => 'de',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'template_code' => 'forgot_password_email',
                'template_name' => 'Forgot Password Email',
                'subject_text' => 'www.sportogin.ch: Reset Your Password',
                'body_text' => '<h3>Hi<strong> [~~USER_NAME~~], </strong></h3>

<h3>Reset Your Password</h3>

<h3>Please click on the below link to reset your password.</h3>

<h2><a href="[~~URL~~]">Reset Password</a></h2>

<p>&nbsp;</p>

<p>Thank you</p>

<p>The login page is accessible by clicking on the following link: <strong>[~~HOSTNAME~~][~~SCHOOL_CODE~~]/ </strong>
</p>',
                'language' => 'en',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'template_code' => 'forgot_password_email',
                'template_name' => 'Mot de passe oublié',
                'subject_text' => 'www.sportogin.ch : Réinitialisez votre mot de passe',
                'body_text' => '<h3>Hi<strong> [~~USER_NAME~~], </strong></h3>

<h3>Réinitialisez votre mot de passe/h3>

<h3>Veuillez cliquer sur le lien ci-dessous pour réinitialiser votre mot de passe.</h3>

<h2><a href="[~~URL~~]">réinitialiser le mot de passe</a></h2>

<p>&nbsp;</p>

<p>Merci</p>

<p>La page de connexion est accessible en cliquant sur le lien suivant : <strong>[~~HOSTNAME~~][~~SCHOOL_CODE~~]/</strong>
</p>',
                'language' => 'fr',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'template_code' => 'reminder_email_unpaid',
                'template_name' => 'E-Mail-Erinnerung für unbezahlte Rechnung',
                'subject_text' => 'Rechnung ausstehende Zahlung',
                'body_text' => '<p><strong>Hallo [~~USER_NAME~~], </strong></p>

<p>Bis heute wurde Ihre Rechnung nicht ber&uuml;cksichtigt.</p>

<p>Bitte senden Sie mir einen Nachweis, wenn Sie bereits bezahlt haben. Wenn nicht, bitte ich Sie, die notwendigen Schritte zu unternehmen, um die Situation zu regeln.</p>

<p>Mit freundlichen Gr&uuml;&szlig;en.</p>

<p><strong>[~~SCHOOL_CODE~~]</strong></p>',
                'language' => 'de',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'template_code' => 'reminder_email_unpaid',
                'template_name' => 'e-mail reminder for unpaid invoice',
                'subject_text' => 'Invoice pending payment',
                'body_text' => '<p><strong>Hello [~~USER_NAME~~], </strong></p>

<p>This is a friendly reminder that you have an outstanding balance remaining on your account. Please pay this amount immediately to remain in good standing.</p>

<p>Sincerely,</p>

<p><strong>[~~SCHOOL_CODE~~]</strong></p>',
                'language' => 'en',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'template_code' => 'reminder_email_unpaid',
                'template_name' => 'email de relance pour facture impayée',
                'subject_text' => 'Facture en attente de paiement',
                'body_text' => '<p><strong>Hello [~~USER_NAME~~], </strong></p>

<p>Ceci est un rappel amical que vous avez une facture impayée.</p>

<p>Sincerement,</p>

<p><strong>[~~SCHOOL_CODE~~]</strong></p>',
                'language' => 'fr',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'template_code' => 'reset_pass_email',
                'template_name' => 'Passwort zurücksetzen',
                'subject_text' => NULL,
                'body_text' => NULL,
                'language' => 'de',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'template_code' => 'reset_pass_email',
                'template_name' => 'Reset password',
                'subject_text' => 'Reset Password',
                'body_text' => '<p>Hi,</p>

<p>Please use below link to reset your password.</p>

<p><a href="#">Reset</a></p>

<p>Thanks</p>

<p>&nbsp;</p>',
                'language' => 'en',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-02-12 12:24:24',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'template_code' => 'reset_pass_email',
                'template_name' => 'Réinitialiser le modèle de courriel',
                'subject_text' => NULL,
                'body_text' => NULL,
                'language' => 'fr',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'template_code' => 'school',
                'template_name' => 'Schulaktivierungs-E-Mail',
                'subject_text' => 'Willkommen! Deine Schule [~~SCHOOL_NAME~~] wurde geöffnet',
                'body_text' => '<pre>
<strong>Hallo und herzlich willkommen,</strong>

Sie wurden gerade in das Team von Vanessa Gusmeroli aufgenommen. Ihr Zugriff auf die <strong>[~~ SCHOOL_CODE ~~] </strong>Software wurde ge&ouml;ffnet. Hier Schritt f&uuml;r Schritt das folgende Verfahren:

Die Login-Seite ist erreichbar, indem Sie auf den folgenden Link klicken: <strong>[~~HOSTNAME~~][~~SCHOOL_CODE~~]/</strong>

Dein Nutzername lautet: <strong>[~~USER_NAME~~]</strong>
Ihr Passwort lautet: 11223344

Sobald Ihre Seite aktiviert ist, f&uuml;llen Sie bitte Ihre Schulinformationen in Ihrem Konto (Adresse, Telefon) aus. F&uuml;r Fragen stehen wir Ihnen selbstverst&auml;ndlich gerne zur Verf&uuml;gung.</pre>',
                'language' => 'de',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'template_code' => 'school',
                'template_name' => 'School Activation email',
                'subject_text' => 'Welcome! Your school [~~SCHOOL_NAME~~] has been opened',
                'body_text' => '<table border="0" cellpadding="0" cellspacing="0" style="width:100%">
<tbody>
<tr>
<td style="background-color:#0e2245; height:100px; text-align:center"><a href="[~~HOSTNAME~~][~~USER_NAME~~]/index.html"><img alt="SPORTLOGIN" src="http://sportlogin.ch/img/banner-sport-login.jpg" style="height:100%; width:100%" /></a></td>
</tr>
</tbody>
</table>
<!-- BEGIN BODY -->

<p><strong>Hello and welcome,</strong></p>

<p>You have just been accepted into the team of Sportlogin. Your access to the<strong> </strong>[~~SCHOOL_NAME~~] software has been opened. Here step by step the procedure to follow:</p>

<p>The login page is accessible by clicking on the following link:<strong> [~~ HOSTNAME ~~]/#login</strong></p>

<p>&nbsp;&nbsp;&nbsp; Your username is: <strong>[~~USER_NAME~~] </strong><br />
&nbsp;&nbsp;&nbsp; Your password is: [your choosen password]</p>

<p>Once your page is activated, please fill in your school information in your account (address, phone). We remain of course at your disposal for any question.</p>',
                'language' => 'en',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-07-14 11:36:48',
                'created_by' => 0,
                'modified_by' => 1,
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'template_code' => 'school',
            'template_name' => 'E-mail d\'activation scolaire(School Activation email)',
                'subject_text' => 'Bienvenue! Votre école [~~SCHOOL_NAME~~] a été ouverte',
                'body_text' => '<p><strong>Bonjour et bienvenue,</strong></p>

<p>Vous venez d`etre accept&eacute; dans l`&eacute;quipe de Vanessa Gusmeroli.Votre acc&egrave;s au logiciel de <strong>[~~SCHOOL_CODE~~]</strong>a &eacute;t&eacute; ouvert. Voici pas &agrave; pas la d&eacute;marche &agrave; suivre :</p>

<p>La page de connexion est accessible en cliquant sur le lien suivant : <strong>[~~HOSTNAME~~]</strong><strong>[~~SCHOOL_CODE~~]/</strong></p>

<ul>
<li>Votre nom d`utilisateur est: <strong>[~~USER_NAME~~]</strong></li>
<li>Votre mot de passe est : 11223344</li>
</ul>

<pre>
Une fois votre page activ&eacute;e, veuillez renseigner vos informations scolaires dans votre compte (adresse, t&eacute;l&eacute;phone). Nous restons bien entendu &agrave; votre disposition pour toute question.</pre>',
                'language' => 'fr',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'template_code' => 'send_approve_pdf_invoice',
                'template_name' => 'Rechnungs-E-Mail senden',
                'subject_text' => 'Rechnung ist online verfügbar',
                'body_text' => '<p>Hallo,</p>

<p>Ich informiere Sie, dass Ihre Rechnung des letzten Monats bereits auf der Software <strong>[~~SCHOOL_CODE~~]</strong>, in Ihrem pers&ouml;nlichen Bereich, im Tab &quot;Abrechnung&quot; verf&uuml;gbar ist.</p>

<p>Mit freundlichen Gr&uuml;&szlig;en.</p>

<p><strong>[~~SCHOOL_CODE~~]</strong></p>',
                'language' => 'de',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'template_code' => 'send_approve_pdf_invoice',
                'template_name' => 'Send bill email',
                'subject_text' => 'Invoice is available online',
                'body_text' => '<p>Hello,</p>

<p>Please find attached an invoice for&nbsp; &nbsp;[~~USER_NAME~~].</p>

<p>Thank you.</p>

<p>Sincerely,</p>

<p><strong>[~~SCHOOL_CODE~~]</strong></p>',
                'language' => 'en',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'template_code' => 'send_approve_pdf_invoice',
                'template_name' => 'email d\'annonce facture',
                'subject_text' => 'Facture est accessible en ligne',
                'body_text' => '<p>Veuillez trouver ci joint la facture de&nbsp; &nbsp;[~~USER_NAME~~].</p>

<p>Merci,</p>

<p>Sincèrement,</p>

<p><strong>[~~SCHOOL_CODE~~]</strong></p>',
                'language' => 'fr',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'template_code' => 'sign_up_confirmation_email',
                'template_name' => 'Sign up confirmation Congratulation mail',
                'subject_text' => 'Willkommen bei Sportlogin',
                'body_text' => '<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody>
<tr>
<td style="background-color:#0e2245; height:100px; text-align:center"><a href="[~~HOSTNAME~~][~~USER_NAME~~]/index.html"><img alt="SPORTLOGIN" src="http://sportlogin.ch/img/banner-sport-login.jpg" style="height:100%; width:100%" /></a></td>
</tr>
</tbody>
</table>
<!-- BEGIN BODY -->

<table align="center" cellpadding="15" style="width:100%">
<tbody>
<tr>
<td style="text-align:center">
<h2><span style="color:#2980b9"><strong>Willkommen bei Sportlogin!</strong></span></h2>

<p><span style="color:#2980b9">Bitte bestätigen Sie Ihr Konto, indem Sie auf klicken</span></p>

<p><strong><a href="[~~URL~~]">BESTÄTIGEN SIE</a></strong></p>

<p>[~~HOSTNAME~~][~~USER_NAME~~]/index.html</p>
</td>
</tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" style="width:100%">
</table>',
                'language' => 'de',
                'is_active' => 'Y',
                'created_at' => '2022-01-16 11:39:51',
                'modified_at' => '2022-01-16 11:40:02',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'template_code' => 'sign_up_confirmation_email',
                'template_name' => 'Sign up confirmation Congratulation mail',
                'subject_text' => 'Welcome to Sportlogin',
                'body_text' => '<table border="0" cellpadding="0" cellspacing="0" style="width:100%">
<tbody>
<tr>
<td style="background-color:#0e2245; height:100px; text-align:center"><a href="[~~HOSTNAME~~][~~USER_NAME~~]/index.html"><img alt="SPORTLOGIN" src="http://sportlogin.ch/img/banner-sport-login.jpg" style="height:100%; width:100%" /></a></td>
</tr>
</tbody>
</table>
<!-- BEGIN BODY -->

<table align="center" cellpadding="15" style="width:100%">
<tbody>
<tr>
<td style="text-align:center">
<h2><strong>Welcome to Sportlogin!</strong></h2>

<p><em>Welcome to Sportlogin, an invite has been sent to you by </em>[~~SCHOOL_NAME~~]</p>

<p>If you do not already have an account, please click <strong><a href="[~~URL~~]">HERE</a></strong></p>

<p>[~~HOSTNAME~~][~~USER_NAME~~]/</p>
</td>
</tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" style="width:100%">
</table>',
                'language' => 'en',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 22:20:15',
                'modified_at' => '2022-07-14 11:20:58',
                'created_by' => 0,
                'modified_by' => 1,
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'template_code' => 'sign_up_confirmation_email',
                'template_name' => 'Sign up confirmation Congratulation mail',
                'subject_text' => 'Bienvenue sur Sportlogin',
                'body_text' => '<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody>
<tr>
<td style="background-color:#0e2245; height:100px; text-align:center"><a href="[~~HOSTNAME~~][~~USER_NAME~~]/index.html"><img alt="SPORTLOGIN" src="http://sportlogin.ch/img/banner-sport-login.jpg" style="height:100%; width:100%" /></a></td>
</tr>
</tbody>
</table>
<!-- BEGIN BODY -->

<table align="center" cellpadding="15" style="width:100%">
<tbody>
<tr>
<td style="text-align:center">
<h2><span style="color:#2980b9"><strong>Bienvenue sur Sportlogin!</strong></span></h2>

<p><span style="color:#2980b9">Veuillez confirmer votre compte en cliquant sur</span></p>

<p><strong><a href="[~~URL~~]">CONFIRMER</a></strong></p>

<p>[~~HOSTNAME~~][~~USER_NAME~~]/index.html</p>
</td>
</tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" style="width:100%">
</table>',
                'language' => 'fr',
                'is_active' => 'Y',
                'created_at' => '2022-01-16 11:39:51',
                'modified_at' => '2022-01-16 11:39:51',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'template_code' => 'student',
                'template_name' => 'Willkommen E-Mail und Kontoaktivierung für Schüler',
                'subject_text' => 'Willkommen! Ihr Zugang zu [~~SCHOOL_CODE~~] wurde geöffnet',
                'body_text' => '<p>Hallo und willkommen,</p>

<p>Du wurdest gerade in die Schule aufgenommen <strong>[~~SCHOOL_CODE~~].</strong> Ihr Zugang zur Sportlogin.ch Software wurde ge&ouml;ffnet.</p>

<p>Hier Schritt f&uuml;r Schritt das folgende Verfahren:</p>

<p>Die Login-Seite erreichen Sie &uuml;ber den folgenden Link: <strong>[~~HOSTNAME~~][~~SCHOOL_CODE~~]/</strong>
</p>

<p>Dein Benutzername ist: <strong>[~~USER_NAME~~] </strong></p>

<p>Ihr Passwort lautet: <strong>11223344</strong></p>

<p>Sobald Ihre Seite aktiviert ist, geben Sie bitte Ihre pers&ouml;nlichen Daten in Ihrem Konto (Adresse, Telefon) ein. F&uuml;r Fragen stehen wir Ihnen selbstverst&auml;ndlich gerne zur Verf&uuml;gung.</p>

<p>Mit freundlichen Gr&uuml;&szlig;en.</p>

<p><strong>[~~SCHOOL_CODE~~]</strong></p>',
                'language' => 'de',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'template_code' => 'student',
                'template_name' => 'welcome email and account activation for student',
                'subject_text' => 'Welcome ! Your access to [~~SCHOOL_CODE~~] has been opened',
                'body_text' => '<p>Hello and welcome,</p>

<p>You have just been accepted to school <strong>[~~SCHOOL_CODE~~]</strong>.</p>

<p>Your access to the Sportlogin.ch software has been opened. Here step by step the procedure to follow:</p>

<p>The login page is accessible by clicking on the following link: <strong>[~~HOSTNAME~~][~~SCHOOL_CODE~~]/</strong>
</p>

<p>Your username is: <strong>[~~USER_NAME~~] </strong></p>

<p>Your password is: <strong>11223344</strong></p>

<p>Once your page is activated, please fill in your personal information in your account (address, phone). We remain of course at your disposal for any question.</p>

<p>Cordially.</p>

<p><strong>[~~SCHOOL_CODE~~]</strong></p>',
                'language' => 'en',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'template_code' => 'student',
                'template_name' => 'email de bienvenue et activation du compte pour élève',
                'subject_text' => 'Bienvenue ! Votre accès à [~~SCHOOL_CODE~~] a été ouvert',
                'body_text' => '<p>Bonjour et bienvenue,</p>

<p>Vous venez d`&ecirc;tre accept&eacute; dans l`&eacute;cole <strong>[~~SCHOOL_CODE~~].</strong> Votre acc&egrave;s au logiciel de Sportlogin.ch a &eacute;t&eacute; ouvert.</p>

<p>Voici pas &agrave; pas la d&eacute;marche &agrave; suivre :</p>

<p>La page de connexion est accessible en cliquant sur le lien suivant : <strong>[~~HOSTNAME~~][~~SCHOOL_CODE~~]/</strong>
</p>

<p>Votre nom d`utilisateur est : <strong>[~~USER_NAME~~]</strong></p>

<p>Votre mot de passe est : <strong>11223344 </strong></p>

<p>Une fois votre page activ&eacute;e, merci de compl&eacute;ter vos informations personnelles dans votre compte (adresse, telephone). Nous restons bien entendu &agrave; votre disposition pour toute question.</p>

<p>Cordialement.</p>

<p><strong>[~~SCHOOL_CODE~~]</strong></p>',
                'language' => 'fr',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'template_code' => 'student_activation_email',
                'template_name' => 'Student Activation Mail',
                'subject_text' => 'Welcome to Sportlogin',
                'body_text' => '<table border="0" cellpadding="0" cellspacing="0" style="height:52px; width:1035px">
<tbody>
<tr>
<td style="background-color:#0e2245; height:100px; text-align:center"><a href="[~~HOSTNAME~~]index.html"><img alt="SPORTLOGIN" src="[~~HOSTNAME~~]img/banner-sport-login.jpg" style="height:100%; width:100%" /></a></td>
</tr>
</tbody>
</table>
<!-- BEGIN BODY -->

<table align="center" cellpadding="15" style="width:100%">
<tbody>
<tr>
<td style="text-align:center">
<h2><span style="color:#2980b9"><strong>Willkommen bei Sportlogin!</strong></span></h2>
<p>Sie wurden eingeladen von [~~SCHOOL_CODE~~], Bitte bestätigen Sie Ihr Konto und geben Sie Ihre Daten ein.</p>
<p><span style="color:#2980b9">Bitte bestätigen Sie Ihr Konto, indem Sie auf klicken</span></p>

<p><strong><a href="[~~URL~~]">BESTÄTIGEN SIE</a></strong></p>
<p>Dein Benutzername ist: <strong>"[~~USER_NAME~~]"</strong></p>
<p>Anmeldung [~~HOSTNAME~~][~~SCHOOL_CODE~~]/</p>
</td>
</tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" style="width:100%">
</table>',
                'language' => 'de',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'template_code' => 'student_activation_email',
                'template_name' => 'Student Activation Mail',
                'subject_text' => 'Welcome to Sportlogin',
                'body_text' => '<table border="0" cellpadding="0" cellspacing="0" style="height:52px; width:1035px">
<tbody>
<tr>
<td style="background-color:#0e2245; height:100px; text-align:center"><a href="[~~HOSTNAME~~]index.html"><img alt="SPORTLOGIN" src="[~~HOSTNAME~~]img/banner-sport-login.jpg" style="height:100%; width:100%" /></a></td>
</tr>
</tbody>
</table>
<!-- BEGIN BODY -->

<table align="center" cellpadding="15" style="width:100%">
<tbody>
<tr>
<td style="text-align:center">
<h2><span style="color:#2980b9"><strong>Welcome to Sportlogin!</strong></span></h2>
<p>You have been invited by [~~SCHOOL_CODE~~], Please confirm your account and fill in your information.</p>
<p><span style="color:#2980b9">Please confirm your account by clicking on</span></p>

<p><strong><a href="[~~URL~~]">CONFIRM</a></strong></p>
<p>Your username is: <strong>"[~~USER_NAME~~]"</strong></p>
<p>log-in [~~HOSTNAME~~][~~SCHOOL_CODE~~]/</p>
</td>
</tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" style="width:100%">
</table>',
                'language' => 'en',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'template_code' => 'student_activation_email',
                'template_name' => 'Student Activation Mail',
                'subject_text' => 'Bienvenue sur Sportlogin',
                'body_text' => '<table border="0" cellpadding="0" cellspacing="0" style="height:52px; width:1035px">
<tbody>
<tr>
<td style="background-color:#0e2245; height:100px; text-align:center"><a href="[~~HOSTNAME~~]index.html"><img alt="SPORTLOGIN" src="[~~HOSTNAME~~]img/banner-sport-login.jpg" style="height:100%; width:100%" /></a></td>
</tr>
</tbody>
</table>
<!-- BEGIN BODY -->

<table align="center" cellpadding="15" style="width:100%">
<tbody>
<tr>
<td style="text-align:center">
<h2><span style="color:#2980b9"><strong>Bienvenue sur Sportlogin!</strong></span></h2>
<p>Vous avez été invité par [~~SCHOOL_CODE~~], Veuillez confirmer votre compte et remplir vos informations.</p>
<p><span style="color:#2980b9">Veuillez confirmer votre compte en cliquant sur</span></p>

<p><strong><a href="[~~URL~~]">CONFIRMER</a></strong></p>
<p>Votre nom d\'utilisateur est: <strong>"[~~USER_NAME~~]"</strong></p>
<p>connexion [~~HOSTNAME~~][~~SCHOOL_CODE~~]/</p>
</td>
</tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" style="width:100%">
</table>',
                'language' => 'fr',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'template_code' => 'teacher',
                'template_name' => 'Willkommensmail und Kontoaktivierung für den Lehrer',
                'subject_text' => 'Willkommen! Ihr Zugang zu [~~SCHOOL_CODE~~] wurde geöffnet',
                'body_text' => '<p>Hallo und willkommen,</p>

<p>Du wurdest gerade in die Schule aufgenommen <strong>[~~SCHOOL_CODE~~].</strong></p>

<p>Ihr Zugang zur Sportlogin.ch Software wurde ge&ouml;ffnet. Hier Schritt f&uuml;r Schritt das folgende Verfahren:</p>

<p>Die Login-Seite erreichen Sie &uuml;ber den folgenden Link: <strong>[~~HOSTNAME~~][~~SCHOOL_CODE~~]/ </strong></p>

<p>Dein Benutzername ist: <strong>[~~USER_NAME~~]</strong></p>

<p>Ihr Passwort lautet: 11223344</p>

<p>Sobald Ihre Seite aktiviert ist, geben Sie bitte Ihre pers&ouml;nlichen Daten in Ihrem Konto (Adresse, Telefon) ein. F&uuml;r Fragen stehen wir Ihnen selbstverst&auml;ndlich gerne zur Verf&uuml;gung.</p>

<p>Mit freundlichen Gr&uuml;&szlig;en.</p>

<p><strong>[~~SCHOOL_CODE~~]</strong></p>',
                'language' => 'de',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'template_code' => 'teacher',
                'template_name' => 'welcome email and account activation for the teacher',
                'subject_text' => 'Welcome ! Your access to [~~SCHOOL_CODE~~] has been opened',
                'body_text' => '<p>Hello and welcome,</p>

<p>You have just been accepted to school <strong>[~~SCHOOL_CODE~~]. </strong></p>

<p>Your access to the Sportlogin.ch software has been opened. Here step by step the procedure to follow:</p>

<p>The login page is accessible by clicking on the following link: [~~HOSTNAME~~][~~SCHOOL_CODE~~]/
</p>


<p>Your username is:<strong> [~~USER_NAME~~] </strong></p>

<p>Your password is: <strong>11223344 </strong></p>

<p>Once your page is activated, please fill in your personal information in your account (address, phone). We remain of course at your disposal for any question.</p>

<p>Cordially.</p>

<p><strong>[~~SCHOOL_CODE~~]</strong></p>',
                'language' => 'en',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'template_code' => 'teacher',
                'template_name' => 'email de bienvenue et activation du compte pour le professeur',
                'subject_text' => 'Bienvenue ! Votre accès à [~~SCHOOL_CODE~~] a été ouvert',
                'body_text' => '<p>Bonjour et bienvenue,</p>

<p>Vous venez d`&ecirc;tre accept&eacute; dans l`&eacute;cole<strong> [~~SCHOOL_CODE~~]. </strong></p>

<p>Votre acc&egrave;s au logiciel de Sportlogin.ch a &eacute;t&eacute; ouvert. Voici pas &agrave; pas la d&eacute;marche &agrave; suivre :</p>

<p>La page de connexion est accessible en cliquant sur le lien suivant : [~~HOSTNAME~~][~~SCHOOL_CODE~~]/
</p>


<p>Votre nom d`utilisateur est :<strong> [~~USER_NAME~~] </strong></p>

<p>Votre mot de passe est : <strong>11223344 </strong></p>

<p>Une fois votre page activ&eacute;e, merci de compl&eacute;ter vos informations personnelles dans votre compte (adresse, telephone). Nous restons bien entendu &agrave; votre disposition pour toute question.</p>

<p>Cordialement.</p>

<p><strong>[~~SCHOOL_CODE~~]</strong></p>',
                'language' => 'fr',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 21:56:34',
                'modified_at' => '2022-01-03 21:56:34',
                'created_by' => 0,
                'modified_by' => NULL,
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'template_code' => 'sign_up_confirmation_email_exist',
                'template_name' => 'Sign up confirmation mail existing user',
                'subject_text' => 'Welcome to Sportlogin',
                'body_text' => '<table border="0" cellpadding="0" cellspacing="0" style="width:100%">
<tbody>
<tr>
<td style="background-color:#0e2245; height:100px; text-align:center"><a href="[~~HOSTNAME~~][~~USER_NAME~~]/index.html"><img alt="SPORTLOGIN" src="http://sportlogin.ch/img/banner-sport-login.jpg" style="height:100%; width:100%" /></a></td>
</tr>
</tbody>
</table>
<!-- BEGIN BODY -->

<table align="center" cellpadding="15" style="width:100%">
<tbody>
<tr>
<td style="text-align:center">
<h2><strong>Welcome to Sportlogin!</strong></h2>

<p><em>Welcome to Sportlogin, an invite has been sent to you by </em>[~~SCHOOL_NAME~~]</p>

<p>If you already have an account, please click <strong><a href="[~~URL~~]">HERE</a></strong></p>

<p>[~~HOSTNAME~~][~~USER_NAME~~]/</p>
</td>
</tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" style="width:100%">
</table>',
                'language' => 'en',
                'is_active' => 'Y',
                'created_at' => '2022-01-03 22:20:15',
                'modified_at' => '2022-07-14 11:20:41',
                'created_by' => 0,
                'modified_by' => 1,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}