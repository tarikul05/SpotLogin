@extends('layouts.main')

@section('head_links')

@endsection
<!-- Code within resources/views/blade.php -->
@php
	//$zone = $_COOKIE['timezone_user'];
	$zone = $timezone;
	$date_start = Helper::formatDateTimeZone($lessonData->date_start, 'long','UTC',$zone);
	$date_end = Helper::formatDateTimeZone($lessonData->date_end, 'long','UTC', $zone);
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
				<div class="col-sm-6 col-xs-12 btn-area">
		
				</div>   
			</div>          

		<!-- Tabs navs -->

		<nav style="margin-bottom:0; padding-bottom:0;">
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Lesson information') }}</button>
			</div>
		</nav>
		<!-- Tabs navs -->

	</header>

	
	<div class="row">
		<div class="col-lg-10">

		<input type="hidden" name="confirm_event_id" id="confirm_event_id" value="{{ !empty($lessonlId) ? $lessonlId : ''; }}">
							
		<!-- Tabs content -->
		<div class="tab-content view_part" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Lesson information') }}</label>
						</div>
						@if((($AppUI->person_id == $lessonData->teacher_id) || (($lessonData->eventcategory->invoiced_type == 'S') && ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()))) && ($lessonData->is_locked ==1))
							<div class="alert alert-warning">
								<label>This lesson is blocked, but it can still be modified by first clicking the unlock button.</label>
								<button class="btn btn-sm btn-warning" onclick="confirm_event(true)">Unlock</button>
							</div>
						@endif
						<div class="card">
							<div class="card-body bg-tertiary">
						<div class="row">
							<div class="col-md-12">
							<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Category') }} :</label>
									<div class="col-sm-7">
										{{ !empty($lessonData->title) ? $lessonData->title : ''; }}
									</div>
								</div>
							<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Location') }} :</label>
									<div class="col-sm-7">
										{{ !empty($locations->title) ? $locations->title : ''; }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Assistant Type') }} :</label>
									<div class="col-sm-7">
										{{ !empty($lessonCategory->title) ? $lessonCategory->title : ''; }}
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
									<label class="col-lg-3 col-sm-3 text-left">{{__('Duration') }} :</label>
									<div class="col-sm-7">
										{{ !empty($lessonData->duration_minutes) ? $lessonData->duration_minutes : '' }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('All day') }} :</label>
									<div class="col-sm-7">
										{{ !empty($lessonData->fullday_flag) ? 'Yes' : 'No' }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Type of billing') }} :</label>
									<div class="col-sm-7">
										{{ !empty($lessonData->billing_method) ? $lessonData->billing_method : '' }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Number of students') }} :</label>
									<div class="col-sm-7">
										{{ !empty($lessonData->price_amount_buy) ? 'Group lessons for '.$lessonData->no_of_students.' students' : '' }}
									</div>
								</div>
							</div>
						</div>
							</div>
						</div>
							<div class="section_header_class">
								<label id="teacher_personal_data_caption">{{ __('Attendance') }}</label>
							</div>
							<div class="card">
								<div class="card-body bg-tertiary">
							<div class="row">
								
							<div class="col-md-12">
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
																@if($showPrice)
																	<th width="10%" style="text-align:left;">
																	@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
																	<label id="row_hdr_buy" name="row_hdr_buy">{{ __('Teacher') }}</label>
																	<label>{{ !empty($eventData->price_currency) ? '(' + $eventData->price_currency + ')' : '' }}</label>
																	@endif
																	</th>
																	<th width="10%" style="text-align:center">
																	<label id="row_hdr_sale" name="row_hdr_sale">{{ __('Student') }}</label>
																	<label>{{ !empty($eventData->price_currency) ? '(' + $eventData->price_currency + ')' : '' }}</label>
																	</th>
																@endif
															</tr>
															@foreach($studentOffList as $student)
															<tr>
																<td>{{ $student->student_id }}</td>
																<td>
																<img src="{{ asset('img/photo_blank.jpg') }}" width="18" height="18" class="img-circle account-img-small"> {{ $student->nickname }}
																</td>
																<td><?php if(!empty($student->participation_id)){ if($student->participation_id == 0 ){ echo 'scheduled'; }elseif($student->participation_id == 199 ){ echo 'Absent'; }elseif($student->participation_id == 200 ){ echo 'Present'; } }  ?></td>
																@if($showPrice)
																	<td>
																		@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
																		{{ $student->buy_price }}
																		@endif
																	</td>
																	<td style="text-align:center">{{ $student->sell_price }}</td>
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
							</div>

								</div>
							</div>

							<div class="section_header_class">
								<label id="teacher_personal_data_caption">{{ __('Optional information') }}</label>
							</div>
							<div class="card">
								<div class="card-body bg-tertiary">
							<div class="row">
							<div class="col-md-12">
								<div class="form-group row">
									<div class="col-sm-7">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left">{{__('Description') }} :</label>
										<div class="col-sm-7 descrip">
											{{ !empty($lessonData->description) ? $lessonData->description : 'no description'; }}
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
				<div class="card-body d-flex flex-wrap">
					<a class="btn btn-sm btn-info text-white" href="<?= $BASE_URL;?>/agenda" id="back_btn"> 
						<i class="fa fa-arrow-left"></i>
						{{ __('Back')}}
					</a>
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
					// window.location.reload();
					window.location.href = '/{{$schoolId}}/edit-lesson/{{$lessonlId}}'
                }
                else {
                    errorModalCall('{{ __("Event validation error ")}}');
                }
            },   //success
            complete: function( xhr ) {
                //$("#pageloader").fadeOut();
            },
            error: function (ts) { 
				$("#pageloader").fadeOut();
                ts.responseText+'-'+errorModalCall('{{ __("Event validation error ")}}');
            }
        }); //ajax-type            

    }
</script>
@endsection