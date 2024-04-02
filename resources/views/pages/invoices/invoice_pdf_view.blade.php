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
        .table-bordered .extra_col_sub td{
            border: 0;
            font-weight: bold;
        }
        .table-bordered .extra_col_sub2 td{
            border: 0;
        }
        .extra_col_h{
            height: 10px;
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
        .payment-info table tr td{
            width: 33.33%;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo_area">
            <div class="left_part">
                <?php if ($invoice_data->logo !== null): ?>
                <img class="img_logo" src="{{ asset($invoice_data->logo) }}" alt="" style="height: 50px;">
                <?php endif; ?>
            </div>
            <div class="right_part">
                <div class="invoice_date">
                    <div><span class="txt">{{ __('date_of_invocie') }} </span><span class="date">{{ Carbon\Carbon::parse($invoice_data->date_invoice)->format('d.m.Y');}}</span></div>
                    <div class="padding_top"><span class="txt">{{ __('due_date_of_invocie') }}</span><span class="date">{{ $invoice_data->date_due ?  Carbon\Carbon::parse($invoice_data->date_due)->format('d.m.Y') : Carbon\Carbon::parse($invoice_data->date_invoice)->addDays(7)->format('d.m.Y')}}</span></div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="info_area" style="clear: both">
            <div class="left_cont">
                <p class="first_name">{{$invoice_data->seller_name}}</p>
                <p class="info_txt">{{$invoice_data->seller_street_number?$invoice_data->seller_street_number.',':''}} {{$invoice_data->seller_street}}</p>
                <p class="info_txt">{{$invoice_data->seller_zip_code}} {{$invoice_data->seller_place}}</p>
                <p class="info_txt">{{$invoice_data->seller_mobile?$invoice_data->seller_mobile.',':''}} {{$invoice_data->seller_phone}}</p>
                <p class="email"><a href="mailto:{{$invoice_data->seller_email}}">{{$invoice_data->seller_email}}</a></p>
            </div>
            <div class="right_cont">
                <p class="first_name">{{$invoice_data->client_name}}</p>
                <p class="last_name">{{$invoice_data->client_street_number ? $invoice_data->client_street_number.',': ''}} {{$invoice_data->client_street?$invoice_data->client_street:''}}</p>
                <p class="info_txt">{{$invoice_data->client_zip_code}} {{$invoice_data->client_place}}</p>
            </div>
        </div>
        <div class="invoice_table">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="col_date">{{ __('invoice_column_date') }}</th>
                        <th class="col_details">{{ __('invoice_column_details') }}</th>
                        <th class="col_duration"><?php if($invoice_data->invoice_type > 0) { ?>{{ __('invoice_column_duration') }}<?php } ?></th>
                        <th class="col_amount">{{ __('invoice_column_amount') }}</th>
                    </tr>
                </thead>
                <?php
                    $sub_total_lesson = 0;
                    $sub_total_min_lesson = 0;
                    $total_lesson = 0;
                    $sub_total_event = 0;
                    $sub_total_min_event = 0;
                    $total_event = 0;
                    $total_min = 0;
                    $total = 0;
                    $sub_total = 0;

                    foreach($invoice_items as $event_key => $invoice_item){ ?>
                    <tbody>
                        <?php
                            foreach($invoice_item as $key => $item){
                                $total_min += $item->unit;
                        ?>
                        @php
                        $item->item_date = Helper::formatDateTimeZone($item->item_date, 'long','UTC',$school->timezone);
                        @endphp
                        <tr>
                            <td>{{ !empty($item->item_date) ? Carbon\Carbon::parse($item->item_date)->format('d.m.Y') : ''; }}</td>
                            <td>
                            <?php echo htmlspecialchars_decode(!empty($item->caption) ? $item->caption : ''); ?>
                            <?php
                            $event = \App\Models\Event::find($item->event_id);
                            if ($event) {
                                $description = $event->description;
                                if($description){
                                    echo '<br>Description: ' . $description;
                                }
                            }
                           ?>
                           <br>
                            @if ($invoice_data->invoice_type > 0)
                                <?php
                                if($item->no_of_students == 1) {
                                    if($event_key == 10){
                                        echo 'Private';
                                    }
                                } else {
                                    if($event_key == 10){
                                    //echo 'Group of ' . $item->no_of_students . ' students';
                                    }
                                } ?>
                            @endif
                            </td>
                            <td style="text-align: left;"><?php if($item->unit){ echo $item->unit.' minutes';} ?> </td>
                            <td style="text-align: right;">
                            <?php
                                if($invoice_data->invoice_type == 1 || $invoice_data->invoice_type == 2 ){
                                    echo number_format($item->price_unit, '2');
                                }else{
                                    echo number_format($item->total_item, '2');
                                }
                            ?>
                            </td>
                        </tr>
                        <?php
                            if($event_key == 10){
                                if ($invoice_data->invoice_type == 1 || $invoice_data->invoice_type == 2){
                                    $sub_total_lesson += $item->price;
                                }else{
                                    $sub_total_lesson += $item->total_item;
                                }
                                $sub_total_min_lesson = $sub_total_min_lesson + $item->unit;
                            }else{
                                if($invoice_data->invoice_type == 1 || $invoice_data->invoice_type == 2){
                                    $sub_total_event += $item->price;
                                }else{
                                    $sub_total_event += $item->total_item;
                                }
                                $sub_total_min_event = $sub_total_min_event + $item->unit;
                            }

                            }
                        ?>
                        <?php if ($event_key == 10){ ?>
                            <tr class="extra_col_sub extra_col_h">
                                <td colspan="4"></td>
                            </tr>
                            <tr class="extra_col_sub">
                                <td colspan="2" style="text-align:right">Sub-total Lessons</td>
                                <td style="text-align:right">{{$sub_total_min_lesson}} minutes</td>
                                <td style="text-align:right">{{ number_format($sub_total_lesson,'2') }}</td>
                            </tr>
                            <?php if($invoice_data->lesson_discount_description || $invoice_data->event_discount_description){ ?>
                                <!--<div style="position:absolute; max-width:350px; font-size:12px; padding:5px; color:#000000; border:#EEE solid 1px;"> test
                                    <?php
                                        if($invoice_data->lesson_discount_description){
                                            echo $invoice_data->lesson_discount_description;
                                        }
                                    ?>
                                </div>-->
                            <?php } ?>
                            <?php if($invoice_data->amount_discount_1 != 0){ ?>
                                <tr class="extra_col_sub2">
                                    <td colspan="2" style="text-align:right">
                                        <?php
                                            if($invoice_data->invoice_type == 1){
                                                echo '<b>Discount on lesson</b>';
                                                if($invoice_data->lesson_discount_description){
                                                    echo '<br><span style="font-size:12px;">' . $invoice_data->lesson_discount_description . '</span>';
                                                }
                                            }else if($invoice_data->invoice_type == 2){
                                                echo '<b>Commission amount</b>';
                                            }else{
                                                echo '<b>Commission amount</b>';
                                            }
                                        ?>
                                    </td>
                                    <td style="text-align:right"><span style="font-size:12px;"><b>{{ $invoice_data->discount_percent_1 .' %' }}</b></span></td>
                                    <td class="price" style="text-align:right">- <b>{{ number_format(round(($sub_total_lesson*$invoice_data->discount_percent_1)/100),'2') }}</b></td>
                                    <?php $totalDiscount = number_format(($sub_total_lesson*$invoice_data->discount_percent_1)/100,'2'); ?>
                                </tr>

                                <?php } else { $totalDiscount = 0; }?>

                            <tr class="extra_col_sub">
                                <td colspan="3" style="text-align:right">Total Lesson:</td>
                                <td style="text-align:right">
                                    <?php
                                        $total_lesson = $sub_total_lesson - round($totalDiscount);
                                    ?>
                                    <span id="stotal_amount_with_discount_lesson"
                                    class="form-control-static numeric"
                                    style="text-align:right;">{{number_format($total_lesson,'2')}}</span>
                                </td>
                            </tr>

                            <?php if($invoice_data->extra_1 > 0){ ?>
                                <tr class="extra_col_sub2" style="text-decoration: none!important;">
                                    <td colspan="3" style="text-align:right"><b>Extra Lesson:</b>
                                    <br><span>{{ $invoice_data->extra_1_description }}</span>
                                    </td>
                                    <td style="text-align:right;" class="small">
                                        + {{number_format($invoice_data->extra_1, '2')}}
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if($invoice_data->extra_2 > 0){ ?>
                                <tr class="extra_col_sub2" style="text-decoration: none!important;">
                                    <td colspan="3" style="text-align:right"><b>Extra Event:</b>
                                    <br><span>{{ $invoice_data->extra_2_description }}</span>
                                    </td>
                                    <td style="text-align:right;" class="small">
                                        + {{number_format($invoice_data->extra_2, '2')}}
                                    </td>
                                </tr>
                            <?php } ?>

                            <tr class="extra_col_sub extra_col_h">
                                <td colspan="4"></td>
                            </tr>
                            <?php }else{ ?>
                                <tr class="extra_col_sub extra_col_h">
                                    <td colspan="4"></td>
                                </tr>
                                <tr class="extra_col_sub">
                                    <td colspan="2" style="text-align:right"><!--{{ __('invoice_sub_total') }}--></td>
                                    <td style="text-align:right"><!--{{$sub_total_min_event}} minutes--></td>
                                    <td style="text-align:right"><!--{{ number_format($sub_total_event,'2') }}--></td>
                                </tr>
                            <?php } ?>
                    </tbody>
                <?php } ?>
                <tfoot>


                    <?php if($invoice_data->amount_discount_2 != 0){ ?>
                        <?php if($invoice_data->lesson_discount_description || $invoice_data->event_discount_description){ ?>
                            <!--<div style="position:absolute; max-width:350px; font-size:12px; padding:5px; color:#000000; border:#EEE solid 1px;">
                                <?php
                                    if($invoice_data->event_discount_description){
                                        echo $invoice_data->event_discount_description;
                                    }
                                ?>
                            </div>-->
                        <?php } ?>
                        <tr class="extra_col">
                            <td colspan="2" style="text-align:right; font-size:12px;" class="text">
                                <?php
                                    if($invoice_data->invoice_type == 1){
                                        echo '<b>Discount on Event</b>';
                                        if($invoice_data->event_discount_description){
                                            echo '<br><span style="font-size:12px;">' . $invoice_data->event_discount_description . '</span>';
                                        }
                                    }else if($invoice_data->invoice_type == 2){
                                        echo '<b>Discount on Event</b>';
                                    }else{
                                        echo '<b>Discount on Event</b>';
                                    }
                                ?>
                            </td>
                            <td style="text-align:right"><span style="font-size:12px;"><b>{{ $invoice_data->discount_percent_2 .' %' }}</b></span></td>
                            <td class="price" style="text-align:right; font-size:12px;">
                                <?php $EventDiscountAmout = number_format((($sub_total_event-$invoice_data->extra_expenses)*$invoice_data->discount_percent_2)/100,'2'); ?>
                                - <b>{{ number_format((($sub_total_event-$invoice_data->extra_expenses)*$invoice_data->discount_percent_2)/100,'2') }}</b></td>
                            <?php $totalDiscountEvent = number_format(($sub_total_event*$invoice_data->discount_percent_2)/100,'2'); ?>
                        </tr>

                        <?php } else { $totalDiscountEvent = 0; }?>

                        <?php if($invoice_data->amount_discount_2 != 0){ ?>
                            <tr class="extra_col">
                            <td colspan="3" style="text-align:right; font-size:12px;"><b>Total Event:</b></td>
                            <td style="text-align:right" class="text">
                                    <?php
                                        $sub_total_event = $sub_total_event - $EventDiscountAmout;
                                    ?>
                                    <span id="stotal_amount_with_discount_event"
                                    class="form-control-static numeric"
                                    style="text-align:right; font-size:12px;">{{number_format($sub_total_event,'2')}}</span>
                                </td>
                            </tr>
                        <?php } ?>

                        <tr class="extra_col">
                            <td colspan="4" style="text-align:right; font-size:12px;"></td>
                        </tr>


                    <?php
                    $totalTaxesSupp=0;
                    if($invoice_data->tax_perc > 0){ ?>

                    <?php
                        if($invoice_data->invoice_type > 0)
                                    foreach ($InvoicesTaxData as $item) {
                                        echo '<tr class="extra_col">';
                                        echo '<td style="text-align:right" colspan="2" class="text"><b>' . $item['tax_name'] . '</b> <span style="font-size:11px;">[ N° ' . $item['tax_number'] . ' ]</span></td>';
                                        echo '<td style="text-align:right; font-size:13px;" class="text">' . $item['tax_percentage'] . '%</td>';
                                        echo '<td style="text-align:right" colspan="1" class="price"><b>' . number_format( ((($sub_total_event-$invoice_data->extra_expenses)+$total_lesson) * $item['tax_percentage']) /100, '2') . '</b></td>';
                                        echo '</tr>';
                                        //((($sub_total_event-$invoice_data->extra_expenses)+$total_lesson)*$item['tax_percentage'])/100;
                                        $totalTaxesSupp = ($totalTaxesSupp + ((($sub_total_event-$invoice_data->extra_expenses)+$total_lesson) * $item['tax_percentage']) /100);
                                    }
                        else {
                            foreach ($InvoicesTaxData as $item) {
                                        echo '<tr class="extra_col">';
                                        echo '<td style="text-align:right" colspan="2" class="text"><b>' . $item['tax_name'] . '</b> <span style="font-size:11px;">[ N° ' . $item['tax_number'] . ' ]</span></td>';
                                        echo '<td style="text-align:right; font-size:13px;" class="text">' . $item['tax_percentage'] . '%</td>';
                                        echo '<td style="text-align:right" colspan="1" class="price"><b>' . number_format( ((($sub_total_event)+$total_lesson) * $item['tax_percentage']) /100, '2') . '</b></td>';
                                        echo '</tr>';
                                        //((($sub_total_event-$invoice_data->extra_expenses)+$total_lesson)*$item['tax_percentage'])/100;
                                        $totalTaxesSupp = $invoice_data->invoice_type > 0 ? ($totalTaxesSupp + ((($sub_total_event)+$total_lesson) * $item['tax_percentage']) /100) : ($totalTaxesSupp + $invoice_data->extra_expenses + ((($sub_total_event)+$total_lesson) * $item['tax_percentage']) /100);
                                    }
                        }
                    ?>
                    <?php } ?>

                    <?php if($invoice_data->extra_expenses > 0){ ?>
                        <?php
                        foreach ($InvoicesExpData as $item) {
                            echo '<tr class="extra_col">';
                            echo '<td style="text-align:right" colspan="2" class="text"><b>' . $item['expense_name'] . '</b></td>';
                            echo '<td style="text-align:right" colspan="2" class="price">+ <b>' . $item['expense_amount'] . '</b></td>';
                            echo '</tr>';
                        }
                        ?>
                        <?php } ?>


                        <?php if(!empty($invoice_data->invoice_footer)){ ?>
                            <div style="position:absolute; max-width:350px; font-size:12px; padding:5px; color:#000000; border:#EEE solid 1px;">
                                <?php if(!empty($invoice_data->invoice_footer)){ ?>
                                    <div class="text"><small>Note:</small> {{ $invoice_data->invoice_footer }}</div>
                                <?php } ?>
                            </div>
                        <?php } ?>


                    <?php $total = $sub_total_event + $total_lesson + $invoice_data->extra_1 + $invoice_data->extra_2 + $totalTaxesSupp ; ?>
                    <tr class="total_col">
                         <td style="text-align:right" colspan="2" class="text">{{ __('invoice_total') }} <?php echo $invoice_data->invoice_currency ? ' ('.$invoice_data->invoice_currency .') ':''; ?> </td>
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
            <?php if($invoice_data->invoice_type > 0) { ?>
                <p>{{ __('invoice_time') }} {{ str_pad($hours, 2 ,'0', STR_PAD_LEFT) }} {{ __('invoice_duration_hours') }} {{ $minutes }} {{ __('invoice_duration_minutes') }}.</p>
            <?php } ?>
        </div>
    </main>
    <footer>
        <div class="title-top">{{ __('Payment Preference') }}</div>
        <div class="payment-info">
            <table class="table" style="border: 0;">
                <tr>
                    <td>
                        <?php if(!empty($invoice_data->etransfer_acc) || !empty($invoice_data->e_transfer_email)
                        || !empty($invoice_data->payment_bank_account_name) || !empty($invoice_data->payment_bank_name)
                        || !empty($invoice_data->payment_bank_zipcode) || !empty($invoice_data->payment_bank_place)
                        || $invoice_data->payment_bank_place || !empty($invoice_data->payment_bank_country_code) ) {?>
                            <div class="payment_title">
                                <?php if(!empty($invoice_data->etransfer_acc) || !empty($invoice_data->e_transfer_email)){ ?>
                                    E-Transfer
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <?php if($invoice_data->seller_country_code === "CA" || $invoice_data->seller_country_code === "US"){?>
                            <?php if(!empty($invoice_data->payment_bank_account_name)){?>
                                <div class="txt"><!--<b>Bank Name : </b>-->{{ $invoice_data->payment_bank_account_name }}</div>
                            <?php } ?>
                        <?php } ?>
                        <?php if($invoice_data->seller_country_code !== "CA" && $invoice_data->seller_country_code !== "US"){?>
                            <?php if(!empty($invoice_data->payment_bank_name)){?>
                                <div class="txt"><!--<b>Bank Name : </b>-->{{ $invoice_data->payment_bank_name }}</div>
                            <?php } ?>
                        <?php } ?>
                        <?php if(!empty($invoice_data->payment_bank_account && $invoice_data->seller_country_code !== 'CA' && $invoice_data->seller_country_code !== 'US')){ ?>
                            <div class="txt"><b>Account name : </b>{{ $invoice_data->payment_bank_account }}</div>
                        <?php } ?>
                        <?php if($invoice_data->seller_country_code === "CA" || $invoice_data->seller_country_code === "US"){?>
                            <div class="txt">{{ $invoice_data->payment_bank_account }}</div>
                            <div class="txt">{{ $invoice_data->payment_bank_iban }}</div>
                        <?php } ?>

                        <?php if(!empty($invoice_data->payment_bank_address)){?>
                            <div class="txt"><b>Address : </b>{{ $invoice_data->payment_bank_address }}</div>
                        <?php } ?>
                        <?php if(!empty($invoice_data->payment_bank_zipcode)){?>
                            <div class="txt"><b>Zipcode : </b>{{ $invoice_data->payment_bank_zipcode }}</div>
                        <?php } ?>
                        <?php if(!empty($invoice_data->payment_bank_place)){?>
                            <div class="txt"><b>Place : <b>{{ $invoice_data->payment_bank_place }}</div>
                        <?php } ?>
                        <!--<?php if(!empty($invoice_data->payment_bank_country_code)){?>
                            <div class="txt"><b>Bank Country Code : <b>{{ $invoice_data->payment_bank_country_code }}</div>
                        <?php } ?>-->
                        <?php if(!empty($invoice_data->etransfer_acc)){ ?>
                            <div class="txt"><b>{{ __('E-transfer') }}:</b> {{ $invoice_data->etransfer_acc }}</div>
                        <?php } ?>
                        <?php if(!empty($invoice_data->e_transfer_email)){?>
                            <div class="txt"><b>{{ __('invoice_footer_Email') }}</b>{{ $invoice_data->e_transfer_email }}</div>
                        <?php } ?>
                    </td>
                    <td>
                        <div class="payment_title"></div>
                        <?php if(!empty($invoice_data->name_for_checks) || !empty($invoice_data->cheque_payee)){ ?>
                            <div class="payment_title">{{ __('invoice_payment_subtitle_2') }}</div>
                        <?php } ?>
                        <?php if(!empty($invoice_data->name_for_checks)){ ?>
                            <div class="txt"><b>{{ __('invoice_footer_check_name') }}</b>{{ $invoice_data->name_for_checks }}</div>
                        <?php } ?>

                        <?php if(!empty($invoice_data->cheque_payee)){ ?>
                            <div class="txt"><b>{{ __('invoice_pay_by') }}</b>{{ $invoice_data->cheque_payee }}</div>
                        <?php } ?>
                        <?php if(!empty($invoice_data->payment_bank_iban)){ ?>
                            <?php if($invoice_data->seller_country_code !== "CA" && $invoice_data->seller_country_code !== "US") { ?>
                            <div class="txt"><b>{{ __('invoice_iban_no') }}</b>{{ $invoice_data->payment_bank_iban }}</div>
                        <?php } ?>
                        <?php } ?>
                        <?php if(!empty($invoice_data->payment_phone)){ ?>
                            <div class="txt"><b>{{ __('invoice_ac_no') }}</b>{{ $invoice_data->payment_bank_account }}</div>
                        <?php } ?>
                        <?php if(!empty($invoice_data->payment_bank_swift)){ ?>
                            <div class="txt"><b>{{ __('invoice_swift_no') }}</b>{{ $invoice_data->payment_bank_swift }}</div>
                        <?php } ?>
                        <?php if(!empty($invoice_data->payment_phone)){ ?>
                            <div class="txt"><b>{{ __('invoice_phone_number') }}</b>{{ $invoice_data->payment_phone }}</div>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div>
    </footer>
</body>

</html>
