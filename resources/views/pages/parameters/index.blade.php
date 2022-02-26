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
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_category" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Event Category</button>
				<button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#tab_location" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Locations</button>
				<button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#tab_level" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Level</button>
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_category" role="tabpanel" aria-labelledby="tab_category">
				<div class="section_header_class row">
					<div class="col-md-3 col-5">
						<label>Category Name</label>
					</div>
					<div class="col-md-3 col-6">
						<label class="invoice_type_label">Invoice Type</label>
					</div>
					<div class="col-md-2 col-1">
						<label></label>
					</div>
				</div>
				<div class="row">
					<div id="add_more_event_category_div" class="col-md-8">
						<div class="col-md-12 add_more_event_category_row row">
							<div class="col-md-5 col-5">
								<div class="form-group row">
									<div class="col-sm-11">
										<input class="form-control" id="snickname" maxlength="50" name="snickname" placeholder="Pseudo" type="text" value="">
									</div>
								</div>
							</div>
							<div class="col-md-5 col-6">
								<div class="form-group row invoice_part">
									<div class="col-sm-6">
										<input name="school_invoice" type="radio" value="School Invoiced" checked> <label> School Invoiced</label>
									</div>
									<div class="col-sm-6">
										<input name="teacher_invoice" type="radio" value="Teacher Invoiced"> <label> Teacher Invoiced </label>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-1">
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
			<div class="tab-pane fade" id="tab_location" role="tabpanel" aria-labelledby="tab_location">
				<form role="form" id="location_form" class="form-horizontal" method="post" action="{{route('add_event_location.create')}}">
					@csrf
					<div class="section_header_class row">
						<div class="col-md-3 col-9">
							<label>Location Name</label>
						</div>
						<div class="col-md-2 col-2">
							<label></label>
						</div>
					</div>
					<div class="row">
						<div id="add_more_location_div" class="col-md-8">
							<div class="col-md-12 add_more_location_row row">
								<div class="col-md-5 col-9">
									<div class="form-group row">
										<div class="col-sm-11">
											<input class="form-control" id="location_name" maxlength="50" name="title[]" placeholder="Location Name" type="text">
										</div>
									</div>
								</div>
								<div class="offset-1 col-2">
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
				</form>	
			</div>
			<div class="tab-pane fade" id="tab_level" role="tabpanel" aria-labelledby="tab_level">
				<form role="form" id="level_form" class="form-horizontal" method="post" action="#">
				@csrf
					<div class="section_header_class row">
						<div class="col-md-3 col-9">
							<label>Level Name</label>
						</div>
						<div class="col-md-2 col-2">
							<label></label>
						</div>
					</div>
					<div class="row">
						<div id="add_more_level_div" class="col-md-8">
							<div class="col-md-12 add_more_level_row row">
								<div class="col-md-5 col-9">
									<div class="form-group row">
										<div class="col-sm-11">
											<input class="form-control" id="level_name[]" maxlength="50" name="level_name[]" placeholder="Level Name" type="text">
										</div>
									</div>
								</div>
								<div class="col-md-2 offset-1 col-2">
									<div class="form-group row">
										<div class="col-sm-5">
											<button type="button" class="btn btn-theme-warn delete_level" data-category_id="10"><i class="fa fa-trash" aria-hidden="true"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<button id="add_more_level_btn" type="button" class="btn btn-success save_button"><i class="fa fa-plus" aria-hidden="true"></i>Add Another Level</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- End Tabs content -->
@endsection


@section('footer_js')
<script type="text/javascript">
	$(document).on('click','#add_more_event_category_btn',function(){
		var resultHtml = `<div class="col-md-12 add_more_event_category_row row">
			<div class="col-md-5 col-5">
				<div class="form-group row">
					<div class="col-sm-11">
						<input class="form-control" id="snickname" maxlength="50" name="snickname" placeholder="Pseudo" type="text" value="">
					</div>
				</div>
			</div>
			<div class="col-md-5 col-6">
				<div class="form-group row invoice_part">
					<div class="col-sm-6">
						<input name="school_invoice" type="radio" value="School Invoiced" checked> <label> School Invoiced</label>
					</div>
					<div class="col-sm-6">
						<input name="teacher_invoice" type="radio" value="Teacher Invoiced"> <label> Teacher Invoiced </label>
					</div>
				</div>
			</div>
			<div class="col-md-2 col-1">
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
			<div class="col-md-5 col-9">
				<div class="form-group row">
					<div class="col-sm-11">
						<input class="form-control" id="location name" maxlength="50" name="title[]" placeholder="location name" type="text">
					</div>
				</div>
			</div>
			<div class="col-md-2 offset-1 col-2">
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

	// level part
	$(document).on('click','#add_more_level_btn',function(){
	var resultHtml = `<div class="col-md-12 add_more_level_row row">
			<div class="col-md-5 col-9">
				<div class="form-group row">
					<div class="col-sm-11">
						<input class="form-control" id="snickname" maxlength="50" name="snickname" placeholder="Pseudo" type="text" value="">
					</div>
				</div>
			</div>
			<div class="col-md-2 offset-1 col-2">
				<div class="form-group row">
					<div class="col-sm-5">
						<button type="button" class="btn btn-theme-warn delete_level" data-category_id="10"><i class="fa fa-trash" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>`;

		$("#add_more_level_div").append(resultHtml);
	})
	
	$(document).on('click','.delete_level',function(){
		var category_id = $(this).data('category_id');
		var current_obj = $(this);
		current_obj.parents('.add_more_level_row').remove();
	});

	// save functionality
	$('#save_btn').click(function (e) {
		var x=document.getElementsByClassName("tab-pane active");
		if (x[0].id == "tab_category"){
			save_event_category();
		}else if (x[0].id == "tab_location"){
			save_event_location();
		}else if (x[0].id == "tab_level"){
			save_event_level();
		}
	}); 

	function save_event_category(){
			var list = document.getElementsByClassName("category_name_class");
			for (var i = 0; i < list.length; i++) {
				if (list[i].value == '') {
					try {
						errorModalCall(GetAppMessage('invalid_category_name_msg'));
						return false;
					} catch (err) {
						//null
					}
				}
			}
		
		var p_person_id="";
		//var p_school_id=document.getElementById("school_id").value;

		var formData = new FormData($('#event_cat_form')[0]);
		formData.append("p_school_id", school_id);
		formData.append("p_person_id", "");
		formData.append("type", "save_event_category");

		$.ajax({
				url: '../school/school_data.php',
				data: formData,
				type: 'POST',
				dataType: 'json',
				async: false,
				processData: false,
				contentType: false,
				success: function(response){	
				if(response.status == 'success')
					PopulateEventLocation();
					successModalCall(GetAppMessage('save_confirm_message'));
					
				},
				error: function(e){ errorModalCall(GetAppMessage('error_message_text'));
					//alert('Error processing your request: '+e.responseText+' update_student_disc_perc');
				}
			})            
	}

	function save_event_location(){	
		
		var formData = $('#location_form').serializeArray();
		var school_id = 1;
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
      console.log(formData);

		formData.push({
			"name": "_token",
			"value": csrfToken
		});
		
		$.ajax({
				url: BASE_URL + '/add-event-location',
				data: formData,
				type: 'POST',
				dataType: 'json',
				success: function(response){	
					if(response.status == 'success'){
					
					}
				}
			})
	}

	function save_event_level(){
		var formData = new FormData($('#level_form')[0]);
		formData.append("p_school_id", school_id);
		formData.append("type", "save_event_level");

		$.ajax({
				url: 'handler/param_data.php',
				data: formData,
				type: 'POST',
				dataType: 'json',
				async: false,
				processData: false,
				contentType: false,
				success: function(response){	
					if(response.status == 'success'){

					}
				}
			})
	}
</script>
@endsection