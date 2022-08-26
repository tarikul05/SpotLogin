@extends('layouts.raw')

@section('head_links')

@endsection

@section('content')
  <div class="content">
	<div class="container-fluid">
		
		<!-- Tabs navs -->

		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Confirmation login') }}</button>
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
				<form action="{{route('user.active_school')}}" class="form-horizontal" id="add_user" name="add_user" method="post" role="form">
					@csrf
          <input type="hidden" name="user_id" value="{{ !empty($user_data->user) ? $user_data->user->id : '' }}">
          
					<input type="hidden" name="person_id" value="{{ !empty($user_data) ? $user_data->id : '' }}">
          <input type="hidden" name="person_type" value="{{ !empty($verifyToken) ? $verifyToken->person_type : '' }}">
					<input type="hidden" name="school_id" value="{{ !empty($verifyToken) ? $verifyToken->school_id : '' }}">
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">{{ __('Confirm via login') }}</label>
							<p>You are requested to join this school ({{ $school->school_name }})</p>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
						            <input type="text" class="form-control" placeholder="Username" id="login_username" name="login_username" required>
						          </div>
						          <div class="form-group">
						            <div class="input-group" id="show_hide_password">
						              <input class="form-control" autocomplete="on" type="password" placeholder="Password" id="login_password" name="login_password" required>
						              <!-- <div class="input-group-addon">
						                <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
						              </div> -->
						            </div>
						          </div>



				                <div class="form-group">
													
				                    <div class="float-end btn-group">
				                      <button id="save_btn" name="save_btn" class="btn btn-theme-success">
				                        <i class="fa fa-save"></i>{{ __('Login') }} 
				                      </button>
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

<script type="text/javascript">
$(document).ready(function(){
	
	// save functionality
	$('#save_btn').click(function (e) {
		var userForm = document.getElementById("add_user");

		if (validateUserForm()) {	
			userForm.submit();
			return false;
		} else {
			e.preventDefault(e); 
		}
	});
});
	$("body").on('click', '#show_hide_password a', function(event) {
    event.preventDefault();
    if ($('#show_hide_password input').attr("type") == "text") {
      $('#show_hide_password input').attr('type', 'password');
      $('#show_hide_password i').addClass("fa-eye-slash");
      $('#show_hide_password i').removeClass("fa-eye");
    } else if ($('#show_hide_password input').attr("type") == "password") {
      $('#show_hide_password input').attr('type', 'text');
      $('#show_hide_password i').removeClass("fa-eye-slash");
      $('#show_hide_password i').addClass("fa-eye");
    }
  });

function validateUserForm() {
  let error = false;
	$("#add_user .form-control.require" ).each(function( key, value ) {
		var lname = $(this).val();
		
		if(lname=='' || lname==null || lname==undefined){
			console.log(lname);
			$(this).addClass('error');
			error = true;
		}else{
			$(this).removeClass('error');
		}
	});
	if (error) {
		return false
	} else {
		return true;
	}
	
}

 
</script>
@endsection