@extends('layouts.main')

@section('head_links')

@endsection
<!-- Code within resources/views/blade.php -->
@php
	//$zone = $_COOKIE['timezone_user'];
	$zone = $timezone;
	$date_start = Helper::formatDateTimeZone($eventData->date_start, 'long','UTC',$zone);
	$date_end = Helper::formatDateTimeZone($eventData->date_end, 'long','UTC', $zone);
	$priceShow = ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()) && ($eventData->event_invoice_type == 'S') || ($AppUI->isTeacher() && ($eventData->event_invoice_type == 'T')) 
@endphp
@section('content')
  <div class="content">
	<div class="container-fluid body">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area" style="padding-top:8px; padding-bottom:20px;">
					<div class="page_header_class">
						<label id="page_header" class="page_header bold" name="page_header"><i class="fa-solid fa-calendar-day"></i> {{ __('Event') }}</label>
					</div>
				</div>    
				<div class="col-sm-6 col-xs-12 btn-area">
					
				</div>
			</div>          
	
		<!-- Tabs navs -->

		<nav style="margin-bottom:0; padding-bottom:0;">
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Event information') }}</button>
			</div>
		</nav>
		<!-- Tabs navs -->

	</header>

	<div class="row">
		<div class="col-lg-10">

		<!-- Tabs content -->
		<div class="tab-content view_part" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Event information') }}</label>
						</div>
						@if((($AppUI->person_id == $eventData->teacher_id) || (  ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()))) && ($eventData->is_locked ==1))
							<div class="alert alert-warning">
								<label>This event is blocked, but it can still be modified by first clicking the unlock button.</label>
								<!--<button class="btn btn-warning" onclick="confirm_event(true)"><i class="fa-solid fa-lock-open"></i> Unlock</button>-->
								<input type="hidden" name="confirm_event_id" id="confirm_event_id" value="{{ !empty($eventId) ? $eventId : ''; }}">
					
							</div>
						@endif
						<div class="card">
							<div class="card-body bg-tertiary">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-stripped table-hover">
									  <tbody>
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left">{{__('Location') }} :</th>
										  <td class="col-sm-7">{{ !empty($locations->title) ? $locations->title : '-' }}</td>
										</tr>
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left">{{__('Title') }} :</th>
										  <td class="col-sm-7">{{ !empty($eventData->title) ? $eventData->title : '-' }}</td>
										</tr>
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left">{{__('Professor') }} :</th>
										  <td class="col-sm-7">{{ !empty($professors->full_name) ? $professors->full_name : '-' }}</td>
										</tr>
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left">{{__('Student') }} :</th>
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
										  <th class="col-lg-3 col-sm-3 text-left">{{__('Start date') }} :</th>
										  <td class="col-sm-7">{{ !empty($date_start) ? date('l jS F-Y', strtotime($date_start)) : '-' }}</td>
										</tr>
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left">{{__('End Date') }} :</th>
										  <td class="col-sm-7">{{ !empty($date_end) ? date('l jS F-Y', strtotime($date_end)) : '-' }}</td>
										</tr>
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left">{{__('All day') }} :</th>
										  <td class="col-sm-7">{{ !empty($eventData->fullday_flag) ? 'Yes' : 'No' }}</td>
										</tr>
										@if($priceShow)
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left">{{__('Currency') }} :</th>
										  <td class="col-sm-7">{{ !empty($eventData->price_currency) ? $eventData->price_currency : '-' }}</td>
										</tr>
										@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left">{{__('Teacher price (per event)') }} :</th>
										  <td class="col-sm-7">{{ !empty($eventData->price_amount_buy) ? $eventData->price_amount_buy : '-' }}</td>
										</tr>
										@endif
										<tr>
										  <th class="col-lg-3 col-sm-3 text-left">{{__('Student price (per student)') }} :</th>
										  <td class="col-sm-7">{{ !empty($eventData->price_amount_sell) ? $eventData->price_amount_sell : '-' }}</td>
										</tr>
										@endif
									  </tbody>
									</table>
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
																<th width="40%" style="text-align:left">
																<span>{{ __('Student') }}</span>
																</th>
																<th width="15%" style="text-align:left"></th>
																@if($priceShow)
																	<th width="10%" style="text-align:left;">
																		@if($eventData->is_locked !=1)
																		<label id="row_hdr_buy" name="row_hdr_buy">{{ __('Teacher') }}</label>
																		<label>({{ !empty($eventData->price_currency) ? $eventData->price_currency : '' }})</label>
																		@endif
																	</th>
																	<th width="10%" style="text-align:center">
																		<label id="row_hdr_sale" name="row_hdr_sale">{{ __('Student') }}</label>
																		<label>({{ !empty($eventData->price_currency) ? $eventData->price_currency : '' }})</label>
																	</th>
																	<th width="10%" style="text-align:right">
																		<label>{{ __('Extra charges') }}</label>
																	</th>
																@endif
															</tr>
															@foreach($studentOffList as $student)
															<tr>
																<!--<td>{{ $student->student_id }}</td>-->
																<td>
																<img src="{{ asset('img/photo_blank.jpg') }}" width="18" height="18" class="img-circle account-img-small"> {{ $student->nickname }}
																</td>
																<td>Present</td>
																@if($priceShow)
																	<td>
																		@if($eventData->is_locked !=1)
																		{{ $student->buy_price }}
																		@endif
																	</td>
																	<td style="text-align:center">
																		
																		{{ $student->sell_price }}
																		
																	</td>
																	<td style="text-align:center">{{ $eventData->extra_charges }}</td>
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

							<div class="section_header_class">
								<label id="teacher_personal_data_caption">{{ __('Optional information') }}</label>
							</div>
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
						</div>
							</div>
						</div>
					</fieldset>
					<!--<button id="save_btn" class="btn btn-theme-back">{{ __('Back') }} </button>-->
			</div>


		</div>
	</div>
	<div class="col-lg-2">

		<div class="col-lg-2 btn_actions" style="position: fixed; right: 0;">
			<div class="section_header_class">
				<label><br></label>
			</div>
			<div class="card" style="border-radius: 8px 0 0 8px; background-color: #EEE;">
				<div class="card-body p-3 pb-3 text-center">
					<a class="btn btn-sm btn-info w-100 text-white mb-2" href="<?= $BASE_URL;?>/agenda" id="back_btn"> 
						<i class="fa-solid fa-arrow-left"></i>
						{{ __('Back')}}
					</a>

					@if((($AppUI->person_id == $eventData->teacher_id) || (  ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()))) && ($eventData->is_locked ==1))

						<button class="btn btn-sm btn-warning w-100" onclick="confirm_event(true)"><i class="fa-solid fa-lock-open"></i> Unlock</button>
						<input type="hidden" name="confirm_event_id" id="confirm_event_id" value="{{ !empty($eventId) ? $eventId : ''; }}">

					@endif


				</div>
			</div>
		</div>
	</div>
	</div>
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