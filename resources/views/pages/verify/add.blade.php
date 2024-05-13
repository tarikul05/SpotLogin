@extends('layouts.verify')

@section('head_links')

@endsection

@section('content')
  <div class="content">
	<div class="container">

        <div class="row justify-content-center pt-1">
            <div class="col-md-10">

            <div class="text-center">
                <img style="width: 100%; max-width: 100px" src="{{ asset('img/logo-blue.png') }}" class="spotlogin_logo" id="spotlogin_logo" alt="Sportlogin">
            </div>

		<header class="panel-heading2" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area">
					<div class="page_header_class2">
						<label id="page_header" name="page_header">{{ __('Welcome') }} {{ !empty($user_data) ? $user_data->firstname : '' }},</label>
						<span>{{ __('You are invited by') }} <b>{{ $school->school_name }}</b> {{ __('to create your personnal account') }}</span>
					</div>
				</div>
			</div>
		</header>

		<div class="tab-content pt-2" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
				<form action="{{route('user.add')}}" class="form-horizontal" id="add_user" name="add_user" method="post" role="form">
					@csrf

					<input type="hidden" name="person_id" value="{{ !empty($user_data) ? $user_data->id : '' }}">
                    <input type="hidden" name="person_type" value="{{ !empty($verifyToken) ? $verifyToken->person_type : '' }}">
					<input type="hidden" name="school_id" value="{{ !empty($verifyToken) ? $verifyToken->school_id : '' }}">
					<fieldset>

						<div class="card">
							<div class="card-body bg-tertiary">
						<div class="row">
							<div class="col-md-6">
                			<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="nickname" id="nickname_label_id">{{__('Login ID') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control require" id="add_username" maxlength="50" name="username" placeholder="username" value="{{ !empty($user_data) ? $user_data->lastname : '' }}" type="text" autocomplete="off">
									</div>
								</div>
                				<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="password" id="password_label_id">{{__('Password') }} : *</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input class="form-control require" id="add_password" name="password" placeholder="password" type="password" autocomplete="off">
											<span class="input-group-addon" id="showpass" style="cursor: pointer;">
												<i class="fa fa-eye"></i>
											</span>
										</div>

									</div>
								</div>
                                @if($verifyToken->person_type !== 'App\Models\Parents')
								<!--<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="gender_id" id="gender_label_id">{{__('Gender') }} : *</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control require" id="gender_id" name="gender_id">
												<option value="">Select</option>
												@foreach($genders as $key => $gender)
													<option value="{{ $key }}"
													{{!empty($user_data->gender_id) ? (old('gender_id', $user_data->gender_id) == $key ? 'selected' : '') : (old('gender_id') == $key ? 'selected' : '')}}
													>{{ $gender }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>-->
								<input type="hidden" name="gender_id" value="1">

								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="lastname" id="family_name_label_id">{{__('Family Name') }} : *</label>
									<div class="col-sm-7">
										<input class="form-control require" id="lastname" name="lastname" type="text" value="{{ !empty($user_data) ? $user_data->lastname : '' }}">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="firstname" id="first_name_label_id">{{__('First Name') }} : <span class="required_sign">*</span></label>
									<div class="col-sm-7">
										<input class="form-control require" id="firstname" name="firstname" type="text" value="{{ !empty($user_data) ? $user_data->firstname : '' }}">
									</div>
								</div>
                                @else
                                    <input type="hidden" id="gender_id" name="gender_id" value="{{ !empty($user_data) ? $user_data->gender_id : '0' }}">
                                    <input type="hidden" id="lastname" name="lastname" type="text" value="{{ !empty($user_data) ? $user_data->lastname : '' }}">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-sm-3 text-left" for="firstname" id="first_name_label_id">{{__('Family Name') }} : <span class="required_sign">*</span></label>
                                        <div class="col-sm-7">
                                            <input class="form-control require" id="firstname" name="firstname" type="text" value="{{ !empty($user_data) ? $user_data->firstname : '' }}">
                                        </div>
                                    </div>
                                @endif
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="email" id="email_caption">{{__('Email') }} :</label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
											<input class="form-control" id="add_email" value="{{ !empty($user_data) ? $user_data->email : '' }}" name="email" type="text">
										</div>
									</div>
								</div>
							</div>

					<div class="col-md-6">
						<small>
							<strong>{{ __('Password Must') }}:</strong></br>
								> {{ __('Be more than 7 Characters') }}</br>
								> {{ __('An Uppercase Character') }}</br>
								> {{ __('A Lowercase Character') }}</br>
								> {{ __('A Number') }}</br>
								> {{ __('A Special character') }}</br>
							</small>

						<br>

						<button id="save_btn" name="save_btn" class="btn btn-theme-success"><i class="fa fa-save"></i>{{ __('Create my account') }} </button>
					</div>
						</div>
							</div></div>
					</fieldset>
				</form>
			</div>
        </div>
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
	$("#showpass").click(function(event) {
		var attrTpe = $("#add_password").attr('type')
		if (attrTpe == 'text') {
			$("#add_password").attr('type', 'password');
		}else{
			$("#add_password").attr('type', 'text');
		}

	});
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
