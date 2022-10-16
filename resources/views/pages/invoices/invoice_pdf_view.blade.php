<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <title>invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style type='text/css'>
        body {
            font-family: 'Gilroy';
        }

        .logo_area {
            height: 60px;
        }

        .logo_area .left_part {
            float: left;
            width: 60%;
        }

        .logo_area .right_part {
            float: right;
            width: 40%;
        }
        .padding_top{
            padding-top: 10px;
        }
        .logo_area .right_part .invoice_date .txt {
            font-weight: bold;
            font-size: 15px;
            display: inline-block;
            width: 180px;
        }

        .logo_area .right_part .invoice_date .date {
            font-size: 14px;
        }

        .info_area {
            width: 100%;
            height: 110px;
            padding-bottom: 40px;
        }

        .info_area .left_cont {
            width: 42%;
            height: 100%;
            float: left;
            padding: 10px 15px 0;
            background: #e1eff7;
        }

        .info_area .right_cont {
            width: 42%;
            height: 100%;
            float: right;
            padding: 10px 15px 0;
            background: #e1eff7;
        }

        .info_area .left_cont p,
        .info_area .right_cont p {
            padding: 0;
        }

        .first_name {
            font-weight: bold;
            color: #0075BF;
            font-size: 14px;
            height: 5px;
        }

        .address {
            color: #000;
            font-size: 14px;
            height: 5px;
        }

        .last_name,
        .phone,
        .info_txt,
        .email {
            color: #000;
            font-size: 14px;
            height: 5px;
        }

        .email a {
            color: #000;
            text-decoration: none;
        }

        .table-bordered {
            width: 100%;
        }

        .table-bordered thead tr th,
        .table-bordered tbody tr td {
            border: 1px solid #B3D6EC;
        }

        .table-bordered thead tr th {
            padding: 8px 10px;
            font-size: 14px;
        }

        .table-bordered tbody tr td {
            padding: 4px 10px;
            font-size: 12px;
        }

        .table-bordered thead tr th {
            font-weight: bold;
        }

        .table-bordered .col_date {
            width: 100px;
        }

        .table-bordered .col_details {
            width: 350px;
        }

        .table-bordered .col_duration {
            width: 60px;
        }

        .table-bordered .col_amount {
            width: 50px;
        }

        .table-bordered .total {
            padding-top: 8px;
            padding-right: 10px;
            text-align: right;
            font-family: 'Gilroy-Bold';
            font-weight: bold;
            color: #0075BF;
            font-size: 14px;
        }

        .table-bordered .sub-total .txt {
            text-align: center;
            color: #0075BF;
            font-size: 14px;
        }

        .table-bordered .sub-total .price {
            text-align: center;
            color: #0075BF;
            padding-right: 10px;
        }

        .table-bordered .extra_col td {
            padding-top: 8px;
            padding-right: 10px;
        }

        .table-bordered .extra_col .text {
            text-align: center;
            font-size: 14px;
        }

        .table-bordered .extra_col .price {
            text-align: right;
            font-size: 14px;
        }

        .table-bordered .total_col td {
            padding-top: 10px;
            padding-right: 10px;
        }

        .table-bordered .total_col .text {
            text-align: center;
            font-family: 'Gilroy-Bold';
            color: #0075BF;
            font-size: 15px;
            font-weight: bold;
        }

        .table-bordered .total_col .price {
            text-align: right;
            font-family: 'Gilroy-Bold';
            color: #0075BF;
            font-size: 15px;
            font-weight: bold;
        }

        .course-duration {
            padding-top: 10px;
        }

        .course-duration p {
            font-size: 12px;
            text-align: right;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #03a9f4;
            padding: 5px 6px;
            background: #e1eff7;
        }
        .title-top{
            font-weight: bold;
            color: #0075BF;
            font-size: 15px;
            padding-bottom: 10px;
        }
        .payment_title{
            font-size: 13px;
            font-weight: bold;
        }
        .text_center{
            text-align: center;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo_area">
            <div class="left_part">
                <img class="img_logo" src="{{ public_path('img/invoice_logo.png') }}" alt="" style="height: 50px;">
            </div>
            <div class="right_part">
                <div class="invoice_date">
                    <div><span class="txt">Date of invoice: </span><span class="date">08.10.2022</span></div>
                    <div class="padding_top"><span class="txt">Due Date: </span><span class="date">08.10.2022</span></div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="info_area" style="clear: both">
            <div class="left_cont">
                <p class="first_name">bobby powali</p>
                <p class="last_name">bobby powali</p>
                <p class="email"><a href="mailto:jonduo@gmail.com">jonduo@gmail.com</a></p>
            </div>
            <div class="right_cont">
                <p class="first_name">bobby powali</p>
                <p class="info_txt">A7/5 DIAMOND PARK, JOKA</p>
                <p class="info_txt">A7/5 DIAMOND PARK, JOKA</p>
                <p class="info_txt">-700104 Joka</p>
                <p class="email"><a href="mailto:jonduo@gmail.com">jonduo@gmail.com</a></p>
            </div>
        </div>
        <div class="invoice_table">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="col_date">Date</th>
                        <th class="col_details">Details</th>
                        <th class="col_duration">Duration</th>
                        <th class="col_amount">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>20.10.2022 00:00</td>
                        <td>3e</td>
                        <td align="right">20 minutes</td>
                        <td align="right">30.00</td>
                    </tr>
                    <tr>
                        <td>27.10.2022 00:00</td>
                        <td>asDXCZ</td>
                        <td align="right">20 minutes</td>
                        <td align="right">50.00</td>
                    </tr>
                    <tr class="sub-total">
                        <td colspan="3" class="txt" style="border: 0px; padding:10px 0;">Sub-total</td>
                        <td class="price" style="border: 0px;padding:10px 0;">50.00</td>
                    </tr>
                    <tr>
                        <td>27.10.2022 00:00</td>
                        <td>asDXCZ</td>
                        <td align="right">20 minutes</td>
                        <td align="right">50.00</td>
                    </tr>
                    <tr class="sub-total">
                        <td colspan="3" class="txt" style="border: 0px; padding:10px 0;">Sub-total</td>
                        <td class="price" style="border: 0px;padding:10px 0;">76.57</td>
                    </tr>
                    <tr>
                        <td>27.10.2022 00:00</td>
                        <td>asDXCZ</td>
                        <td align="right">20 minutes</td>
                        <td align="right">50.00</td>
                    </tr>

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="total">80.00</td>
                    </tr>
                    <tr class="extra_col">
                        <td colspan="2" class="text">Extra charges </td>
                        <td colspan="2" class="price">+ 45.00</td>
                    </tr>
                    <tr class="extra_col">
                        <td colspan="2" class="text"></td>
                        <td colspan="2" class="price">+ 45.00</td>
                    </tr>
                    <tr class="total_col">
                        <td colspan="2" class="text">Total (CHF) </td>
                        <td colspan="2" class="price">165.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="course-duration">
            <p>Total duration of courses 75 minutes, 01 hours and 15 minutes.</p>
        </div>
    </main>
    <footer>
        <div class="title-top">Payment Information</div>
        <div class="payment-info">
            <table class="table" style="border: 0;">
                <tr>
                    <td>
                        <div class="payment_title">For payment by E-Transfer</div>
                    </td>
                    <td>
                        <div class="payment_title">For payment by check</div>
                    </td>
                    <td>
                        <div class="payment_title text_center">Tax Number</div>
                    </td>
                </tr>
            </table>
        </div>
    </footer>
</body>

</html>