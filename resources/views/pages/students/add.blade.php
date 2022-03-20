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
						<button id="save_btn" name="save_btn" class="btn btn-theme-success"><i class="fa fa-save"></i>{{ __('Save') }} </button>
					</div>
				</div>    
			</div>          
		</header>
		<!-- Tabs navs -->

		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Student Information') }}</button>
				<button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Contact Information') }}</button>
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
				<form action="" class="form-horizontal" id="add_teacher" method="post" role="form"
					 action="{{!empty($school) ? route('school.user_update',[$school->id]): '/'}}"  name="add_teacher" role="form">
					@csrf

					<input type="hidden" name="user_id" value="{{ !empty($exTeacher) ? $exTeacher->id : '' }}">
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Student Personal Information') }}</label>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Status') }}</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" name="availability_select" id="availability_select">
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
										<input class="form-control require" id="nickname" maxlength="50" name="nickname" placeholder="Pseudo" type="text" value="">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="gender_id" id="gender_label_id">{{__('Gender') }} : *</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control require" id="gender_id" name="gender_id">
												@foreach($genders as $key => $gender)
								                    <option value="{{ $key }}">{{ $gender }}</option>
								                @endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Hourly rate applied') }}</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control"id="billing_method_list" name="billing_method_list">
												<option value="E">Event-wise</option>
												<option value="M">Monthly</option>
												<option value="Y">Yearly</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="email" id="email_caption">{{__('Email') }} :</label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span> <input class="form-control" id="email" value="{{$searchEmail}}" name="email" type="text">
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
										<input class="form-control require" id="lastname" name="lastname" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="firstname" id="first_name_label_id">{{__('First Name') }} : <span class="required_sign">*</span></label>
									<div class="col-sm-7">
										<input class="form-control require" id="firstname" name="firstname" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" id="birth_date_label_id">{{__('Birth date') }}:</label>
									<div class="col-sm-7">
										<div class="input-group" id="birth_date_div"> 
											<input id="birth_date" name="birth_date" type="text" class="form-control">
											<span class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group row" id="profile_image">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Profile Image') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control" type="file" id="formFile" onchange="preview()" style="display:none">
										<label for="formFile"><img src="{{ asset('img/default_profile_image.png') }}"  id="frame" width="150px" alt="SpotLogin"></label>
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
										<label class="col-lg-3 col-sm-3 text-left" for="lavel" id="lavel">{{__('Level') }} :</label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control m-bot15" id="lavel" name="lavel">
													<option value="" selected="">Select Level</option>
													<option value="1">Gold</option>
													<option value="2">Silver</option>
													<option value="7">Bronze</option>
													<option value="8">Professional</option>
													<option value="10">Beginner</option>
													<option value="11">Junior-below 5 yrsasdd</option>
												</select>
											</div>
										</div>
									</div>
									<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" id="date_last_level_label_id">{{__('Date last level ASP') }}:</label>
										<div class="col-sm-7">
											<div class="input-group" id="date_last_level_div"> 
												<input id="date_last_level" name="date_last_level" type="text" class="form-control">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="apr_license" id="postal_code_caption">{{__('ARP license') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" id="apr_license" name="apr_license" type="text">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="license_number" id="locality_caption">{{__('License number') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" id="license_number" name="license_number" type="text">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="usp_Level" id="locality_caption">{{__('USP Level') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" id="usp_Level" name="usp_Level" type="text">
										</div>
									</div>
									<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" id="date_last_level_label_id">{{__('Date last level ASP') }}:</label>
										<div class="col-sm-7">
											<div class="input-group" id="date_last_level_usp_div"> 
												<input id="date_last_level_usp" name="date_last_level_usp" type="text" class="form-control">
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
												<textarea class="form-control" cols="60" id="scomment" name="comment" rows="5"></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
			
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
								<input class="form-control" id="street" name="street" type="text">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="street_number" id="street_number_caption">{{__('Street No') }} :</label>
							<div class="col-sm-7">
								<input class="form-control" id="street_number" name="street_number" type="text">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="zip_code" id="postal_code_caption">{{__('Postal Code') }} :</label>
							<div class="col-sm-7">
								<input class="form-control" id="zip_code" name="zip_code" type="text">
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="place" id="locality_caption">{{__('City') }} :</label>
							<div class="col-sm-7">
								<input class="form-control" id="place" name="place" type="text">
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
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="Province_code" id="pays_caption">{{__('Province') }} :</label>
							<div class="col-sm-7">
								<div class="selectdiv">
									<select class="form-control" id="Province_code" name="Province_code">
										<option value="">Select Province</option>
										<option value="3">Alberta</option>
										<option value="2">British Columbia</option>
										<option value="5">Manitoba</option>
										<option value="10">Newfoundland &amp; Labrador</option>
										<option value="12">Northwest territory</option>
										<option value="8">Nova Scotia</option>
										<option value="11">Nunavut</option>
										<option value="6">Ontario</option>
										<option value="9">PEI</option>
										<option value="7">Quebec</option>
										<option value="4">Saskatchewan</option>
										<option value="13">Yukon</option>
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
							<label class="col-lg-3 col-sm-3 text-left" for="street" id="street_caption">{{__('Street') }} :</label>
							<div class="col-sm-7">
								<input class="form-control" id="street" name="street" type="text">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="street_number" id="street_number_caption">{{__('Street No') }} :</label>
							<div class="col-sm-7">
								<input class="form-control" id="street_number" name="street_number" type="text">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="street2" id="street_caption">{{__('Street2') }} :</label>
							<div class="col-sm-7">
								<input class="form-control" id="street2" name="street2" type="text">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="zip_code" id="postal_code_caption">{{__('Postal Code') }} :</label>
							<div class="col-sm-7">
								<input class="form-control" id="zip_code" name="zip_code" type="text">
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="place" id="locality_caption">{{__('City') }} :</label>
							<div class="col-sm-7">
								<input class="form-control" id="place" name="place" type="text">
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
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="Province_code" id="pays_caption">{{__('Province') }} :</label>
							<div class="col-sm-7">
								<div class="selectdiv">
									<select class="form-control" id="Province_code" name="Province_code">
										<option value="">Select Province</option>
										<option value="3">Alberta</option>
										<option value="2">British Columbia</option>
										<option value="5">Manitoba</option>
										<option value="10">Newfoundland &amp; Labrador</option>
										<option value="12">Northwest territory</option>
										<option value="8">Nova Scotia</option>
										<option value="11">Nunavut</option>
										<option value="6">Ontario</option>
										<option value="9">PEI</option>
										<option value="7">Quebec</option>
										<option value="4">Saskatchewan</option>
										<option value="13">Yukon</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="section_header_class">
					<label id="address_caption">{{__('Contact information') }}</label>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="father_phone" id="father_phone">{{__("Father’s phone") }} :</label>
							<div class="col-sm-7">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-phone-square"></i></span> <input class="form-control" id="phone" name="phone" type="text">
								</div>
							</div>
						</div>
						<div class="form-group row">
						<label class="col-lg-3 col-sm-3 text-left" for="mother_phone" id="mother_phone">{{__("Mother's phone") }} :</label>
							<div class="col-sm-7">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-phone-square"></i></span> <input class="form-control" id="phone" name="phone" type="text">
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="student_phone" id="student_phone">{{__("Student's phone:") }} :</label>
							<div class="col-sm-7">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-phone-square"></i></span> <input class="form-control" id="phone" name="phone" type="text">
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="father_email" id="father_email">{{__("Father’s email") }} :</label>
							<div class="col-sm-7">
								<div class="input-group">
									<span class="input-group-addon"><input type="checkbox"></span> <input class="form-control" id="phone" name="phone" type="text"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="mother_email" id="mother_email">{{__("Mother’s email") }} :</label>
							<div class="col-sm-7">
								<div class="input-group">
									<span class="input-group-addon"><input type="checkbox"></span> <input class="form-control" id="phone" name="phone" type="text"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" for="student_email" id="student_email">{{__("Student's email") }} :</label>
							<div class="col-sm-7">
								<div class="input-group">
									<span class="input-group-addon"><input type="checkbox"></span> <input class="form-control" id="phone" name="phone" type="text"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
								</div>
							</div>
						</div>
					</div>
				</div>
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
	$("#date_last_level").datetimepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
	$("#date_last_level_usp").datetimepicker({
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

$(function() { $('.colorpicker').wheelColorPicker({ sliders: "whsvp", preview: true, format: "css" }); });

// save functionality
$('#save_btn').click(function (e) {
		var formData = $('#add_teacher').serializeArray();
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
			$.ajax({
				url: BASE_URL + '/{{$schoolId}}/add-teacher-action',
				data: formData,
				type: 'POST',
				dataType: 'json',
				success: function(response){	
					if(response.status == 1){
						$('#modal_add_teacher').modal('show');
						$("#modal_alert_body").text(response.message);
					}
				}
			})
		}else{
			$('#modal_add_teacher').modal('show');
			$("#modal_alert_body").text('{{ __('Required field is empty') }}');
		}	            
});  
</script>
<script>
	function preview() {
		frame.src = URL.createObjectURL(event.target.files[0]);
	}
</script>
@endsection