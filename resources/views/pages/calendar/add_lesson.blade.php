@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<script src="{{ asset('js/jquery.multiselect.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/jquery.multiselect.css') }}">
@endsection

@section('content')
  <div class="content">
	<div class="container-fluid">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area">
					<div class="page_header_class">
						<label id="page_header" class="page_header bold" name="page_header">{{ __('Lesson') }} : <i class="fa fa-plus-square" aria-hidden="true"></i></label>
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
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Contact Information') }}</button>
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
				<form action="" class="form-horizontal" id="add_teacher" method="post" role="form"
					 action="{{!empty($school) ? route('school.user_update',[$school->id]): '/'}}"  name="add_teacher" role="form">
					@csrf
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Lesson information') }}</label>
						</div>
						<div class="row">
							<div class="col-md-7 offset-md-2">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Type') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="category_select" name="category_select">
											<option category_type="T" value="10">Ice Skating-School</option>
											<option category_type="S" value="11">Soccer-School</option>
											<option category_type="T" value="92">Ice Skating-Teacher</option>
											<option category_type="S" value="109">Football-School</option>
											<option category_type="T" value="110">Football-Coach</option>
											<option category_type="S" value="111">test cat SCHOOL</option>
											<option category_type="T" value="118">sacasc</option>
											<option category_type="T" value="119">test</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Location') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="location" name="location">
												<option value="0"></option>
												<option value="26">kolkatass</option>
												<option value="27">joka</option>
												<option value="31">Tollygunge</option>
												<option value="32">Diamond Park</option>
												<option value="33">Ballygunge</option>
												<option value="37">pune</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Professor') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="teacher_select" name="teacher_select">
												<option value="EC7E9C27-1B10-11EC-9CF6-067B4964D503">Arindam (Biswas)</option>
												<option value="3330B801-1EC4-11EC-9CF6-067B4964D503">suparna (dutta)</option>
												<option value="14086343-9DB8-11EA-8FFD-0A608F1BF91B">teacher (min)</option>
												<option value="6503D09C-9DB7-11EA-8FFD-0A608F1BF91B">teacher (all)</option>
												<option value="CC6AB82C-9DB7-11EA-8FFD-0A608F1BF91B">teacher (med)</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Student') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv student_list">
											<select class="form-control" id="student" name="student">
												<option value="0">Arindam Student (Bronze)</option>
												<option value="1">Arindam1 Biswas1</option>
												<option value="2">avijit chakraborty</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Start date') }} :</label>
									<div class="col-sm-7 row">
										<div class="col-sm-4">
											<div class="input-group" id="start_date_div"> 
												<input id="start_date" name="start_date" type="text" class="form-control" value="{{old('start_date')}}">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>	
										<div class="col-sm-4 offset-md-1">
											<div class="input-group"> 
												<input id="start_time" name="start_time" type="text" class="form-control" value="{{old('start_time')}}">
												<span class="input-group-addon">
													<i class="fa fa-clock-o"></i>
												</span>
											</div>
										</div>	
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('End date') }} :</label>
									<div class="col-sm-7 row">
										<div class="col-sm-4">
											<div class="input-group" id="end_date_div"> 
												<input id="end_date" name="end_date" type="text" class="form-control" value="{{old('end_date')}}">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>	
										<div class="col-sm-4 offset-md-1">
											<div class="input-group"> 
												<input id="end_time" name="end_time" type="text" class="form-control" value="{{old('end_time')}}">
												<span class="input-group-addon">
													<i class="fa fa-clock-o"></i>
												</span>
											</div>
										</div>	
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Duration') }} :</label>
									<div class="col-sm-2">
										<div class="input-group"> 
											<input id="end_time" name="end_time" type="text" class="form-control" value="{{old('duration')}}">
										</div>
									</div>		
								</div>
								<div class="form-group row">
									<div id="all_day_div111" class="row">
										<label class="col-lg-3 col-sm-3 text-left" for="all_day" id="has_user_ac_label_id">{{__('All day') }} :</label>
										<div class="col-sm-7">
											<input id="all_day" name="has_user_account" type="checkbox" value="1">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Type of billing') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="sis_paying" name="sis_paying">
												<option value="0">No charge</option>
												<option value="1">Hourly rate</option>
												<option value="2">Price per student</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Number of students') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="sevent_price" name="sevent_price">
												<option value="price_1">Private session</option>
												<option value="price_2">Group lessons for 2 students</option>
												<option value="price_3">Group lessons for 3 students</option>
												<option value="price_4">Group lessons for 4 students</option>
												<option value="price_5">Group lessons for 5 students</option>
												<option value="price_6">Group lessons for 6 students</option>
												<option value="price_7">Group lessons for 7 students</option>
												<option value="price_8">Group lessons for 8 students</option>
												<option value="price_9">Group lessons for 9 students</option>
												<option value="price_10">Group lessons for 10 students</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="section_header_class">
								<label id="teacher_personal_data_caption">{{ __('Optional information') }}</label>
							</div>
							<div class="col-md-7 offset-md-2">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Title') }} :</label>
									<div class="col-sm-7">
										<div class="input-group"> 
											<input id="Title" name="title" type="text" class="form-control" value="{{old('title')}}">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Description') }} :</label>
									<div class="col-sm-7">
										<div class="input-group"> 
											<textarea class="form-control" cols="60" id="description" name="description" rows="5">{{old('description')}}</textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
@endsection


@section('footer_js')
<script type="text/javascript">
$(function() {
	$("#start_date").datetimepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
	$("#end_date").datetimepicker({
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

$('#student').multiselect({
	search: true
});

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
				beforeSend: function( xhr ) {
				    $("#pageloader").show();
				 },
				success: function(response){	
					if(response.status == 1){
						$('#modal_add_teacher').modal('show');
						$("#modal_alert_body").text(response.message);
					}
				},
				complete: function( xhr ) {
				    $("#pageloader").hide();
				}
			})
		}else{
			$('#modal_add_teacher').modal('show');
			$("#modal_alert_body").text('{{ __('Required field is empty') }}');
		}	            
});  
</script>
@endsection