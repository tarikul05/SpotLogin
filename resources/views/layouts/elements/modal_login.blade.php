<!-- Modal -->
<div class="modal fade login-signup-modal" id="loginModal" tabindex="-1" aria-hidden="true" aria-labelledby="loginModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block text-center border-0">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->

        <h3 class="modal-title light-blue-txt gilroy-bold" id="loginModalLabel">{{ __('Sign in') }}</h3>

        <p class="mb-0">{{ __('Welcome back!') }}</p>
      </div>
      <div class="modal-body" style="max-width: 375px; margin: 0 auto;padding-top: 0;">
        <form id="login_form" name="login_form" method="POST" action="{{ route('login.submit') }}">

          <div class="form-group">
            <input type="text" class="form-control" placeholder="Username" id="login_username" name="login_username" required>
          </div>
          <div class="form-group">
            <div class="input-group" id="show_hide_password">
              <input class="form-control" type="password" placeholder="Password" id="login_password" name="login_password" required>
              <div class="input-group-addon">
                <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>
          <div style="margin-bottom:10px;"><small><a class="forgot_password_btn" data-toggle="modal" data-target="#forgotPasswordModal">{{ __('Forgot password?') }}</a></small></div>
          <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('Sign in') }}</button>
        </form>
        
        <!-- <div style="text-align:center;margin-top:10px;">
            <p>{{ __('Please_sign_up') }} <a href="#" class="signup_btn" data-bs-toggle="modal" data-bs-target="#signupModal">Sign Up</a>
            </p>
        </div> -->
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  function FirstLoginAfterResetPass() {
      
      var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
      
      var status = 0;
      var formdata = $("#login_form").serializeArray();
      
      formdata.push({
        "name": "type",
        "value": "check_first_login"
      });
      formdata.push({
        "name": "_token",
        "value": csrfToken
      });
      $.ajax({
        url: BASE_URL + '/login',
        data: formdata,
        type: 'POST',
        dataType: 'json',
        async: false,
        encode: true,
        success: function(data) {
          status = data.status;
        }, // sucess
        error: function(ts) {
          errorModalCall(GetAppMessage('error_message_text'));

        }
      });
      if (status == 0) {
        //return true; //demo
        return false;
      } else {
        return true;
      }
    }

  $("#login_form").submit(function(e) {
    e.preventDefault();
  }).validate({
    // Specify validation rules
    rules: {
      login_username: {
        required: true
      },
      login_password: {
        required: true,
        minlength: 6
      }
    },
    // Specify validation error messages
    messages: {

      login_password: {
        required: "{{ __('Please provide a password') }}",
        minlength: "{{ __('Your password must be at least 6 characters long') }}"
      },
      login_username: "{{ __('Please enter a username') }}"
    },
    errorPlacement: function(error, element) {
      if (element.attr("type") == "checkbox") {
        $(element).parents('.checkbox').append(error);
      } else {
        $(element).parents('.form-group').append(error);
      }
    },

    submitHandler: function(form) {

      if (FirstLoginAfterResetPass()) {

        document.getElementById("display_username").innerHTML = document.getElementById("login_username").value;
        document.getElementById("reset_username").value = document.getElementById("login_username").value;
        $("#loginModal").modal('hide');
        $("#resetModal").modal('show');
        return false;
      }
      return false;

      var formdata = $("#login_form").serializeArray();
      var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
      
      
      formdata.push({
        "name": "type",
        "value": "login_submit"
      });
      formdata.push({
        "name": "_token",
        "value": csrfToken
      });
      $.ajax({
        url: BASE_URL + '/login',
        data: formdata,
        type: 'POST',
        dataType: 'json',
        async: false,
        encode: true,
        success: function(data) {
          
          if (data.status == 0) {
            var username = $("#login_username").val();

           
           

            successModalCall("{{ __('Logged In Successfully') }}");
            $("#loginModal").modal('hide');
            setTimeout(function() {
              window.location.href = "../teachers";
            }, 2000);



          } else {

            errorModalCall("{{ __('Invalid username or password') }}");


          }

        }, // sucess
        error: function(ts) {
          errorModalCall("{{ __('Invalid username or password') }}");

        }
      });

    }
  });
});
</script>