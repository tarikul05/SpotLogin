@extends('layouts.main')

@section('head_links')

@endsection
<!-- Code within resources/views/blade.php -->
@php
	//$zone = $_COOKIE['timezone_user'];
	$zone = $timezone;
	$initDate = new Helper();
	$date_start = $initDate->formatDateTimeZone($lessonData->date_start, 'long','UTC',$zone);
	$date_end = $initDate->formatDateTimeZone($lessonData->date_end, 'long','UTC', $zone);
	$current_time = $initDate->formatDateTimeZone(now(), 'long','UTC', $zone);
	$showPrice = ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()) && ($lessonData->eventcategory->invoiced_type == 'S') || ($AppUI->isTeacher() && ($lessonData->eventcategory->invoiced_type == 'T'))
@endphp
@section('content')
  <div class="content">
	<div class="container">

		<div class="row justify-content-center pt-3 pb-4">
			<div class="col-md-10">

				<div class="page_header_class pt-1" style="position: static;">
					<h5 class="titlePage">{{ __('Lesson') }}</h5>
				</div>


	<div class="row">
		<div class="col-lg-12">

		<input type="hidden" name="confirm_event_id" id="confirm_event_id" value="{{ !empty($lessonlId) ? $lessonlId : ''; }}">

		<!-- Tabs content -->
		<div class="tab-content view_part" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
					<fieldset>
					
						@if((($AppUI->person_id == $lessonData->teacher_id) || (($lessonData->eventcategory->invoiced_type == 'S') && ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()))) && ($lessonData->is_locked ==1))
							<div class="alert alert-warning">
								<label>{{ __('This lesson is blocked, but it can still be modified by first clicking the unlock button') }}.</label>
								<!--<button class="btn btn-sm btn-warning" onclick="confirm_event(true)"><i class="fa-solid fa-lock-open"></i> Unlock</button>-->
							</div>
						@endif

						@php
							$invoiceId = DB::table('invoice_items')->where('event_id', $lessonlId)->where('deleted_at', null)->value('invoice_id');
                            //$invoiceExists = DB::table('invoices')->where('id', $invoiceId)->whereNull('invoices.deleted_at')->exists();
						@endphp

						@php
						$invoiceIds = DB::table('invoice_items')
										->where('event_id', $lessonlId)
										->whereNull('deleted_at')
										->pluck('invoice_id');

						$invoices = DB::table('invoices')
									->whereIn('id', $invoiceIds)
									->whereNull('invoices.deleted_at')
									->get();
						@endphp

						@if ($invoices->isNotEmpty())
						<div class="alert alert-warning">
							<label>{{ __('This lesson has invoices attached to it') }}:</label>
							<ul>
								@foreach ($invoices as $invoice)
									@php
										$studentName = DB::table('students')
														->where('id', DB::table('invoice_items')
																		->where('invoice_id', $invoice->id)
																		->value('student_id'))
														->value(DB::raw("CONCAT_WS(' ', firstname, lastname) AS student_name"));
									@endphp
									<li>{{ $studentName }} {{ __('is billed for this event') }}. <a href="{{ route('adminmodificationInvoice', [$schoolId, $invoice->id]) }}">See invoice #{{ $invoice->id }}</a></li>
								@endforeach
							</ul>
						</div>
						@endif


						<div class="card2">
							<div class="card-header titleCardPage">{{ __('Lesson detail') }}</div>
							<div class="card-body bg-tertiary">

						<div class="row">
							<div class="col-md-12">
							<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Category') }} :</label>
									<div class="col-sm-7">
										{{ !empty($lessonCategory->title) ? $lessonCategory->title : ''; }}
									</div>
								</div>
								@if(!empty($locations->location_id))
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Location') }} :</label>
									<div class="col-sm-7">
										{{ !empty($locations->location_id) ? $locations->location_id : ''; }}
									</div>
								</div>
								@endif
		
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
											//echo $student->nickname;
											$studentName = App\Models\Student::find($student->student_id);
                                            if(!empty($studentName)) {
                                                echo $studentName->firstname . ' ' . $studentName->lastname;
                                                if($i != $count){
                                                    echo ', ';
                                                }
                                            } else {
                                                echo 'Student not found (deleted)';
                                                if($i != $count){
                                                    echo ', ';
                                                }
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
								@if(!empty($lessonData->billing_method))
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Type of billing') }} :</label>
									<div class="col-sm-7">
										{{ !empty($lessonData->billing_method) ? $lessonData->billing_method : '' }}
									</div>
								</div>
								@endif
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Number of students') }} :</label>
									<div class="col-sm-7">
										{{ $lessonData->no_of_students }}
									</div>
								</div>
							</div>
						</div>
							</div>
						</div>
						
					
							
								
							
							<div class="card2">
								<div class="card-header titleCardPage">{{ __('Students') }}</div>
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
																<th width="15%" style="text-align:left">
																<!--<span>{{ __('Student') }}</span>-->
																</th>
																<th width="15%" style="text-align:left"></th>
																@if($showPrice)
																@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
																	<th width="10%" style="text-align:left;">
																	<label id="row_hdr_buy" name="row_hdr_buy">{{ __('Teacher') }}</label>
																	<label>{{ !empty($eventData->price_currency) ? '(' + $eventData->price_currency + ')' : '' }}</label>
																	</th>
																	@endif
																	@if(!$AppUI->isTeacherSchoolAdmin() && !$AppUI->isSchoolAdmin())@endif
																	<th width="10%" style="text-align:center">
																	<label id="row_hdr_sale" name="row_hdr_sale">{{ __('Student') }}</label>
																	<label>{{ !empty($eventData->price_currency) ? '(' + $eventData->price_currency + ')' : '' }}</label>
																	</th>
																	
																@endif
															</tr>
															@foreach($studentOffList as $student)
															<tr>
																<td>
																<img src="{{ asset('img/photo_blank.jpg') }}" width="18" height="18" class="img-circle account-img-small">
																@php
																$studentName = App\Models\Student::find($student->student_id);
																@endphp
                                                                @if(!empty($studentName))
																{{$studentName->firstname}} {{$studentName->lastname}}
                                                                @else
                                                                Student not found (deleted)
                                                                @endif
																</td>
																<td>
																	<?php if(!empty($student->participation_id)){ if($student->participation_id == 0 ){ echo 'scheduled'; }elseif($student->participation_id == 199 ){ echo 'Absent'; }elseif($student->participation_id == 200 ){ echo 'Present'; } }  ?>
																</td>
																@if($showPrice)
																@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
													
																	<td>	
																		{{ number_format($student->price_amount_sell, 2) }} {{ $student->price_currency }}
																	</td>
																@endif
																@if(!$AppUI->isTeacherSchoolAdmin() && !$AppUI->isSchoolAdmin())
																	<td style="text-align:center">								
																			{{ number_format($student->price_amount_sell, 2) }} {{ $student->price_currency }}	 
																	</td>
																@else
																	<td style="text-align:center">								
																		{{ number_format($student->price_amount_buy, 2) }} {{ $student->price_currency }}	 
																	</td>
																@endif
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

		
							<div class="card2">
								<div class="card-header titleCardPage">{{ __('Optional information') }}</div>
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
					
					<a class="btn btn-default mobile-editLesson-btn" href="<?= $BASE_URL;?>/agenda" id="back_btn">
						<i class="fa-solid fa-arrow-left"></i>
						{{ __('Back')}}
					</a>

			</div>
		</div>

	</div>

	</div>

</div>
</div>
</div>


	<div class="row justify-content-center" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important; width:100%;">
		<div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.91!important; background-color:#DDDD!important;">

			@if((($AppUI->person_id == $lessonData->teacher_id) || (($lessonData->eventcategory->invoiced_type == 'S') && ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()))) && ($lessonData->is_locked ==1))

				<a class="btn btn-sm btn-warning" onclick="confirm_event(true)"><i class="fa-solid fa-lock-open"></i> {{__('Unlock')}}</a>
				<input type="hidden" name="confirm_event_id" id="confirm_event_id" value="{{ !empty($eventId) ? $eventId : ''; }}">

			@endif

		</div>
	</div>



@endsection


@section('footer_js')
<script type="text/javascript">
	function confirm_event(unlock=false){
		var invoiceId = {{ json_encode($invoiceId) }};
        var p_event_auto_id=document.getElementById('confirm_event_id').value;
        console.log(p_event_auto_id);
        var data = 'p_event_auto_id=' + p_event_auto_id;
        if (unlock) {
            var data = 'unlock=1&p_event_auto_id=' + p_event_auto_id;
        }

		if (unlock && invoiceId) {
        	return Swal.fire({
			title: 'Are you sure?',
			text: "Be carrefull, this lessons already as an invoice attached to it. If you unlock it, the lesson will show in the lessons to be invoiced again and might be invoiced twice, please check",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, unlock it!'
			}).then((result) => {
			if (result.isConfirmed) {
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
							$("#pageloader").fadeOut();
							let timerInterval
								Swal.fire({
								title: 'Invoice has been unlocked!',
								timer: 2000,
								timerProgressBar: true,
								didOpen: () => {
									Swal.showLoading()
									const b = Swal.getHtmlContainer().querySelector('b')
									timerInterval = setInterval(() => {
									b.textContent = Swal.getTimerLeft()
									}, 100)
								},
								willClose: () => {
									clearInterval(timerInterval)
								}
								}).then((result) => {
								/* Read more about handling dismissals below */
								if (result.dismiss === Swal.DismissReason.timer) {
									window.location.href = '/{{$schoolId}}/edit-lesson/{{$lessonlId}}'
								}
								})

						}
						else {
							errorModalCall('{{ __("Event validation error ")}}');
						}
					},
					complete: function( xhr ) {
					},
					error: function (ts) {
						$("#pageloader").fadeOut();
						ts.responseText+'-'+errorModalCall('{{ __("Event validation error ")}}');
					}
				});
			}
			})
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
					window.location.href = '/{{$schoolId}}/edit-lesson/{{$lessonlId}}'
                }
                else {
                    errorModalCall('{{ __("Event validation error ")}}');
                }
            },
            complete: function( xhr ) {
            },
            error: function (ts) {
				$("#pageloader").fadeOut();
                ts.responseText+'-'+errorModalCall('{{ __("Event validation error ")}}');
            }
        });

    }
</script>
@endsection
