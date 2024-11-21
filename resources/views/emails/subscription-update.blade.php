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


@if ($details['status'] === 'cancelled' || $details['cancel_at_period_end'] === true)
    <h3>Your Subscription was Canceled:</h3>
    <ul>
        <li>Plan: {{ $details['plan_name'] }}</li>
        <li>Status: Canceled</li>
        <li>Active Until: {{ $details['next_billing_date'] }}</li>
    </ul>
    <p>We are sorry to see you go! If you have any questions or feedback about your subscription, please contact our support team.</p>
@else
    <h3>Your Subscription was updated:</h3>
    
    <ul>
        <li>Plan: {{ $details['plan_name'] }}</li>
        <li>New status: {{ $details['status'] }}</li>
    </ul>
    <ul>
        <li>Next Billing Date: {{ $details['next_billing_date'] }}</li>
        <li>Amount: {{ $details['amount'] }} {{ $details['currency'] }}</li>
    </ul>
    <p>If you have any questions about your subscription, please don't hesitate to contact our support team.</p>
@endif

<div style="margin:0 auto; width:100%; max-width:500px; text-align:center; border-top:1px solid #EEE; color:#BBB;">  
<p>Best regards,<br>
    {{ config('app.name') }} Team</p>
</div>

</div>
</div>
