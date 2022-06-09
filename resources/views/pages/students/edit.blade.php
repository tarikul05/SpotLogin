@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
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
						<label id="page_header" name="page_header">{{ __('Student Information:') }} {{!empty($relationalData->nickname) ? $relationalData->nickname : ''}}</label>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 btn-area">
					<div class="float-end btn-group">
						<a style="display: none;" id="delete_btn" href="#" class="btn btn-theme-warn"><em class="glyphicon glyphicon-trash"></em> {{ __('Delete:') }}</a>
					</div>
				</div>    
			</div>          
		</header>
		<!-- Tabs navs -->

		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Student Information') }}</button>
				<button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Contact Information') }}</button>
				<button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Lesson') }}</button>
				<!-- <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#tab_4" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('User Account') }}</button> -->
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<form enctype="multipart/form-data" class="form-horizontal" id="add_student" method="post" action="{{!empty($student) ? route('editStudentAction',[$student->id]): '/'}}"  name="add_student" role="form">
		<input type="hidden" name="school_id" value="{{ $relationalData->school_id }}">
		<input type="hidden" id="school_name" name="school_name" value="{{$schoolName}}">
		@csrf	
		<div class="tab-content" id="ex1-content">
				<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Student Personal Information') }}</label>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="is_active" id="visibility_label_id">{{__('Status') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" name="is_active" id="is_active">
												<option value="10">Active</option>
												<option value="0">Inactive</option>
												<option value="-9">Deleted</option>
											</select>
										</div>
									</div>
								</div>
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
											<input class="form-control" id="email" value="{{!empty($relationalData->email) ? old('email', $relationalData->email) : old('email')}}" name="email" type="text">
										</div>
									</div>
								</div>

								<div class="form-group row" id="shas_user_account_div">
									<div id="shas_user_account_div111" class="row">
										<label class="col-lg-3 col-sm-3 text-left" for="shas_user_account" id="has_user_ac_label_id">{{__('Enable student account') }} :</label>
										<div class="col-sm-7">
											<input id="shas_user_account" name="has_user_account" type="checkbox" value="1">
										</div>
									</div>
								</div>
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
										<div class="input-group" id="birth_date_div"> 
											<input id="birth_date" name="birth_date" type="text" class="form-control" value="{{!empty($student->birth_date) ? old('birth_date', $student->birth_date) : old('birth_date')}}"">
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
											<i class="fa fa-plus" style="display:none"></i>
											</label>
											<i class="fa fa-close"></i>
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
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="licence_usp" id="locality_caption">{{__('License number') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" id="licence_usp" name="licence_usp" type="text" value="{{!empty($relationalData->licence_usp) ? old('licence_usp', $relationalData->licence_usp) : old('licence_usp')}}">
										</div>
									</div>
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
								</div>
							</div>
							
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
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="street2" id="street_caption">{{__('Street2') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="street2" name="street2" value="{{!empty($student->street2) ? old('street2', $student->street2) : old('street2')}}" type="text">
								</div>
							</div>
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
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="province_id" id="pays_caption">{{__('Province') }} :</label>
								<div class="col-sm-7">
									<div class="selectdiv">
										<select class="form-control" id="province_id" name="province_id">
											<option value="">Select Province</option>
											<option value="3" {{!empty($student->province_id) ? (old('province_id', $student->province_id) == '3' ? 'selected' : '') : (old('province_id') == '3' ? 'selected' : '')}}>Alberta</option>
											<option value="2" {{!empty($student->province_id) ? (old('province_id', $student->province_id) == '2' ? 'selected' : '') : (old('province_id') == '2' ? 'selected' : '')}}>British Columbia</option>
											<option value="5" {{!empty($student->province_id) ? (old('province_id', $student->province_id) == '5' ? 'selected' : '') : (old('province_id') == '5' ? 'selected' : '')}}>Manitoba</option>
											<option value="10" {{!empty($student->province_id) ? (old('province_id', $student->province_id) == '10' ? 'selected' : '') : (old('province_id') == '10' ? 'selected' : '')}}>Newfoundland &amp; Labrador</option>
											<option value="12" {{!empty($student->province_id) ? (old('province_id', $student->province_id) == '12' ? 'selected' : '') : (old('province_id') == '12' ? 'selected' : '')}}>Northwest territory</option>
											<option value="8" {{!empty($student->province_id) ? (old('province_id', $student->province_id) == '8' ? 'selected' : '') : (old('province_id') == '8' ? 'selected' : '')}}>Nova Scotia</option>
											<option value="11" {{!empty($student->province_id) ? (old('province_id', $student->province_id) == '11' ? 'selected' : '') : (old('province_id') == '11' ? 'selected' : '')}}>Nunavut</option>
											<option value="6" {{!empty($student->province_id) ? (old('province_id', $student->province_id) == '6' ? 'selected' : '') : (old('province_id') == '6' ? 'selected' : '')}}>Ontario</option>
											<option value="9" {{!empty($student->province_id) ? (old('province_id', $student->province_id) == '9' ? 'selected' : '') : (old('province_id') == '9' ? 'selected' : '')}}>PEI</option>
											<option value="7" {{!empty($student->province_id) ? (old('province_id', $student->province_id) == '7' ? 'selected' : '') : (old('province_id') == '7' ? 'selected' : '')}}>Quebec</option>
											<option value="4" {{!empty($student->province_id) ? (old('province_id', $student->province_id) == '4' ? 'selected' : '') : (old('province_id') == '4' ? 'selected' : '')}}>Saskatchewan</option>
											<option value="13" {{!empty($student->province_id) ? (old('province_id', $student->province_id) == '13' ? 'selected' : '') : (old('province_id') == '13' ? 'selected' : '')}}>Yukon</option>
										
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
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="billing_street2" id="street_caption">{{__('Street2') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="billing_street2" name="billing_street2" value="{{!empty($student->billing_street2) ? old('billing_street2', $student->billing_street2) : old('billing_street2')}}" type="text">
								</div>
							</div>
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
											<option value="3" {{!empty($student->billing_province_id) ? (old('billing_province_id', $student->billing_province_id) == '3' ? 'selected' : '') : (old('billing_province_id') == '3' ? 'selected' : '')}}>Alberta</option>
											<option value="2" {{!empty($student->billing_province_id) ? (old('billing_province_id', $student->billing_province_id) == '2' ? 'selected' : '') : (old('billing_province_id') == '2' ? 'selected' : '')}}>British Columbia</option>
											<option value="5" {{!empty($student->billing_province_id) ? (old('billing_province_id', $student->billing_province_id) == '5' ? 'selected' : '') : (old('billing_province_id') == '5' ? 'selected' : '')}}>Manitoba</option>
											<option value="10" {{!empty($student->billing_province_id) ? (old('billing_province_id', $student->billing_province_id) == '10' ? 'selected' : '') : (old('billing_province_id') == '10' ? 'selected' : '')}}>Newfoundland &amp; Labrador</option>
											<option value="12" {{!empty($student->billing_province_id) ? (old('billing_province_id', $student->billing_province_id) == '12' ? 'selected' : '') : (old('billing_province_id') == '12' ? 'selected' : '')}}>Northwest territory</option>
											<option value="8" {{!empty($student->billing_province_id) ? (old('billing_province_id', $student->billing_province_id) == '8' ? 'selected' : '') : (old('billing_province_id') == '8' ? 'selected' : '')}}>Nova Scotia</option>
											<option value="11" {{!empty($student->billing_province_id) ? (old('billing_province_id', $student->billing_province_id) == '11' ? 'selected' : '') : (old('billing_province_id') == '11' ? 'selected' : '')}}>Nunavut</option>
											<option value="6" {{!empty($student->billing_province_id) ? (old('billing_province_id', $student->billing_province_id) == '6' ? 'selected' : '') : (old('billing_province_id') == '6' ? 'selected' : '')}}>Ontario</option>
											<option value="9" {{!empty($student->billing_province_id) ? (old('billing_province_id', $student->billing_province_id) == '9' ? 'selected' : '') : (old('billing_province_id') == '9' ? 'selected' : '')}}>PEI</option>
											<option value="7" {{!empty($student->billing_province_id) ? (old('billing_province_id', $student->billing_province_id) == '7' ? 'selected' : '') : (old('billing_province_id') == '7' ? 'selected' : '')}}>Quebec</option>
											<option value="4" {{!empty($student->billing_province_id) ? (old('billing_province_id', $student->billing_province_id) == '4' ? 'selected' : '') : (old('billing_province_id') == '4' ? 'selected' : '')}}>Saskatchewan</option>
											<option value="13" {{!empty($student->billing_province_id) ? (old('billing_province_id', $student->billing_province_id) == '13' ? 'selected' : '') : (old('billing_province_id') == '13' ? 'selected' : '')}}>Yukon</option>
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
						<div class="alert alert-warning">
							{{ __('Enter the percentage reduction amount for each of the tranches. If a tranche is not applicable, the amount of the reduction must be set to')}} 
						</div>
						<div class="row">
							<div class="col-md-8">
								<div class="form-group row">
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
								<tbody>
									<tr class="course_week_header">
										<td colspan="1">{{ __('Week 20') }}</td>
										<td colspan="1"></td>
										<td colspan="1">{{ __('Date') }}</td>
										<td colspan="1">{{ __('Time') }}</td>
										<td colspan="1">{{ __('Duration') }}</td>
										<td colspan="1">{{ __('Type') }}</td>
										<td colspan="1">{{ __('Coach') }}</td>
										<td colspan="1">{{ __('Lesson') }}</td>
										<td style="text-align:right" colspan="1">{{ __('Buy Price') }}</td>
										<td style="text-align:right" colspan="1">{{ __('Sell Price') }}</td>
										<td style="text-align:right" colspan="1">{{ __('Extra charges') }}</td>
									</tr>
									<tr>
										<td style="display:none;">30533</td>
										<td>-</td>
										<td></td>
										<td width="10%">18/05/2022</td>
										<td>14:30</td>
										<td>30 minutes </td>
										<td> (Soccer-School)</td>
										<td>teacher all</td>
										<td>Group lessons for 3 students</td>
										<td></td>
										<td>
											<a id="correct_btn" href="" class="btn btn-xs btn-info"> 
												<i class="fa fa-pencil"></i>{{__('Validate')}}
											</a>
										</td>
										<td style="text-align:right"></td>
									</tr>
									<tr style="font-weight: bold;">
										<td colspan="6"></td>
										<td colspan="2">{{ __('Sub-total Week')}} </td>
										<td style="text-align:right">75.00</td>
										<td style="text-align:right">75.00</td>
									</tr>
									<tr style="font-weight: bold;">
										<td colspan="6"></td>
										<td colspan="2">{{ __('Sub-total Monthly')}}: </td>
										<td style="text-align:right">75.00</td>
										<td style="text-align:right">75.00</td>
									</tr>
									<tr style="font-weight: bold;">
										<td colspan="6"></td>
										<td colspan="2">{{ __('Total Monthly')}}</td>
										<td style="text-align:right">75.00</td>
										<td style="text-align:right">75.00</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="alert alert-danger" id="lesson_footer_div" style="display: block;">
							<label id="verify_label_id" style="display: block;">{{ __('Please check all entries before you can convert these items into invoices.') }}</label>
						</div>
					</form>
				</div>
				<!--Start of Tab 4 -->
				<div class="tab-pane fade" id="tab_4" role="tabpanel" aria-labelledby="tab_4">
					<form id="studentUserForm" name="studentUserForm" class="form-horizontal" role="form"
					 action="{{!empty($student) ? route('student.user_update',[$student->id]): '/'}}" method="POST" enctype="multipart/form-data">
						@csrf
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
			@can('students-update')
				<button type="submit" id="save_btn" name="save_btn" class="btn btn-theme-success student_save"><i class="fa fa-save"></i>{{ __('Save') }}</button>
			@endcan
		
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
		format: "dd/mm/yyyy",
		autoclose: true,
		todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
	$("#billing_period_end_date").datetimepicker({
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
	$('#bill_address_same_as').click(function(){
		if($(this).is(':checked')){
			$('#billing_place').val( $('#place').val() );
			$('#billing_street').val( $('#street').val() );
			$('#billing_street2').val( $('#street2').val() );
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
	}
	// if (document.getElementById("find_flag").value== "0"){
	// 	document.getElementById("delete_btn").style.display="none";
	// 	document.getElementById("save_btn").style.display="none";                
	// }            
	

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
		var studentForm = document.getElementById("add_student");
		var studentUserForm = document.getElementById("studentUserForm");
		if (x[0].id == "tab_3") {
			studentUserForm.submit();
		} else{
			studentForm.submit();
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


$('.box_img i.fa.fa-close').click(function (e) {
	 document.getElementById("frame").src = BASE_URL +"/img/default_profile_image.png";
	$('#profile_image i.fa.fa-plus').show();
	$('#profile_image i.fa.fa-close').hide();
})
</script>
@endsection