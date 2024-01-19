@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<script src="{{ asset('js/jquery.multiselect.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/jquery.multiselect.css') }}">
<script src="{{ asset('js/lib/moment.min.js')}}"></script>

@endsection

@section('content')
  <div class="content">
	<div class="container">



        <div class="row justify-content-center pt-1">
            <div class="col-md-9 mb-4">
                <h5>{{ __('Student time off') }}</h5>

                <form class="form-horizontal" id="student_off" method="post" action="{{ route('studentOff.createAction',[$schoolId]) }}"  name="student_off" role="form">
        <div class="card" style="border-radius:10px;">
            <div class="card-header d-flex justify-content-between align-items-center">
                {{ __('Time off information') }}
            </div>
            <div class="card-body">

					@csrf
					<input id="save_btn_value" name="save_btn_more" type="hidden" class="form-control" value="3">
					<fieldset>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group row">
									<label class="col-lg-12 col-sm-12 text-left" for="availability_select" id="visibility_label_id">{{__('Title') }} :</label>
									<div class="col-sm-6">
										<div class="input-group">
											<input id="Title" name="title" type="text" class="form-control" value="{{old('title')}}">
										</div>
									</div>
								</div>
								@if(!$AppUI->isStudent())
									<div class="form-group row">
										<label class="col-lg-12 col-sm-12 text-left" for="availability_select" id="visibility_label_id">{{__('Student') }} :</label>
										<div class="col-sm-6">
											<div class="selectdiv student_list">
												<select class="form-control" id="student" name="student[]" multiple="multiple">
													@foreach($students as $key => $student)
														<option value="{{ $student->student_id }}" {{ old('student') == $student->id ? 'selected' : ''}}>{{ $student->nickname }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
								@endif
								<div class="form-group row">
									<label class="col-lg-12 col-sm-12 text-left" for="availability_select" id="visibility_label_id">{{__('Start date') }} :</label>
									<div class="col-sm-12 row">
										<div class="col-sm-6">
											<div class="input-group" id="start_date_div">
												<input id="start_date" required="true" name="start_date" type="text" class="form-control" value="{{old('start_date')}}" autocomplete="off">
												<input type="hidden" name="zone" id="zone" value="<?php echo $timezone; ?>">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>

                                            @if($futurEvents && $futurEvents->count() > 0)
                                                <div class="card bg-warning p-2 mt-2">
                                                <b>Information</b>
                                                @foreach ($futurEvents as $key => $event)
                                                {{ __('You have a lesson tomorrow') }} {{ $event->event->date_start }}
                                                @endforeach
                                                </div>
                                            @endif

										</div>

									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-12 col-sm-12 text-left" for="availability_select" id="visibility_label_id">{{__('End date') }} :</label>
									<div class="col-sm-12 row">
										<div class="col-sm-6">
											<div class="input-group" id="end_date_div">
												<input id="end_date" required="true" name="end_date" type="text" class="form-control" value="{{old('end_date')}}" autocomplete="off">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
								@if(!$AppUI->isStudent() && !$AppUI->isParent())
									<div class="form-group row">
										<div id="all_day_div111" class="row">
											<label class="col-lg-12 col-sm-12 text-left" for="fullday_flag" id="has_user_ac_label_id">{{__('All day') }} :</label>
											<div class="col-sm-12">
												<input id="fullday_flag" name="fullday_flag" type="checkbox" value="Y">
											</div>
										</div>
									</div>
								@endif

							</div>
							<div class="col-md-12">
								<div class="form-group row">
									<label class="col-lg-12 col-sm-12 text-left" for="availability_select" id="visibility_label_id">{{__('Description') }} :</label>
									<div class="col-sm-12">
										<div class="input-group">
											<textarea class="form-control" cols="60" id="description" name="description" rows="5">{{old('description')}}</textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</fieldset>


			</div>
		</div>
        <div class="pt-3">
            <a class="btn btn-theme-outline" href="<?= $BASE_URL;?>/agenda">Back</a>
            <button id="save_btn" name="save_btn" class="btn btn-theme-success"><i class="fa fa-save"></i>{{ __('Save') }} </button>
        </div>
    </form>
	</div>
@endsection


@section('footer_js')
<script type="text/javascript">
$(function() {
	// var zone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    // document.getElementById("zone").value = zone;
    var today = new Date();
today.setDate(today.getDate() + 2); // Définir la date minimale comme demain

	var zone = document.getElementById("zone").value;
	$("#start_date").datetimepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
        minDate: today
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

//Verifiy dates
$('#start_date').on('change', function(e) {

var startDate = moment($("#start_date").val(), "DD/MM/YYYY");
var today = moment().add(2, 'days');

if (startDate.isBefore(today, 'day')) {
    errorModalCall('{{ __("Please select a time-off after tomorrow")}}');
} else {
	setTimeout(() => {
		$("#end_date").val($("#start_date").val());
	}, "200")
}

if ($("#end_date").val() < $("#start_date").val()) {
	$("#end_date").val($("#start_date").val());
	setTimeout(() => {
		$("#end_date").val($("#start_date").val());
	}, "200")
}
})

$('#end_date').on('change', function(e) {
    var startDate = moment($("#start_date").val(), "DD/MM/YYYY");
    var endDate = moment($("#end_date").val(), "DD/MM/YYYY");

    if (endDate.isBefore(startDate)) {
        $("#end_date").val($("#start_date").val());
        errorModalCall('{{ __("Please ensure that the end date comes after the start date ")}}');
        setTimeout(() => {
            $("#end_date").val($("#start_date").val());
        }, 200);
    }
});

// save functionality
$('#student_off').on('submit', function() {
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

	$(window).scroll(function() {
		var scroll = $(window).scrollTop();
		if (scroll >= 80) {
				$("#student_off .btn_area").addClass("btn_area_fixed");
		} else {
			$("#student_off .btn_area").removeClass("btn_area_fixed");
		}
	});
</script>
@endsection
