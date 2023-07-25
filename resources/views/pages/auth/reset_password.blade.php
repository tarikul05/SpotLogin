@extends('layouts.verify')

@section('content')
<!-- Reset Password -->
<div class="login-signup-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content pt-5">
            <div class="modal-header d-block text-center border-0">
                
                <h5 class="modal-title font-weight-bold" id="resetpasswordModalLabel">{{ __('Reset Pasword')}}</h5>
                <span>Account : {{ $user->firstname }} {{ $user->lastname }}
            </div>

            <div class="card">
                <div class="card-body bg-tertiary">
            <div class="modal-body" style="max-width: 375px; margin: 0 auto;padding-top: 0;">
                <form method="POST" action="{{route('reset_password.submit')}}">
                
                    @csrf
                    @if(session()->has('error'))
                        <div class="alert alert-danger invalid-feedback d-block">{{ session()->get('error') }}</div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success">
                        {{ session('status') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="alert alert-warning">
                        {{ session('warning') }}
                        </div>
                    @endif
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control" type="hidden" id="reset_password_username" name="reset_password_username" value="{{$user->username}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control" type="hidden" id="reset_password_user_id" name="reset_password_user_id" value="{{$user->id}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Enter New Password" id="reset_password_pass" name="reset_password_pass" required>
                    </div>
                   

                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Confirm Password" id="reset_password_confirm_pass" name="reset_password_confirm_pass" required>
                    </div>


                    <div class="card">
                        <div class="card-body bg-tertiary">
                    <small>
                        <strong>{{ __('Password Must') }}:</strong></br>
                            > {{ __('Be more than 7 Characters') }}</br>
                            > {{ __('An Uppercase Character') }}</br>
                            > {{ __('A Lowercase Character') }}</br>
                            > {{ __('A Number') }}</br>
                            > {{ __('A Special character') }}</br>
                        </small>
                        </div>
                    </div>
                        
                    <div class="mt-3 text-center">
                        <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('Submit')}}</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {


    $("#reset_password_form_new").validate({
        // Specify validation rules
        rules: {
            reset_password_pass: {
                required: true, 
                minlength: 8
            },
            reset_password_confirm_pass: {
                required: true, 
                equalTo : "#reset_password_pass"
            }
        },
        // Specify validation error messages
        messages: {
            
            
            reset_password_pass: {
                required: "{{ __('Please provide a password')}}",
                minlength: "{{ __('Your password must be at least 8 characters long')}}"
            },
            reset_password_confirm_pass: {
                required: "{{ __('Please provide confirm password')}}"
            }
        },
        errorPlacement: function (error, element) {
            if (element.attr("type") == "checkbox") {
                $(element).parents('.checkbox').append(error);
            } else {
                $(element).parents('.form-group').append(error);
            }
        },
        
        submitHandler: function (form) {
            

            var formdata = $("#reset_password_form_new").serializeArray();
            
        }
    });

  });
  
</script>
@endsection



