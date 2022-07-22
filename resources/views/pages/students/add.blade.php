@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<!-- color wheel -->
<script src="{{ asset('js/jquery.wheelcolorpicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/wheelcolorpicker.css')}}"/>
@endsection

@section('content')
  <div class="content">
	<div class="container-fluid">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area">
					<div class="page_header_class">
						<label id="page_header" name="page_header">{{ __('Student Information:') }}</label>
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

			<!-- user email check start -->
			<form action="" class="form-horizontal" action="{{ auth()->user()->isSuperAdmin() ? route('admin.student.create',[$schoolId]) : route('student.create')}}" method="post" action="" role="form">
				@csrf
				<div class="form-group row">
					<label class="col-lg-3 col-sm-3 text-left" for="email" id="email_caption">{{__('Student Find') }} <i class="fa fa-info-circle" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Enter email to auto-populate the page')}}"></i> :</label>
					<div class="col-sm-5 search_area">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-envelope"></i></span> 
							<input class="form-control" id="email" placeholder="{{ __('email@domain.com') }}" value="{{$searchEmail}}" name="email" type="email">
						</div>
					</div>
					<div class="col-sm-2 check_btn">
						<button  class="btn btn-primary check" type="submit"><i class="fa fa-search"></i> Check</button>
					</div>
				</div>
			</form>
			<!-- // user email check end -->
	{{-- @if($searchEmail) --}}
		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Student Information') }}</button>
				<button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Contact Information') }}</button>
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<form enctype="multipart/form-data" class="form-horizontal" id="add_student" method="post" action="{{ route('student.createAction') }}"  name="add_student" role="form">
		<input type="hidden" name="school_id" value="{{ $schoolId }}">
		<input type="hidden" name="user_id" value="{{ !empty($exUser) ? $exUser->id : '' }}">
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
													<option value="1">Active</option>
													<option value="0">Inactive</option>
												</select>
											</div>
										</div>
									</div>
								@endhasanyrole
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="nickname" id="nickname_label_id">{{__('Nickname') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control require" required="true" id="nickname" maxlength="50" name="nickname" placeholder="Nickname" type="text" value="{{old('nickname')}}">
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
											<select class="form-control require" require id="gender_id" name="gender_id">
												@foreach($genders as $key => $gender)
													<option value="{{ $key }}" {{ old('gender_id') == $key ? 'selected' : ''}}>{{ $gender }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="billing_method" id="visibility_label_id">{{__('Rate') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control"id="billing_method" name="billing_method">
												<option value="E" >coming soon</option>
												<!-- comented for 1st release
												<option value="E" {{ old('billing_method') == 'Y' ? 'selected' : ''}} >Event-wise</option>
												<option value="M" {{ old('billing_method') == 'M' ? 'selected' : ''}} >Monthly</option>
												<option value="Y" {{ old('billing_method') == 'Y' ? 'selected' : ''}}>Yearly</option> -->
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="email" id="email_caption">{{__('Email') }} <i class="fa fa-info-circle" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('If you enter an email, the student will receive an email with instructions to connect to his student account.')}}"></i> :</label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span> 
											<input class="form-control" id="email" value="{{$searchEmail}}" name="email" type="text">
										</div>
									</div>
								</div>

								<!-- <div class="form-group row" id="shas_user_account_div">
									<div id="shas_user_account_div111" class="row">
										<label class="col-lg-3 col-sm-3 text-left" for="shas_user_account" id="has_user_ac_label_id">{{__('Enable student account') }} :</label>
										<div class="col-sm-7">
											<input id="shas_user_account" name="has_user_account" type="checkbox" value="1">
										</div>
									</div>
								</div> -->
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="lastname" id="family_name_label_id">{{__('Family Name') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control require" required="true" value="{{ $exStudent ? $exStudent->lastname : '' }}" {{ $exStudent ? 'readonly' : '' }} id="lastname" name="lastname" type="text" value="{{old('lastname')}}">
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
										<input class="form-control require" required="true" value="{{ $exStudent ? $exStudent->firstname : '' }}" {{ $exStudent ? 'readonly' : '' }} id="firstname" name="firstname" type="text" value="{{old('firstname')}}">
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
											<input id="birth_date" value="{{ $exStudent ? $exStudent->birth_date : '' }}" {{ $exStudent ? 'readonly' : '' }} name="birth_date" type="text" class="form-control" value="{{old('birth_date')}}">
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
											<img src="{{ asset('img/default_profile_image.png') }}"  id="frame" width="150px" alt="SpotLogin">
											<i class="fa fa-plus"></i>
											</label>
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
													<option selected value="">Select level</option>
													@foreach($levels as $key => $level)
														<option value="{{ $level->id }}">{{ $level->title }}</option>
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
												<input id="level_date_arp" value="{{ $exStudent ? $exStudent->level_date_arp : '' }}" {{ $exStudent ? 'readonly' : '' }} name="level_date_arp" type="text" class="form-control" value="{{old('level_date_arp')}}">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="licence_arp" id="postal_code_caption">{{__('ARP license') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" id="licence_arp" value="{{ $exStudent ? $exStudent->licence_arp : '' }}" {{ $exStudent ? 'readonly' : '' }} name="licence_arp" type="text" value="{{old('licence_arp')}}">
										</div>
									</div>
									@endif
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="licence_usp" id="locality_caption">{{__('License number') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" id="licence_usp" value="{{ $exStudent ? $exStudent->licence_usp : '' }}" {{ $exStudent ? 'readonly' : '' }} name="licence_usp" type="text" value="{{old('licence_usp')}}">
										</div>
									</div>
									@if($school->country_code == 'CH')
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="level_skating_usp" id="locality_caption">{{__('USP Level') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" id="level_skating_usp" value="{{ $exStudent ? $exStudent->level_skating_usp : '' }}" {{ $exStudent ? 'readonly' : '' }} name="level_skating_usp" type="text" value="{{old('level_skating_usp')}}">
										</div>
									</div>
									<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Date last level USP') }}:</label>
										<div class="col-sm-7">
											<div class="input-group" id="date_last_level_usp_div"> 
												<input id="level_date_usp" value="{{ $exStudent ? $exStudent->level_date_usp : '' }}" {{ $exStudent ? 'readonly' : '' }} name="level_date_usp" type="text" class="form-control" value="{{old('level_date_usp')}}">
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
													<textarea class="form-control" cols="60" id="comment" name="comment" rows="5">{{old('comment')}}</textarea>
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
									<input class="form-control" id="street" value="{{ $exStudent ? $exStudent->street : '' }}" {{ $exStudent ? 'readonly' : '' }} name="street" value="{{old('street')}}" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="street_number" id="street_number_caption">{{__('Street No') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="street_number" value="{{ $exStudent ? $exStudent->street_number : '' }}" {{ $exStudent ? 'readonly' : '' }} name="street_number" value="{{old('street_number')}}" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="street2" id="street_caption">{{__('Street2') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="street2" value="{{ $exStudent ? $exStudent->street2 : '' }}" {{ $exStudent ? 'readonly' : '' }} name="street2" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="zip_code" id="postal_code_caption">{{__('Postal Code') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="zip_code" value="{{ $exStudent ? $exStudent->zip_code : '' }}" {{ $exStudent ? 'readonly' : '' }} name="zip_code" value="{{old('zip_code')}}" type="text">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="place" id="locality_caption">{{__('City') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="place" value="{{ $exStudent ? $exStudent->place : '' }}" {{ $exStudent ? 'readonly' : '' }} name="place" value="{{old('place')}}" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="country_code" id="pays_caption">{{__('Country') }} :</label>
								<div class="col-sm-7">
									<div class="selectdiv">
										<select class="form-control" id="country_code" name="country_code">
											@foreach($countries as $country)
												<option value="{{ $country->code }}">{{ $country->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="form-group row" id="province_id_div">
								<label class="col-lg-3 col-sm-3 text-left" for="province_id" id="pays_caption">{{__('Province') }} :</label>
								<div class="col-sm-7">
									<div class="selectdiv">
										<select class="form-control" id="province_id" name="province_id">
											<option value="">Select Province</option>
											@foreach($provinces as $key => $province)
												<option value="{{ $key }}" {{ old('province_id') == $key ? 'selected' : ''}}>{{ $province }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="section_header_class">
						<label id="address_caption">{{__('Billing address - Same as above') }} <input type="checkbox" name="bill_address_same_as" id="bill_address_same_as"></label>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="billing_street" id="street_caption">{{__('Street') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="billing_street" value="{{ $exStudent ? $exStudent->billing_street : '' }}" {{ $exStudent ? 'readonly' : '' }} name="billing_street" value="{{old('billing_street')}}" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="billing_street_number" id="street_number_caption">{{__('Street No') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="billing_street_number" value="{{ $exStudent ? $exStudent->billing_street_number : '' }}" {{ $exStudent ? 'readonly' : '' }} name="billing_street_number" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="billing_street2" id="street_caption">{{__('Street2') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="billing_street2" value="{{ $exStudent ? $exStudent->billing_street2 : '' }}" {{ $exStudent ? 'readonly' : '' }} name="billing_street2" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="billing_zip_code" id="postal_code_caption">{{__('Postal Code') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="billing_zip_code" value="{{ $exStudent ? $exStudent->billing_zip_code : '' }}" {{ $exStudent ? 'readonly' : '' }} name="billing_zip_code" type="text">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="billing_place" id="locality_caption">{{__('City') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="billing_place" value="{{ $exStudent ? $exStudent->billing_place : '' }}" {{ $exStudent ? 'readonly' : '' }} name="billing_place" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="billing_country_code" id="pays_caption">{{__('Country') }} :</label>
								<div class="col-sm-7">
									<div class="selectdiv">
									<select class="form-control" id="billing_country_code" name="billing_country_code">
										@foreach($countries as $country)
											<option value="{{ $country->code }}">{{ $country->name }}</option>
										@endforeach
									</select>
									</div>
								</div>
							</div>
							<div class="form-group row" id="billing_province_id_div">
								<label class="col-lg-3 col-sm-3 text-left" for="province_id" id="pays_caption">{{__('Province') }} :</label>
								<div class="col-sm-7">
									<div class="selectdiv">
										<select class="form-control" id="billing_province_id" name="billing_province_id">
											<option value="">Select Province</option>
											@foreach($provinces as $key => $province)
												<option value="{{ $key }}" {{ old('billing_province_id') == $key ? 'selected' : ''}}>{{ $province }}</option>
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
								<label class="col-lg-3 col-sm-3 text-left" for="father_phone" id="father_phone">{{__("Father’s phone") }} :</label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-phone-square"></i></span> <input class="form-control" id="father_phone" value="{{ $exStudent ? $exStudent->father_phone : '' }}" {{ $exStudent ? 'readonly' : '' }} name="father_phone"  type="text">
									</div>
								</div>
							</div>
							<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="mother_phone" id="mother_phone">{{__("Mother's phone") }} :</label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-phone-square"></i></span> <input class="form-control" id="mother_phone" name="mother_phone" value="{{ $exStudent ? $exStudent->mother_phone : '' }}" {{ $exStudent ? 'readonly' : '' }} type="text">
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="student_phone" id="student_phone">{{__("Student's phone:") }} :</label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-phone-square"></i></span> <input class="form-control" id="mobile" name="mobile" value="{{ $exStudent ? $exStudent->mobile : '' }}" {{ $exStudent ? 'readonly' : '' }} type="text">
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="father_email" id="father_email">{{__("Father’s email") }} :</label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-group-addon"><input type="checkbox"value="1" name="father_notify"></span> <input class="form-control" id="father_email" name="father_email" value="{{ $exStudent ? $exStudent->father_email : '' }}" {{ $exStudent ? 'readonly' : '' }} type="text"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="mother_email">{{__("Mother’s email") }} :</label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-group-addon"><input type="checkbox" value="1" name="mother_notify"></span> <input class="form-control" id="mother_email" name="mother_email" value="{{ $exStudent ? $exStudent->mother_email : '' }}" {{ $exStudent ? 'readonly' : '' }} type="text"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left" for="email2" >{{__("Student's email") }} :</label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-group-addon"><input type="checkbox" value="1" name="student_notify"></span> 
										<input class="form-control" id="email2" name="email2" value="{{$searchEmail}}" type="text"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		</div>
		<button type="submit" id="save_btn" name="save_btn" class="btn btn-theme-success student_save"><i class="fa fa-save"></i>{{ __('Save') }}</button>
		</form>
	{{-- @endif --}} 
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
$(function() {
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
			return true;
		}else{
			return false;
		}	            
	});  


});
$(function() { $('.colorpicker').wheelColorPicker({ sliders: "whsvp", preview: true, format: "css" }); });


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


$('#country_code').change(function(){
	var country = $(this).val();

	if(country == 'CA'){
		$('#province_id_div').show();
	}else{
		$('#province_id_div').hide();
	}
})


$('#billing_country_code').change(function(){
	var country = $(this).val();

	if(country == 'CA'){
		$('#billing_province_id_div').show();
	}else{
		$('#billing_province_id_div').hide();
	}
})
</script>
@endsection