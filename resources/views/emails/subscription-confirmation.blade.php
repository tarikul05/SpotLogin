<!DOCTYPE html>
<html>
<head>
    <title>Subscription Updated</title>
</head>
<body>
<div class="wrapper" style="margin: 0; padding: 0; width: 100%; background-color: #fefefe;">
<div class="container_email" style="background-color: #ffffff; border-radius: 10px; padding: 20px; box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1); max-width: 600px; margin: 0 auto;">
<div style="width: 100%; text-align:center; border-bottom:1px solid #EEE;">
<img src="https://sportlogin.app/img/logo-blue.png" width="60px" />
<br><b style="color:#0075bf; font-size:22px;">Dear {{ $details['customer_name'] }},</b><br><br>
</div>

<h2>Thank you for your subscription!</h2>
<p>We are excited to have you as a member of our community.<br>
    Your subscription has been successfully processed, and you can now access all the features and benefits of our premium plan.
</p>

<h3>Subscription Details:</h3>

@if ($details['trial_end'] !== null)
    @php
        $trialEndTimestamp = $details['trial_end'];
        $currentTime = time();
    @endphp

    @if ($trialEndTimestamp > $currentTime)
        <p>=> Your trial period is active. It will end on: {{ \Carbon\Carbon::createFromTimestamp($trialEndTimestamp)->format('d-m-Y H:i:s') }}</p>
        <p>=> You will not be charged until the end of your trial period.</p>
    @endif
@endif

<ul>
    <li>Plan: {{ $details['plan_name'] }}</li>
    <li>Amount: {{ $details['amount'] }} {{ $details['currency'] }}</li>
    <li>Billing Period: {{ ucfirst($details['billing_period']) }}</li>
    <li>Start Date: {{ $details['start_date'] }}</li>
    <li>School: {{ $details['school_name'] }}</li>
</ul>

<h3>Payment Information:</h3>
<ul>
    <li>Payment Method: {{ $details['payment_method'] }}</li>
    <li>Next Billing Date: {{ $details['next_billing_date'] }}</li>
</ul>

<p>If you have any questions about your subscription, please don't hesitate to contact our support team.</p>

<div style="margin:0 auto; width:100%; max-width:500px; text-align:center; border-top:1px solid #EEE; color:#BBB;">  
<p>Best regards,<br>
{{ config('app.name') }} Team</p>
</div>

</div>
</div>