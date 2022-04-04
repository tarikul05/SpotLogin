@extends('layouts.main')

@section('head_links')
<link href="{{ asset('css/fullcalendar.min.css')}}" rel='stylesheet' />
<link href="{{ asset('css/fullcalendar.print.min.css')}}" rel='stylesheet' media='print' />
<script src="{{ asset('js/lib/moment.min.js')}}"></script>
<script src="{{ asset('js/fullcalendar.js')}}"></script>
@endsection

@section('content')
<div class="content email_template_page">
	<div class="container-fluid area-container">
		<form method="POST" action="{{route('add.email_template')}}" id="emailForm" name="emailForm" class="form-horizontal" role="form">
			<header class="panel-heading" style="border: none;">
				<div class="row panel-row" style="margin:0;">
					<div class="col-sm-6 col-xs-12 header-area">
							<div class="page_header_class">
									<label id="page_header" name="page_header">
										{{__('Agenda')}}: Apr 4 – 10, 2022
									</label>
							</div>
					</div>
					<div class="col-sm-6 col-xs-12 btn-area">
							<div class="pull-right btn-group">
									<a class="btn btn-sm btn-info text-white" href="../admin/" id="back_btn"> 
										<i class="fa fa-arrow-left"></i>
										{{ __('back')}}
									</a>
							</div>
					</div>    
				</div>                 
			</header>
		
			<div class="col-lg-12 col-md-12 col-sm-12">
				@csrf
				<div class="row">
					<input type="hidden" name="type" id="type" value="">
					<input type="hidden" name="last_template_code" id="last_template_code" value="{{old('template_code') ? old('template_code') : ''}}">
					<input type="hidden" name="html_subject_text" id="html_subject_text" value="{{old('subject_text') ? old('subject_text') : ''}}">
					<input type="hidden" name="html_body_text" id="html_body_text" value="{{old('body_text') ? old('body_text') : ''}}">
					
					
					<div class="col-md-10 offset-md-1 row">
						<h4 class="section_header_class">{{ __('Email Template information')}}</h4>
						
						<div class="row col-lg-5 col-md-5 col-sm-12">
							
						</div>
						
					</div>
				</div>
			</div>
		
		
		</form>
	</div>
</div>

	<!-- End Tabs content -->
@endsection


@section('footer_js')
<script>
	
	

	$(document).ready(function(){
		$('#back_btn').click(function (e) {							
	   	window.history.back();
		});
		
		
	}); //ready

	
</script>
@endsection