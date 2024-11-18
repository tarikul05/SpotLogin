<!DOCTYPE html>
<html>
<head>
    <title>Payment Confirmation</title>
</head>
<body>
<div class="wrapper" style="margin: 0; padding: 0; width: 100%; background-color: #fefefe;">
<div class="container_email" style="background-color: #ffffff; border-radius: 10px; padding: 20px; box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1); max-width: 600px; margin: 0 auto;">
<div style="width: 100%; text-align:center; border-bottom:1px solid #EEE;">
<img src="https://sportlogin.app/img/logo-blue.png" width="60px" />
<br><b style="color:#0075bf; font-size:22px;">Dear {{ $paymentData['student_name'] }},</b><br><br>
</div>
<h2>Payment Confirmation</h2>
<p>Dear {{ $paymentData['student_name'] }},</p>

<p>Your payment has been successfully processed:</p>

<ul>
<li>Amount: {{ $paymentData['amount'] }} {{ $paymentData['currency'] }}</li>
<li>Invoice Number: {{ $paymentData['invoice_id'] }}</li>
<li>Coach: {{ $paymentData['coach_name'] }}</li>
<li>Date: {{ $paymentData['date'] }}</li>
</ul>

<p>Thank you for your payment!</p>
<br><br><br>
<div style="margin:0 auto; width:100%; max-width:500px; text-align:center; border-top:1px solid #EEE; color:#BBB;">  
<p>Best regards,<br>
{{ $paymentData['coach_name'] }}</p>
</div>
</div>
</div>
</body>
</html>