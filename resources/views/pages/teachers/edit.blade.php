@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/datetimepicker-lang/moment-with-locales.js')}}"></script>
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<!-- color wheel -->
<script src="{{ asset('js/jquery.wheelcolorpicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/wheelcolorpicker.css')}}"/>
<script src="{{ asset('ckeditor/ckeditor.js')}}"></script>

<style type="text/css">
	input[readonly="readonly"] {
	  border: none;
	}
</style>
@endsection

@section('content')
  <div class="content">
	<div class="container-fluid">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area">
					<div class="page_header_class">
						<label id="page_header" name="page_header">{{ __('Teacher Information:') }} {{!empty($relationalData->full_name) ? $relationalData->full_name : ''}}</label>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 btn-area">
					<div class="float-end btn-group">
						<a style="display: none;" id="delete_btn" href="#" class="btn btn-theme-warn"><em class="glyphicon glyphicon-trash"></em> {{ __('Delete')}}</a>
					</div>
				</div>    
			</div>          
		</header>
		<!-- Tabs navs -->

		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
					{{ __('Contact Information') }}
				</button>
				<button class="nav-link" id="nav-logo-tab" data-bs-toggle="tab" data-bs-target="#tab_4" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
					{{ __('Photo')}}
				</button>
				<!-- <a class="nav-link" href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{__('coming soon')}}" aria-controls="nav-logo" aria-selected="false">
					{{ __('Sections and prices')}}
				</a> -->
				<button class="nav-link" id="nav-prices-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
					{{ __('Sections and prices')}}
				</button>
				<!-- <a class="nav-link" href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{__('coming soon')}}" aria-controls="nav-logo" aria-selected="false">
					{{ __('Lesson')}}
				</a> -->
				<button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
					{{ __('Lesson') }}
				</button>
				@can('teachers-users-update')
					@if($teacher->user)
					<!-- <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#tab_4" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">
						{{ __('User Account')}}
					</button> -->
					@endif
				@endcan
			</div>	
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<input type="hidden" id="user_id" name="user_id" value="{{ !empty($teacher->user) ? $teacher->user->id : null }}">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
				<form class="form-horizontal" id="add_teacher" action="{{!empty($teacher) ? route('editTeacherAction',[$teacher->id]): '/'}}"  method="POST" enctype="multipart/form-data" name="add_teacher" role="form">
					@csrf
					<input type="hidden" id="school_id" name="school_id" value="{{$schoolId}}">
					<input type="hidden" id="school_name" name="school_name" value="{{$schoolName}}">
					<input type="hidden" id="smonth" name="smonth" value="0">
					<input type="hidden" id="syear" name="syear" value="0">
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Personal information') }}</label>
						</div>
						<div class="row">
							<div class="col-md-6">
								@hasanyrole('teachers_admin|teachers_all|school_admin|superadmin')
									
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Status') }}</label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control" name="is_active" id="availability_select">
													<option value="">Select</option>
													<option value="1" {{!empty($relationalData->is_active) ?  'selected' : '' }}>{{ __('Active')}}</option>
													<option value="0" {{ ($relationalData->is_active == 0) ? 'selected' : ''}}>{{ __('Inactive')}}</option>
												
												</select>
											</div>
										</div>
									</div>
								@endhasanyrole
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="nickname" id="nickname_label_id">{{__('Nickname') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control require" id="nickname" maxlength="50" name="nickname" placeholder="Pseudo" type="text" 
										value="{{!empty($relationalData->nickname) ? old('nickname', $relationalData->nickname) : old('nickname')}}"
										>
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
								</div>
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
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" id="birth_date_label_id">{{__('Birth date') }}:</label>
									<div class="col-sm-7">
										<div class="input-group" id="birth_date_div"> 
											<input id="birth_date" value="{{!empty($teacher->birth_date) ? date('d/m/Y', strtotime($teacher->birth_date)) : old('birth_date')}}" name="birth_date" type="text" class="form-control">
											<span class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" id="slicence_js_caption">{{__('License number') }} :</label>
									<div class="col-sm-7">
										<input class="form-control" value="{{!empty($relationalData->licence_js) ? old('licence_js', $relationalData->licence_js) : old('licence_js')}}" id="licence_js" name="licence_js" type="text">
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
								<!-- <div class="form-group row" id="shas_user_account_div">
									<div id="shas_user_account_div111" class="row">
										<label class="col-lg-3 col-sm-3 text-left" for="shas_user_account" id="has_user_ac_label_id">{{__('Enable teacher account') }} :</label>
										<div class="col-sm-7">
											<input id="shas_user_account"  name="has_user_account" type="checkbox" value="1" {{!empty($relationalData->has_user_account) ? (old('has_user_account', $relationalData->has_user_account) == 1 ? 'checked' : '') : (old('has_user_account') == 1 ? 'checked' : '')}}>
										</div>
									</div>
								</div> -->
								<div class="form-group row" id="authorisation_div">
										<label class="col-lg-3 col-sm-3 text-left"><span id="autorisation_caption">{{__('Authorization') }} :</span> </label>
									<div class="col-sm-7">
										<b><input id="authorisation_all" name="role_type" type="radio" value="teachers_all" {{ ($relationalData->role_type == 'teachers_all') ? 'checked' : '' }}> ALL<br>
										<input id="authorisation_med" name="role_type" type="radio" value="teachers_medium" {{($relationalData->role_type == 'teachers_medium') ? 'checked' : '' }}> Medium<br>
										<input id="authorisation_min" name="role_type" type="radio" value="teachers_minimum" {{($relationalData->role_type == 'teachers_minimum') ? 'checked' : '' }}> Minimum<br></b>
									</div>
								</div>

								<!-- <div class="form-group row" >
										<label class="col-lg-3 col-sm-3 text-left"></label>
									<div class="col-sm-7">
										@if(!$teacher->user)
					                        <form method="post" style="display: inline;" class="form-inline" onsubmit="return confirm('{{ __("Are you sure want to send Invitation?")}}')" action="{{route('teacherInvitation',['school'=>$schoolId,'teacher'=>$teacher->id])}}">
					                          @method('post')
					                          @csrf
					                          <button  class="btn btn-warning" type="submit" title="Send invitation" ><i class="fa fa-envelope txt-grey">{{ __(" Send invitation")}}</i></button>
					                        </form>
					                    @endif
									</div>
								</div> -->
								<div class="form-group row" id="sbg_color_agenda_div">
									<label class="col-lg-3 col-sm-3 text-left" for="sbg_color_agenda" id="sbg_color_agenda_caption">{{__('Agenda Color') }} :</label>
									<div class="col-sm-2">
										<input type="text" name="bg_color_agenda" value="{{!empty($relationalData->bg_color_agenda) ? $relationalData->bg_color_agenda : old('bg_color_agenda')}}"  class="colorpicker dot" />
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="address_caption">{{__('Address') }}</label>
							</div>
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
											<select class="form-control" id="country_code" name="country_code">
												@foreach($countries as $country)
								                    <option value="{{ $country->code }}" {{!empty($teacher->country_code) ? (old('country_code', $teacher->country_code) == $country->code ? 'selected' : '') : (old('country_code') == $country->code ? 'selected' : '')}}>{{ $country->name }}</option>
								                @endforeach
											</select>
											</div>
										</div>
									</div>
									<div id="province_id_div" class="form-group row" style="display:none">
										<label id="province_caption" for="province_id" class="col-lg-3 col-sm-3 text-left">Province: </label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control" id="province_id" name="province_id">
													<option value="">Select Province</option>
													@foreach($provinces as $province)
														<option value="{{ $province['id'] }}" {{!empty($teacher->province_id) ? (old('province_id', $teacher->province_id) == $province['id'] ? 'selected' : '') : (old('province_id') == $province['id'] ? 'selected' : '')}}>{{ $province['province_name'] }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="contact_info_caption">{{ __('Contact information') }}</label>
							</div>
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
										<label class="col-lg-3 col-sm-3 text-left" for="mobile" id="mobile_caption">{{__('Téléphone mobile') }} :</label>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-mobile"></i></span> 
												<input class="form-control" value="{{!empty($teacher->mobile) ? old('mobile', $teacher->mobile) : old('mobile')}}" id="mobile" name="mobile" type="text">
											</div>
										</div>
									</div>
								</div>
								<!-- <div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="email2" id="email_caption">{{__('Email') }} :</label>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-envelope"></i></span> 
												<input class="form-control" value="{{!empty($teacher->email2) ? old('email2', $teacher->email2) : old('email2')}}" id="email2" name="email2" type="text">
											</div>
										</div>
									</div>
								</div> -->
							</div>


							<div class="clearfix"></div>
							@if($school->country_code != 'CA' )
							<div class="section_header_class">
								<label id="contact_info_caption">{{ __('Teacher Bank Information')}}</label>
							</div>
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
													<option value="">Select</option>
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
							@else
							<div class="section_header_class">
								<label id="contact_info_caption">{{ __('Teacher Bank Information')}}</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="etransfer_acc_div">{{ __('E-transfer email')}}:</label>
										<div class="col-sm-7">
											<input class="form-control" id="etransfer_acc" name="etransfer_acc" type="text"
												value="{{!empty($teacher->etransfer_acc) ? old('etransfer_acc', $teacher->etransfer_acc) : old('etransfer_acc')}}">
												<span class="etransfer_acc"></span>	
										</div>
									</div>
								</div>	
							</div>
							@endif	


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
													<textarea class="form-control" cols="60" id="scomment" name="comment" rows="5"> {{!empty($relationalData->comment) ? old('comment', $relationalData->comment) : old('comment')}} </textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
							@endhasanyrole
						</div>
					</fieldset>
					@can('teachers-update')
						<button type="submit" id="save_btn" name="save_btn" class="btn btn-success teacher_save"><i class="fa fa-save"></i>{{ __('Save') }}</button>
					@endcan
				</form>
			</div>
			<div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tab_3">
				<form role="form" id="form_invoicing" class="form-horizontal" method="post" action="#">
					
					<input type="hidden" name="selected_month" id="selected_month" value="">
					<input type="hidden" name="selected_year" id="selected_year" value="">
					<input type="hidden" name="person_id" id="person_id" value="{{!empty($teacher->id) ? old('person_id', $teacher->id) : old('person_id')}}"> 
					<input type="hidden" name="no_of_teachers" id="no_of_teachers" value="{{!empty($school->max_teachers) ? old('no_of_teachers', $school->max_teachers) : old('no_of_teachers')}}"> 
					
					<div class="row">
						<div id="teacher_disc_perc_div" name="teacher_disc_perc_div">
							
							<div class="form-group row">
								<div class="col-md-8">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left"> {{ __("Commission") }} (%):</label>
										<div class="col-sm-2">
											 
										</div>
										<div class="col-sm-2 offset-md-0"  style="margin-left: 10px;">
											<input id="discount_perc" name="discount_perc" type="text" value="{{!empty($teacher->tax_perc) ? $teacher->tax_perc : 0 }}" class="form-control"> 
										</div>
										<div class="col-sm-1" style="margin-left: 10px;">
											<button id="changer_btn" class="btn btn-sm btn-primary">Modify</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group row">
								<label class="col-lg-3 col-sm-3 text-left"> {{ __('Period') }}:</label>
								<div class="col-sm-2">
									<input class="form-control" name="billing_period_start_date" id="billing_period_start_date"> 
								</div>
								<div class="col-sm-2 offset-md-0"  style="margin-left: 10px;">
									<input class="form-control" name="billing_period_end_date" id="billing_period_end_date"> 
								</div>
								<div class="col-sm-1" style="margin-left: 10px;">
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
						<button style="position: absolute;right: 0;top: -2px;" class="btn btn-primary pull-right" id="btn_convert_invoice">Generate invoice</button>
					</div>
				</form>
			</div>
			<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
				<form class="form-horizontal" id="add_price" action="{{!empty($teacher) ? route('updatePriceAction',[$teacher->id]): '/'}}"  method="POST" enctype="multipart/form-data" name="add_price" role="form">
					@csrf
					<div class="section_header_class">
						<label id="teacher_personal_data_caption">{{__('Number of students') }}</label>
					</div>

					<table id="tariff_table_rate" class="table list-item tariff_table_rate" width="100%">
						<thead>
							<tr>
								<th>#</th>
								<th>{{__('Category Type')}}</th>
								<th>{{__('Type of billing')}}</th>
								<th class="buy"><span>{{__('Teacher price') }}</span></th>
								<th class="sell"><span>{{__('Student price') }}</span></th>
							</tr>
						</thead>
						<tbody>
							@foreach($eventCategory as $key => $category)
							<tr style="background:lightblue;">
								<td></td>
								<td colspan="2"><input class="form-control disable_input" disabled="" id="category_name12" type="hidden" style="text-align:left" value="Soccer-School2"><label><strong>{{$category->title}}</strong></label></td>
								<td>Total price/hour</td>
								<td align="right" colspan="1">price/student/hour</td>
							</tr>
								@foreach($lessonPrices as $key => $lessionPrice)
								
								<?php 
									if ($lessionPrice->divider == 1) {
										$textForTypeBilling = 'Private session';
									}elseif ($lessionPrice->divider == 9999) {
										$textForTypeBilling = 'Student more then 10';
									}elseif ($lessionPrice->divider == -1) {
										$textForTypeBilling = 'Fixed price';
									}else{
										$textForTypeBilling = "Group lessons for {$lessionPrice->divider} students";
									}
									// 0 = hourly 1= fix
									$tacherPrice = $category->s_thr_pay_type;
									$studentPrice = $category->s_std_pay_type; 

									if ( ($tacherPrice == 1) && (in_array($studentPrice, [1,2])) ) { // fix and fix price
										if ($lessionPrice->divider != -1) continue;
									}elseif (($tacherPrice == 0) && ($studentPrice == 0)) { // hourly and hourly
										 if ($lessionPrice->divider == -1) continue;
									}else{
										

									}

								 ?>

									
								<tr class="<?= $lessionPrice->divider != -1 ? 'regular' : 'fix' ?>">
									<td>{{$key+1}}
										<input type="hidden" 
										name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][id]" 
										value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['id'] : '' }}"
										>
										<input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_student]" value="{{$lessionPrice->lesson_price_student}}">
										<input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_id]" value="{{$lessionPrice->id}}">
									</td>
									<td>{{__('Lessons/Events..')}}</td>
									<td>{{ __($textForTypeBilling) }}</td>
									<td>
										<input type="text" 
										name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][price_buy]"  
										value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['price_buy'] : '0.00' }}"
										style="text-align:right" 
										class="form-control numeric float 
										<?= (($tacherPrice == 1) && ($lessionPrice->divider != -1)) || (($tacherPrice == 0) && ($lessionPrice->divider == -1))  ? 'd-none' : '' ?>"
										>
									</td>
									<td>
										<input type="text" 
										name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][price_sell]"  
										value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell'] : '0.00' }}"
										style="text-align:right" 
										class="form-control numeric float 
										<?= ( ($studentPrice == 1) && ($lessionPrice->divider != -1) ) || (($studentPrice == 0) && ($lessionPrice->divider == -1)) ||  ($studentPrice == 2) ? 'd-none' : '' ?>"
										>
									</td>
								</tr>
								@endforeach
							@endforeach
						</tbody>
						</table>

					@can('teachers-update')
						<button type="submit" id="save_btn" name="save_btn" class="btn btn-success teacher_save"><em class="glyphicon glyphicon-floppy-save"></em> {{ __('Save')}}</button>
					@endcan
				</form>
			</div>
			<div class="tab-pane fade" id="tab_4" role="tabpanel" aria-labelledby="tab_4">
				<div class="row">
					<div class="col-sm-12 col-xs-12 header-area">
						<div class="page_header_class">
							<label id="page_header" class="page_title text-black">{{ __('Photo')}}</label>
						</div>
					</div>
				
					<div class="col-md-6">
						<form enctype="multipart/form-data" role="form" id="form_images" class="form-horizontal" method="post" action="#">
							<div class="form-group row">
								<div class="col-sm-8">
									<fieldset>
										<div class="profile-image-cropper responsive">
										<?php if (!empty($teacher->profileImage->path_name)): ?>
											<img id="profile_image_user_account" src="{{ $teacher->profileImage->path_name }}"
													height="128" width="128" class="img-circle"
													style="margin-right:10px;">
										<?php else: ?>
											<img id="profile_image_user_account" src="{{ asset('img/photo_blank.jpg') }}"
													height="128" width="128" class="img-circle"
													style="margin-right:10px;">
										<?php endif; ?>

											@can('teachers-update')
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
														<a id="delete_profile_image" name="delete_profile_image" class="btn btn-theme-warn" style="{{!empty($teacher->profile_image_id) ? '' : 'display:none;'}}">
															<i class="fa fa-trash"></i>
															<span id="delete_image_button_caption">{{ __('Remove Image')}}</span>
														</a>
													</div>
												<?php //endif; ?>
											</div>
											@endcan
										</div>
									</fieldset>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			@if(!empty($teacher->user))
			<div class="tab-pane fade" id="tab_5" role="tabpanel" aria-labelledby="tab_5">
				<form id="teacherUserForm" name="teacherUserForm" class="form-horizontal" role="form"
				 action="{{!empty($teacher) ? route('teacher.user_update',[$teacher->user->id]): '/'}}" method="POST" enctype="multipart/form-data">
					@csrf
					<input type="hidden" id="user_id" name="user_id" value="{{!empty($teacher->user->id) ? old('user_id', $teacher->user->id) : old('user_id')}}">
					<div class="section_header_class">
						<label id="course_for_billing_caption">{{ __('User Account')}}</label>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Name of User')}}:</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" id="admin_username" name="admin_username" value="{{!empty($teacher->user->username) ? old('admin_username', $teacher->user->username) : old('admin_username')}}">      
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">{{ __('Email')}}:</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" id="admin_email" name="admin_email" value="{{!empty($teacher->user->email) ? old('admin_email', $teacher->user->email) : old('admin_email')}}">
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
									<option value="1" {{!empty($teacher->user->is_active) ? (old('admin_is_active', $teacher->user->is_active) == 1 ? 'selected' : '') : (old('admin_is_active') == 1 ? 'selected' : '')}}>{{ __('Active')}}</option>
									<option value="0" {{!empty($teacher->user->is_active) ? (old('admin_is_active', $teacher->user->is_active) == 0 ? 'selected' : '') : (old('admin_is_active') == 0 ? 'selected' : '')}}>{{ __('Inactive')}}</option>
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
							<input type="text" class="form-control" id="email_to_id" name="email_to_id" value="{{!empty($teacher->user->email) ? $teacher->user->email : old('email_to_id')}}">
						
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

					<button type="submit" id="save_btn" name="save_btn" class="btn btn-success teacher_save"><em class="glyphicon glyphicon-floppy-save"></em> {{ __('Save')}}</button>
				</form>
			</div>
			@endif
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
	var saction = getUrlVarsO()["action"];
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
	$("#billing_period_start_date").datetimepicker({
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
	// CKEDITOR.replace( "body_text", {
	// 	customConfig: '/ckeditor/config_email.js',
	// 	height: 300
	// 	,extraPlugins: 'Cy-GistInsert'
	// 	,extraPlugins: 'AppFields'
	// });
	$('#delete_profile_image').click(function (e) {
		DeleteProfileImage();      // refresh lesson details for billing
	})


	// $("#send_email_btn").click(function (e) {
	// 	var user_id = $("#user_id").val();
	// 	var email_to = $("#email_to_id").val(),
	// 			school_name = $("#school_name").val(),
	// 			email_body  = CKEDITOR.instances["body_text"].getData()
	// 	email_body = email_body.replace(/'/g, "''");
	// 	email_body = email_body.replace(/&/g, "<<~>>");
	// 	let loader = $('#pageloader');
 //    	loader.show();

	// 	var teacherUserForm = document.getElementById("teacherUserForm");
	// 	var formdata = $("#teacherUserForm").serializeArray();
	// 	var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';

		
	// 	formdata.push({
	// 			"name": "_token",
	// 			"value": csrfToken
	// 	});
	// 	formdata.push({
	// 			"name": "user_id",
	// 			"value": user_id
	// 	});
	// 	formdata.push({
	// 			"name": "email_body",
	// 			"value": email_body
	// 	});
	// 	formdata.push({
	// 			"name": "school_name",
	// 			"value": school_name
	// 	});
	// 	//console.log(formdata);

	// 	$.ajax({
	// 		url: BASE_URL + '/teacher_email_send',
	// 		data: formdata,
	// 		type: 'POST',
	// 		dataType: 'json',
	// 		async: false,
	// 		encode: true,
	// 		success: function(data) {
	// 			loader.hide();
	// 			if (data.status) {
	// 					successModalCall("{{ __('email_sent')}}");
	// 			} else {
	// 					errorModalCall(data.msg);
	// 			}

	// 		}, // sucess
	// 		error: function(ts) {
	// 			loader.hide();
	// 			errorModalCall('error_message_text');
	// 		}
	// 	});
	
	// });    //contact us button click 














	$('#changer_btn').click(function(e) {
		var p_person_id=document.getElementById("person_id").value;
		var p_disc1 = document.getElementById('discount_perc').value;

		$.ajax({
			url: BASE_URL + '/teacher_update_discount_perc',
			//$.ajax({
			//url: 'insert_update_delete_teacher.php',
			data: 'type=update_discount_perc&p_disc1=' + p_disc1+"&p_person_id="+p_person_id,
			type: 'POST',
			dataType: 'json',
			async: false,
			success: function(response) {
				if (response.status == 'success')
					successModalCall('save_confirm_message');
			},
			error: function(e) {
				errorModalCall(GetAppMessage('error_message_text'));
				// alert('Error processing your request: ' + e.responseText + ' DeleteProfileImage');
			}
		});
		return false;

	})

	populate_teacher_lesson();

	$('#billing_period_search_btn').on('click', function() {
		populate_teacher_lesson(); //refresh lesson details for billing	
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
		$(this).attr("disabled", "disabled");
		var p_event_ids = GetCheckBoxSelectedValues('event_check');
		if (p_event_ids == '') {
			//DisplayMessage("Sélectionnez au moins un élément pour générer la facture.");
			return false;
		}
	  var p_person_id = document.getElementById("person_id").value;
		let school_id = document.getElementById("school_id").value;

	    var from_date = moment(($("#billing_period_start_date").val()),"DD/MM/YYYY").format("YYYY.MM.DD");
		var to_date = moment(($("#billing_period_end_date").val()),"DD/MM/YYYY").format("YYYY.MM.DD");
		
	    var p_invoice_id = '';
	    var auto_id = 0;
	    var p_month = document.getElementById("smonth").value;
	    var p_year = document.getElementById("syear").value;

			var p_billing_period_start_date = $("#billing_period_start_date").val();
			var p_billing_period_end_date = $("#billing_period_end_date").val();

	    var p_discount_perc = document.getElementById('discount_perc').value;

	    data = 'type=generate_teacher_invoice&school_id=' + school_id + '&p_person_id=' + p_person_id + '&p_invoice_id=' + p_invoice_id + '&p_month=' + p_month + '&p_year=' + p_year + '&p_discount_perc=' + p_discount_perc+'&p_billing_period_start_date='+from_date+'&p_billing_period_end_date='+to_date+ '&p_event_ids=' + p_event_ids;
	    
		$.ajax({
			url: BASE_URL + '/generate_teacher_invoice',
			
			//url: '../teacher/teacher_events_data.php',
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
					errorModalCall(GetAppMessage('error_message_text'));
					// alert(value.status);
				}
				
				//location.reload();
			}, // success
			error: function(ts) {
				errorModalCall(GetAppMessage('error_message_text'));
				// alert(ts.responseText + ' Generate Invoice')
			}
	    }); // Ajax
	    if (auto_id > 0) {
	      	var url = "/admin/"+document.getElementById("school_id").value+"/modification-invoice/"+auto_id;
			setTimeout(function(){ 
				window.location = BASE_URL+ url;  
				}, 3000);
			
			return false;
	    }

	    return false;
	});





})

$(function() { 
	$('.colorpicker').wheelColorPicker({ sliders: "whsvp", preview: true, format: "css" }); 
	$('.colorpicker').wheelColorPicker('value', "{{ $relationalData->bg_color_agenda }}");


	var vtab=getUrlVarsO()["tab"];
	if (typeof vtab === "undefined") {
		vtab='';
	}
	if (vtab == 'tab_3') {
		document.getElementById("delete_btn").style.display="none";
		document.getElementById("save_btn").style.display="none";					
		activaTab('tab_3');
	}

});

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
	formData.append('teacher_id', {{ $teacher->id }});
	
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
		data: 'teacher_id=' + {{ $teacher->id }},
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

function populate_teacher_lesson() {
	var record_found = 0,
	all_ready = 1,
	total_buy = 0,
	total_sell = 0,
	week_total_buy = 0,
	week_total_sell = 0,
	cost_1 = 0.00,
	cost_2 = 0.00,
	prev_week = '',
	data = '',
	p_person_id = document.getElementById("person_id").value,
	school_id = document.getElementById("school_id").value,
	p_month = document.getElementById("smonth").value,
	p_year = document.getElementById("syear").value,
	p_billing_period_start_date = document.getElementById("billing_period_start_date").value,
	p_billing_period_end_date = document.getElementById("billing_period_end_date").value;

	var disc_caption = 'DISC';
	var disc_caption_disp = '';
	var week_caption = 'week';
	var month_caption = 'month';
	var sub_total_caption = 'Sub Total';
	if ((p_billing_period_start_date == '') || (p_billing_period_end_date == '')) {
		resultHtml = '<tbody><tr class="lesson-item-list-empty"> <td colspan="12">..</td></tr></tbody>';
		$('#lesson_table').html(resultHtml);
		document.getElementById("lesson_footer_div").style.display = "none";
		return false;
	}

	var invoice_already_generated = 0,
		person_type = 'teacher_lessons';

	var disc1_amt = 0;
	let selected_items = 0;
	var resultHtml = '',
		resultHtmlHeader = '',
		resultHtmlFooter = '',
		resultHtmlDetails = '';
	//resultHtml='<tr><td colspan="8"><font color="blue"><h5> Cours disponibles à la facturation</h5></font></tr>';
	data = 'type=' + person_type + '&school_id=' + school_id + '&p_person_id=' + p_person_id + '&p_billing_period_start_date=' + p_billing_period_start_date + '&p_billing_period_end_date=' + p_billing_period_end_date;

	$.ajax({
		url: BASE_URL + '/get_teacher_lessons',
		//url: '../teacher/teacher_events_data.php',
		data: data,
		type: 'POST',
		dataType: 'json',
		async: false,
		success: function(result) {
			$.each(result.data, function(key, value) {
				record_found += 1;


				// week summary
				if ((prev_week != '') && (prev_week != value.week_name)) {
					resultHtml += '<tr style="font-weight: bold;"><td colspan="4">';
					resultHtml += '<td colspan="2">' + sub_total_caption + ' ' + week_caption + ' </td>';
					resultHtml += '<td style="text-align:right">' + week_total_buy.toFixed(2) + '</td>';
					//resultHtml+='<td style="text-align:right">'+week_total_sell.toFixed(2)+'</td>';
					resultHtml += '</tr>'
					week_total_buy = 0;
					week_total_sell = 0;
				}

				if (prev_week != value.week_name) {
					//resultHtml+='<b><tr class="course_week_header"><td colspan="10">'+week_caption+' '+value.week_no+'</td></tr></b>';
					resultHtml += '<b><tr class="course_week_header"><td colspan="1">' + week_caption + ' ' + value.week_no + '</td>';
					//resultHtml += '<b><td colspan="1">Date</td>';
					resultHtml += '<b><td colspan="1"></td>';
					resultHtml += '<b><td colspan="1"></td>';
					resultHtml += '<b><td colspan="1"></td>';
					resultHtml += '<b><td colspan="1"></td>';
					resultHtml += '<b><td colspan="1"></td>';
					resultHtml += '<td colspan="2" style="text-align:right">' + '' + '</td>';
					resultHtml += '<td style="text-align:right" colspan="3">Extra Charges</td></tr></b>';;
				}
				resultHtml += '<tr>';
				resultHtml += '<td style="display:none;">' + value.detail_id + '</td>';
				if ((value.is_buy_invoiced == 0) && (value.ready_flag == 1)) {
						selected_items += 1;
						resultHtml += "<td><input class='event_class' type=checkbox id='event_check' name='event_check' checked value=" + value.event_id + "></td>";
				} else {
						resultHtml += "<td>-</td>";
				}
				resultHtml += '<td width="10%">' + value.date_start + '</td>';
				resultHtml += '<td>' + value.time_start + '</td>';
				resultHtml += '<td>' + value.duration_minutes + ' minutes </td>';
				if (value.event_type == 100) {
					resultHtml += '<td>Event</td>';
				} else {
					resultHtml += '<td>Lesson</td>';
				}
				
				resultHtml += '<td>' + value.student_name + '</td>';
				//resultHtml += '<td>' + value.title + '</td>';
				resultHtml += '<td>' + value.price_name + '</td>';

				// all_ready = 0 means not ready to generate invoice
				if (value.ready_flag == "0") {
					all_ready = 0;
					//resultHtml+="<td></td>";
					if (value.event_type == 100) {
						resultHtml += "<td colspan='2'><a id='correct_btn' class='button_lock_and_save' href='/"+school_id+"/edit-event/"+value.event_id+"/?redirect_url="+CURRENT_URL+"' class='btn btn-xs btn-info'> <em class='glyphicon glyphicon-pencil'></em>Validate</a>";
					} else {
						resultHtml += "<td colspan='2'><a id='correct_btn' class='button_lock_and_save' href='/"+school_id+"/edit-lesson/"+value.event_id+"/?redirect_url="+CURRENT_URL+"' class='btn btn-xs btn-info'> <em class='glyphicon glyphicon-pencil'></em>Validate</a>";
					}
				} else {
					resultHtml += '<td style="text-align:right" colspan="2">' + value.price_currency + ' ' + value.buy_total + '</td>';
					//resultHtml+='<td style="text-align:right">' + value.price_currency + ' ' + value.sell_total + '</td>';
					total_buy += value.buy_total + value.costs_1;
					week_total_buy += value.buy_total + value.costs_1;
				}
				if (value.extra_charges != 0) {
					resultHtml += '<td style="text-align:right" colspan="3">' + value.costs_1 + '</td>';
				} else {
					//resultHtml += '<td style="text-align:right"></td>';
					resultHtml+='<td style="text-align:right" colspan="3"></td>';
				}

				resultHtml += '</tr>';
				

				prev_week = value.week_name;
				// for teacher
				if (value.is_buy_invoiced != 0) {
					invoice_already_generated = 1;
				}
			}); //for each record
		}, // success
		error: function(ts) {
			errorModalCall(GetAppMessage('invalid_person_id'));
			//   alert(ts.responseText + ' populate_teacher_lesson')
		}
	}); // Ajax
	if (record_found > 0) {

		// summary for last week of course records
		if ((week_total_buy > 0) || (week_total_sell > 0)) {
			resultHtml += '<tr style="font-weight: bold;"><td colspan="4">';
			resultHtml += '<td colspan="2">' + sub_total_caption + ' ' + week_caption + ' </td>';
			resultHtml += '<td style="text-align:right">' + week_total_buy.toFixed(2) + '</td>';
			resultHtml += '</tr>'
			week_total_buy = 0;
			week_total_sell = 0;
		}

		// display grand total
		resultHtml += '<tr style="font-weight: bold;"><td colspan="4">';
		resultHtml += '<td colspan="2">' + sub_total_caption + ': </td>';
		resultHtml+='<td style="text-align:right" colspan="2">'+total_buy.toFixed(2)+'</td>'; 
		resultHtml+='<td style="text-align:right" colspan="3"></td>';    
		//resultHtml += '<td style="text-align:right">' + total_sell.toFixed(2) + '</td>';
		resultHtml += '</tr>'


		var disc1_perc = 0;
		var amt_for_disc = 0.00;
		amt_for_disc = Number(total_buy);
		disc1_perc = document.getElementById("discount_perc").value;

		if (Number(disc1_perc) > 0) {
			disc1_amt = ((amt_for_disc * disc1_perc) / 100);
		}
		//Retenue de 10% sur tranche 0.00 à 0.00 soit -0.00
		// if (disc1_amt > 0) {
		// 	disc_caption_disp = disc_caption;
		// 	disc_caption_disp = disc_caption_disp.replace("[~~SYSTEM_DISC_PERC~~]", disc1_perc);
		// 	disc_caption_disp = disc_caption_disp.replace("[~~SYSTEM_RANGE_FROM~~]", '0.00');
		// 	disc_caption_disp = disc_caption_disp.replace("[~~SYSTEM_RANGE_TO~~]", '0.00');
		// 	disc_caption_disp = disc_caption_disp.replace("[~~SYSTEM_AMOUNT~~]", disc1_amt.toFixed(2));
		// 	resultHtml += '<tr><td colspan="8">' + disc_caption_disp + '</tr>';
		// 	//resultHtml+='<tr><td colspan="8">Réduction de '+disc1_perc+'% sur tranche 0.00 à 0.00 soit -'+disc1_amt.toFixed(2)+'</tr>';
		// }

		total_disc = disc1_amt;
		total_buy = total_buy - total_disc;
		//console.log(total_buy);
		if (total_disc > 0) {
			resultHtml += '<tr><td colspan="4">';
			//resultHtml+='<td colspan="2">Montant total de la réduction:';
			resultHtml += '<td colspan="2"><strong>Total Commission</strong></td>';
			resultHtml += '<td style="text-align:right" colspan="2">-' + total_disc.toFixed(2) + '</tr>';
		}

		// display grand total
		resultHtml += '<tr style="font-weight: bold;"><td colspan="4">';
		resultHtml += '<td colspan="2">Total</td>';
		resultHtml += '<td style="text-align:right" colspan="2">' + total_buy.toFixed(2) + '</td>';
		resultHtml+='<td style="text-align:right" colspan="3"></td>';   
		//resultHtml+='<td style="text-align:right">'+total_buy.toFixed(2)+'</td>';
		resultHtml += '</tr>'

		//display grand total
		$('#lesson_table').html(resultHtml);
		//alert(all_ready);
		if (all_ready == 1) {
			if (invoice_already_generated == 1) {
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
		resultHtml = '<tbody><tr class="lesson-item-list-empty"> <td colspan="12"></td></tr></tbody>';
		$('#lesson_table').html(resultHtml);
		document.getElementById("lesson_footer_div").style.display = "none";
	}
} // populate_teacher_lesson


// save functionality
// $('#save_btn').click(function (e) {
// 		var formData = $('#add_teacher').serializeArray();
// 		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
// 		var error = '';
// 		$( ".form-control.require" ).each(function( key, value ) {
// 			var lname = $(this).val();
// 			if(lname=='' || lname==null || lname==undefined){
// 				$(this).addClass('error');
// 				error = 1;
// 			}else{
// 				$(this).removeClass('error');
// 				error = 0;
// 			}
// 		});
// 		formData.push({
// 			"name": "_token",
// 			"value": csrfToken,
// 		});
// 		if(error < 1){	
// 			$.ajax({
// 				url: BASE_URL + '/add-teacher-action',
// 				data: formData,
// 				type: 'POST',
// 				dataType: 'json',
// 				success: function(response){	
// 					if(response.status == 1){
// 						$('#modal_add_teacher').modal('show');
// 						$("#modal_alert_body").text('{{ __('Sauvegarde réussie') }}');
// 					}
// 				}
// 			})
// 		}else{
// 			$('#modal_add_teacher').modal('show');
// 			$("#modal_alert_body").text('{{ __('Required field is empty') }}');
// 		}	            
// });  
$('#country_code').change(function(){
	var country = $(this).val();

	if(country == 'CA'){
		$('#province_id_div').show();
	}else{
		$('#province_id_div').hide();
	}
})
</script>
@endsection