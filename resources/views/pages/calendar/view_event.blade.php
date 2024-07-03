@extends('layouts.main')

@section('head_links')

@endsection
<!-- Code within resources/views/blade.php -->
@php
	//$zone = $_COOKIE['timezone_user'];
	$zone = $timezone;
	$initDate = new Helper();
	$date_start = $initDate->formatDateTimeZone($eventData->date_start, 'long','UTC',$zone);
	$date_end = $initDate->formatDateTimeZone($eventData->date_end, 'long','UTC', $zone);
	$priceShow = ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()) && ($eventData->event_invoice_type == 'S') || ($AppUI->isTeacher() && ($eventData->event_invoice_type == 'T')) 
@endphp
@section('content')
  <div class="content">
	<div class="container">

		<div class="row justify-content-center pt-3 pb-4">
			<div class="col-md-10">

				<div class="page_header_class pt-1" style="position: static;">
					<h5 class="titlePage">{{ __('Event') }}</h5>
				</div>

	<div class="row">


		<!-- Tabs content -->
		<div class="tab-content view_part" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
					<fieldset>
						@if((($AppUI->person_id == $eventData->teacher_id) || (  ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()))) && ($eventData->is_locked ==1))
							<div class="alert alert-warning">
								<label>This event is blocked, but it can still be modified by first clicking the unlock button.</label>
								<!--<button class="btn btn-warning" onclick="confirm_event(true)"><i class="fa-solid fa-lock-open"></i> Unlock</button>-->
								<input type="hidden" name="confirm_event_id" id="confirm_event_id" value="{{ !empty($eventId) ? $eventId : ''; }}">
					
							</div>
						@endif
						<div class="card2">
							<div class="card-header titleCardPage">{{ __('Event detail') }}</div>
							<div class="card-body bg-tertiary">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-stripped table-hover">
									  <tbody>
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left titleFieldPage">{{__('Location') }} :</th>
										  <td class="col-sm-7">{{ !empty($locations->title) ? $locations->title : '-' }}</td>
										</tr>
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left titleFieldPage">{{__('Title') }} :</th>
										  <td class="col-sm-7">{{ !empty($eventData->title) ? $eventData->title : '-' }}</td>
										</tr>
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left titleFieldPage">{{__('Professor') }} :</th>
										  <td class="col-sm-7">{{ !empty($professors->full_name) ? $professors->full_name : '-' }}</td>
										</tr>
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left titleFieldPage">{{__('Student') }} :</th>
										  <td class="col-sm-7">
											<?php $i=1; $count = count($studentOffList);
											foreach($studentOffList as $student){
											  echo $student->nickname;
											  if($i != $count){
												echo ', ';
											  }
											  $i++;
											}
											?>  
										  </td>
										</tr>
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left titleFieldPage">{{__('Start date') }} :</th>
										  <td class="col-sm-7">{{ !empty($date_start) ? date('l jS F-Y', strtotime($date_start)) : '-' }}</td>
										</tr>
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left titleFieldPage">{{__('End Date') }} :</th>
										  <td class="col-sm-7">{{ !empty($date_end) ? date('l jS F-Y', strtotime($date_end)) : '-' }}</td>
										</tr>
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left titleFieldPage">{{__('All day') }} :</th>
										  <td class="col-sm-7">{{ !empty($eventData->fullday_flag) ? 'Yes' : 'No' }}</td>
										</tr>
										
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left titleFieldPage">{{__('Currency') }} :</th>
										  <td class="col-sm-7">{{ !empty($eventData->price_currency) ? $eventData->price_currency : '-' }}</td>
										</tr>
										@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left titleFieldPage">{{__('Teacher price (per event)') }} :</th>
										  <td class="col-sm-7">{{ !empty($eventData->price_amount_buy) ? $eventData->price_amount_buy : '-' }}</td>
										</tr>
										@endif
										<tr>
											<th class="col-lg-3 col-sm-3 text-left titleFieldPage">{{__('Student price (per student)') }} :</th>
											<td class="col-sm-7">{{ !empty($eventData->price_amount_sell) ? $eventData->price_amount_sell : '-' }}</td>
										</tr>
									  </tbody>
									</table>
								  </div>
								  <div class="card2">
									<div class="card-header titleCardPage">{{ __('Students') }}</div>
									<div class="card-body bg-tertiary">
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
																<th width="40%" style="text-align:left">
																<span>{{ __('Student') }}</span>
																</th>
																<th width="15%" style="text-align:left"></th>
															
																	<th width="15%" style="text-align:center;">
																		@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin() || $AppUI->isTeacherMinimum() || $AppUI->isTeacherMedium())
																		<label  name="row_hdr_buy">{{ __('Teacher') }}</label>
																		@endif
																	</th>
																	<th width="15%" style="text-align:center">
																		<label id="row_hdr_sale" name="row_hdr_sale">{{ __('Student') }}</label>
																	</th>
																	<th width="15%" style="text-align:center">
																		<label>{{ __('Extra charges') }}</label>
																	</th>
																
															</tr>
															@foreach($studentOffList as $student)
															<tr>
																<!--<td>{{ $student->student_id }}</td>-->
																<td>
																<img src="{{ asset('img/photo_blank.jpg') }}" width="18" height="18" class="img-circle account-img-small"> {{ $student->nickname }}
																</td>
																<td>Present</td>
														
																	<td style="text-align:center">
																		@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin() || $AppUI->isTeacherMinimum() || $AppUI->isTeacherMedium())
																		{{ number_format($student->price_amount_sell, 2) }}  {{ $student->price_currency }}
																		@endif
																	</td>
																	<td style="text-align:center">
																		{{ number_format($student->price_amount_buy, 2) }}  {{ $student->price_currency }}																
																	</td>
																	<td style="text-align:center">
																		{{ number_format($eventData->extra_charges, 2) }}  {{ $eventData->price_currency }}
																	</td>
																
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

									</div></div>

									<div class="card2">
										<div class="card-header titleCardPage">{{ __('Optional information') }}</div>
										<div class="card-body bg-tertiary">
							<div class="col-md-12">
								<div class="form-group row">
									<div class="col-sm-7">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left">{{__('Description') }} :</label>
										<div class="col-sm-7 descrip">
											{{ !empty($eventData->description) ? $eventData->description : 'no description.'; }}
										</div>
									</div>
									</div>
								</div>
							</div>
										</div></div>
						</div>
							</div>
						</div>
					</fieldset>
					<!--<button id="save_btn" class="btn btn-theme-back">{{ __('Back') }} </button>-->
			</div>


		</div>


	</div>
</div>
	</div>


	</div>
  </div>

  <div class="row justify-content-center" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important; width:100%; text-align:center;">
	<div class="col-md-12 mt-3 pt-3 pb-3 d-flex justify-content-center card-header text-center" style="opacity:0.91!important; background-color:#DDDD!important; text-align:center;">

		@if((($AppUI->person_id == $eventData->teacher_id) || (  ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()))) && ($eventData->is_locked ==1))

			<button class="btn btn-sm btn-warning" onclick="confirm_event(true)"><i class="fa-solid fa-lock-open"></i> Unlock</button>
			<input type="hidden" name="confirm_event_id" id="confirm_event_id" value="{{ !empty($eventId) ? $eventId : ''; }}">

		@endif

		</div>
	</div>

@endsection


@section('footer_js')
<script type="text/javascript">
	function confirm_event(unlock=false){
        var p_event_auto_id=document.getElementById('confirm_event_id').value;
        console.log(p_event_auto_id);
        var data = 'p_event_auto_id=' + p_event_auto_id;
        if (unlock) {
            var data = 'unlock=1&p_event_auto_id=' + p_event_auto_id;
        }
        
        var status = '';
        $.ajax({
            url: BASE_URL + '/confirm_event',
            data: data,
            type: 'POST',
            dataType: 'json',
            beforeSend: function( xhr ) {
				$("#pageloader").fadeIn();
            },
            success: function (result) {
                status = result.status;
                if (status == 'success') {
                    // if (unlock) {
                    //     successModalCall('{{ __("Event has been unlocked ")}}');
                    // } else{
                    //     successModalCall('{{ __("Event has been validated ")}}');
                    // }

					window.location.href = '/{{$schoolId}}/edit-event/{{$eventId}}'
                }
                else {
                    errorModalCall('{{ __("Event validation error ")}}');
                }
            },   //success
            complete: function( xhr ) {
				
            },
            error: function (ts) { 
				$("#pageloader").fadeOut();
                ts.responseText+'-'+errorModalCall('{{ __("Event validation error ")}}');
            }
        }); //ajax-type            

    }
</script>
@endsection