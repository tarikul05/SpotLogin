@extends('layouts.raw')

@section('content')
<!-- Reset Password -->
<div class="login-signup-modal mt-200">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-block text-center border-0">
                
                <h2 class="modal-title text-primary font-weight-bold" id="resetpasswordModalLabel">{{ __('Reset Pasword')}}</h2>
                
            </div>
            <div class="modal-body" style="max-width: 375px; margin: 0 auto;padding-top: 0;">
                <form id="reset_password_form_new" name="reset_password_form_new" method="POST" action="#">
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Enter New Password" id="reset_password_pass" name="reset_password_pass" required>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" id="reset_password_confirm_pass" name="reset_password_confirm_pass" required>
                </div>
                    
                
                <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('Submit')}}</button>
                </form>
                
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
                minlength: 6
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
                minlength: "{{ __('Your password must be at least 6 characters long')}}"
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
            var username = getUrlParameter('username');
            var hxunid = getUrlParameter('hxunid');
            
            formdata.push({"name":"type","value":"reset_password_submit"});
            formdata.push({"name":"username","value":username});
            formdata.push({"name":"hxunid","value":hxunid});
            
            $.ajax({
                url: 'forgot_password.php',
                data: formdata,
                type: 'POST',
                dataType: 'json',
                async: false,
                encode:true,
                success: function (data) { 
                        // alert(data.status);
                    if(data.status){
                        $.alert({
                            title: 'Success',
                            content: data.msg,
                            type: 'green',
                            buttons: {
                                OK: function () {
                                    window.location.href="/";
                                }
                            }
                        });

                    
                    } else {
                        $.alert({
                            title: 'Alert!',
                            content: data.msg,
                            type: 'red'
                        });

                    }

                },   // sucess
                error: function (ts) { 
                    $.alert({
                        title: 'Alert!',
                        content: "{{ __('Oops Something went wrong')}}",
                        type: 'red'
                    });  
                }
            });

        }
    });

  });
  
</script>
@endsection



