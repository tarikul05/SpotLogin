<!-- Forgot Password Modal -->
<div class="modal fade login-signup-modal" id="forgotPasswordModal" tabindex="-1" aria-hidden="true" aria-labelledby="forgotPasswordModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-block text-center border-0">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title text-primary font-weight-bold" id="forgotPasswordModalLabel">{{ __('Forgot Password?') }}</h3>
                
            </div>
            <div class="modal-body" style="max-width: 375px; margin: 0 auto;padding-top: 0;">
                <form id="forgot_password_form" name="forgot_password_form" method="POST" action="#">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Username" id="forgot_password_username" name="forgot_password_username" required>
                </div>
                <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('Submit') }}</button>
                </form>
                
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#forgot_password_form").submit(function(e) {
        e.preventDefault();
    }).validate({
        // Specify validation rules
        rules: {
            forgot_password_username: {
                required: true
            }
        },
        // Specify validation error messages
        messages: {
            forgot_password_username: "{{ __('Please enter a username')}}"
        },
        errorPlacement: function(error, element) {
            if (element.attr("type") == "checkbox") {
                $(element).parents('.checkbox').append(error);
            } else {
                $(element).parents('.form-group').append(error);
            }
        },

        submitHandler: function(form) {

            var p_lang = $('#setLan').val();
            
            var formdata = $("#forgot_password_form").serializeArray();
            var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
      
            formdata.push({
                "name": "type",
                "value": "forgot_password_submit"
            });
            formdata.push({
                "name": "_token",
                "value": csrfToken
            });
            formdata.push({
                "name": "p_lang",
                "value": p_lang
            });

            $.ajax({
                url: BASE_URL + '/forgot_password',
                data: formdata,
                type: 'POST',
                dataType: 'json',
                async: false,
                encode: true,
                success: function(data) {
                //alert(data.status);  
                if (data.status) {
                    successModalCall("{{ __('Reset Password Email Sent, Reset password link has been sent to your registered email')}}");
                } else {
                    errorModalCall(data.msg);
                }

                }, // sucess
                error: function(ts) {
                    errorModalCall(GetAppMessage('error_message_text'));
                }
            });

        }
    });
});
</script>