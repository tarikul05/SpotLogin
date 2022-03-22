@extends('layouts.raw')

@section('head_links')

@endsection

@section('content')
  <div class="content">
	<div class="container-fluid">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area">
					<div class="page_header_class">
						<label id="page_header" name="page_header">{{ __('User Information:') }}</label>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 btn-area">
					<div class="float-end btn-group">
						<button id="save_btn" name="save_btn" class="btn btn-theme-success"><i class="fa fa-save"></i>{{ __('Save') }} </button>
					</div>
				</div>    
			</div>          
		</header>
		<!-- Tabs navs -->

		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Contact Information') }}</button>
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
				<form action="" class="form-horizontal" id="add_user" method="post" role="form"
					 action=""  name="add_user" role="form">
					@csrf

					<input type="hidden" name="person_id" value="{{ !empty($user_data) ? $user_data->id : '' }}">
          <input type="hidden" name="person_type" value="{{ !empty($verifyToken) ? $verifyToken->person_type : '' }}">
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Personal information') }}</label>
						</div>
						<div class="row">
							<div class="col-md-6">
                <div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="nickname" id="nickname_label_id">{{__('Username') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control require" id="username" maxlength="50" name="username" placeholder="username" type="text" value="">
									</div>
								</div>
                <div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="password" id="nickname_label_id">{{__('Password') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control require" id="password" maxlength="50" name="password" placeholder="password" type="text" value="">
									</div>
								</div>
							@if(!empty($user_data))
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="gender_id" id="gender_label_id">{{__('Gender') }} : *</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control require" id="gender_id" name="gender_id">
												@foreach($genders as $key => $gender)
								                    <option value="{{ $key }}">{{ $gender }}</option>
								                @endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="lastname" id="family_name_label_id">{{__('Family Name') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control require" id="lastname" name="lastname" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="firstname" id="first_name_label_id">{{__('First Name') }} : <span class="required_sign">*</span></label>
									<div class="col-sm-7">
										<input class="form-control require" id="firstname" name="firstname" type="text">
									</div>
								</div>
							@endif
							</div>
							<div class="col-md-6">

							
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="email" id="email_caption">{{__('Email') }} :</label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span> <input class="form-control" id="email" value="{{ !empty($user_data) ? $user_data->email : '' }}" name="email" type="text">
										</div>
									</div>
								</div>

							
							</div>
							<div class="clearfix"></div>
					
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
	<!-- success modal-->
	<div class="modal modal_parameter" id="modal_add_teacher">
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
	<!-- End Tabs content -->
@endsection


@section('footer_js')
<script type="text/javascript">
$(function() {
	
});


// save functionality
$('#save_btn').click(function (e) {
		var formData = $('#add_teacher').serializeArray();
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
		var error = '';
		$( ".form-control.require" ).each(function( key, value ) {
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
				url: BASE_URL + '//add-teacher-action',
				data: formData,
				type: 'POST',
				dataType: 'json',
				success: function(response){	
					if(response.status == 1){
						$('#modal_add_teacher').modal('show');
						$("#modal_alert_body").text(response.message);
					}
				}
			})
		}else{
			$('#modal_add_teacher').modal('show');
			$("#modal_alert_body").text('{{ __('Required field is empty') }}');
		}	            
});  
</script>
@endsection