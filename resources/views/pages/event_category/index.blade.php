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

					@can('parameters-create-udpate')
						<button id="save_btn" name="save_btn" class="btn btn-success save_button"><em class="glyphicon glyphicon-floppy-save"></em> Save</button>
					@endcan
					</div>
				</div>    
			</div>          
		</header>
		<!-- Tabs navs -->

		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<a class="nav-link active" href="{{ auth()->user()->isSuperAdmin() ? route('admin_event_category.index',['school'=> $schoolId]) : route('event_category.index') }}">{{ __('Event Category') }}</a>
				<a class="nav-link" href="{{ auth()->user()->isSuperAdmin() ? route('admin_event_location.index',['school'=> $schoolId]) : route('event_location.index') }}">{{ __('Locations') }}</a>
				<a class="nav-link" href="{{ auth()->user()->isSuperAdmin() ? route('admin_event_level.index',['school'=> $schoolId]) : route('event_level.index') }}">{{ __('Level') }}</a>
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_category" role="tabpanel" aria-labelledby="tab_category">
				<form role="form" id="event_form" class="form-horizontal" method="post" action="{{route('event_category.create')}}">
					@csrf
					<div class="section_header_class row">
						<div class="col-md-3 col-5">
							<label>{{ __('Category Name') }}</label>
						</div>
						<div class="col-md-3 col-6">
							<label class="invoice_type_label">{{ __('Invoice Type') }}</label>
						</div>
						<div class="col-md-2 col-1">
							<label></label>
						</div>
					</div>
					<div class="row">
						<div id="add_more_event_category_div" class="col-md-8">
						@php $count= isset($eventLastCatId->id) ? ($eventLastCatId->id) : 1; @endphp
							@foreach($eventCat as $cat)
								<div class="col-md-12 add_more_event_category_row row">
									<div class="col-md-5 col-5">
										<div class="form-group row">
											<div class="col-sm-11">
												<input type="hidden" name="category[{{$count}}][id]" value="<?= $cat->id; ?>">
												<input class="form-control category_name" name="category[{{$count}}][name]" placeholder="{{ __('Category Name') }}" value="<?= $cat->title; ?>" type="text">
											</div>
										</div>
									</div>
									@if(!$AppUI->isTeacher())
									<div class="col-md-5 col-6">
										<div class="form-group row invoice_part">
											<div class="col-sm-6">
												<input type="radio" name="category[{{$count}}][invoice]" value="S" <?php if($cat->invoiced_type == 'S'){ echo 'checked'; }  ?>> <label> {{ __('School Invoiced') }}</label>
											</div>
											<div class="col-sm-6">
												<input type="radio" name="category[{{$count}}][invoice]" value="T" <?php if($cat->invoiced_type == 'T'){ echo 'checked'; }  ?>> <label> {{ __('Teacher Invoiced') }}</label>
											</div>
										</div>
									</div>
									@endif
									<div class="col-md-2 col-1">
										@can('parameters-delete')
										<div class="form-group row">
											<div class="col-sm-5">
												<button type="button" class="btn btn-theme-warn delete_event" data-category_id="<?= $cat->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
											</div>
										</div>
										@endcan
									</div>
								</div>
							@php $count++; endforeach @endphp
						</div>
						<div class="col-md-2">
						@can('parameters-create-udpate')
							<button id="add_more_event_category_btn" data-last_id="{{$count}}" type="button" class="btn btn-success save_button"><i class="fa fa-plus" aria-hidden="true"></i>Add Another Category</button>
						@endcan
						</div>
					</div>
				</form>	
			</div>
		</div>
		<!-- End Tabs content -->
	</div>
	

	<!-- success modal-->
	<div class="modal modal_parameter" id="modal_parameter">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<p id="modal_alert_body"></p>
				</div>
				<div class="modal-footer">
					<button type="button" id="modalClose" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Ok') }}</button>
				</div>
			</div>
		</div>
	</div>
@endsection


@section('footer_js')
<script type="text/javascript">
	$(document).on('click','#add_more_event_category_btn',function(){
		var lst_id = $(this).attr('data-last_id');
		var incre = (parseInt(lst_id)+1);
		$(this).attr('data-last_id',incre);

		var resultHtml = `<div class="col-md-12 add_more_event_category_row row">
			<div class="col-md-5 col-5">
				<div class="form-group row">
					<div class="col-sm-11">
						<input class="form-control category_name" name="category[`+lst_id+`][name]" placeholder="Category Name" type="text">
					</div>
				</div>
			</div>
			@if(!$AppUI->isTeacher())
			<div class="col-md-5 col-6">
				<div class="form-group row invoice_part">
					<div class="col-sm-6">
						<input name="category[`+lst_id+`][invoice]" type="radio" value="S" checked> <label> School Invoiced</label>
					</div>
					<div class="col-sm-6">
						<input name="category[`+lst_id+`][invoice]" type="radio" value="T"> <label> Teacher Invoiced </label>
					</div>
				</div>
			</div>
			@endif
			<div class="col-md-2 col-1">
				<div class="form-group row">
					<div class="col-sm-5">
						<button type="button" class="btn btn-theme-warn delete_event" data-r_id="`+lst_id+`"><i class="fa fa-trash" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>`;

		$("#add_more_event_category_div").append(resultHtml);
	})

	$(document).on('click','.delete_event',function(){
		var lst_id = $(this).attr('data-r_id');
		var incre = parseInt(lst_id);
		$(this).attr('data-last_id',incre);
		if (!confirm('{{ __("Are you want to delete?") }}')) return

		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
		var id = $(this).data('category_id');
		var current_obj = $(this);
		if(id){
			$.ajax({
				url: BASE_URL + '/remove-event-category/'+id,
				type: 'DELETE',
				dataType: 'json',
				data: {
					"id": id,
					"_token": csrfToken,
				},
				success: function(response){	
					if(response.status == 1){
						current_obj.parents('.add_more_event_category_row').remove();
					}
				}
			})
		}else{
			current_obj.parents('.add_more_event_category_row').remove();
		}
	});

	// save functionality
	$('#save_btn').click(function (e) {
		var formData = $('#event_form').serializeArray();
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
	
		var error = '';

		$( ".category_name" ).each(function( key, value ) {
			var lname = $(this).val();
			if(lname=='' || lname==null || lname==undefined){
				$(this).addClass('error');
				error = 1;
			}else{
				$(this).removeClass('error');
				error = 0;
			}
		});

		formData.push({
			"name": "_token",
			"value": csrfToken,
		});
		formData.push({
			"name": "school_id",
			"value": "{{$schoolId}}",
		});

		if(error < 1){	
			$.ajax({
				url: BASE_URL + '/add-event-category',
				data: formData,
				type: 'POST',
				dataType: 'json',
				success: function(response){	
					if(response.status == 1){
						$('#modal_parameter').modal('show');
						$("#modal_alert_body").text('{{ __('Successfully Registered') }}');
						window.location.reload();
					}
				}
			})
		}else{
			$('#modal_parameter').modal('show');
			$("#modal_alert_body").text('{{ __('Required field is empty') }}');
		}	            
	});



</script>
@endsection