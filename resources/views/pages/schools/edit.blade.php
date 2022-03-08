@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>

<script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
@endsection

@section('content')
  <div class="content">
	<div class="container-fluid body">
		
			<header class="panel-heading" style="border: none;">
				<div class="row panel-row" style="margin:0;">
					<div class="col-sm-6 col-xs-12 header-area">
						<div class="page_header_class">
							<label id="page_header" name="page_header">School Key Information</label>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12 btn-area">
						<div class="float-end btn-group">
							<button type="submit" class="btn bg-info text-white save_button float-end" id="update_btn">
								{{ __('Save')}}
							</button>
						</div>
					</div>    
				</div>          
			</header>
			<!-- Tabs navs -->

			<nav>
				<div class="nav nav-tabs" id="nav-tab" role="tablist">
					<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Contact Information</button>
					<button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">User Account</button>
					<button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Parameters</button>
				</div>
			</nav>
			<!-- Tabs navs -->

			<!-- Tabs content -->
			<div class="tab-content" id="ex1-content">
				<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
					<form id="schoolForm" name="schoolForm" class="form-horizontal" role="form"
					 action="{{!empty($school) ? route('school.update',[$school->id]): '/'}}" method="POST" enctype="multipart/form-data">
						@csrf
						<fieldset>
							<div class="section_header_class">
								<label id="teacher_personal_data_caption">Personal data of the school</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{ __('Status')}}: *</label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control" name="is_active" id="is_active">
													<option value="">Select</option>
													<option value="1" {{!empty($school) ? (old('is_active', $school->is_active) == 1 ? 'selected' : '') : (old('is_active') == 1 ? 'selected' : '')}}>{{ __('Active')}}</option>
													<option value="0" {{!empty($school) ? (old('is_active', $school->is_active) == 0 ? 'selected' : '') : (old('is_active') == 0 ? 'selected' : '')}}>{{ __('Inactive')}}</option>
												</select>
											</div>
										</div>
									</div>
									<!-- <div class="form-group row">
											<label id="school_code_caption" name="school_code_caption" class="col-lg-3 col-sm-3 text-left">School Code
													*:</label>
											<div class="col-sm-7">
												<input type="text" class="form-control" id="school_code"
														name="school_code" maxlength="30" 
														value="{{!empty($school->school_code) ? old('school_code', $school->school_code) : old('school_code')}}">
												
											</div>
									</div> -->
									<div class="form-group row">
										<label id="row_hdr_school_name"
												class="col-lg-3 col-sm-3 text-left">Name of the School
												*:</label>
										<div class="col-sm-7">
												<input type="text" class="form-control" id="school_name"
														name="school_name"
														value="{{!empty($school->school_name) ? old('school_name', $school->school_name) : old('school_name')}}">
										</div>
										
									</div>
									<div class="form-group row">
										<label id="organization_type_caption"
										class="col-lg-3 col-sm-3 text-left">Organization Type *:</label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control" name="legal_status" id="legal_status">
													<option value="">Select</option>
													@foreach ($legal_status as $key => $value)
														<option value="{{ $value['code'] }}" {{!empty($school->legal_status) ? (old('legal_status', $school->legal_status) == $value['code'] ? 'selected' : '') : (old('legal_status') == $value['code'] ? 'selected' : '')}}>
															
														{{ __($value['drop_text']) }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">

										<label id="school_type_lbl" class="col-lg-3 col-sm-3 text-left">
											Account Type:
										</label>
										<label id="school_type" class="col-lg-3 col-sm-3 text-left">
											School
										</label>
									</div>
									<div class="form-group row">
										<label id="sender_email_label" name="sender_email_label" 
										class="col-lg-3 col-sm-3 text-left">Sender email address :</label>
										<div class="col-sm-7">
												<input type="email" id="sender_email"
												name="sender_email" size="100" class="form-control" 
												value="{{!empty($school->sender_email) ? old('sender_email', $school->sender_email) : old('sender_email')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" id="birth_date_label_id">Incorporation Date:</label>
										<div class="col-sm-7">
											<div class="input-group" id="sbirth_date_div"> 
												<input id="incorporation_date" name="incorporation_date" type="text" class="form-control date_picker"
												value="{{!empty($school->incorporation_date) ? old('incorporation_date', $school->incorporation_date) : old('incorporation_date')}}">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>
									</div>
									
								
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="row">
								<div class="col-xs-11" style="padding: 0 30px;">
										<div class="form-group alert alert-info row">
												<label for="default_currency_code" id="default_currency_lbl"
														name="default_currency_lbl"
														class="col-lg-3 col-sm-3 text-end">base currency</label>
												<div class="col-sm-2">
														<div class="selectdiv">
															<select class="form-control" name="default_currency_code" id="default_currency_code">
															<option value="">Select</option>
															@foreach ($currency as $key => $value)
																		<option 
																		value="{{ $value->currency_code }}" {{!empty($school->default_currency_code) ? (old('default_currency_code', $school->default_currency_code) == $value->currency_code ? 'selected' : '') : (old('default_currency_code') == $value->currency_code ? 'selected' : '')}}
																		>  {{ $value->currency_code }}</option>
																@endforeach
															</select>
														</div>
												</div>
												<div class="col-sm-7">
														<label id="currency_alert_text"
																name="currency_alert_text">Wanring: Generate all pending invoices before change base currency.</label>
												</div>
										</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="address_caption">Subscription (Zero means unlimited)</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-6 col-sm-6 text-left" for="sstreet" id="street_caption">Maximum Number of Students:</label>
										<div class="col-sm-4">
											<input type="number" min="0" max="5000" class="form-control right" id="max_students" name="max_students"
											value="{{!empty($school->max_students) ? old('max_students', $school->max_students) : old('max_students')}}">
											
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-6 col-sm-6 text-left" for="sstreet" id="street_caption">Maximum number of teachers:</label>
										<div class="col-sm-4">
										<input type="number" min="0" max="5000" class="form-control" id="max_teachers" name="max_teachers"
										value="{{!empty($school->max_teachers) ? old('max_teachers', $school->max_teachers) : old('max_teachers')}}">
										</div>
									</div>
								</div>
							</div>
								
							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="contact_info_caption">Billing Method</label>
							</div>

							<div class="row">
									<div class="col-md-6">
											<div class="form-group row">
													<label id="billing_method_lbl" name="billing_method_lbl"
															for="billing_method_list"
															class="col-md-4 col-sm-4">Billing Method:</label>
													<div class="col-sm-4">
															<div class="selectdiv">
																<select class="form-control" 
																			id="billing_method_list"
																			name="billing_method">
																	<option value="">Select</option>
																	<option value="M" {{!empty($school->billing_method) ? (old('billing_method', $school->billing_method) == 'M' ? 'selected' : '') : (old('billing_method') == 'M' ? 'selected' : '')}}>{{ __('Monthly')}}</option>
																	<option value="Y" {{!empty($school->billing_method) ? (old('billing_method', $school->billing_method) == 'Y' ? 'selected' : '') : (old('billing_method') == 'Y' ? 'selected' : '')}}>{{ __('Yearly')}}</option>
																</select>
															</div>
													</div>
											</div>
									</div>
									<div class="col-md-6">
											<div id="monthly_job_day_div3" class="form-group row">
													<label id="monthly_job_day_label"
															name="monthly_job_day_label"
															class="col-lg-4 col-sm-4 text-left">Issue date (Confusion):</label>
													<div class="col-sm-4">
															<div class="selectdiv">
																<select class="form-control"
																			id="monthly_job_day"
																			name="monthly_job_day">
																			<option value="">Select</option>
																</select>
															</div>
													</div>
											</div>
									</div>
							</div>
							<div class="row" id="billing_method_amt_div"
									name="billing_method_amt_div">
									<div class="col-md-6">
											<div class="form-group row">
													<label for="billing_currency" id="row_hdr_amount"
															name="row_hdr_amount" class="col-md-4 col-sm-4">Amount</label>
													<div class="col-sm-4 col-xs-6">
															<div class="selectdiv">
																<select class="form-control"
																			id="billing_currency"
																			name="billing_currency">
																	<option value="">Select</option>
																	@foreach ($currency as $key => $value)
																		<option 
																		value="{{ $value->currency_code }}" {{!empty($school->billing_currency) ? (old('billing_currency', $school->billing_currency) == $value->currency_code ? 'selected' : '') : (old('billing_currency') == $value->currency_code ? 'selected' : '')}}
																		>  {{ $value->currency_code }}</option>
																	@endforeach
																</select>
															</div>
													</div>
													<div class="col-sm-4 col-xs-6">
															<input name="billing_amount"
																	id="billing_amount" class="form-control numeric"
																	type="text" data-force-required="true"
																	data-isrequired="true" maxlength="5" 
																	value="{{!empty($school->billing_amount) ? old('billing_amount', $school->billing_amount) : old('billing_amount')}}">
													</div>
											</div>
									</div>
									<div class="col-md-6">
											<div class="form-group row">
													<label id="billing_period_lbl" name="billing_period_lbl"
															class="col-md-2 col-sm-2">Period</label>
													<div class="col-sm-4 col-xs-4">
															<div class="input-group datetimepicker"
																	id="billing_date_start_div">
																	<input id="billing_date_start" name="billing_date_start" type="text" 
																	class="form-control date_picker"
																	value="{{!empty($school->billing_date_start) ? old('billing_date_start', $school->billing_date_start) : old('billing_date_start')}}">
																	<span class="input-group-addon">
																		<i class="fa fa-calendar"></i>
																	</span>
															</div>
													</div>
													<div class="col-sm-4 col-xs-4">
															<div class="input-group datetimepicker"
																	id="billing_date_end_div">
																	<input id="billing_date_end" name="billing_date_end" type="text" 
																	class="form-control date_picker"
																	value="{{!empty($school->billing_date_end) ? old('billing_date_end', $school->billing_date_end) : old('billing_date_end')}}">
																	
																	<span class="input-group-addon">
																		<i class="fa fa-calendar"></i>
																	</span>
															</div>
													</div>
											</div>
									</div>
							</div>
							<!-- End Billing Methid -->

							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="address_caption">Contact Person</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Genre:</label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control" name="contact_gender_id" id="contact_gender_id">
													<option value="">Select</option>
													<option value="1" {{!empty($school->contact_gender_id) ? (old('contact_gender_id', $school->contact_gender_id) == 1 ? 'selected' : '') : (old('contact_gender_id') == 1 ? 'selected' : '')}}>{{ __('Masculin')}}</option>
													<option value="0" {{!empty($school->contact_gender_id) ? (old('contact_gender_id', $school->contact_gender_id) == 2 ? 'selected' : '') : (old('contact_gender_id') == 2 ? 'selected' : '')}}>{{ __('Féminin')}}</option>
												</select>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">First Name :</label>
										<div class="col-sm-7">
											<input class="form-control" id="contact_firstname" name="contact_firstname" type="text"
											value="{{!empty($school->contact_firstname) ? old('contact_firstname', $school->contact_firstname) : old('contact_firstname')}}">
																	
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Family Name :</label>
										<div class="col-sm-7">
											<input class="form-control" id="contact_lastname" name="contact_lastname" type="text"
											value="{{!empty($school->contact_lastname) ? old('contact_lastname', $school->contact_lastname) : old('contact_lastname')}}">
													
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Position:</label>
										<div class="col-sm-7">
											<input class="form-control" id="contact_position" name="contact_position" type="text"
											value="{{!empty($school->contact_position) ? old('contact_position', $school->contact_position) : old('contact_position')}}">
												
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="address_caption">School Address</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Street:</label>
										<div class="col-sm-7">
											<input class="form-control" id="street" name="street" type="text"
											value="{{!empty($school->street) ? old('street', $school->street) : old('street')}}">
												
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Street No:</label>
										<div class="col-sm-7">
											<input class="form-control" id="street_number" name="street_number" type="text"
											value="{{!empty($school->street_number) ? old('street_number', $school->street_number) : old('street_number')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Street 2:</label>
										<div class="col-sm-7">
											<input class="form-control" id="street2" name="street2" type="text"
											value="{{!empty($school->street2) ? old('street2', $school->street2) : old('street2')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Postal Code:</label>
										<div class="col-sm-7">
											<input class="form-control" id="zip_code" name="zip_code" type="text"
											value="{{!empty($school->zip_code) ? old('zip_code', $school->zip_code) : old('zip_code')}}">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="place" id="place_caption">City:</label>
										<div class="col-sm-7">
											<input class="form-control" id="place" name="place" type="text"
											value="{{!empty($school->place) ? old('place', $school->place) : old('place')}}">
										
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="country_code" id="country_code_caption">Country:</label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control" name="country_code" id="country_code">
													<option value="">Select</option>
													@foreach ($country as $key => $value)
														<option 
														value="{{ $value->code }}" {{!empty($school->country_code) ? (old('country_code', $school->country_code) == $value->code ? 'selected' : '') : (old('country_code') == $value->code ? 'selected' : '')}}
														>  {{ $value->name }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="contact_info_caption">Contact information</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sphone" id="phone_caption">Phone:</label>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-phone-square"></i>
												</span> 
												<input class="form-control" id="phone" name="phone" type="text"
												value="{{!empty($school->phone) ? old('phone', $school->phone) : old('phone')}}">
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="btn-group col-lg-3 col-sm-3 text-left">
											<label>Phone 2</label> <label class="text-left"></label>
										</div>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-phone"></i>
												</span> 
												<input class="form-control" id="phone2" name="phone2" type="text"
												value="{{!empty($school->phone2) ? old('phone2', $school->phone2) : old('phone2')}}">
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="smobile" id="mobile_caption">Mobile:</label>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-mobile"></i>
												</span> 
												<input class="form-control" id="mobile" name="mobile" type="text"
												value="{{!empty($school->mobile) ? old('mobile', $school->mobile) : old('mobile')}}">
											</div>
										</div>
									</div>
								
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="semail" id="email_caption">Email:</label>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-envelope"></i>
												</span> 
												<input class="form-control" id="email" name="email" type="text"
												value="{{!empty($school->email) ? old('email', $school->email) : old('email')}}">
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="btn-group col-lg-3 col-sm-3 text-left">
											<label for="semail2">Email</label> <label class="text-left">(2)</label>
										</div>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-envelope"></i>
												</span> 
												<input class="form-control" id="email2" name="email2" type="text"
												value="{{!empty($school->email2) ? old('email2', $school->email2) : old('email2')}}">
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="contact_info_caption">School Bank Information</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Bank Name:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_name" name="bank_name" type="text"
												value="{{!empty($school->bank_name) ? old('bank_name', $school->bank_name) : old('bank_name')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Address:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_address" name="bank_address" type="text"
												value="{{!empty($school->bank_address) ? old('bank_address', $school->bank_address) : old('bank_address')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Postal Code:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_zipcode" name="bank_zipcode" type="text"
												value="{{!empty($school->bank_zipcode) ? old('bank_zipcode', $school->bank_zipcode) : old('bank_zipcode')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">City:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_place" name="bank_place" type="text"
											value="{{!empty($school->bank_place) ? old('bank_place', $school->bank_place) : old('bank_place')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Country:</label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control" name="bank_country_code" id="bank_country_code">
													<option value="">Select</option>
													@foreach ($country as $key => $value)
														<option 
														value="{{ $value->code }}" {{!empty($school->bank_country_code) ? (old('bank_country_code', $school->bank_country_code) == $value->code ? 'selected' : '') : (old('bank_country_code') == $value->code ? 'selected' : '')}}
														>  {{ $value->name }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Account Holder information:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_account_holder" name="bank_account_holder" type="text"
												value="{{!empty($school->bank_account_holder) ? old('bank_account_holder', $school->bank_account_holder) : old('bank_account_holder')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Account No:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_account" name="bank_account" type="text"
												value="{{!empty($school->bank_account) ? old('bank_account', $school->bank_account) : old('bank_account')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">IBAN No:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_iban" name="bank_iban" type="text"
												value="{{!empty($school->bank_iban) ? old('bank_iban', $school->bank_iban) : old('bank_iban')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">SWIFT A/c No:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_swift" name="bank_swift" type="text"
												value="{{!empty($school->bank_swift) ? old('bank_swift', $school->bank_swift) : old('bank_swift')}}">
										</div>
									</div>
								</div>
							</div>
							
							<div class="clearfix"></div>
							
						</fieldset>
					</form>
				</div>
				<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
					<form id="schoolUserForm" name="schoolUserForm" class="form-horizontal" role="form"
					 action="{{!empty($school) ? route('school.user_update',[$school->id]): '/'}}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="section_header_class">
							<label id="course_for_billing_caption">{{ __('User Account')}}</label>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Name of User')}}:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="username" name="username" value="{{!empty($AppUI['username']) ? old('username', $AppUI['username']) : old('username')}}" disabled="disabled">      
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Email')}}:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="email" name="email" value="{{!empty($AppUI['email']) ? old('email', $AppUI['email']) : old('email')}}">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Password')}}:</label>
							<div class="col-sm-7">
								<input type="password" type="text" class="form-control" id="password" name="password" value="">
                      
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Status:</label>
							<div class="col-sm-7">
								<div class="selectdiv">
									<select class="form-control" name="is_active" id="is_active">
										<option value="">Select</option>
										<option value="1" {{!empty($data) ? (old('is_active', $data->is_active) == 1 ? 'selected' : '') : (old('is_active') == 1 ? 'selected' : '')}}>{{ __('Active')}}</option>
										<option value="0" {{!empty($data) ? (old('is_active', $data->is_active) == 0 ? 'selected' : '') : (old('is_active') == 0 ? 'selected' : '')}}>{{ __('Inactive')}}</option>
									</select>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="section_header_class">
							<label id="course_for_billing_caption">Send Activation Email</label>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">TO:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="email_to_id" name="email_to_id" value="{{!empty($AppUI['email']) ? old('email_to_id', $AppUI['email']) : old('email_to_id')}}">
							
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Subject:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="email_subject_id" name="subject_text" value="{{!empty($emailTemplate->subject_text) ? old('subject_text', $emailTemplate->subject_text) : old('subject_text')}}">
							
							</div>
						</div>
						<div class="row">
							<div class="col-lg-10 col-md-10">
								<div class="email_template_tbl table-responsive mt-1">
									<table id="email_template_tbl" name="email_template_tbl" width="100%" border="0" class="email_template school resizable">
										<tbody>
											<tr align="left" valign="middle">
												<td>
													<div class="form-group-data">
														<textarea rows="30" name="body_text" id="body_text" type="textarea" class="form-control my_ckeditor textarea">
														{{!empty($emailTemplate->body_text) ? old('body_text', $emailTemplate->body_text) : old('body_text')}}
														</textarea>
														<span id="body_text_error" class="error"></span>
														<span class="pull-right">
															<div class="text-center">
															<a id="send_email_btn" name="send_email_btn" href="#" class="btn btn-sm btn-info">Send Email</a>
															<!-- <button id="send_email_btn" name="send_email_btn" class="btn btn-sm btn-info" ><em class="glyphicon glyphicon-send"></em> envoyer </button> -->
															</div>
														</span>
													</div>
													
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		
	</div>

	<!-- End Tabs content -->
@endsection


@section('footer_js')
<script type="text/javascript">
$(function() {
	$(".date_picker").datetimepicker({
		format: "yyyy/mm/dd",
		autoclose: true,
		todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});

	CKEDITOR.replace( "body_text", {
		customConfig: '/ckeditor/config_email.js',
		height: 300
		,extraPlugins: 'Cy-GistInsert'
		,extraPlugins: 'AppFields'
	});
});

$(document).ready(function(){
	PopulateProcessingDays();
	$('#billing_method_list').on('change', function () {
		var value = $(this).val();

		if (value == 'M') {
				document.getElementById("monthly_job_day_div3").style.visibility = "visible";
		} else {
				document.getElementById("monthly_job_day_div3").style.visibility = "hidden";
		}
	});

	$('#update_btn').click(function (e) {
		var x = document.getElementsByClassName("tab-pane active");
		var schoolForm = document.getElementById("schoolForm");
		var schoolUserForm = document.getElementById("schoolUserForm");

		if (x[0].id == "tab_1") {
			
			if (validateSchoolForm()) {
				schoolForm.submit();
				return false;
			} else {
				e.preventDefault(e); 
			}
			
		} 
		else if (x[0].id == "tab_2") {
			schoolUserForm.submit();
		} 
		//else if (x[0].id == "tab_3") {
		// 		SaveOpeningHour();
		// } else if (x[0].id == "tab_4") {

		// } else if (x[0].id == "tab_5") {
		// 		save_update_user_account();
		// } else if (x[0].id == "student_disc") {
		// 		update_student_disc_perc();
		// } else if (x[0].id == "event_category") {
		// 		save_event_category();
		// 		//successModalCall('in progress....');
		// }
	});
})

function PopulateProcessingDays() {
	var resultHtml = "", mday = 1;
	while (mday <= 31) {
			resultHtml += '<option value="' + mday + '">' + mday + '</option>';
			mday++;
	}
	$('#monthly_job_day').html(resultHtml);
}

function validateSchoolForm() {

	//var p_school_code = document.getElementById("school_code").value;
	let error = false;

	// if (p_school_code.trim() == '') {
	// 	errorModalCall("Code d'école invalide.");
	// 	error = true;
	// }
	//added for max students and teachers
	var max_students = document.getElementById("max_students").value;
	var max_teachers = document.getElementById("max_teachers").value;

	if (max_students.trim() == 0) {
			//alert("nombre max invalide");
			errorModalCall('max_students required');
			document.getElementById("max_students").focus();
			error = true;
	}
	if (max_teachers.trim() == 0) {
			//alert("nombre max invalide");	
			errorModalCall('max_teachers required');
			document.getElementById("max_teachers").focus();
			error = true;
	}
	if (error) {
		return false;
	}            			
	else
	{
		return true;
	}
}

  
</script>
@endsection