@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<script src="{{ asset('js/jquery.multiselect.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/jquery.multiselect.css') }}">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
<!-- Code within resources/views/blade.php -->
@php
	//$zone = $_COOKIE['timezone_user'];
	$zone = $timezone;
	$initDate = new Helper();
	$date_start = $initDate->formatDateTimeZone($eventData->date_start, 'long','UTC',$zone);
	$date_end = $initDate->formatDateTimeZone($eventData->date_end, 'long','UTC', $zone);
	$current_time = $initDate->formatDateTimeZone(now(), 'long','UTC', $zone);

	$priceShow = ((($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()) && ($eventData->event_invoice_type == 'S')) || ($AppUI->isTeacher() && ($eventData->event_invoice_type == 'T')));
@endphp
@section('content')

<form class="form-horizontal" id="edit_event" method="post" action="{{ route('event.editAction',['school'=> $schoolId,'event'=> $eventId]) }}"  name="edit_event" role="form">
	@csrf

  <div class="content">
	<div class="container mb-5">


		<div class="row justify-content-center pt-3 pb-5">
			<div class="col-md-10">

		<div class="page_header_class pt-1" style="position: static;">
            <h5 class="titlePage">{{ __('Event') }}</h5>
        </div>

	<div class="row">
		<div class="col-lg-12">

	
			@if(strtotime($date_end) < strtotime($current_time))
			<div id="button_lock_and_save_div" class="alert alert-info mb-3 text-center" role="alert" style="position: relative; display: block;  margin:0 auto;"><label id="button_lock_and_save_help_text"><i class="fa-regular fa-bell fa-bounce"></i> Please validate the event to make it available for invoicing</label>
				<div class="save_and_more_area mt-1" style="width:100%; max-width:190px; margin:0 auto;">
				<input type="submit" class="btn btn-sm btn-info button_lock_and_save w-100" name="validate" value="Validate">
				<i class="fa-solid fa-lock"></i>
				</div>
			</div>
			@endif
		


		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">

					<input id="redirect_url" name="redirect_url" type="hidden" class="form-control" value="{{$redirect_url}}">
					<fieldset>
						<div class="card2">
							<div class="card-body bg-tertiary">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group row">
									<label class="col-lg-4 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Location') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="location" name="location">
												<option value="">{{__('Select Location') }}</option>
												@foreach($locations as $key => $location)
												<option value="{{ $location->id }}" {{!empty($eventData->location_id) ? (old('location', $eventData->location_id) == $location->id ? 'selected' : '') : (old('location') == $location->id ? 'selected' : '')}}>{{ $location->title }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-4 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Title') }} :</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input id="Title" name="title" type="text" class="form-control" value="{{!empty($eventData->title) ? old('title', $eventData->title) : old('title')}}">
										</div>
									</div>
								</div>
								<div class="form-group row">
									@if(!$AppUI->isTeacherAdmin())
									<label class="col-lg-4 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Professor') }} :</label>
									@endif
									@if($AppUI->isTeacherAdmin())
										<input style="display:none" type="text" name="teacher_select" class="form-control" value="{{ $AppUI->person_id; }}" readonly>
									@else
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="teacher_select" name="teacher_select">
												<option value="">{{__('Select Professor') }}</option>
												@foreach($professors as $key => $professor)
												<option value="{{ $professor->teacher_id }}" {{!empty($eventData->teacher_id) ? (old('teacher_select', $eventData->teacher_id) == $professor->teacher_id ? 'selected' : '') : (old('teacher_select') == $professor->teacher_id ? 'selected' : '')}}>{{ $professor->full_name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									@endif
								</div>
								<div class="form-group row">
									<label class="col-lg-4 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Student') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv student_list">
											<select class="form-control" id="student" name="student[]" multiple="multiple">
												@foreach($students as $sub)
													<option value="{{ $sub->student_id}}"   @foreach($studentOffList as $sublist){{$sublist->student_id == $sub->student_id ? 'selected': ''}}   @endforeach> {{ $sub->nickname }}</option>
												@endforeach
			  								</select>
										</div>
									</div>
								</div>
								<div class="form-group row not-allday">
									<label class="col-lg-4 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Start date') }} :</label>
									<div class="col-sm-7 row">
										<div class="col-sm-4">
											<div class="input-group" id="start_date_div">
												<input id="start_date" name="start_date" type="text" class="form-control" value="{{!empty($date_start) ? old('start_date', date('d/m/Y', strtotime($date_start))) : old('start_date')}}" autocomplete="off">
												<input type="hidden" name="zone" id="zone" value="<?php echo $timezone; ?>">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>
										<!-- <div class="col-sm-4 offset-md-1">
											<div class="input-group">
												<input id="start_time" name="start_time" type="text" class="form-control timepicker1" value="{{old('start_time')}}">
												<span class="input-group-addon">
													<i class="fa fa-clock-o"></i>
												</span>
											</div>
										</div>	 -->
									</div>
								</div>
								<div class="form-group row not-allday">
									<label class="col-lg-4 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('End date') }} :</label>
									<div class="col-sm-7 row">
										<div class="col-sm-4">
											<div class="input-group" id="end_date_div">
												<input id="end_date" name="end_date" type="text" class="form-control" value="{{!empty($date_end) ? old('end_date', date('d/m/Y', strtotime($date_end))) : old('end_date')}}" autocomplete="off">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>
										<!-- <div class="col-sm-4 offset-md-1">
											<div class="input-group">
												<input id="end_time" name="end_time" type="text" class="form-control timepicker2" value="{{old('end_time')}}">
												<span class="input-group-addon">
													<i class="fa fa-clock-o"></i>
												</span>
											</div>
										</div>	 -->
									</div>
								</div>
								<!-- <div class="form-group row">
									<div id="all_day_div111" class="row">
										<label class="col-lg-3 col-sm-3 text-left" for="all_day" id="has_user_ac_label_id">{{__('All day') }} :</label>
										<div class="col-sm-7">
											<input id="all_day" name="fullday_flag" type="checkbox" value="Y" >
										</div>
									</div>
								</div> -->
								<div class="form-group row" id="hourly" style="display:none">
									<label class="col-lg-4 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Number of students') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="sevent_price" name="sevent_price">
												@foreach($lessonPrice as $key => $lessprice)
												<option value="{{ $lessprice->lesson_price_student }}" {{!empty($eventData->no_of_students) ? (old('sevent_price', 'price_'.$eventData->no_of_students) == $lessprice->lesson_price_student ? 'selected' : '') : (old('sevent_price') == 'price_'.$lessprice->lesson_price_student ? 'selected' : '')}}>Group lessons for {{ $lessprice->divider }} students</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								
									<div class="form-group row">
										<label class="col-lg-4 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Currency') }} :</label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control" id="sprice_currency" name="sprice_currency">
													@foreach($currency as $key => $curr)
														<option value="{{$curr->currency_code}}">{{$curr->currency_code}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<?php if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin() || $AppUI->isTeacherMinimum() || $AppUI->isTeacherMedium()){ ?>
									<div class="form-group row">
										<label class="col-lg-4 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Teacher price (per event)') }} :</label>
										<div class="col-sm-4">
											<div class="input-group" id="sprice_amount_buy_div">
												<span class="input-group-addon">
													<i class="fa fa-arrow-right"></i>
												</span>
												<input id="sprice_amount_buy" placeholder="0" name="sprice_amount_buy" type="text" class="form-control" value="{{!empty($eventData->buy_total) ? old('price_amount_buy', $eventData->price_amount_buy) : old('price_amount_buy')}}" autocomplete="off">
												<input type="hidden" name="attendBuyPrice" value="{{ !empty($eventData->price_amount_buy) ? $eventData->price_amount_buy : ''; }}">
											</div>
										</div>
									</div>
									<?php } ?>
									<div class="form-group row">
										<label class="col-lg-4 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Student price (per student)') }} :</label>
										<div class="col-sm-4">
											<div class="input-group" id="sprice_amount_sell_div">
												<span class="input-group-addon">
													<i class="fa fa-arrow-right"></i>
												</span>
												<input id="sprice_amount_sell" placeholder="0" name="sprice_amount_sell" type="number" class="form-control" value="{{!empty($eventData->price_amount_sell) ? old('sprice_amount_sell', $eventData->price_amount_sell) : old('sprice_amount_sell')}}" autocomplete="off">
												<input type="hidden" name="attendSellPrice" value="{{ !empty($eventData->price_amount_sell) ? $eventData->price_amount_sell : ''; }}">
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-4 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Extra Charges (per student)') }} :</label>
										<div class="col-sm-4">
											<div class="input-group" id="extra_charges_div">
												<span class="input-group-addon">
													<i class="fa fa-arrow-right"></i>
												</span>
												<input id="extra_charges" placeholder="0" name="extra_charges" type="number" class="form-control" value="{{!empty($eventData->extra_charges) ? old('sextra_charges', $eventData->extra_charges) : old('sextra_charges')}}" autocomplete="off">
												<input type="hidden" name="attendSellPrice" value="{{ ($eventData->price_amount_sell) }}">
											</div>
										</div>
									</div>
								
							</div>
							<div class="mt-5 mb-3">
								<div class="card-header titleCardPage">{{ __('Students') }}</div>
							</div>
							<div class="col-md-12">
								<div class="form-group row">
									<div class="col-sm-12">
										<div class="form-group row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table id="attn_tbl" class="table">
														<tbody>
															<tr>
																<!--<th width="5%" style="text-align:left"></th>-->
																<th width="20%" style="text-align:left">
																	
																</th>
																<th width="15%" style="text-align:left">
																	<button id="mark_present_btn" class="btn btn-xs btn-outline-success btn-sm" type="button" style="display: block;">Mark all present</button>
																</th>
														
																@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin() || $AppUI->isTeacherMinimum() || $AppUI->isTeacherMedium() || $AppUI->isTeacherAll())
																	<th width="15%" style="text-align:right;">
																		<label id="row_hdr_buy" name="row_hdr_buy">{{ __('Teacher') }}</label>
																	</th>
																@endif
																	<th width="15%" style="text-align:right">
																		<label id="row_hdr_sale" name="row_hdr_sale">{{ __('Student') }}</label>
																	</th>
																	<th width="15%" style="text-align:right">
																		<label id="row_hdr_sale" name="row_hdr_sale">{{ __('Extra charges') }}</label>
																	</th>
																
															</tr>

															@foreach($studentOffList as $student)
															<tr>
																<!--<td>{{ $student->student_id }}</td>-->
																<td>
																<img src="{{ asset('img/photo_blank.jpg') }}" width="18" height="18" class="img-circle account-img-small"> {{ $student->nickname }}
																</td>
																<td>
																	<div class="selectdiv">
																		<select class="form-control student_attn" name="student_attn" id="student_attn" data-id="{{ $student->student_id }}">
																			<option value="0" {{!empty($student->participation_id) ? (old('student_attn', $student->participation_id) == 0 ? 'selected' : '') : (old('student_attn') == 0 ? 'selected' : '')}}>Scheduled</option>
																			<option value="199" {{!empty($student->participation_id) ? (old('student_attn', $student->participation_id) == 199 ? 'selected' : '') : (old('student_attn') == 199 ? 'selected' : '')}}>Absent</option>
																			<option value="200" {{!empty($student->participation_id) ? (old('student_attn', $student->participation_id) == 200 ? 'selected' : '') : (old('student_attn') == 200 ? 'selected' : '')}}>Present</option>
																		</select>
																	</div>
																	<input type="hidden" name="attnValue[{{$student->id}}]" value="{{$student->participation_id}}">
																</td>

															
																	@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin() || $AppUI->isTeacherMinimum() || $AppUI->isTeacherMedium() || $AppUI->isTeacherAll())
																	<td style="text-align:right"> {{ ($relationData->price_currency) }} {{ ($student->price_amount_buy) }}</td>
																	@endif
																	<td style="text-align:right"> {{ ($relationData->price_currency) }} {{ !empty($eventData->price_amount_sell) ? $eventData->price_amount_sell : ''; }} </td>
																	<td style="text-align:right"> {{ ($relationData->price_currency) }} {{ $eventData->extra_charges }}  </td>
															
															</tr>
															@endforeach
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="d-block d-sm-none">
							@if(strtotime($date_end) < strtotime($current_time))
							<div id="button_lock_and_save_div" class="alert alert-info text-center" role="alert"><label id="button_lock_and_save_help_text"><i class="fa-regular fa-bell fa-bounce"></i> Please validate the event to make it available for invoicing</label>
								<input type="submit" class="mt-1 btn btn-sm btn-info button_lock_and_save w-100"  name="validate" value="Validate">
							</div>
							@endif
							</div>
							<div class="mt-5 mb-3">
								<div class="card-header titleCardPage">{{ __('Optional information') }}</div>
							</div>
							<div class="col-md-10">
								<p style="font-size:11px;">(the description will be added on the invoice)</p>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Description') }} :</label>
									<div class="col-sm-7">
										<div class="input-group">
											<textarea class="form-control" cols="60" id="description" name="description" rows="5">{{!empty($eventData->description) ? old('description', $eventData->description) : old('description')}}</textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						</div>
					</div>
					</fieldset>
					<!--<div class="btn_area page_header_class" style="position: fixed; top:101px; z-index:9999; right:0; padding-right:85px;">
						<a class="btn btn-theme-outline" href="<?= $BASE_URL;?>/agenda">Back</a>
						@if($AppUI->person_id == $eventData->teacher_id)
							@can('self-delete-event')
								<a class="btn btn-theme-warn" href="#" id="delete_btn"  style="display: block !important;">Delete</a>
							@endcan
						@else
							@if(($eventData->event_invoice_type == 'S') && ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()))
								<a class="btn btn-theme-warn" href="#" id="delete_btn"  style="display: block !important;">Delete</a>
							@else
							@can('self-delete-event')
								<a class="btn btn-theme-warn" href="#" id="delete_btn"  style="display: block !important;">Delete</a>
							@endcan
							@endif
						@endif

					</div>-->

			</div>
		</div>
		<a class="btn btn-default" href="<?= $BASE_URL; ?>/agenda"><i class="fa-solid fa-arrow-left"></i> Back</a>
	</div>
</div>

	</div>

	</div>
	

	</div>


	<div class="row justify-content-center" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important; width:100%;">
        <div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.91!important; background-color:#DDDD!important;">

			@if($AppUI->person_id == $eventData->teacher_id)
				@can('self-delete-event')
					<a class="btn btn-outline-danger" href="#" id="delete_btn" style="display: inline-block !important;">Delete</a>
				@endcan
			@else
				@if(($eventData->event_invoice_type == 'S') && ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()))
					<a class="btn btn-outline-danger" href="#" id="delete_btn" style="display: inline-block !important;">Delete</a>
				@else
					@can('self-delete-event')
						<a class="btn btn-outline-danger" href="#" id="delete_btn" style="display: inline-block !important;">Delete</a>
					@endcan
				@endif
			@endif

			<button id="save_btn" name="save_btn" class="btn btn-success">{{ __('Save') }}</button>

        </div>
    </div>

</form>

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

	$('#end_date').datetimepicker({
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

$( document ).ready(function() {
	// var zone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    // document.getElementById("zone").value = zone;
	var zone = document.getElementById("zone").value;
	var value = $('#sis_paying').val();
	$('#hourly').hide();
	$('#price_per_student').hide();
	if(value == 1){
		$('#hourly').show();
	}else if(value == 2){
		$('#price_per_student').show();
	}

	var start_time = moment("{{$date_start}}").format("HH:mm")
	var end_time = moment("{{$date_end}}").format("HH:mm")

	$('.timepicker1').timepicker({
		timeFormat: 'HH:mm',
		interval: 15,
		minTime: '0',
		maxTime: '23:59',
		defaultTime: start_time,
		startTime: '00:00',
		dynamic: false,
		dropdown: true,
		scrollbar: true
	});

	$('.timepicker2').timepicker({
		timeFormat: 'HH:mm',
		interval: 15,
		minTime: '0',
		maxTime: '23:59',
		defaultTime: end_time,
		startTime: '00:00',
		dynamic: false,
		dropdown: true,
		scrollbar: true
	});
})

$( document ).ready(function() {
	var value = $('#sis_paying').val();
	$('#hourly').hide();
	$('#price_per_student').hide();
	if(value == 1){
		$('#hourly').show();
	}else if(value == 2){
		$('#price_per_student').show();
	}
})


$('#sis_paying').on('change', function() {
	$('#hourly').hide();
	$('#price_per_student').hide();
	if(this.value == 1){
		$('#hourly').show();
	}else if(this.value == 2){
		$('#price_per_student').show();
	}
});

// save functionality
$('.student_attn').change(function (e) {
	var stuId = $(this).attr('data-id');
	var typeId = $(this).val();

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$.ajax({
		url: BASE_URL + '/{{$schoolId}}/student-attend-action/{{$eventId}}',
		data: { type:1, stuId:stuId, typeId:typeId },
		type: 'POST',
		dataType: 'json',
		beforeSend: function( xhr ) {
			$("#pageloader").show();
		},
		success: function(response){
			if(response.status == 1){

			}
		},
		complete: function( xhr ) {
			$("#pageloader").hide();
		}
	})

});


// save functionality
$('#mark_present_btn').click(function (e) {
	//if (confirm("Mark all student as present ?")) {
		var data = [];
		$('.student_attn').val('200');
		$('.student_attn').each(function(){
			data.push({stuId : $(this).attr('data-id'), typeId : 200 })
		});

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			url: BASE_URL + '/{{$schoolId}}/student-attend-action/{{$eventId}}',
			data: { type:2, data:data },
			type: 'POST',
			dataType: 'json',
			beforeSend: function( xhr ) {
				$("#pageloader").show();
			},
			success: function(response){
				if(response.status == 1){

				}
			},
			complete: function( xhr ) {
				$("#pageloader").hide();
			}
		})
	//}

});

// $("body").on('click', '#all_day', function(event) {
//     if ($(this).prop('checked')) {
//         $(".not-allday").hide();
//     }else{
//         $(".not-allday").show();
//     }
// });

if ({{!empty($eventData->fullday_flag) ? 1 : 0}}) {
	$("#all_day").click()
}

$('#edit_event').on('submit', function() {
	var title = $('#Title').val();
	var professor = $('#teacher_select').val();
	var selected = $("#student :selected").map((_, e) => e.value).get();
	var startDate = $('#start_date').val();
	var endDate = $('#end_date').val();

	var errMssg = '';

	// if(title == ''){
	// 	var errMssg = 'Title required';
	// 	$('#Title').addClass('error');
	// }else{
	// 	$('#Title').removeClass('error');
	// }

	if( selected < 1){
		var errMssg = 'Select student';
		$('.student_list').addClass('error');
	}else{
		$('.student_list').removeClass('error');
	}

	if(professor == ''){
		var errMssg = 'professor required';
		$('#teacher_select').addClass('error');
	}else{
		$('#teacher_select').removeClass('error');
	}

	if(startDate == ''){
		var errMssg = 'Start date required';
		$('#start_date').addClass('error');
	}else{
		$('#start_date').removeClass('error');
	}

	if(endDate == ''){
		var errMssg = 'Ednd date required';
		$('#end_date').addClass('error');
	}else{
		$('#end_date').removeClass('error');
	}

	if(errMssg == ""){
		return true;
	}else{
		return false;
	}

});
// $("#button_lock_and_save").on('click', function(event) {
// 	event.preventDefault();
// 	confirm_event();
// });
// function confirm_event(){
// 	var redirect_url = $('#redirect_url').val();
// 	var data = 'school_id={{ $schoolId }}&p_event_auto_id={{ $eventId }}';
// 	var status = '';
// 	$.ajax({
// 		url: BASE_URL + '/confirm_event',
// 		data: data,
// 		type: 'POST',
// 		dataType: 'json',
// 		beforeSend: function( xhr ) {
// 			$("#pageloader").show();
// 		},
// 		success: function (result) {
// 			status = result.status;
// 			if (status == 'success') {
// 				successModalCall('{{ __("Event has been validated ")}}');
// 				if (redirect_url !='') {
// 					window.location.href = redirect_url;
// 				} else {
// 					window.location.href = '/{{$schoolId}}/view-event/{{$eventId}}'
// 				}
// 			}
// 			else {
// 				errorModalCall('{{ __("Event validation error ")}}');
// 			}
// 		},   //success
// 		complete: function( xhr ) {
// 			$("#pageloader").hide();
// 		},
// 		error: function (ts) {
// 			ts.responseText+'-'+errorModalCall('{{ __("Event validation error ")}}');
// 		}
// 	}); //ajax-type

// }
	function delete_event(event_id){
        var data='type=delete_events'+'&event_id='+event_id;
		$.ajax({type: "POST",
			url: BASE_URL + '/delete_event',
			data: data,
			dataType: "JSON",
			success:function(result){
				var status =  result.status;
				if (status == 'success') {
					window.location.href = BASE_URL+'/agenda';
				}
			},   //success
			error: function(ts) {
				errorModalCall('delete_events:'+ts.responseText+'-'+GetAppMessage('error_message_text'));
			}
		}); //ajax-type
    }
	// delete event
	$('#delete_btn').click(function (e) {
		var p_event_type_id='{{$eventId}}';
		console.log(p_event_type_id);
		//var retVal = confirm("Tous les événements affichés seront supprimés. Voulez-vous supprimer ?");
		e.preventDefault();
		confirmDeleteModalCall('','Do you want to delete this event',"delete_event("+p_event_type_id+");",false);
		return false;
	})

	$(window).scroll(function() {
		var scroll = $(window).scrollTop();
		if (scroll >= 80) {
				$("#edit_event .btn_area").addClass("btn_area_fixed");
		} else {
			$("#edit_event .btn_area").removeClass("btn_area_fixed");
		}
	});
</script>
@endsection
