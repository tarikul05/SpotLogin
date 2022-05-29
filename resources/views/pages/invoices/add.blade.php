@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<!-- color wheel -->
<script src="{{ asset('js/jquery.wheelcolorpicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/wheelcolorpicker.css')}}"/>
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
						<a id="payment_btn" target="" href="" class="btn btn-theme-warn" style="display: block;"><i class="fa fa-money" aria-hidden="true"></i> Flag as Paid</a>
						<button id="approved_btn" target="" href="" class="btn btn-theme-success" style="display: block;">Send by email</button>
						<a id="download_pdf_btn_a" target="" href="" class="btn btn-theme-outline" style="display: block;"><i class="fa fa-file-pdf-o"></i>
							<lebel name="download_pdf_btn" id="download_pdf_btn">Download PDF</lebel>
						</a>
					</div>
				</div>
			</div>
		</header>
		<!-- Tabs navs -->
		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true"> {{ __('Invoice Detail') }} </button>
				<button class="nav-link" id="nav-prices-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-logo" aria-selected="false"> {{ __('Calculation') }} </button>
				<button class="nav-link" id="nav-logo-tab" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="nav-logo" aria-selected="false"> {{ __('Basic Data') }} </button>
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<input type="hidden" id="user_id" name="user_id" value="4">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
				<form role="form" id="form_main" class="form-horizontal" method="post" action="">
					<fieldset>
						<label class="section_header_class" id="tab1_header"></label>
						<table class="table" id="invoice_list_item" name="invoice_list_item" style="font-size:1em;">
							<tbody>
								<tr>
									<th width="30%"><span id="row_hdr_date" name="row_hdr_date">{{ __('Date') }}</span></th>
									<th width="40%"><span id="item_particular_caption" name="item_particular_caption">{{ __('Details') }}</span></th>
									<th width="15%" style="text-align:right"><span id="item_unit_caption" name="item_unit_caption">{{ __('Unit') }}</span></th>
									<th width="15%" style="text-align:right"><span id="row_hdr_amount" name="row_hdr_amount">{{ __('Amount') }}</span></th>
								</tr>
								<tr>
									<td>16.04.2022 11:22</td>
									<td>heloo (ice skating-school) Number of Students 3</td>
									<td style="text-align:right">30 minutes</td>
									<td style="text-align:right">25.00</td>
								</tr>
								<tr>
									<td>18.05.2022 14:30</td>
									<td> (soccer-school) Number of Students 3</td>
									<td style="text-align:right">30 minutes</td>
									<td style="text-align:right">225.00</td>
								</tr>
								<tr>
									<td colspan="1" rowspan="7" style="vertical-align:middle;" class="disc_bottom_rows">{{ __('Reduction of 10.00% on value 201 to 400 is 25.00 Total duration of courses 60 minutes, 1 hours and 0 minutes.') }}</td>
								</tr>
								<tr>
									<td colspan="1" style="text-align:right">{{ __('Sub-total') }} </td>
									<td style="text-align:right">60 minutes</td>
									<td style="text-align:right">250.00</td>
								</tr>
								<tr>
									<td colspan="1" style="text-align:right">{{ __('Discount on course') }} </td>
									<td></td>
									<td style="text-align:right">- 25.00</td>
								</tr>
								<tr>
									<td colspan="1" style="text-align:right">{{ __('Extra charges') }}</td>
									<td></td>
									<td style="text-align:right">+ <span id="extra_expenses_cap">0.00</span></td>
								</tr>
								<tr>
									<td colspan="1" style="text-align:right">{{ __('Total') }}</td>
									<td></td>
									<td style="text-align:right"><span id="grand_total_cap">225.00</span></td>
								</tr>
							</tbody>
						</table>
					</fieldset>
				</form>
			</div>
			<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
				<form role="form" id="form_finance" class="form-horizontal" method="post" action="">
					<fieldset>
					<div class="section_header_class">
						<label id="subtot_before_disc_cap">Subtotals before discounts</label>
					</div>
					<div class="col-md-8 offset-md-2">
						<div class="form-group row">
							<label id="payment_status_label" class="col-lg-3 col-sm-3 text-right">{{ __('Payment Status') }}:</label>
							<div class="col-sm-2">
								<p>
									<label id="payment_status_text" name="payment_status_text"></label>
								</p>
							</div>
						</div>
						<div class="form-group row">
							<label id="disc_on_course_hrs_cap" class="col-lg-3 col-sm-3 text-right">{{ __('Subtotal (not subject to reduction)') }}</label>
							<div class="col-sm-2">
								<p class="form-control-static numeric">
									<label class="currency_display">CHF</label>
								</p>
							</div>
							<div class="col-sm-2">
								<p class="form-control-static numeric">
									<label id="ssubtotal_amount_no_discount">0.00</label>
								</p>
							</div>
						</div>
						<div class="form-group row">
							<label id="subtot_subject_to_redu_cap" name="subtot_subject_to_redu_cap" class="col-lg-3 col-sm-3 text-right">{{ __('Subtotal (subject to reduction)') }}</label>
							<div class="col-sm-2">
								<p class="form-control-static"> +
									<label class="currency_display">CHF</label>
								</p>
							</div>
							<div class="col-sm-2">
								<p class="form-control-static numeric">
									<label id="ssubtotal_amount_with_discount">250.00</label>
								</p>
							</div>
						</div>
						<div class="form-group row">
							<label id="sub_total_caption" name="sub_total_caption" class="col-lg-3 col-sm-3 text-right">{{ __('Sous-total') }}:</label>
							<div class="col-sm-2">
								<p class="form-control-static"> =
									<label class="currency_display">CHF</label>
								</p>
							</div>
							<div class="col-sm-2">
								<p class="form-control-static numeric">
									<label id="ssubtotal_amount_all">250.00</label>
								</p>
							</div>
						</div>
					</div>

					<div class="section_header_class">
						<label class="section_header_class" id="redu_on_course_hrs_cap" name="redu_on_course_hrs_cap">{{ __('Reduction on course hours') }}</label>
					</div>

					<div class="col-md-8 offset-md-2">
						<div class="form-group row">
							<label id="sdiscount_percent_1_cap" class="col-lg-3 col-sm-3 text-right" for="sdiscount_percent_1">{{ __('Reduction Rate') }}: </label>
							<div class="col-sm-2">
								<div class="input-group"><span class="input-group-addon currency_display">CHF</span>
									<input type="text" class="form-control numeric_amount" id="samount_discount_1" name="samount_discount_1" value="0" placeholder="">
								</div>
							</div>
							<div class="col-sm-2 offset-md-1">
								<div class="input-group"><span class="input-group-addon">%</span>
									<input type="text" class="form-control numeric" id="sdiscount_percent_1" name="sdiscount_percent_1" value="0" placeholder=""> </div>
							</div>
						</div>
						<div style="display:none;">
							<div class="form-group row">
								<label id="sdiscount_percent_2_cap" class="col-lg-3 col-sm-3 text-right" for="sdiscount_percent_2">{{ __('Taux réduction 401-600') }}:</label>
								<div class="col-sm-2">
									<p class="form-control-static">+
										<label class="currency_display">CHF</label>
									</p>
								</div>
								<div class="col-sm-2">
									<input type="text" class="form-control numeric_amount" id="samount_discount_2" name="samount_discount_2" value="0" placeholder="">
								</div>
								<div class="col-sm-2 text-right">
									<div class="input-group"><span class="input-group-addon">%</span>
										<input type="text" class="form-control numeric" id="sdiscount_percent_2" name="sdiscount_percent_2" value="0" placeholder=""> 
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label id="sdiscount_percent_3_cap" class="col-lg-3 col-sm-3 text-right" for="sdiscount_percent_3">{{ __('Taux réduction 601-800') }}:</label>
								<div class="col-sm-2">
									<p class="form-control-static">+
										<label class="currency_display">CHF</label>
									</p>
								</div>
								<div class="col-sm-2">
									<input type="text" class="form-control numeric_amount" id="samount_discount_3" name="samount_discount_3" value="0" placeholder="">
								</div>
								<div class="col-sm-2">
									<div class="input-group"><span class="input-group-addon">%</span>
										<input type="text" class="form-control numeric" id="sdiscount_percent_3" name="sdiscount_percent_3" value="0" placeholder=""> </div>
								</div>
							</div>
							<div class="form-group row">
								<label id="sdiscount_percent_4_cap" class="col-lg-3 col-sm-3 text-right" for="sdiscount_percent_4">{{ __('Taux réduction 801-1000') }}:</label>
								<div class="col-sm-2">
									<p class="form-control-static">+
										<label class="currency_display">CHF</label>
									</p>
								</div>
								<div class="col-sm-2">
									<input type="text" class="form-control numeric_amount" id="samount_discount_4" name="samount_discount_4" value="0" placeholder="">
								</div>
								<div class="col-sm-2">
									<div class="input-group"><span class="input-group-addon">%</span>
										<input type="text" class="form-control numeric" id="sdiscount_percent_4" name="sdiscount_percent_4" value="0" placeholder=""> 
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label id="sdiscount_percent_5_cap" class="col-lg-3 col-sm-3 text-right" for="sdiscount_percent_5">{{ __('Taux réduction 1001-1200') }}:</label>
								<div class="col-sm-2">
									<p class="form-control-static">+
										<label class="currency_display">CHF</label>
									</p>
								</div>
								<div class="col-sm-2">
									<input type="text" class="form-control numeric_amount" id="samount_discount_5" name="samount_discount_5" value="0" placeholder="">
								</div>
								<div class="col-sm-2">
									<div class="input-group"><span class="input-group-addon">%</span>
										<input type="text" class="form-control numeric" id="sdiscount_percent_5" name="sdiscount_percent_5" value="0" placeholder=""> 
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label id="sdiscount_percent_6_cap" class="col-lg-3 col-sm-3 text-right" for="sdiscount_percent_6">{{ __('Taux réduction 1200 et plus') }}:</label>
								<div class="col-sm-2">
									<p class="form-control-static">+
										<label class="currency_display">CHF</label>
									</p>
								</div>
								<div class="col-sm-2">
									<input type="text" class="form-control numeric_amount" id="samount_discount_6" name="samount_discount_6" value="0" placeholder="">
								</div>
								<div class="col-sm-2">
									<div class="input-group"><span class="input-group-addon">%</span>
										<input type="text" class="form-control numeric" id="sdiscount_percent_6" name="sdiscount_percent_6" value="0" placeholder=""> 
									</div>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label id="stotal_amount_discount_cap" class="col-sm-3 text-right">{{ __('Total de la réduction') }}:</label>
							<div class="col-sm-2">
								<div class="input-group"><span class="input-group-addon currency_display">CHF</span>
									<input type="text" class="form-control numeric_amount" id="stotal_amount_discount" name="stotal_amount_discount" value="0" placeholder="" readonly=""> 
								</div>
							</div>
						</div>
					</div>
					
					<div class="section_header_class">
						<label class="section_header_class" id="final_total_cap">{{ __('Final Total') }}</label>
					</div>

					<div class="col-md-8 offset-md-2">
						<div class="form-group row">
							<label id="stotal_amount_no_discount_cap" class="col-lg-3 col-sm-3 text-right">{{ __('Total Amount before Discount') }}</label>
							<div class="col-sm-2">
								<p class="form-control-static">
									<label class="currency_display">CHF</label>
								</p>
							</div>
							<div class="col-sm-2">
								<p id="stotal_amount_no_discount" class="form-control-static numeric">0.00</p>
							</div>
						</div>
						<div class="form-group row">
							<label id="stotal_amount_with_discount_cap" class="col-lg-3 col-sm-3 text-right">{{ __('Total Amount after Discount') }}</label>
							<div class="col-sm-2">
								<p class="form-control-static"> +
									<label class="currency_display">CHF</label>
								</p>
							</div>
							<div class="col-sm-2">
								<p id="stotal_amount_with_discount" class="form-control-static numeric">225.00</p>
							</div>
						</div>
						<div class="form-group row">
							<label id="sextra_expenses_cap" name="sextra_expenses_cap" class="col-lg-3 col-sm-3 text-right">{{ __('Frais (Additional Expenses)') }}:</label>
							<div class="col-sm-2">
								<p class="form-control-static"> +
									<label class="currency_display">CHF</label>
								</p>
							</div>
							<div class="col-sm-2">
								<input type="text" class="form-control numeric" id="sextra_expenses" name="sextra_expenses" value="0" placeholder=""> </div>
						</div>
						<div id="tax_amount_div" name="tax_amount_div" class="form-group row">
							<label id="tax_cap" name="tax_cap" class="col-lg-3 col-sm-3 text-right">Tax:</label>
							<div class="col-sm-2">
								<p class="form-control-static"> +
									<label class="currency_display">CHF</label>
								</p>
							</div>
							<div class="col-sm-2">
								<input type="text" class="form-control numeric" id="tax_amount" name="tax_amount" value="0" placeholder=""> </div>
						</div>
						<div class="form-group row">
							<label id="grand_total_amount_cap" name="grand_total_amount_cap" class="col-lg-3 col-sm-3 text-right">{{ __('Grand Total') }}</label>
							<div class="col-sm-2">
								<p class="form-control-static"> =
									<label class="currency_display">CHF</label>
								</p>
							</div>
							<div class="col-sm-2">
								<p id="stotal_amount" class="form-control-static numeric" style="text-align:right;display: none;">225.00</p>
								<p id="grand_total_amount" name="grand_total_amount" class="form-control-static numeric">225.00</p>
							</div>
						</div>
					</div>
					</fieldset>
				</form>
			</div>
			<div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tab_3">
				<form class="form-horizontal" id="add_teacher" action="http://localhost:8000/edit-teacher/12" method="POST" enctype="multipart/form-data" name="add_teacher" role="form">
					<input type="hidden" name="_token" value="sFD17gatUngGK5kuFWC2nZKEa1vtNtraV0nqnMvz">
					<input type="hidden" id="school_id" name="school_id" value="1">
					<input type="hidden" id="school_name" name="school_name" value="Tarikul Islam">
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Basic Data') }}</label>
						</div>
						<div class="row">
							<div class="col-md-8 offset-md-2">
								<div class="form-group row">
									<label id="invoice_type_cap" for="invoice_type_name" class="col-lg-3 col-sm-3 text-right">{{ __('Invoice Type') }}</label>
									<label id="invoice_type_name" class="col-sm-5">Professor</label>
								</div>
								<div class="form-group row">
									<label id="row_hdr_status" name="row_hdr_status" for="invoice_status" class="col-lg-3 col-sm-3 text-right">{{ __('Status') }}</label>
									<div class="col-lg-2 col-sm-2 text-left">
										<label id="invoice_status">{{ __('Issued') }}</label>
										<div> <a id="unlock_btn" href="" class="btn btn-xs btn-warning" style="display: block;"><span id="unlock_btn_cap">Unlock</span></a> </div>
									</div>
								</div>
								<!-- invoice date -->
								<div class="form-group row">
									<label id="invoice_date_cap" class="col-lg-3 col-sm-3 text-right">{{ __('Date of invoice') }}</label>
									<div class="col-sm-2">
										<div class="input-group" id="date_invoice_div">
											<input id="date_invoice" value="" name="date_invoice" type="text" class="form-control"> 
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
										</div>
									</div>
								</div>
								<!-- -->
								<div class="form-group row">
									<label id="start_of_period_cap" class="col-lg-3 col-sm-3 text-right">{{ __('Start of Period') }}</label>
									<label id="start_date" class="col-sm-7">Friday, April 1, 2022 12:00 AM</label>
								</div>
								<div class="form-group row">
									<label id="end_of_period_cap" class="col-lg-3 col-sm-3 text-right">End of Period</label>
									<label id="end_date" class="col-sm-7">Sunday, May 22, 2022 12:00 AM</label>
								</div>
								<div class="form-group row">
									<label id="payment_date_cap" class="col-lg-3 col-sm-3 text-right">{{ __('Date of Payment') }}</label>
									<label id="placement" class="col-sm-5"></label>
								</div>
								<div class="form-group row">
									<label id="invoice_title_cap" for="invoice_title" class="col-lg-3 col-sm-3 text-right">{{ __('invoice Title') }}</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" id="invoice_title" name="invoice_title" value="" placeholder="" maxlength="150"> </div>
								</div>
								<div class="form-group row">
									<label id="invoice_header_cap" for="invoice_header" class="col-lg-3 col-sm-3 text-right">{{ __('Invoice Header') }}</label>
									<div class="col-sm-7">
										<textarea class="form-control" id="invoice_header" name="invoice_header" placeholder="" rows="6" maxlength="2000"></textarea>
									</div>
								</div>
								<div class="form-group row">
									<label id="invoice_footer_cap" for="invoice_footer" class="col-lg-3 col-sm-3 text-right">{{ __('Invoice Footer') }}</label>
									<div class="col-sm-7">
										<textarea class="form-control" id="invoice_footer" name="invoice_footer" placeholder="" rows="6" maxlength="2000"></textarea>
									</div>
								</div>

							</div>
							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="address_caption">{{ __('Client Information') }}:</label>
							</div>
							<div class="row">
								<div class="col-md-8 offset-md-2">
									<div class="form-group row">
										<label id="client_name_caption" name="client_name_caption" for="client_name" class="col-lg-3 col-sm-3 text-right">{{ __('Client Name') }}</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="client_name" name="client_name" value="Team VG Skating" placeholder="" maxlength="250"> </div>
									</div>
									<div class="form-group row">
										<label id="gender_label_id" name="gender_label_id" for="client_gender_id" class="col-lg-3 col-sm-3 text-right">{{ __('Gender') }} : *</label>
										<div class="col-sm-5">
											<div class="selectdiv">
												<select class="form-control" id="client_gender_id" name="client_gender_id">
													@foreach($genders as $key => $gender)
														<option value="{{ $key }}" {{ old('gender_id') == $key ? 'selected' : ''}}>{{ $gender }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label id="family_name_label_id" name="family_name_label_id" for="client_lastname" class="col-lg-3 col-sm-3 text-right">{{ __('Family Name :') }}*</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="client_lastname" name="client_lastname" value="" placeholder="" maxlength="250"> </div>
									</div>
									<div class="form-group row">
										<label id="first_name_label_id" name="first_name_label_id" for="client_firstname" class="col-lg-3 col-sm-3 text-right">{{ __('First Name :') }} *</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="client_firstname" name="client_firstname" value="Vanessa" placeholder="" maxlength="250"> </div>
									</div>
									<div class="form-group row">
										<label id="street_caption" name="street_caption" for="client_street" class="col-lg-3 col-sm-3 text-right">{{ __('Street') }}</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="client_street" name="client_street" value="Rue Hans Wilsdorf" placeholder="" maxlength="120"> </div>
									</div>
									<div class="form-group row">
										<label id="street_number_caption" name="street_number_caption" for="client_street_number" class="col-lg-3 col-sm-3 text-right">{{ __('Street No') }} :</label>
										<div class="col-sm-2">
											<input type="text" class="form-control" id="client_street_number" name="client_street_number" value="" placeholder="" maxlength="20"> </div>
									</div>
									<div class="form-group row">
										<label id="street2_caption" name="street2_caption" for="client_street2" class="col-lg-3 col-sm-3 text-right">{{ __('Street 2 ') }}:</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="client_street2" name="client_street2" value="Patinoire des Vernets" placeholder="" maxlength="100"> </div>
									</div>
									<div class="form-group row">
										<label id="postal_code_caption" name="postal_code_caption" for="client_zip_code" class="col-lg-3 col-sm-3 text-right">{{ __('Postal Code') }} :</label>
										<div class="col-sm-2">
											<input type="text" class="form-control" id="client_zip_code" name="client_zip_code" value="1227" placeholder="" maxlength="8"> </div>
									</div>
									<div class="form-group row">
										<label id="locality_caption" name="locality_caption" for="client_place" class="col-lg-3 col-sm-3 text-right">{{ __('City') }} :</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="client_place" name="client_place" value="Les Acacias" placeholder="" maxlength="120"> </div>
									</div>
									<div class="form-group row">
										<label id="pays_caption" name="pays_caption" for="client_country_id" class="col-lg-3 col-sm-3 text-right">{{ __('Country') }} :</label>
										<div class="col-sm-5">
											<div class="selectdiv">
												<select class="form-control" id="client_country_id" name="client_country_id">
													@foreach($countries as $country)
														<option value="{{ $country->code }}">{{ $country->name }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div id="client_province_id_div" class="form-group row" style="display:none;">
										<label id="province_caption" for="client_province_id" class="col-lg-3 col-sm-3 text-right">{{ __('Province') }}: </label>
										<div class="col-sm-5">
											<div class="selectdiv">
												<select class="form-control" id="client_province_id" name="client_province_id">
													<option value="">Select Province</option>
													<option value="3">Alberta</option>
													<option value="2">British Columbia</option>
													<option value="5">Manitoba</option>
													<option value="10">Newfoundland &amp; Labrador</option>
													<option value="12">Northwest territory</option>
													<option value="8">Nova Scotia</option>
													<option value="11">Nunavut</option>
													<option value="6">Ontario</option>
													<option value="9">PEI</option>
													<option value="7">Quebec</option>
													<option value="4">Saskatchewan</option>
													<option value="13">Yukon</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="contact_info_caption">{{ __('Basic data Seller (creditor of invoice)') }}</label>
							</div>
							<div class="row">
								<div class="col-md-8 offset-md-2">
									<div class="form-group row">
										<label id="seller_name_caption" name="seller_name_caption" for="seller_name" class="col-lg-3 col-sm-3 text-right">{{ __('Seller Name') }}</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="seller_name" name="seller_name" value="Soumendra Middey" placeholder="" maxlength="250"> </div>
									</div>
									<div class="form-group row">
										<label id="gender_label_id" name="gender_label_id" for="seller_gender_id" class="col-lg-3 col-sm-3 text-right">{{ __('Gender') }} : *</label>
										<div class="col-sm-5">
											<div class="selectdiv">
												<select class="form-control" id="seller_gender_id" name="seller_gender_id">
													@foreach($genders as $key => $gender)
														<option value="{{ $key }}" {{ old('gender_id') == $key ? 'selected' : ''}}>{{ $gender }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label id="family_name_label_id" name="family_name_label_id" for="seller_lastname" class="col-lg-3 col-sm-3 text-right">{{ __('Family Name') }} :*</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="seller_lastname" name="seller_lastname" value="" placeholder="" maxlength="250"> </div>
									</div>
									<div class="form-group row">
										<label id="first_name_label_id" name="first_name_label_id" for="seller_firstname" class="col-lg-3 col-sm-3 text-right">{{ __('First Name') }} : *</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="seller_firstname" name="seller_firstname" value="" placeholder="" maxlength="250"> </div>
									</div>
									<div class="form-group row">
										<label id="street_caption" name="street_caption" for="seller_street" class="col-lg-3 col-sm-3 text-right">{{ __('Street') }}</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="seller_street" name="seller_street" value="" placeholder="" maxlength="120"> </div>
									</div>
									<div class="form-group row">
										<label id="street_number_caption" name="street_number_caption" for="seller_street_number" class="col-lg-3 col-sm-3 text-right">{{ __('Street No') }} :</label>
										<div class="col-sm-2">
											<input type="text" class="form-control" id="seller_street_number" name="seller_street_number" value="" placeholder="" maxlength="20"> </div>
									</div>
									<div class="form-group row">
										<label id="street_caption" name="street_caption" for="seller_street2" class="col-lg-3 col-sm-3 text-right">{{ __('Street') }}</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="seller_street2" name="seller_street2" value="" placeholder="" maxlength="100"> </div>
									</div>
									<div class="form-group row">
										<label id="postal_code_caption" name="postal_code_caption" for="seller_zip_code" class="col-lg-3 col-sm-3 text-right">{{ __('Postal Code') }} :</label>
										<div class="col-sm-2">
											<input type="text" class="form-control" id="seller_zip_code" name="seller_zip_code" value="" placeholder="" maxlength="8"> </div>
									</div>
									<div class="form-group row">
										<label id="locality_caption" name="locality_caption" for="seller_place" class="col-lg-3 col-sm-3 text-right">{{ __('City') }} :</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="seller_place" name="seller_place" value="" placeholder="" maxlength="120"> </div>
									</div>
									<div class="form-group row">
										<label id="pays_caption" name="pays_caption" for="seller_country_id" class="col-lg-3 col-sm-3 text-right">{{ __('Country') }} :</label>
										<div class="col-sm-5">
											<div class="selectdiv">
												<select class="form-control" id="seller_country_id" name="seller_country_id">
													@foreach($countries as $country)
														<option value="{{ $country->code }}">{{ $country->name }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div id="seller_province_id_div" class="form-group row" style="display:none">
										<label id="province_caption" for="seller_province_id" class="col-lg-3 col-sm-3 text-right">{{ __('Province') }}: </label>
										<div class="col-sm-5">
											<div class="selectdiv">
												<select class="form-control" id="seller_province_id" name="seller_province_id">
													<option value="">Select Province</option>
													<option value="3">Alberta</option>
													<option value="2">British Columbia</option>
													<option value="5">Manitoba</option>
													<option value="10">Newfoundland &amp; Labrador</option>
													<option value="12">Northwest territory</option>
													<option value="8">Nova Scotia</option>
													<option value="11">Nunavut</option>
													<option value="6">Ontario</option>
													<option value="9">PEI</option>
													<option value="7">Quebec</option>
													<option value="4">Saskatchewan</option>
													<option value="13">Yukon</option>
												</select>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label id="phone_caption" name="phone_caption" for="seller_phone" class="col-lg-3 col-sm-3 text-right">{{ __('Téléphone') }}:</label>
										<div class="col-sm-5">
											<input type="text" class="form-control" id="seller_phone" name="seller_phone" value="" placeholder="" maxlength="50"> </div>
									</div>
									<div class="form-group row">
										<label id="mobile_caption" name="mobile_caption" for="seller_mobile" class="col-lg-3 col-sm-3 text-right">{{ __('Mobile') }}:</label>
										<div class="col-sm-5">
											<input type="text" class="form-control" id="seller_mobile" name="seller_mobile" value="" placeholder="" maxlength="50"> </div>
									</div>
									<div class="form-group row">
										<label id="email_caption" name="email_caption" for="seller_email" class="col-lg-3 col-sm-3 text-right">{{ __('Email') }}:</label>
										<div class="col-sm-5">
											<input type="text" class="form-control" id="seller_email" name="seller_email" value="" placeholder="" maxlength="50"> </div>
									</div>
									<div class="form-group row">
										<label for="seller_eid" class="col-lg-3 col-sm-3 text-right">{{ __('EID') }}:</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="seller_eid" name="seller_eid" value="" placeholder="" maxlength="100"> </div>
									</div>
								</div>
							</div>

							<div id="commentaire_div">
								<div class="section_header_class">
									<label id="private_comment_caption">{{ __('Payment Bank Information') }}</label>
								</div>
								<div class="row">
									<div class="col-md-8 offset-md-2">
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-right">{{ __('Payment Bank Account Name') }} :</label>
											<div class="col-sm-7">
												<input type="text" name="private_bank_info" class="form-control"> 
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-right">{{ __('IBAN No') }} </label>
											<div class="col-sm-7">
												<input type="text" name="private_bank_info" class="form-control"> 
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-right">{{ __('Account No') }} </label>
											<div class="col-sm-7">
												<input type="text" name="private_bank_info" class="form-control"> 
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-right">{{ __('SWIFT A/c No') }} </label>
											<div class="col-sm-7">
												<input type="text" name="private_bank_info" class="form-control"> 
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-right">{{ __('Nom de la banque') }}:</label>
											<div class="col-sm-7">
												<input type="text" name="private_bank_info" class="form-control"> 
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-right">{{ __('Address') }}</label>
											<div class="col-sm-7">
												<input type="text" name="private_bank_info" class="form-control"> 
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-right">{{ __('Postal Code') }} :</label>
											<div class="col-sm-7">
												<input type="text" name="private_bank_info" class="form-control"> 
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-right">{{ __('City') }} :</label>
											<div class="col-sm-7">
												<input type="text" name="private_bank_info" class="form-control"> 
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-right">{{ __('Country') }} :</label>
											<div class="col-sm-7">
												<div class="selectdiv">
													<select class="form-control" id="spayment_bank_country_id" name="spayment_bank_country_id">
														@foreach($countries as $country)
															<option value="{{ $country->code }}">{{ $country->name }}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div id="spayment_bank_country_id_div" class="form-group row" style="display:none">
											<label id="province_caption" for="seller_province_id" class="col-lg-3 col-sm-3 text-right">{{ __('Province') }}: </label>
											<div class="col-sm-5">
												<div class="selectdiv">
													<select class="form-control" id="seller_province_id" name="seller_province_id">
														<option value="">Select Province</option>
														<option value="3">Alberta</option>
														<option value="2">British Columbia</option>
														<option value="5">Manitoba</option>
														<option value="10">Newfoundland &amp; Labrador</option>
														<option value="12">Northwest territory</option>
														<option value="8">Nova Scotia</option>
														<option value="11">Nunavut</option>
														<option value="6">Ontario</option>
														<option value="9">PEI</option>
														<option value="7">Quebec</option>
														<option value="4">Saskatchewan</option>
														<option value="13">Yukon</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
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
    <div id="pageloader">
      <img src="http://localhost:8000/img/loading.gif" alt="processing..." />
    </div>
</div>
@endsection


@section('footer_js')
<script type="text/javascript">
$(document).ready(function(){
	var c_country = $('#client_country_id option:selected').val();
	var s_country = $('#seller_country_id option:selected').val();
	var p_country = $('#spayment_bank_country_id option:selected').val();
	if(s_country == 'CA'){
		$('#seller_province_id_div').show();
	}
	if(c_country == 'CA'){
		$('#client_province_id_div').show();
	}
	if(p_country == 'CA'){
		$('#spayment_bank_country_id_div').show();
	}
})

$('#seller_country_id').change(function(){
	var country = $(this).val();

	if(country == 'CA'){
		$('#seller_province_id_div').show();
	}else{
		$('#seller_province_id_div').hide();
	}
})

$('#spayment_bank_country_id').change(function(){
	var country = $(this).val();

	if(country == 'CA'){
		$('#spayment_bank_country_id_div').show();
	}else{
		$('#spayment_bank_country_id_div').hide();
	}
})

$('#client_country_id').change(function(){
	var country = $(this).val();

	if(country == 'CA'){
		$('#client_province_id_div').show();
	}else{
		$('#client_province_id_div').hide();
	}
})

$(function() {
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
});

</script>
@endsection