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
				<a class="nav-link" href="<?= $BASE_URL;?>/event-category">{{ __('Event Category') }}</a>
				<a class="nav-link active" href="<?= $BASE_URL;?>/event-location">{{ __('Locations') }}</a>
				<a class="nav-link" href="<?= $BASE_URL;?>/event-level">{{ __('Level') }}</a>
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_location" role="tabpanel" aria-labelledby="tab_location">
				<form role="form" id="location_form" class="form-horizontal" method="post" action="{{route('event_location.create')}}">
					<input type="hidden" name="school_id" value="3">
					@csrf
					<div class="section_header_class row">
						<div class="col-md-3 col-9">
							<label>{{ __('Location Name') }}</label>
						</div>
						<div class="col-md-2 col-2">
							<label></label>
						</div>
					</div>
					<div class="row">
						<div id="add_more_location_div" class="col-md-8">
							@php $count= isset($eventLastLocaId->id) ? ($eventLastLocaId->id) : 1; @endphp
							@foreach($locations as $loca)
								<div class="col-md-12 add_more_location_row row">
									<div class="col-md-5 col-9">
										<div class="form-group row">
											<div class="col-sm-11">
												<input type="hidden" name="location[{{$count}}][id]" value="<?= $loca->id; ?>">
												<input class="form-control location_name" name="location[{{$count}}][name]" placeholder="{{ __('Location Name') }}" value="<?= $loca->title; ?>" type="text">
											</div>
										</div>
									</div>
									<div class="offset-1 col-2">
										<div class="form-group row">
											<div class="col-sm-5">
												<button type="button" class="btn btn-theme-warn delete_location" data-location_id="<?= $loca->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
											</div>
										</div>
									</div>
								</div>
							@php $count++; endforeach @endphp
						</div>
						<div class="col-md-2">
							<button id="add_more_location_btn" data-last_id="{{$count}}" type="button" class="btn btn-success save_button"><i class="fa fa-plus" aria-hidden="true"></i>{{ __('Add Another Location') }}</button>
						</div>
					</div>
				</form>	
			</div>
		</div>
	</div>
	<!-- End Tabs content -->

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
	// location part
	$(document).on('click','#add_more_location_btn',function(){
		var lst_id = $(this).attr('data-last_id');
		var incre = (parseInt(lst_id)+1);
		$(this).attr('data-last_id',incre);

		var resultHtml = `<div class="col-md-12 add_more_location_row row">
			<div class="col-md-5 col-9">
				<div class="form-group row">
					<div class="col-sm-11">
						<input class="form-control location_name" name="location[`+lst_id+`][name]" placeholder="location name" type="text">
					</div>
				</div>
			</div>
			<div class="col-md-2 offset-1 col-2">
				<div class="form-group row">
					<div class="col-sm-5">
						<button type="button" class="btn btn-theme-warn delete_location"><i class="fa fa-trash" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>`;

		$("#add_more_location_div").append(resultHtml);
	})
	
	$(document).on('click','.delete_location',function(){
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
		var id = $(this).data('location_id');
		var current_obj = $(this);
		var lst_id = $(this).attr('data-r_id');
		var incre = parseInt(lst_id);
		$(this).attr('data-last_id',incre);

		if(id){
			$.ajax({
				url: BASE_URL + '/remove-event-location/'+id,
				type: 'DELETE',
				dataType: 'json',
				data: {
					"id": id,
					"_token": csrfToken,
				},
				success: function(response){	
					if(response.status == 1){
						current_obj.parents('.add_more_location_row').remove();
					}
				}
			})
		}else{
			current_obj.parents('.add_more_location_row').remove();
		}
		
	});

	// save functionality
	$('#save_btn').click(function (e) {		
		var formData = $('#location_form').serializeArray();
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
		
		var error = '';

		$( ".location_name" ).each(function( key, value ) {
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
		
		if(error < 1){
			$.ajax({
				url: BASE_URL + '/add-event-location',
				data: formData,
				type: 'POST',
				dataType: 'json',
				success: function(response){	
					if(response.status == 1){
						$('#modal_parameter').modal('show');
						$("#modal_alert_body").text('{{ __('Sauvegarde réussie') }}');
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