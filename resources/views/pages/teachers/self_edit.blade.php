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
	<div class="container-fluid body">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-12 col-xs-12 header-area pb-3">
					<div class="page_header_class">
						<label id="page_header" name="page_header"><i class="fa-solid fa-user"></i> {{ __('Coach Information') }}</label>
					</div>
				</div>
				<!--<div class="col-sm-6 col-xs-12 btn-area">
					<div class="float-end btn-group">
						<a style="display: none;" id="delete_btn" href="#" class="btn btn-theme-warn"><em class="glyphicon glyphicon-trash"></em> {{ __('Delete')}}</a>
					</div>
				</div>-->
			</div>


	</header>

		<nav cklass="subNav">
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
					{{ __('Contact Information') }}
				</button>
				@can('parameters-list')
				@if($AppUI->isTeacherAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
					<button class="nav-link" id="nav-parameters-tab" data-bs-toggle="tab" data-bs-target="#tab_5" type="button" role="tab" aria-controls="nav-parameters" aria-selected="false">
					{{ __('Parameters')}}
					</button>
					@endif
				@endcan
				@if($AppUI->isTeacherAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
				 <button class="nav-link" id="nav-prices-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
					{{ __('Sections and prices')}}
				</button>
				@endif
				@if($AppUI->isTeacherAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
				 <button class="nav-link" id="nav-prices-tab" data-bs-toggle="tab" data-bs-target="#tab_taxes" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
					{{ __('Taxes')}}
				</button>
				@endif
			</div>
		</nav>
		<!-- Tabs navs -->

	<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<input type="hidden" id="user_id" name="user_id" value="{{$teacher->user->id}}">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
				<form class="form-horizontal" id="add_teacher" action="{{ route('updateTeacherAction') }}"  method="POST" enctype="multipart/form-data" name="add_teacher" role="form">
					@csrf
					<input type="hidden" id="school_id" name="school_id" value="{{$schoolId}}">
					<input type="hidden" id="school_name" name="school_name" value="{{$schoolName}}">



					<div class="row">

						<fieldset class="col-lg-10">
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Personal information') }}</label>
						</div>


						<div class="card">
							<div class="card-body bg-tertiary">

						<div class="row">
							<div class="col-md-6">
								<!-- <div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Status') }}</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" name="availability_select" id="availability_select">
												<option value="10" >Active</option>
												<option value="0">Inactive</option>
												<option value="-9">Deleted</option>
											</select>
										</div>
									</div>
								</div> -->
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="nickname" id="nickname_label_id">{{__('Nickname') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control" disabled="disabled" id="nickname" maxlength="50" name="nickname" placeholder="Pseudo" type="text"
										value="{{!empty($relationalData->nickname) ? old('nickname', $relationalData->nickname) : old('nickname')}}"
										>
										@if ($errors->has('nickname'))
											<span id="" class="error">
													<strong>{{ $errors->first('nickname') }}.</strong>
											</span>
										@endif
									</div>
								</div>
								<!-- <div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="gender_id" id="gender_label_id">{{__('Gender') }} : *</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control require" id="gender_id" name="gender_id">
												@foreach($genders as $key => $gender)
								                    <option value="{{ $key }}" {{!empty($teacher->gender_id) ? (old('gender_id', $teacher->gender_id) == $key ? 'selected' : '') : (old('gender_id') == $key ? 'selected' : '')}}>{{ $gender }}</option>
								                @endforeach
											</select>
											@if ($errors->has('gender_id'))
												<span id="" class="error">
														<strong>{{ $errors->first('gender_id') }}.</strong>
												</span>
											@endif
										</div>
									</div>
								</div> -->
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="lastname" id="family_name_label_id">{{__('Family Name') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control require" value="{{!empty($teacher->lastname) ? old('lastname', $teacher->lastname) : old('lastname')}}" id="lastname" name="lastname" type="text">
										@if ($errors->has('lastname'))
											<span id="" class="error">
													<strong>{{ $errors->first('lastname') }}.</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="firstname" id="first_name_label_id">{{__('First Name') }} : <span class="required_sign">*</span></label>
									<div class="col-sm-7">
										<input class="form-control require" value="{{!empty($teacher->firstname) ? old('firstname', $teacher->firstname) : old('firstname')}}" id="firstname" name="firstname" type="text">
										@if ($errors->has('firstname'))
											<span id="" class="error">
													<strong>{{ $errors->first('firstname') }}.</strong>
											</span>
										@endif
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<!-- <div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" id="birth_date_label_id">{{__('Birth date') }}:</label>
									<div class="col-sm-7">
										<div class="input-group" id="birth_date_div">
											<input id="birth_date" value="{{!empty($teacher->birth_date) ? date('d/m/Y', strtotime($teacher->birth_date)) : old('birth_date')}}" name="birth_date" type="text" class="form-control">
											<span class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</span>
										</div>
									</div>
								</div> -->
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" id="slicence_js_caption">{{__('License number') }} :</label>
									<div class="col-sm-7">
										<input class="form-control" value="{{!empty($teacher->licence_js) ? old('licence_js', $teacher->licence_js) : old('licence_js')}}" id="licence_js" name="licence_js" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="email" id="email_caption">{{__('Email') }} :</label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
											<input class="form-control" value="{{!empty($teacher->email) ? old('email', $teacher->email) : old('email')}}" id="email" name="email" type="text">
										</div>
									</div>
								</div>
								<!-- <div class="form-group row" id="sbg_color_agenda_div">
									<label class="col-lg-3 col-sm-3 text-left" for="sbg_color_agenda" id="sbg_color_agenda_caption">{{__('Agenda Color') }} :</label>
									<div class="col-sm-2">
										<input type="text" name="bg_color_agenda" value="{{!empty($relationalData->bg_color_agenda) ? $relationalData->bg_color_agenda : old('bg_color_agenda')}}"  class="colorpicker dot" />
									</div>
								</div> -->
							</div>
						</div>

							</div>
						</div>

						<div class="row">

							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="address_caption">{{__('Address') }}</label>
								<p style="color:red; font-size:14px;">Optional - this information will appear on the invoice</p>
							</div>
							<div class="card">
								<div class="card-body bg-tertiary">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="street" id="street_caption">{{__('Street') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" value="{{!empty($teacher->street) ? old('street', $teacher->street) : old('street')}}" id="street" name="street" type="text">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="street_number" id="street_number_caption">{{__('Street No') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" value="{{!empty($teacher->street_number) ? old('street_number', $teacher->street_number) : old('street_number')}}" id="street_number" name="street_number" type="text">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="zip_code" id="postal_code_caption">{{__('Postal Code') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" value="{{!empty($teacher->zip_code) ? old('zip_code', $teacher->zip_code) : old('zip_code')}}" id="zip_code" name="zip_code" type="text">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="place" id="locality_caption">{{__('City') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" value="{{!empty($teacher->place) ? old('place', $teacher->place) : old('place')}}" id="place" name="place" type="text">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="country_code" id="pays_caption">{{__('Country') }} :</label>
										<div class="col-sm-7">
											<div class="selectdiv">
											<select class="form-control select_two_defult_class" id="country_code" name="country_code">
												@foreach($countries as $country)
														<option value="{{ $country->code }}" {{!empty($teacher->country_code) ? (old('country_code', $teacher->country_code) == $country->code ? 'selected' : '') : (old('country_code') == $country->code ? 'selected' : '')}}>{{ $country->name }} ({{ $country->code }})</option>
												@endforeach
											</select>
											</div>
										</div>
									</div>
									<div id="province_id_div" class="form-group row" style="display:none">
										<label id="province_caption" for="province_id" class="col-lg-3 col-sm-3 text-left">Province: </label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control select_two_defult_class" id="province_id" name="province_id">
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>

								</div>
							</div>

							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="contact_info_caption">{{ __('Contact information') }}</label>
								<p style="color:red; font-size:14px;">Optional - this information will appear on the invoice</p>
							</div>
							<div class="card">
								<div class="card-body bg-tertiary">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="phone" id="phone_caption">{{__('Phone') }} :</label>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-phone-square"></i></span>
												<input class="form-control" value="{{!empty($teacher->phone) ? old('phone', $teacher->phone) : old('phone')}}" id="phone" name="phone" type="text">
											</div>
										</div>
									</div>
									<!-- <div class="form-group row">
										<div class="btn-group col-lg-3 col-sm-3 text-left">
											<label>{{ __('Phone2') }} :</label> <label class="text-left"></label>
										</div>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-phone"></i></span> <input class="form-control" id="sphone2" name="sphone2" type="text">
											</div>
										</div>
									</div> -->
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="mobile" id="mobile_caption">{{__('Mobile') }} :</label>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-mobile"></i></span>
												<input class="form-control" value="{{!empty($teacher->mobile) ? old('mobile', $teacher->mobile) : old('mobile')}}" id="mobile" name="mobile" type="text">
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="email2" id="email_caption">{{__('Email') }} :</label>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
												<input class="form-control" value="{{!empty($teacher->email2) ? old('email2', $teacher->email2) : old('email2')}}" id="email2" name="email2" type="text">
											</div>
										</div>
									</div>
								</div>
							</div>

								</div>
							</div>


							<div class="clearfix"></div>
							@if ($isInEurope)
							<div class="section_header_class">
								<label id="contact_info_caption">{{ __('Teacher Bank Information')}}</label>
								<p style="color:red; font-size:14px;">Optional - this information will appear on the invoice</p>
							</div>
							<div class="card">
								<div class="card-body bg-tertiary">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Bank Name')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_name" name="bank_name" type="text"
												value="{{!empty($teacher->bank_name) ? old('bank_name', $teacher->bank_name) : old('bank_name')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Address')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_address" name="bank_address" type="text"
												value="{{!empty($teacher->bank_address) ? old('bank_address', $teacher->bank_address) : old('bank_address')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Postal Code')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_zipcode" name="bank_zipcode" type="text"
												value="{{!empty($teacher->bank_zipcode) ? old('bank_zipcode', $teacher->bank_zipcode) : old('bank_zipcode')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('City')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_place" name="bank_place" type="text"
											value="{{!empty($teacher->bank_place) ? old('bank_place', $teacher->bank_place) : old('bank_place')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Country')}}:</label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control" name="bank_country_code" id="bank_country_code">
													<option value="">{{ __('Select')}}</option>
													@foreach($countries as $country)
															<option value="{{ $country->code }}" {{!empty($teacher->bank_country_code) ? (old('country_code', $teacher->bank_country_code) == $country->code ? 'selected' : '') : (old('country_code') == $country->code ? 'selected' : '')}}>{{ $country->name }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">

									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Account No')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_account" name="bank_account" type="text"
												value="{{!empty($teacher->bank_account) ? old('bank_account', $teacher->bank_account) : old('bank_account')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('IBAN No')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_iban" name="bank_iban" type="text"
												value="{{!empty($teacher->bank_iban) ? old('bank_iban', $teacher->bank_iban) : old('bank_iban')}}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('SWIFT A/c No')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="bank_swift" name="bank_swift" type="text"
												value="{{!empty($teacher->bank_swift) ? old('bank_swift', $teacher->bank_swift) : old('bank_swift')}}">
										</div>
									</div>
								</div>
							</div>

								</div>
							</div>
							@else
							<div class="section_header_class">
								<label id="contact_info_caption">{{ __('Payment Information')}}</label>
							</div>
							<div class="card">
								<div class="card-body bg-tertiary">


									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="mobile" id="mobile_caption">{{__('Payment preference') }} :</label>
										<div class="col-sm-5">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa-solid fa-money-check-dollar"></i></span>
												<input class="form-control" id="bank_name" name="bank_name" type="text"
												value="{{!empty($teacher->bank_name) ? old('bank_name', $teacher->bank_name) : old('bank_name')}}">
											</div>
										</div>
									</div>

                                    <div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="mobile" id="mobile_caption">{{__('Payment preference 2') }} :</label>
										<div class="col-sm-5">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa-solid fa-money-check-dollar"></i></span>
												<input class="form-control" id="bank_account" name="bank_account" type="text"
												value="{{!empty($teacher->bank_account) ? old('bank_account', $teacher->bank_account) : old('bank_account')}}">
											</div>
										</div>
									</div>

                                    <div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="mobile" id="mobile_caption">{{__('Payment preference 3') }} :</label>
										<div class="col-sm-5">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa-solid fa-money-check-dollar"></i></span>
												<input class="form-control" id="bank_iban" name="bank_iban" type="text"
												value="{{!empty($teacher->bank_iban) ? old('bank_iban', $teacher->bank_iban) : old('bank_iban')}}">
											</div>
										</div>
									</div>

									@if($teacher == 'CA')
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="mobile" id="mobile_caption">{{__('E-transfer email') }} :</label>
										<div class="col-sm-5">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa-brands fa-canadian-maple-leaf"></i></span>
												<input class="form-control" id="etransfer_acc" name="etransfer_acc" type="text"
												value="{{!empty($teacher->etransfer_acc) ? old('etransfer_acc', $teacher->etransfer_acc) : old('etransfer_acc')}}">
												<span class="etransfer_acc"></span>
											</div>
										</div>
									</div>
									@endif



								</div>
							</div>

							@endif
							<!-- <div id="commentaire_div">
								<div class="section_header_class">
									<label id="private_comment_caption">{{__('Private comment') }}</label>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-left">{{__('Private comment') }} :</label>
											<div class="col-sm-7">
												<textarea class="form-control" cols="60" id="scomment" name="comment" rows="5"> {{!empty($relationalData->comment) ? old('comment', $relationalData->comment) : old('comment')}} </textarea>
											</div>
										</div>
									</div>
								</div>
							</div> -->
						</div>
					</fieldset>

					<div class="col-lg-2 btn_actions" style="position:fixed; right:0;">
						<div class="section_header_class">
						<label><br></label>
						</div>
						<div class="card" style="border-radius:8px 0 0 8px; background-color:#EEE;">
							<div class="card-body">
						<button type="submit" id="save_btn" name="save_btn" class="btn btn-theme-success"><i class="fa fa-save"></i> {{ __('Save') }}</button>
							</div>
						</div>
					</div>

				</div>


				</form>
			</div>
			<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
				<form class="form-horizontal" id="add_price" action="{{ route('selfUpdatePriceAction') }}"  method="POST" enctype="multipart/form-data" name="add_price" role="form">
					@csrf
					<div class="section_header_class">
						<label id="teacher_personal_data_caption">{{__('Category and prices') }}</label>
					</div>

					<div class="row">
					<div class="col-lg-10">


                                <div class="accordion" id="accordionExample">

                                    @foreach($eventCategory as $key => $category)
                                    <div class="accordion-item">
                                      <h2 class="accordion-header" id="heading-{{ $key }}">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $key }}" aria-expanded="true" aria-controls="collapse-{{ $key }}">
                                            <h6><small><i class="fa-solid fa-arrow-right"></i> {{$category->title}}</small></h6>
                                        </button>
                                      </h2>
                                      <div id="collapse-{{ $key }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $key }}" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <table id="tariff_table_rate" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>{{__('Type of course')}}</th>
                                                        <th>{{__('Type of billing')}}</th>
                                                        <th class="sell" style="text-align: right;"><span>{{__('Per student /hour')}}</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($lessonPrices as $key => $lessionPrice)
                                                    <?php
                                                    $class = "";
                                                    if ($lessionPrice->divider == 1) {
                                                        $textForTypeBilling = 'Private session';
                                                        $textTooltip = "student";
                                                    } elseif ($lessionPrice->divider == 9999) {
                                                        $textForTypeBilling = 'Student more than 10';
                                                        $textTooltip = "each of the students";
                                                        $class = "hide-custom-price";
                                                    } elseif ($lessionPrice->divider == -1) {
                                                        $textForTypeBilling = 'Fixed price';
                                                        $textTooltip = "each student";
                                                        $classFiexPrice = "hide-show-more";
                                                    } else {
                                                        $textForTypeBilling = "Group lessons for $lessionPrice->divider students";
                                                        $textTooltip = "each of the  $lessionPrice->divider  students";
                                                    if (($lessionPrice->divider >=1 && $lessionPrice->divider < 6) || $lessionPrice->divider === 9999) {
                                                            $class = "";
                                                        } else {
                                                            $class = "hide-custom-price";
                                                        }
                                                    }

                                                    $studentPrice = $category->s_std_pay_type;

                                                    if ($studentPrice == 1) {
                                                        if ($lessionPrice->divider != -1) continue;
                                                    } elseif ($studentPrice == 0) {
                                                        if ($lessionPrice->divider == -1) continue;
                                                    } else {
                                                    }
                                                    ?>
                                                <tr class="{{$class}}">
                                                    <input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][id]" value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['id'] : '' }}">
                                                    <input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_student]" value="{{$lessionPrice->lesson_price_student}}">
                                                    <input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_id]" value="{{$lessionPrice->id}}">
                                                    <td>{{__('Lessons/Events')}}</td>
                                                    <td>{{ __($textForTypeBilling) }}</td>
                                                    <td>
                                                        <input data-toggle="tooltip" data-bs-divider="{{ $lessionPrice->divider }}" data-bs-original-title="For 15 mn. {{  $textTooltip }}  will pay  ({{ $school->default_currency_code }}) {{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? ($ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell']/4) : '0.00' }}<hr>For 30 mn. {{  $textTooltip }} will pay ({{ $school->default_currency_code }}) {{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? ($ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell']/2) : '0.00' }}" type="text" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][price_sell]" value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell'] : '0.00' }}" style="text-align:right" class="form-control input-price numeric float <?= ($studentPrice == 1) && ($lessionPrice->divider != -1) ? 'd-none' : '' ?>">
                                                    </td>
                                                </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if($category->s_std_pay_type == 0)
                                                <div class="pull-right">
                                                    <a href="#" class="btn btn-theme-primary" id="add_new_price"><i class="fa fa-plus"></i> {{ __('See more') }}</a>
                                                </div>
                                            @endif
                                        </div>
                                      </div>
                                    </div>
                                    @endforeach

                                  </div>


					</div>

						<div class="col-lg-2 btn_actions" style="position:fixed; right:0;">
							<div class="section_header_class">
							</div>
							<div class="card" style="border-radius:8px 0 0 8px; background-color:#EEE;">
								<div class="card-body">
							<button type="submit" id="save_btn" name="save_btn" class="btn btn-theme-success"><i class="fa fa-save"></i> {{ __('Save') }}</button>
								</div>
							</div>
						</div>

					</div>

				</form>
			</div>









	<div class="tab-pane fade" id="tab_taxes" role="tabpanel" aria-labelledby="tab_taxes">
				<div class="section_header_class">
					<label id="teacher_personal_data_caption">{{__('Taxes') }}</label>
				</div>
				<form class="form-horizontal" id="add_price" action="{{ route('selfUpdateTaxeAction') }}"  method="POST" enctype="multipart/form-data" name="add_price" role="form">
					@csrf
				<div class="row">
					<div class="col-lg-10">
						<?php foreach($InvoicesTaxData as $tax): ?>
						<div class="add_more_tax_row row mb-2">
							<div class="card">
									<div class="card-body bg-tertiary">
								<div class="col-md-12">
									<div class="form-group row">
										<label id="tax_name_caption" for="tax_name" class="col-lg-3 col-sm-3 text-left">Name of Tax</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" name="tax_name[]" value="<?= $tax['tax_name'] ?>" placeholder="Tax Name" maxlength="255">
										</div>
									</div>
									<div class="form-group row">
										<label id="tax_percentage_caption" for="tax_percentage" class="col-lg-3 col-sm-3 text-left">% of Tax</label>
										<div class="col-sm-7">
											<input type="text" class="form-control tax_percentage" name="tax_percentage[]" value="<?= $tax['tax_percentage'] ?>" placeholder="Tax Percentage" maxlength="6">
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group row">
										<label id="tax_number_caption" for="tax_number" class="col-lg-3 col-sm-3 text-left">Tax Number</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" name="tax_number[]" value="<?= $tax['tax_number'] ?>" placeholder="Tax Number" maxlength="100">
											<p style="font-size:11px;">this number will show on your invoice</p>
										</div>
										<div class="col-sm-2">
											<button type="button" class="btn btn-theme-warn delete_tax"><i class="fa-solid fa-trash"></i></button>
										</div>
									</div>
								</div>
							</div></div>
							</div>
					<?php endforeach; ?>

						<div id="add_more_tax_div"></div>

					</div>
					<div class="col-lg-2 btn_actions" style="position:fixed; right:0;">
						<div class="section_header_class">
						</div>
						<div class="card" style="border-radius:8px 0 0 8px; background-color:#EEE;">
							<div class="card-body">
								<button id="add_more_tax_btnn" type="button" class="btn bg-info text-white w-100"><em class="glyphicon glyphicon-plus"></em>Add Another Tax</button>
								<button type="submit" id="save_btn" name="save_btn" class="btn btn-theme-success w-100 mt-1">{{ __('Save') }}</button>
							</div>
						</div>
			</div>
		</div>
				</form>
	</div>







			<!--Start of Tab 5 -->
			<div id="tab_5" class="tab-pane">
				@include('pages.schools.elements.school-parameters')
			</div>
			<!--End of Tab 5-->
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
					<button type="button" id="modalClose" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Ok') }}</button>
				</div>
			</div>
		</div>
	</div>
	<!-- End Tabs content -->
@endsection


@section('footer_js')

<script type="text/javascript">

let currencyCode = "{{ $school->default_currency_code }}";

$(function () {
  $('[data-toggle="tooltip"]').tooltip({html:true,placement:"right"})
})

$(document).ready(function(){
    $('.input-price').keyup(function(e){
        if ($(this).hasClass('input-price')) {
        var divider = $(this).data('bs-divider');
        console.log(divider);

        var textForTypeBilling, textTooltip;

    if (divider == 1) {
        textForTypeBilling = 'Private session';
        textTooltip = "student";
    } else if (divider == 9999) {
        textForTypeBilling = 'Student more than 10';
        textTooltip = "each of the students";
    } else if (divider == -1) {
        textForTypeBilling = 'Fixed price';
        textTooltip = "each student";
    } else {
        textForTypeBilling = "Group lessons for " + divider + " students";
        textTooltip = "each of the " + divider + " students";
    }

    var newValue = this.value;
    var tooltipText = "For 15 mn. " + textTooltip + " will pay ("+currencyCode+") " + (newValue / 4) + "<hr>For 30 mn. " + textTooltip + " will pay ("+currencyCode+") " + (newValue / 2);

        var tooltipElement = $(this).closest('[data-toggle="tooltip"]');
            tooltipElement.attr('data-bs-original-title', tooltipText);
            tooltipElement.tooltip('show');
        }
    });
});


    $(document).on('click','#add_new_price',function(){
        $('.hide-custom-price').slideDown('slow');
        $('#add_new_price').hide();
    });


	//var isInEurope = {{ $isInEurope ? 'true' : 'false' }};

	$(document).on('click','#add_more_tax_btnn',function(){

		var resultHtml = `<div class="add_more_tax_row row mb-2">
			<div class="card">
					<div class="card-body bg-tertiary">
						<span class="badge bg-info">new</span>
				<div class="col-md-12">
					<div class="form-group row">
						<label id="tax_name_caption" for="tax_name" class="col-lg-3 col-sm-3 text-left">Name of Tax</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" name="tax_name[]" value="" placeholder="Tax Name" maxlength="255">
						</div>
					</div>
					<div class="form-group row">
						<label id="tax_percentage_caption" for="tax_percentage" class="col-lg-3 col-sm-3 text-left">% of Tax</label>
						<div class="col-sm-7">
							<input type="text" class="form-control tax_percentage" name="tax_percentage[]" value="" placeholder="Tax Percentage" maxlength="6">
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-group row">
						<label id="tax_number_caption" for="tax_number" class="col-lg-3 col-sm-3 text-left">Tax Number</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" name="tax_number[]" value="" placeholder="Tax Number" maxlength="255">
						</div>
						<div class="col-sm-2">
							<button type="button" class="btn btn-theme-warn delete_tax"><i class="fa-solid fa-trash"></i></button>
						</div>
					</div>

				</div>
			</div></div>
			</div>`;

			$("#add_more_tax_div").append(resultHtml);
			window.scrollTo(0, document.body.scrollHeight);

	})

	$(document).on('click','.delete_tax',function(){
		$(this).parents('.add_more_tax_row').remove();
	})

	</script>




<script type="text/javascript">

	/*
	* Billing province list
	* function @billing province
	*/
	$('#country_code').change(function(){
		var country_code = $(this).val();
		var set_province = '<?= $teacher->province_id ?>';

		get_province_lists(country_code, set_province);
	})

	$(document).ready(function(){
		var country_code = $('#country_code option:selected').val();
		var set_province = '<?= $teacher->province_id ?>';
		console.log(set_province,'set_provinceset_province');
		get_province_lists(country_code, set_province);
	});

	function get_province_lists(country_code, set_province){
		$.ajax({
			url: BASE_URL + '/get_province_by_country',
			data: 'country_name=' + country_code,
			type: 'POST',
			dataType: 'json',
			async: false,
			success: function(response) {
					if(response.data.length > 0){
						var html = '';
						$.each(response.data, function(i, item) {
							if(item.id == set_province){
								var select = 'selected';
							}else{
								var select = '';
							}
							html += '<option ' + select + ' value="'+ item.id +'">' + item.province_name + '</option>';
						});
						$('#province_id').html(html);
						$('#province_id_div').show();
				}else{
					$('#province_id').html('');
					$('#province_id_div').hide();
				}
			},
			error: function(e) {
				//error
			}
		});
	}

	$(document).ready(function(){
		$("#birth_date").datetimepicker({
					format: "dd/mm/yyyy",
					autoclose: true,
					todayBtn: true,
			minuteStep: 10,
			minView: 3,
			maxView: 3,
			viewSelect: 3,
			todayBtn:false,
		});
	var x = document.getElementsByClassName("tab-pane active");
	//var update_btn = document.getElementById("update_btn");
	console.log(x[0].id);
	if (x[0].id == "tab_5") {
		document.getElementById("save_btn").style.display = "none";
	}
	else  {
		document.getElementById("save_btn").style.display = "block";
	}
	var vtab=getUrlVarsO()["tab"];
	console.log(vtab);
	if (typeof vtab === "undefined") {
		vtab='';
	}
	if (vtab == 'tab_5') {//?action=edit&tab=tab_5
		document.getElementById("save_btn").style.display = "none";
		activaTab('tab_5');
	} else {
		document.getElementById("save_btn").style.display = "block";
	}

	$(document).on( 'shown.bs.tab', 'button[data-bs-toggle="tab"]', function (e) {
		console.log(e.target.id) // activated tab
		if (e.target.id == 'nav-parameters-tab') {
			document.getElementById("save_btn").style.display = "none";
		}
		else  {
			document.getElementById("save_btn").style.display = "block";
		}
	})

	$('#delete_profile_image').click(function (e) {
		DeleteProfileImage();      // refresh lesson details for billing
	})

})

$(function() {
	$('.colorpicker').wheelColorPicker({ sliders: "whsvp", preview: true, format: "css" });
	$('.colorpicker').wheelColorPicker('value', "{{ $relationalData->bg_color_agenda }}");
});

function activaTab(tab) {
	$('.nav-tabs button[data-bs-target="#' + tab + '"]').tab('show');
};
function getUrlVarsO()
{
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	//alert(hashes);
	for(var i = 0; i < hashes.length; i++)
	{
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	//Salert(vars);
	return vars;
}  //getUrlVarsO
function UploadImage() {
	document.getElementById("profile_image_file").value = "";
	$("#profile_image_file").trigger('click');
}
function ChangeImage() {
	var user_id = $("#user_id").val(),
			p_file_id = '', data = '';
	var file_data = $('#profile_image_file').prop('files')[0];
	var formData = new FormData();
	formData.append('profile_image_file', file_data);
	formData.append('type', 'upload_image');
	formData.append('user_id', user_id);

	let loader = $('#pageloader');
	loader.show("fast");
	$.ajax({
		url: BASE_URL + '/update-teacher-photo',
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
	var user_id = document.getElementById('user_id').value;
	document.getElementById("profile_image_file").value = "";
	let loader = $('#pageloader');
	$.ajax({
		url: BASE_URL + '/delete-teacher-photo',
		data: 'user_id=' + user_id,
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
</script>
@endsection
