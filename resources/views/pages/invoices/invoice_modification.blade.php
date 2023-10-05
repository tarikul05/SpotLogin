@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{ asset('js/datetimepicker-lang/moment-with-locales.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<!-- color wheel -->
<script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
@endsection
<?php
    $invoiceIssued = ($invoice->invoice_status ==10);
    $totalWithDiscount=0;
 ?>
@section('content')
<div class="content">
	<div class="container-fluid">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-6 header-area">
				<div class="page_header_class">
						<label id="page_header" name="page_header"><i class="fa-solid fa-file-invoice"></i> {{ __('Invoice Detail')}}</label>
					</div>
				</div>
				<div class="col-sm-6 col-6 btn-area" style="text-align: right;">
					    <div class="pull-right btn-group save-button" id="invoice_modification">

                            @if($invoice->invoice_status ==10)

                            @if(!$AppUI->isStudent())
                                @if($invoice->payment_status ==0)
                                    <a id="payment_btn" target href class="btn btn-theme-warn">
                                        <i class="fa-solid fa-hand-holding-dollar"></i>  <span class="d-none d-sm-block">{{__('Flag as Paid')}}</span>
                                    </a>
                                @else
                                    <a id="payment_btn" target href class="btn btn-theme-success"><i class="fa fa-money" aria-hidden="true"></i>
                                        <i class="fa-solid fa-hand-holding-dollar"></i> <span class="d-none d-sm-block">{{__('Flag as UnPaid')}}</span>
                                    </a>
                                @endif
                                <button id="approved_btn" target="" href="" class="btn btn-theme-success" onclick="SendPayRemiEmail({{$invoice->id}},{{$invoice->invoice_type}},{{$invoice->school_id}})"><i class="fa-solid fa-envelope-open-text"></i> <span class="d-none d-sm-block">{{__('Send by email')}}</span></button>
                            @endif
                            <a id="download_pdf_btn_a" target="_blank" href="{{ route('generateInvoicePDF',['invoice_id'=> $invoice->id, 'type' => 'print_view']) }}" class="btn btn-theme-outline">
                                    <label name="download_pdf_btn d-none d-sm-block" id="download_pdf_btn"><i class="fa-solid fa-file-pdf"></i> <span class="d-none d-sm-block"> {{__('Download PDF')}}</span></label>
                                </a>

                            @else
                                <a id="issue_inv_btn" name="issue_inv_btn" class="btn btn-sm btn-success" target="">
                                    <i class="fa-solid fa-check"></i> <span class="d-none d-sm-block">{{__('Issue invoice')}}</span>
                                </a>
                                <a id="print_preview_btn" href="{{ route('generateInvoicePDF',['invoice_id'=> $invoice->id, 'type' => 'print_view']) }}" name="print_preview_btn" class="btn btn-theme-outline" target="_blank"><i class="fa-solid fa-file-pdf"></i> <span class="d-none d-sm-block">{{__('Print Preview')}}</span></a>

                            @endif

                            <a id="delete_btn_inv" name="delete_btn_inv" class="btn btn-theme-warn" href=""><i class="fa-solid fa-trash"></i> <span class="d-none d-sm-block">{{__('Delete')}}</span></a>
                            <a id="save_btn" name="save_btn" class="btn btn-theme-success" style="display: none;"><i class="fa-solid fa-check"></i> <span class="d-none d-sm-block">{{__('Save')}}</span></a>


                        </div>
				</div>
			</div>
		</header>
		<!-- Tabs navs -->
		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-invoice-tab" data-bs-toggle="tab" data-bs-target="#tab_1" data-bs-target_val="tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Invoice Detail') }}</button>
				@if(!$AppUI->isStudent())
                @if($invoice->invoice_status !== 10)
                <button class="nav-link" id="nav-basic-tab" data-bs-toggle="tab" data-bs-target="#tab_3" data-bs-target_val="tab_3" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Edit') }}</button>
                @endif
                @endif
            </div>
		</nav>
		<!-- Tabs navs -->
		<!-- Tabs content -->
			<div class="tab-content" id="invoice-details-content">
				<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
						<form role="form" id="form_main" class="form-horizontal" method="post" action="">
                        <fieldset class="section_header_class">
                            @if ($invoice->seller_country_id != 'CA')
                                <label>{{ $invoice->invoice_header }}</label>
                            @endif
                            <table class="table table-stripped table-hover" id="invoice_list_item" name="invoice_list_item" style="font-size:1em;">


                                    @php

                                        $zone = $timeZone;
                                        $sub_total_lesson = 0;
                                        $sub_total_min_lesson = 0;
                                        $total_lesson = 0;
                                        $sub_total_event = 0;
                                        $sub_total_min_event = 0;
                                        $total_event = 0;
                                        $total_min = 0;
                                        $total = 0;
                                        $invoice->date_invoice = Helper::formatDateTimeZone($invoice->date_invoice, 'long','UTC',$zone);
                                        $invoice->date_due = $invoice->date_due ? Helper::formatDateTimeZone($invoice->date_due, 'long','UTC',$zone) : '';
                                        $invoice->period_starts = Helper::formatDateTimeZone($invoice->period_starts, 'long','UTC',$zone);
                                        $invoice->period_ends = Helper::formatDateTimeZone($invoice->period_ends, 'long','UTC',$zone);

                                    @endphp

                                    <tbody>
                                        <tr class="header_tbl">
                                            <th width="30%"><span id="row_hdr_date" name="row_hdr_date">{{ __('invoice_column_date') }}</span></th>
                                            <th width="40%" style="text-align:right"><span id="item_particular_caption" name="item_particular_caption">{{ __('invoice_column_details') }}</span></th>
                                            <th width="15%" style="text-align:right"><?php if($invoice->invoice_type > 0) { ?><span id="item_unit_caption" name="item_unit_caption">{{ __('Unit') }}<?php } ?></span></th>
                                            <th width="15%" style="text-align:right"><span id="row_hdr_amount" name="row_hdr_amount">{{ __('invoice_column_amount') }}</span></th>
                                        </tr>
                                    @if (!empty($invoice->invoice_items))
                                        @foreach($invoice->invoice_items as $event_type => $group)
                                            @php //print_r($event_type); @endphp
                                            <tbody>

                                            @foreach($group as $key => $item)
                                                @php

                                                $item->item_date = Helper::formatDateTimeZone($item->item_date, 'long','UTC',$zone);

                                                @endphp
                                                <tr>
                                                    <td>{{ !empty($item->item_date) ? Carbon\Carbon::parse($item->item_date)->format('d.m.Y') : ''; }}</td>
                                                    <td style="text-align:right">
                                                        {!! !empty($item->caption) ? $item->caption : ''; !!}
                                                        <?php
                                                            $cost1 = extractExtraCharges($item->caption);
                                                        ?>

                                                        <br>
                                                        @if ($invoice->invoice_type > 0)
                                                        <?php
                                                        if($item->no_of_students == 1) {
                                                            echo 'Private Lesson';
                                                        } else {
                                                            echo 'Group of ' . $item->no_of_students . ' students';
                                                        } ?>
                                                        @endif
                                                    </td>
                                                    @if ($item->unit == 0)
                                                        <td></td>
                                                    @else
                                                        <td style="text-align:right">{{ $item->unit }} minutes</td>
                                                    @endif
                                                    @if ($invoice->invoice_type == 2)
                                                        <td style="text-align:right">{{ !empty($item->price) ? number_format($item->price,'2') : ''; }}</td>
                                                    @else
                                                        <td style="text-align:right">
                                                        <?php  if ($event_type == 10){ ?>
                                                            {{ !empty($item->price_unit) ? number_format($item->price_unit,'2') : ''; }}
                                                        <?php } else { ?>
                                                            {{ !empty($item->price_unit) ? number_format($item->price_unit-$cost1,'2') : ''; }}
                                                        <?php } ?>
                                                        </td>
                                                    @endif
                                                </tr>
                                                @php
                                                if ($event_type == 10){
                                                    if ($invoice->invoice_type == 2){
                                                        $sub_total_lesson += $item->price;
                                                    }
                                                    else{
                                                        $sub_total_lesson += $item->price_unit;
                                                    }
                                                    $sub_total_min_lesson = $sub_total_min_lesson + $item->unit;
                                                } else {
                                                    if ($invoice->invoice_type == 2){
                                                        $sub_total_event += $item->price;
                                                    }
                                                    else{
                                                        $sub_total_event += $item->price_unit;
                                                    }
                                                    $sub_total_min_event = $sub_total_min_event + $item->unit;
                                                }
                                                //$total_amount +=$item->total_item;

                                                @endphp
                                            @endforeach
                                            <!-- <tr>
                                                <td colspan="1" rowspan="7" style="vertical-align:bottom;"></td>
                                            </tr> -->
                                            <label style="display:none;"
                                                id="ssubtotal_amount_all">
                                                <?php echo !empty($sub_total_lesson) ? number_format($sub_total_lesson,'2') :'0.00'; ?></label>

                                            @if ($event_type == 10)


                                                <tr>
                                                    <td colspan="2" style="text-align:right"><b>Sub-Total Lessons</b></td>
                                                    <td style="text-align:right">{{$sub_total_min_lesson}} minutes</td>
                                                    <td style="text-align:right">
                                                        {{ number_format($sub_total_lesson,'2') }}
                                                    </td>
                                                </tr>

                                                <p style="display: none;" id="ssubtotal_amount_with_discount_lesson">{{ number_format($sub_total_lesson,'2') }}</p>

                                                <tr style="background-color: #EEE;">
                                                    @if ($invoice->invoice_type == 1)
                                                    <td colspan="2" style="text-align:right">Discount(%) on Lessons:<br>
                                                    {{$invoice->lesson_discount_description ? $invoice->lesson_discount_description : ''}}
                                                    </td>
                                                    @else
                                                    <td colspan="2" style="text-align:right">Commission(%) on Lessons:</td>
                                                    @endif
                                                    <td style="text-align:right">

                                                    </td>
                                                    <td style="text-align:right">
                                                        <!--<input type="text" class="form-control numeric" id="sdiscount_percent_1" name="sdiscount_percent_1" style="text-align: right; padding-right: 5px;" value="{{$invoice->discount_percent_1 ? $invoice->discount_percent_1 :0}}" placeholder="" {{ $invoiceIssued ? "disabled='disabled'" : "" }}>-->
                                                        <span><b>-{{$invoice->discount_percent_1 ? $invoice->discount_percent_1 :0}}%</b></span>
                                                    </td>
                                                </tr>

                                                <tr style="background-color: #EEE;">
                                                    @if ($invoice->invoice_type == 1)
                                                    <td colspan="2" style="text-align:right">Discount Amount:</td>
                                                    @else
                                                    <td colspan="2" style="text-align:right">Commission Amount:</td>
                                                    @endif
                                                    <td style="text-align:right">

                                                    </td>
                                                    <td style="text-align:right">
                                                        <?php
                                                        $disc1_amt = $invoice->total_amount_discount ? $invoice->total_amount_discount :0;
                                                        ?>
                                                        <?php $discount = $invoice->discount_percent_1 > 0 ? $sub_total_lesson*$invoice->discount_percent_1/100 : 0; ?>
                                                        <?php $totalWithDiscount = $sub_total_lesson-$discount; ?>
                                                        <!-- <p id="samount_discount_1" class="form-control-static numeric"
                                                                                                style="text-align:right;">0.00</p> -->
                                                        <!--<input type="text" class="form-control numeric_amount" id="samount_discount_1" name="samount_discount_1" style="text-align: right; padding-right: 5px;" value="{{number_format($discount,'2')}}" placeholder="" {{ $invoiceIssued ? "disabled='disabled'" : "" }}>-->
                                                        <span><b>-{{number_format($discount,'2')}}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align:right"><b>Total Lesson:</b></td>
                                                    <td style="text-align:right">

                                                    </td>
                                                    <td style="text-align:right">



                                                        <?php
                                                        $total_lesson = $sub_total_lesson-$disc1_amt = $invoice->amount_discount_1 ? $invoice->amount_discount_1 :0;
                                                        ?>
                                                        <span id="stotal_amount_with_discount_lesson"
                                                        class="form-control-static numeric"
                                                        style="text-align:right;">{{number_format($totalWithDiscount,'2')}}</>

                                                    </td>
                                                </tr>
                                                <!-- <div class="input-group"><span class="input-group-addon">%</span>
                                                    <input type="text" class="form-control numeric" id="sdiscount_percent_1" name="sdiscount_percent_1" value="{{$invoice->discount_percent_1 ? $invoice->discount_percent_1 :0}}" placeholder="">
                                                </div> -->
                                            @else
                                                <p style="display: none;" id="stotal_amount_with_discount_event">{{ number_format($sub_total_event,'2') }}</p>




                                                @if($invoice->discount_percent_2 > 0)
                                                <tr>
                                                <td colspan="2" style="text-align:right">Discount(%) on Event: {{ $invoice->discount_percent_2 }}%
                                                    <br>{{$invoice->event_discount_description ? $invoice->event_discount_description : ''}}</td>
                                                    <td style="text-align:right"><!--{{$sub_total_min_event}} minutes--></td>
                                                    @if ($invoice->invoice_type == 1)
                                                    <td style="text-align:right"><b>-{{ number_format((($sub_total_event-$invoice->extra_expenses) * $invoice->discount_percent_2/100),'2') }}</b></td>
                                                    @else
                                                    <td style="text-align:right"></td>
                                                    @endif
                                                </tr>
                                                @endif

                                                <tr>
                                                    <td colspan="2" style="text-align:right">Total events: </td>
                                                    <td style="text-align:right"></td>
                                                    @if ($invoice->invoice_type == 1)
                                                    <td style="text-align:right">{{ number_format((($sub_total_event-$invoice->extra_expenses)) - number_format((($sub_total_event-$invoice->extra_expenses) * $invoice->discount_percent_2/100)),'2') }}</td>
                                                    @else
                                                    <td style="text-align:right">{{ number_format($sub_total_event,'2') }}</td>
                                                    @endif
                                                </tr>
                                            @endif

                                        @endforeach
                                    @endif
                                </tbody>

                                <tr>
                                    <td colspan="2" style="text-align:right"><b>Sub-Total</b>: </td>
                                    <td style="text-align:right"></td>
                                    <td style="text-align:right">
                                        @if ($invoice->invoice_type == 1)
                                        <b><?php echo  $totaux = number_format((((($sub_total_event-$invoice->extra_expenses)) - number_format((($sub_total_event-$invoice->extra_expenses) * $invoice->discount_percent_2/100)))+$totalWithDiscount),2); ?></b>
                                        @else
                                        <b><?php echo  $totaux = number_format((($sub_total_event)+$totalWithDiscount),2); ?></b>
                                        @endif
                                    </td>
                                </tr>

                                <tbody>
                                    @if ($invoice->invoice_type == 2 && $invoice->total_amount_discount != 0)
                                        <tr>
                                            <td colspan="2" style="text-align:right">Commission</td>
                                            <td></td>
                                            <td style="text-align:right">- {{number_format($invoice->total_amount_discount,'2')}}</td>
                                            <input type="hidden" class="form-control numeric_amount" id="total_commission" name="total_commission" value="{{$invoice->total_amount_discount ? number_format($invoice->total_amount_discount,'2') :0.00}}" placeholder="" readonly="">
                                        </tr>
                                    @endif


                                    <!--<br>
                                    Subtotal Lesson <?php echo $sub_total_lesson ; ?>
                                    <br>
                                    Discount : <?php echo $discount = $sub_total_lesson*$invoice->discount_percent_1/100 ?>
                                    <br>TOTAL LESSON => <?php echo $totalWithDiscount = $sub_total_lesson-$discount; ?>
                                    <br>TOTAL EVENTS => <?php echo $sub_total_event; ?>
                                    <br>TOTAUX => <?php echo  $totaux = $invoice->invoice_type == 0 ? ((($sub_total_event-(($sub_total_event-$invoice->extra_expenses) * $invoice->discount_percent_2/100)))+$totalWithDiscount) : ((($sub_total_event-$invoice->extra_expenses)-(($sub_total_event-$invoice->extra_expenses) * $invoice->discount_percent_2/100))+$totalWithDiscount); ?>-->

                                    <?php $countAllTaxes=0; ?>

                                  <?php
                                  $invoices_taxes = DB::table('invoices_taxes')->where('invoice_id', $invoice->id)->get();
                                    foreach ($invoices_taxes as $item) {
                                        // Calcul du nouvel amount en fonction du pourcentage de taxe pour chaque élément
                                        $newAmount = $totaux * ($item->tax_percentage / 100);

                                        // Ajout du nouvel amount à la variable $countAllTaxes (en utilisant une opération de somme)
                                        $countAllTaxes += $item->tax_amount ? number_format($newAmount, 2) : 0;
                                    }
                                  ?>

                                    <!--TOUT FINI => <?php echo $totaux + $countAllTaxes + $invoice->extra_expenses ?>-->


                                    <?php $totaltaxAmount=0; ?>
                                    @if ($invoice->tax_amount > 0)

                                    @foreach(DB::table('invoices_taxes')->where('invoice_id', $invoice->id)->get() as $item)
                                    <tr style="background-color: #EEE;">
                                        <td colspan="2" style="text-align:right">{{ $item->tax_name }}</td>
                                        <td>({{ $item->tax_percentage }}%)</td>
                                        <td style="text-align:right; margin-right:0 padding-right:0;">
                                            <!--<input type="text" class="form-control numeric" id="taxes" name="taxes" value="{{ $item->tax_amount ? number_format($totaux*$item->tax_percentage/100, 2) : '0' }}" placeholder="" style="text-align: right; padding-right: 5px;" disabled="disabled">-->
                                            <span>+{{ $item->tax_amount ? number_format($totaux*$item->tax_percentage/100, 2) : '0' }}</span>
                                            <?php $totaltaxAmount = $item->tax_amount ? ( $totaltaxAmount + number_format($totaux*$item->tax_percentage/100, 2)) : 0; ?>
                                        </td>
                                    </tr>
                                    @endforeach


                                    <tr>
                                        <td colspan="2" style="text-align:right"><b>Sub-Total:</b></td>
                                        <td style="text-align:right"><!--{{$sub_total_min_event}} minutes--></td>
                                        <td style="text-align:right"><b>{{ $invoice->invoice_type == 0 ? number_format((($sub_total_event)+$totalWithDiscount+$totaltaxAmount),2) : number_format(((($sub_total_event-$invoice->extra_expenses))-number_format((($sub_total_event-$invoice->extra_expenses) * $invoice->discount_percent_2/100))+$totalWithDiscount+$totaltaxAmount),2) }}</b></td>
                                    </tr>


                                    @if ($invoice->extra_expenses > 0)

                                        <tr>
                                            <td></td>
                                            <td colspan="2"></td>
                                            <td class="text-left small">Charges and Additional Expenses</td>

                                        </tr>

                                        @foreach(DB::table('invoices_expenses')->where('invoice_id', $invoice->id)->get() as $item)
                                        <tr>
                                            <td colspan="2" style="text-align:right">{{ $item->expense_name }}</td>
                                            <td></td>
                                            <td style="text-align:right; margin-right:0 padding-right:0;">
                                                <!--<input style="display:none;" type="text" class="form-control numeric" id="taxes" name="sextra_expenses" value="{{ $item->expense_amount ? number_format($item->expense_amount, 1) : '0' }}" placeholder="" style="text-align: right; padding-right: 5px;" disabled="disabled">-->
                                                {{ number_format($item->expense_amount,'2') }}
                                            </td>
                                        </tr>
                                        @endforeach


                                    @endif


                                    @if ($invoice->extra_expenses > 0)
                                    <tr>
                                        <td colspan="2" style="text-align:right"><b>Total Extra Charges:</b></td>
                                        <td style="text-align:right"><!--{{$sub_total_min_event}} minutes--></td>
                                        <td style="text-align:right"><b>{{ number_format($invoice->extra_expenses,'2') }}</b></td>
                                        <input style="display:none;" type="text" class="form-control numeric" id="sextra_expenses" name="sextra_expenses" value="{{ number_format($invoice->extra_expenses,'2') }}" placeholder="" style="text-align: right; padding-right: 5px;">
                                    </tr>
                                    @endif




                                    <!--<tr>
                                        <td colspan="2" style="text-align:right">Tax:</td>
                                        <td></td>
                                        <td style="text-align:right">
                                            <input type="text" class="form-control numeric" id="taxes" name="taxes" value="{{$invoice->tax_amount ? number_format($invoice->tax_amount,'2') :0}}" placeholder="" style="margin-left: 0px;" disabled='disabled'>
                                        </td>
                                    </tr>-->
                                    @endif
                                    @php
                                        $grand_total = $sub_total_event +$sub_total_lesson + $invoice->extra_expenses-$invoice->total_amount_discount + $invoice->tax_amount;
                                    @endphp
                                    <tr class="alert alert-info">
                                        <td colspan="2" style="text-align:right"><b>Total</b></td>
                                        <td></td>
                                        <?php $grandTotalFinal = $totaux + $countAllTaxes + $invoice->extra_expenses; ?>
                                        <td style="text-align:right"><span id="grand_total_cap"><b>{{ number_format($grandTotalFinal,'2') }}</b></span></td>
                                    </tr>
                                </tbody>
                            </table>



                            <?php $grandTotalFinal = number_format($totaux) + number_format($countAllTaxes) + number_format($invoice->extra_expenses); ?>

                            <input type="hidden" id="approved_flag" name="approved_flag" value="0">
                            <input type="hidden" id="invoice_id" name="invoice_id" value="{{$invoice->id}}">
                            <input type="hidden" id="invoice_type" name="invoice_type" value="{{$invoice->invoice_type}}">
                            <input type="hidden" id="payment_status" name="payment_status" value="{{$invoice->payment_status}}">
                            <input id="p_school_id" name="p_school_id" style="display: none;" value="{{$invoice->school_id}}">

                            @if($invoice->invoice_type ==2)
                                <input type="hidden" id="person_id" name="person_id" value="{{$invoice->client_id}}">
                            @else
                                <input type="hidden" id="person_id" name="person_id" value="{{$invoice->seller_id}}">
                            @endif

                        </fieldset>

                        <fieldset style="display: none;">
                            <label class="section_header_class">{{__('Invoice Calculation')}}</label>
                            <div class="form-group row">
                                <label id="stotal_amount_no_discount_cap" class="col-lg-3 col-sm-3 text-right" style="text-align:right;">Total Amount before Discount</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static" style="text-align:right;">
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? $invoice->invoice_currency :''; ?></label>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <p id="stotal_amount_no_discount" class="form-control-static numeric" style="text-align:right;"><?php echo $invoice->subtotal_amount_all ? number_format($invoice->subtotal_amount_all,'2') :'0.00'; ?></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="sdiscount_percent_1_cap" class="col-lg-3 col-sm-3 text-right" style="text-align:right;" for="sdiscount_percent_1">Reduction Rate: </label>
                                <div class="col-sm-2" style="display:none;">

                                    <?php
                                    $disc1_amt = $invoice->amount_discount_1 ? $invoice->amount_discount_1 :0;
                                    ?>
                                    <!-- <p id="samount_discount_1" class="form-control-static numeric"
                                                                            style="text-align:right;">0.00</p> -->
                                    <div class="input-group"><span class="input-group-addon currency_display"><?php echo $invoice->invoice_currency ? ' ('.$invoice->invoice_currency .') ':''; ?></span>
                                        <input type="text" class="form-control numeric_amount" id="samount_discount_1" name="samount_discount_1" value="{{number_format($disc1_amt,'2')}}" placeholder="">
                                    </div>
                                </div>
                                <div class="col-sm-2 text-right">
                                    <div class="input-group"><span class="input-group-addon">%</span>
                                        <input type="text" class="form-control numeric" id="sdiscount_percent_1" name="sdiscount_percent_1" value="{{$invoice->discount_percent_1 ? $invoice->discount_percent_1 :0}}" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="stotal_amount_with_discount_cap" class="col-lg-3 col-sm-3 text-right" style="text-align:right;">Total Amount after Discount</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static" style="text-align:right;"> +
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? $invoice->invoice_currency :''; ?></label>
                                    </p>
                                </div>
                                <!-- <div class="col-sm-1">
                                    <p id="stotal_amount_with_discount" class="form-control-static numeric" style="text-align:right;"><?php echo $invoice->total_amount_with_discount ? number_format($invoice->total_amount_with_discount,'2') :'0.00'; ?></p>
                                </div> -->
                            </div>
                            <div class="form-group row">
                                <label id="sextra_expenses_cap" name="sextra_expenses_cap" class="col-lg-3 col-sm-3 text-right" style="text-align:right;">Charges and Additional Expenses:</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static" style="text-align:right;"> +
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? $invoice->invoice_currency :''; ?></label>
                                    </p>
                                </div>
                                <!-- <div class="col-sm-1">
                                    <input type="text" class="form-control numeric" id="sextra_expenses" name="sextra_expenses" value="{{$invoice->extra_expenses ? number_format($invoice->extra_expenses,'2') :0}}" placeholder="">
                                </div> -->
                            </div>
                            <div id="tax_amount_div" name="tax_amount_div" class="form-group" style="display: none;">
                                <label id="tax_cap" name="tax_cap" class="col-lg-3 col-sm-3 text-right" style="text-align:right;">Tax:</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static" style="text-align:right;"> +
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? $invoice->invoice_currency :''; ?></label>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <input type="text" class="form-control numeric" id="tax_amount" name="tax_amount" value="{{$invoice->tax_amount ? number_format($invoice->tax_amount,'2') :0}}" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="grand_total_amount_cap" name="grand_total_amount_cap" class="col-lg-3 col-sm-3 text-right" style="text-align:right;">Grand Total</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static" style="text-align:right;"> =
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? $invoice->invoice_currency : ''; ?></label>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <p id="stotal_amount" class="form-control-static numeric" style="text-align:right;display: none;"><?php echo $invoice->total_amount ? number_format($invoice->total_amount,'2') : '0.00'; ?></p>
                                    <p id="grand_total_amount" name="grand_total_amount" class="form-control-static numeric" style="text-align:right;"><?php echo $invoice->total_amount ? number_format($invoice->total_amount,'2') : '0.00'; ?></p>
                                </div>
                            </div>
                        </fieldset>
                    </form>
				</div>

				<div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tab_3">
					<form role="form" id="form_details" class="form-horizontal" method="post" action="">
                        <fieldset>
                            <label class="section_header_class" id="basic_data" name="basic_data">Basic Data</label>
                            <div class="form-group row">
                                <label id="invoice_type_cap" for="invoice_type_name" class="col-lg-3 col-sm-3 text-right">Invoice Type</label>
                                <label id="invoice_type_name" class="col-sm-5">{{ $invoice_type_all[$invoice->invoice_type]; }}</label>
                            </div>
                            <div class="form-group row">
                                <label id="row_hdr_status" name="row_hdr_status" for="invoice_status" class="col-lg-3 col-sm-3 text-right">Status</label>
                                <div class="col-lg-2 col-sm-2 text-left">
                                    <label id="invoice_status_text">{{ $invoice_status_all[$invoice->invoice_status]; }}</label>

                                    @if(!$AppUI->isStudent())
                                    <div>
                                        <a id="unlock_btn" href="" class="btn btn-xs btn-warning" style="display: none;">
                                            <span id="unlock_btn_cap">Unlock</span>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <!-- invoice date -->
                            <div class="form-group row">
                                <label id="invoice_date_cap" class="col-lg-3 col-sm-3 text-right">Date of invoice</label>
                                <div class="col-sm-2">
                                    <div class="input-group" id="date_invoice1">
                                        <input id="date_invoice" name="date_invoice" type="text" class="form-control" value="{{$invoice->date_invoice ? date('Y-m-d', strtotime(str_replace('.', '-', $invoice->date_invoice))) :''}}">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="invoice_date_cap" class="col-lg-3 col-sm-3 text-right">Date due</label>
                                <div class="col-sm-2">
                                    <?php
                                      $date = $invoice->date_invoice;
                                      $new_date = date('Y-m-d', strtotime('+7 days', strtotime($date)));
                                    ?>
                                        <div class="input-group" id="date_invoice_due1">
                                        <input id="date_invoice_due" name="date_invoice_due" type="text" class="form-control" value="{{$invoice->date_due ? date('Y-m-d', strtotime(str_replace('.', '-', $invoice->date_due))) : $new_date}}">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <!-- -->
                            <div class="form-group row">
                                <label id="start_of_period_cap" class="col-lg-3 col-sm-3 text-right">Start of Period</label>
                                <label id="start_date" class="col-sm-7">{{$invoice->period_starts ? date('Y-m-d', strtotime(str_replace('.', '-', $invoice->period_starts))) :''}}</label>
                            </div>
                            <div class="form-group row">
                                <label id="end_of_period_cap" class="col-lg-3 col-sm-3 text-right">End of Period</label>
                                <label id="end_date" class="col-sm-7">{{$invoice->period_ends ? date('Y-m-d', strtotime(str_replace('.', '-', $invoice->period_ends))) :''}}</label>
                            </div>

                            <div class="form-group row">
                                <label id="invoice_title_cap" for="invoice_name" class="col-lg-3 col-sm-3 text-right">invoice Title</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="invoice_name" name="invoice_name" value="{{$invoice->invoice_name ? $invoice->invoice_name :''}}" placeholder="" maxlength="150">
                                </div>
                            </div>
                            <div class="form-group" style="display:none;">
                                <label id="invoice_header_cap" for="invoice_header" class="col-lg-3 col-sm-3 text-right">Invoice Header</label>
                                <div class="col-sm-7">
                                    <textarea class="form-control" id="invoice_header" name="invoice_header" placeholder="" rows="6" maxlength="2000">
                                        {{$invoice->invoice_header ? $invoice->invoice_header :''}}
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="invoice_footer_cap" for="invoice_footer" class="col-lg-3 col-sm-3 text-right">Add notes</label>
                                <div class="col-sm-7">
                                    <textarea class="form-control" id="invoice_footer" name="invoice_footer" placeholder="" rows="6" maxlength="2000">{{$invoice->invoice_footer ? $invoice->invoice_footer :''}}</textarea>
                                </div>
                            </div>
                            <p>&nbsp;</p>
                            <!-- Customer (debtor of the invoice) -->
                            <label class="section_header_class" id="lbl_client_information">Client Information:</label>
                            <div class="form-group row">
                                <label id="client_name_caption" name="client_name_caption" for="client_name" class="col-lg-3 col-sm-3 text-right">Client Name</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="client_name" name="client_name" value="{{$invoice->client_name ? $invoice->client_name :''}}" placeholder="" maxlength="250">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="gender_label_id" name="gender_label_id" for="client_gender_id" class="col-lg-3 col-sm-3 text-right">Gender : *</label>
                                <div class="col-sm-5">
                                    <div class="selectdiv">
                                        <select class="form-control" id="client_gender_id" name="client_gender_id">
                                            <option value="1" data-valuenumber-id="1">Masculin</option>
                                            <option value="2" selected="selected" data-valuenumber-id="2">Féminin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="family_name_label_id" name="family_name_label_id" for="client_lastname" class="col-lg-3 col-sm-3 text-right">Family Name :*</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="client_lastname" name="client_lastname" value="{{$invoice->client_lastname ? $invoice->client_lastname :''}}" placeholder="" maxlength="250">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="first_name_label_id" name="first_name_label_id" for="client_firstname" class="col-lg-3 col-sm-3 text-right">First Name : *</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="client_firstname" name="client_firstname" value="{{$invoice->client_firstname ? $invoice->client_firstname :''}}" placeholder="" maxlength="250"> </div>
                            </div>
                            <div class="form-group row">
                                <label id="street_caption" name="street_caption" for="client_street" class="col-lg-3 col-sm-3 text-right">Street</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="client_street" name="client_street" value="{{$invoice->client_street ? $invoice->client_street :''}}" placeholder="" maxlength="120">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="street_number_caption" name="street_number_caption" for="client_street_number" class="col-lg-3 col-sm-3 text-right">Street No :</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="client_street_number" name="client_street_number" value="{{$invoice->client_street_number ? $invoice->client_street_number :''}}" placeholder="" maxlength="20"> </div>
                            </div>
                            <div class="form-group row">
                                <label id="street2_caption" name="street2_caption" for="client_street2" class="col-lg-3 col-sm-3 text-right">Street 2 :</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="client_street2" name="client_street2" value="{{$invoice->client_street2 ? $invoice->client_street2 :''}}" placeholder="" maxlength="100"> </div>
                            </div>
                            <div class="form-group row">
                                <label id="postal_code_caption" name="postal_code_caption" for="client_zip_code" class="col-lg-3 col-sm-3 text-right">Postal Code :</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="client_zip_code" name="client_zip_code" value="{{$invoice->client_zip_code ? $invoice->client_zip_code :''}}" placeholder="" maxlength="8">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="locality_caption" name="locality_caption" for="client_place" class="col-lg-3 col-sm-3 text-right">City :</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="client_place" name="client_place" value="{{$invoice->client_place ? $invoice->client_place :''}}" placeholder="" maxlength="120">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="pays_caption" name="pays_caption" for="client_country_id" class="col-lg-3 col-sm-3 text-right">Country :</label>
                                <div class="col-sm-5">
                                    <div class="selectdiv">
                                        <select class="form-control" id="client_country_id" name="client_country_id">
                                            <option value="CA">Canada</option>
                                            <option value="FR">France</option>
                                            <option value="CH">Switzerland</option>
                                            <option value="US">United States</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="client_province_id_div" class="form-group" style="display:none;">
                                <label id="province_caption" for="client_province_id" class="col-lg-3 col-sm-3 text-right">Province: </label>
                                <div class="col-sm-5">
                                    <div class="selectdiv">
                                        <select class="form-control" id="client_province_id" name="client_province_id">
                                            <option value="">Select Province</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <p>&nbsp;</p>
                            <!-- Seller (the invoice of creditor) -->
                            <label class="section_header_class" id="lbl_seller_information">Basic data Seller (creditor of invoice)</label>
                            <div class="form-group row">
                                <label id="seller_name_caption" name="seller_name_caption" for="seller_name" class="col-lg-3 col-sm-3 text-right">Seller Name</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="seller_name" name="seller_name" value="{{$invoice->seller_name ? $invoice->seller_name :''}}" placeholder="" maxlength="250"> </div>
                            </div>
                            <div class="form-group row">
                                <label id="gender_label_id" name="gender_label_id" for="seller_gender_id" class="col-lg-3 col-sm-3 text-right">Gender : *</label>
                                <div class="col-sm-5">
                                    <div class="selectdiv">
                                        <select class="form-control" id="seller_gender_id" name="seller_gender_id">
                                            <option value="1" selected="selected" data-valuenumber-id="1">Masculin</option>
                                            <option value="2" data-valuenumber-id="2">Féminin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="family_name_label_id" name="family_name_label_id" for="seller_lastname" class="col-lg-3 col-sm-3 text-right">Family Name :*</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="seller_lastname" name="seller_lastname" value="{{$invoice->seller_lastname ? $invoice->seller_lastname :''}}" placeholder="" maxlength="250"> </div>
                            </div>
                            <div class="form-group row">
                                <label id="first_name_label_id" name="first_name_label_id" for="seller_firstname" class="col-lg-3 col-sm-3 text-right">First Name : *</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="seller_firstname" name="seller_firstname" value="{{$invoice->seller_firstname ? $invoice->seller_firstname :''}}" placeholder="" maxlength="250"> </div>
                            </div>
                            <div class="form-group row">
                                <label id="street_caption" name="street_caption" for="seller_street" class="col-lg-3 col-sm-3 text-right">Street</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="seller_street" name="seller_street" value="{{$invoice->seller_street ? $invoice->seller_street :''}}" placeholder="" maxlength="120"> </div>
                            </div>
                            <div class="form-group row">
                                <label id="street_number_caption" name="street_number_caption" for="seller_street_number" class="col-lg-3 col-sm-3 text-right">Street No :</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="seller_street_number" name="seller_street_number" value="{{$invoice->seller_street_number ? $invoice->seller_street_number :''}}" placeholder="" maxlength="20"> </div>
                            </div>
                            <div class="form-group row">
                                <label id="street_caption" name="street_caption" for="seller_street2" class="col-lg-3 col-sm-3 text-right">Street</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="seller_street2" name="seller_street2" value="{{$invoice->seller_street2 ? $invoice->seller_street2 :''}}" placeholder="" maxlength="100"> </div>
                            </div>
                            <div class="form-group row">
                                <label id="postal_code_caption" name="postal_code_caption" for="seller_zip_code" class="col-lg-3 col-sm-3 text-right">Postal Code :</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="seller_zip_code" name="seller_zip_code" value="{{$invoice->seller_zip_code ? $invoice->seller_zip_code :''}}" placeholder="" maxlength="8"> </div>
                            </div>
                            <div class="form-group row">
                                <label id="locality_caption" name="locality_caption" for="seller_place" class="col-lg-3 col-sm-3 text-right">City :</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="seller_place" name="seller_place" value="{{$invoice->seller_place ? $invoice->seller_place :''}}" placeholder="" maxlength="120"> </div>
                            </div>
                            <div class="form-group row">
                                <label id="pays_caption" name="pays_caption" for="seller_country_id" class="col-lg-3 col-sm-3 text-right">Country :</label>
                                <div class="col-sm-5">
                                    <div class="selectdiv">
                                        <select class="form-control" id="seller_country_id" name="seller_country_id">
                                            <option value="CA">Canada</option>
                                            <option value="FR">France</option>
                                            <option value="CH">Switzerland</option>
                                            <option value="US">United States</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="seller_province_id_div" class="form-group" style="display:none;">
                                <label id="province_caption" for="seller_province_id" class="col-lg-3 col-sm-3 text-right">Province: </label>
                                <div class="col-sm-5">
                                    <div class="selectdiv">
                                        <select class="form-control" id="seller_province_id" name="seller_province_id">
                                            <option value="">Select Province</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="phone_caption" name="phone_caption" for="seller_phone" class="col-lg-3 col-sm-3 text-right">Téléphone:</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="seller_phone" name="seller_phone" value="{{$invoice->seller_phone ? $invoice->seller_phone :''}}" placeholder="" maxlength="50">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="mobile_caption" name="mobile_caption" for="seller_mobile" class="col-lg-3 col-sm-3 text-right">Mobile:</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="seller_mobile" name="seller_mobile" value="{{$invoice->seller_mobile ? $invoice->seller_mobile :''}}" placeholder="" maxlength="50">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="email_caption" name="email_caption" for="seller_email" class="col-lg-3 col-sm-3 text-right">Email:</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="seller_email" name="seller_email" value="{{$invoice->seller_email ? $invoice->seller_email :''}}" placeholder="" maxlength="50">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="seller_eid" class="col-lg-3 col-sm-3 text-right">EID:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="seller_eid" name="seller_eid" value="{{$invoice->seller_eid ? $invoice->seller_eid :''}}" placeholder="" maxlength="100">
                                </div>
                            </div>
                            <p>&nbsp;</p>
                            <!-- payment information -->
                            <div id="payment_detail_div">
                                <label class="section_header_class" id="lbl_seller_information">Payment Bank Information</label>
                                <div id="canada_payment_div" style="display: block;">
                                    <div class="form-group row">
                                        <label id="etransfer_acc_cap" class="col-lg-3 col-sm-3 text-right">Payment Preference:</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="etransfer_acc" name="etransfer_acc" value="{{$invoice->payment_bank_account_name ? $invoice->payment_bank_account_name :''}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label id="etransfer_acc_cap" class="col-lg-3 col-sm-3 text-right">To pay by e-transfer:</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="etransfer_acc" name="etransfer_acc" value="{{$invoice->etransfer_acc ? $invoice->etransfer_acc :''}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label name="cheque_payee" class="col-lg-3 col-sm-3 text-right">To pay by check:</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="cheque_payee" name="cheque_payee" value="{{$invoice->cheque_payee ? $invoice->cheque_payee :''}}"> </div>
                                    </div>
                                </div>
                                <div id="professor_payment_div" style="display: none;">
                                    <div class="form-group row">
                                        <label id="payment_bank_account_name_cap" name="payment_bank_account_name_cap" for="spayment_bank_account_name" class="col-lg-3 col-sm-3 text-right">Payment Bank Account Name</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="spayment_bank_account_name" name="spayment_bank_account_name" value="{{$invoice->payment_bank_account_name ? $invoice->payment_bank_account_name :''}}" placeholder="" maxlength="150">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label id="iban_caption" name="iban_caption" for="spayment_bank_iban" class="col-lg-3 col-sm-3 text-right">IBAN No</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="spayment_bank_iban" name="spayment_bank_iban" value="{{$invoice->payment_bank_iban ? $invoice->payment_bank_iban :''}}" placeholder="" maxlength="50"> </div>
                                    </div>
                                    <div class="form-group row">
                                        <label id="account_number" name="account_number" for="spayment_bank_account" class="col-lg-3 col-sm-3 text-right">Account No</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="spayment_bank_account" name="spayment_bank_account" value="{{$invoice->payment_bank_account ? $invoice->payment_bank_account :''}}" placeholder="" maxlength="30">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label id="swift_number" name="swift_number" for="payment_bank_swift" class="col-lg-3 col-sm-3 text-right">SWIFT A/c No</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="spayment_bank_swift" name="spayment_bank_swift" value="{{$invoice->payment_bank_swift ? $invoice->payment_bank_swift :''}}" placeholder="" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label id="name_of_bank_captiontion" name="name_of_bank_captiontion" for="spayment_bank_name" class="col-lg-3 col-sm-3 text-right">Nom de la banque:</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="spayment_bank_name" name="spayment_bank_name" value="{{$invoice->payment_bank_name ? $invoice->payment_bank_name :''}}" placeholder="" maxlength="120"> </div>
                                    </div>
                                    <div class="form-group row">
                                        <label id="address_caption" name="address_caption" for="payment_bank_address" class="col-lg-3 col-sm-3 text-right">Address</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="spayment_bank_address" name="spayment_bank_address" value="{{$invoice->payment_bank_address ? $invoice->payment_bank_address :''}}" placeholder="" maxlength="100">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label id="postal_code_caption" name="postal_code_caption" for="payment_bank_zipcode" class="col-lg-3 col-sm-3 text-right">Postal Code :</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="spayment_bank_zipcode" name="spayment_bank_zipcode" value="{{$invoice->payment_bank_zipcode ? $invoice->payment_bank_zipcode :''}}" placeholder="" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label id="locality_caption" name="locality_caption" for="spayment_bank_place" class="col-lg-3 col-sm-3 text-right">City :</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="spayment_bank_place" name="spayment_bank_place" value="{{$invoice->payment_bank_place ? $invoice->payment_bank_place :''}}" placeholder="" maxlength="100">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label id="pays_caption" name="pays_caption" for="spayment_bank_country_id" class="col-lg-3 col-sm-3 text-right">Country :</label>
                                        <div class="col-sm-5">
                                            <div class="selectdiv">
                                                <select class="form-control" id="spayment_bank_country_id" name="spayment_bank_country_id">
                                                    <option value="CA" {{!empty($invoice->payment_bank_country_code) ? (old('spayment_bank_country_id', 'CA') == $invoice->payment_bank_country_code ? 'selected' : '') : (old('spayment_bank_country_id') == $invoice->payment_bank_country_code ? 'selected' : '')}}>Canada</option>
                                                    <option value="FR" {{!empty($invoice->payment_bank_country_code) ? (old('spayment_bank_country_id', 'FR') == $invoice->payment_bank_country_code ? 'selected' : '') : (old('spayment_bank_country_id') == $invoice->payment_bank_country_code ? 'selected' : '')}}>France</option>
                                                    <option value="CH" {{!empty($invoice->payment_bank_country_code) ? (old('spayment_bank_country_id', 'CH') == $invoice->payment_bank_country_code ? 'selected' : '') : (old('spayment_bank_country_id') == $invoice->payment_bank_country_code ? 'selected' : '')}}>Switzerland</option>
                                                    <option value="US" {{!empty($invoice->payment_bank_country_code) ? (old('spayment_bank_country_id', 'US') == $invoice->payment_bank_country_code ? 'selected' : '') : (old('spayment_bank_country_id') == $invoice->payment_bank_country_code ? 'selected' : '')}}>United States</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="bank_province_id_div" class="form-group" style="display:none;">
                                        <label id="province_caption" for="bank_province_id" class="col-lg-3 col-sm-3 text-right">Province: </label>
                                        <div class="col-sm-5">
                                            <div class="selectdiv">
                                                <select class="form-control" id="bank_province_id" name="bank_province_id">
                                                    <option value="">Select Province</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <p>&nbsp;</p>
                                </div>
                            </div>
                            <!-- payment info end -->
                        </fieldset>
                    </form>
				</div>
				<!--End of Tab 4 -->



                <div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2" style="display:none;">
					<form role="form" id="form_finance" class="form-horizontal" method="post" action="">
                        <fieldset>
                            <!-- Subtotal before discounts-->
                            <!-- <label class="section_header_class">{{__('Subtotals before discounts')}}</label>
                            <div class="form-group row">
                                <label id="payment_status_label" class="col-lg-3 col-sm-3 text-right" style="text-align:right;">Payment Status:</label>
                                <div class="col-sm-1">
                                    <p style="text-align:right;">
                                        <label id="payment_status_text" name="payment_status_text"></label>
                                    </p>
                                </div>
                            </div> -->
                            <!-- <div class="form-group row">
                                <label id="disc_on_course_hrs_cap" class="col-lg-3 col-sm-3 text-right">Subtotal (not subject to reduction)</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static numeric" style="text-align:right;">
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? ' ('.$invoice->invoice_currency .') ':''; ?></label>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <p class="form-control-static numeric" style="text-align:right;">
                                        <label id="ssubtotal_amount_no_discount"><?php echo $invoice->subtotal_amount_no_discount ? number_format($invoice->subtotal_amount_no_discount,'2') : '0.00'; ?></label>
                                    </p>
                                </div>
                            </div> -->
                            <div class="form-group row" style="display:none;">
                                <label class="col-lg-3 col-sm-3 text-right">{{__("Subtotal (subject to reduction)")}}</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static" style="text-align:right;"> +
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? $invoice->invoice_currency :''; ?></label>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <p class="form-control-static numeric" style="text-align:right;">
                                        <label id="ssubtotal_amount_with_discount1"><?php echo $invoice->subtotal_amount_with_discount ? number_format($invoice->subtotal_amount_with_discount,'2') :'0.00'; ?></label>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row"  style="display:none;">
                                <label id="sub_total_caption" name="sub_total_caption" class="col-lg-3 col-sm-3 text-right" style="text-align:right;">Sous-total:</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static" style="text-align:right;"> =
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? ' ('.$invoice->invoice_currency .') ':''; ?></label>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <p class="form-control-static numeric" style="text-align:right;">
                                        <label id="ssubtotal_amount_all1"><?php echo $invoice->subtotal_amount_all ? number_format($invoice->subtotal_amount_all,'2') :'0.00'; ?></label>
                                    </p>
                                </div>
                            </div>


                            <div class="form-group row" style="display:none;">
                                <label id="stotal_amount_discount_cap" class="col-sm-3 text-right" style="text-align:right;">Total de la réduction:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon currency_display"><?php echo $invoice->invoice_currency ? ' ('.$invoice->invoice_currency .') ':''; ?></span>
                                        <input type="text" class="form-control numeric_amount" id="stotal_amount_discount" name="stotal_amount_discount" value="{{$invoice->total_amount_discount ? number_format($invoice->total_amount_discount,'2') :0.00}}" placeholder="" readonly="">
                                    </div>
                                </div>
                            </div>

                        </fieldset>
                    </form>
				</div>
			</div>
		</form>
	</div>
	<!-- success modal-->
	<div class="modal modal_parameter" id="modal_add_teacher">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<p id="modal_alert_body"></p>
				</div>
				<div class="modal-footer">
					<button type="button" id="modalClose" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
				</div>
			</div>
		</div>
	</div>
	<!-- End Tabs content -->

    <div class="modal fade confirm-modal" id="email_list_modal" tabindex="-1" aria-hidden="true"
        aria-labelledby="email_list_modal" name="email_list_modal">
        <div class="modal-dialog modal-dialog-centered mt-5" role="document">
            <div class="modal-content">
                <div class="modal-header text-center border-0">
                    <h4 class="light-blue-txt gilroy-bold">Send the invoice</h4>
                </div>
                <div class="modal-body row" style="margin: 0 auto;padding-top: 0;">
                    <!-- <form id="email_list_form" name="email_list_form" method="POST"> -->
                        <div class="alert alert-info">
                            <div class="form-group col-md-12">
                            <i class="fa-solid fa-file-pdf"></i> Format type file: PDF<br>
                            <i class="fa-regular fa-envelope"></i> Send Type: By email
                            </div>
                        </div>
                        <div class="form-group row col-md-12" id="father_email_div">
                            <div class="btn-group border-bottom col-md-9 text-left">
                                <input  type="checkbox" id="father_email_chk" name="father_email_chk" value="" style="float: left;margin: 15px 5px;width: 15px;height: 15px;" checked>
                                <label for="father_email_chk" id="father_email_cap" name="father_email_cap"></label>
                                <div class="d-block d-sm-none text-small" style="font-size:10px;">({{ __("Father's email")}})</div>
                            </div>
                            <div class="col-md-3 border-bottom pt-2 text-right d-none d-sm-block">
                                ({{ __("Father's email")}})
                            </div>
                        </div>

                        <div class="form-group row col-md-12" id="mother_email_div">
                            <div class="btn-group col-md-9 border-bottom text-left">
                                <input type="checkbox" id="mother_email_chk" name="mother_email_chk" value="" style="float: left;margin: 15px 5px;width: 15px;height: 15px;" checked>
                                <label for="mother_email_chk" id="mother_email_cap" name="mother_email_cap"></label>
                                <div class="d-block d-sm-none text-small" style="font-size:10px;">({{ __("Mother's email")}})</div>
                            </div>
                            <div class="col-md-3 border-bottom pt-2 text-right d-none d-sm-block">
                                ({{ __("Mother's email")}})
                            </div>
                        </div>

                        <div class="form-group row col-md-12" id="student_email_div">
                            <div class="btn-group col-md-9 text-left">
                                <input type="checkbox" id="student_email_chk" name="student_email_chk" value="" style="float: left;margin: 15px 5px;width: 15px;height: 15px;" checked>
                                <label for="student_email_chk" id="student_email_cap" name="student_email_cap"></label>
                                <div class="d-block d-sm-none text-small" style="font-size:10px;">({{ __("Student's email")}})</div>
                            </div>
                            <div class="col-md-3 pt-2 text-right d-none d-sm-block">
                                ({{ __("Student's email")}})
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="text-left">
                                <div class="checked">
                                    <input class="form-control" style="display: block;" type="email" id="other_email" name="other_email" placeholder="Add another email adress here" value="" maxlength="100">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 text-left">
                                <div>
                                    <p></p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                                <button type="submit" id="email_send" class="btn btn-sm btn-theme-success">Send</button>
                        </div>

                    <!-- </form> -->

                </div>
            </div>
        </div>
    </div>
</div>
<?php
function extractExtraCharges($inputString) {
    // Utilisation d'une expression régulière pour rechercher le motif "Extra charges" suivi d'un espace et d'un ou plusieurs chiffres
    $pattern = '/Extra charges (\d+)/';

    // Utilisation de la fonction preg_match pour chercher le motif dans le string $inputString
    if (preg_match($pattern, $inputString, $matches)) {
        // $matches[0] contient la chaîne correspondant au motif entier (par exemple, "Extra charges 50")
        // $matches[1] contient le premier groupe capturé par les parenthèses dans l'expression régulière (dans ce cas, le chiffre)
        // On retourne le chiffre extrait
        return $matches[1];
    } else {
        // Si le motif n'a pas été trouvé, on peut retourner false ou une valeur par défaut selon les besoins
        return false;
    }
}
?>

@endsection


@section('footer_js')

<script type="text/javascript">
    $("#date_invoice").datetimepicker({
	format: "yyyy-mm-dd",
		autoclose: true,
		todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
    $("#date_invoice_due").datetimepicker({
		format: "yyyy-mm-dd",
		autoclose: true,
		todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
    $(document).ready(function () {
        if (document.getElementById("invoice_status").value == '10') {
            document.getElementById("unlock_btn").style.display = "block";
        } else {

        }

        // tabclick event
        var x = document.getElementsByClassName("tab-pane active");
		// $('#active_tab').val(x[0].id);
		// $('#active_tab_user').val(x[0].id);
        $('button[data-bs-toggle=tab]').click(function(e){
            var target = $(e.target).attr("data-bs-target_val") // activated tab

             //document.getElementById("save_btn").style.display = "none";
            // document.getElementById("delete_btn_inv").style.display = "none";
            // document.getElementById("issue_inv_btn").style.display = "none";
            // document.getElementById("print_preview_btn").style.display = "none";
            // document.getElementById("download_pdf_btn_a").style.display = "none";


            var x = document.getElementsByClassName("tab-pane active");
            //console.log(x[0].id);
            console.log(target);
            //DisplayOnOff_buttons(target);

            //invoice_status: 10 - issued, 1- create
            if (target == "tab_1") {
                document.getElementById("save_btn").style.display = "none";
            } else if (target == "tab_2") {
                document.getElementById("save_btn").style.display = "none";
            } else if (target == "tab_3") {
                document.getElementById("save_btn").style.display = "block";
            }

        });

        $(".numeric").keyup(function () {
            CalculateDiscount('discount');
        });

        $(".numeric_amount").keyup(function () {
            CalculateDiscount('amount');
        });


        $('#unlock_btn').click(function (e) {
            var p_invoice_id = document.getElementById("invoice_id").value;

            if (p_invoice_id == '') {
                errorModalCall(GetAppMessage('Invalid_invoice'));

                return false;
            }
            var status = '';
            var data = 'type=unlock_invoice&p_invoice_id=' + p_invoice_id;
            $.ajax({
                url: BASE_URL+'/unlock_invoice',
                data: data,
                type: 'POST',
                dataType: 'json',
                async: false,
                success: function (result) {
                    status = result.status;
                    if (status == 'success') {
                        document.getElementById("invoice_status_text").text = 'En création';
                        document.getElementById("invoice_status").value = '1';
                        DisplayOnOff_buttons();
                    }
                    else {
                        errorModalCall(GetAppMessage('error_message_text'));

                    }
                },   //success
                error: function (ts) {
                    errorModalCall(GetAppMessage('error_message_text'));

                }
            }); //ajax-type

        });

        $('#payment_btn').click(function (e) {
            var p_invoice_id = document.getElementById("invoice_id").value;
            var payment_status = ''; /* 0=unpaid, 1=paid*/

            if (p_invoice_id == '') {
                errorModalCall('Invalid_invoice');
                return false;
            }

            if (document.getElementById("payment_status").value == '1') {
                payment_status = '0';
            } else {
                payment_status = '1';
            }
            //alert('payment_status='+payment_status);
            var status = '';
            var data = 'type=update_payment_status&p_payment_status=' + payment_status + '&p_auto_id=' + p_invoice_id;
            // console.log(data);
            // return false;
            $.ajax({
                url: BASE_URL+'/update_payment_status',
                data: data,
                type: 'POST',
                dataType: 'json',
                async: false,
                success: function (result) {

                    status = result.status;
                    if (status == 'success') {
				        successModalCall('invoice payment paid');
                    }
                    else {
                        errorModalCall('error_message_text');
                    }
                },   //success
                error: function (ts) {
                    errorModalCall('error_message_text');

                }
            }); //ajax-type

        });
    });

    function DisplayOnOff_buttons(p_tab) {
        var invoice_status = document.getElementById("invoice_status").value;
        if (invoice_status == '10') {
            document.getElementById("unlock_btn").style.display = "block";
            document.getElementById("issue_inv_btn").style.display = "none";
            document.getElementById("print_preview_btn").style.display = "none";
            document.getElementById("delete_btn_inv").style.display = "none";
            document.getElementById("save_btn").style.display = "none";
            document.getElementById("download_pdf_btn_a").style.display = "block";

            if ($("#approved_flag").val() == '0') {
                document.getElementById("approved_btn").style.display = "block";
            }
            else {
                document.getElementById("approved_btn").style.display = "none";
            }
        } else {
            if (p_tab == "tab_1") {
                document.getElementById("save_btn").style.display = "none";
                document.getElementById("issue_inv_btn").style.display = "block";
                document.getElementById("print_preview_btn").style.display = "block";
                document.getElementById("delete_btn_inv").style.display = "block";
            } else {
                document.getElementById("save_btn").style.display = "block";
                document.getElementById("print_preview_btn").style.display = "block";
                document.getElementById("delete_btn_inv").style.display = "block";

            }

        }
    }
    // $('#print_preview_btn').click(function (e) {
    //     /*
    //     var auto_id=document.getElementById("auto_id").value;
    //     var url='../invoice/invoice_view.php?auto_id='+auto_id+'&action=view';
    //     window.open(url, '_blank');
    //     */
    //     Generate_View_PDF('preview');
    // });
    function executeAsynchronously(functions, timeout) {
        for(var i = 0; i < functions.length; i++) {
            setTimeout(functions[i], timeout);
            if (i==functions.length-1) {
                setTimeout(function () {
                //successModalCall('Invoice Updated successfully!');

                    location.reload()
                }, 1000);
            }
        }
    }
    $('#issue_inv_btn').click(function (e) {
        var x = document.getElementsByClassName("tab-pane active");
        //if (x[0].id == "pane_main") {

        // } else if (x[0].id == "tab_2") {
          //  step1().then(step2).then(step3);
          executeAsynchronously(
    [UpdateInvoiceInfo, UpdateInvoiceSummaryAmount, Generate_View_PDF], 10);
        // if (x[0].id == "tab_3") {
        //     UpdateInvoiceInfo();
        // } else {
        //     //console.log('sss');
        //     UpdateInvoiceSummaryAmount();
        // }
        // Generate_View_PDF('issue_pdf');
        //setTimeout(function(){ window.location.replace('/admin/'+p_school_id+'/invoices'); }, 1000);


    });

    function Generate_View_PDF(p_type='issue_pdf') {
        if (p_type =='preview') {
            console.log('{{ $invoice->invoice_filename ? $invoice->invoice_filename : "" }}');
            window.open('{!! $invoice->invoice_filename !!}', '_blank');
        } else {
            var p_invoice_id = document.getElementById("invoice_id").value;
            var data = 'type=' + p_type + '&p_invoice_id=' + p_invoice_id;

            UpodateInvStatusIssue(p_invoice_id)
        }

    }

    function UpodateInvStatusIssue(p_invoice) {
        var modal = document.getElementById('myModal');
        //modal.style.display = "block";
        $.ajax({
            url: BASE_URL + '/update_payment_status',
            //url: 'update_status_issue',
            data: 'invoice_status=10&approved_flag=0&p_auto_id=' + p_invoice,
            type: 'POST',
            dataType: 'json',
            //async: false,
            success: function (result) {
                var status = result.status;

                if (status == 'success') {
                    $("#invoice_status_text").text('Emise');
                    //document.getElementById("invoice_status").text='Emise';
                    document.getElementById("invoice_status").value = '10';
                    document.getElementById("unlock_btn").style.display = "block";
                    //$('#unlock_btn').style.display="block";
                    //DisplayOnOff_buttons();
                    //modal.style.display = "none";
                    location.reload()
                }
                else {

                    errorModalCall(GetAppMessage('error_message_text'));

                }
            },   //success
            error: function (ts) {
                //modal.style.display = "none";
                // errorModalCall(GetAppMessage('error_message_text'));

            }
        }); //ajax-type

    }

    $('#delete_btn_inv').click(function (e) {
        var x = document.getElementsByClassName("tab-pane active");

        Swal.fire({
        title: 'Are you sure?',
        text: "The lessons/events from that invoice will be available to invoice after you delete this invoice.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
            'Deleted!',
            'Invoice has been deleted.',
            'success'
            )
            DeleteInvoice();
        }
        })
        return false;
    });

    function DeleteInvoice() {
        var p_invoice_id = document.getElementById("invoice_id").value;
        var p_person_id = document.getElementById("person_id").value;
        var p_invoice_type = document.getElementById("invoice_type").value;

        if (p_invoice_id == '') {
            errorModalCall(GetAppMessage('Invalid_invoice'));

            return false;
        }
        var status = '';
        var data = 'type=delete_invoice&p_invoice_id=' + p_invoice_id;
        data += '&p_person_id=' + p_person_id + '&p_invoice_type=' + p_invoice_type;
        $.ajax({
            //url: 'invoice_data.php',
            url: BASE_URL + '/delete_invoice',
            data: data,
            type: 'POST',
            dataType: 'json',
            async: false,
            success: function (result) {
                status = result.status;
                if (status == 'success') {
                    successModalCall('Invoice deleted');

                    var p_school_id = document.getElementById("p_school_id").value;

                    setTimeout(function(){ window.location.replace('/admin/'+p_school_id+'/invoices'); }, 1000);
                }
                else {
                    errorModalCall(GetAppMessage('error_message_text'));

                }
            },   //success
            error: function (ts) {
                errorModalCall(GetAppMessage('error_message_text'));

            }
        }); //ajax-type
    }


    function SendPayRemiEmail(p_value,p_invoice_type,p_school_id) {

        $('#seleted_auto_id').val(p_value);
        $('#p_school_id').val(p_school_id);

        $('#seleted_invoice_type').val(p_invoice_type);
        //console.log('p_value='+p_value);
        var p_attached_file = '';

        var find_flag = 0;
        //return false;
        //populate lis of emails
        $.ajax({
            url: BASE_URL + '/pay_reminder_email_fetch',
            //url: 'invoice_data.php',
            data: 'type=email_list&p_auto_id=' + p_value,
            type: 'POST',
            dataType: 'json',
            //async: false,
            success: function (result) {
                if (result.status) {
                    confirmPayReminderModalCall(p_value,'Do you want to validate events',result.data,p_school_id);
                    return false;

                }
                else {
                    errorModalCall('{{ __("Event validation error ")}}');
                }

            },   // sucess
            error: function (ts) {
                errorModalCall(GetAppMessage('error_message_text'));
                //alert(ts.responseText + 'populate Invoice Payment Status')
            }
        }); // Ajax


        $("#email_list_modal").modal('show');

    };

    $('#email_send').click(function (e) {
        var p_emails = '', p_attached_file = '';
        var p_inv_auto_id = document.getElementById("invoice_id").value;
        var p_seleted_invoice_type = document.getElementById("invoice_type").value;
        var p_school_id = document.getElementById("p_school_id").value;

        if (document.getElementById("father_email_chk").checked) {
            if (document.getElementById("father_email_cap").textContent != '') {
                p_emails = document.getElementById("father_email_cap").textContent + "|";
            }
        }
        if (document.getElementById("mother_email_chk").checked) {
            if (document.getElementById("mother_email_cap").textContent != '') {
                p_emails += document.getElementById("mother_email_cap").textContent + "|";
            }
        }

        if (document.getElementById("student_email_chk").checked) {
            if (document.getElementById("student_email_cap").textContent != '') {
                p_emails += document.getElementById("student_email_cap").textContent + "|";
            }
        }

        if ($('#other_email').val() != '') {
            p_emails += $('#other_email').val();
        }


        console.log('list emails send', p_emails);

        SendInvoiceEmail('send_approve_pdf_invoice', p_inv_auto_id, p_attached_file, p_emails,p_school_id)




    });

    $('#download_pdf_btn_a').click(function (e) {
        //var inv='invoice-'+document.getElementById("invoice_id").value.toLowerCase().replace(/-/ig,'');

        var inv = document.getElementById("invoice_filename").value;
        //var filename='../medias/vgskating/pdf/invoice-'+inv+'.pdf';
        var filename = '../medias/schools/teamvg/' + 'pdf/';
        //filename=filename+inv+'.pdf';
        filename = filename + inv;
        //window.open(filename,'_blank');
        window.open('../invoice/viewdownload_pdf.php?type=D&filename=' + inv, '_blank');

    });

    $('#save_btn').click(function (e) {

        var formdata = $("#form_details").serializeArray();
//return console.log(formdata);

        var x = document.getElementsByClassName("tab-pane active");
        //if (x[0].id == "pane_main") {

        // } else if (x[0].id == "tab_2") {

        // if (x[0].id == "tab_3") {
        //     UpdateInvoiceInfo();
        // } else {
        //     //console.log('sss');
        //     UpdateInvoiceSummaryAmount();
        // }
        //executeAsynchronously(
        //[UpdateInvoiceInfo, UpdateInvoiceSummaryAmount], 10);
        UpdateInvoiceInfo();
    });

    function UpdateInvoiceSummaryAmount() {
        var p_invoice_id = document.getElementById("invoice_id").value;

        if (p_invoice_id == '') {
            errorModalCall(GetAppMessage('Invalid_invoice'));

            return false;
        }

        var p_disc1 = $("#sdiscount_percent_1").val();
        if ($('#total_commission').length > 0) {
            var p_amt1 = $("#total_commission").val();
        } else{
            var p_amt1 = $("#samount_discount_1").val();
        }

        var p_extra_expenses = $("#sextra_expenses").val();


        var p_total_amount =  $("#grand_total_amount").text();
        var total_amount_with_discount = $("#stotal_amount_with_discount").text();
        //var p_tax_amount = $("#tax_amount").val();

        var data = 'type=update_invoice_discount&p_invoice_id=' + p_invoice_id;
        data += '&p_disc1=' + p_disc1;
        data += '&p_amt1=' + p_amt1;
        data += '&total_amount_with_discount=' + total_amount_with_discount + '&p_extra_expenses=' + p_extra_expenses;
        data += '&p_total_amount=' + p_total_amount;
        //data += '&p_tax_amount=' + p_tax_amount;
        // console.log(data);
        // return false;
        $.ajax({
            url: BASE_URL + '/update_invoice_discount',
            //url: 'invoice_data.php',
            data: data,
            type: 'POST',
            dataType: 'json',
            async: false,
            success: function (result) {
                var status = result.status;
                // console.log(result);
                // return true;
                //if (status == 'success') {
                    successModalCall('Invoice issued');
                    var p_school_id = document.getElementById("p_school_id").value;
                    //return true;
                    //setTimeout(function(){ window.location.replace('/admin/'+p_school_id+'/invoices'); }, 1000);
                    //alert(GetAppMessage("save_confirm_message"));
                //}
                //else {
                //    errorModalCall(GetAppMessage('error_message_text'));

                //}
            }, //success
            error: function (ts) {
                errorModalCall(GetAppMessage('error_message_text'));

            }
        }); //ajax-type
        return false;
    }

    function UpdateInvoiceInfo() {
        var p_invoice_id = document.getElementById("invoice_id").value;

        if (p_invoice_id == '') {
            errorModalCall(GetAppMessage('error_message_text'));

            return false;
        }

        var vform = $("#form_details")[0];
        var form_data = new FormData(vform);
        for (var [key, value] of form_data.entries()) {
            console.log(key, value);
        }
        form_data.append('type', 'update_invoice_info');
        form_data.append('p_invoice_id', p_invoice_id);

    //  return  console.log(form_data)

        // return false;
        $.ajax({
            url: BASE_URL+'/update_invoice_info',
            data: form_data,
            type: 'POST',
            dataType: 'json',
            async: false,
            contentType: false,
            cache: false,
            processData: false,
            success: function (result) {
                //console.log(result);
                var status = result.status;
                if (status == 'success') {
                    successModalCall('Invoice issued');
                    var p_school_id = document.getElementById("p_school_id").value;
                    return true;

                    //alert(GetAppMessage("save_confirm_message"));
                }
                else {
                    errorModalCall(GetAppMessage('error_message_text'));

                }
            },   //success
            error: function (ts) {
                errorModalCall(GetAppMessage('error_message_text'));

            }
        }); //ajax-type
        return false;
    }

    function CalculateDiscount(type) {
        var subtotal_amount_all = 0.0, amt_for_disc = 0.0, total_amount_discount = 0.0, total_commission = 0.0, total_amount = 0.0, subtotal_amount_no_discount = 0.0;
        var disc1 = 0.0, disc2 = 0.0, disc3 = 0.0, disc4 = 0.0, disc5 = 0.0, disc6 = 0.0;
        var disc1_amt = 0.0, disc2_amt = 0.0, disc3_amt = 0.0, disc4_amt = 0.0, disc5_amt = 0.0, disc6_amt = 0.0, tax_amount = 0.0;



        var subtotal_amount_with_discount = 0.0;
        var subtotal_amount_with_discount_lesson = 0.0;
        var subtotal_amount_with_discount_event = 0.0;

        if ($('#ssubtotal_amount_with_discount_lesson').length > 0) {
            subtotal_amount_all = $("#ssubtotal_amount_with_discount_lesson").text();
            if ($('#sdiscount_percent_1').length > 0) {
                disc1 = $("#sdiscount_percent_1").val();
            }
            if ($('#samount_discount_1').length > 0) {
                disc1_amt = $("#samount_discount_1").val();
            }

            disc1_amt = ((type == 'discount')?Number((subtotal_amount_all * disc1) / 100):Number(disc1_amt));
            disc1 = ((type == 'amount')?Number((disc1_amt * 100) / subtotal_amount_all):Number(disc1));

            if(type == 'discount'){
                if ($('#samount_discount_1').length > 0) {
                    $("#samount_discount_1").val(parseFloat(disc1_amt).toFixed(2));
                }
            } else {
                if ($('#sdiscount_percent_1').length > 0) {
                    $("#sdiscount_percent_1").val(parseFloat(disc1).toFixed(2));
                }
            }


            total_amount_discount = Number(disc1_amt);
            if ($('#stotal_amount_discount').length > 0) {
                $("#stotal_amount_discount").val(parseFloat(total_amount_discount).toFixed(2));
            }
            subtotal_amount_with_discount_lesson = Number($("#ssubtotal_amount_with_discount_lesson").text());
            subtotal_amount_with_discount_lesson = Number(+subtotal_amount_with_discount_lesson) - Number(+total_amount_discount);
        }
        if ($('#stotal_amount_with_discount_lesson').length > 0) {
            $("#stotal_amount_with_discount_lesson").text(parseFloat(subtotal_amount_with_discount_lesson).toFixed(2));
        }
        if ($('#stotal_amount_with_discount_event').length > 0) {
            subtotal_amount_with_discount_event = Number($("#stotal_amount_with_discount_event").text());
        }
        //console.log(subtotal_amount_with_discount_event);
        subtotal_amount_with_discount = Number(+subtotal_amount_with_discount_lesson) + Number(+subtotal_amount_with_discount_event);

        if ($('#stotal_amount_with_discount').length > 0) {
            $("#stotal_amount_with_discount").text(parseFloat(subtotal_amount_with_discount).toFixed(2));
        }
        //subtotal_amount_with_discount_lesson = Number(+stotal_amount_with_discount_lesson) + Number(+total_amount_discount);

        //subtotal_amount_no_discount=parseFloat($("#ssubtotal_amount_no_discount").text());
        subtotal_amount_no_discount = 0;

        total_amount = (+subtotal_amount_no_discount) + (+subtotal_amount_with_discount);

        if ($('#total_commission').length > 0) {
            total_commission = Number($("#total_commission").val());

            total_amount = (Number(total_amount) - (total_commission));

        }


        //$("#stotal_amount").text(total_amount);
        //console.log(total_amount);

        var extra = 0;
        var taxes = 0;
        if ($('#sextra_expenses').length > 0) {
            extra = Number(document.getElementById("sextra_expenses").value);
        }
        if ($('#taxes').length > 0) {
            taxes = Number(document.getElementById("taxes").value);
        }
        var grand_total = (+total_amount) + (+extra) + (+taxes);

        //console.log(grand_total);
        $("#grand_total_amount").text(parseFloat(grand_total).toFixed(2));

        $("#grand_total_cap").html(parseFloat(grand_total).toFixed(2));
        //$("#tax_amount_cap").html(parseFloat(tax_amount).toFixed(2));
        //$("#extra_expenses_cap").html(parseFloat(extra).toFixed(2));

    }
</script>
@endsection
