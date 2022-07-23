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
				@can('parameters-list')
					<button class="nav-link" id="nav-parameters-tab" data-bs-toggle="tab" data-bs-target="#tab_5" type="button" role="tab" aria-controls="nav-parameters" aria-selected="false">
					{{ __('Parameters')}}
					</button>
				@endcan
				<a class="nav-link" href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{__('coming soon')}}" aria-controls="nav-logo" aria-selected="false">
					{{ __('Sections and prices')}}
				</a>
				<!-- comented for 1st release
				 <button class="nav-link" id="nav-prices-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
					{{ __('Sections and prices')}}
				</button> -->



			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<input type="hidden" id="user_id" name="user_id" value="{{$teacher->user->id}}">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
				<form class="form-horizontal" id="add_teacher" action="{{ route('updateTeacherAction') }}"  method="POST" enctype="multipart/form-data" name="add_teacher" role="form">
					@csrf
					<input type="hidden" id="school_id" name="school_id" value="{{$schoolId}}">
					<input type="hidden" id="school_name" name="school_name" value="{{$schoolName}}">

					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Personal information') }}</label>
						</div>
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
					
					<button type="submit" id="save_btn" name="save_btn" class="btn btn-success teacher_save"><i class="fa fa-save"></i>{{ __('Save') }}</button>
					
				</form>
			</div>
			<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
				<form class="form-horizontal" id="add_price" action="{{ route('selfUpdatePriceAction') }}"  method="POST" enctype="multipart/form-data" name="add_price" role="form">
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
								<!-- <th class="buy"><span>{{__('Buy') }}</span> {{__('The purchase price is the value offered to the teacher for the lesson Sell') }}</th> -->
								<th class="sell"><span>{{__('Sell') }}</span> {{__('The sale price is the sale value to the students') }}</th>
							</tr>
						</thead>
						<tbody>
							@foreach($eventCategory as $key => $category)
							<tr style="background:lightblue;">
								<td></td>
								<td colspan="2"><input class="form-control disable_input" disabled="" id="category_name12" type="hidden" style="text-align:left" value="Soccer-School2"><label><strong>{{$category->title}}</strong></label></td>
								<td><label></label></td>
								<td align="right" colspan="1"></td>
							</tr>
								@foreach($lessonPrices as $key => $lessionPrice)
								<tr>
									<td>{{$lessionPrice->divider}}
										<input type="hidden"
										name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][id]"
										value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['id'] : '' }}"
										>
										<input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_student]" value="{{$lessionPrice->lesson_price_student}}">
										<input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_id]" value="{{$lessionPrice->id}}">
									</td>
									<td>{{__('Lessons/Events..')}}</td>
									@if($lessionPrice->divider == 1)
										<td>{{ __('Private session') }}</td>
									@else
										<td>{{ __('Group lessons for '.$lessionPrice->divider.' students') }}</td>
									@endif

									<!-- <td>
										<input type="text"
										name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][price_buy]"
										value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['price_buy'] : '0.00' }}"
										style="text-align:right" class="form-control numeric float"
										>
									</td> -->
									<td>
										<input type="text"
										name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][price_sell]"
										value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell'] : '0.00' }}"
										style="text-align:right" class="form-control numeric float"
										>
									</td>
								</tr>
								@endforeach
							@endforeach
						</tbody>
						</table>

					
						<button type="submit" id="save_btn" name="save_btn" class="btn btn-success teacher_save"><em class="glyphicon glyphicon-floppy-save"></em> {{ __('Save')}}</button>
					
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
