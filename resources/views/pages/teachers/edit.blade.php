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
						<label id="page_header" name="page_header">{{ __('Teacher Information:') }}</label>
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
				<button class="nav-link" id="nav-prices-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
					{{ __('Sections and prices')}}
				</button>
				<button class="nav-link" id="nav-logo-tab" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
					{{ __('Logo')}}
				</button>
				<button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#tab_4" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">
					{{ __('User Account')}}
				</button>
			</div>	
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
				<form class="form-horizontal" id="add_teacher" action="{{!empty($teacher) ? route('editTeacherAction',[$teacher->id]): '/'}}"  method="POST" enctype="multipart/form-data" name="add_teacher" role="form">
					@csrf
					<input type="hidden" name="school_id" value="{{$schoolId}}">
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Personal information') }}</label>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group row">
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
								</div>
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
											<input id="birth_date" value="{{!empty($teacher->birth_date) ? old('birth_date', $teacher->birth_date) : old('birth_date')}}" name="birth_date" type="text" class="form-control">
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
								<div class="form-group row" id="shas_user_account_div">
									<div id="shas_user_account_div111" class="row">
										<label class="col-lg-3 col-sm-3 text-left" for="shas_user_account" id="has_user_ac_label_id">{{__('Enable teacher account') }} :</label>
										<div class="col-sm-7">
											<input id="shas_user_account"  name="has_user_account" type="checkbox" value="1" {{!empty($relationalData->has_user_account) ? (old('has_user_account', $relationalData->has_user_account) == 1 ? 'checked' : '') : (old('has_user_account') == 1 ? 'checked' : '')}}>
										</div>
									</div>
								</div>
								<div class="form-group row" id="authorisation_div">
										<label class="col-lg-3 col-sm-3 text-left"><span id="autorisation_caption">{{__('Authorization') }} :</span> </label>
									<div class="col-sm-7">
										<b><input id="authorisation_all" name="role_type" type="radio" value="teachers_all" {{ ($relationalData->role_type == 'teachers_all') ? 'checked' : '' }}> ALL<br>
										<input id="authorisation_med" name="role_type" type="radio" value="teachers_medium" {{($relationalData->role_type == 'teachers_medium') ? 'checked' : '' }}> Medium<br>
										<input id="authorisation_min" name="role_type" type="radio" value="teachers_minimum" {{($relationalData->role_type == 'teachers_minimum') ? 'checked' : '' }}> Minimum<br></b>
									</div>
								</div>
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
						</div>
					</fieldset>
					<button type="submit" id="save_btn" name="save_btn" class="btn btn-success teacher_save"><em class="glyphicon glyphicon-floppy-save"></em> {{ __('Save')}}</button>
				</form>
			</div>
			<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
				<form class="form-horizontal" id="add_price" action=""  method="POST" enctype="multipart/form-data" name="add_price" role="form">
					@csrf
					<div class="section_header_class">
						<label id="teacher_personal_data_caption">{{__('Number of students') }}</label>
					</div>

					<table id="tariff_table_rate" class="table list-item tariff_table_rate" width="100%">
						<thead>
							<tr>
								<th>#</th>
								<th>{{__('Type of course')}}</th>
								<th>{{__('Hourly rate applied')}}</th>
								<th class="buy"><span>{{__('Buy') }}</span> {{__('The purchase price is the value offered to the teacher for the lesson Sell') }}</th>
								<th class="sell"><span>{{__('Sell') }}</span> {{__('The sale price is the sale value to the students') }}</th>
							</tr>
						</thead>
						<tbody>
							<tr style="background:lightblue;">
								<td></td>
								<td colspan="2"><input class="form-control disable_input" disabled="" id="category_name12" type="hidden" style="text-align:left" value="Soccer-School2"><label><strong>Soccer-School2</strong></label></td>
								<td><label></label></td>
								<td align="right" colspan="1"></td>
							</tr>
							<tr>
								<td>8<input type="hidden" name="price_id" value="price_8"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 8 élèves</td>
								<td><input id="price_buy19" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell19" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>9<input type="hidden" name="price_id" value="price_9"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 9 élèves</td>
								<td><input id="price_buy20" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell20" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>10<input type="hidden" name="price_id" value="price_10"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 10 élèves</td>
								<td><input id="price_buy21" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell21" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr style="background:lightblue;">
								<td></td>
								<td colspan="2"><input class="form-control disable_input" disabled="" id="category_name22" type="hidden" style="text-align:left" value="Football-School"><label><strong>Football-School</strong></label></td>
								<td><label></label></td>
								<td align="right" colspan="1"></td>
							</tr>
							<tr>
								<td>1<input type="hidden" name="price_id" value="price_1"></td>
								<td>Lessons/Events..</td>
								<td>Cours privé</td>
								<td><input id="price_buy22" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell22" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>2<input type="hidden" name="price_id" value="price_2"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 2 élèves</td>
								<td><input id="price_buy23" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell23" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>3<input type="hidden" name="price_id" value="price_3"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 3 élèves</td>
								<td><input id="price_buy24" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell24" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>4<input type="hidden" name="price_id" value="price_4"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 4 élèves</td>
								<td><input id="price_buy25" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell25" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>5<input type="hidden" name="price_id" value="price_5"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 5 élèves</td>
								<td><input id="price_buy26" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell26" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>6<input type="hidden" name="price_id" value="price_6"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 6 élèves</td>
								<td><input id="price_buy27" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell27" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>7<input type="hidden" name="price_id" value="price_7"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 7 élèves</td>
								<td><input id="price_buy28" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell28" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>8<input type="hidden" name="price_id" value="price_8"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 8 élèves</td>
								<td><input id="price_buy29" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell29" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>9<input type="hidden" name="price_id" value="price_9"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 9 élèves</td>
								<td><input id="price_buy30" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell30" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>10<input type="hidden" name="price_id" value="price_10"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 10 élèves</td>
								<td><input id="price_buy31" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell31" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr style="background:lightblue;">
								<td></td>
								<td colspan="2"><input class="form-control disable_input" disabled="" id="category_name32" type="hidden" style="text-align:left" value="test cat SCHOOL"><label><strong>test cat SCHOOL</strong></label></td>
								<td><label></label></td>
								<td align="right" colspan="1"></td>
							</tr>
							<tr>
								<td>1<input type="hidden" name="price_id" value="price_1"></td>
								<td>Lessons/Events..</td>
								<td>Cours privé</td>
								<td><input id="price_buy32" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell32" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>2<input type="hidden" name="price_id" value="price_2"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 2 élèves</td>
								<td><input id="price_buy33" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell33" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>3<input type="hidden" name="price_id" value="price_3"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 3 élèves</td>
								<td><input id="price_buy34" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell34" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>4<input type="hidden" name="price_id" value="price_4"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 4 élèves</td>
								<td><input id="price_buy35" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell35" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>5<input type="hidden" name="price_id" value="price_5"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 5 élèves</td>
								<td><input id="price_buy36" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell36" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>6<input type="hidden" name="price_id" value="price_6"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 6 élèves</td>
								<td><input id="price_buy37" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell37" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>7<input type="hidden" name="price_id" value="price_7"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 7 élèves</td>
								<td><input id="price_buy38" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell38" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>8<input type="hidden" name="price_id" value="price_8"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 8 élèves</td>
								<td><input id="price_buy39" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell39" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>9<input type="hidden" name="price_id" value="price_9"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 9 élèves</td>
								<td><input id="price_buy40" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell40" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
							<tr>
								<td>10<input type="hidden" name="price_id" value="price_10"></td>
								<td>Lessons/Events..</td>
								<td>Cours collectif de 10 élèves</td>
								<td><input id="price_buy41" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
								<td><input id="price_sell41" type="text" style="text-align:right" class="form-control numeric float" value="0.00"></td>
							</tr>
						</tbody>
						</table>
					<button type="submit" id="save_btn" name="save_btn" class="btn btn-success teacher_save"><em class="glyphicon glyphicon-floppy-save"></em> {{ __('Save')}}</button>
				</form>
			</div>
			<div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tab_3">
			{{ __('Logo')}}
			</div>
			<div class="tab-pane fade" id="tab_4" role="tabpanel" aria-labelledby="tab_4">
			{{ __('User Account')}}
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
});

$(function() { 
	$('.colorpicker').wheelColorPicker({ sliders: "whsvp", preview: true, format: "css" }); 
	$('.colorpicker').wheelColorPicker('value', "{{ $relationalData->bg_color_agenda }}");
});

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
</script>
@endsection