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
				<a class="nav-link" href="{{ route('event_category.index') }}">{{ __('Event Category') }}</a>
				<a class="nav-link" href="{{ route('event_location.index') }}">{{ __('Locations') }}</a>
				<a class="nav-link active" href="{{ route('event_level.index') }}">{{ __('Level') }}</a>
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_level" role="tabpanel" aria-labelledby="tab_level">
				<form role="form" id="level_form" class="form-horizontal" method="post" action="#">
					<input type="hidden" name="school_id" value="3">
					@csrf
					<div class="section_header_class row">
						<div class="col-md-3 col-9">
							<label>{{ __('Level Name') }}</label>
						</div>
						<div class="col-md-2 col-2">
							<label></label>
						</div>
					</div>
					<div class="row">
						<div id="add_more_level_div" class="col-md-8">
						@php $count= isset($eventLastLevelId->id) ? ($eventLastLevelId->id) : 1; @endphp
						 @foreach($levels as $lvl)
								<div class="col-md-12 add_more_level_row row">
									<div class="col-md-5 col-9">
										<div class="form-group row">
											<div class="col-sm-11">
												<input type="hidden" name="level[{{$count}}][id]" value="<?= $lvl->id; ?>">
												<input class="form-control level_name" name="level[{{$count}}][name]" placeholder="{{ __('Level Name') }}" value="<?= $lvl->title; ?>" type="text">
											</div>
										</div>
									</div>
									<div class="col-md-2 offset-1 col-2">
										@can('parameters-delete')
										<div class="form-group row">
											<div class="col-sm-5">
												<button type="button" class="btn btn-theme-warn delete_level" data-level_id="{{ $lvl->id; }}"><i class="fa fa-trash" aria-hidden="true"></i></button>
											</div>
										</div>
										@endcan
									</div>
								</div>
							@php $count++; endforeach @endphp
						</div>
						<div class="col-md-2">
						@can('parameters-create-udpate')
							<button id="add_more_level_btn" type="button" data-last_id="{{$count}}"  class="btn btn-success save_button"><i class="fa fa-plus" aria-hidden="true"></i>{{ __('Add Another Level') }}</button>
						@endcan
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
	// level part
	$(document).on('click','#add_more_level_btn',function(){
		var lst_id = $(this).attr('data-last_id');
		var incre = (parseInt(lst_id)+1);
		$(this).attr('data-last_id',incre);

		var resultHtml = `<div class="col-md-12 add_more_level_row row">
			<div class="col-md-5 col-9">
				<div class="form-group row">
					<div class="col-sm-11">
						<input class="form-control level_name" name="level[`+lst_id+`][name]" placeholder="Level Name" type="text">
					</div>
				</div>
			</div>
			<div class="col-md-2 offset-1 col-2">
				<div class="form-group row">
					<div class="col-sm-5">
						<button type="button" class="btn btn-theme-warn delete_level"><i class="fa fa-trash" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>`;

		$("#add_more_level_div").append(resultHtml);
	})
	
	$(document).on('click','.delete_level',function(){
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
		var id = $(this).data('level_id');
		var current_obj = $(this);
		var lst_id = $(this).attr('data-r_id');
		var incre = parseInt(lst_id);
		$(this).attr('data-last_id',incre);

		if (!confirm('{{ __("Are you want to delete?") }}')) return

		if(id){
			$.ajax({
				url: BASE_URL + '/remove-event-level/'+id,
				type: 'DELETE',
				dataType: 'json',
				data: {
					"id": id,
					"_token": csrfToken,
				},
				success: function(response){	
					if(response.status == 1){
						current_obj.parents('.add_more_level_row').remove();
					}
				}
			})
		}else{
			current_obj.parents('.add_more_level_row').remove();
		}	
	});

	// save functionality
	$('#save_btn').click(function (e) {		
		var formData = $('#level_form').serializeArray();
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';

		$( ".level_name").each(function( key, value ) {
			var lvname = $(this).val();
			if(lvname=='' || lvname==null || lvname==undefined){
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
			url: BASE_URL + '/add-event-level',
				data: formData,
				type: 'POST',
				dataType: 'json',
				success: function(response){	
					if(response.status == 1){
						$('#modal_parameter').modal('show');
						$("#modal_alert_body").text('{{ __('Sauvegarde réussie') }}');
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