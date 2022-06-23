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
							<label id="page_header" name="page_header">{{ __('School Key Information')}}</label>
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
					<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
						{{ __('Contact Information')}}
					</button>
					<button class="nav-link" id="nav-logo-tab" data-bs-toggle="tab" data-bs-target="#tab_4" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
					{{ __('Logo')}}
					</button>
					<!-- @can('schools-user-udpate')
						<button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">
						{{ __('User Account')}}
						</button>
					@endcan -->
					@can('parameters-list')
						<a class="nav-link" id="nav-parameters-tab" data-bs-toggle="tab" data-bs-target="#tab_5" type="button" role="tab" aria-controls="nav-parameters" aria-selected="false" href="{{ auth()->user()->isSuperAdmin() ? route('admin_event_category.index',['school'=> $school->id]) : route('event_category.index') }}">{{ __('Parameters')}}</a>
					@endcan
					<!-- </button> -->
				</div>
			</nav>
			<!-- Tabs navs -->

			<!-- Tabs content -->
			<div class="tab-content" id="ex1-content">
				<input type="hidden" id="role_type" name="role_type" value="{{$role_type}}">
				<input type="hidden" id="school_id" name="school_id" value="{{$school->id}}">
				<!--Start of Tab 1 -->
				<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
					<form id="schoolForm" name="schoolForm" class="form-horizontal" role="form"
					 action="{{!empty($school) ? route('school.update',[$school->id]): '/'}}" method="POST" enctype="multipart/form-data">
						@csrf
						<fieldset>
							<div class="section_header_class">
								<label id="teacher_personal_data_caption">{{ __('Personal data of the school')}}</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{ __('Status')}}: </label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control" name="is_active" id="is_active">
													<option value="">Select</option>
													<option value="1" {{!empty($school) ? (old('is_active', $school->is_active) == 1 ? 'selected' : '') : (old('is_active') == 1 ? 'selected' : '')}}>{{ __('Active')}}</option>
													<option value="0" {{!empty($school) ? (old('is_active', $school->is_active) == 0 ? 'selected' : '') : (old('is_active') == 0 ? 'selected' : '')}}>{{ __('Inactive')}}</option>
												</select>
												@if ($errors->has('is_active'))
													<span id="is_active_error" class="error">
															<strong>{{ $errors->first('is_active') }}.</strong>
													</span>
												@endif
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
												class="col-lg-3 col-sm-3 text-left">{{ __('Name of the School')}}*:</label>
										<div class="col-sm-7">
												<input type="text" class="form-control" id="school_name"
														name="school_name"
														value="{{!empty($school->school_name) ? old('school_name', $school->school_name) : old('school_name')}}">
												@if ($errors->has('school_name'))
													<span id="" class="error">
															<strong>{{ $errors->first('school_name') }}.</strong>
													</span>
												@endif
										</div>
										
									</div>
									<div class="form-group row">
										<label id="organization_type_caption"
										class="col-lg-3 col-sm-3 text-left">{{ __('Organization Type')}}:</label>
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
											{{ __('Account Type')}}:
										</label>
										<label id="school_type" class="col-lg-3 col-sm-3 text-left">
											School
										</label>
									</div>
									<div class="form-group row">
										<label id="sender_email_label" name="sender_email_label" 
										class="col-lg-3 col-sm-3 text-left">{{ __('Sender email address')}} :</label>
										<div class="col-sm-7">
												<input type="email" id="sender_email"
												name="sender_email" size="100" class="form-control" 
												value="{{!empty($school->sender_email) ? old('sender_email', $school->sender_email) : old('sender_email')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" id="birth_date_label_id">{{ __('Incorporation Date')}}:</label>
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
								<div class="col-xs-12">
										<div class="form-group alert alert-info row">
												<label for="default_currency_code" id="default_currency_lbl"
														name="default_currency_lbl"
														class="col-lg-3 col-sm-3 text-end">{{ __('Base currency')}}</label>
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
																name="currency_alert_text">{{ __('Wanring: Generate all pending invoices before change base currency')}}.</label>
												</div>
										</div>
								</div>
							</div>
							<div class="clearfix"></div>
							@role('superadmin')
							<div class="section_header_class">
								<label id="address_caption">{{ __('Subscription (Zero means unlimited)')}}</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-6 col-sm-6 text-left" for="sstreet" id="street_caption">{{ __('Maximum Number of Students')}}:</label>
										<div class="col-sm-4">
											<input type="number" min="0" max="5000" class="form-control right" id="max_students" name="max_students"
											value="{{!empty($school->max_students) ? old('max_students', $school->max_students) : old('max_students')}}">
											
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-6 col-sm-6 text-left" for="sstreet" id="street_caption">{{ __('Maximum number of teachers')}}:</label>
										<div class="col-sm-4">
										<input type="number" min="0" max="5000" class="form-control" id="max_teachers" name="max_teachers"
										value="{{!empty($school->max_teachers) ? old('max_teachers', $school->max_teachers) : old('max_teachers')}}">
										</div>
									</div>
								</div>
							</div>
							@endrole	
							@unlessrole('superadmin')
						<!-- 	<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="contact_info_caption">{{ __('Billing Method')}}</label>
							</div>

							<div class="row">
									<div class="col-md-6">
											<div class="form-group row">
													<label id="billing_method_lbl" name="billing_method_lbl"
															for="billing_method_list"
															class="col-md-4 col-sm-4">{{ __('Billing Method')}}:</label>
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
															class="col-lg-4 col-sm-4 text-left">{{ __('Issue date')}}:</label>
													<div class="col-sm-4">
															<div class="selectdiv">
																<select class="form-control"
																			id="monthly_job_day"
																			name="monthly_job_day">
																			<option value="">Select</option>
																			@php
																			for ($loop=1; $loop <= 31 ; $loop++) {
																				@endphp
																				<option value="{{ $loop }}" {{ (old('monthly_job_day') ? old('monthly_job_day') : $monthly_issue ?? '') == $loop ? 'selected' : '' }}>{{ $loop }}</option>
																				@php
																			}
																			@endphp
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
															name="row_hdr_amount" class="col-md-4 col-sm-4">{{ __('Amount')}}</label>
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
															class="col-md-2 col-sm-2">{{ __('Period')}}</label>
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
							</div> -->
							<!-- End Billing Methid -->
							@endunlessrole

							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="address_caption">{{ __('Contact Person')}}</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Genre')}}:</label>
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
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('First Name')}} :*</label>
										<div class="col-sm-7">
											<input class="form-control" id="contact_firstname" name="contact_firstname" type="text"
											value="{{!empty($school->contact_firstname) ? old('contact_firstname', $school->contact_firstname) : old('contact_firstname')}}">
											@if ($errors->has('contact_firstname'))
												<span id="" class="error">
														<strong>{{ $errors->first('contact_firstname') }}.</strong>
												</span>
											@endif					
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Family Name')}} :*</label>
										<div class="col-sm-7">
											<input class="form-control" id="contact_lastname" name="contact_lastname" type="text"
											value="{{!empty($school->contact_lastname) ? old('contact_lastname', $school->contact_lastname) : old('contact_lastname')}}">
											@if ($errors->has('contact_lastname'))
												<span id="" class="error">
														<strong>{{ $errors->first('contact_lastname') }}.</strong>
												</span>
											@endif		
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Position')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="contact_position" name="contact_position" type="text"
											value="{{!empty($school->contact_position) ? old('contact_position', $school->contact_position) : old('contact_position')}}">
												
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="address_caption">{{ __('School Address')}}</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Street')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="street" name="street" type="text"
											value="{{!empty($school->street) ? old('street', $school->street) : old('street')}}">
												
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Street No')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="street_number" name="street_number" type="text"
											value="{{!empty($school->street_number) ? old('street_number', $school->street_number) : old('street_number')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Street 2')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="street2" name="street2" type="text"
											value="{{!empty($school->street2) ? old('street2', $school->street2) : old('street2')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Postal Code')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="zip_code" name="zip_code" type="text"
											value="{{!empty($school->zip_code) ? old('zip_code', $school->zip_code) : old('zip_code')}}">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="place" id="place_caption">{{ __('City')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="place" name="place" type="text"
											value="{{!empty($school->place) ? old('place', $school->place) : old('place')}}">
										
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="country_code" id="country_code_caption">{{ __('Country')}}:</label>
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
								<label id="contact_info_caption">{{ __('Contact Information')}}</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sphone" id="phone_caption">{{ __('Phone')}}:</label>
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
											<label>{{ __('Phone 2')}}</label> <label class="text-left"></label>
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
										<label class="col-lg-3 col-sm-3 text-left" for="smobile" id="mobile_caption">{{ __('Mobile')}}:</label>
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
										<label class="col-lg-3 col-sm-3 text-left" for="semail" id="email_caption">{{ __('Email')}}:</label>
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
											<label for="semail2">{{ __('Email')}}</label> <label class="text-left">(2)</label>
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
							@if($AppUI->isTeacherAdmin() || $school->country_code != 'CA' )
							<div class="section_header_class">
								<label id="contact_info_caption">{{ __('School Bank Information')}}</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Bank Name')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_name" name="bank_name" type="text"
												value="{{!empty($school->bank_name) ? old('bank_name', $school->bank_name) : old('bank_name')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Address')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_address" name="bank_address" type="text"
												value="{{!empty($school->bank_address) ? old('bank_address', $school->bank_address) : old('bank_address')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Postal Code')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_zipcode" name="bank_zipcode" type="text"
												value="{{!empty($school->bank_zipcode) ? old('bank_zipcode', $school->bank_zipcode) : old('bank_zipcode')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('City')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_place" name="bank_place" type="text"
											value="{{!empty($school->bank_place) ? old('bank_place', $school->bank_place) : old('bank_place')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Country')}}:</label>
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
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Account Holder information')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_account_holder" name="bank_account_holder" type="text"
												value="{{!empty($school->bank_account_holder) ? old('bank_account_holder', $school->bank_account_holder) : old('bank_account_holder')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Account No')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_account" name="bank_account" type="text"
												value="{{!empty($school->bank_account) ? old('bank_account', $school->bank_account) : old('bank_account')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('IBAN No')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_iban" name="bank_iban" type="text"
												value="{{!empty($school->bank_iban) ? old('bank_iban', $school->bank_iban) : old('bank_iban')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('SWIFT A/c No')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_swift" name="bank_swift" type="text"
												value="{{!empty($school->bank_swift) ? old('bank_swift', $school->bank_swift) : old('bank_swift')}}">
										</div>
									</div>
								</div>
							</div>
							@else
							<div class="section_header_class">
								<label id="contact_info_caption">{{ __('School Bank Information')}}</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="etransfer_acc_div">{{ __('E-transfer email')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="etransfer_acc" name="etransfer_acc" type="text"
												value="{{!empty($school->etransfer_acc) ? old('etransfer_acc', $school->etransfer_acc) : old('etransfer_acc')}}">
												<span class="etransfer_acc"></span>	
										</div>
									</div>
								</div>	
							</div>
							@endif	
							
							<div class="clearfix"></div>
							
						</fieldset>
					</form>
				</div>
				<!--End of Tab 1-->

				<!--Start of Tab 2 -->
				<!-- <div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
					<form id="schoolUserForm" name="schoolUserForm" class="form-horizontal" role="form"
					 action="{{!empty($school) ? route('school.user_update',[$school->id]): '/'}}" method="POST" enctype="multipart/form-data">
						@csrf
						<input type="hidden" id="user_id" name="user_id" value="{{!empty($school_admin->id) ? old('user_id', $school_admin->id) : old('user_id')}}">
						<div class="section_header_class">
							<label id="course_for_billing_caption">{{ __('User Account')}}</label>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Name of User')}}:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="admin_username" name="admin_username" value="{{!empty($school_admin->username) ? old('admin_username', $school_admin->username) : old('admin_username')}}" disabled="disabled">      
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Email')}}:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="admin_email" name="admin_email" value="{{!empty($school_admin->email) ? old('admin_email', $school_admin->email) : old('admin_email')}}">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Password')}}:</label>
							<div class="col-sm-7">
								<input type="password" type="text" class="form-control" id="admin_password" name="admin_password" value="">
                      
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Status')}}:</label>
							<div class="col-sm-7">
								<div class="selectdiv">
									<select class="form-control" name="admin_is_active" id="admin_is_active">
										<option value="">Select</option>
										<option value="1" {{!empty($school_admin->is_active) ? (old('admin_is_active', $school_admin->is_active) == 1 ? 'selected' : '') : (old('admin_is_active') == 1 ? 'selected' : '')}}>{{ __('Active')}}</option>
										<option value="0" {{!empty($school_admin->is_active) ? (old('admin_is_active', $school_admin->is_active) == 0 ? 'selected' : '') : (old('admin_is_active') == 0 ? 'selected' : '')}}>{{ __('Inactive')}}</option>
									</select>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="section_header_class">
							<label id="course_for_billing_caption">{{ __('Send Activation Email')}}</label>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('TO')}}:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="email_to_id" name="email_to_id" value="{{!empty($school_admin->email) ? $school_admin->email : old('email_to_id')}}">
							
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Subject')}}:</label>
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
															<a id="send_email_btn" name="send_email_btn" href="#" class="btn btn-sm btn-info">{{ __('Send Email')}}</a>
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
				</div> -->
				<!--End of Tab 2-->

				<!--Start of Tab 4 -->
				<div id="tab_4" class="tab-pane">
					<div class="row">
						<div class="col-sm-12 col-xs-12 header-area">
							<div class="page_header_class">
								<label id="page_header" class="page_title text-black">{{ __('Logo')}}</label>
							</div>
						</div>
					
						<div class="col-md-6">
							<form enctype="multipart/form-data" role="form" id="form_images" class="form-horizontal" method="post" action="#">
								<div class="form-group row">
									<div class="col-sm-8">
										<fieldset>
											<div class="profile-image-cropper responsive">
											<?php if (!empty($school->logoImage->path_name)): ?>
												<img id="profile_image_user_account" src="{{ $school->logoImage->path_name }}"
														height="128" width="128" class="img-circle"
														style="margin-right:10px;">
											<?php else: ?>
												<img id="profile_image_user_account" src="{{ asset('img/photo_blank.jpg') }}"
														height="128" width="128" class="img-circle"
														style="margin-right:10px;">
											<?php endif; ?>

												
												<div style="display:flex;flex-direction: column;">
													<div style="margin:5px;">
														<span class="btn btn-theme-success">
															<i class="fa fa-picture-o"></i>
															<span id="select_image_button_caption" onclick="UploadImage()">{{ __('Choose an image ...')}}</span>
															<input onchange="ChangeImage()"
																	class="custom-file-input" id="profile_image_file"
																	type="file" name="profile_image_file"
																	accept="image/*" style="display: none;">
														</span>
													</div>
													<?php //if (!empty($AppUI->profile_image_id)): ?>
														<div style="margin:5px;">
															<a id="delete_profile_image" name="delete_profile_image" class="btn btn-theme-warn" style="{{!empty($school->logo_image_id) ? '' : 'display:none;'}}">
																<i class="fa fa-trash"></i>
																<span id="delete_image_button_caption">{{ __('Remove Image')}}</span>
															</a>
														</div>
													<?php //endif; ?>
												</div>
											</div>
										</fieldset>
									</div>
								</div>
							</form>
						</div>
					</div>

				</div>
				<!--End of Tab 4-->

				<!--Start of Tab 5 -->
				<div id="tab_5" class="tab-pane">
					<div class="row">
						<header class="panel-heading" style="border: none;">
							<div class="row panel-row" style="margin:0;">
								<div class="col-sm-6 col-xs-12 header-area">
									<div class="page_header_class">
										<label id="page_header" name="page_header">Parameters</label>
									</div>
								</div>
								<div class="col-sm-6 col-xs-12 btn-area">
									<div class="float-end btn-group">
										<a style="display: none;" id="delete_btn" href="#" class="btn btn-theme-warn"><em class="glyphicon glyphicon-trash"></em> Delete</a>

									@can('parameters-create-udpate')
										<button id="save_btn" name="save_btn" class="btn btn-success save_button"><em class="glyphicon glyphicon-floppy-save"></em> Save Parameters</button>
									@endcan
									</div>
								</div>    
							</div>          
						</header>

						<!-- Tabs navs -->
						<nav>
							<div class="nav nav-tabs" id="nav-tab" role="tablist">
								<a class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab_inner_part1" type="button" role="tab" aria-controls="nav-tab_inner_part1" aria-selected="false" href="{{ auth()->user()->isSuperAdmin() ? route('admin_event_category.index',['school'=> $schoolId]) : route('event_category.index') }}">{{ __('Event Category') }}</a>
								<a class="nav-link" data-bs-toggle="tab" data-bs-target="#tab_inner_part2" type="button" role="tab" aria-controls="nav-tab_inner_part2" aria-selected="false"  href="{{ auth()->user()->isSuperAdmin() ? route('admin_event_location.index',['school'=> $schoolId]) : route('event_location.index') }}">{{ __('Locations') }}</a>
								<a class="nav-link" data-bs-toggle="tab" data-bs-target="#tab_inner_part3" type="button" role="tab" aria-controls="nav-tab_inner_part3" aria-selected="false"  href="{{ auth()->user()->isSuperAdmin() ? route('admin_event_level.index',['school'=> $schoolId]) : route('event_level.index') }}">{{ __('Level') }}</a>
							</div>
						</nav>
						<!-- Tabs navs -->
						<!-- Tabs content -->
						<form role="form" id="location_form" class="form-horizontal" method="post" action="{{route('event_location.create')}}">
							<div class="tab-content" id="tab_inner_part">
								<div id="tab_inner_part1" class="tab_inner tab-pane fade show active">
									<div class="tab-pane fade show active" id="tab_category" role="tabpanel" aria-labelledby="tab_category">
										<form role="form" id="event_form" class="form-horizontal" method="post" action="{{route('event_category.create')}}">
											@csrf
											<div class="section_header_class row">
												<div class="col-md-3 col-5">
													<label>{{ __('Category Name') }}</label>
												</div>
												<div class="col-md-3 col-6">
													<label class="invoice_type_label">{{ __('Invoice Type') }}</label>
												</div>
												<div class="col-md-2 col-1">
													<label></label>
												</div>
											</div>
											<div class="row">
												<div id="add_more_event_category_div" class="col-md-8">
												@php $count= isset($eventLastCatId->id) ? ($eventLastCatId->id) : 1; @endphp
													@foreach($eventCat as $cat)
														<div class="col-md-12 add_more_event_category_row row">
															<div class="col-md-5 col-5">
																<div class="form-group row">
																	<div class="col-sm-11">
																		<input type="hidden" name="category[{{$count}}][id]" value="<?= $cat->id; ?>">
																		<input class="form-control category_name" name="category[{{$count}}][name]" placeholder="{{ __('Category Name') }}" value="<?= $cat->title; ?>" type="text">
																	</div>
																</div>
															</div>
															@if(!$AppUI->isTeacher())
															<div class="col-md-5 col-6">
																<div class="form-group row invoice_part">
																	<div class="col-sm-6">
																		<input type="radio" name="category[{{$count}}][invoice]" value="S" <?php if($cat->invoiced_type == 'S'){ echo 'checked'; }  ?>> <label> {{ __('School Invoiced') }}</label>
																	</div>
																	<div class="col-sm-6">
																		<input type="radio" name="category[{{$count}}][invoice]" value="T" <?php if($cat->invoiced_type == 'T'){ echo 'checked'; }  ?>> <label> {{ __('Teacher Invoiced') }}</label>
																	</div>
																</div>
															</div>
															@endif
															<div class="col-md-2 col-1">
																@can('parameters-delete')
																<div class="form-group row">
																	<div class="col-sm-5">
																		<button type="button" class="btn btn-theme-warn delete_event" data-category_id="<?= $cat->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
																	</div>
																</div>
																@endcan
															</div>
														</div>
													@php $count++; endforeach @endphp
												</div>
												<div class="col-md-2">
												@can('parameters-create-udpate')
													<button id="add_more_event_category_btn" data-last_id="{{$count}}" type="button" class="btn btn-success save_button"><i class="fa fa-plus" aria-hidden="true"></i>Add Another Category</button>
												@endcan
												</div>
											</div>
										</form>	
									</div>
								</div>
								<!-- End Tabs content -->
								<!-- Tabs content -->
								<div id="tab_inner_part2" class="tab_inner tab-pane tab-content">
									<div class="tab-pane fade show active" id="tab_location" role="tabpanel" aria-labelledby="tab_location">
										
											<input type="hidden" name="school_id" value="3">
											@csrf
											<div class="section_header_class row">
												<div class="col-md-3 col-9">
													<label>{{ __('Location Name') }}</label>
												</div>
												<div class="col-md-2 col-2">
													<label></label>
												</div>
											</div>
											<div class="row">
												<div id="add_more_location_div" class="col-md-8">
													@php $count= isset($eventLastLocaId->id) ? ($eventLastLocaId->id) : 1; @endphp
													@foreach($locations as $loca)
														<div class="col-md-12 add_more_location_row row">
															<div class="col-md-5 col-9">
																<div class="form-group row">
																	<div class="col-sm-11">
																		<input type="hidden" name="location[{{$count}}][id]" value="<?= $loca->id; ?>">
																		<input class="form-control location_name" name="location[{{$count}}][name]" placeholder="{{ __('Location Name') }}" value="<?= $loca->title; ?>" type="text">
																	</div>
																</div>
															</div>
															<div class="offset-1 col-2">
																@can('parameters-delete')
																<div class="form-group row">
																	<div class="col-sm-5">
																		<button type="button" class="btn btn-theme-warn delete_location" data-location_id="<?= $loca->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
																	</div>
																</div>
																@endcan
															</div>
														</div>
													@php $count++; endforeach @endphp
												</div>
												<div class="col-md-2">
												@can('parameters-create-udpate')
													<button id="add_more_location_btn" data-last_id="{{$count}}" type="button" class="btn btn-success save_button"><i class="fa fa-plus" aria-hidden="true"></i>{{ __('Add Another Location') }}</button>
												@endcan
												</div>
											</div>
										
									</div>
								</div>
								<!-- End Tabs content -->
								<!-- Tabs content -->
								<div id="tab_inner_part3" class="tab_inner tab-pane tab-content">
									<div class="tab-pane fade show active" id="tab_level" role="tabpanel" aria-labelledby="tab_level">
										<form role="form" id="level_form" class="form-horizontal" method="post" action="#">
											<input type="hidden" name="school_id" value="3">
											@csrf
											<div class="section_header_class row">
												<div class="col-md-3 col-9">
													<label>{{ __('Level Name') }}</label>
												</div>
												<div class="col-md-2 col-2">
													<label></label>
												</div>
											</div>
											<div class="row">
												<div id="add_more_level_div" class="col-md-8">
												@php $count= isset($eventLastLevelId->id) ? ($eventLastLevelId->id) : 1; @endphp
												@foreach($levels as $lvl)
														<div class="col-md-12 add_more_level_row row">
															<div class="col-md-5 col-9">
																<div class="form-group row">
																	<div class="col-sm-11">
																		<input type="hidden" name="level[{{$count}}][id]" value="<?= $lvl->id; ?>">
																		<input class="form-control level_name" name="level[{{$count}}][name]" placeholder="{{ __('Level Name') }}" value="<?= $lvl->title; ?>" type="text">
																	</div>
																</div>
															</div>
															<div class="col-md-2 offset-1 col-2">
																@can('parameters-delete')
																<div class="form-group row">
																	<div class="col-sm-5">
																		<button type="button" class="btn btn-theme-warn delete_level" data-level_id="{{ $lvl->id; }}"><i class="fa fa-trash" aria-hidden="true"></i></button>
																	</div>
																</div>
																@endcan
															</div>
														</div>
													@php $count++; endforeach @endphp
												</div>
												<div class="col-md-2">
												@can('parameters-create-udpate')
													<button id="add_more_level_btn" type="button" data-last_id="{{$count}}"  class="btn btn-success save_button"><i class="fa fa-plus" aria-hidden="true"></i>{{ __('Add Another Level') }}</button>
												@endcan
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</form>
						<!-- End Tabs content -->
					</div>

				</div>
				<!--End of Tab 5-->
			</div>
		
	</div>
	<!-- success modal-->
	<div class="modal modal_parameter" id="modal_parameter">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<p id="modal_alert_body"></p>
				</div>
				<div class="modal-footer">
					<button type="button" id="modalClose" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Ok') }}</button>
				</div>
			</div>
		</div>
	</div>
	<!-- End Tabs content -->
@endsection


@section('footer_js')
<script type="text/javascript">


$(document).ready(function(){
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

	// CKEDITOR.replace( "body_text", {
	// 	customConfig: '/ckeditor/config_email.js',
	// 	height: 300
	// 	,extraPlugins: 'Cy-GistInsert'
	// 	,extraPlugins: 'AppFields'
	// });

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
	});

	$('#delete_profile_image').click(function (e) {
		DeleteProfileImage();      // refresh lesson details for billing
	})



	$("#send_email_btn").click(function (e) {
		var user_id = $("#user_id").val();
		var email_to = $("#email_to_id").val(),
				school_name = $("#school_name").val(),
				email_body  = CKEDITOR.instances["body_text"].getData()
		email_body = email_body.replace(/'/g, "''");
		email_body = email_body.replace(/&/g, "<<~>>");
		let loader = $('#pageloader');
    	loader.show();

		var schoolUserForm = document.getElementById("schoolUserForm");
		var formdata = $("#schoolUserForm").serializeArray();
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';

		
		formdata.push({
				"name": "_token",
				"value": csrfToken
		});
		formdata.push({
				"name": "user_id",
				"value": user_id
		});
		formdata.push({
				"name": "email_body",
				"value": email_body
		});
		formdata.push({
				"name": "school_name",
				"value": school_name
		});
		//console.log(formdata);

		$.ajax({
				url: BASE_URL + '/school_email_send',
				data: formdata,
				type: 'POST',
				dataType: 'json',
				async: false,
				encode: true,
				success: function(data) {
					loader.hide();
					if (data.status) {
							successModalCall("{{ __('email_sent')}}");
					} else {
							errorModalCall(data.msg);
					}

				}, // sucess
				error: function(ts) {
					loader.hide();
					errorModalCall('error_message_text');
				}
		});
	
	});    //contact us button click 

	$(document).on('click','#add_more_event_category_btn',function(){
		var lst_id = $(this).attr('data-last_id');
		var incre = (parseInt(lst_id)+1);
		$(this).attr('data-last_id',incre);

		var resultHtml = `<div class="col-md-12 add_more_event_category_row row">
			<div class="col-md-5 col-5">
				<div class="form-group row">
					<div class="col-sm-11">
						<input class="form-control category_name" name="category[`+lst_id+`][name]" placeholder="Category Name" type="text">
					</div>
				</div>
			</div>
			@if(!$AppUI->isTeacher())
			<div class="col-md-5 col-6">
				<div class="form-group row invoice_part">
					<div class="col-sm-6">
						<input name="category[`+lst_id+`][invoice]" type="radio" value="S" checked> <label> School Invoiced</label>
					</div>
					<div class="col-sm-6">
						<input name="category[`+lst_id+`][invoice]" type="radio" value="T"> <label> Teacher Invoiced </label>
					</div>
				</div>
			</div>
			@endif
			<div class="col-md-2 col-1">
				<div class="form-group row">
					<div class="col-sm-5">
						<button type="button" class="btn btn-theme-warn delete_event" data-r_id="`+lst_id+`"><i class="fa fa-trash" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>`;

		$("#add_more_event_category_div").append(resultHtml);
	})
	$(document).on('click','.delete_event',function(){
		var lst_id = $(this).attr('data-r_id');
		var incre = parseInt(lst_id);
		$(this).attr('data-last_id',incre);
		if (!confirm('{{ __("Are you want to delete?") }}')) return

		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
		var id = $(this).data('category_id');
		var current_obj = $(this);
		if(id){
			$.ajax({
				url: BASE_URL + '/remove-event-category/'+id,
				type: 'DELETE',
				dataType: 'json',
				data: {
					"id": id,
					"_token": csrfToken,
				},
				success: function(response){	
					if(response.status == 1){
						current_obj.parents('.add_more_event_category_row').remove();
					}
				}
			})
		}else{
			current_obj.parents('.add_more_event_category_row').remove();
		}
	});


	// level part
	$(document).on('click','#add_more_level_btn',function(){
		var lst_id = $(this).attr('data-last_id');
		var incre = (parseInt(lst_id)+1);
		$(this).attr('data-last_id',incre);

		var resultHtml = `<div class="col-md-12 add_more_level_row row">
			<div class="col-md-5 col-9">
				<div class="form-group row">
					<div class="col-sm-11">
						<input class="form-control level_name" name="level[`+lst_id+`][name]" placeholder="Level Name" type="text">
					</div>
				</div>
			</div>
			<div class="col-md-2 offset-1 col-2">
				<div class="form-group row">
					<div class="col-sm-5">
						<button type="button" class="btn btn-theme-warn delete_level"><i class="fa fa-trash" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>`;

		$("#add_more_level_div").append(resultHtml);
	})
	
	$(document).on('click','.delete_level',function(){
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
		var id = $(this).data('level_id');
		var current_obj = $(this);
		var lst_id = $(this).attr('data-r_id');
		var incre = parseInt(lst_id);
		$(this).attr('data-last_id',incre);

		if (!confirm('{{ __("Are you want to delete?") }}')) return

		if(id){
			$.ajax({
				url: BASE_URL + '/remove-event-level/'+id,
				type: 'DELETE',
				dataType: 'json',
				data: {
					"id": id,
					"_token": csrfToken,
				},
				success: function(response){	
					if(response.status == 1){
						current_obj.parents('.add_more_level_row').remove();
					}
				}
			})
		}else{
			current_obj.parents('.add_more_level_row').remove();
		}	
	});


	// location part
	$(document).on('click','#add_more_location_btn',function(){
		var lst_id = $(this).attr('data-last_id');
		var incre = (parseInt(lst_id)+1);
		$(this).attr('data-last_id',incre);

		var resultHtml = `<div class="col-md-12 add_more_location_row row">
			<div class="col-md-5 col-9">
				<div class="form-group row">
					<div class="col-sm-11">
						<input class="form-control location_name" name="location[`+lst_id+`][name]" placeholder="location name" type="text">
					</div>
				</div>
			</div>
			<div class="col-md-2 offset-1 col-2">
				<div class="form-group row">
					<div class="col-sm-5">
						<button type="button" class="btn btn-theme-warn delete_location"><i class="fa fa-trash" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>`;

		$("#add_more_location_div").append(resultHtml);
	})
	
	$(document).on('click','.delete_location',function(){
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
		var id = $(this).data('location_id');
		var current_obj = $(this);
		var lst_id = $(this).attr('data-r_id');
		var incre = parseInt(lst_id);
		$(this).attr('data-last_id',incre);

		if (!confirm('{{ __("Are you want to delete?") }}')) return

		if(id){
			$.ajax({
				url: BASE_URL + '/remove-event-location/'+id,
				type: 'DELETE',
				dataType: 'json',
				data: {
					"id": id,
					"_token": csrfToken,
				},
				success: function(response){	
					if(response.status == 1){
						current_obj.parents('.add_more_location_row').remove();
					}
				}
			})
		}else{
			current_obj.parents('.add_more_location_row').remove();
		}
		
	});


	// save functionality
	$('#save_btn').click(function (e) {		

		// var x = document.getElementsByClassName("tab-pane active");
		// var studentForm = document.getElementById("add_student");
		// var studentUserForm = document.getElementById("studentUserForm");
		// if (x[0].id == "tab_3") {
		// 	studentUserForm.submit();
		// } else{
		// 	studentForm.submit();
		// } 


		var formData = $('#location_form').serializeArray();
		// var eventFormData = $('#event_form').serializeArray();
		// var levelFormData = $('#level_form').serializeArray();
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
		
		var error = '';

		$( ".location_name" ).each(function( key, value ) {
			var lname = $(this).val();
			if(lname=='' || lname==null || lname==undefined){
				$(this).addClass('error');
				error = 1;
			}else{
				$(this).removeClass('error');
				error = 0;
			}
		});
		$( ".level_name").each(function( key, value ) {
			var lvname = $(this).val();
			if(lvname=='' || lvname==null || lvname==undefined){
				$(this).addClass('error');
				error = 1;
			}else{
				$(this).removeClass('error');
				error = 0;
			}
		});
		$( ".category_name" ).each(function( key, value ) {
			var lname = $(this).val();
			if(lname=='' || lname==null || lname==undefined){
				$(this).addClass('error');
				error = 1;
			}else{
				$(this).removeClass('error');
				error = 0;
			}
		});

		formData.push({
			"name": "_token",
			"value": csrfToken,
		});

		formData.push({
			"name": "school_id",
			"value": "{{$schoolId}}",
		});
		
		if(error < 1){
			$.ajax({
				url: BASE_URL + '/add-school-parameters',
				data: formData,
				type: 'POST',
				dataType: 'json',
				success: function(response){	
					if(response.status == 1){
						$('#modal_parameter').modal('show');
						$("#modal_alert_body").text('{{ __('Sauvegarde réussie') }}');
						window.location.reload();
					}
				}
			})
		}else{
			$('#modal_parameter').modal('show');
			$("#modal_alert_body").text('{{ __('Required field is empty') }}');
		}
	}); 

})


function UploadImage() {
	document.getElementById("profile_image_file").value = "";
	$("#profile_image_file").trigger('click');
}
function ChangeImage() {
	var school_id = $("#school_id").val(),
			p_file_id = '', data = '';
	var file_data = $('#profile_image_file').prop('files')[0];
	var formData = new FormData();
	formData.append('profile_image_file', file_data);
	formData.append('type', 'upload_image');
	formData.append('school_id', school_id);
	
	let loader = $('#pageloader');
	loader.show("fast");
	$.ajax({
		url: BASE_URL + '/update-school-logo',
		data: formData,
		type: 'POST',
		//dataType: 'json',
		processData: false,
		contentType: false,
		beforeSend: function (xhr) {
			loader.show("fast");
		},
		success: function (result) {
			loader.hide("fast");
			var mfile = result.image_file + '?time=' + new Date().getTime();
			$("#profile_image_user_account").attr("src",mfile);
			$("#delete_profile_image").show();
		},// success
		error: function (reject) {
			loader.hide("fast");
			let errors = $.parseJSON(reject.responseText);
			errors = errors.errors;
			$.each(errors, function (key, val) {
				//$("#" + key + "_error").text(val[0]);
				errorModalCall(val[0]+ ' '+GetAppMessage('error_message_text')); 
			});
		},
		complete: function() {
			loader.hide("fast");
		}
	});
}

function DeleteProfileImage() {
	//delete image
	var school_id = document.getElementById('school_id').value;
	document.getElementById("profile_image_file").value = "";
	let loader = $('#pageloader');
	$.ajax({
		url: BASE_URL + '/delete-school-logo',
		data: 'school_id=' + school_id,
		type: 'POST',
		dataType: 'json',
		beforeSend: function (xhr) {
			loader.show("fast");
		},
		success: function(response) {
			if (response.status == 'success'){
				loader.hide("fast");
				$("#profile_image_user_account").attr("src",BASE_URL+'/img/photo_blank.jpg');
				$("#delete_profile_image").hide();
				successModalCall(response.message);
			}
						
		},
		error: function (reject) {
			loader.hide("fast");
			let errors = $.parseJSON(reject.responseText);
			errors = errors.errors;
			$.each(errors, function (key, val) {
				//$("#" + key + "_error").text(val[0]);
				errorModalCall(val[0]+ ' '+GetAppMessage('error_message_text')); 
			});
		},
		complete: function() {
			loader.hide("fast");
		}
	});

}


function validateSchoolForm() {
	return true;
	//var p_school_code = document.getElementById("school_code").value;
	let error = false;

	// if (p_school_code.trim() == '') {
	// 	errorModalCall("Code d'école invalide.");
	// 	error = true;
	// }
	//added for max students and teachers
	// var max_students = document.getElementById("max_students").value;
	// var max_teachers = document.getElementById("max_teachers").value;

	// if (max_students.trim() == 0) {
	// 		//alert("nombre max invalide");
	// 		errorModalCall("{{ __('Maximum students required') }}");
	// 		document.getElementById("max_students").focus();
	// 		error = true;
	// }
	// if (max_teachers.trim() == 0) {
	// 		//alert("nombre max invalide");	
	// 		errorModalCall("{{ __('Maximum teacher required') }}");
	// 		document.getElementById("max_teachers").focus();
	// 		error = true;
	// }
	// if (error) {
	// 	return false;
	// }            			
	// else
	// {
	// 	return true;
	// }
}

$('#etransfer_acc').on('input', function() {

	var val = $(this).val();
	var validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

	if (val.match(validRegex)) {
		console.log('ddd')
		$('.etransfer_acc').html('');
	}else{
		console.log('invaddd')
		$('.etransfer_acc').html('Invalid email address!');
	}

});

  
</script>
@endsection