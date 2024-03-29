@extends('layouts.main')

@section('head_links')

@endsection
<!-- Code within resources/views/blade.php -->
@php
	//$zone = $_COOKIE['timezone_user'];
	$zone = $timezone;
	$date_start = Helper::formatDateTimeZone($studentOffData->date_start, 'long','UTC',$zone);
	$date_end = Helper::formatDateTimeZone($studentOffData->date_end, 'long','UTC', $zone);
@endphp
@section('content')
  <div class="content">
	<div class="container-fluid">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area">
					<div class="page_header_class">
						<label id="page_header" class="page_header bold" name="page_header">{{ __('Student time off') }} : <i class="fa fa-plus-square" aria-hidden="true"></i></label>
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
							<label id="teacher_personal_data_caption">{{ __('Lesson information') }}</label>
						</div>
						<div class="alert alert-warning">
							<label>This course is blocked, but it can still be modified by first clicking the unlock button.</label>
							<button class="btn btn-sm btn-warning">Unlock</button>
						</div>
						<div class="row">
							<div class="col-md-7 offset-md-2">
								<div class="form-group row">
									<label class="col-lg-2 col-sm-2 text-left">{{__('Title') }} :</label>
									<div class="col-sm-7">
										{{ !empty($studentOffData->title) ? $studentOffData->title : ''; }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-2 col-sm-2 text-left">{{__('Student') }} :</label>
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
									<label class="col-lg-2 col-sm-2 text-left">{{__('Start date') }} :</label>
									<div class="col-sm-7">
										{{ !empty($date_start) ? date('l jS F-Y', strtotime($date_start)) : ''; }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-2 col-sm-2 text-left">{{__('End Date') }} :</label>
									<div class="col-sm-7">
										{{ !empty($date_end) ? date('l jS F-Y', strtotime($date_end)) : ''; }}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-2 col-sm-2 text-left">{{__('All day') }} :</label>
									<div class="col-sm-7">
										{{ !empty($studentOffData->fullday_flag) ? 'Yes' : 'Non(No)' }}
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
											{{ !empty($studentOffData->description) ? $studentOffData->description : ''; }}
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