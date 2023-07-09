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
<meta name="csrf-token" content="{{ csrf_token() }}">
<style type="text/css">
	.alert{
		top: -30px;
	}
</style>
@endsection
<!-- Code within resources/views/blade.php -->
@php
	//$zone = $_COOKIE['timezone_user'];
	$zone = $timezone;
	$date_start = Helper::formatDateTimeZone($lessonData->date_start, 'long','UTC',$zone);
	$date_end = Helper::formatDateTimeZone($lessonData->date_end, 'long','UTC', $zone);
	$current_time = Helper::formatDateTimeZone(now(), 'long','UTC', $zone);
	$showPrice = ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()) && ($lessonData->eventcategory->invoiced_type == 'S') || ($AppUI->isTeacher() && ($lessonData->eventcategory->invoiced_type == 'T'))
@endphp
@section('content')
  <div class="content">
	<div class="container-fluid body">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area" style="padding-top:8px; padding-bottom:20px;">
					<div class="page_header_class">
						<label id="page_header" class="page_header bold" name="page_header"><i class="fa-solid fa-calendar-day"></i> {{ __('Lesson') }}</label>
					</div>
				</div>    
			</div>          
	
		<!-- Tabs navs -->

		<nav style="margin-bottom:0; padding-bottom:0;">
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Lesson Information') }}</button>
			</div>
		</nav>
		<!-- Tabs navs -->

	</header>

	<form class="form-horizontal" id="edit_lesson" method="post" action="{{ route('lesson.editAction',['school'=> $schoolId,'lesson'=> $lessonlId]) }}"  name="edit_lesson" role="form">
		<div class="row">
			<div class="col-lg-10">

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
	
					@csrf
					<input id="save_btn_value" name="save_btn_more" type="hidden" class="form-control" value="0">
					<input id="redirect_url" name="redirect_url" type="hidden" class="form-control" value="{{$redirect_url}}">
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Lesson information') }}</label>
						</div>
						<div class="card">
							<div class="card-body bg-tertiary">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Category') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="category_select" name="category_select">
												@foreach($eventCategory as $key => $eventcat)
													<option data-invoice="{{ $eventcat->invoiced_type }}"  data-s_thr_pay_type="{{ $eventcat->s_thr_pay_type }}" data-s_std_pay_type="{{  $eventcat->s_std_pay_type }}" data-t_std_pay_type="{{  $eventcat->t_std_pay_type }}" value="{{ $eventcat->id }}" {{!empty($lessonData->event_category) ? (old('category_select', $lessonData->event_category) == $eventcat->id ? 'selected' : '') : (old('category_select') == $eventcat->id ? 'selected' : '')}}>{{ $eventcat->title }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Location') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="location" name="location">
												<option value="">{{__('Select Location') }}</option>
												@foreach($locations as $key => $location)
													<option value="{{ $location->id }}" {{!empty($lessonData->location_id) ? (old('location', $lessonData->location_id) == $location->id ? 'selected' : '') : (old('location') == $location->id ? 'selected' : '')}}>{{ $location->title }}</option>
												@endforeach
											</select>
										</div>
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
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Professor') }} :</label>
									@endif
									@if($AppUI->isTeacherAdmin())
										<input style="display:none" type="text" name="teacher_select" class="form-control" value="{{ $AppUI->id; }}" readonly>
									@else	
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="teacher_select" name="teacher_select">
													<option value="">{{__('Select Professor') }}</option>
												@foreach($professors as $key => $professor)
													<option value="{{ $professor->teacher_id }}" {{!empty($lessonData->teacher_id) ? (old('teacher_select', $lessonData->teacher_id) == $professor->teacher_id ? 'selected' : '') : (old('teacher_select') == $professor->teacher_id ? 'selected' : '')}}>{{ $professor->full_name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									@endif
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Student') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv student_list">
											<select class="form-control" id="student" name="student[]" multiple="multiple">
												@foreach($students as $sub)
													<option value="{{ $sub->student_id }}"  @foreach($studentOffList as $sublist){{$sublist->student_id == $sub->student_id ? 'selected': ''}}   @endforeach> 
														@php
														$student = App\Models\Student::find($sub->student_id);
														@endphp

														{{$student->firstname}} {{$student->lastname}}
													</option>
												@endforeach
			  								</select>
										</div>
									</div>
									<div class="col-sm-2 p-l-n p-r-n">
										<span class="no_select" id="std-check-div"> <input type="checkbox" name="student_empty" id="student_empty" <?php if(empty($studentOffList[0]->student_id)){ echo 'checked'; } ?>> {{__('do not select') }} <i class="fa fa-info-circle" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="right" title="{{__('If you wish to not select any students for the lesson, for ’school invoiced’ lesson with a many students for example. Remember that if no students are selected, no invoice will be generated for them for that lesson.')}}"></i></span>
									</div>
								</div>
								<div class="form-group row not-allday">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Start date') }} :</label>
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
										<div class="col-sm-4 offset-md-1">
											<div class="input-group"> 
												<input id="start_time" name="start_time" type="text" class="form-control timepicker1" value="{{date('H:i', strtotime($date_start))}}">
												<span class="input-group-addon">
													<i class="fa fa-clock-o"></i>
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
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>	
										<div class="col-sm-4 offset-md-1">
											<div class="input-group"> 
												<input id="end_time" name="end_time" type="text" class="form-control timepicker2" value="{{!empty($lessonData->end_time) ? old('end_time', $lessonData->end_time) : old('end_time')}}">
												<span class="input-group-addon">
													<i class="fa fa-clock-o"></i>
												</span>
											</div>
										</div>	
									</div>
								</div>
								<div class="form-group row not-allday">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Duration') }} :</label>
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
								<div class="form-group row lesson hide_on_off" id="teacher_type_billing">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Teacher type of billing') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="sis_paying" name="sis_paying">
												<option value="0" {{!empty($lessonData->is_paying) ? (old('student_attn', $lessonData->is_paying) == 0 ? 'selected' : '') : (old('student_attn') == 0 ? 'selected' : '')}}>Hourly rate</option>
												<option value="1" {{!empty($lessonData->is_paying) ? (old('student_attn', $lessonData->is_paying) == 1 ? 'selected' : '') : (old('student_attn') == 1 ? 'selected' : '')}}>Price per student</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row lesson hide_on_off">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Student type of billing') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="student_sis_paying" name="student_sis_paying">
												<option value="0" {{!empty($lessonData->is_paying) ? (old('student_attn', $lessonData->is_paying) == 0 ? 'selected' : '') : (old('student_attn') == 0 ? 'selected' : '')}}>Hourly rate</option>
												<option value="1" {{!empty($lessonData->is_paying) ? (old('student_attn', $lessonData->is_paying) == 1 ? 'selected' : '') : (old('student_attn') == 1 ? 'selected' : '')}}>Fixed price</option>
												<option value="2" {{!empty($lessonData->is_paying) ? (old('student_attn', $lessonData->is_paying) == 2 ? 'selected' : '') : (old('student_attn') == 2 ? 'selected' : '')}}>Packaged</option>
											</select>
										</div>
									</div>
								</div>
								
								<div class="form-group row" id="hourly" style="display:none">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Number of students') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="sevent_price" name="sevent_price">
												@foreach($lessonPrice as $key => $lessprice)
													<option value="{{ $lessprice->lesson_price_student }}" {{!empty($lessonData->no_of_students) ? (old('sevent_price', 'price_'.$lessonData->no_of_students) == $lessprice->lesson_price_student ? 'selected' : '') : (old('sevent_price') == 'price_'.$lessprice->lesson_price_student ? 'selected' : '')}}>
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
								</div>

								<div id="price_per_student">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Currency') }} :</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="sprice_currency" name="sprice_currency" readonly>
												@foreach($currency as $key => $curr)
													<option value="{{$curr->currency_code}}">{{$curr->currency_code}}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Teacher price (class/hour)') }} :</label>
									@endif
									<div class="col-sm-4">
										<div class="input-group" id="sprice_amount_buy_div"> 
											@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
											<span class="input-group-addon">
												<i class="fa fa-calendar1"></i>
											</span>
											<input id="sprice_amount_buy" name="sprice_amount_buy" type="text" class="form-control" value="{{ isset($lessonData['price_amount_buy']) && !empty($lessonData['price_amount_buy']) ? $lessonData['price_amount_buy'] : 0 }}" autocomplete="off">
											@else
											<input id="sprice_amount_buy" name="sprice_amount_buy" type="hidden" class="form-control" value="{{ isset($lessonData['price_amount_buy']) && !empty($lessonData['price_amount_buy']) ? $lessonData['price_amount_buy'] : 0 }}" autocomplete="off">
											@endif
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Student price (student/hour)') }} :</label>
									<div class="col-sm-4">
										<div class="input-group" id="sprice_amount_sell_div"> 
											<span class="input-group-addon">
												<i class="fa fa-calendar1"></i>
											</span>
											<input id="sprice_amount_sell" name="sprice_amount_sell" type="text" class="form-control" value="{{ isset($lessonData['price_amount_sell']) && !empty($lessonData['price_amount_sell']) ? $lessonData['price_amount_sell'] : 0 }}" autocomplete="off">
										</div>
									</div>
								</div>
								</div>
							</div>
							<div class="section_header_class">
								<label id="teacher_personal_data_caption">{{ __('Attendance') }}</label>
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
																	<span>{{ __('Student') }}</span>
																</th>
																<th width="15%" style="text-align:left">
																	<button id="mark_present_btn" class="btn btn-xs btn-theme-success" type="button" style="display: block;">Mark all present</button>	
																</th>
																@if($showPrice)
																@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
																	<th width="15%" style="text-align:right;">
																		<label id="row_hdr_buy" name="row_hdr_buy">{{ __('Teacher') }}</label>
																	</th>
																@endif
																	<th width="15%" style="text-align:right">
																		<label id="row_hdr_sale" name="row_hdr_sale">{{ __('Student') }}</label>
																	</th>
																@endif
															</tr>
															
															@foreach($studentOffList as $student)
															<tr>
																<!--<td>{{ $student->student_id }}</td>-->
																<td>
																<img src="{{ asset('img/photo_blank.jpg') }}" width="18" height="18" class="img-circle account-img-small">
																@php
																$studentName = App\Models\Student::find($student->student_id);
																@endphp
																{{$studentName->firstname}} {{$studentName->lastname}}
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
																@if($showPrice)
																@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
																	<td style="text-align:right"> {{ isset($lessonData->price_currency) && !empty($lessonData->price_currency) ? $lessonData->price_currency : '' }} {{ isset($relationData->buy_price) ? $relationData->buy_price: 0  }}</td>
																@else
																
																@endif
																	<td style="text-align:right">{{ isset($lessonData->price_currency) && !empty($lessonData->price_currency) ? $lessonData->price_currency : '' }} {{ isset($relationData->sell_price) ? $relationData->sell_price : 0 }}</td>
																@endif
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
							<div id="button_lock_and_save_div" class="alert alert-info mt-5 mb-3 text-center" role="alert" style="position: relative; display: block;"><label id="button_lock_and_save_help_text"><i class="fa-regular fa-bell fa-bounce"></i> Please validate the event to make it available for invoicing</label>
								<div class="save_and_more_area mt-1">
								<input type="submit" class="btn btn-sm btn-info button_lock_and_save w-100" name="validate" value="Validate">
								<i class="fa-solid fa-lock"></i>
								</div>
							</div>
							@endif
							</div>
							<div class="section_header_class">
								<label id="teacher_personal_data_caption">{{ __('Optional information') }}</label>
							</div>
							<div class="col-md-12">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Description') }} :</label>
									<div class="col-sm-7">
										<div class="input-group"> 
											<textarea class="form-control" cols="60" id="description" name="description" rows="5">{{!empty($lessonData->description) ? old('description', $lessonData->description) : old('description')}}</textarea>
										</div>
									</div>
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
	<div class="col-lg-2">

		<div class="col-lg-2 btn_actions" style="position: fixed; right: 0;">
			<div class="section_header_class">
				<label><br></label>
			</div>
			<div class="card" style="border-radius: 8px 0 0 8px; background-color: #EEE;">
				<div class="card-body p-3 pb-3">
					<div class="row">
						<div class="col-12">
							<a class="btn btn-default w-100 mb-1" href="<?= $BASE_URL; ?>/agenda"><i class="fa-solid fa-arrow-left"></i> Back</a>
						</div>
						<div class="col-12">
							@if($AppUI->person_id == $lessonData->teacher_id)
								@can('self-delete-event')
									<a class="btn btn-theme-warn w-100 mb-1" href="#" id="delete_btn" style="display: block !important;">Delete</a>
								@endcan
							@else
								@if(($lessonData->eventcategory->invoiced_type == 'S') && ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()))
									<a class="btn btn-theme-warn w-100 mb-1" href="#" id="delete_btn" style="display: block !important;">Delete</a>
								@else
									@can('self-delete-event')
										<a class="btn btn-theme-warn w-100 mb-1" href="#" id="delete_btn" style="display: block !important;">Delete</a>
									@endcan
								@endif
							@endif
						</div>
					</div>
				
					<button id="save_btn" class="btn btn-theme-success w-100 mb-1">{{ __('Save') }}</button>
					<button id="save_btn_more" class="btn btn-theme-success w-100 mb-1">{{ __('Save & add more') }}</button>
				
					<div class="d-none d-sm-block">
						@if(strtotime($date_end) < strtotime($current_time))
						<div id="button_lock_and_save_div" class="alert alert-info mt-5 text-center" role="alert">
							<label id="button_lock_and_save_help_text"><i class="fa-regular fa-bell fa-bounce"></i> Please validate the event to make it available for invoicing</label>
							<div class="save_and_more_area">
								<input type="submit" class="mt-1 btn btn-sm btn-info button_lock_and_save w-100 mb-1"  name="validate" value="{{ __('Validate') }}">
								<i class="fa-solid fa-lock"></i>
							</div>
						</div>
						@endif
					</div>
				</div>

				</div>
			</div>
		</div>

	</div>

	</div>

</form>

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
	}else{
		document.getElementById("sevent_price").value='price_'+cnt;
	}
		
	 
})

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
			$('#sprice_amount_sell').prop('disabled', true);   
		}else if(s_std_pay_type == 1){
			$('#sprice_amount_sell').prop('disabled', false);  
		}else if(s_std_pay_type == 2){
			$('#sprice_amount_sell').prop('disabled', true);  
		}

    }else{
        $("#teacher_type_billing").hide();
        $("#student_sis_paying").val(t_std_pay_type);
		if(s_thr_pay_type == 0){
			$('#sprice_amount_buy').prop('disabled', true);   
		}else if(s_thr_pay_type == 1){
			$('#sprice_amount_buy').prop('disabled', false);  
		}
		if(t_std_pay_type == 0){
			$('#sprice_amount_sell').prop('disabled', true);   
		}else if(t_std_pay_type == 1){
			$('#sprice_amount_sell').prop('disabled', false);  
		}else if(t_std_pay_type == 2){
			$('#sprice_amount_sell').prop('disabled', true);  
		}
    }
	
	if(s_thr_pay_type == 0){
		$('#hourly').show();
        $('#price_per_student').show();
	}else if(s_thr_pay_type == 1 && s_std_pay_type == 1){
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
	$('#price_per_student').show();
	if(this.value == 0){
		$('#hourly').show();
	}else if(this.value == 1){
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
		url: BASE_URL + '/{{$schoolId}}/student-attend-action/{{$lessonlId}}',
		data: { type:1, stuId:stuId, typeId:typeId },
		type: 'POST',
		dataType: 'json',
		beforeSend: function( xhr ) {
			$("#pageloader").fadeIn();
		},
		success: function(response){	
			if(response.status == 1){
				$("#pageloader").fadeOut();
			}
		},
		complete: function( xhr ) {
			
		},
		error: function (ts) { 
				$("#pageloader").fadeOut();
                ts.responseText+'-'+errorModalCall('{{ __("Lesson validation error ")}}');
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
			url: BASE_URL + '/{{$schoolId}}/student-attend-action/{{$lessonlId}}',
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

$("body").on('click', '#all_day', function(event) {
    if ($(this).prop('checked')) {
        $(".not-allday").hide();
    }else{
        $(".not-allday").show();
    }
})

if ({{!empty($lessonData->fullday_flag) ? 1 : 0}}) {
	$("#all_day").click()
}


$('#edit_lesson').on('submit', function() {
	var title = $('#Title').val();
	var professor = $('#teacher_select').val();
	var selected = $("#student :selected").map((_, e) => e.value).get();
	var startDate = $('#start_date').val();
	var endDate = $('#end_date').val();
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
		var errMssg = 'End date required';
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
		var p_event_type_id='{{$lessonlId}}';
		console.log(p_event_type_id);
		//var retVal = confirm("Tous les événements affichés seront supprimés. Voulez-vous supprimer ?");
		e.preventDefault();
		confirmDeleteModalCall('','Do you want to delete this event',"delete_event("+p_event_type_id+");",false);
		return false;
	})
	$("body").on('change', '#category_select', function(event) {

    	var categoryId = +$("#category_select").val();
	    var teacherSelect = +$("#teacher_select").val();
	    var stdSelected = $("#student :selected").map((_, e) => e.value).get().length;

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
				$('#sprice_amount_sell').prop('disabled', true);   
			}else if(s_std_pay_type == 1){
				$('#sprice_amount_sell').prop('disabled', false);  
			}else if(s_std_pay_type == 2){
				$('#sprice_amount_sell').prop('disabled', true);  
			}
			
		}else{
			$("#sis_paying").val(s_thr_pay_type);
			$("#student_sis_paying").val(t_std_pay_type);
			$("#std-check-div").css('display', 'none');
			$("#teacher_type_billing").show();
			$("#student_empty").prop('checked', false);

			if(s_thr_pay_type == 0){
				$('#sprice_amount_buy').prop('disabled', true);   
			}else if(s_thr_pay_type == 1){
				$('#sprice_amount_buy').prop('disabled', false);  
			}

			if(t_std_pay_type == 0){
				$('#sprice_amount_sell').prop('disabled', true);   
			}else if(t_std_pay_type == 1){
				$('#sprice_amount_sell').prop('disabled', false);  
			}else if(t_std_pay_type == 2){
				$('#sprice_amount_sell').prop('disabled', true);  
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

        getLatestPrice();
	});

$("#student, #teacher_select").on('change', function(event) {
    getLatestPrice()
});

	function getLatestPrice() {
	    var agendaSelect = +$("#agenda_select").val();
	    var categoryId = +$("#category_select").val();
	    var teacherSelect = +$("#teacher_select").val();
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
	    formData.push({
	        "name": "teacher_select",
	        "value": teacherSelect,
	    });
	    formData.push({
	        "name": "no_of_students",
	        "value": stdSelected,
	    });
	    
	    if (categoryId > 0 && teacherSelect > 0) {
	        $.ajax({
	            url: BASE_URL + '/check-lesson-fixed-price',
	            async: false, 
	            data: formData,
	            type: 'POST',
	            dataType: 'json',
	            success: function(response){
	                if(response.status == 1){
	                    if (response.data) {
	                        $("#sprice_amount_buy").val(response.data.price_buy)
	                        @if(isset($lessonData->eventcategory->t_std_pay_type) && $lessonData->eventcategory->t_std_pay_type == 1)
	                        @else
	                        $("#sprice_amount_sell").val(response.data.price_sell)
	                        @endif
	                    }
	                }
	            }
	        })
	    }
	    
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
			$("#save_btn_value"). val(1);
			});
			$("#save_btn").click(function(){
			$("#save_btn_value"). val(0);
			});
		});
	});

	$(window).scroll(function() {    
		var scroll = $(window).scrollTop();
		if (scroll >= 80) {
				$("#edit_lesson .btn_area").addClass("btn_area_fixed");
		} else {
			$("#edit_lesson .btn_area").removeClass("btn_area_fixed");
		}
	});
</script>
@endsection`