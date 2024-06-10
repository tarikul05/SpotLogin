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


						<div class="card">
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
																<th width="15%" style="text-align:left">
																<span>{{ __('Student') }}</span>
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
																		{{ $student->price_amount_buy }} {{ $student->price_currency }}
																	</td>
																@endif
																@if(!$AppUI->isTeacherSchoolAdmin() && !$AppUI->isSchoolAdmin())@endif
																	<td style="text-align:center">								
																			{{ $student->sell_price }}	{{ $student->price_currency }}	 
																	</td>
																
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
				<div class="card-body p-3 pb-3 text-center">
					<a class="btn btn-sm btn-info w-100 text-white mb-2" href="<?= $BASE_URL;?>/agenda" id="back_btn">
						<i class="fa-solid fa-arrow-left"></i>
						{{ __('Back')}}
					</a>

					@if((($AppUI->person_id == $lessonData->teacher_id) || (($lessonData->eventcategory->invoiced_type == 'S') && ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin()))) && ($lessonData->is_locked ==1))

						<button class="btn btn-sm btn-warning w-100" onclick="confirm_event(true)"><i class="fa-solid fa-lock-open"></i> {{__('Unlock')}}</button>
						<input type="hidden" name="confirm_event_id" id="confirm_event_id" value="{{ !empty($eventId) ? $eventId : ''; }}">

					@endif

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
