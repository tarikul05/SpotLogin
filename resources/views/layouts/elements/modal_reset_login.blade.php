<!-- RESET Modal after first logged in-->
<div class="modal fade reset-pass-modal" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="resetModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block text-center border-0">
        <h4 class="modal-title light-blue-txt gilroy-bold" id="resetModalLabel">{{ __('Reset Password')}}</h4>
        <h6 class="mb-0">{{ __('Welcome!')}}</h6>
      </div>
      <div class="modal-body" style="max-width: 375px; margin: 0 auto;padding-top: 0;">
        <form id="reset_form" name="reset_form" method="POST" action="#">
          <div class="form-group text-center">
            <h4 id="display_username" name="display_username"> Username: </h4>
          </div>

          <div class="form-group">
            <div class="input-group">
              <input class="form-control" type="hidden" id="reset_username" name="reset_username">
            </div>
          </div>

          <div class="form-group">
            <div class="input-group" id="show_hide_password">
              <input class="form-control" autocomplete="on" type="password" placeholder="Old Password" id="old_password" name="old_password" required>
              <div class="input-group-addon">
                <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>


          <div class="form-group">
            <div class="input-group" id="show_hide_password">
              <input class="form-control" autocomplete="on" type="password" placeholder="New Password" id="new_password" name="new_password" required>
              <div class="input-group-addon">
                <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>
          <small id="" class="password_hint">
            <strong>Password Must:</strong></br>
            > Be more than 7 Characters</br>
            > An Uppercase Character</br>
            > A Lowercase Character</br>
            > A Number</br>
            > A Special character</br>
          </small>

          <div class="form-group">
            <div class="input-group" id="show_hide_password">
              <input class="form-control" autocomplete="on" type="password" placeholder="Confirm Password" id="confirm_password" name="confirm_password" required>
              <div class="input-group-addon">
                <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('Reset Password')}}</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $("#reset_form").submit(function(e) {
    e.preventDefault();
  }).validate({
    // Specify validation rules
    rules: {
      old_password: {
        required: true
      },
      new_password: {
        required: true,
        minlength: 8
      },
      confirm_password: {
        required: true,
        minlength: 8
      }

    },
    // Specify validation error messages
    messages: {

      old_password: {
        required: "{{ __('Please provide you old password') }}",
        minlength: "{{ __('Your password must be at least 6 characters long') }}"
      },
      new_password: {
        required: "{{ __('Please provide new password') }}",
        minlength: "{{ __('Your password must be at least 6 characters long') }}"
      },
      confirm_password: {
        required: "{{ __('Please confirm new password') }}",
        minlength: "{{ __('Your password must be at least 6 characters long') }}"
      }
    },
    errorPlacement: function(error, element) {
      if (element.attr("type") == "checkbox") {
        $(element).parents('.checkbox').append(error);
      } else {
        $(element).parents('.form-group').append(error);
      }
    },

    submitHandler: function(form) {

      var old_pass = $("#old_password").val();
      var new_pass = $("#new_password").val();
      var confirm_pass = $("#confirm_password").val();
      let loader = $('#pageloader');
      

      if (new_pass.trim() != confirm_pass.trim()) {
        successModalCall("{{ __('Invalid confirm password: password and confirm password must be same.') }}");
        return false;
      }

      if (old_pass.trim() == new_pass.trim()) {
        successModalCall("{{ __('Invalid new password: old password and new password cannot be same.')}}");
        return false;
      }
      loader.show("fast");
      var formdata = $("#reset_form").serializeArray();
      var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
      
      //alert($("#reset_username").val());
      formdata.push({
        "name": "type",
        "value": "change_first_password"
      });
      formdata.push({
        "name": "_token",
        "value": csrfToken
      });
      $.ajax({
        url: BASE_URL + '/change_first_password',
        data: formdata,
        type: 'POST',
        dataType: 'json',
        //async: false,
        //encode: true,
        beforeSend: function (xhr) {
          loader.show("fast");
        },
        success: function(data) {
          loader.hide("fast");
          if (data.status == 0) {
            //var username = $("#login_username").val();
            successModalCall("{{__('Password changed Successfully.')}}");
            $("#login_password").val('');
            $("#resetModal").modal('hide');
            $("#loginModal").modal('show');
            //setTimeout(function(){ window.location.href = "../" + data.school_code + "/agenda/agenda.html"; }, 2000);

          } else {
            errorModalCall("{{__('Invalid username or old password')}}");
          }
        }, // succes
        error: function (reject) {
          loader.hide("fast");
          let errors = $.parseJSON(reject.responseText);
          errors = errors.errors;
          $.each(errors, function (key, val) {
              //$("#" + key + "_error").text(val[0]);
            errorModalCall(val[0]+ ' '+GetAppMessage('error_message_text')); 
          });
        },
        complete: function() {
            loader.hide("fast");
        }
      });
      return false; // required to block normal submit since you used ajax

    }
  });
});
</script>