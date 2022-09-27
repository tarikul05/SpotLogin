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
						<label id="page_header" name="page_header">{{ __('Student Information:') }} {{!empty($relationalData->full_name) ? $relationalData->full_name : ''}}</label>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 btn-area">
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
						<div class="row">
							<div class="col-md-6">
								@hasanyrole('teachers_admin|teachers_all|school_admin|superadmin')
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
								@endhasanyrole
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
											<input id="birth_date" name="birth_date" type="text" class="form-control" value="{{!empty($student->birth_date) ? date('d/m/Y', strtotime($student->birth_date)) : ''}}">
											<span class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</span>
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
							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="address_caption">{{__('Level') }}</label>
							</div>
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
												<input id="level_date_arp" name="level_date_arp" type="text" class="form-control" value="{{!empty($relationalData->level_date_arp) ? old('level_date_arp', $relationalData->level_date_arp) : old('level_date_arp')}}">
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
												<input id="level_date_usp" name="level_date_usp" type="text" class="form-control" value="{{!empty($relationalData->level_date_usp) ? old('level_date_usp', $relationalData->level_date_usp) : old('level_date_usp')}}">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>
									</div>
									@endif
								</div>
							</div>
							@hasanyrole('teachers_admin|teachers_all|school_admin|superadmin')
								<div id="commentaire_div">
									<div class="section_header_class">
										<label id="private_comment_caption">{{__('Private comment') }}</label>
									</div>
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
							@endhasanyrole
						</div>
					</fieldset>
				</div>
				<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
					<div class="section_header_class">
						<label id="address_caption">{{__('Address') }}</label>
					</div>
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
										<select class="form-control" id="country_code" name="country_code">
											@foreach($countries as $country)
												<option value="{{ $country->code }}" {{!empty($student->country_code) ? (old('country_code', $student->country_code) == $country->code ? 'selected' : '') : (old('country_code') == $country->code ? 'selected' : '')}}>{{ $country->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="form-group row" id="province_id_div" style="display: none;">
								<label class="col-lg-3 col-sm-3 text-left" for="province_id" id="pays_caption">{{__('Province') }} :</label>
								<div class="col-sm-7">
									<div class="selectdiv">
										<select class="form-control" id="province_id" name="province_id">
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
					<div class="section_header_class">
						<label id="address_caption">{{__('Billing address - Same as above') }} <input onclick="bill_address_same_as_click()" type="checkbox" name="bill_address_same_as" id="bill_address_same_as"></label>
					</div>
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
									<select class="form-control" id="billing_country_code" name="billing_country_code">
										@foreach($countries as $country)
											<option value="{{ $country->code }}" {{!empty($student->billing_country_code) ? (old('billing_country_code', $student->billing_country_code) == $country->code ? 'selected' : '') : (old('billing_country_code') == $country->code ? 'selected' : '')}}>{{ $country->name }}</option>
										@endforeach
									</select>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="province_id" id="pays_caption">{{__('Province') }} :</label>
								<div class="col-sm-7">
									<div class="selectdiv">
										<select class="form-control" id="billing_province_id" name="billing_province_id">
											<option value="">Select Province</option>
											@foreach($provinces as $province)
												<option value="{{ $province['id'] }}" {{!empty($student->billing_province_id) ? (old('billing_province_id', $student->billing_province_id) == $province['id'] ? 'selected' : '') : (old('billing_province_id') == $province['id'] ? 'selected' : '')}}>{{ $province['province_name'] }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="section_header_class">
						<label id="address_caption">{{__('Contact Information (At least one email needs to be selected to receive invoices)') }}</label>
					</div>
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
								<label class="col-lg-3 col-sm-3 text-left" for="father_email" id="father_email">{{__("Father’s email") }} :</label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-group-addon"><input type="checkbox" name="father_notify" value="1" {{ !empty($student->father_notify) ? 'checked' : '' }} ></span> 
										<input class="form-control" id="father_email" name="father_email" value="{{!empty($student->father_email) ? old('father_email', $student->father_email) : old('father_email')}}" type="text"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="mother_email" >{{__("Mother’s email") }} :</label>
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
				</form>
				<div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tab_3">
					<form role="form" id="form_invoicing" class="form-horizontal" method="post" action="#">
						<input type="hidden" name="selected_month" id="selected_month" value="">
						<input type="hidden" name="selected_year" id="selected_year" value="">
						<input type="hidden" name="person_id" id="person_id" value="{{!empty($student->id) ? old('person_id', $student->id) : old('person_id')}}"> 
						<input type="hidden" name="no_of_teachers" id="no_of_teachers" value="{{!empty($school->max_teachers) ? old('no_of_teachers', $school->max_teachers) : old('no_of_teachers')}}"> 
						
						
						<div class="alert alert-warning">
							{{ __('Enter the percentage reduction amount for each of the tranches. If a tranche is not applicable, the amount of the reduction must be set to')}} 
						</div>
						<div class="row">
							<div class="col-md-8">
								<div class="form-group row below_space">
									<label class="col-lg-2 col-sm-2 text-left"> {{ __('Choice of period') }}:</label>
									<div class="col-sm-2">
										<input class="form-control" name="billing_period_start_date" id="billing_period_start_date"> 
									</div>
									<div class="col-sm-2 offset-md-1">
										<input class="form-control" name="billing_period_end_date" id="billing_period_end_date"> 
									</div>
									<div id="show_only_pend_div" class="col-lg-3 col-sm-3 text-left offset-md-1">
										<input type="checkbox" id="chk_show_only_pend" name="chk_show_only_pend" checked="">
										<label id="lbl_chk_show_only_pend" name="lbl_chk_show_only_pend" for="chk_show_only_pend">{{ __('Only pending lessons') }}</label>
									</div>
									<div class="col-sm-1">
										<button type="button" class="btn btn-primary" id="billing_period_search_btn">{{ __('Search') }}</button>
									</div>
								</div>
							</div>
						</div>
						<div class="section_header_class">
							<label id="course_for_billing_caption">{{ __('Lessons applicable for invoicing') }}</label>
						</div>
						<div class="table-responsive">
							<table class="table lessons-list" id="lesson_table">
								
							</table>
						</div>
						<div class="alert alert-danger" id="lesson_footer_div" style="display: none;">
								<label id="verify_label_id">{{ __('Please check all entries before you can convert these items into invoices.') }}</label>
								<button class="btn btn-primary" id="btn_convert_invoice">Generate invoice</button>
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
$(document).ready(function(){
	var country_code = $('#country_code option:selected').val();
	if(country_code == 'CA'){
		$('#province_id_div').show();
	}
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
		format: "dd/mm/yyyy",
		autoclose: true,
		todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
	$("#level_date_usp").datetimepicker({
		format: "dd/mm/yyyy",
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

		var p_event_ids = GetCheckBoxSelectedValues('event_check');
		if (p_event_ids == '') {
			//DisplayMessage("Sélectionnez au moins un élément pour générer la facture.");
			return false;
		}

		var p_person_id = document.getElementById("person_id").value;
			//p_month = document.getElementById("smonth").value,
			//p_year = document.getElementById("syear").value;

		// var from_date = moment((p_year + '-' + p_month + '-01'), "YYYY-MM-DD").format("YYYY.MM.DD");
		// var to_date = moment((p_year + '-' + p_month + '-01'), "YYYY-MM-DD").endOf('month').format("YYYY.MM.DD");
		
		var from_date = moment(($("#billing_period_start_date").val()),"DD/MM/YYYY").format("YYYY.MM.DD");
		var to_date = moment(($("#billing_period_end_date").val()),"DD/MM/YYYY").format("YYYY.MM.DD");
		
		
		var p_invoice_id = '';
		var auto_id = 0;
		var p_discount_percent_1 = document.getElementById("s_percent_1").value,
			p_discount_percent_2 = document.getElementById("s_percent_2").value,
			p_discount_percent_3 = document.getElementById("s_percent_3").value,
			p_discount_percent_4 = document.getElementById("s_percent_4").value,
			p_discount_percent_5 = document.getElementById("s_percent_5").value,
			p_discount_percent_6 = document.getElementById("s_percent_6").value;


		data = 'type=generate_student_invoice&p_person_id=' + p_person_id + '&p_invoice_id=' + p_invoice_id + '&p_from_date=' + from_date + '&p_to_date=' + to_date + '&p_event_ids=' + p_event_ids;
		data += '&p_discount_percent_1=' + p_discount_percent_1 + '&p_discount_percent_2=' + p_discount_percent_2;
		data += '&p_discount_percent_3=' + p_discount_percent_3 + '&p_discount_percent_4=' + p_discount_percent_4;
		data += '&p_discount_percent_5=' + p_discount_percent_5 + '&p_discount_percent_6=' + p_discount_percent_6;
		console.log(data);

		$.ajax({
			url: '../student/student_events_data.php',
			data: data,
			type: 'POST',
			dataType: 'json',
			async: false,
			success: function(result) {
				$.each(result, function(key, value) {
					if (value.status == 'success') {
						auto_id = value.auto_id;
						var msg = GetAppMessage("invoice_generated_msg");
						DisplayMessage(msg);

						//location.reload(); //commented by soumen divert to invoice screen.     
					} else {
						errorModalCall(GetAppMessage('error_message_text'));
						// alert(value.status);
					}

				});
			}, // success
			error: function(ts) {
				errorModalCall(GetAppMessage('error_message_text'));
				// alert(ts.responseText + ' Generate Invoice')
			}
		}); // Ajax

		if (auto_id > 0) { 
			var url = "../invoice/invoice_modification.html?auto_id=" + auto_id + "&action=edit";
		setTimeout(function(){ window.open(url, "_self"); }, 3000);
			
			//window.location(url); 
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
	$('#billing_period_search_btn').on('click', function() {   
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
				resultHtml = '<tbody><tr class="lesson-item-list-empty"> <td colspan="12">..</td></tr></tbody>';
				$('#lesson_table').html(resultHtml);
				document.getElementById("lesson_footer_div").style.display = "none";
				return false;
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


		//resultHtml='<tr><td colspan="8"><font color="blue"><h5> Cours disponibles à la facturation</h5></font></tr>';
		data = 'type=' + person_type + '&school_id=' + school_id + '&p_person_id=' + p_person_id + '&p_billing_period_start_date='+p_billing_period_start_date+'&p_billing_period_end_date=' + p_billing_period_end_date+'&p_pending_only='+p_pending_only;
		console.log(data);
		$.ajax({
			url: BASE_URL + '/get_student_lessons',
			//url: '../student/student_events_data.php',
			data: data,
			type: 'POST',
			dataType: 'json',
			async: false,
			success: function(result) {
					$.each(result.data, function(key, value) {
							record_found += 1;
							// week summary
							if ((prev_week != '') && (prev_week != value.week_name)) {
									resultHtml += '<tr style="font-weight: bold;"><td colspan="6">';
									resultHtml += '<td colspan="2">' + sub_total_caption + ' ' + week_caption + ' </td>';
									// alert('no_of_teachers='+no_of_teachers);
									if (no_of_teachers == 1){
											resultHtml += '<td style="text-align:right"></td>';
									}else {
											resultHtml += '<td style="text-align:right">' + week_total_buy.toFixed(2) + '</td>';
									}
		
									resultHtml += '<td style="text-align:right">' + week_total_sell.toFixed(2) + '</td>';
									resultHtml += '</tr>'
									week_total_buy = 0;
									week_total_sell = 0;
							}

							if (prev_week != value.week_name) {
									//resultHtml+='<b><tr class="course_week_header"><td colspan="10">'+week_caption+' '+value.week_no+'</tr></b>';
									resultHtml += '<b><tr class="course_week_header"><td colspan="1">' + week_caption + ' ' + value.week_no + '</td>';
									resultHtml += '<b><td colspan="1">' + '' + '</td>';
									resultHtml += '<b><td colspan="1">Date</td>';
									resultHtml += '<b><td colspan="1">Time</td>';
									resultHtml += '<b><td colspan="1">Duration</td>';
									resultHtml += '<b><td colspan="1">Type</td>';
									resultHtml += '<b><td colspan="1">Coach</td>';
									resultHtml += '<b><td colspan="1">Lesson</td>';
									
									//resultHtml+='<b><td style="text-align:center" colspan="2">'+value.price_currency+'</td>';
									if (result.no_of_teachers == 1){
										resultHtml += '<b><td style="text-align:right" colspan="1">' + '' + '</td>';
										resultHtml += '<b><td style="text-align:right" colspan="1">Price</td>';
									} else {
										resultHtml += '<b><td style="text-align:right" colspan="1">Buy Price</td>';
										resultHtml += '<b><td style="text-align:right" colspan="1">Sell Price</td>';
									}
		
		
									resultHtml += '<td style="text-align:right" colspan="1">Extra Charges</td></tr></b>';

							}
							resultHtml += '<tr>';

							resultHtml += '<td style="display:none;">' + value.event_id + '</td>';

							if ((value.is_sell_invoiced == 0) && (value.ready_flag == 1)) {
									selected_items += 1;
									resultHtml += "<td><input class='event_class' type=checkbox id='event_check' name='event_check' checked value=" + value.event_id + "></td>";
							} else {
									resultHtml += "<td>-</td>";
							}

							//below locked and invoiced
							resultHtml += "<td>";
							if (value.ready_flag == 1) {
									resultHtml += "<em class='glyphicon glyphicon-lock'></em> ";
							}
							//if (value.is_sell_invoiced > 0) {
									//comments as Kim as per Sportlogin Before the app.doc
									//resultHtml += "<em class='glyphicon glyphicon glyphicon-print'></em>";
							//}
							resultHtml += "</td>";
							//above locked and invoiced

							resultHtml += '<td width="10%">' + value.date_start + '</td>';
							if (value.duration_minutes == 0) {
								resultHtml += '<td style="text-align:center" colspan="2">' + GetAppMessage('allday_event_caption') + '</td>';
							}else {
								resultHtml += '<td>' + value.time_start + '</td>';
								resultHtml += '<td>' + value.duration_minutes + ' minutes </td>';
							}
							resultHtml += '<td>' + value.category_name + '</td>';
							resultHtml += '<td>' + value.teacher_name + '</td>';
							resultHtml += '<td>' + value.title + '</td>';

							// all_ready = 0 means not ready to generate invoice
							//var icon  ='<img src="../images/icons/locked.gif" width="12" height="12"/>';
							if (value.ready_flag == 0) {
								all_ready = 0;
								resultHtml += "<td></td>";
								resultHtml += "<td><a id='correct_btn' class='button_lock_and_save' href='/"+school_id+"/edit-lesson/"+value.event_id+"' class='btn btn-xs btn-info'> <em class='glyphicon glyphicon-pencil'></em>" + correct_btn_text + "</a>";
							} else {
								if (no_of_teachers == 1){
										resultHtml += '<td style="text-align:right"></td>';
								}else {
										resultHtml += '<td style="text-align:right">' + value.price_currency + ' ' + value.buy_price + '</td>';
								}
								resultHtml += '<td style="text-align:right">' + value.price_currency + ' ' + value.sell_price + '</td>';
								total_buy += parseFloat(value.buy_price);
								total_sell += parseFloat(value.sell_price) + parseFloat(value.costs_1);
								
								week_total_buy += parseFloat(value.buy_price);
								week_total_sell += parseFloat(value.sell_price) + parseFloat(value.costs_1);
								

								if (value.event_type == 10) {
									amount_for_disc=amount_for_disc+parseFloat(value.sell_price);
								}
							}

							costs_1 = parseFloat(value.costs_1);
							if (value.costs_1 != 0) {
									resultHtml += '<td style="text-align:right">' + costs_1.toFixed(2) + '</td>';
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
					}); //for each record
			}, // success
			error: function(ts) {
				//errorModalCall(GetAppMessage('error_message_text'));
				console.log(ts.responseText + ' populate_student_lesson')
			}
		}); // Ajax

		
		if (record_found > 0) {

				// summary for last week of course records
				if ((week_total_buy > 0) || (week_total_sell > 0)) {
					
						resultHtml += '<tr style="font-weight: bold;"><td colspan="6">';
						resultHtml += '<td colspan="2">' + sub_total_caption + ' ' + week_caption + ' </td>';

						if (no_of_teachers == 1){
							resultHtml += '<td style="text-align:right"></td>';
						}else {
							resultHtml += '<td style="text-align:right">' + week_total_buy.toFixed(2) + '</td>';
						}

						resultHtml += '<td style="text-align:right">' + week_total_sell.toFixed(2) + '</td>';
						resultHtml += '</tr>'
						week_total_buy = 0;
						week_total_sell = 0;
				}

				// display grand total
				resultHtml += '<tr style="font-weight: bold;"><td colspan="6">';
				resultHtml += '<td colspan="2">' + sub_total_caption + ' ' + month_caption + ': </td>';

				if (no_of_teachers == 1){
				resultHtml += '<td style="text-align:right"></td>';
				}else {
					resultHtml += '<td style="text-align:right">' + total_buy.toFixed(2) + '</td>';
				}

				resultHtml += '<td style="text-align:right">' + total_sell.toFixed(2) + '</td>';
				resultHtml += '</tr>'


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
						total_sell = total_sell - total_disc;
				} // calculate disc

				if (total_disc > 0) {
						resultHtml += '<tr><td colspan="3"></td>';
						resultHtml += '<td colspan="5"><strong>' + GetAppMessage("total_deduction_caption") + ' </strong></td>';
						resultHtml += '<td style="text-align:right" colspan="2"><strong>-' + total_disc.toFixed(2) + '<strong></tr>';
				}

				// display grand total
				resultHtml += '<tr style="font-weight: bold;"><td colspan="6">';
				resultHtml += '<td colspan="2">Total ' + month_caption + '</td>';
				
				if (no_of_teachers == 1){
					resultHtml += '<td style="text-align:right"></td>';
				}else {
					resultHtml += '<td style="text-align:right">' + total_buy.toFixed(2) + '</td>';
				}

				resultHtml += '<td style="text-align:right">' + total_sell.toFixed(2) + '</td>';
				resultHtml += '</tr>'

				//display grand total
				$('#lesson_table').html(resultHtml);
				//alert('all_ready='+all_ready+', invoice_already_generated='+invoice_already_generated);

				if (all_ready == 1) {
						if (selected_items == 0) {
								document.getElementById("lesson_footer_div").style.display = "none";
						} else {
								document.getElementById('lesson_footer_div').className = "alert alert-info";
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
				resultHtml = '<tbody><tr class="lesson-item-list-empty"> <td colspan="12">No Recrd</td></tr></tbody>';
				$('#lesson_table').html(resultHtml);
				document.getElementById("lesson_footer_div").style.display = "none";
		}
	} // populate_student_lesson


	function preview() {
		frame.src = URL.createObjectURL(event.target.files[0]);
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
	$('#country_code').change(function(){
		var country = $(this).val();

		if(country == 'CA'){
			$('#province_id_div').show();
		}else{
			$('#province_id_div').hide();
		}
	})
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