<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <title>Sportlogin</title>
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
        .payment-info .txt{
            font-size: 12px;
            padding-top: 4px;
            font-weight: bold;
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
                    <div><span class="txt">{{ __('date_of_invocie') }} </span><span class="date">{{ Carbon\Carbon::parse($invoice_data->date_invoice)->format('d.m.Y');}}</span></div>
                    <div class="padding_top"><span class="txt">{{ __('due_date_of_invocie') }}</span><span class="date">{{ Carbon\Carbon::parse($invoice_data->period_ends)->format('d.m.Y');}}</span></div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="info_area" style="clear: both">
            <div class="left_cont">
                <p class="first_name">{{$invoice_data->client_name}}</p>
                <p class="last_name">{{$invoice_data->client_street_number ? $invoice_data->client_street_number.',': ''}} {{$invoice_data->client_street?$invoice_data->client_street:''}}</p>
                <p class="info_txt">{{$invoice_data->client_place}}</p>
            </div>
            <div class="right_cont">
                <p class="first_name">{{$invoice_data->seller_name}}</p>
                <p class="info_txt">{{$invoice_data->seller_place}}</p>
                <p class="info_txt">{{$invoice_data->seller_street_number?$invoice_data->seller_street_number.',':''}} {{$invoice_data->seller_street}}</p>
                <p class="info_txt">{{$invoice_data->seller_mobile?$invoice_data->seller_mobile.',':''}} {{$invoice_data->seller_phone}}</p>
                <p class="email"><a href="mailto:{{$invoice_data->seller_email}}">{{$invoice_data->seller_email}}</a></p>
            </div>
        </div>
        <div class="invoice_table">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="col_date">{{ __('invoice_column_date') }}</th>
                        <th class="col_details">{{ __('invoice_column_details') }}</th>
                        <th class="col_duration">{{ __('invoice_column_duration') }}</th>
                        <th class="col_amount">{{ __('invoice_column_amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $sub_total = 0;
                        $total_min = 0;
                        foreach($invoice_data->invoice_items as $key=> $item){ 
                            $total_min += $item->unit;    
                            if($invoice_data->invoice_type = 1 || $invoice_data->invoice_type = 2 ){
                                $sub_total += $item->price_unit;
                            }else{
                                $sub_total += $item->item_total;
                            }
                    ?>
                    <tr>
                        <td>{{ Carbon\Carbon::parse($item->item_date)->format('d.m.Y');}}</td>
                        <td>{{ !empty($item->caption) ? $item->caption : ''; }}</td>
                        <td align="right"><?php if($item->unit){ echo $item->unit.' minutes';} ?> </td>
                        <td align="right">
                        <?php 
                            if($invoice_data->invoice_type = 1 || $invoice_data->invoice_type = 2 ){
                                echo number_format($item->price_unit, '2');
                            }else{
                                echo number_format($item->item_total, '2');
                            }
                        ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <!-- <tr class="sub-total">
                        <td colspan="3" class="txt" style="border: 0px; padding:10px 0;">Sub-total</td>
                        <td class="price" style="border: 0px;padding:10px 0;">50.00</td>
                    </tr>
                    <tr>
                        <td>27.10.2022 00:00</td>
                        <td>asDXCZ</td>
                        <td align="right">20 minutes</td>
                        <td align="right">50.00</td>
                    </tr> -->

                    <tr class="sub-total">
                        <td colspan="3" class="txt" style="border: 0px; padding:10px 0;">{{ __('invoice_sub_total') }}</td>
                        <td align="right" class="price total" style="border: 0px;padding:10px 10px 0;text-align:right">{{ number_format($sub_total,'2') }}</td>
                    </tr>

                    <!-- <tr>
                        <td>27.10.2022 00:00</td>
                        <td>asDXCZ</td>
                        <td align="right">20 minutes</td>
                        <td align="right">50.00</td>
                    </tr> -->

                </tbody>
                <tfoot>
                    <!-- <tr>
                        <td colspan="4" class="total">80.00</td>
                    </tr> -->
                    <?php if($invoice_data->extra_expenses > 0){ ?>
                    <tr class="extra_col">
                        <td colspan="2" class="text">{{ __('invoice_extra_charges') }} </td>
                        <td colspan="2" class="price">+ {{ $invoice_data->extra_expenses }}</td>
                    </tr>
                    <?php } ?>

                    <?php if($invoice_data->total_amount_discount != 0){ ?>
                    <tr class="extra_col">
                        <td colspan="2" class="text">Discount</td>
                        <td colspan="2" class="price">- {{ $invoice_data->total_amount_discount }}</td>
                    </tr>
                    <?php } ?>
                    <!-- <tr class="extra_col">
                        <td colspan="2" class="text"></td>
                        <td colspan="2" class="price">+ 45.00</td>
                    </tr> -->
                    <?php $total = $sub_total + $invoice_data->extra_expenses - $invoice_data->total_amount_discount; ?>
                    <tr class="total_col">
                        <td align="center" colspan="2" class="text">{{ __('invoice_total') }} <?php echo $invoice_data->invoice_currency ? ' ('.$invoice_data->invoice_currency .') ':''; ?> </td>
                        <td colspan="2" class="price">{{ number_format($total, '2') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="course-duration">
            <?php 
                $hours = floor($total_min / 60);
                $minutes = $total_min % 60;
            ?>
            <p>{{ __('invoice_duration_text') }} {{ str_pad($hours, 2 ,'0', STR_PAD_LEFT) }} {{ __('invoice_duration_hours') }} {{ $minutes }} {{ __('invoice_duration_minutes') }}.</p>
        </div>
    </main>
    <footer>
        <div class="title-top">{{ __('invoice_payment_title') }}</div>
        <div class="payment-info">
            <table class="table" style="border: 0;">
                <?php if($invoice_data->seller_country_code === 'CA') {?>
                <tr>
                    <td>
                        <div class="payment_title">{{ __('invoice_payment_subtitle') }}</div>
                        <?php if($invoice_data->etransfer_acc){ ?>
                            <div class="txt"><b>{{ __('invoice_ac_no') }}</b>{{ $invoice_data->etransfer_acc }}</div>
                        <?php } ?>
                        <?php if($invoice_data->e_transfer_email){ ?>
                            <div class="txt"><b>{{ __('invoice_Email') }}</b>{{ $invoice_data->e_transfer_email }}</div>
                        <?php } ?>
                    </td>
                    <td>
                        <div class="payment_title">{{ __('invoice_payment_subtitle_2') }}</div>
                        <?php if($invoice_data->name_for_checks){ ?>
                            <div class="txt"><b>{{ __('invoice_check_name') }}</b>{{ $invoice_data->name_for_checks }}</div>
                        <?php } ?>
                        <?php if($invoice_data->cheque_payee){ ?>
                            <div class="txt"><b>{{ __('invoice_pay_by') }}</b>{{ $invoice_data->cheque_payee }}</div>
                        <?php } ?>
                    </td>
                    <td>
                        <div class="payment_title text_center">{{ __('invoice_payment_subtitle_3') }}</div>
                        <?php if($invoice_data->tax_amount){ ?>
                            <div class="txt"><b>{{ __('invoice_tax_amount') }}</b>{{ $invoice_data->tax_amount }}</div>
                        <?php } ?>
                    </td>
                </tr>
                <?php } else { ?>
                    <tr>
                        <td>
                            <div class="payment_title">{{ __('invoice_payment_subtitle') }}</div>
                            <?php if($invoice_data->etransfer_acc){ ?>
                                <div class="txt"><b>{{ __('invoice_ac_no') }}</b>{{ $invoice_data->etransfer_acc }}</div>
                            <?php } ?>
                            <?php if($invoice_data->e_transfer_email){ ?>
                                <div class="txt"><b>{{ __('invoice_payment_subtitle_1_Email') }}</b>{{ $invoice_data->e_transfer_email }}</div>
                            <?php } ?>
                        </td>
                        <td>
                            <div class="payment_title">{{ __('invoice_payment_subtitle_2') }}</div>
                            <?php if($invoice_data->payment_bank_iban){ ?>
                                <div class="txt"><b>{{ __('invoice_iban_no') }}</b>{{ $invoice_data->payment_bank_iban }}</div>
                            <?php } ?>
                            <?php if($invoice_data->payment_bank_account){ ?>
                                <div class="txt"><b>{{ __('invoice_ac_no') }}</b>{{ $invoice_data->payment_bank_account }}</div>
                            <?php } ?>
                            <?php if($invoice_data->cheque_payee){ ?>
                                <div class="txt"><b>{{ __('invoice_swift_no') }}</b>{{ $invoice_data->payment_bank_swift }}</div>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </footer>
</body>

</html>