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
						<label id="page_header" class="page_header bold" name="page_header">{{ __('Coach time off') }} : <i class="fa fa-plus-square" aria-hidden="true"></i></label>
					</div>
				</div>    
			</div>          
		</header>
		<!-- Tabs navs -->

		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Lesson') }}</button>
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
				<form class="form-horizontal" id="coach_off" method="post" action="{{ route('coachOff.editAction',['school'=> $schoolId,'id'=> $coachoffId]) }}"  name="coach_off" role="form">
					@csrf
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Lesson information') }}</label>
						</div>
						<div class="row">
							<div class="col-md-7 offset-md-2">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Title') }} :</label>
									<div class="col-sm-7">
										<div class="input-group"> 
											<input id="Title" name="title" type="text" class="form-control" value="{{!empty($coachoffData->title) ? old('title', $coachoffData->title) : old('title')}}">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Professor') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control require" id="teacher_select" name="teacher_select">
													<option value="">{{__('Select Professor') }}</option>
												@foreach($professors as $key => $professor)
													<option value="{{ $professor->teacher_id }}" {{!empty($coachoffData->teacher_id) ? (old('teacher_select', $coachoffData->teacher_id) == $professor->teacher_id ? 'selected' : '') : (old('teacher_select') == $professor->teacher_id ? 'selected' : '')}}>{{ $professor->full_name }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Start date') }} :</label>
									<div class="col-sm-7 row">
										<div class="col-sm-4">
											<div class="input-group" id="start_date_div"> 
												<input id="start_date" name="start_date" type="text" class="form-control" value="{{!empty($coachoffData->date_start) ? old('start_date', date('d/m/Y', strtotime($coachoffData->date_start))) : old('start_date')}}" autocomplete="off" autocomplete="off">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
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
												<input id="end_date" name="end_date" type="text" class="form-control" value="{{!empty($coachoffData->date_end) ? old('end_date', date('d/m/Y', strtotime($coachoffData->date_end))) : old('end_date')}}" autocomplete="off">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>	
									</div>
								</div>
								<!-- <div class="form-group row">
									<div id="all_day_div111" class="row">
										<label class="col-lg-3 col-sm-3 text-left" for="fullday_flag" id="has_user_ac_label_id">{{__('All day') }} :</label>
										<div class="col-sm-7">
											<input id="fullday_flag" name="fullday_flag" type="checkbox" value="Y" {{ !empty($coachoffData->fullday_flag) ? 'checked' : '';  }}>
										</div>
									</div>
								</div> -->
							</div>
							<div class="section_header_class">
								<label id="teacher_personal_data_caption">{{ __('Optional information') }}</label>
							</div>
							<div class="col-md-7 offset-md-2">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Description') }} :</label>
									<div class="col-sm-7">
										<div class="input-group"> 
											<textarea class="form-control" cols="60" id="description" name="description" rows="5">{{!empty($coachoffData->description) ? old('description', $coachoffData->description) : old('description')}}</textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
					<div class="btn_area">
						<a class="btn btn-theme-outline" href="<?= $BASE_URL;?>/agenda">Back</a>
						<a class="btn btn-theme-warn" href="#" id="delete_btn" style="display: block;">Delete</a>
						<button id="save_btn" name="save_btn" class="btn btn-theme-success"><i class="fa fa-save"></i>{{ __('Save') }} </button>
					</div>
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
	var formData = $('#coach_off').serializeArray();
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

	if(error < 1){	
		return true;
	}else{
		return false;
	}	            
});  
</script>
@endsection