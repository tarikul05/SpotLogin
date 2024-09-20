@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<script src="{{ asset('js/jquery.multiselect.js') }}"></script>
<script src="{{ asset('js/lib/moment.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/jquery.multiselect.css') }}">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
@endsection

@php
use App\Helpers\Helper;
    //$zone = $_COOKIE['timezone_user'];
    $zone = $timezone;
	$initDate = new Helper();
	$date_start = $initDate->formatDateTimeZone($lessonData->date_start, 'long','UTC',$zone);
	$date_end = $initDate->formatDateTimeZone($lessonData->date_end, 'long','UTC', $zone);
    $showPrice = ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()) && ($lessonData->eventcategory->invoiced_type == 'S') || ($AppUI->isTeacher() && ($lessonData->eventcategory->invoiced_type == 'T'));
@endphp

@section('content')
  <div class="content">
	<div class="container">


				<form class="form-horizontal" id="add_lesson" method="post" action="{{ route('lesson.createAction',[$schoolId]) }}"  name="add_lesson" role="form">
					@csrf

					<div class="row justify-content-center pt-5 pb-5">
						<div class="col-md-10">
			
					<div class="page_header_class pt-1 pb-3" style="position: static;">
						<h5 class="titlePage">{{ __('Lesson Information') }}</h5>
					</div>

					<div class="row">
						<div class="col-lg-12">

					<input id="save_btn_value" name="save_btn_more" type="hidden" class="form-control" value="0">
				
					<div class="card2">
				
					<div class="card-header titleCardPage">{{ __('Add lesson') }}</div>
					<div class="card-body bg-tertiary">
					<div class="row">
						<div class="col-md-12">

					
						<div class="row">
						
								@if($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin())	
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Category type') }} :</label>
									<div class="col-sm-7">
											<select class="form-control" id="event_invoice_type" name="event_invoice_type">
												<option value="">{{__('Select Type') }}</option>
												<option value="T" @if(session('event_invoice_type') == "T") selected @endif>{{__('Teacher invoice')}}</option>
												<option value="S" @if(session('event_invoice_type') == "S") selected @endif>{{__('School invoice')}}</option>
											</select>
									</div>
								</div>
								@else
								<input style="opacity: 0; visibility: hidden; height: 0 !important" type="text" id="event_invoice_type" name="event_invoice_type"  value="T">
								@endif
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Category') }} :</label>
									<div class="col-sm-7">
											<select class="form-control" id="category_select" name="category_select">
												@foreach($eventCategory as $key => $eventcat)
													<option data-invoice="{{ $eventcat->invoiced_type }}" data-s_thr_pay_type="{{ $eventcat->s_thr_pay_type }}" data-s_std_pay_type="{{  $eventcat->s_std_pay_type }}" data-t_std_pay_type="{{  $eventcat->t_std_pay_type }}" value="{{ $eventcat->id }}" {{!empty($lessonData->event_category) ? (old('category_select', $lessonData->event_category) == $eventcat->id ? 'selected' : '') : (old('category_select') == $eventcat->id ? 'selected' : '')}}>{{ $eventcat->title }}</option>
												@endforeach
											</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Location') }} :</label>
									<div class="col-sm-7">
											<select class="form-control" id="location" name="location">
												<option value="">{{__('Select Location') }}</option>
												@foreach($locations as $key => $location)
													<option value="{{ $location->id }}" {{!empty($lessonData->location_id) ? (old('location', $lessonData->location_id) == $location->id ? 'selected' : '') : (old('location') == $location->id ? 'selected' : '')}}>{{ $location->title }}</option>
												@endforeach
											</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Title') }} :</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input id="Title" name="title" type="text" class="form-control" value="{{!empty($lessonData->title) ? old('title', $lessonData->title) : old('title')}}">
										</div>
									</div>
								</div>
								<div class="form-group row">
									@if(!$AppUI->isTeacherAdmin())
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Professor') }} : </label>
									@endif
									<div class="col-sm-7">
										@if($AppUI->isTeacherAdmin())
											<input type="hidden" name="teacher_select" class="form-control" value="{{ $lessonData->teacher_id; }}" readonly>
										@else
											<select class="form-control" id="teacher_select" name="teacher_select">
													<option value="">{{__('Select Professor') }}</option>
												@foreach($professors as $key => $professor)
													<option value="{{ $professor->teacher_id }}" {{!empty($lessonData->teacher_id) ? (old('teacher_select', $lessonData->teacher_id) == $professor->teacher_id ? 'selected' : '') : (old('teacher_select') == $professor->teacher_id ? 'selected' : '')}}>{{ $professor->full_name }}</option>
												@endforeach
											</select>
										@endif
									</div>
								</div>


								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Student') }} :</label>
									<div class="col-sm-7">
										<div class="student_list">
											<select class="form-control" id="student" name="student[]" multiple="multiple">
												@foreach($students as $sub)
													<option value="{{ $sub->student_id }}"  @foreach($studentOffList as $sublist){{$sublist->student_id == $sub->student_id ? 'selected': ''}}   @endforeach> {{ $sub->nickname }}</option>
												@endforeach
			  								</select>
										</div>
									</div>
									<div class="col-sm-2 p-l-n p-r-n">
										<span class="no_select" id="std-check-div"> <input type="checkbox" name="student_empty" id="student_empty" <?php if(empty($studentOffList[0]->student_id)){ echo 'checked'; } ?>> {{__('do not select') }} <i class="fa fa-info-circle" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="right" title="{{__('If you wish to not select any students for the lesson, for ’school invoiced’ lesson with a many students for example. Remember that if no students are selected, no invoice will be generated for them for that lesson.')}}"></i></span>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Start date') }} :</label>
									<div class="col-sm-7 row">
										<div class="col-sm-4">
											<div class="input-group" id="start_date_div">
												<input id="start_date" name="start_date" type="text" class="form-control" value="{{!empty($date_start) ? old('start_date', date('d/m/Y', strtotime($date_start))) : old('start_date')}}" autocomplete="off">
												<input type="hidden" name="zone" id="zone" value="<?php echo $timezone; ?>">
												<span class="input-group-addon">
													<i class="fa-solid fa-clock"></i>
												</span>
											</div>
										</div>
										<div class="col-sm-4 offset-md-1">
											<div class="input-group">
												<input id="start_time" name="start_time" type="text" class="form-control timepicker1" value="{{date('H:i', strtotime($date_start))}}">
												<span class="input-group-addon">
													<i class="fa-solid fa-clock"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group row not-allday">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('End date') }} :</label>
									<div class="col-sm-7 row">
										<div class="col-sm-4">
											<div class="input-group" id="end_date_div">
												<input id="end_date" name="end_date" type="text" class="form-control" value="{{!empty($date_end) ? old('end_date', date('d/m/Y', strtotime($date_end))) : old('end_date')}}" autocomplete="off" readonly>
												<span class="input-group-addon">
													<i class="fa-solid fa-clock"></i>
												</span>
											</div>
										</div>
										<div class="col-sm-4 offset-md-1">
											<div class="input-group">
												<input id="end_time" name="end_time" type="text" class="form-control timepicker2" value="{{date('H:i', strtotime($date_end))}}">
												<span class="input-group-addon">
													<i class="fa-solid fa-clock"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group row not-allday">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Duration') }} <small>(minutes)</small> :</label>
									<div class="col-sm-2">
										<div class="input-group">
											<input id="duration" name="duration" type="text" class="form-control" value="{{!empty($lessonData->duration_minutes) ? old('duration', $lessonData->duration_minutes) : old('duration')}}">
										</div>
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
								<div class="form-group row" id="teacher_type_billing">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Teacher type of billing') }} :</label>
									<div class="col-sm-7">
											<select class="form-control" id="sis_paying" name="sis_paying">
												<option value="0">Hourly rate</option>
												<option value="1">Fixed price</option>
											</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Student type of billing') }} :</label>
									<div class="col-sm-7">
											<select class="form-control" id="student_sis_paying" name="student_sis_paying">
												<option value="0">Hourly rate</option>
												<option value="1">Fixed price</option>
												<option value="2">Packaged</option>
											</select>
									</div>
								</div>
								<div class="form-group row" id="hourly" style="display:none">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Number of students') }} :</label>
									<div class="col-sm-7">
											<select class="form-control" id="sevent_price" name="sevent_price">
												@foreach($lessonPrice as $key => $lessprice)
													<option value="{{ $lessprice->lesson_price_student }}" {{ old('sevent_price') == $lessprice->lesson_price_student ? 'selected' : ''}}>
													@if($lessprice->lesson_price_student == 'price_1')
														Private Group
													@else
														Group lessons for {{ $lessprice->divider }} students
													@endif
													</option>
												@endforeach
											</select>
									</div>
								</div>
								<div id="price_per_student" style="display:none;">
								<!--<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Currency') }} :</label>
									<div class="col-sm-4">
											<input type="text" class="form-control" id="sprice_currency" name="sprice_currency" value="{{$lessonData->price_currency}}" readonly>
									</div>
								</div>-->
								<div class="form-group row">
									<!--<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Teacher price (per class)') }} :</label>-->
									<div class="col-sm-4">
										<div class="input-group" id="sprice_amount_buy_div">
											<!--<span class="input-group-addon">
												<i class="fa fa-calendar1"></i>
											</span>-->
											<input id="sprice_amount_buy" name="sprice_amount_buy" type="hidden" class="form-control" value="{{old('sprice_amount_buy')}}" autocomplete="off">
										</div>
									</div>
								</div>
								<!--<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Student price (student/hour)') }} :</label>
									<div class="col-sm-4">
										<div class="input-group" id="sprice_amount_sell_div">
											<span class="input-group-addon">
												<i class="fa-solid fa-arrow-right"></i>
											</span>
											<input id="sprice_amount_sell" name="sprice_amount_sell" type="text" class="form-control" value="{{old('sprice_amount_sell')}}" autocomplete="off" readonly>
                                        </div>
									</div>
								</div>-->
								</div>
							
							<div class="mt-5 mb-3">
								<div class="card-header titleCardPage">{{ __('Optional information') }}</div>
							</div>
							<div class="col-md-11" style="margin:0 auto;">
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

						<br>
						<a class="btn btn-theme-outline" href="<?= $BASE_URL;?>/agenda">Back</a>
						<br><br><br><br>

						</div>
					</div>
					</div>
				</div>
					
			
			</div>
		</div>
	</div>
</div>
</div>


<div class="row justify-content-center" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important; width:100%;">
	<div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.99!important; background-color:#fbfbfb!important; border:1px solid #fcfcfc;">
	   
        <button id="save_btn_more" class="btn btn-outline-success">{{ __('Save & add more') }}</button>
		<button id="save_btn" class="btn btn-success">{{ __('Save') }}</button>

	</div>
</div>

</form>
</div>
	</div>

	<!-- success modal-->
	<div class="modal modal_parameter" id="modal_lesson_price">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<p id="modal_alert_body">
						{{__('Price setup is not available for this event category and coach. please check and update.')}}
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" id="modalClose" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Ok') }}</button>
				</div>
			</div>

	<!-- End Tabs content -->
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

	$('#start_date').on('change', function(e){
		$("#end_date").val($("#start_date").val());
	});
});

$('#student').multiselect({
	search: true
});

$('#student').on('change', function(event) {
	var cnt = $('#student option:selected').length;
	var price=document.getElementById("sis_paying").value;
	if (cnt >= 10) {
		document.getElementById("sevent_price").value='price_10';
	}
	else
	{
		document.getElementById("sevent_price").value='price_'+cnt;
	}

});


$('#event_invoice_type').on('change', function() {
	var event_invoice_type = $("#event_invoice_type option:selected").val();
	var teacher = "{{$AppUI->person_id}}";
	console.log(event_invoice_type, teacher);
	getCategoryByType('{{ $schoolId }}', event_invoice_type, teacher);

});

function getCategoryByType(school_id=null, type=null, teacher=null) {

if (school_id !=null) {
    var menuHtml='';
    var data = 'school_id='+school_id+'&type='+type+'&teacher='+teacher+'';
    $('#category_select').html('');

    $.ajax({
        url: BASE_URL + '/get_event_category_by_type',
        data: data,
        type: 'POST',
        dataType: 'json',
        //async: false,
        beforeSend: function( xhr ) {
            $("#pageloader").show();
        },
        success: function(data) {
          $("#pageloader").hide();
            if (data.length >0) {
                var resultHtml ="";
                resultHtml+='<option data-s_thr_pay_type="0" data-s_std_pay_type="0" data-t_std_pay_type="0" data-invoice="T" value="0">Select Category</option>';
                var i='0';
                $.each(data, function(key,value){
                    var isAdmin = "{{ $AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() }}";
                    let textAdmin = "";
                    if(isAdmin) {
                        textAdmin = "<span class='text-danger'>("+value.invoiced_type+")</span> ";
                    }
                    var lastCat = '{{ session('last_cat') }}';
                    resultHtml+='<option data-s_thr_pay_type="'+value.s_thr_pay_type+'" data-s_std_pay_type="'+value.s_std_pay_type+'" data-t_std_pay_type="'+value.t_std_pay_type+'" value="'+value.id+'" data-invoice="'+value.invoiced_type+'" ' + ((lastCat && lastCat == value.id) ? 'selected' : '') + '>'+textAdmin+''+value.title+'</option>';
                });
                $('#category_select').html(resultHtml);
                $('#category_select').change();

            } else {

                var resultHtml ="";
                resultHtml+='<option value="">No category found</option>';
                $('#category_select').html(resultHtml);
                $('#category_select').change();

            }

        },   //success
        complete: function( xhr ) {
          $("#pageloader").hide();
        },
        error: function(ts) {
            console.log(ts);
            errorModalCall('Populate Event Type:'+GetAppMessage('error_message_text'));
        }
    }); // Ajax
}

}


$( document ).ready(function() {
	// var zone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    // document.getElementById("zone").value = zone;
	var zone = document.getElementById("zone").value;
	var value = $('#sis_paying').val();
	var datainvoiced = $("#category_select option:selected").data('invoice');
    var s_thr_pay_type = $("#category_select option:selected").data('s_thr_pay_type');
    var s_std_pay_type = $("#category_select option:selected").data('s_std_pay_type');
    var t_std_pay_type = $("#category_select option:selected").data('t_std_pay_type');
    if (datainvoiced == 'S') {
        $("#student_sis_paying").val(s_std_pay_type);
        $("#sis_paying").val(s_thr_pay_type);
        $("#teacher_type_billing").show();
		if(s_thr_pay_type == 0){
			$('#sprice_amount_buy').prop('disabled', true);
		}else if(s_thr_pay_type == 1){
			$('#sprice_amount_buy').prop('disabled', false);
		}
		if(s_std_pay_type == 0){
			//$('#sprice_amount_sell').prop('disabled', true);
		}else if(s_std_pay_type == 1){
			//$('#sprice_amount_sell').prop('disabled', false);
		}else if(s_std_pay_type == 2){
			//$('#sprice_amount_sell').prop('disabled', true);
		}

    }else{
        $("#teacher_type_billing").show();
        $("#student_sis_paying").val(t_std_pay_type);
		if(s_thr_pay_type == 0){
			$('#sprice_amount_buy').prop('disabled', true);
		}else if(s_thr_pay_type == 1){
			$('#sprice_amount_buy').prop('disabled', false);
		}
		if(t_std_pay_type == 0){
			//$('#sprice_amount_sell').prop('disabled', true);
		}else if(t_std_pay_type == 1){
			//$('#sprice_amount_sell').prop('disabled', false);
		}else if(t_std_pay_type == 2){
			//$('#sprice_amount_sell').prop('disabled', true);
		}
    }

	if(s_thr_pay_type == 0){
		$('#hourly').show();
        $('#price_per_student').show();
	}else if(s_thr_pay_type == 1 && s_std_pay_type == 1){
        $('#hourly').hide();
		$('#price_per_student').show();
	}

	//$('#sprice_amount_buy').val(0);
	//$('#sprice_amount_sell').val(0);

	if(s_thr_pay_type == 0){
		$('#hourly').show();
        $('#price_per_student').hide();
	}else if(s_thr_pay_type == 1){
        $('#hourly').hide();
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
		scrollbar: true,
		change:function(time){
			$('#end_time').val(recalculate_end_time(moment(time).format('HH:mm'),15));
			CalcDuration();
		}
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
		scrollbar: true,
		change:function(time){
			CalcDuration();
		}
	});

	function CalcDuration(){
		var el_start = $('#start_time'),
		el_end = $('#end_time'),
		el_duration = $('#duration');

			if (el_end.val() < el_start.val()) {
				$('#end_time').val(recalculate_end_time(el_start.val(),15));
				el_duration.val(recalculate_duration(el_start.val(), $('#end_time').val()));
			}
			else{
				el_duration.val(recalculate_duration(el_start.val(), el_end.val()));
			}
           // getLatestPrice()
		}

	function recalculate_end_time(start_value, duration) {
		if (validateStringHours(start_value) && parseInt(duration, 10) == duration) {
			var start_minutes = +(parseInt(string_left(start_value, 2), 10) * 60) + parseInt(string_right(start_value, 2), 10) + parseInt(duration, 10),
				start_hours_number = parseInt((start_minutes / 60).toString(), 10),
				start_hours = start_hours_number;
				if (start_hours > 23) {start_hours = start_hours - 24;}
				return string_right('00' + start_hours.toString(), 2) + ':' + string_right('00' + (start_minutes - (start_hours_number * 60)).toString(), 2);
		}
		return 0;
	}
	function recalculate_duration(start_value, end_value) {
		if (validateStringHours(start_value) && validateStringHours(end_value)) {
			return -(parseInt(string_left(start_value, 2), 10) * 60)
					- parseInt(string_right(start_value, 2), 10)
					+ (parseInt(string_left(end_value, 2), 10) * 60)
					+ (parseInt(string_right(end_value, 2), 10));
		}
		return 0;
	}

	function validateStringHours(s_hours) {
            if (s_hours == '24:00') {return true;}
            var re = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
            return re.test(s_hours);
	}
	function string_left(str, n){
		if (n <= 0)
			return "";
		else if (n > String(str).length)
			return str;
		else
			return String(str).substring(0,n);
	}

	function string_right(str, n){
		if (n <= 0)
			return "";
		else if (n > String(str).length)
			return str;
		else {
			var iLen = String(str).length;
			return String(str).substring(iLen, iLen - n);
		}
	}

	function filterParseDigits(str) {
		return str.replace(/[^\d]/g, '');
	}

	function validateStringHours(s_hours) {
		if (s_hours == '24:00') {return true;}
		var re = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
		return re.test(s_hours);
	}

	$('#start_time, #end_time, #duration').on('change', function(e){
	var event_source = $(this).attr('id');
	var el_duration = $('#duration');
	if (event_source === 'start_time'){
		if(!el_duration.val()){el_duration.val('15');}
		$('#end_time').val(recalculate_end_time($('#start_time').val(), el_duration.val()));
	}

	var el_start = $('#start_time'),
		el_end = $('#end_time');

		if (event_source === 'end_time' || event_source === 'start_time') {
			if (el_end.val() < el_start.val()) {
				$('#end_time').val(el_start.val());
			};
			el_duration.val(recalculate_duration(el_start.val(), el_end.val()));
		} else {
			if (!(parseInt(el_duration.val(), 10) == el_duration.val())) {
				el_duration.val(20);
				$('#end_time').val(el_start.val());
			} else {
				if (parseInt(el_duration.val(), 10) >= (60*24)) {
					el_duration.val(((60*24) - 1));
				}
				$('#end_time').val(recalculate_end_time(el_start.val(), el_duration.val()));
			}
		}
	});
})

$('#sis_paying').on('change', function() {
	$('#hourly').hide();
	$('#price_per_student').hide();
	//$('#sprice_amount_buy').val(0);
	//$('#sprice_amount_sell').val(0);
	if(this.value == 1){
		$('#hourly').show();
	}else if(this.value == 2){
		$('#price_per_student').show();
	}
});

$('#add_lesson').on('submit', function(e) {

	var formData = $('#add_lesson').serializeArray();
	var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
	formData.push({
		"name": "_token",
		"value": csrfToken,
	});

	var title = $('#Title').val();
	var professor = $('#teacher_select').val();
	var selected = $("#student :selected").map((_, e) => e.value).get();
	var startDate = $('#start_date').val();
	var endDate = $('#end_date').val();
	var bill_type = $('#sis_paying').val();
	var emptyStdchecked = $("#student_empty").prop('checked');

	var errMssg = '';

	// if(title == ''){
	// 	var errMssg = 'Title required';
	// 	$('#Title').addClass('error');
	// }else{
	// 	$('#Title').removeClass('error');
	// }

	if ($("#student_empty").prop('checked') == false){
		if (!emptyStdchecked) {
			if( selected < 1){
				var errMssg = 'Select student';
				$('.student_list').addClass('error');
			}else{
				var errMssg = '';
				$('.student_list').removeClass('error');
			}
		}
	}else{
		var errMssg = '';
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


$("body").on('change', '#category_select', function(event) {

	var datainvoiced = $("#category_select option:selected").data('invoice');
	var s_thr_pay_type = $("#category_select option:selected").data('s_thr_pay_type');
	var s_std_pay_type = $("#category_select option:selected").data('s_std_pay_type');
	var t_std_pay_type = $("#category_select option:selected").data('t_std_pay_type');

	if (datainvoiced == 'S') {
		if (s_std_pay_type == 2) {
             $("#std-check-div").css('display', 'block');
        }else{
            $("#std-check-div").css('display', 'none');
        }
		$("#teacher_type_billing").show();
		$("#student_sis_paying").val(s_std_pay_type);
		$("#sis_paying").val(s_thr_pay_type);

		if(s_thr_pay_type == 0){
			$('#sprice_amount_buy').prop('disabled', true);
		}else if(s_thr_pay_type == 1){
			$('#sprice_amount_buy').prop('disabled', false);
		}
		if(s_std_pay_type == 0){
			//$('#sprice_amount_sell').prop('disabled', true);
		}else if(s_std_pay_type == 1){
			//$('#sprice_amount_sell').prop('disabled', false);
		}else if(s_std_pay_type == 2){
			//$('#sprice_amount_sell').prop('disabled', true);
		}
	}else{
		$("#sis_paying").val(s_thr_pay_type);
		$("#student_sis_paying").val(t_std_pay_type);
		$("#std-check-div").css('display', 'none');
		$("#teacher_type_billing").hide();
		$("#student_empty").prop('checked', false);

		if(s_thr_pay_type == 0){
			$('#sprice_amount_buy').prop('disabled', true);
		}else if(s_thr_pay_type == 1){
			$('#sprice_amount_buy').prop('disabled', false);
		}

		if(t_std_pay_type == 0){
			//$('#sprice_amount_sell').prop('disabled', true);
		}else if(t_std_pay_type == 1){
			//$('#sprice_amount_sell').prop('disabled', false);
		}else if(t_std_pay_type == 2){
			//$('#sprice_amount_sell').prop('disabled', true);
		}
	}
	if(s_thr_pay_type == 0){
		$('#hourly').hide();
		$('#price_per_student').show();
	}else if(s_thr_pay_type == 1){
		$('#hourly').hide();
		$('#price_per_student').show();
	}


	var isSchoolAdmin = +"{{$AppUI->isSchoolAdmin()}}";
    var isTeacherAdmin = +"{{$AppUI->isTeacherAdmin()}}";
    var isTeacher = +"{{$AppUI->isTeacher()}}";

    if( ((isSchoolAdmin || isTeacherAdmin) && datainvoiced == 'S') || (isTeacher &&  datainvoiced == 'T') ){
        $("#price_per_student").show();
    }else{
        $("#price_per_student").hide();
    }

	//getLatestPrice();
});

$("#student, #teacher_select").on('change', function(event) {
   // getLatestPrice()
});

	function getLatestPrice() {

	    var agendaSelect = +$("#agenda_select").val();
	    var categoryId = +$("#category_select").val();
	    //var teacherSelect = +$("#teacher_select").val();
	    var stdSelected = $("#student :selected").map((_, e) => e.value).get().length;

	    var formData = $('#from').serializeArray();
	    var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
	    formData.push({
	        "name": "_token",
	        "value": csrfToken,
	    });

	    formData.push({
	        "name": "event_category_id",
	        "value": categoryId,
	    });
	    /*formData.push({
	        "name": "teacher_select",
	        "value": teacherSelect,
	    });*/
	    formData.push({
	        "name": "no_of_students",
	        "value": stdSelected,
	    });


	    //if (categoryId > 0 && teacherSelect > 0) {
	        $.ajax({
	            url: BASE_URL + '/check-lesson-price',
	            async: false,
	            data: formData,
	            type: 'POST',
	            dataType: 'json',
	            success: function(response){
                   
	                if(response){
	                    if (response.status == 1) {
	                        $("#sprice_amount_buy").val(response.lessonPriceTeacher['price_buy'])
	                        $("#sprice_amount_sell").val(response.lessonPriceTeacher['price_sell'])

                           
                            var newDuration = $("#duration").val();

                            var $sellPriceCal = (response.lessonPriceTeacher['price_sell']*(newDuration/60));

                            var inputElem = document.getElementById('sprice_amount_sell');
                            if(inputElem) {
                                inputElem.removeAttribute('disabled');
                                inputElem.value = $sellPriceCal;
                                inputElem.setAttribute('disabled', 'disabled');
                            }


	                    }
	                }
	            },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error: ", textStatus, errorThrown);
                }
	        })
	    //}

	}

$("body").on('click', '#student_empty', function(event) {
	if ($("#student_empty").prop('checked')) {
		$('#student').multiselect( 'reset' );
		$('#student').multiselect( 'disable', true );
	}else{
		$('#student').multiselect( 'disable', false );
	}

})

$( document ).ready(function() {

	$("#category_select").trigger('change');

    $(function() {
        $("#save_btn_more").click(function(){
           $("#save_btn_value"). val(2);
        });
        $("#save_btn").click(function(){
           $("#save_btn_value"). val(3);
        });
    });
});

</script>
@endsection
