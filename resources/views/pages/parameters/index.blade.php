@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<!-- color wheel -->
<script src="{{ asset('js/jquery.wheelcolorpicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/wheelcolorpicker.css')}}"/>
@endsection

@section('content')
  <div class="content">
	<div class="container-fluid">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area">
					<div class="page_header_class">
						<label id="page_header" name="page_header">Parameters</label>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 btn-area">
					<div class="float-end btn-group">
						<a style="display: none;" id="delete_btn" href="#" class="btn btn-theme-warn"><em class="glyphicon glyphicon-trash"></em> Delete</a>
						<button id="save_btn" name="save_btn" class="btn btn-success save_button"><em class="glyphicon glyphicon-floppy-save"></em> Save</button>
					</div>
				</div>    
			</div>          
		</header>
		<!-- Tabs navs -->

		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Event Category</button>
				<button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Locations</button>
				<button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Level</button>
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
				<div class="section_header_class row">
					<div class="col-md-3">
						<label>Category Name</label>
					</div>
					<div class="col-md-3">
						<label class="invoice_type_label">Invoice Type</label>
					</div>
					<div class="col-md-2">
						<label></label>
					</div>
				</div>
				<div class="row">
					<div id="add_more_event_category_div" class="col-md-8">
						<div class="col-md-12 add_more_event_category_row row">
							<div class="col-md-5">
								<div class="form-group row">
									<div class="col-sm-11">
										<input class="form-control" id="snickname" maxlength="50" name="snickname" placeholder="Pseudo" type="text" value="">
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<div class="col-sm-5">
										<input name="school_invoice" type="radio" value="School Invoiced" checked> <label> School Invoiced</label>
									</div>
									<div class="col-sm-6">
										<input name="teacher_invoice" type="radio" value="Teacher Invoiced"> <label> Teacher Invoiced </label>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group row">
									<div class="col-sm-5">
										<button type="button" class="btn btn-theme-warn delete_event" data-category_id="10"><i class="fa fa-trash" aria-hidden="true"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<button id="add_more_event_category_btn" type="button" class="btn btn-success save_button"><i class="fa fa-plus" aria-hidden="true"></i>Add Another Category</button>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
				<div class="section_header_class row">
					<div class="col-md-3">
						<label>Location Name</label>
					</div>
					<div class="col-md-2">
						<label></label>
					</div>
				</div>
				<div class="row">
					<div id="add_more_location_div" class="col-md-8">
						<div class="col-md-12 add_more_location_row row">
							<div class="col-md-5">
								<div class="form-group row">
									<div class="col-sm-11">
										<input class="form-control" id="snickname" maxlength="50" name="snickname" placeholder="Pseudo" type="text" value="">
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group row">
									<div class="col-sm-5">
										<button type="button" class="btn btn-theme-warn delete_location" data-category_id="10"><i class="fa fa-trash" aria-hidden="true"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<button id="add_more_location_btn" type="button" class="btn btn-success save_button"><i class="fa fa-plus" aria-hidden="true"></i>Add Another Location</button>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tab_3">
				<div class="section_header_class row">
					<div class="col-md-3">
						<label>Level Name</label>
					</div>
					<div class="col-md-2">
						<label></label>
					</div>
				</div>
				<div class="row">
					<div id="add_more_location_div" class="col-md-8">
						<div class="col-md-12 add_more_location_row row">
							<div class="col-md-5">
								<div class="form-group row">
									<div class="col-sm-11">
										<input class="form-control" id="snickname" maxlength="50" name="snickname" placeholder="Pseudo" type="text" value="">
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group row">
									<div class="col-sm-5">
										<button type="button" class="btn btn-theme-warn delete_location" data-category_id="10"><i class="fa fa-trash" aria-hidden="true"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<button id="add_more_location_btn" type="button" class="btn btn-success save_button"><i class="fa fa-plus" aria-hidden="true"></i>Add Another Level</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- End Tabs content -->
@endsection


@section('footer_js')
<script type="text/javascript">
	$(document).on('click','#add_more_event_category_btn',function(){
		var resultHtml = `<div class="col-md-12 add_more_event_category_row row">
			<div class="col-md-5">
				<div class="form-group row">
					<div class="col-sm-11">
						<input class="form-control" id="snickname" maxlength="50" name="snickname" placeholder="Pseudo" type="text" value="">
					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="form-group row">
					<div class="col-sm-5">
						<input name="school_invoice" type="radio" value="School Invoiced" checked> <label> School Invoiced</label>
					</div>
					<div class="col-sm-6">
						<input name="teacher_invoice" type="radio" value="Teacher Invoiced"> <label> Teacher Invoiced </label>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group row">
					<div class="col-sm-5">
						<button type="button" class="btn btn-theme-warn delete_event" data-category_id="10"><i class="fa fa-trash" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>`;

		$("#add_more_event_category_div").append(resultHtml);
	})

	$(document).on('click','.delete_event',function(){
		var category_id = $(this).data('category_id');
		var current_obj = $(this);
		current_obj.parents('.add_more_event_category_row').remove();

		// $.ajax({
		// 	type: "POST",
		// 	url: "../school/school_data.php", 
		// 	data: {"type":"delete_school_event_category","p_category_id":category_id,"p_school_id":p_school_id},
		// 	dataType: "json",
		// 	async: false,
		// 	success:function(result){
				
		// 		if(result.status){

		// 			current_obj.parents('.add_more_event_category_row').remove();
		// 			successModalCall(GetAppMessage("delete_confirm_message"));
					
				
		// 		} else {
		// 			successModalCall(GetAppMessage("delete_category_event_exist_msg"));
		// 		}
			
		// 	},   
		// 	error: function(ts) { 
		// 		errorModalCall(ts.responseText+ ' '+GetAppMessage('error_message_text')); 
		// 	}
	});

	// location part
	$(document).on('click','#add_more_location_btn',function(){
		var resultHtml = `<div class="col-md-12 add_more_location_row row">
			<div class="col-md-5">
				<div class="form-group row">
					<div class="col-sm-11">
						<input class="form-control" id="snickname" maxlength="50" name="snickname" placeholder="Pseudo" type="text" value="">
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group row">
					<div class="col-sm-5">
						<button type="button" class="btn btn-theme-warn delete_location" data-category_id="10"><i class="fa fa-trash" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>`;

		$("#add_more_location_div").append(resultHtml);
	})
	
	$(document).on('click','.delete_location',function(){
		var category_id = $(this).data('category_id');
		var current_obj = $(this);
		current_obj.parents('.add_more_location_row').remove();
	});
</script>
@endsection