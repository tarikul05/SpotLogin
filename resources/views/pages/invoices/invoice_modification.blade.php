@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{ asset('js/datetimepicker-lang/moment-with-locales.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<!-- color wheel -->
<script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
@endsection

@section('content')
<div class="content">
	<div class="container-fluid">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area">
					<div class="page_header_class">
						<label id="page_header" name="page_header">Invoice Detail</label>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 btn-area">
					    <div class="pull-right btn-group save-button">
                            <a id="issue_inv_btn" name="issue_inv_btn" class="btn btn-sm btn-success" target="">
                                <i class="fa fa-cog" aria-hidden="true"></i> Issue invoice
                            </a> 
                            <a id="print_preview_btn" href="<?php echo $invoice->invoice_filename?$invoice->invoice_filename : route('generateInvoicePDF',['invoice_id'=> $invoice->id]) ?>" name="print_preview_btn" class="btn btn-theme-outline" target="_blank">Print Preview</a>
                            <a id="delete_btn_inv" name="delete_btn_inv" class="btn btn-theme-warn" href="">Delete</a>
                            <a id="save_btn" name="save_btn" class="btn btn-theme-success">Save</a>

                            <a id="payment_btn" target="" href="" class="btn"><i class="fa fa-money" aria-hidden="true"></i> payé</a>
                            <button id="approved_btn" target="" href="" class="btn btn-theme-success" onclick="SendPayRemiEmail({{$invoice->id}},{{$invoice->invoice_type}},{{$invoice->school_id}})">Send by email</button>
                            <a id="download_pdf_btn_a" target="_blank" href="<?php echo $invoice->invoice_filename?$invoice->invoice_filename : route('generateInvoicePDF',['invoice_id'=> $invoice->id]) ?>" class="btn btn-theme-outline"><i class="fa fa-file-pdf-o"></i>
                                <lebel name="download_pdf_btn" id="download_pdf_btn">Download PDF</lebel>
                            </a>

                        </div>
                        <!-- <div class="float-end btn-group">
                            <button type="submit" id="save_btn" name="save_btn" class="btn btn-theme-success student_save"><i class="fa fa-save"></i>Save</button> 
                            <a style="display: none;" id="delete_btn_inv" href="#" class="btn btn-theme-warn"><em class="glyphicon glyphicon-trash"></em> Delete:</a> 
                        </div> -->
				</div>
			</div>
		</header>
		<!-- Tabs navs -->
		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-invoice-tab" data-bs-toggle="tab" data-bs-target="#tab_1" data-bs-target_val="tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Invoice Detail') }}</button>
				<button class="nav-link" id="nav-calculation-tab" data-bs-toggle="tab" data-bs-target="#tab_2" data-bs-target_val="tab_2" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Calculation') }}</button>
				<button class="nav-link" id="nav-basic-tab" data-bs-toggle="tab" data-bs-target="#tab_3" data-bs-target_val="tab_3" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Basic Data') }}</button>
			</div>
		</nav>
		<!-- Tabs navs -->
		<!-- Tabs content -->
			<div class="tab-content" id="invoice-details-content">
				<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
						<form role="form" id="form_main" class="form-horizontal" method="post" action="">
                        <fieldset>
                            @if ($invoice->seller_country_id != 'CA')
                                <label class="section_header_class">{{ $invoice->invoice_header }}</label>
                            @endif
                            <table class="table" id="invoice_list_item" name="invoice_list_item" style="font-size:1em;">
                                <tbody>
                                    <tr class="header_tbl">
                                        <th width="30%"><span id="row_hdr_date" name="row_hdr_date">{{ __('Date') }}</span></th>
                                        <th width="40%"><span id="item_particular_caption" name="item_particular_caption">{{ __('Details') }}</span></th>
                                        <th width="15%" style="text-align:right"><span id="item_unit_caption" name="item_unit_caption">{{ __('Unit') }}</span></th>
                                        <th width="15%" style="text-align:right"><span id="row_hdr_amount" name="row_hdr_amount">{{ __('Amount') }}</span></th>
                                    </tr>
                                    @php 
                                        $total_min = 0;
                                        $total_amount = 0;
                                        $sub_total = 0;
                                    @endphp
                                    @if (!empty($invoice->invoice_items))
                                        @foreach($invoice->invoice_items as $key => $item)
                                            <tr>
                                                <td>{{ !empty($item->item_date) ? Carbon\Carbon::parse($item->item_date)->format('d.m.Y') : ''; }}</td>
                                                <td style="text-align:right">{{ !empty($item->caption) ? $item->caption : ''; }}</td>
                                                @if ($item->unit == 0)
                                                    <td></td>
                                                @else
                                                    <td style="text-align:right">{{ $item->unit }} minutes</td>
                                                @endif
                                                <td style="text-align:right">{{ !empty($item->total_item) ? number_format($item->total_item,'2') : ''; }}</td>
                                            </tr>
                                            @php 
                                                $sub_total += $item->price_unit;
                                                $total_amount +=$item->total_item;
                                                $total_min = $total_min + $item->unit;
                                            @endphp
                                        @endforeach
                                    @endif
                                    
                                   
                                    <tr>
                                        <td colspan="1" rowspan="7" style="vertical-align:bottom;"></td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="1" style="text-align:right">Sub-total </td>
                                        <td style="text-align:right">{{$total_min}} minutes</td>
                                        <td style="text-align:right">{{ number_format($sub_total,'2') }}</td>
                                    </tr>
                                    @if ($invoice->total_amount_discount != 0)
                                        <tr>
                                            <td colspan="1" style="text-align:right">Discount</td>
                                            <td></td>
                                            <td style="text-align:right">- {{$invoice->total_amount_discount}}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="1" style="text-align:right">Extra charges</td>
                                        <td></td>
                                        <td style="text-align:right">+ <span id="extra_expenses_cap">{{$invoice->extra_expenses}}</span></td>
                                    </tr>
                                    @if ($invoice->tax_amount != 0)
                                        <tr>
                                            <td colspan="1" style="text-align:right">{{$invoice->tax_desc}}</td>
                                            <td></td>
                                            <td style="text-align:right">+<span id="tax_amount_cap">{{$invoice->tax_amount}}</span></td>
                                        </tr>
                                    @endif
                                    @php
                                        $grand_total = $sub_total + $invoice->extra_expenses;
                                    @endphp
                                    <tr>
                                        <td colspan="1" style="text-align:right">Total</td>
                                        <td></td>
                                        <td style="text-align:right"><span id="grand_total_cap">{{ number_format($grand_total,'2') }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="hidden" id="total_min" name="action" value="{{$total_min}}">
                            <input type="hidden" id="invoice_status" name="invoice_status" value="{{$invoice->invoice_status}}">
                            <input type="hidden" id="approved_flag" name="approved_flag" value="0">
                            <input type="hidden" id="invoice_id" name="invoice_id" value="{{$invoice->id}}">
                            <input type="hidden" id="invoice_type" name="invoice_type" value="{{$invoice->invoice_type}}">
                            
                            @if($invoice->invoice_type ==1)
                                <input type="hidden" id="person_id" name="person_id" value="{{$invoice->client_id}}">
                            @else
                                <input type="hidden" id="person_id" name="person_id" value="{{$invoice->seller_id}}">
                            @endif
                        </fieldset>
                    </form>
				</div>
				<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
					<form role="form" id="form_finance" class="form-horizontal" method="post" action="">
                        <fieldset>
                            <!-- Subtotal before discounts-->
                            <label class="section_header_class">{{__('Subtotals before discounts')}}</label>
                            <div class="form-group row">
                                <label id="payment_status_label" class="col-lg-3 col-sm-3 text-right" style="text-align:right;">Payment Status:</label>
                                <div class="col-sm-1">
                                    <p style="text-align:right;">
                                        <label id="payment_status_text" name="payment_status_text"></label>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="disc_on_course_hrs_cap" class="col-lg-3 col-sm-3 text-right">Subtotal (not subject to reduction)</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static numeric" style="text-align:right;">
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? ' ('.$invoice->invoice_currency .') ':''; ?></label>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <p class="form-control-static numeric" style="text-align:right;">
                                        <label id="ssubtotal_amount_no_discount"><?php echo $invoice->subtotal_amount_no_discount ? $invoice->subtotal_amount_no_discount :''; ?></label>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-sm-3 text-right">{{__("Subtotal (subject to reduction)")}}</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static" style="text-align:right;"> +
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? $invoice->invoice_currency :''; ?></label>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <p class="form-control-static numeric" style="text-align:right;">
                                        <label id="ssubtotal_amount_with_discount"><?php echo $invoice->subtotal_amount_with_discount ? $invoice->subtotal_amount_with_discount :''; ?></label>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="sub_total_caption" name="sub_total_caption" class="col-lg-3 col-sm-3 text-right" style="text-align:right;">Sous-total:</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static" style="text-align:right;"> =
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? ' ('.$invoice->invoice_currency .') ':''; ?></label>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <p class="form-control-static numeric" style="text-align:right;">
                                        <label id="ssubtotal_amount_all"><?php echo $invoice->subtotal_amount_all ? $invoice->subtotal_amount_all :''; ?></label>
                                    </p>
                                </div>
                            </div>
                            <label class="section_header_class" id="redu_on_course_hrs_cap" name="redu_on_course_hrs_cap">Reduction on course hours</label>
                            <div class="form-group row">
                                <label id="sdiscount_percent_1_cap" class="col-lg-3 col-sm-3 text-right" style="text-align:right;" for="sdiscount_percent_1">Reduction Rate: </label>
                                <div class="col-sm-2">

                                    <?php
                                    $disc1_amt = $invoice->amount_discount_1 ? $invoice->amount_discount_1 :0;            
                                    ?>
                                    <!-- <p id="samount_discount_1" class="form-control-static numeric"
                                                                            style="text-align:right;">0.00</p> -->
                                    <div class="input-group"><span class="input-group-addon currency_display"><?php echo $invoice->invoice_currency ? ' ('.$invoice->invoice_currency .') ':''; ?></span>
                                        <input type="text" class="form-control numeric_amount" id="samount_discount_1" name="samount_discount_1" value="{{$disc1_amt}}" placeholder=""> 
                                    </div>
                                </div>
                                <div class="col-sm-2 text-right">
                                    <div class="input-group"><span class="input-group-addon">%</span>
                                        <input type="text" class="form-control numeric" id="sdiscount_percent_1" name="sdiscount_percent_1" value="{{$invoice->discount_percent_1 ? $invoice->discount_percent_1 :0}}" placeholder=""> 
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label id="stotal_amount_discount_cap" class="col-sm-3 text-right" style="text-align:right;">Total de la réduction:</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon currency_display"><?php echo $invoice->invoice_currency ? ' ('.$invoice->invoice_currency .') ':''; ?></span>
                                        <input type="text" class="form-control numeric_amount" id="stotal_amount_discount" name="stotal_amount_discount" value="{{$invoice->total_amount_discount ? $invoice->total_amount_discount :0}}" placeholder="" readonly=""> 
                                    </div>
                                </div>
                            </div>
                            <label class="section_header_class">{{__('Final Total')}}</label>
                            <div class="form-group row">
                                <label id="stotal_amount_no_discount_cap" class="col-lg-3 col-sm-3 text-right" style="text-align:right;">Total Amount before Discount</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static" style="text-align:right;">
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? $invoice->invoice_currency :''; ?></label>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <p id="stotal_amount_no_discount" class="form-control-static numeric" style="text-align:right;"><?php echo $invoice->total_amount_no_discount ? $invoice->total_amount_no_discount :''; ?></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="stotal_amount_with_discount_cap" class="col-lg-3 col-sm-3 text-right" style="text-align:right;">Total Amount after Discount</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static" style="text-align:right;"> +
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? $invoice->invoice_currency :''; ?></label>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <p id="stotal_amount_with_discount" class="form-control-static numeric" style="text-align:right;"><?php echo $invoice->total_amount_with_discount ? $invoice->total_amount_with_discount :''; ?></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label id="sextra_expenses_cap" name="sextra_expenses_cap" class="col-lg-3 col-sm-3 text-right" style="text-align:right;">Frais (Additional Expenses):</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static" style="text-align:right;"> +
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? $invoice->invoice_currency :''; ?></label>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <input type="text" class="form-control numeric" id="sextra_expenses" name="sextra_expenses" value="{{$invoice->extra_expenses ? $invoice->extra_expenses :0}}" placeholder=""> 
                                </div>
                            </div>
                            <div id="tax_amount_div" name="tax_amount_div" class="form-group" style="display: none;">
                                <label id="tax_cap" name="tax_cap" class="col-lg-3 col-sm-3 text-right" style="text-align:right;">Tax:</label>
                                <div class="col-sm-1" style="width:80px;">
                                    <p class="form-control-static" style="text-align:right;"> +
                                        <label class="currency_display"><?php echo $invoice->invoice_currency ? $invoice->invoice_currency :''; ?></label>
                                    </p>
                                </div>
                                <div class="col-sm-1">
                                    <input type="text" class="form-control numeric" id="tax_amount" name="tax_amount" value="{{$invoice->tax_amount ? $invoice->tax_amount :0}}" placeholder=""> 
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
                                    <p id="stotal_amount" class="form-control-static numeric" style="text-align:right;display: none;"><?php echo $invoice->total_amount ? $invoice->total_amount : ''; ?></p>
                                    <p id="grand_total_amount" name="grand_total_amount" class="form-control-static numeric" style="text-align:right;"><?php echo $invoice->total_amount ? $invoice->total_amount : ''; ?></p>
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
                                    <label id="invoice_status">{{ $invoice_status_all[$invoice->invoice_status]; }}</label>
                                    <div> 
                                        <a id="unlock_btn" href="" class="btn btn-xs btn-warning" style="display: none;">
                                            <span id="unlock_btn_cap">Unlock</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- invoice date -->
                            <div class="form-group row">
                                <label id="invoice_date_cap" class="col-lg-3 col-sm-3 text-right">Date of invoice</label>
                                <div class="col-sm-2">
                                    <div class="input-group" id="date_invoice1">
                                        <input id="date_invoice" name="date_invoice" type="text" class="form-control" value="{{$invoice->date_invoice ? $invoice->date_invoice :''}}"> 
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <!-- -->
                            <div class="form-group row">
                                <label id="start_of_period_cap" class="col-lg-3 col-sm-3 text-right">Start of Period</label>
                                <label id="start_date" class="col-sm-7">{{$invoice->period_starts ? $invoice->period_starts :''}}</label>
                            </div>
                            <div class="form-group row">
                                <label id="end_of_period_cap" class="col-lg-3 col-sm-3 text-right">End of Period</label>
                                <label id="end_date" class="col-sm-7">{{$invoice->period_ends ? $invoice->period_ends :''}}</label>
                            </div>
                            <div class="form-group row">
                                <label id="payment_date_cap" class="col-lg-3 col-sm-3 text-right">Date of Payment</label>
                                <label id="placement" class="col-sm-5"></label>
                            </div>
                            <div class="form-group row">
                                <label id="invoice_title_cap" for="invoice_title" class="col-lg-3 col-sm-3 text-right">invoice Title</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="invoice_title" name="invoice_title" value="{{$invoice->invoice_name ? $invoice->invoice_name :''}}" placeholder="" maxlength="150"> 
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
                                                    <option value="CA">Canada</option>
                                                    <option value="FR">France</option>
                                                    <option value="CH">Switzerland</option>
                                                    <option value="US">United States</option>
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
        <div class="modal-dialog mt-5" role="document">
            <div class="modal-content">
                <div class="modal-header text-center border-0">
                    <h4 class="light-blue-txt gilroy-bold">Send a reminder</h4>
                </div>
                <div class="modal-body row" style="margin: 0 auto;padding-top: 0;">
                    <!-- <form id="email_list_form" name="email_list_form" method="POST"> -->

                        <div class="form-group col-md-12" id="father_email_div">
                            <div class="btn-group text-left">
                                <input type="checkbox" id="father_email_chk" name="father_email_chk" value="" style="float: left;margin: 8px 5px;width: 20px;height: 20px;" checked>
                                <label for="father_email_chk" id="father_email_cap" name="father_email_cap">Father's email:</label>
                            </div>
                        </div>

                        <div class="form-group col-md-12" id="mother_email_div">
                            <div class="btn-group text-left">
                                <input type="checkbox" id="mother_email_chk" name="mother_email_chk" value="" style="float: left;margin: 8px 5px;width: 20px;height: 20px;" checked>
                                <label for="mother_email_chk" id="mother_email_cap" name="mother_email_cap">Mother's email:</label>
                            </div>
                        </div>

                        <div class="form-group col-md-12" id="student_email_div">
                            <div class="btn-group text-left">
                                <input type="checkbox" id="student_email_chk" name="student_email_chk" value="" style="float: left;margin: 8px 5px;width: 20px;height: 20px;" checked>
                                <label for="student_email_chk" id="student_email_cap" name="student_email_cap">Student's email:</label>
                            </div>

                        </div>

                        <div class="form-group col-md-12">
                            <div class="text-left">
                                <div class="checked">
                                    <input class="form-control" style="display: block;" type="email" id="other_email" name="other_email" placeholder="other email if any." value="" maxlength="100">
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
@endsection


@section('footer_js')
<script type="text/javascript">
    $("#date_invoice").datetimepicker({
		format: "dd/mm/yyyy",
		autoclose: true,
		todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
    $(document).ready(function () {
        document.getElementById("save_btn").style.display = "none";
        document.getElementById("print_preview_btn").style.display = "none";
        document.getElementById("issue_inv_btn").style.display = "none";
        document.getElementById("delete_btn_inv").style.display = "none";
        document.getElementById("download_pdf_btn_a").style.display = "none";
        document.getElementById("approved_btn").style.display = "none";
        document.getElementById("payment_btn").style.display = "none";
        if (document.getElementById("invoice_status").val == 10) {
            document.getElementById("unlock_btn").style.display = "block";
            document.getElementById("issue_inv_btn").style.display = "none";
            document.getElementById("print_preview_btn").style.display = "none";
            document.getElementById("delete_btn_inv").style.display = "none";
            document.getElementById("save_btn").style.display = "none";
            document.getElementById("download_pdf_btn_a").style.display = "block";
            document.getElementById("payment_btn").style.display = "block";
            if ($("#approved_flag").val() == '0') {
                document.getElementById("approved_btn").style.display = "block";
            }
            else {
                document.getElementById("approved_btn").style.display = "none";
            }
           
        } else {
            document.getElementById("unlock_btn").style.display = "none";
            document.getElementById("issue_inv_btn").style.display = "block";
            document.getElementById("delete_btn_inv").style.display = "block";
            document.getElementById("approved_btn").style.display = "none";
            document.getElementById("payment_btn").style.display = "none";
            document.getElementById("print_preview_btn").style.display = "block";
                
        }

        // tabclick event
        var x = document.getElementsByClassName("tab-pane active");
		// $('#active_tab').val(x[0].id);
		// $('#active_tab_user').val(x[0].id);
        $('button[data-bs-toggle=tab]').click(function(e){
            var target = $(e.target).attr("data-bs-target_val") // activated tab
            
            document.getElementById("save_btn").style.display = "none";
            document.getElementById("delete_btn_inv").style.display = "none";
            document.getElementById("issue_inv_btn").style.display = "none";
            document.getElementById("print_preview_btn").style.display = "none";
            document.getElementById("download_pdf_btn_a").style.display = "none";


            var x = document.getElementsByClassName("tab-pane active");
            //console.log(x[0].id);
            console.log(target);
            DisplayOnOff_buttons(target);

            //invoice_status: 10 - issued, 1- create
            if (target == "tab_1") {

            } else if (target == "tab_2") {

            } else if (target == "tab_3") {

            }

        });

        
    });

    function DisplayOnOff_buttons(p_tab) {
        var invoice_status = document.getElementById("invoice_status").value;
        if (invoice_status == 10) {
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
    $('#issue_inv_btn').click(function (e) {
        Generate_View_PDF('issue_pdf');
    });

    function Generate_View_PDF(p_type) {
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
                    $("#invoice_status").text('Emise');
                    //document.getElementById("invoice_status").text='Emise';
                    document.getElementById("invoice_status").value = '10';
                    document.getElementById("unlock_btn").style.display = "block";
                    //$('#unlock_btn').style.display="block";
                    DisplayOnOff_buttons();
                    //modal.style.display = "none";
                }
                else {

                    errorModalCall(GetAppMessage('error_message_text'));

                }
            },   //success
            error: function (ts) {
                modal.style.display = "none";
                errorModalCall(GetAppMessage('error_message_text'));

            }
        }); //ajax-type        

    }

    $('#delete_btn_inv').click(function (e) {
        var x = document.getElementsByClassName("tab-pane active");
        DeleteInvoice();
        window.history.back();
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

                    successModalCall(GetAppMessage('save_confirm_message'));
                        
                        setTimeout(function(){ window.location.replace('/admin/invoices'); }, 3000);
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
                //console.log(result);
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
        var p_inv_auto_id = $('#seleted_auto_id').val();
        var p_seleted_invoice_type = $('#seleted_invoice_type').val();
        var p_school_id = document.getElementById("p_school_id").value;
        if ((document.getElementById("father_email_chk").checked == true) && (document.getElementById("father_email_chk").value != '')) {
            p_emails = document.getElementById("father_email_chk").value + "|";
        }
        if ((document.getElementById("mother_email_chk").checked == true) && (document.getElementById("mother_email_chk").value != '')) {
            p_emails += document.getElementById("mother_email_chk").value + "|";
        }

        if ((document.getElementById("student_email_chk").checked == true) && (document.getElementById("student_email_chk").value != '')) {
            p_emails += document.getElementById("student_email_chk").value + "|";
        }
        if ($('#other_email').val() != '') {
            p_emails += $('#other_email').val();
        }


        console.log(p_seleted_invoice_type);
        if (p_seleted_invoice_type == 2) {
            SendInvoiceEmail('send_approve_pdf_invoice', p_inv_auto_id, p_attached_file, p_emails,p_school_id);
        } else {
            SendInvoiceEmail('reminder_email_unpaid', p_inv_auto_id, p_attached_file, p_emails,p_school_id);
        }


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
</script>
@endsection