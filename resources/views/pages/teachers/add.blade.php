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
						<a style="display: none;" id="delete_btn" href="#" class="btn btn-theme-warn"><em class="glyphicon glyphicon-trash"></em> {{ __('Delete:') }}</a>
						<button id="save_btn" name="save_btn" class="btn btn-theme-success"><i class="fa fa-save"></i>{{ __('Save') }} </button>
					</div>
				</div>    
			</div>          
		</header>
		<!-- Tabs navs -->
		<div>
			<!-- user email check start -->
				<form action="" class="form-horizontal" action="{{ auth()->user()->isSuperAdmin() ? route('admin.teachers.create',[$schoolId]) : route('teachers.create')}}" method="post" action="" role="form">
					@csrf
					<div class="form-group row">
						<label class="col-lg-3 col-sm-3 text-left" for="email" id="email_caption">{{__('Teachers Find') }} <i class="fa fa-info-circle" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Search for autopulate teacher information')}}"></i> : </label>
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
		</div>

	{{-- @if(!empty($searchEmail)) --}}
		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Contact Information') }}</button>
				<a class="nav-link"  type="button" href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{__('coming soon')}}" >{{ __('Sections and prices') }}</a>
				<!-- <button class="nav-link" id="nav-rate-tab" data-bs-toggle="tab" data-bs-target="#tab_rate" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Sections and prices') }}</button> -->
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<form action="" class="form-horizontal" id="add_teacher" method="post" role="form"
			 action="{{!empty($school) ? route('school.user_update',[$school->id]): '/'}}"  name="add_teacher" role="form">
			@csrf
			<div class="tab-content" id="ex1-content">
				<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
					<input type="hidden" name="user_id" value="{{ !empty($exUser) ? $exUser->id : '' }}">
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
												<select class="form-control" name="availability_select" id="availability_select">
													<option value="10">Active</option>
													<option value="0">Inactive</option>
													<option value="-9">Deleted</option>
												</select>
											</div>
										</div>
									</div>
								@endhasanyrole
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
									<label class="col-lg-3 col-sm-3 text-left" for="lastname" id="family_name_label_id">{{__('Family Name') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control require" value="{{ $exTeacher ? $exTeacher->lastname : '' }}" {{ $exTeacher ? 'disabled' : '' }} id="lastname" name="lastname" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="firstname" id="first_name_label_id">{{__('First Name') }} : <span class="required_sign">*</span></label>
									<div class="col-sm-7">
										<input class="form-control require" value="{{ $exTeacher ? $exTeacher->firstname : '' }}" {{ $exTeacher ? 'disabled' : '' }} id="firstname" name="firstname" type="text">
									</div>
								</div>

							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" id="birth_date_label_id">{{__('Birth date') }}:</label>
									<div class="col-sm-7">
										<div class="input-group" id="birth_date_div"> 
											<input id="birth_date" name="birth_date" value="{{ $exTeacher ? $exTeacher->birth_date : '' }}" {{ $exTeacher ? 'disabled' : '' }} type="text" class="form-control">
											<span class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" id="slicence_js_caption">{{__('License number') }} :</label>
									<div class="col-sm-7">
										<input class="form-control" id="licence_js" value="{{ $exTeacher ? $exTeacher->licence_js : '' }}" {{ $exTeacher ? 'disabled' : '' }}  name="licence_js" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="email" id="email_caption">{{__('Email') }} : <i class="fa fa-info-circle" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('If you enter an email, the teacher will receive an email with instructions to connect to his teacher account.')}}"></i></label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span> <input class="form-control" id="email" value="{{$searchEmail}}" name="email" type="text">
										</div>
									</div>
								</div>

							@if(empty($exTeacher))
								<!-- <div class="form-group row" id="shas_user_account_div">
									<div id="shas_user_account_div111" class="row">
										<label class="col-lg-3 col-sm-3 text-left" for="shas_user_account" id="has_user_ac_label_id">{{__('Enable teacher account') }} :</label>
										<div class="col-sm-7">
											<input id="shas_user_account" name="has_user_account" type="checkbox" value="1">
										</div>
									</div>
								</div> -->
							@endif
								<div class="form-group row" id="authorisation_div">
										<label class="col-lg-3 col-sm-3 text-left"><span id="autorisation_caption">{{__('Authorization') }} :</span> </label>
									<div class="col-sm-7">
										<b><input id="authorisation_all" name="role_type" type="radio" value="teachers_all"> ALL<br>
										<input id="authorisation_med" name="role_type" type="radio" value="teachers_medium"> Medium<br>
										<input checked="true" id="authorisation_min" name="role_type" type="radio" value="teachers_minimum"> Minimum<br></b>
									</div>
								</div>
								<div class="form-group row" id="sbg_color_agenda_div">
									<label class="col-lg-3 col-sm-3 text-left" for="sbg_color_agenda" id="sbg_color_agenda_caption">{{__('Agenda Color') }} :</label>
									<div class="col-sm-2">
										<input type="text" name="bg_color_agenda" class="colorpicker dot" />
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
											<input class="form-control" value="{{ $exTeacher ? $exTeacher->street : '' }}" {{ $exTeacher ? 'disabled' : '' }}  id="street" name="street" type="text">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="street_number" id="street_number_caption">{{__('Street No') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" value="{{ $exTeacher ? $exTeacher->street_number : '' }}" {{ $exTeacher ? 'disabled' : '' }}  id="street_number" name="street_number" type="text">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="zip_code" id="postal_code_caption">{{__('Postal Code') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" value="{{ $exTeacher ? $exTeacher->zip_code : '' }}" {{ $exTeacher ? 'disabled' : '' }}  id="zip_code" name="zip_code" type="text">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="place" id="locality_caption">{{__('City') }} :</label>
										<div class="col-sm-7">
											<input class="form-control" value="{{ $exTeacher ? $exTeacher->place : '' }}" {{ $exTeacher ? 'disabled' : '' }}  id="place" name="place" type="text">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="country_code" id="pays_caption">{{__('Country') }} :</label>
										<div class="col-sm-7">
											<div class="selectdiv">
											<select class="form-control" {{ $exTeacher ? 'disabled' : '' }} id="country_code" name="country_code">
												@foreach($countries as $country)
								                    <option {{ $exTeacher && ($exTeacher->country_code == $country->code) ? 'selected' : '' }} value="{{ $country->code }}">{{ $country->name }}</option>
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
													@foreach($provinces as $key => $province)
														<option value="{{ $key }}" {{ old('province_id') == $key ? 'selected' : ''}}>{{ $province }}</option>
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
												<input class="form-control" value="{{ $exTeacher ? $exTeacher->phone : '' }}" {{ $exTeacher ? 'disabled' : '' }}  id="phone" name="phone" type="text">
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
												<input class="form-control" value="{{ $exTeacher ? $exTeacher->mobile : '' }}" {{ $exTeacher ? 'disabled' : '' }}  id="mobile" name="mobile" type="text">
											</div>
										</div>
									</div>
								</div>
								<!-- <div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="email2" id="email_caption">{{__('Email') }} :</label>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-envelope"></i></span> <input class="form-control" id="email2"  name="email2" type="text">
											</div>
										</div>
									</div>
								</div> -->
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
				</div>
				<div class="tab-pane fade show" id="tab_rate" role="tabpanel" aria-labelledby="tab_1">
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
										value=""
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
										value="0.00"
										style="text-align:right" class="form-control numeric float"
										>
									</td> -->
									<td>
										<input type="text" 
										name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][price_sell]"  
										value="0.00"
										style="text-align:right" class="form-control numeric float requr"
										>
									</td>
								</tr>
								@endforeach
							@endforeach
						</tbody>
						</table>
				</div>
			</div>
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
});

$(function() { $('.colorpicker').wheelColorPicker({ sliders: "whsvp", preview: true, format: "css" }); });

// save functionality
$('#save_btn').click(function (e) {
		var formData = $('#add_teacher').serializeArray();
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
		// var error = 2;
		var error = 0;
		
		$( ".form-control.requr" ).each(function( key, value ) {
			var lname = +$(this).val();
			console.log(key, lname)
			if (lname > 0 ) {
				error = 0;
			}

			// if(lname=='' || lname==null || lname==undefined || lname==0 ){
			// 	$(this).addClass('error');
			// 	error = 2;
			// }else{
			// 	$(this).removeClass('error');
			// }
		});
		$( ".form-control.require" ).each(function( key, value ) {
			var lname = $(this).val();
			if(lname=='' || lname==null || lname==undefined){
				$(this).addClass('error');
				error = 1;
			}else{
				$(this).removeClass('error');
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
				beforeSend: function( xhr ) {
				    $("#pageloader").show();
				 },
				success: function(response){	
					if(response.status == 1){
						$('#modal_add_teacher').modal('show');
						$("#modal_alert_body").text(response.message);
						window.location.href = window.location.href
					}
				},
				complete: function( xhr ) {
				    $("#pageloader").hide();
				}
			})
		}else if (error == 2){
			$("#nav-rate-tab").click();
			$('#modal_add_teacher').modal('show');
			$("#modal_alert_body").text('{{ __('warning: you didnt fill the lesson and rate page, the lessons will be invoiced at 0') }}');
		}else{
			$("#nav-home-tab").click();
			$('#modal_add_teacher').modal('show');
			$("#modal_alert_body").text('{{ __('Required field is empty') }}');
		}	            
});  

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