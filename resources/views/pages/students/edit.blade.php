@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{ asset('js/datetimepicker-lang/moment-with-locales.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<script src="{{ asset('js/lib/moment.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.43/moment-timezone-with-data-10-year-range.js" integrity="sha512-QSV7x6aYfVs/XXIrUoerB2a7Ea9M8CaX4rY5pK/jVV0CGhYiGSHaDCKx/EPRQ70hYHiaq/NaQp8GtK+05uoSOw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- color wheel -->
<script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
@endsection

@section('content')
  <div class="content">
	<div class="container-fluid body">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area" style="padding-bottom:25px;">
					<div class="page_header_class">
						<label id="page_header" name="page_header"><i class="fa-solid fa-user"></i> <span class="d-none d-sm-inline">{{ __('Student Information:') }}</span> <small>{{!empty($relationalData->full_name) ? $relationalData->full_name : ''}}</small></label>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 btn-area pt-1">
					<div class="float-end btn-group">
						@can('students-update')
							<button type="submit" id="save_btn" name="save_btn" class="btn btn-theme-success student_save"><i class="fa fa-save"></i>{{ __('Save') }}</button>
						@endcan
						<a style="display: none;" id="delete_btn" href="#" class="btn btn-theme-warn"><em class="glyphicon glyphicon-trash"></em> {{ __('Delete:') }}</a>
					</div>
				</div>
			</div>
		</header>
		<!-- Tabs navs -->

		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" data-bs-target_val="tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Student Information') }}</button>
				<button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#tab_2" data-bs-target_val="tab_2" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Contact Information') }}</button>
				<!-- <a class="nav-link" href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{__('coming soon')}}" aria-controls="nav-logo" aria-selected="false">
					{{ __('Lesson')}}
				</a> -->
				<button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Lesson') }}</button>

				<!-- <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#tab_4" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('User Account') }}</button> -->
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<form enctype="multipart/form-data" class="form-horizontal" id="add_student" method="post" action="{{!empty($student) ? route('editStudentAction',[$student->id]): '/'}}"  name="add_student" role="form">
		<input type="hidden" id="school_id" name="school_id" value="{{$schoolId}}">

		<input type="hidden" id="school_name" name="school_name" value="{{$schoolName}}">
		<input type="hidden" id="active_tab" name="active_tab" value="">

		@csrf
		<div class="tab-content" id="ex1-content">
				<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Student Personal Information') }}</label>
						</div>
						<div class="card">
							<div class="card-body bg-tertiary">
						<div class="row">
							<div class="col-md-6">
								@if($AppUI->isTeacherAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin() || $AppUI->isTeacherAll())
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="is_active" id="visibility_label_id">{{__('Status') }} :</label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control" name="is_active" id="is_active">
													<option value="">Select</option>
													<option value="1" {{!empty($relationalData->is_active) ? (old('is_active', $relationalData->is_active) == 1 ? 'selected' : '') : (old('is_active') == 1 ? 'selected' : '')}}>{{ __('Active')}}</option>
													<option value="0" {{!empty($relationalData->is_active) ? (old('is_active', $relationalData->is_active) == 0 ? 'selected' : '') : (old('is_active') == 0 ? 'selected' : '')}}>{{ __('Inactive')}}</option>
												</select>
											</div>
										</div>
									</div>
								@endif
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="nickname" id="nickname_label_id">{{__('Nickname') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control require" id="nickname" maxlength="50" name="nickname" placeholder="Nickname" type="text" value="{{!empty($relationalData->nickname) ? old('nickname', $relationalData->nickname) : old('nickname')}}">
										@if ($errors->has('nickname'))
											<span id="" class="error">
													<strong>{{ $errors->first('nickname') }}.</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="gender_id" id="gender_label_id">{{__('Gender') }} : *</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control require" id="gender_id" name="gender_id">
												@foreach($genders as $key => $gender)
													<option value="{{ $key }}" {{!empty($student->gender_id) ? (old('gender_id', $student->gender_id) == $key ? 'selected' : '') : (old('gender_id') == $key ? 'selected' : '')}}>{{ $gender }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="billing_method" id="visibility_label_id">{{__('Hourly rate applied') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control"id="billing_method" name="billing_method">
												<option value="E" {{!empty($relationalData->billing_method) ? (old('billing_method', $relationalData->billing_method) == 'E' ? 'selected' : '') : (old('billing_method') == 'E' ? 'selected' : '')}} >Event-wise</option>
												<option value="M" {{!empty($relationalData->billing_method) ? (old('billing_method', $relationalData->billing_method) == 'M' ? 'selected' : '') : (old('billing_method') == 'M' ? 'selected' : '')}} >Monthly</option>
												<option value="Y" {{!empty($relationalData->billing_method) ? (old('billing_method', $relationalData->billing_method) == 'Y' ? 'selected' : '') : (old('billing_method') == 'Y' ? 'selected' : '')}}>Yearly</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="email" id="email_caption">{{__('Email') }} :</label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
											<input class="form-control" id="email" value="{{!empty($student->email) ? $student->email : old('email')}}" name="email" type="text">
										</div>
									</div>
								</div>

								<!-- <div class="form-group row" id="shas_user_account_div">
									<div id="shas_user_account_div111" class="row">
										<label class="col-lg-3 col-sm-3 text-left" for="shas_user_account" id="has_user_ac_label_id">{{__('Enable student account') }} :</label>
										<div class="col-sm-7">
											<input id="shas_user_account" name="has_user_account" type="checkbox" value="1" {{!empty($relationalData->has_user_account) ? (old('has_user_account', $relationalData->has_user_account) == 1 ? 'checked' : '') : (old('has_user_account') == 1 ? 'checked' : '')}}>
										</div>
									</div>
								</div> -->
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="lastname" id="family_name_label_id">{{__('Family Name') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control require" id="lastname" name="lastname" type="text" value="{{!empty($student->lastname) ? old('lastname', $student->lastname) : old('lastname')}}">
										@if ($errors->has('lastname'))
											<span id="" class="error">
													<strong>{{ $errors->first('lastname') }}.</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="firstname" id="first_name_label_id">{{__('First Name') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control require" id="firstname" name="firstname" type="text" value="{{!empty($student->firstname) ? old('firstname', $student->firstname) : old('firstname')}}">
										@if ($errors->has('firstname'))
											<span id="" class="error">
													<strong>{{ $errors->first('firstname') }}.</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" id="birth_date_label_id">{{__('Birth date') }}:</label>
									<div class="col-sm-7">
										<div class="input-group" id="birth_date_div" >
											@if($student->birth_date !== "1970-01-01 00:00:00")
											<input id="birth_date" name="birth_date" type="text" class="form-control" value="{{!empty($student->birth_date) ? date('d/m/Y', strtotime($student->birth_date)) : ''}}">
											<span class="input-group-addon">
												<i class="fa-solid fa-calendar-check"></i>
											</span>
											@endif
											@if($student->birth_date === "1970-01-01 00:00:00")
											<div class="pt-3 pb-3 mr-2 pr-2"><i class="fa-regular fa-calendar-plus"></i></div>
											<input id="birth_date"  class="datetimepicker" name="birth_date" type="text" value="not specified" style="border:none;padding:0; margin-left:5px;">
											@endif
										</div>
									</div>
								</div>
								<div class="form-group row" id="profile_image">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Profile Image') }} : </label>
									<div class="col-sm-7">
										<input class="form-control" type="file" accept="image/*" id="profile_image_file" name="profile_image_file" style="display:none">
										<span class="box_img">
											<label for="profile_image_file" class="profile_img_area">
											<img src="{{ isset($profile_image->path_name) ? $profile_image->path_name : asset('img/default_profile_image.png') }}"  id="frame" width="150px" alt="SpotLogin">
											@if(empty($profile_image->path_name))
											<i class="fa fa-plus"></i>
											@endif
											<i class="fa fa-plus" style="display:none"></i>
											</label>
											@if(!empty($profile_image->path_name))
											<i class="fa fa-close"></i>
											@endif
											<i class="fa fa-close" style="display:none"></i>
										</span>
									</div>
								</div>

							</div>
						</div>
							</div>
						</div>
						<div class="row">
							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="address_caption">{{__('Level') }}</label>
							</div>
							<div class="card">
								<div class="card-body bg-tertiary">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="level_id">{{__('Level') }} :</label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control m-bot15" id="level_id" name="level_id">
													<option value="">Select level</option>
													@foreach($levels as $key => $level)
														<option value="{{ $level->id }}"  {{ ($relationalData->level_id == $level->id) ? 'selected' : ''}}>{{ $level->title }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									@if($school->country_code == 'CH')
									<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Date last level ASP') }}:</label>
										<div class="col-sm-7">
											<div class="input-group">
												<input id="level_date_arp" name="level_date_arp" type="text" class="form-control" value="<?= !empty($relationalData->level_date_arp) ? old('level_date_arp', date('Y-m-d', strtotime(str_replace('.', '-', $relationalData->level_date_arp)))) : old('level_date_arp') ?>">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="licence_arp" id="postal_code_caption">{{__('ARP license') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" id="licence_arp" name="licence_arp" type="text" value="{{!empty($relationalData->licence_arp) ? old('licence_arp', $relationalData->licence_arp) : old('licence_arp')}}">
										</div>
									</div>
									@endif
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="licence_usp" id="locality_caption">{{__('License number') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" id="licence_usp" name="licence_usp" type="text" value="{{!empty($relationalData->licence_usp) ? old('licence_usp', $relationalData->licence_usp) : old('licence_usp')}}">
										</div>
									</div>
									@if($school->country_code == 'CH')
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="level_skating_usp" id="locality_caption">{{__('USP Level') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" id="level_skating_usp" name="level_skating_usp" type="text" value="{{!empty($relationalData->level_skating_usp) ? old('level_skating_usp', $relationalData->level_skating_usp) : old('level_skating_usp')}}">
										</div>
									</div>
									<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Date last level USP') }}:</label>
										<div class="col-sm-7">
											<div class="input-group" id="date_last_level_usp_div">
												<input id="level_date_usp" name="level_date_usp" type="text" class="form-control" value="<?= !empty($relationalData->level_date_usp) ? old('level_date_usp', date('Y-m-d', strtotime(str_replace('.', '-', $relationalData->level_date_usp)))) : old('level_date_usp') ?>">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>
									</div>
									@endif
								</div>
							</div>
								</div>
							</div>
							@if($AppUI->isTeacherAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin() || $AppUI->isTeacherAll())
								<div id="commentaire_div">
									<div class="section_header_class">
										<label id="private_comment_caption">{{__('Private comment') }}</label>
									</div>
									<div class="card">
										<div class="card-body bg-tertiary">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group row">
												<label class="col-lg-3 col-sm-3 text-left">{{__('Private comment') }} :</label>
												<div class="col-sm-7">
													<textarea class="form-control" cols="60" id="comment" name="comment" rows="5">{{!empty($relationalData->comment) ? old('comment', $relationalData->comment) : old('comment')}}</textarea>
												</div>
											</div>
										</div>
									</div>
										</div>
									</div>
								</div>
							@endif
						</div>
					</fieldset>
				</div>
				<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
					<div class="section_header_class">
						<label id="address_caption">{{__('Address') }}</label>
					</div>
					<div class="card">
						<div class="card-body bg-tertiary">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="street" id="street_caption">{{__('Street') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="street" name="street" value="{{!empty($student->street) ? old('street', $student->street) : old('street')}}" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="street_number" id="street_number_caption">{{__('Street No') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="street_number" name="street_number" value="{{!empty($student->street_number) ? old('street_number', $student->street_number) : old('street_number')}}" type="text">
								</div>
							</div>
							<!-- <div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="street2" id="street_caption">{{__('Street2') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="street2" name="street2" value="{{!empty($student->street2) ? old('street2', $student->street2) : old('street2')}}" type="text">
								</div>
							</div> -->
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="zip_code" id="postal_code_caption">{{__('Postal Code') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="zip_code" name="zip_code" value="{{!empty($student->zip_code) ? old('zip_code', $student->zip_code) : old('zip_code')}}" type="text">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="place" id="locality_caption">{{__('City') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="place" name="place" value="{{!empty($student->place) ? old('place', $student->place) : old('place')}}" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="country_code" id="pays_caption">{{__('Country') }} :</label>
								<div class="col-sm-7">
									<div class="selectdiv">
										<select class="form-control select_two_defult_class" id="country_code" name="country_code">
											<option value="">{{ 'Select Country' }}</option>
											@foreach($countries as $country)
												<option value="{{ $country->code }}" {{!empty($student->country_code) ? (old('country_code', $student->country_code) == $country->code ? 'selected' : '') : (old('country_code') == $country->code ? 'selected' : '')}}>{{ $country->name }}({{ $country->code }})</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="form-group row" id="province_id_div" style="display: none;">
								<label class="col-lg-3 col-sm-3 text-left" for="province_id" id="pays_caption">{{__('Province') }} :</label>
								<div class="col-sm-7">
									<div class="selectdiv">
										<select class="form-control select_two_defult_class" id="province_id" name="province_id">
											<option value="">Select Province</option>
											@foreach($provinces as $province)
												<option value="{{ $province['id'] }}" {{!empty($student->province_id) ? (old('province_id', $student->province_id) == $province['id'] ? 'selected' : '') : (old('province_id') == $province['id'] ? 'selected' : '')}}>{{ $province['province_name'] }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
						</div>
					</div>
					<div class="section_header_class">
						<label id="address_caption">{{__('Billing address - Same as above') }} <input onclick="bill_address_same_as_click()" type="checkbox" name="bill_address_same_as" id="bill_address_same_as"></label>
					</div>
					<div class="card">
						<div class="card-body bg-tertiary">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="billing_street" id="street_caption">{{__('Street') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="billing_street" name="billing_street" value="{{!empty($student->billing_street) ? old('billing_street', $student->billing_street) : old('billing_street')}}" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="billing_street_number" id="street_number_caption">{{__('Street No') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="billing_street_number" name="billing_street_number" value="{{!empty($student->billing_street_number) ? old('billing_street_number', $student->billing_street_number) : old('billing_street_number')}}" type="text">
								</div>
							</div>
							<!-- <div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="billing_street2" id="street_caption">{{__('Street2') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="billing_street2" name="billing_street2" value="{{!empty($student->billing_street2) ? old('billing_street2', $student->billing_street2) : old('billing_street2')}}" type="text">
								</div>
							</div> -->
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="billing_zip_code" id="postal_code_caption">{{__('Postal Code') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="billing_zip_code" name="billing_zip_code" value="{{!empty($student->billing_zip_code) ? old('billing_zip_code', $student->billing_zip_code) : old('billing_zip_code')}}" type="text">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="billing_place" id="locality_caption">{{__('City') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="billing_place" name="billing_place" value="{{!empty($student->billing_place) ? old('billing_place', $student->billing_place) : old('billing_place')}}" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="billing_country_code" id="pays_caption">{{__('Country') }} :</label>
								<div class="col-sm-7">
									<div class="selectdiv">
									<select class="form-control select_two_defult_class" id="billing_country_code" name="billing_country_code">
										<option value="">{{ 'Select Country' }}</option>
										@foreach($countries as $country)
											<option value="{{ $country->code }}" {{!empty($student->billing_country_code) ? (old('billing_country_code', $student->billing_country_code) == $country->code ? 'selected' : '') : (old('billing_country_code') == $country->code ? 'selected' : '')}}>{{ $country->name }} ({{ $country->code }})</option>
										@endforeach
									</select>
									</div>
								</div>
							</div>
							<div class="form-group row" id="billing_province_id_div">
								<label class="col-lg-3 col-sm-3 text-left" for="province_id" id="pays_caption">{{__('Province') }} :</label>
								<div class="col-sm-7">
									<div class="selectdiv">
										<select class="form-control select_two_defult_class" id="billing_province_id" name="billing_province_id">
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
						</div>
					</div>
					<div class="section_header_class">
						<label id="address_caption">{{__('Contact Information (At least one email needs to be selected to receive invoices)') }}</label>
					</div>
					<div class="card">
						<div class="card-body bg-tertiary">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="father_phone" >{{__("Father’s phone") }} :</label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-phone-square"></i></span>
										<input class="form-control" id="father_phone" name="father_phone" value="{{!empty($student->father_phone) ? old('father_phone', $student->father_phone) : old('father_phone')}}" type="text">
									</div>
								</div>
							</div>
							<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="mother_phone" id="mother_phone">{{__("Mother's phone") }} :</label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-phone-square"></i></span>
										<input class="form-control" id="mother_phone" name="mother_phone" value="{{!empty($student->mother_phone) ? old('mother_phone', $student->mother_phone) : old('mother_phone')}}" type="text">
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="student_phone" id="student_phone">{{__("Student's phone:") }} :</label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-phone-square"></i></span>
										<input class="form-control" id="mobile" name="mobile" value="{{!empty($student->mobile) ? old('mobile', $student->mobile) : old('mobile')}}" type="text">
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="father_email" id="father_email">{{__("Father's email") }} :</label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-group-addon"><input type="checkbox" name="father_notify" value="1" {{ !empty($student->father_notify) ? 'checked' : '' }} ></span>
										<input class="form-control" id="father_email" name="father_email" value="{{!empty($student->father_email) ? old('father_email', $student->father_email) : old('father_email')}}" type="text"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="mother_email" >{{__("Mother's email") }} :</label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-group-addon"><input type="checkbox" name="mother_notify" value="1" {{ !empty($student->mother_notify) ? 'checked' : '' }} ></span>
										<input class="form-control" id="mother_email" name="mother_email" value="{{!empty($student->mother_email) ? old('mother_email', $student->mother_email) : old('mother_email')}}" type="text"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="student_email" >{{__("Student's email") }} :</label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-group-addon"><input type="checkbox" name="student_notify" value="1" {{ !empty($student->student_notify) ? 'checked' : '' }} ></span>
										<input class="form-control" id="email2" name="email2" value="{{!empty($student->email2) ? old('email2', $student->email2) : old('email2')}}" type="text"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									</div>
								</div>
							</div>
						</div>
					</div>
						</div>
					</div>
				</div>
				</form>
				<div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tab_3">
					<form role="form" id="form_invoicing" class="form-horizontal" method="post" action="#">
						<input type="hidden" name="selected_month" id="selected_month" value="">
						<input type="hidden" name="selected_year" id="selected_year" value="">
						<input type="hidden" name="person_id" id="person_id" value="{{!empty($student->id) ? old('person_id', $student->id) : old('person_id')}}">
						<input type="hidden" name="no_of_teachers" id="no_of_teachers" value="{{!empty($school->max_teachers) ? old('no_of_teachers', $school->max_teachers) : old('no_of_teachers')}}">


						<!--<div class="alert alert-warning">
							{{ __('Enter the percentage reduction amount for each of the tranches. If a tranche is not applicable, the amount of the reduction must be set to')}}
						</div>-->
						<div class="row">
                            <label class="text-left"> {{ __('Period') }} :</label>
                            <div class="col-md-8">
                              <div class="form-group row below_space">


                                <div class="col-12 col-sm-3">
                                  <input class="form-control" name="billing_period_start_date" id="billing_period_start_date" placeholder="Period Start Date" type="text">
                                </div>

                                <div class="col-12 col-sm-3">
                              <input class="form-control" name="billing_period_end_date" id="billing_period_end_date" placeholder="Period End Date" type="text">
                                </div>

                                <div id="show_only_pend_div" class="col-12 col-sm-3 text-right" style="padding-left:9px;">
                                  <input type="checkbox" id="chk_show_only_pend" name="chk_show_only_pend" checked>
                                  <label id="lbl_chk_show_only_pend" name="lbl_chk_show_only_pend" for="chk_show_only_pend">{{ __('Only pending lessons') }}</label>
                                </div>

                                <div class="col-12 col-sm-3">
                                  <button type="button" class="btn btn-primary" id="billing_period_search_btn">{{ __('Rechercher') }}</button>
                                </div>

                              </div>
                            </div>
                          </div>
						<div class="section_header_class">
							<label id="course_for_billing_caption">{{ __('Lessons applicable for invoicing') }}</label>
						</div>
						<div class="table-responsive lesson_table">
							<table class="w-100" id="lesson_table">

							</table>
						</div>

						<input type="text" style="display:none;" name="finaltotaltaxes" value="0" id="finaltotaltaxes">


						<div class="alert alert-default" id="lesson_footer_div" style="display: none;">
								<label id="verify_label_id">{{ __('Please check all entries before you can convert these items into invoices.') }}</label>
								<br><br>
								<button style="position: absolute;right: 0px;top: 20px;" class="btn btn-primary pull-right" id="btn_convert_invoice">Generate invoice</button>
						</div>
						<!-- <div class="alert alert-danger" id="lesson_footer_div" style="display: block;">
							<label id="verify_label_id" style="display: block;">{{ __('Please check all entries before you can convert these items into invoices.') }}</label>
						</div> -->
					</form>


					<!-- START discount_div -->
					<div style="display:none;" id="discount_div" name="discount_div">
							<div class="form-group">
									<label id="disc_201_400_caption" class="col-lg-4 col-sm-4 text-left">Taux discount 201-400:</label>
									<div class="col-sm-8">
											<input value="" name="s_percent_1" id="s_percent_1" style="width:100%" class="form-control numeric" type="text" data-force-required="true" data-isrequired="true" maxlength="5">
									</div>
							</div>
							<div class="form-group">
									<label id="disc_401_600_caption" class="col-lg-4 col-sm-4 text-left">Taux discount 401-600:</label>
									<div class="col-sm-8">
											<input value="" name="s_percent_2" id="s_percent_2" style="width:100%" class="form-control numeric" type="text" data-force-required="true" data-isrequired="true" maxlength="2">
									</div>
							</div>
							<div class="form-group">
									<label id="disc_601_800_caption" class="col-lg-4 col-sm-4 text-left">Taux discount 601-800:</label>
									<div class="col-sm-8">
											<input value="" name="s_percent_3" id="s_percent_3" style="width:100%" class="form-control numeric" type="text" data-force-required="true" data-isrequired="true" maxlength="2">
									</div>
							</div>
							<div class="form-group">
									<label id="disc_801_1000_caption" class="col-lg-4 col-sm-4 text-left">Taux discount 801-1000:</label>
									<div class="col-sm-8">
											<input value="" name="s_percent_4" id="s_percent_4" style="width:100%" class="form-control numeric" type="text" data-force-required="true" data-isrequired="true" maxlength="2">
									</div>
							</div>
							<div class="form-group">
									<label id="disc_1001_1200_caption" class="col-lg-4 col-sm-4 text-left">Taux discount 1001-1200:</label>
									<div class="col-sm-8">
											<input value="" name="s_percent_5" id="s_percent_5" style="width:100%" class="form-control numeric" type="text" data-force-required="true" data-isrequired="true" maxlength="2">
									</div>
							</div>
							<div class="form-group">
									<label id="disc_1200_plus_caption" class="col-lg-4 col-sm-4 text-left">Taux discount 1200 et plus:</label>
									<div class="col-sm-8">
											<input value="" name="s_percent_6" id="s_percent_6" style="width:100%" class="form-control numeric" type="text" data-force-required="true" data-isrequired="true" maxlength="2">
									</div>
							</div>
					</div>
					<!-- END discount_div -->
				</div>
				<!--Start of Tab 4 -->
				<div class="tab-pane fade" id="tab_4" role="tabpanel" aria-labelledby="tab_4">
					<form id="studentUserForm" name="studentUserForm" class="form-horizontal" role="form"
					 action="{{!empty($student) ? route('student.user_update',[$student->id]): '/'}}?tab=tab_4" method="POST" enctype="multipart/form-data">
						@csrf
						<input type="hidden" id="active_tab_user" name="active_tab_user" value="">

						<input type="hidden" id="user_id" name="user_id" value="{{!empty($student->user->id) ? old('user_id', $student->user->id) : old('user_id')}}">
						<div class="section_header_class">
							<label id="course_for_billing_caption">{{ __('User Account')}}</label>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Name of User')}}:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="admin_username" name="admin_username" value="{{!empty($student->user->username) ? old('admin_username', $student->user->username) : old('admin_username')}}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Email')}}:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control" id="admin_email" name="admin_email" value="{{!empty($student->user->email) ? old('admin_email', $student->user->email) : old('admin_email')}}">
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
										<option value="1" {{!empty($student->user->is_active) ? (old('admin_is_active', $student->user->is_active) == 1 ? 'selected' : '') : (old('admin_is_active') == 1 ? 'selected' : '')}}>{{ __('Active')}}</option>
										<option value="0" {{!empty($student->user->is_active) ? (old('admin_is_active', $student->user->is_active) == 0 ? 'selected' : '') : (old('admin_is_active') == 0 ? 'selected' : '')}}>{{ __('Inactive')}}</option>
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
								<input type="text" class="form-control" id="email_to_id" name="email_to_id" value="{{!empty($student->user->email) ? $student->user->email : old('email_to_id')}}">

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
						<input type="hidden" name="selected_tax_ids" value="">
					</form>
				</div>
				<!--End of Tab 4 -->
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

let initPopulate=false;

$(document).ready(function(){
	$("#birth_date").on("focus", function() {
 $("#birth_date").datetimepicker({
        defaultDate: "01/07/2023 00:00:00",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
		format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
        minuteStep: 10,
        minView: 3,
        maxView: 3,
        viewSelect: 3,
      });
});
});

	/*
	* student province list
	* function @billing province
	*/
	$(document).ready(function(){
		var country_code = $('#country_code option:selected').val();
		var set_province = '<?= $student->province_id ?>';
		get_province_lists(country_code, set_province);
	});

	$('#country_code').change(function(){
		var country_code = $(this).val();
		var set_province = '<?= $student->province_id ?>';
		get_province_lists(country_code, set_province);
	})

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

	/*
	* Billing province list
	* function @billing province
	*/
	$('#billing_country_code').change(function(){
		var country_code = $(this).val();
		var set_province = '<?= $student->billing_province_id ?>';
		get_billing_province_lists(country_code, set_province);
	})

	$(document).ready(function(){
		var billing_country_code = $('#billing_country_code option:selected').val();
		var billing_province_id = '<?= $student->billing_province_id ?>';
		get_billing_province_lists(billing_country_code, billing_province_id);
	});

	function get_billing_province_lists(country_code, set_province){
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
						$('#billing_province_id').html(html);
						$('#billing_province_id_div').show();
				}else{
					$('#billing_province_id').html('');
					$('#billing_province_id_div').hide();
				}
			},
			error: function(e) {
				//error
			}
		});
	}

</script>

<script type="text/javascript">

$(document).ready(function(){

	$("#birth_date").datetimepicker({
        defaultDate: new Date(),
		format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
        minuteStep: 10,
        minView: 3,
        maxView: 3,
        viewSelect: 3,
		todayBtn:true,
      });

$("#country_code, #billing_country_code").trigger('change')

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
	$("#level_date_arp").datetimepicker({
		format: "yyyy-mm-dd",
		autoclose: true,
		todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
	$("#level_date_usp").datetimepicker({
		format: "yyyy-mm-dd",
		autoclose: true,
		todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
	$("#billing_period_start_date").datetimepicker({
		// locale: 'fr',
		format: "dd/mm/yyyy",
		autoclose: true,
		todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
		defaultDate:  moment().subtract(1, 'months').startOf('month')
	});
	$('#billing_period_start_date').val(moment().subtract(1, 'months').startOf('month').format('DD/MM/YYYY'));
	$("#billing_period_end_date").datetimepicker({
		//locale: 'fr',
		format: "dd/mm/yyyy",
		autoclose: true,
		todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
		defaultDate: moment()
	});
	$('#billing_period_end_date').val(moment().format('DD/MM/YYYY'));


	$('#billing_period_start_date').datetimepicker().on('dp.change', function (e) {
			var incrementDay = moment(new Date(e.date));
			incrementDay.add(0, 'days');
			$('#billing_period_end_date').data('DateTimePicker').minDate(incrementDay);
			$(this).data("DateTimePicker").hide();
	});

	$('#billing_period_end_date').datetimepicker().on('dp.change', function (e) {
			var decrementDay = moment(new Date(e.date));
			decrementDay.subtract(0, 'days');
			$('#billing_period_start_date').data('DateTimePicker').maxDate(decrementDay);
				$(this).data("DateTimePicker").hide();
	});


	CKEDITOR.replace( "body_text", {
		customConfig: '/ckeditor/config_email.js',
		height: 300
		,extraPlugins: 'Cy-GistInsert'
		,extraPlugins: 'AppFields'
	});
	function GetCheckBoxSelectedValues(p_chkbox) {
		var selected_events = '';
		var cboxes = document.getElementsByName(p_chkbox);
		var len = cboxes.length;

		$("input[name='" + p_chkbox + "']:checked").each(function(i) {
			selected_events += $(this).val() + ',';
		});
		return selected_events;
	}




	$('#btn_convert_invoice').click(function(e) {

		var selectedTaxIds = [];

		var sdiscountPercentInput = document.getElementById('sdiscount_percent_1');
		if(sdiscountPercentInput>100) {
			errorModalCall('Please change your discount percentage')
		}

		var checkboxes = document.querySelectorAll('.taxe_class');
		checkboxes.forEach(function(checkbox) {
			if (checkbox.checked) {
				var taxId = checkbox.dataset.id;
				selectedTaxIds.push(taxId);
			}
		});

		$(this).attr("disabled", "disabled");
		var p_event_ids = GetCheckBoxSelectedValues('event_check');
		if (p_event_ids == '') {
			errorModalCall('Please add lesson(s) or event(s) to continue...')
			return false;
		}



		var p_person_id = document.getElementById("person_id").value,
			school_id = document.getElementById("school_id").value
			//p_month = document.getElementById("smonth").value,
			//p_year = document.getElementById("syear").value;

		// var from_date = moment((p_year + '-' + p_month + '-01'), "YYYY-MM-DD").format("YYYY.MM.DD");
		// var to_date = moment((p_year + '-' + p_month + '-01'), "YYYY-MM-DD").endOf('month').format("YYYY.MM.DD");

		var from_date = moment(($("#billing_period_start_date").val()),"DD/MM/YYYY").format("YYYY.MM.DD");
		var to_date = moment(($("#billing_period_end_date").val()),"DD/MM/YYYY").format("YYYY.MM.DD");


		var p_invoice_id = '';
		var auto_id = 0;
		var inv_type=getUrlVarsO()["inv_type"];

		console.log('he', JSON.stringify(selectedTaxIds))
		var tax_ids = selectedTaxIds

		var sdiscountPercentInput = document.getElementById('sdiscount_percent_1');
		var discountPercentage = 0;
		if (sdiscountPercentInput) {
			discountPercentage = parseFloat(sdiscountPercentInput.value);
		}
		var finaltaxess = document.getElementById('total-taxes')
		var finaltotaltaxes = finaltaxess.textContent
		var totalAmountGet = document.getElementById('grand_total_amount')
		var totalAmountGet = parseFloat(totalAmountGet.textContent);
        var lesson_discount_description_get = document.getElementById('lesson_discount_description');
        var lesson_discount_description = lesson_discount_description_get.value
        //return console.log('yo', 'type=generate_student_invoice&school_id=' + school_id +'&p_person_id=' + p_person_id + '&p_invoice_id=' + p_invoice_id + '&p_from_date=' + from_date + '&p_to_date=' + to_date + '&p_event_ids=' + p_event_ids+'&inv_type=' + inv_type+'&selectedTaxIds=' + tax_ids+'&discountPercentage='+discountPercentage+'&finaltotaltaxes='+finaltotaltaxes + '&totalAmountGet=' + totalAmountGet)
	data = 'type=generate_student_invoice&school_id=' + school_id +'&p_person_id=' + p_person_id + '&p_invoice_id=' + p_invoice_id + '&p_from_date=' + from_date + '&p_to_date=' + to_date + '&p_event_ids=' + p_event_ids+'&inv_type=' + inv_type+'&selectedTaxIds=' + tax_ids+'&discountPercentage='+discountPercentage+'&finaltotaltaxes='+finaltotaltaxes + '&totalAmountGet=' + totalAmountGet + '&lesson_discount_description='+lesson_discount_description;

		$.ajax({
			url: BASE_URL + '/generate_student_invoice',

			//url: '../student/student_events_data.php',
			data: data,
			type: 'POST',
			dataType: 'json',
			async: false,
			success: function(result) {
				if (result.status == 'success') {
					auto_id = result.auto_id;

					successModalCall("{{ __('invoice generated')}}");

					//location.reload(); //commented by soumen divert to invoice screen.
				} else {
					//errorModalCall(result);
					// alert(value.status);
				}
			}, // success
			error: function(ts) {
				//errorModalCall(ts);
				console.log(ts)
				// alert(ts.responseText + ' Generate Invoice')
			}
		}); // Ajax

		if (auto_id > 0) {
			//admin/1/modification-invoice/42
			var url = "/admin/"+document.getElementById("school_id").value+"/modification-invoice/"+auto_id;
			setTimeout(function(){
				window.location = BASE_URL+ url;
			}, 1500);

			return false;
		}

		return false;


	});

	$("#send_email_btn").click(function (e) {
		var user_id = $("#user_id").val();
		var email_to = $("#email_to_id").val(),
				school_name = $("#school_name").val(),
				email_body  = CKEDITOR.instances["body_text"].getData()
		email_body = email_body.replace(/'/g, "''");
		email_body = email_body.replace(/&/g, "<<~>>");
		let loader = $('#pageloader');
    	loader.show();

		var schoolUserForm = document.getElementById("studentUserForm");
		var formdata = $("#studentUserForm").serializeArray();
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
				url: BASE_URL + '/student_email_send',
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
});

$(function() {
	var x = document.getElementsByClassName("tab-pane active");
		$('#active_tab').val(x[0].id);
		$('#active_tab_user').val(x[0].id);
	$('button[data-bs-toggle=tab]').click(function(e){
		var target = $(e.target).attr("data-bs-target_val") // activated tab
		$('#active_tab').val(target);
		$('#active_tab_user').val(target);
	});

	$('#bill_address_same_as').click(function(){
		if($(this).is(':checked')){
			$('#billing_place').val( $('#place').val() );
			$('#billing_street').val( $('#street').val() );
			// $('#billing_street2').val( $('#street2').val() );
			$('#billing_street_number').val( $('#street_number').val() );
			$('#billing_zip_code').val( $('#zip_code').val() );
			$('#billing_country_code').val( $('#country_code option:selected').val() );
			$('#billing_province_id').val( $('#province_id option:selected').val() );
		}
	});

	var saction= getUrlVarsO()["action"];
	console.log(saction)

	//For fetching the student details
	//document.getElementById("sperson_id").value=getUrlVarsO()["person_id"];
	//document.getElementById("saction").value=saction;
	//SetObjectsAccess(user_role);
	//PopulateMonthYearList();
	//PopulateMonthYearListForInv();
	//Fetch_student_info();
	//PopulateDiscountPerc();

	var vtab=getUrlVarsO()["tab"];
	if (typeof vtab === "undefined") {
		vtab='';
	}
	if (vtab == 'tab_3') {
		document.getElementById("delete_btn").style.display="none";
		document.getElementById("save_btn").style.display="none";
		activaTab('tab_3');
	} else {
		if (vtab != '') {
			activaTab(vtab);
		}
	}

	let no_of_teachers = document.getElementById("no_of_teachers").value;
	// if (document.getElementById("find_flag").value== "0"){
	// 	document.getElementById("delete_btn").style.display="none";
	// 	document.getElementById("save_btn").style.display="none";
	// }





	populate_student_lesson(); // refresh lesson details for billing
        $(document).on('click', '#billing_period_search_btn', function() {
		// document.getElementById("smonth").value = data_month;
		// document.getElementById("syear").value = data_year;
		populate_student_lesson(); // refresh lesson details for billing

	});


});



$('#save_btn').click(function (e) {
	var formData = $('#add_student').serializeArray();
	var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
	var error = '';
	$( ".form-control.require" ).each(function( key, value ) {
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
	if(error < 1){
		var x = document.getElementsByClassName("tab-pane active");
		$('#active_tab').val(x[0].id);
		$('#active_tab_user').val(x[0].id);
		var studentForm = document.getElementById("add_student");
		var studentUserForm = document.getElementById("studentUserForm");
		if (x[0].id == "tab_3") {
			if (studentUserForm.submit()){
				var url = window.location.href;
				url = addOrChangeParameters( url, {tab:x[0].id} );
				//window.location.href = url;
				return true;
			}
		} else{
			var url = window.location.href;
			url = addOrChangeParameters( url, {tab:x[0].id} );
			// console.log(url);
			// 	return false;

			studentForm.submit();
			return true;
		}
	}else{
		return false;
	}
});


	$('#profile_image_file').change(function(e) {
		var reader = new FileReader();
		reader.onload = function(e) {
			document.getElementById("frame").src = e.target.result;
		};
		reader.readAsDataURL(this.files[0]);
			$('#profile_image i.fa.fa-plus').hide();
		$('#profile_image i.fa.fa-close').show();
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


	function populate_student_lesson() {
		//var CURRENT_URL = '{{ $CURRENT_URL ?? '' }}';
		//console.log(CURRENT_URL);
		//location.href = "/1/edit-lesson/30/?redirect_url="+CURRENT_URL;
		let p_pending_only="1";
		var pendChBox = document.getElementById("chk_show_only_pend");


		if (pendChBox.checked == false) {
			p_pending_only="0";
		}

		// if ((user_role == 'student')) {
		// 	return false;
		// }
		var costs_1 = 0.00;
		var record_found = 0,
			all_ready = 1,
			total_buy = 0,
			total_sell = 0,
			week_total_buy = 0,
			week_total_sell = 0,
			total_disc = 0.00,
			prev_week = '',
			data = '',

			p_person_id = document.getElementById("person_id").value,
			school_id = document.getElementById("school_id").value,
			p_billing_period_start_date = document.getElementById("billing_period_start_date").value,
			p_billing_period_end_date = document.getElementById("billing_period_end_date").value;


		if ((p_billing_period_start_date == '') || (p_billing_period_end_date == '')) {
            if($(window).width() > 768){
                resultHtml = '<tbody><tr class="lesson-item-list-empty"> <td colspan="12">..</td></tr></tbody>';
				$('#lesson_table').html(resultHtml);
				document.getElementById("lesson_footer_div").style.display = "none";
				return false;
            }
				else {
            resultHtml = '';
				$('#lesson_table').html(resultHtml);
				document.getElementById("lesson_footer_div").style.display = "none";
				return false;
                }
		}

		var invoice_already_generated = 0,
		person_type = 'student_lessons',
		selected_items = 0;

		var disc1_amt = 0,
		disc2_amt = 0,
		disc3_amt = 0,
		disc4_amt = 0,
		disc5_amt = 0,
		disc6_amt = 0;

		var correct_btn_text = 'Validate';

		var resultHtml = '',
			resultHtmlHeader = '',
			resultHtmlFooter = '',
			resultHtmlDetails = '';
		var amount_for_disc=0.00;
		var disc_caption = 'DESC';
		var disc_caption_disp = '';
		var week_caption = 'Weekly';
		var month_caption = 'Monthly';
		var sub_total_caption = 'Sub Total';
		var isTeacher = +"{{$AppUI->isTeacher()}}";
		var inv_type=getUrlVarsO()["inv_type"]

		var subTotalLessons = 0;
		var subTotalEvents = 0;
		var subTotalEventsExtra = 0;
		var totalMinutesLessons = 0;
		var currencyTotal = 'USD';

		//resultHtml='<tr><td colspan="8"><font color="blue"><h5> Cours disponibles à la facturation</h5></font></tr>';
		data = 'type=' + person_type + '&school_id=' + school_id + '&p_person_id=' + p_person_id + '&p_billing_period_start_date='+p_billing_period_start_date+'&p_billing_period_end_date=' + p_billing_period_end_date+'&p_pending_only='+p_pending_only+'&inv_type=' + inv_type;
		console.log(data);
		$.ajax({
			url: BASE_URL + '/get_student_lessons',
			//url: '../student/student_events_data.php',
			data: data,
			type: 'POST',
			dataType: 'json',
			async: false,
			success: function(result) {

						// Initialise les tableaux pour les événements et les leçons
						var events = [];
						var lessons = [];

						// Sépare les événements et les leçons en fonction de leur type
						for (var i = 0; i < result.data.length; i++) {
							var item = result.data[i];
							if (item.event_type === 100) {
								events.push(item);
							} else if (item.event_type === 10) {
								lessons.push(item);
							}
						}

						var justOneWeekLabel = 0;

					//List lessons
					$.each(lessons, function(key, value) {
							record_found += 1;
							// week summary
							//if ((prev_week != '') && (prev_week != value.week_name)) {
							//		resultHtml += '<tr style="font-weight: bold;"><td colspan="6">';
							//		resultHtml += '<td colspan="2">' + sub_total_caption + ' ' + week_caption + ' </td>';
							//		// alert('no_of_teachers='+no_of_teachers);
							//		if (no_of_teachers == 1){
							//				resultHtml += '<td style="text-align:right"></td>';
							//		}else {
							//				if (!isTeacher) {
							//					resultHtml += '<td style="text-align:right">' + week_total_buy.toFixed(2) + '</td>';
							//				}
							//			}
		//
							//		resultHtml += '<td style="text-align:right">' + week_total_sell.toFixed(2) + '</td>';
							//		resultHtml += '</tr>'
							//		week_total_buy = 0;
							//		week_total_sell = 0;
							//}

							if (prev_week != value.week_name) {

								if(justOneWeekLabel == 0) {

									//resultHtml+='<b><tr class="course_week_header"><td colspan="10">'+week_caption+' '+value.week_no+'</tr></b>';
									resultHtml += '<b><tr class="course_week_header table_header_invoice"><td colspan="1"><span style="font-size:11px;">[ LESSON ]</span><br><i class="fa-solid fa-calendar-check"></i> ' + week_caption + ' ' + value.week_no + '</td>';
									resultHtml += '<b><td colspan="1">' + '' + '</td>';
									resultHtml += '<b><td class="h6 pt-3" colspan="1"><b>Date</b></td>';
									resultHtml += '<b><td class="h6 pt-3" colspan="1"><b>Time</b></td>';
									resultHtml += '<b><td class="h6 pt-3" colspan="1"><b>Duration</b></td>';
									resultHtml += '<b><td class="h6 pt-3" colspan="1"><b>Category</b></td>';
									resultHtml += '<b><td class="h6 pt-3" colspan="1"><b>Teacher</b></td>';
									resultHtml += '<b><td class="h6 pt-3" colspan="1"><b>Lesson</b></td>';



									//resultHtml+='<b><td style="text-align:center" colspan="2">'+value.price_currency+'</td>';
									if (result.no_of_teachers == 1){
										resultHtml += '<b><td class="h6 pt-3" style="text-align:right" colspan="1">' + '' + '</td>';
										resultHtml += '<b><td class="h6 pt-3" style="text-align:right" colspan="1"><b>Price</b></td>';
									} else {
										if (!isTeacher) {
											resultHtml += '<b><td class="h6 pt-3" style="text-align:right" colspan="1"><b>Teacher Price</b></td>';
										}
										resultHtml += '<b><td class="h6 pt-3" style="text-align:right" colspan="1"><b>Student Price</b></td>';
									}


									resultHtml += '<td class="h6 pt-3" style="text-align:right" colspan="1">Extra Charges</td></tr></b>';
								} else {

									//resultHtml+='<b><tr class="course_week_header"><td colspan="10">'+week_caption+' '+value.week_no+'</tr></b>';
										resultHtml += '<b><tr class="course_week_header table_header_invoice"><td colspan="1"><span style="font-size:11px;">[ LESSON ]</span><br><i class="fa-solid fa-calendar-check"></i> ' + week_caption + ' ' + value.week_no + '</td>';
									resultHtml += '<b><td colspan="1">' + '' + '</td>';
									resultHtml += '<b><td colspan="1"></td>';
									resultHtml += '<b><td colspan="1"></td>';
									resultHtml += '<b><td colspan="1"></td>';
									resultHtml += '<b><td colspan="1"></td>';
									resultHtml += '<b><td colspan="1"></td>';
									resultHtml += '<b><td colspan="1"></td>';



									//resultHtml+='<b><td style="text-align:center" colspan="2">'+value.price_currency+'</td>';
									if (result.no_of_teachers == 1){
										resultHtml += '<b><td style="text-align:right" colspan="1">' + '' + '</td>';
										resultHtml += '<b><td style="text-align:right" colspan="1"></td>';
									} else {
										if (!isTeacher) {
											resultHtml += '<b><td style="text-align:right" colspan="1"></td>';
										}
										resultHtml += '<b><td style="text-align:right" colspan="1"></td>';
									}


									resultHtml += '<td style="text-align:right" colspan="1"></td></tr></b>';
								}
									justOneWeekLabel = justOneWeekLabel + 1;

							}

							resultHtml += '<tr>';

							//resultHtml += '<td style="display:none;">' + value.event_id + '</td>';

							if ((value.is_sell_invoiced == 0) && (value.ready_flag == 1)) {
									selected_items += 1;
									resultHtml += "<td><input class='lesson_class' data-amount='"+(value.sell_price+value.extra_charges).toFixed(2)+"' type=checkbox id='event_check' name='event_check' checked value=" + value.event_id + "></td>";
							} else {
									resultHtml += "<td>-</td>";
							}

							//below locked and invoiced

							if (value.ready_flag == 1) {
								resultHtml += "<td>";
									resultHtml += "<i class='fa fa-lock'></i> ";
                                    if($(window).width() < 768){
                                        resultHtml += " is locked to validate";
                                    }
									resultHtml += "</td>";
							} else {
								resultHtml += "<td>";
									resultHtml += "-";
									resultHtml += "</td>";
							}
							//if (value.is_sell_invoiced > 0) {
									//comments as Kim as per Sportlogin Before the app.doc
									//resultHtml += "<em class='glyphicon glyphicon glyphicon-print'></em>";
							//}

							//above locked and invoiced

							resultHtml += '<td width="10%">' + value.date_start + '</td>';
							if (value.duration_minutes == 0) {
								resultHtml += '<td style="text-align:center" colspan="2">' + GetAppMessage('allday_event_caption') + '</td>';
							}else {
								resultHtml += '<td>' + value.time_start + '</td>';
								resultHtml += '<td>' + value.duration_minutes + ' minutes </td>';
								totalMinutesLessons = totalMinutesLessons + value.duration_minutes;
							}
							if (value.event_type == 100) {
								if (value.title != '' && value.title != null) {
									resultHtml += '<td>Event : '+value.title+'</td>';
								}else{
									resultHtml += '<td>Event</td>';
								}
							} else {
								resultHtml += '<td>' + value.category_name + '</td>';
							}

							resultHtml += '<td>' + value.teacher_name + '</td>';
							if (value.event_type == 100) {
								if (value.count_name > 1) {
									resultHtml += '<td>Group Event for '+value.count_name+' Student(s)</td>';
								}
								else{
									resultHtml += '<td>Event</td>';
								}
							} else {
								if (value.count_name > 1) {
									resultHtml += '<td>Group Lessons for '+value.count_name+' Student(s)</td>';
								}
								else{
									resultHtml += '<td>Private Lesson</td>';
								}

							}

							//resultHtml += '<td>' + value.title + '</td>';

							// all_ready = 0 means not ready to generate invoice
							//var icon  ='<img src="../images/icons/locked.gif" width="12" height="12"/>';
							if (value.ready_flag == 0) {
								//all_ready = 0;
								if (!isTeacher) {
									resultHtml += "<td></td>";
								}

							var myTimezone = "{{ $school->timezone }}";
							var TheDateStart = moment(value.date_start, "DD/MM/YYYY").format("YYYY-MM-DD") + ' ' + value.time_start + ':00';
							var TheDateEnd = moment(value.date_end, "DD/MM/YYYY").format("YYYY-MM-DD") + ' ' + value.time_end + ':00';

							var eventStart = moment.utc(TheDateStart + ' ' + value.time_start, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone);
							var eventEnd = moment.utc(TheDateEnd + ' ' + value.time_end, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone);
							var now = moment().tz(myTimezone).format('YYYY-MM-DDTHH:mm:00');
							const eventStartTimeStamp = moment.utc(TheDateStart + ' ' + value.time_start, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone).valueOf();
							const eventEndTimeStamp = moment.utc(TheDateEnd + ' ' + value.time_end, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone).valueOf();
							const nowTimeStamp =  moment.utc(now, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone).valueOf();

							if (eventStart.isBefore(now)) {
								if (value.event_type == 100) {
									resultHtml += "<td><a id='correct_btn' class='button_lock_and_save' href='/"+school_id+"/edit-event/"+value.event_id+"/?redirect_url="+CURRENT_URL+"' class='btn btn-xs btn-info'> <em class='glyphicon glyphicon-pencil'></em>" + correct_btn_text + "</a>";
								} else {
									resultHtml += "<td><a id='correct_btn' class='button_lock_and_save' href='/"+school_id+"/edit-lesson/"+value.event_id+"/?redirect_url="+CURRENT_URL+"' class='btn btn-xs btn-info'> <em class='glyphicon glyphicon-pencil'></em>" + correct_btn_text + "</a>";
								}
							} else {
								var timeBetween = timeDifference(eventStartTimeStamp, nowTimeStamp);
                    			var phrase = "Available in " + timeBetween;
								resultHtml += '<td style="text-align:right; font-size:11px;">('+phrase+')</td>';
							}

							} else {
								if (no_of_teachers == 1){
										resultHtml += '<td style="text-align:right"></td>';
								}else {
									if (value.event_type!=100 && value.cat_invoice_type=='T') {
										value.buy_price = value.sell_price;
									}
									else if (value.event_type!=10 && value.event_invoice_type=='T') {
										value.buy_price = value.sell_price;
									}
									else{
										value.buy_price = value.buy_price;
									}
									if (!isTeacher) {
										resultHtml += '<td style="text-align:right">' + value.price_currency + ' <b>' + value.buy_price.toFixed(2) + '</b></td>';

									}
								}
								currencyTotal = value.price_currency;
								resultHtml += '<td style="text-align:right">' + value.price_currency + ' <b>' + value.sell_price.toFixed(2) + '</b></td>';
								//total_buy += value.buy_price;
								//total_sell += value.sell_price + value.extra_charges;

								//week_total_buy += value.buy_price;
								//week_total_sell += value.sell_price + value.extra_charges;


								if (value.event_type == 10) {
									amount_for_disc=amount_for_disc+value.sell_price;
									subTotalLessons = subTotalLessons + value.sell_price;
								}
							}

							costs_1 = value.extra_charges;
							if (value.extra_charges != 0) {
									resultHtml += '<td style="text-align:right">+' + costs_1.toFixed(2) + '</td>';
							} else {
									resultHtml += '<td style="text-align:right"></td>';
							}
							resultHtml += '</tr>';


							prev_week = value.week_name;

							if (person_type == 'student_lessons') {
									if (value.is_sell_invoiced != 0) {
											//invoice_already_generated=1;  //commented by soumen to display items which has been generated already
									} else {

									}
							}

                            if($(window).width() < 768){
                                resultHtml += '<tr><td colspan="8"><hr></td></tr>';
                            }


					}); //for each record


					if(totalMinutesLessons>0) {
						resultHtml += '<tr style="background-color:#EEE; height:80px;"><td colspan="5" style="text-align:right;"><br><b>Total Lessons duration <i class="fa-solid fa-arrow-right"></i></b> <b>'+totalMinutesLessons+' minutes</b></td><td colspan="4" style="text-align:right;"><br><b>Sub-Total Lessons </b> <i class="fa-solid fa-arrow-right"></i> '+currencyTotal+' <b id="ssubtotal_amount_with_discount_lesson">'+subTotalLessons.toFixed(2)+'</b><br><br></td><td></td></tr>';

					//Lesson Discount
					resultHtml += '<tr>';
					resultHtml += '<td colspan="7" style="text-align:right">Discount(%) on Lessons:</td>';
					resultHtml += '<td style="text-align:right"></td>';
					resultHtml += '<td style="text-align:right">';
					resultHtml += '<input type="text" class="form-control numeric" id="sdiscount_percent_1" name="sdiscount_percent_1" style="text-align:right; padding-right: 5px;" value="0" placeholder="">';
					resultHtml += '</td>';
					resultHtml += '<td></td>';
					resultHtml += '</tr>';
					resultHtml += '<tr>';
					resultHtml += '<td colspan="7" style="text-align:right">Discount Amount:</td>';
					resultHtml += '<td style="text-align:right"></td>';
					resultHtml += '<td style="text-align:right">';
					resultHtml += '<input type="text" class="form-control numeric_amount" id="samount_discount_1" name="samount_discount_1" style="text-align:right; padding-right: 5px;" value="0" placeholder="">';
					resultHtml += '</td>';
					resultHtml += '<td></td>';
					resultHtml += '</tr>';

                    resultHtml += '<tr>';
					resultHtml += '<td colspan="7" style="text-align:right">Description:</td>';
					resultHtml += '<td style="text-align:right"></td>';
					resultHtml += '<td style="text-align:right">';
					resultHtml += '<textarea type="text" class="form-control" id="lesson_discount_description" name="lesson_discount_description" placeholder="Description"></textarea>';
					resultHtml += '</td>';
					resultHtml += '<td></td>';
					resultHtml += '</tr>';




					resultHtml += '<tr style="background-color:#EEE; height:80px;"><td colspan="4" style="text-align:right;"></td><td style="text-align:left;"></td><td colspan="4" style="text-align:right;"><br><b>Total Lessons</b> <i class="fa-solid fa-arrow-right"></i> '+currencyTotal+' <b><span id="ssubtotal_amount_with_discount">'+subTotalLessons.toFixed(2)+'</span></b></td><td></td></tr>';

					} else { resultHtml += '<span style="display:none;" id="ssubtotal_amount_with_discount_lesson">0</span>' }

					justOneWeekLabel = 0;

					$.each(events, function(key, value) {
							record_found += 1;
							// week summary
							//if ((prev_week != '') && (prev_week != value.week_name)) {
							//		resultHtml += '<tr style="font-weight: bold;"><td colspan="6">';
							//		resultHtml += '<td colspan="2">' + sub_total_caption + ' ' + week_caption + ' </td>';
							//		// alert('no_of_teachers='+no_of_teachers);
							//		if (no_of_teachers == 1){
							//				resultHtml += '<td style="text-align:right"></td>';
							//		}else {
							//				if (!isTeacher) {
							//					resultHtml += '<td style="text-align:right">' + week_total_buy.toFixed(2) + '</td>';
							//				}
							//			}
		//
							//		resultHtml += '<td style="text-align:right">' + week_total_sell.toFixed(2) + '</td>';
							//		resultHtml += '</tr>'
							//		week_total_buy = 0;
							//		week_total_sell = 0;
							//}

							if (prev_week != value.week_name) {

								if(justOneWeekLabel == 0) {

									//resultHtml+='<b><tr class="course_week_header"><td colspan="10">'+week_caption+' '+value.week_no+'</tr></b>';
									resultHtml += '<b><tr class="course_week_header table_header_invoice"><td colspan="1"><span style="font-size:11px;">[ EVENT ]</span><br><i class="fa-solid fa-calendar-check"></i> ' + week_caption + ' ' + value.week_no + '</td>';
									resultHtml += '<b><td colspan="1">' + '' + '</td>';
									resultHtml += '<b><td class="h6 pt-3" colspan="1"><b>Date</b></td>';
									resultHtml += '<b><td class="h6 pt-3" colspan="1"><b>Time</b></td>';
									resultHtml += '<b><td class="h6 pt-3" colspan="1"><b>Duration</b></td>';
									resultHtml += '<b><td class="h6 pt-3" colspan="1"><b>Category</b></td>';
									resultHtml += '<b><td class="h6 pt-3" colspan="1"><b>Teacher</b></td>';
									resultHtml += '<b><td class="h6 pt-3" colspan="1"><b>Lesson</b></td>';

									//resultHtml+='<b><td style="text-align:center" colspan="2">'+value.price_currency+'</td>';
									if (result.no_of_teachers == 1){
										resultHtml += '<b><td style="text-align:right" colspan="1">' + '' + '</td>';
										resultHtml += '<b><td class="h6 pt-3" style="text-align:right" colspan="1"><b>Price/<b></td>';
									} else {
										if (!isTeacher) {
											resultHtml += '<b><td class="h6 pt-3" style="text-align:right" colspan="1"><b>Teacher Price</b></td>';
										}
										resultHtml += '<b><td class="h6 pt-3" style="text-align:right" colspan="1"><b>Student Price</b></td>';
									}


									resultHtml += '<td class="h6 pt-3" style="text-align:right" colspan="1">Extra Charges</td></tr></b>';
								} else {

								//resultHtml+='<b><tr class="course_week_header"><td colspan="10">'+week_caption+' '+value.week_no+'</tr></b>';
								resultHtml += '<b><tr class="course_week_header table_header_invoice"><td colspan="1"><span style="font-size:11px;">[ EVENT ]</span><br><i class="fa-solid fa-calendar-check"></i> ' + week_caption + ' ' + value.week_no + '</td>';
								resultHtml += '<b><td colspan="1">' + '' + '</td>';
								resultHtml += '<b><td colspan="1"></td>';
								resultHtml += '<b><td colspan="1"></td>';
								resultHtml += '<b><td colspan="1"></td>';
								resultHtml += '<b><td colspan="1"></td>';
								resultHtml += '<b><td colspan="1"></td>';
								resultHtml += '<b><td colspan="1"></td>';



								//resultHtml+='<b><td style="text-align:center" colspan="2">'+value.price_currency+'</td>';
								if (result.no_of_teachers == 1){
									resultHtml += '<b><td style="text-align:right" colspan="1">' + '' + '</td>';
									resultHtml += '<b><td style="text-align:right" colspan="1"></td>';
								} else {
									if (!isTeacher) {
										resultHtml += '<b><td style="text-align:right" colspan="1"></td>';
									}
									resultHtml += '<b><td style="text-align:right" colspan="1"></td>';
								}


								resultHtml += '<td style="text-align:right" colspan="1"></td></tr></b>';
								}

								justOneWeekLabel = justOneWeekLabel + 1;

							}
							resultHtml += '<tr>';

							resultHtml += '<td style="display:none;">' + value.event_id + '</td>';

							if ((value.is_sell_invoiced == 0) && (value.ready_flag == 1)) {
									selected_items += 1;
									resultHtml += "<td><input class='event_class' data-amount='"+(value.sell_price).toFixed(2)+"' data-extra='"+(value.extra_charges).toFixed(2)+"' type=checkbox id='event_check' name='event_check' checked value=" + value.event_id + "></td>";
							} else {
									resultHtml += "<td>-</td>";
							}

							//below locked and invoiced

							if (value.ready_flag == 1) {
								resultHtml += "<td>";
									resultHtml += "<i class='fa fa-lock'></i> ";
									resultHtml += "</td>";
							} else {
								resultHtml += "<td>";
									resultHtml += "-";
									resultHtml += "</td>";
							}
							//if (value.is_sell_invoiced > 0) {
									//comments as Kim as per Sportlogin Before the app.doc
									//resultHtml += "<em class='glyphicon glyphicon glyphicon-print'></em>";
							//}

							//above locked and invoiced

							resultHtml += '<td width="10%">' + value.date_start + '</td>';
							if (value.duration_minutes == 0) {
								resultHtml += '<td style="text-align:center" colspan="2">' + GetAppMessage('allday_event_caption') + '</td>';
							}else {
								resultHtml += '<td>' + value.time_start + '</td>';
								resultHtml += '<td>' + value.duration_minutes + ' minutes </td>';
							}
							if (value.event_type == 100) {
								if (value.title != '' && value.title != null) {
									resultHtml += '<td>Event : '+value.title+'</td>';
								}else{
									resultHtml += '<td>Event</td>';
								}
							} else {
								resultHtml += '<td>' + value.category_name + '</td>';
							}

							resultHtml += '<td>' + value.teacher_name + '</td>';
							if (value.event_type == 100) {
								if (value.count_name > 1) {
									resultHtml += '<td>Group Event for '+value.count_name+' Student(s)</td>';
								}
								else{
									resultHtml += '<td>Event</td>';
								}
							} else {
								if (value.count_name > 1) {
									resultHtml += '<td>Group Lessons for '+value.count_name+' Student(s)</td>';
								}
								else{
									resultHtml += '<td>Private Lesson</td>';
								}

							}

							//resultHtml += '<td>' + value.title + '</td>';

							// all_ready = 0 means not ready to generate invoice
							//var icon  ='<img src="../images/icons/locked.gif" width="12" height="12"/>';
							if (value.ready_flag == 0) {
								//all_ready = 0;
								if (!isTeacher) {
									resultHtml += "<td></td>";
								}

							var myTimezone = "{{ $school->timezone }}";
							var TheDateStart = moment(value.date_start, "DD/MM/YYYY").format("YYYY-MM-DD") + ' ' + value.time_start + ':00';
							var TheDateEnd = moment(value.date_end, "DD/MM/YYYY").format("YYYY-MM-DD") + ' ' + value.time_end + ':00';

							var eventStart = moment.utc(TheDateStart + ' ' + value.time_start, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone);
							var eventEnd = moment.utc(TheDateEnd + ' ' + value.time_end, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone);
							var now = moment().tz(myTimezone).format('YYYY-MM-DDTHH:mm:00');
							const eventStartTimeStamp = moment.utc(TheDateStart + ' ' + value.time_start, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone).valueOf();
							const eventEndTimeStamp = moment.utc(TheDateEnd + ' ' + value.time_end, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone).valueOf();
							const nowTimeStamp =  moment.utc(now, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone).valueOf();

							if (eventStart.isBefore(now)) {
								if (value.event_type == 100) {
									resultHtml += "<td><a id='correct_btn' class='button_lock_and_save' href='/"+school_id+"/edit-event/"+value.event_id+"/?redirect_url="+CURRENT_URL+"' class='btn btn-xs btn-info'> <em class='glyphicon glyphicon-pencil'></em>" + correct_btn_text + "</a>";
								} else {
									resultHtml += "<td><a id='correct_btn' class='button_lock_and_save' href='/"+school_id+"/edit-lesson/"+value.event_id+"/?redirect_url="+CURRENT_URL+"' class='btn btn-xs btn-info'> <em class='glyphicon glyphicon-pencil'></em>" + correct_btn_text + "</a>";
								}
							} else {
								var timeBetween = timeDifference(eventStartTimeStamp, nowTimeStamp);
                    			var phrase = "Available in " + timeBetween;
								resultHtml += '<td style="text-align:right; font-size:11px;">('+phrase+')</td>';
							}

							} else {
								if (no_of_teachers == 1){
										resultHtml += '<td style="text-align:right"></td>';
								}else {
									if (value.event_type!=100 && value.cat_invoice_type=='T') {
										value.buy_price = value.sell_price;
									}
									else if (value.event_type!=10 && value.event_invoice_type=='T') {
										value.buy_price = value.sell_price;
									}
									else{
										value.buy_price = value.buy_price;
									}
									if (!isTeacher) {
										resultHtml += '<td style="text-align:right">' + value.price_currency + ' ' + value.buy_price.toFixed(2) + '</td>';

									}
								}
								resultHtml += '<td style="text-align:right">' + value.price_currency + ' <b>' + value.sell_price.toFixed(2) + '</b></td>';
								total_buy += value.buy_price;
								total_sell += value.sell_price + value.extra_charges;
								subTotalEvents = subTotalEvents + value.buy_price;
								subTotalEventsExtra = subTotalEventsExtra + value.extra_charges;

								week_total_buy += value.buy_price;
								week_total_sell += value.sell_price + value.extra_charges;


								if (value.event_type == 10) {
									amount_for_disc=amount_for_disc+value.sell_price;
								}
							}

							costs_1 = value.extra_charges;
							if (value.extra_charges != 0) {
									resultHtml += '<td style="text-align:right">+' + costs_1.toFixed(2) + '</td>';
							} else {
									resultHtml += '<td style="text-align:right"></td>';
							}
							resultHtml += '</tr>';


							prev_week = value.week_name;

							if (person_type == 'student_lessons') {
									if (value.is_sell_invoiced != 0) {
											//invoice_already_generated=1;  //commented by soumen to display items which has been generated already
									} else {

									}
							}
					});


					if(subTotalEvents > 0) {
						resultHtml += '<tr style="background-color:#EEE; height:80px;"><td colspan="4" style="text-align:right;"></td><td style="text-align:left;"></td><td colspan="4" style="text-align:right;"><br><b>Total Events</b> <i class="fa-solid fa-arrow-right"></i> '+currencyTotal+' <b><span id="stotal_amount_with_discount_event">'+subTotalEvents.toFixed(2)+'</span></b></td><td></td></tr>';
					} else { resultHtml += '<span style="display:none;" id="stotal_amount_with_discount_event">0</span>'; }


			}, // success
			error: function(ts) {
				//errorModalCall(GetAppMessage('error_message_text'));
				console.log(ts.responseText + ' populate_student_lesson')
			}
		}); // Ajax










		if (record_found > 0) {

				// summary for last week of course records
				//if ((week_total_buy > 0) || (week_total_sell > 0)) {
				//
				//		resultHtml += '<tr style="font-weight: bold;"><td colspan="6">';
				//		resultHtml += '<td colspan="2">' + sub_total_caption + ' ' + week_caption + ' </td>';
//
				//		if (no_of_teachers == 1){
				//			resultHtml += '<td style="text-align:right"></td>';
				//		}else {
				//			if (!isTeacher) {
				//				resultHtml += '<td style="text-align:right">' + week_total_buy.toFixed(2) + '</td>';
				//
				//			}
				//		}
//
				//		resultHtml += '<td style="text-align:right">' + (subTotalLessons + subTotalEvents).toFixed(2) + '</td>';
//
				//		resultHtml += '</tr>'
				//		week_total_buy = 0;
				//		week_total_sell = 0;
				//}

				// display grand total
				resultHtml += '<tr><td colspan="6">';
                    if($(window).width() < 768){
                        resultHtml += '<td colspan="2" style="text-align:right; font-weight: bold;"><br>' + sub_total_caption + ': ';
                    }
                    else {
                        resultHtml += '<td colspan="2" style="text-align:right; font-weight: bold;"><br>' + sub_total_caption + ':</td>';
                    }

				if (no_of_teachers == 1){
				resultHtml += '<td style="text-align:right"></td>';
				}else {
					if (!isTeacher) {
						resultHtml += '<td style="text-align:right; font-weight: bold;"><br>' + total_buy.toFixed(2) + '</td>';
					}
				}

                if($(window).width() < 768){
			    resultHtml += ''+currencyTotal+' <span style="font-weight: bold;" id="stotal_amount_with_discount">' + (subTotalLessons + subTotalEvents).toFixed(2) + '</span><br><br></td>';
                } else {
                    resultHtml += '<td style="text-align:right"><br>'+currencyTotal+' <span style="font-weight: bold;" id="stotal_amount_with_discount">' + (subTotalLessons + subTotalEvents).toFixed(2) + '</span></td>';
                }

				resultHtml += '</tr>'




				var RegisterTaxData = @json($RegisterTaxData);

				var totalTaxAmount = 0;
				var sub_total_lesson = (subTotalLessons + subTotalEvents).toFixed(2);

				RegisterTaxData.forEach(function(tax) {
					totalTaxAmount += sub_total_lesson * parseFloat(tax.tax_percentage) / 100;
				});

				var grandTotal = totalTaxAmount > 0 ? sub_total_lesson + totalTaxAmount : sub_total_lesson;

				RegisterTaxData.forEach(function(tax) {
                    if($(window).width() < 768){
						resultHtml += '<tr style="background-color:#EEE;">' +
					'<td><input id="checkbox-'+tax.id+'" class="taxe_class" type="checkbox" data-amount="' + (sub_total_lesson * parseFloat(tax.tax_percentage) / 100).toFixed(2) + '" data-percentage="' + tax.tax_percentage + '" data-id="' + tax.id + '" checked> ' +
				'tax ' + tax.tax_name + ' (' + tax.tax_percentage + '%)' +
					' => <b>+<span id="cap_tax_' + tax.id + '">' + (sub_total_lesson * parseFloat(tax.tax_percentage) / 100).toFixed(2) + '</span></b><br></td>' +
					'<td></td>' +
					'</tr>';
                        } else {
                            resultHtml += '<tr style="background-color:#EEE;">' +
					'<td><input id="checkbox-'+tax.id+'" class="taxe_class" type="checkbox" data-amount="' + (sub_total_lesson * parseFloat(tax.tax_percentage) / 100).toFixed(2) + '" data-percentage="' + tax.tax_percentage + '" data-id="' + tax.id + '" checked></td>' +
					'<td colspan="7" style="text-align:right">tax ' + tax.tax_name + ' (' + tax.tax_percentage + '%)</td>' +
					'<td colspan="1" style="text-align:right"><b>+<span id="cap_tax_' + tax.id + '">' + (sub_total_lesson * parseFloat(tax.tax_percentage) / 100).toFixed(2) + '</span></b></td>' +
					'<td></td>' +
					'</tr>';
                        }
        		});

				var disc1_perc = 0,
						disc2_perc = 0,
						disc3_perc = 0,
						disc4_perc = 0,
						disc5_perc = 0,
						disc6_perc = 0;
				var amt_for_disc = 0;
				// calculate discount if total sell amount >0
				if (amount_for_disc > 0) {

						disc1_perc = document.getElementById("s_percent_1").value;
						disc2_perc = document.getElementById("s_percent_2").value;
						disc3_perc = document.getElementById("s_percent_3").value;
						disc4_perc = document.getElementById("s_percent_4").value;
						disc5_perc = document.getElementById("s_percent_5").value;
						disc6_perc = document.getElementById("s_percent_6").value;


						//disc slab 201-400
						if (disc1_perc > 0) {
								if (amount_for_disc > 400) {
										amt_for_disc = 200;
								} else {
										amt_for_disc = amount_for_disc - 200;
								}
								if (amt_for_disc > 0) {
										disc1_amt = ((amt_for_disc * disc1_perc) / 100);
								}

						}

						//disc slab 401-600
						if (disc2_perc > 0) {
								if (amount_for_disc > 600) {
										amt_for_disc = 200;
								} else {
										amt_for_disc = amount_for_disc - 400;
								}
								if (amt_for_disc > 0) {
										disc2_amt = ((amt_for_disc * disc2_perc) / 100);
								}
						}

						//disc slab 601-800
						if (disc3_perc > 0) {
								if (amount_for_disc > 800) {
										amt_for_disc = 200;
								} else {
										amt_for_disc = amount_for_disc - 600;
								}
								if (amt_for_disc > 0) {
										disc3_amt = ((amt_for_disc * disc3_perc) / 100);
								}
						}

						//disc slab 801-1000
						if (disc4_perc > 0) {
								if (amount_for_disc > 1000) {
										amt_for_disc = 200;
								} else {
										amt_for_disc = total_sell - 800;
								}
								if (amt_for_disc > 0) {
										disc4_amt = ((amt_for_disc * disc4_perc) / 100);
								}
						}

						//disc slab 1001-1200
						if (disc5_perc > 0) {
								if (amount_for_disc > 1200) {
										amt_for_disc = 200;
								} else {
										amt_for_disc = amount_for_disc - 1000;
								}
								if (amt_for_disc > 0) {
										disc5_amt = ((amt_for_disc * disc5_perc) / 100);
								}
						}

						//disc slab 1200>
						if (disc6_perc > 0) {
								amt_for_disc = amount_for_disc - 1200;
								if (amt_for_disc > 0) {
										disc6_amt = ((amt_for_disc * disc5_perc) / 100);
								}
						}

						if (disc1_amt > 0) {
								disc_caption_disp = GetDiscPercCaption(1, disc1_perc, disc1_amt, 0);
								//resultHtml+='<tr><td colspan="8">Réduction de '+disc1_perc+'% sur tranche 201.00 à 400.00 soit -'+disc1_amt.toFixed(2)+'</tr>';
								resultHtml += '<tr><td colspan="8">' + disc_caption_disp + '</tr>';

						}
						if (disc2_amt > 0) {
								disc_caption_disp = GetDiscPercCaption(2, disc2_perc, disc2_amt, 0);
								resultHtml += '<tr><td colspan="8">' + disc_caption_disp + '</tr>';

								//resultHtml+='<tr><td colspan="8">Réduction de '+disc2_perc+'% sur tranche 401.00 à 600.00 soit -'+disc2_amt.toFixed(2)+'</tr>';
						}
						if (disc3_amt > 0) {
								disc_caption_disp = GetDiscPercCaption(3, disc3_perc, disc3_amt, 0);
								resultHtml += '<tr><td colspan="8">' + disc_caption_disp + '</tr>';
								//resultHtml+='<tr><td colspan="8">Réduction de '+disc3_perc+'% sur tranche 601.00 à 800.00 soit -'+disc3_amt.toFixed(2)+'</tr>';
						}
						if (disc4_amt > 0) {
								disc_caption_disp = GetDiscPercCaption(4, disc4_perc, disc4_amt, 0);
								resultHtml += '<tr><td colspan="8">' + disc_caption_disp + '</tr>';
								//resultHtml+='<tr><td colspan="8">Réduction de '+disc4_perc+'% sur tranche 801.00 à 1000.00 soit -'+disc4_amt.toFixed(2)+'</tr>';
						}
						if (disc5_amt > 0) {
								disc_caption_disp = GetDiscPercCaption(5, disc5_perc, disc5_amt, 0);
								resultHtml += '<tr><td colspan="8">' + disc_caption_disp + '</tr>';
								//resultHtml+='<tr><td colspan="8">Réduction de '+disc5_perc+'% sur tranche 1001.00 à 1200.00 soit -'+disc5_amt.toFixed(2)+'</tr>';
						}
						if (disc6_amt > 0) {
								disc_caption_disp = GetDiscPercCaption(6, disc6_perc, disc6_amt, total_sell);

								/*var str1 = disc_caption_disp.split('[~~SYSTEM_RANGE_FROM~~]');
								if (str1.length > 1) {
										disc_caption_disp=str1[0]+' 1201.00+ '+str1[2];
								}
								*/
								resultHtml += '<tr><td colspan="8">' + disc_caption_disp + '</tr>';
								//resultHtml+='<tr><td colspan="8">Réduction de '+disc6_perc+'% sur tranche 1200 plus -'+disc6_amt.toFixed(2)+'</tr>';
						}

						total_disc = (disc1_amt + disc2_amt + disc3_amt + disc4_amt + disc5_amt + disc6_amt);
						total_sell = (subTotalLessons + subTotalEvents + totalTaxAmount) + subTotalEventsExtra - total_disc;
						var inittotal_sell = total_sell - total_disc;
				} // calculate disc
				else {
					total_sell = (subTotalLessons + subTotalEvents + totalTaxAmount) + subTotalEventsExtra - total_disc;
					var inittotal_sell = subTotalLessons + subTotalEvents - total_disc;
				}

				if (total_disc > 0) {
						resultHtml += '<tr><td colspan="3"></td>';
						resultHtml += '<td colspan="5"><strong>' + GetAppMessage("total_deduction_caption") + ' </strong></td>';
						resultHtml += '<td style="text-align:right" colspan="2"><strong>-' + total_disc.toFixed(2) + '<strong></tr>';
				}

                if($(window).width() < 768){
		    resultHtml += '<tr><td colspan="8" style="text-align:right;"><hr><b>Total taxe: '+currencyTotal+' <b><span id="total-taxes">'+(totalTaxAmount).toFixed(2)+'</span></b></td><td></td></tr>';
                } else {
                    resultHtml += '<tr><td colspan="8" style="text-align:right;"><b>Total taxes</td><td colspan="1" style="text-align:right;">'+currencyTotal+' <b><span id="total-taxes">'+(totalTaxAmount).toFixed(2)+'</span></b></td><td></td></tr>';
                }

                if($(window).width() < 768){
                    resultHtml += '<tr><td colspan="9" style="text-align:right;"><br>Sub-Total:  '+currencyTotal+' <b><span id="sub-total-before-charges">'+(subTotalLessons + subTotalEvents + totalTaxAmount).toFixed(2)+'</span></b><br><br></td><td></td></tr>';
                } else {
                    resultHtml += '<tr><td colspan="8" style="text-align:right;"><br><b>Sub-Total</td><td colspan="1" style="text-align:right;"><br>'+currencyTotal+' <b><span id="sub-total-before-charges">'+(subTotalLessons + subTotalEvents + totalTaxAmount).toFixed(2)+'</span></b></td><td></td></tr>';
                }

				if(subTotalEventsExtra > 0) {
					resultHtml += '<tr style="background-color:#EEE;"><td colspan="8" style="text-align:right;">Total Extra Charges</td><td colspan="1" style="text-align:right;">'+currencyTotal+' <b><span id="extras">'+subTotalEventsExtra.toFixed(2)+'</span></b></td><td></td></tr>';
				} else { resultHtml += '<span id="extras" style="display:none;">0</span>'; }

				// display grand total
				resultHtml += '<tr><td style="font-weight: bold;" colspan="6">';

                if($(window).width() < 768){
				    resultHtml += '<td style="text-align:right; font-weight: bold;" colspan="2" class="pt-3"><b>TOTAL:</b> ';
                } else {
                    resultHtml += '<td style="text-align:right; font-weight: bold;" colspan="2" class="pt-3">TOTAL</td>';
                }

				if (no_of_teachers == 1){
                    if($(window).width() < 768){
					    resultHtml += '<br><br></td>';
                    } else {
                        resultHtml += '<td style="text-align:right;"></td>';
                    }
				}else {
					if (!isTeacher) {
                        if($(window).width() < 768){
						    resultHtml += '' + total_buy.toFixed(2) + '<br><br></td>';
                        } else {
                            resultHtml += '<td style="text-align:right; font-weight: bold;">' + total_buy.toFixed(2) + '</td>';
                        }
					}
				}


                if($(window).width() < 768){
				    resultHtml += ''+currencyTotal+' <span style="font-weight: bold;" id="grand_total_amount">' + (total_sell).toFixed(2) + '</span></td><td></td>';
                } else {
                    resultHtml += '<td style="text-align:right" class="pt-3">'+currencyTotal+' <span style="font-weight: bold;" id="grand_total_amount">' + (total_sell).toFixed(2) + '</span></td><td></td>';
                }
				resultHtml += '</tr>'


                if (initPopulate && $(window).width() < 768) {
                    var tableau = document.createElement('table');

                    tableau.id = 'lesson_table';
                    var divLessonTable = document.querySelector('.lesson_table');

                    if (divLessonTable) {
                    divLessonTable.appendChild(tableau);
                    } else {
                    console.error("no classe 'lesson_table' found.");
                    }
				    $('#lesson_table').html(resultHtml);
                } else {
                    $('#lesson_table').html(resultHtml);
                }



            if ($(window).width() < 768) {
                let table = document.querySelector('#lesson_table');
                if (table) {

                    let newContainer = document.querySelector('.table-container');
                    if (!newContainer) {
                    newContainer = document.createElement('div');
                    newContainer.className = 'table-container';
                    }

                    let rows = table.querySelectorAll('tr');
                    rows.forEach((row) => {
                        if (row.classList.contains('table_header_invoice')) {
                            // Ignore cette ligne et passe à la suivante
                            return;
                        }

                        let newRow = document.createElement('div');
                        newRow.className = 'row';

                        let cells = row.querySelectorAll('td');
                        cells.forEach((cell) => {
                            let newCell = document.createElement('div');
                            newCell.className = 'col-12';
                            newCell.innerHTML = cell.innerHTML;
                            newRow.appendChild(newCell);
                        });

                        newContainer.appendChild(newRow);
                    });

                    table.parentNode.replaceChild(newContainer, table);
                }
            }

            initPopulate=true;

				var totalAmount = 0;
				var checkboxes = document.querySelectorAll('.taxe_class');
				checkboxes.forEach(function(checkbox) {
				var amount = parseFloat(checkbox.dataset.amount);
				totalAmount += amount;
				});

				// Add Event Listeners when DOM created
				var checkboxes = document.querySelectorAll('.taxe_class');
					checkboxes.forEach(function(checkbox) {
					checkbox.addEventListener('change', function(event) {

						var subTotalWithDiscount = document.getElementById('grand_total_amount');
						var TotalWithDiscount = parseFloat(subTotalWithDiscount.textContent);
						var subTotalExtra = document.getElementById('extras');
						var TotalExtra = parseFloat(subTotalExtra.textContent);

						var subtotalAl = parseFloat(document.getElementById('stotal_amount_with_discount').textContent);

						if (event.currentTarget.checked) {
							var amount = parseFloat(checkbox.dataset.amount);
							console.log('total amount tax plus', amount)
							total_sell = parseFloat(TotalWithDiscount + amount);
							document.getElementById('grand_total_amount').textContent = total_sell.toFixed(2);

							var totalNewTaxes = 0;
							var checkboxesTaxes = document.querySelectorAll('.taxe_class');
							checkboxesTaxes.forEach(function(checkbox) {
								if (checkbox.checked) {
									var amount = document.getElementById('stotal_amount_with_discount').textContent * parseFloat(checkbox.dataset.percentage) / 100;
									totalNewTaxes = totalNewTaxes + amount;
									$("#cap_tax_"+checkbox.dataset.id).text(parseFloat(amount).toFixed(2));
									console.log('new tax => ' + amount);
								}
							});

							document.getElementById('total-taxes').textContent = totalNewTaxes.toFixed(2);
							document.getElementById('finaltotaltaxes').value = totalNewTaxes.toFixed(2);
							var TotalsubTotalBeforeCharges = document.getElementById('stotal_amount_with_discount').textContent
							var totalBeforeCHarge = (totalNewTaxes + subtotalAl)
							document.getElementById('sub-total-before-charges').textContent = totalBeforeCHarge.toFixed(2);
						} else {
							var amount = parseFloat(checkbox.dataset.amount);
							console.log('total amount tax less', amount)
							console.log('total => ' + TotalWithDiscount + ' - ' + amount + ' = ' + (TotalWithDiscount - amount))
							total_sell = parseFloat((TotalWithDiscount - amount));
							document.getElementById('grand_total_amount').textContent = total_sell.toFixed(2);

							var totalNewTaxes = 0;
							var checkboxesTaxes = document.querySelectorAll('.taxe_class');
							checkboxesTaxes.forEach(function(checkbox) {
								if (checkbox.checked) {
									var amount = document.getElementById('stotal_amount_with_discount').textContent * parseFloat(checkbox.dataset.percentage) / 100;
									totalNewTaxes = totalNewTaxes + amount;
									$("#cap_tax_"+checkbox.dataset.id).text(parseFloat(amount).toFixed(2));
									console.log('new tax => ' + amount);
								}
							});

							document.getElementById('total-taxes').textContent = totalNewTaxes.toFixed(2);
							document.getElementById('finaltotaltaxes').value = totalNewTaxes.toFixed(2);
							var TotalsubTotalBeforeCharges = document.getElementById('stotal_amount_with_discount').textContent
							var totalBeforeCHarge = (totalNewTaxes + subtotalAl)
							document.getElementById('sub-total-before-charges').textContent = totalBeforeCHarge.toFixed(2);


						}
					});
				});

				var checkboxesEvent = document.querySelectorAll('.event_class');
				checkboxesEvent.forEach(function(checkbox) {
				var amount = parseFloat(checkbox.dataset.amount);
				totalAmount += amount;
				});

				// Add Event Listeners when DOM created
				var checkboxesEvents = document.querySelectorAll('.event_class');
				checkboxesEvents.forEach(function(checkbox) {
					checkbox.addEventListener('change', function(event) {
						if (event.currentTarget.checked) {
							//if checked
							$('#btn_convert_invoice').removeAttr("disabled");
							var oldtotalLessons = document.getElementById("ssubtotal_amount_with_discount_lesson");
							var totalLessons = parseFloat(oldtotalLessons.textContent)
							var newTotalEvents= 0;

							var amount = parseFloat(checkbox.dataset.amount);
							var oldSubTotalEvent = document.getElementById("stotal_amount_with_discount_event");
							var newTotalEvents =  parseFloat(oldSubTotalEvent.textContent);
							$("#stotal_amount_with_discount_event").text((parseFloat(newTotalEvents+amount)).toFixed(2));

							if(totalLessons>0) {
								if ($('#sdiscount_percent_1').length > 0) {
									var newDiscount = $("#sdiscount_percent_1").val();
									var totalDiscount = ((totalLessons*newDiscount) /100);
									var totalLessons = Number((totalLessons - totalDiscount))
									$("#ssubtotal_amount_with_discount").text(parseFloat(totalLessons).toFixed(2));
									$('#samount_discount_1').val(parseFloat(totalDiscount).toFixed(2));
								}
							}

							var newSubTotaux = (parseFloat((newTotalEvents+amount) + totalLessons))
							$("#stotal_amount_with_discount").text(parseFloat(newSubTotaux).toFixed(2));


							var totalNewTaxes = 0;
							var checkboxes = document.querySelectorAll('.taxe_class');
							checkboxes.forEach(function(checkbox) {

									var amount = newSubTotaux * parseFloat(checkbox.dataset.percentage) / 100;
									if (checkbox.checked) {
										totalNewTaxes = totalNewTaxes + amount;
									}
									$("#cap_tax_"+checkbox.dataset.id).text(parseFloat(amount).toFixed(2));
									console.log('new tax => ' + amount);

									var inputElement = document.getElementById('checkbox-'+checkbox.dataset.id);
									inputElement.setAttribute('data-amount', amount);


							});

							$("#total-taxes").text(parseFloat(totalNewTaxes).toFixed(2));
							document.getElementById('finaltotaltaxes').value = totalNewTaxes.toFixed(2);
							$("#sub-total-before-charges").text(parseFloat(newSubTotaux + totalNewTaxes).toFixed(2));


							var totalExtraSupp = 0;
							var suppExtra = parseFloat(checkbox.dataset.extra);
							var spanElement = document.getElementById('extras');
							if (spanElement) {
							var contenuValeurNumerique = parseFloat(spanElement.textContent);
							totalExtraSupp = contenuValeurNumerique + suppExtra
							$("#extras").text(parseFloat(totalExtraSupp).toFixed(2));
							}

							document.getElementById('grand_total_amount').textContent = (totalExtraSupp+newSubTotaux+totalNewTaxes).toFixed(2);


						} else {
							//if Unchecked

							var oldtotalLessons = document.getElementById("ssubtotal_amount_with_discount_lesson");
							var totalLessons = parseFloat(oldtotalLessons.textContent)
							var newTotalEvents= 0;

							var amount = parseFloat(checkbox.dataset.amount);
							var oldSubTotalEvent = document.getElementById("stotal_amount_with_discount_event");
							var newTotalEvents =  parseFloat(oldSubTotalEvent.textContent);
							$("#stotal_amount_with_discount_event").text((parseFloat(newTotalEvents-amount)).toFixed(2));

							if(totalLessons>0) {
								if ($('#sdiscount_percent_1').length > 0) {
									var newDiscount = $("#sdiscount_percent_1").val();
									var totalDiscount = ((totalLessons*newDiscount) /100);
									var totalLessons = Number((totalLessons - totalDiscount))
									$("#ssubtotal_amount_with_discount").text(parseFloat(totalLessons).toFixed(2));
									$('#samount_discount_1').val(parseFloat(totalDiscount).toFixed(2));
								}
							}

							var newSubTotaux = (parseFloat((newTotalEvents-amount) + totalLessons))
							$("#stotal_amount_with_discount").text(parseFloat(newSubTotaux).toFixed(2));


							var totalNewTaxes = 0;
							var checkboxes = document.querySelectorAll('.taxe_class');
							checkboxes.forEach(function(checkbox) {

									var amount = newSubTotaux * parseFloat(checkbox.dataset.percentage) / 100;
									if (checkbox.checked) {
										totalNewTaxes = totalNewTaxes + amount;
									}
									$("#cap_tax_"+checkbox.dataset.id).text(parseFloat(amount).toFixed(2));
									console.log('new tax => ' + amount);

									var inputElement = document.getElementById('checkbox-'+checkbox.dataset.id);
									inputElement.setAttribute('data-amount', amount);


							});

							$("#total-taxes").text(parseFloat(totalNewTaxes).toFixed(2));
							document.getElementById('finaltotaltaxes').value = totalNewTaxes.toFixed(2);
							$("#sub-total-before-charges").text(parseFloat(newSubTotaux + totalNewTaxes).toFixed(2));


							var totalExtraSupp = 0;
							var suppExtra = parseFloat(checkbox.dataset.extra);
							var spanElement = document.getElementById('extras');
							if (spanElement) {
							var contenuValeurNumerique = parseFloat(spanElement.textContent);
							totalExtraSupp = contenuValeurNumerique - suppExtra
							$("#extras").text(parseFloat(totalExtraSupp).toFixed(2));
							}


							document.getElementById('grand_total_amount').textContent = (totalExtraSupp+newSubTotaux+totalNewTaxes).toFixed(2);


						}
					});
				});



				var checkboxesLesson = document.querySelectorAll('.lesson_class');
				checkboxesLesson.forEach(function(checkbox) {
				var amount = parseFloat(checkbox.dataset.amount);
				totalAmount += amount;
				});

				// Add Event Listeners when DOM created
				var checkboxesLessons = document.querySelectorAll('.lesson_class');
				checkboxesLessons.forEach(function(checkbox) {
					checkbox.addEventListener('change', function(event) {
						if (event.currentTarget.checked) {
							//if checked
							$('#btn_convert_invoice').removeAttr("disabled");
							var amount = parseFloat(checkbox.dataset.amount);
							var oldSubtoTalLessons = document.getElementById("ssubtotal_amount_with_discount_lesson");
							console.log('ssubtotal_amount_with_discount_lesson', parseFloat(oldSubtoTalLessons.textContent))
							var newSubtoTalLessons = (parseFloat(oldSubtoTalLessons.textContent)+amount);
							console.log('newSubtoTalLessons', newSubtoTalLessons)
							$("#ssubtotal_amount_with_discount_lesson").text(parseFloat(newSubtoTalLessons).toFixed(2));
								var totalLessons = 0;
								if(newSubtoTalLessons>0) {
									if ($('#sdiscount_percent_1').length > 0) {
										var newDiscount = $("#sdiscount_percent_1").val();
										var totalDiscount = ((newSubtoTalLessons*newDiscount) /100);
										var totalLessons = Number((newSubtoTalLessons - totalDiscount))
										$("#ssubtotal_amount_with_discount").text(parseFloat(totalLessons).toFixed(2));
										$('#samount_discount_1').val(parseFloat(totalDiscount).toFixed(2));
									}
										$('#sdiscount_percent_1').removeAttr("disabled");
										$('#samount_discount_1').removeAttr("disabled");
								} else {
									$("#ssubtotal_amount_with_discount").text(parseFloat(0).toFixed(2));
									//$('#sdiscount_percent_1').val(0);
									//$('#samount_discount_1').val(0);
									$('#sdiscount_percent_1').attr("disabled", "disabled");
									$('#samount_discount_1').attr("disabled", "disabled");
								}

							var newTotalEvents = document.getElementById("stotal_amount_with_discount_event");
							console.log('stotal_amount_with_discount_event', parseFloat(newTotalEvents.textContent));
							var newSubTotaux = (parseFloat(newTotalEvents.textContent) + parseFloat(totalLessons))
							$("#stotal_amount_with_discount").text(parseFloat(newSubTotaux).toFixed(2));

							var totalNewTaxes = 0;
							var checkboxes = document.querySelectorAll('.taxe_class');
							checkboxes.forEach(function(checkbox) {

									var amount = newSubTotaux * parseFloat(checkbox.dataset.percentage) / 100;
									if (checkbox.checked) {
										totalNewTaxes = totalNewTaxes + amount;
									}
									$("#cap_tax_"+checkbox.dataset.id).text(parseFloat(amount).toFixed(2));
									console.log('new tax => ' + amount);

									var inputElement = document.getElementById('checkbox-'+checkbox.dataset.id);
									inputElement.setAttribute('data-amount', amount);


							});

							$("#total-taxes").text(parseFloat(totalNewTaxes).toFixed(2));
							document.getElementById('finaltotaltaxes').value = totalNewTaxes.toFixed(2);
							$("#sub-total-before-charges").text(parseFloat(newSubTotaux + totalNewTaxes).toFixed(2));


							var totalExtraSupp = 0;
							var spanElement = document.getElementById('extras');
							if (spanElement) {
							var contenuValeurNumerique = parseFloat(spanElement.textContent);
							totalExtraSupp = contenuValeurNumerique
							console.log(contenuValeurNumerique);
							}


							document.getElementById('grand_total_amount').textContent = (totalExtraSupp+newSubTotaux+totalNewTaxes).toFixed(2);



						} else {
							//if Unchecked
							var amount = parseFloat(checkbox.dataset.amount);
							var oldSubtoTalLessons = document.getElementById("ssubtotal_amount_with_discount_lesson");
							console.log('ssubtotal_amount_with_discount_lesson', parseFloat(oldSubtoTalLessons.textContent))
							var newSubtoTalLessons = (parseFloat(oldSubtoTalLessons.textContent)-amount);
							console.log('newSubtoTalLessons', newSubtoTalLessons)
							$("#ssubtotal_amount_with_discount_lesson").text(parseFloat(newSubtoTalLessons).toFixed(2));
								var totalLessons = 0;
								if(newSubtoTalLessons>0) {
									if ($('#sdiscount_percent_1').length > 0) {
										var newDiscount = $("#sdiscount_percent_1").val();
										var totalDiscount = ((newSubtoTalLessons*newDiscount) /100);
										var totalLessons = Number((newSubtoTalLessons - totalDiscount))
										$("#ssubtotal_amount_with_discount").text(parseFloat(totalLessons).toFixed(2));
										$('#samount_discount_1').val(parseFloat(totalDiscount).toFixed(2));
									}
								} else {
									$("#ssubtotal_amount_with_discount").text(parseFloat(0).toFixed(2));
									$('#sdiscount_percent_1').val(0);
									$('#samount_discount_1').val(0);
									$('#sdiscount_percent_1').attr("disabled", "disabled");
									$('#samount_discount_1').attr("disabled", "disabled");
									errorModalCall('You unchecked all lessons for this invoice. So your Lesson discount is now reset. Please add again your discount if you need it and if you check a lesson.')
								}

							var newTotalEvents = document.getElementById("stotal_amount_with_discount_event");
							console.log('stotal_amount_with_discount_event', parseFloat(newTotalEvents.textContent));
							var newSubTotaux = (parseFloat(newTotalEvents.textContent) + parseFloat(totalLessons))
							$("#stotal_amount_with_discount").text(parseFloat(newSubTotaux).toFixed(2));

							var totalNewTaxes = 0;
							var checkboxes = document.querySelectorAll('.taxe_class');
							checkboxes.forEach(function(checkbox) {

									var amount = newSubTotaux * parseFloat(checkbox.dataset.percentage) / 100;
									if (checkbox.checked) {
										totalNewTaxes = totalNewTaxes + amount;
									}
									$("#cap_tax_"+checkbox.dataset.id).text(parseFloat(amount).toFixed(2));
									console.log('new tax => ' + amount);

									var inputElement = document.getElementById('checkbox-'+checkbox.dataset.id);
									inputElement.setAttribute('data-amount', amount);


							});

							$("#total-taxes").text(parseFloat(totalNewTaxes).toFixed(2));
							document.getElementById('finaltotaltaxes').value = totalNewTaxes.toFixed(2);
							$("#sub-total-before-charges").text(parseFloat(newSubTotaux + totalNewTaxes).toFixed(2));


							var totalExtraSupp = 0;
							var spanElement = document.getElementById('extras');
							if (spanElement) {
							var contenuValeurNumerique = parseFloat(spanElement.textContent);
							totalExtraSupp = contenuValeurNumerique
							console.log(contenuValeurNumerique);
							}


							document.getElementById('grand_total_amount').textContent = (totalExtraSupp+newSubTotaux+totalNewTaxes).toFixed(2);






						}
					});
				});


				$(".numeric").keyup(function () {
					var checkPercentForDiscount = $("#sdiscount_percent_1").val();
					if(checkPercentForDiscount>100) {
						document.getElementById("sdiscount_percent_1").textContent = 100
						$('#errorModal').modal('hide')
						errorModalCall('The maximum of percentage discount is 100.');
					} else {
						$("#btn_convert_invoice").removeAttr("disabled");
            			CalculateDiscount('discount');
					}
				});

				$(".numeric_amount").keyup(function () {
					var checkboxesLessons = document.querySelectorAll('.lesson_class');
					var maxPossible=0;
					checkboxesLessons.forEach(function(checkbox) {
						if (checkbox.checked) {
							var amount = parseFloat(checkbox.dataset.amount);
							maxPossible += amount;
						};
					})
					var checkAmountForDiscount = $("#samount_discount_1").val();
					if(checkAmountForDiscount>maxPossible){
						$('#errorModal').modal('hide')
						errorModalCall('The maximum amount of discount is ' + maxPossible)
					} else {
						$("#btn_convert_invoice").removeAttr("disabled");
						CalculateDiscount('amount');
					}
				});


				//alert('all_ready='+all_ready+', invoice_already_generated='+invoice_already_generated);

				if (all_ready == 1) {
						if (selected_items == 0) {
								document.getElementById("lesson_footer_div").style.display = "none";
						} else {
								document.getElementById('lesson_footer_div').className = "alert alert-default";
								document.getElementById("lesson_footer_div").style.display = "block";
								document.getElementById("btn_convert_invoice").style.display = "block";

								document.getElementById("verify_label_id").style.display = "none";
						}
				} else {
						document.getElementById('lesson_footer_div').className = "alert alert-danger";
						document.getElementById("lesson_footer_div").style.display = "block";
						document.getElementById("btn_convert_invoice").style.display = "none";
						document.getElementById("verify_label_id").style.display = "block";
				}

		} //found records
		else {
            if($(window).width() > 768){
				resultHtml = '<tbody><tr class="lesson-item-list-empty"> <td colspan="12">No invoices available for this period.</td></tr></tbody>';
				$('#lesson_table').html(resultHtml);
				document.getElementById("lesson_footer_div").style.display = "none";
            } else {
                resultHtml = 'No invoices available for this period.';
				$('.table-container').html(resultHtml);
				document.getElementById("lesson_footer_div").style.display = "none";
            }
		}
	} // populate_student_lesson


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


            total_amount_discount = parseFloat(disc1_amt).toFixed(2);
            if ($('#stotal_amount_discount').length > 0) {
                $("#stotal_amount_discount").val(parseFloat(total_amount_discount).toFixed(2));
            }
            subtotal_amount_with_discount_lesson = Number($("#ssubtotal_amount_with_discount_lesson").text());
            subtotal_amount_with_discount_lesson = Number(+subtotal_amount_with_discount_lesson) - Number(+total_amount_discount);
        }

            $("#ssubtotal_amount_with_discount").text(parseFloat(subtotal_amount_with_discount_lesson).toFixed(2));

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

		var totalNewTaxes = 0;

		var checkboxes = document.querySelectorAll('.taxe_class');
		checkboxes.forEach(function(checkbox) {

				var amount = total_amount * parseFloat(checkbox.dataset.percentage) / 100;
				if (checkbox.checked) {
					totalNewTaxes = totalNewTaxes + amount;
				}
				$("#cap_tax_"+checkbox.dataset.id).text(parseFloat(amount).toFixed(2));
				console.log('new tax => ' + amount);

				var inputElement = document.getElementById('checkbox-'+checkbox.dataset.id);
				inputElement.setAttribute('data-amount', amount);


		});

		$("#total-taxes").text(parseFloat(totalNewTaxes).toFixed(2));
		document.getElementById('finaltotaltaxes').value = totalNewTaxes.toFixed(2);
		$("#sub-total-before-charges").text(parseFloat(subtotal_amount_with_discount_lesson + subtotal_amount_with_discount_event + totalNewTaxes).toFixed(2));


		var totalExtraSupp = 0;
		var spanElement = document.getElementById('extras');
		if (spanElement) {
		var contenuValeurNumerique = parseFloat(spanElement.textContent);
		totalExtraSupp = contenuValeurNumerique
		console.log(contenuValeurNumerique);
		}


		document.getElementById('grand_total_amount').textContent = (totalExtraSupp+totalNewTaxes+total_amount).toFixed(2);


    }


	function preview() {
		frame.src = URL.createObjectURL(event.target.files[0]);
	}


	function timeDifference(date1,date2) {
        var difference = date1 - date2

        var daysDifference = Math.floor(difference/1000/60/60/24);
        difference -= daysDifference*1000*60*60*24

        var hoursDifference = Math.floor(difference/1000/60/60);
        difference -= hoursDifference*1000*60*60

        var minutesDifference = Math.floor(difference/1000/60);
        difference -= minutesDifference*1000*60

        var secondsDifference = Math.floor(difference/1000);

        if(daysDifference > 0) {
            return daysDifference + (daysDifference === 1 ? ' day' : ' days');
        } else {
            if(hoursDifference > 0) {
                return hoursDifference + (hoursDifference === 1 ? ' hour ' : ' hours ') + minutesDifference + (minutesDifference === 1 ? ' minute' : ' minutes');
            } else {
                return minutesDifference + (minutesDifference > 1 ? ' minutes' : ' minute');
            }
        }
    }



	$('.box_img i.fa.fa-close').click(function (e) {
		document.getElementById("frame").src = BASE_URL +"/img/default_profile_image.png";
		$('#profile_image i.fa.fa-plus').show();
		$('#profile_image i.fa.fa-close').hide();
	})
	function addOrChangeParameters( url, params )
	{
		let splitParams = {};
		let splitPath = (/(.*)[?](.*)/).exec(url);
		if ( splitPath && splitPath[2] )
			splitPath[2].split("&").forEach( k => { let d = k.split("="); splitParams[d[0]] = d[1]; } );
		let newParams = Object.assign( splitParams, params );
		let finalParams = Object.keys(newParams).map( (a) => a+"="+newParams[a] ).join("&");
		return splitPath ? (splitPath[1] + "?" + finalParams) : (url + "?" + finalParams);
	}

</script>
@if(!empty(Session::get('vtab')))

<script>
$(function() {

    var vtab = '{!! Session::get('vtab') !!}';
    if (vtab == 'tab_3') {
		document.getElementById("delete_btn").style.display="none";
		document.getElementById("save_btn").style.display="none";
		activaTab('tab_3');
	} else {
		if (vtab != '') {
			activaTab(vtab);
		}
	}
});
</script>

@endif
@endsection
