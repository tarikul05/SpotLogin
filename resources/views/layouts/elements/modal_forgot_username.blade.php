<!-- Forgot Password Modal -->
<div class="modal fade login-signup-modal" id="forgotUsernameModal" tabindex="-1" aria-hidden="true" aria-labelledby="forgotUsernameModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-block text-center border-0">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title light-blue-txt gilroy-bold" id="forgotUsernameModalLabel">{{ __('Forgot Username?') }}</h3>
                <a href="#" class="close" id="modalClose" data-bs-dismiss="modal" style="position: absolute; right: 10px; top: 10px; border-radius:50%!important; padding:3px; font-size:23px;">
                    <i class="fa-solid fa-circle-xmark fa-lg" style="color:#0075bf;"></i>
                </a>
            </div>
            <div class="modal-body text-center" style="max-width: 375px; margin: 0 auto;padding-top: 0;">
                <form id="forgot_username_form" name="forgot_username_form" method="POST" action="#">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="{{ __('Email address required') }}" id="forgot_password_username" name="forgot_password_username" required>
                </div>
                <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('submit') }}</button>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#forgot_username_form").submit(function(e) {
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
            forgot_password_username: "{{ __('Please enter a valid Email address')}}"
        },
        errorPlacement: function(error, element) {
            if (element.attr("type") == "checkbox") {
                $(element).parents('.checkbox').append(error);
            } else {
                $(element).parents('.form-group').append(error);
            }
        },

        submitHandler: function(form) {
            let loader = $('#pageloader');
            loader.fadeIn("fast");
            var p_lang = $('#setLan').val();

            var formdata = $("#forgot_username_form").serializeArray();
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
            formdata['username'] = $('#forgot_password_username').val();

            $.ajax({
                url: BASE_URL + '/forgot_username',
                data: formdata,
                type: 'POST',
                dataType: 'json',
                //async: false,
                //encode: true,
                beforeSend: function (xhr) {
                    loader.fadeIn("fast");
                },
                success: function(data) {
                    //alert(data.status);
                        console.log(data);
                    if (data.status) {
                        $('#forgotUsernameModal').modal('hide');
                       successModalCall("Information", "{{ __('We sent you an activation link. Check your email and click on the link to choose your account Login ID.')}}");
                    } else {
                        errorModalCall('Information', data.msg);
                    }

                }, // sucess
                error: function(ts) {
                    errorModalCall('Information', GetAppMessage('error_message_text'));
                },
                complete: function() {
                    loader.fadeOut("fast");
                }
            });

        }
    });
});
</script>
