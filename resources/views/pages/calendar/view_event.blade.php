@extends('layouts.main')

@section('head_links')

@endsection
<!-- Code within resources/views/blade.php -->
@php
	//$zone = $_COOKIE['timezone_user'];
	$zone = $timezone;
	$date_start = Helper::formatDateTimeZone($eventData->date_start, 'long','UTC',$zone);
	$date_end = Helper::formatDateTimeZone($eventData->date_end, 'long','UTC', $zone);
	$priceShow = ($AppUI->isSchoolAdmin() || $AppUI->isTeacherAdmin()) && ($eventData->event_invoice_type == 'S') || ($AppUI->isTeacher() && ($eventData->event_invoice_type == 'T')) 
@endphp
@section('content')
  <div class="content">
	<div class="container-fluid">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area">
					<div class="page_header_class">
						<label id="page_header" class="page_header bold" name="page_header">{{ __('Event') }} : <i class="fa fa-plus-square" aria-hidden="true"></i></label>
					</div>
				</div>    
				<div class="col-sm-6 col-xs-12 btn-area">
					<div class="pull-right btn-group">
						<a class="btn btn-sm btn-info text-white" href="<?= $BASE_URL;?>/agenda" id="back_btn"> 
							<i class="fa fa-arrow-left"></i>
							{{ __('Back')}}
						</a>
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
		<div class="tab-content view_part" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Event information') }}</label>
						</div>
						@if($eventData->is_locked ==1)
						<div class="alert alert-warning">
							<label>This course is blocked, but it can still be modified by first clicking the unlock button.</label>
							<button class="btn btn-sm btn-warning" onclick="confirm_event(true)">Unlock</button>
							<input type="hidden" name="confirm_event_id" id="confirm_event_id" value="{{ !empty($eventId) ? $eventId : ''; }}">
				
						</div>
						@endif
						<div class="row">
							<div class="col-md-7 offset-md-2">
							<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Location') }} :</label>
									<div class="col-sm-7">
										{{ !empty($locations->title) ? $locations->title : ''; }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Title') }} :</label>
									<div class="col-sm-7">
										{{ !empty($eventData->title) ? $eventData->title : ''; }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Professor') }} :</label>
									<div class="col-sm-7">
										{{!empty($professors->full_name) ? $professors->full_name : ''; }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Student') }} :</label>
									<div class="col-sm-7">

									<?php $i=1; $count = count($studentOffList); 
										foreach($studentOffList as $student){
											echo $student->nickname;
											if($i != $count){
												echo ', ';
											}
										$i++;
										}
									?>	
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Start date') }} :</label>
									<div class="col-sm-7">
										{{ !empty($date_start) ? date('l jS F-Y', strtotime($date_start)) : ''; }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('End Date') }} :</label>
									<div class="col-sm-7">
										{{ !empty($date_end) ? date('l jS F-Y', strtotime($date_end)) : ''; }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('All day') }} :</label>
									<div class="col-sm-7">
										{{ !empty($eventData->fullday_flag) ? 'Yes' : 'Non(No)' }}
									</div>
								</div>
								@if($priceShow)
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left">{{__('Currency') }} :</label>
										<div class="col-sm-7">
											{{ !empty($eventData->price_currency) ? $eventData->price_currency : '' }}
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left">{{__('Teacher price (per event)') }} :</label>
										<div class="col-sm-7">
											{{ !empty($eventData->price_amount_buy) ? $eventData->price_amount_buy : '' }}
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left">{{__('Student price (per student)') }} :</label>
										<div class="col-sm-7">
											{{ !empty($eventData->price_amount_sell) ? $eventData->price_amount_sell : '' }}
										</div>
									</div>
								@endif
							</div>
							<div class="section_header_class">
								<label id="teacher_personal_data_caption">{{ __('Attendance') }}</label>
							</div>
							<div class="col-md-7 offset-md-2">
								<div class="form-group row">
									<div class="col-sm-12">
										<div class="form-group row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table id="attn_tbl" class="table">
														<tbody>
															<tr>
																<th width="5%" style="text-align:left"></th>
																<th width="15%" style="text-align:left">
																<span>{{ __('Student') }}</span>
																</th>
																<th width="15%" style="text-align:left"></th>
																@if($priceShow)
																	<th width="10%" style="text-align:left;">
																		<label id="row_hdr_buy" name="row_hdr_buy">{{ __('Teacher') }}</label>
																		<label>({{ !empty($eventData->price_currency) ? $eventData->price_currency : '' }})</label>
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
																<td>{{ $student->student_id }}</td>
																<td>
																<img src="{{ asset('img/photo_blank.jpg') }}" width="18" height="18" class="img-circle account-img-small"> {{ $student->nickname }}
																</td>
																<td>Present</td>
																@if($priceShow)
																	<td>{{ $student->buy_price }}</td>
																	<td style="text-align:center">{{ $student->sell_price }}</td>
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
							<div class="col-md-7 offset-md-2">
								<div class="form-group row">
									<div class="col-sm-7">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left">{{__('Description') }} :</label>
										<div class="col-sm-7 descrip">
											{{ !empty($eventData->description) ? $eventData->description : ''; }}
										</div>
									</div>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
					<button id="save_btn" class="btn btn-theme-back">{{ __('Back') }} </button>
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
                $("#pageloader").show();
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
                $("#pageloader").hide();
            },
            error: function (ts) { 
                ts.responseText+'-'+errorModalCall('{{ __("Event validation error ")}}');
            }
        }); //ajax-type            

    }
</script>
@endsection