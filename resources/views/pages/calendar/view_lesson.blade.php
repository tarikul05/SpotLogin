@extends('layouts.main')

@section('head_links')

@endsection

@section('content')
  <div class="content">
	<div class="container-fluid">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area">
					<div class="page_header_class">
						<label id="page_header" class="page_header bold" name="page_header">{{ __('lesson') }} : <i class="fa fa-plus-square" aria-hidden="true"></i></label>
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
							<label id="teacher_personal_data_caption">{{ __('Lesson information') }}</label>
						</div>
						<div class="alert alert-warning">
							<label>This course is blocked, but it can still be modified by first clicking the unlock button.</label>
							<button class="btn btn-sm btn-warning">Unlock</button>
						</div>
						<div class="row">
							<div class="col-md-7 offset-md-2">
							<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Type') }} :</label>
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
										{{ !empty($eventCategory->title) ? $eventCategory->title : ''; }}
									</div>
								</div>
	
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Professor') }} :</label>
									<div class="col-sm-7">
										{{!empty($professors->nickname) ? $professors->nickname : ''; }}
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
										{{ !empty($lessonData->date_start) ? date('l jS F-Y', strtotime($lessonData->date_start)) : ''; }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('End Date') }} :</label>
									<div class="col-sm-7">
										{{ !empty($lessonData->date_end) ? date('l jS F-Y', strtotime($lessonData->date_end)) : ''; }}
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
										{{ !empty($lessonData->fullday_flag) ? 'Yes' : 'Non(No)' }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Type of billing') }} :</label>
									<div class="col-sm-7">
										{{ !empty($lessonData->billing_method) ? $lessonData->billing_method : '' }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left">{{__('Number of students)') }} :</label>
									<div class="col-sm-7">
										{{ !empty($lessonData->price_amount_buy) ? 'Group lessons for '.$lessonData->no_of_students.' students' : '' }}
									</div>
								</div>
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
																<th width="10%" style="text-align:left;">
																<label id="row_hdr_buy" name="row_hdr_buy">{{ __('Buy') }}</label>
																<label>({{ !empty($eventData->price_currency) ? $eventData->price_currency : '' }})</label>
																</th>
																<th width="10%" style="text-align:center">
																<label id="row_hdr_sale" name="row_hdr_sale">{{ __('Sell') }}</label>
																<label>({{ !empty($eventData->price_currency) ? $eventData->price_currency : '' }})</label>
																</th>
																<th width="10%" style="text-align:right">
																<label>{{ __('Extra charges') }}</label>
																</th>
															</tr>
															@foreach($studentOffList as $student)
															<tr>
																<td>{{ $student->id }}</td>
																<td>
																<img src="{{ asset('img/photo_blank.jpg') }}" width="18" height="18" class="img-circle account-img-small"> {{ $student->nickname }}
																</td>
																<td><?php if(!empty($student->participation_id)){ if($student->participation_id == 0 ){ echo 'scheduled'; }elseif($student->participation_id == 199 ){ echo 'Absent'; }elseif($student->participation_id == 200 ){ echo 'Present'; } }  ?></td>
																<td>{{ $student->buy_price }}</td>
																<td style="text-align:center">{{ $student->sell_price }}</td>
																<td style="text-align:center">{{ $lessonData->extra_charges }}</td>
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
											{{ !empty($lessonData->description) ? $lessonData->description : ''; }}
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

</script>
@endsection