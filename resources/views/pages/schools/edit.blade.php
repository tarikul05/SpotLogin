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
		<form method="POST" action="{{route('school.update')}}" id="emailForm" name="emailForm" class="form-horizontal" role="form">
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
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">Personal data of the school</label>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">Status: *</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" name="is_active" id="is_active">
												<option value="1" {{!empty($data) ? (old('is_active', $data->is_active) == 1 ? 'selected' : '') : (old('is_active') == 1 ? 'selected' : '')}}>{{ __('Active')}}</option>
												<option value="0" {{!empty($data) ? (old('is_active', $data->is_active) == 0 ? 'selected' : '') : (old('is_active') == 0 ? 'selected' : '')}}>{{ __('Inactive')}}</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
										<label id="school_code_caption" name="school_code_caption" class="col-lg-3 col-sm-3 text-left">School Code
												*:</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="school_code"
													name="school_code" maxlength="30"
													onkeyup="DisplaySchoolURL(this)">
											<strong><label style="color:blue;" id="school_url"
															name="school_url"></label></strong>
										</div>
								</div>
								<div class="form-group row">
									<label id="row_hdr_school_name"
											class="col-lg-3 col-sm-3 text-left">Name of the School
											*:</label>
									<div class="col-sm-7">
											<input type="text" class="form-control" id="school_name"
													name="school_name">
									</div>
									
								</div>
								<div class="form-group row">
									<label id="organization_type_caption"
									class="col-lg-3 col-sm-3 text-left">Organization Type
									*:</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" name="is_active" id="is_active">
												<option value="1" {{!empty($data) ? (old('is_active', $data->is_active) == 1 ? 'selected' : '') : (old('is_active') == 1 ? 'selected' : '')}}>{{ __('Active')}}</option>
												<option value="0" {{!empty($data) ? (old('is_active', $data->is_active) == 0 ? 'selected' : '') : (old('is_active') == 0 ? 'selected' : '')}}>{{ __('Inactive')}}</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row">

									<label id="school_type_lbl" class="col-lg-3 col-sm-3 text-left"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Account Type:</font></font></label>
									<label id="school_type" class="col-lg-3 col-sm-3 text-left"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Coach</font></font></label>
								
								</div>
								<div class="form-group row">
									<label id="sender_email_label" name="sender_email_label" 
									class="col-lg-3 col-sm-3 text-left">Sender email address :</label>
									<div class="col-sm-7">
											<input type="email" id="sender_email"
											name="sender_email" size="100" class="form-control">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" id="birth_date_label_id">Incorporation Date:</label>
									<div class="col-sm-7">
										<div class="input-group" id="sbirth_date_div"> 
											<input id="sbirth_date" name="sbirth_date" type="text" class="form-control date_picker">
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
														<select class="form-control" name="is_active" id="is_active">
															<option value="1" {{!empty($data) ? (old('is_active', $data->is_active) == 1 ? 'selected' : '') : (old('is_active') == 1 ? 'selected' : '')}}>{{ __('Active')}}</option>
															<option value="0" {{!empty($data) ? (old('is_active', $data->is_active) == 0 ? 'selected' : '') : (old('is_active') == 0 ? 'selected' : '')}}>{{ __('Inactive')}}</option>
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
										<input type="number" min="0" max="5000" value="0" class="form-control right" id="max_students" name="max_students">
										
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-lg-6 col-sm-6 text-left" for="sstreet" id="street_caption">Maximum number of teachers:</label>
									<div class="col-sm-4">
									<input type="number" min="0" max="5000" value="0" class="form-control" id="max_teachers" name="max_teachers">
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
																		name="billing_method_list">
																		<!--<option value="E">Event-wise</option>-->
																		<option value="M">Monthly</option>
																		<option value="Y">Yearly</option>
																</select>
														</div>
												</div>
										</div>
								</div>
								<div class="col-md-6">
										<div id="monthly_job_day_div3" class="form-group row">
												<label id="monthly_job_day_label"
														name="monthly_job_day_label"
														class="col-lg-4 col-sm-4 text-left">Issue date:</label>
												<div class="col-sm-4">
														<div class="selectdiv">
															<select class="form-control"
																		id="monthly_job_day"
																		name="monthly_job_day">
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
														<div class="selectdiv"><select class="form-control"
																		id="billing_currency"
																		name="billing_currency"></select></div>
												</div>
												<div class="col-sm-4 col-xs-6">
														<input value="0" name="billing_amount"
																id="billing_amount" class="form-control numeric"
																type="text" data-force-required="true"
																data-isrequired="true" maxlength="5">
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
																<input id="sbirth_date" name="sbirth_date" type="text" class="form-control date_picker">
																<span class="input-group-addon">
																	<i class="fa fa-calendar"></i>
																</span>
														</div>
												</div>
												<div class="col-sm-4 col-xs-4">
														<div class="input-group datetimepicker"
																id="billing_date_end_div">
																<input id="sbirth_date" name="sbirth_date" type="text" class="form-control date_picker">
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
											<select class="form-control" name="is_active" id="is_active">
												<option value="1" {{!empty($data) ? (old('is_active', $data->is_active) == 1 ? 'selected' : '') : (old('is_active') == 1 ? 'selected' : '')}}>{{ __('Active')}}</option>
												<option value="0" {{!empty($data) ? (old('is_active', $data->is_active) == 0 ? 'selected' : '') : (old('is_active') == 0 ? 'selected' : '')}}>{{ __('Inactive')}}</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">First Name :</label>
									<div class="col-sm-7">
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Family Name :</label>
									<div class="col-sm-7">
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Position:</label>
									<div class="col-sm-7">
										<input class="form-control" id="sstreet" name="sstreet" type="text">
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
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Street No:</label>
									<div class="col-sm-7">
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Street 2:</label>
									<div class="col-sm-7">
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Postal Code:</label>
									<div class="col-sm-7">
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">City:</label>
									<div class="col-sm-7">
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Country:</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" name="is_active" id="is_active">
												<option value="1" {{!empty($data) ? (old('is_active', $data->is_active) == 1 ? 'selected' : '') : (old('is_active') == 1 ? 'selected' : '')}}>{{ __('Active')}}</option>
												<option value="0" {{!empty($data) ? (old('is_active', $data->is_active) == 0 ? 'selected' : '') : (old('is_active') == 0 ? 'selected' : '')}}>{{ __('Inactive')}}</option>
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
											<span class="input-group-addon"><i class="fa fa-phone-square"></i></span> <input class="form-control" id="sphone" name="sphone" type="text">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="btn-group col-lg-3 col-sm-3 text-left">
										<label>Phone 2</label> <label class="text-left"></label>
									</div>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-phone"></i></span> <input class="form-control" id="sphone2" name="sphone2" type="text">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="smobile" id="mobile_caption">Mobile:</label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-mobile"></i></span> <input class="form-control" id="smobile" name="smobile" type="text">
										</div>
									</div>
								</div>
							
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="semail" id="email_caption">Email:</label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span> <input class="form-control" id="semail" name="semail" type="text">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="btn-group col-lg-3 col-sm-3 text-left">
										<label for="semail2">Email</label> <label class="text-left">(2)</label>
									</div>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span> <input class="form-control" id="semail2" name="semail2" type="text">
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
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Address:</label>
									<div class="col-sm-7">
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Postal Code:</label>
									<div class="col-sm-7">
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">City:</label>
									<div class="col-sm-7">
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Country:</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" name="is_active" id="is_active">
												<option value="1" {{!empty($data) ? (old('is_active', $data->is_active) == 1 ? 'selected' : '') : (old('is_active') == 1 ? 'selected' : '')}}>{{ __('Active')}}</option>
												<option value="0" {{!empty($data) ? (old('is_active', $data->is_active) == 0 ? 'selected' : '') : (old('is_active') == 0 ? 'selected' : '')}}>{{ __('Inactive')}}</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Account Holder information:</label>
									<div class="col-sm-7">
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Account No:</label>
									<div class="col-sm-7">
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">IBAN No:</label>
									<div class="col-sm-7">
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">SWIFT A/c No:</label>
									<div class="col-sm-7">
										<input class="form-control" id="sstreet" name="sstreet" type="text">
									</div>
								</div>
							</div>
						</div>
						
						<div class="clearfix"></div>
						
					</fieldset>
				</div>
				<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
						<div class="section_header_class">
							<label id="course_for_billing_caption">User Account</label>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Name of User:</label>
							<div class="col-sm-7">
								<input class="form-control" id="sstreet" name="sstreet" type="text">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Email:</label>
							<div class="col-sm-7">
								<input class="form-control" id="sstreet" name="sstreet" type="text">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Password:</label>
							<div class="col-sm-7">
								<input class="form-control" id="sstreet" name="sstreet" type="text">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Status:</label>
							<div class="col-sm-7">
								<div class="selectdiv">
									<select class="form-control" name="is_active" id="is_active">
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
								<input class="form-control" id="sstreet" name="sstreet" type="text">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Subject:</label>
							<div class="col-sm-7">
								<input class="form-control" id="sstreet" name="sstreet" type="text">
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
														<textarea rows="30" name="body_text" id="body_text" type="textarea" class="form-control my_ckeditor textarea">{{old('body_text') ? old('body_text') : ''}}</textarea>
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
				</div>
			</div>
		</form>
	</div>

	<!-- End Tabs content -->
@endsection


@section('footer_js')
<script type="text/javascript">
$(function() {
	$(".date_picker").datetimepicker({
		format: "dd/mm/yyyy",
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

  
</script>
@endsection