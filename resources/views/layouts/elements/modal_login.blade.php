<!-- Modal -->
<div class="modal fade login-signup-modal" id="loginModal" tabindex="-1" aria-hidden="true" aria-labelledby="loginModalLabel">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content modal-body-mobile">
      <div class="modal-header d-block text-center border-0">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->

        <h3 class="modal-title light-blue-txt gilroy-bold" id="loginModalLabel">{{ __('Sign in') }}</h3>
        <a href="#" class="close" id="modalClose" data-bs-dismiss="modal" style="position: absolute; right: 10px; top: 10px; border-radius:50%!important; padding:3px; font-size:23px;">
            <i class="fa-solid fa-circle-xmark fa-lg" style="color:#0075bf;"></i>
        </a>

        <p class="mb-0">{{ __('Welcome to SportLogin!') }}</p>
        <p class="mb-0 text-danger" id="error_msg"></p>
        <div class="text-center" id="otp_div" style="display: none;">
          <p>{{ __('Enter the verification code you receive by email') }}</p>
          <form id="code_form" name="code_form" method="POST" action="{{ route('verify.code') }}">
          <input type="text" class="digit" id="digit1" maxlength="1">
          <input type="text" class="digit" id="digit2" maxlength="1"> 
          <input type="text" class="digit" id="digit3" maxlength="1">
          <input type="text" class="digit" id="digit4" maxlength="1">
          <input type="text" class="digit" id="digit5" maxlength="1">
          <input type="text" class="digit" id="digit6" maxlength="1">
          <div style="display:block; margin: 20px;">
            <button type="submit" class="btn btn-success" id="validate_code">{{ __('Validate my account') }}</button>
          </div>
          </form>
          <p><a href="#" style="color: #0075bf;" id="resend_code_form">{{ __('Re-send the code') }}</a></p>
        </div>

      </div>
      <div class="modal-body text-center" style="max-width: 375px; width:100%; margin: 0 auto;padding-top: 0;">
        <form id="login_form" name="login_form" method="POST" action="{{ route('login.submit') }}">

          <div class="form-group">
            <input type="text" class="form-control" placeholder="Your Login ID" id="login_username" name="login_username" required>
          </div>
          <div class="form-group">
            <div class="input-group" id="show_hide_password">
              <input class="form-control" autocomplete="on" type="password" placeholder="Your Password" id="login_password" name="login_password" required>
              <div class="input-group-addon">
                <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>
          <div style="margin-bottom:10px;">
            <small><a class="forgot_password_btn"  data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">{{ __('Forgot password?') }}</a> 
              <span style="font-size:18px; margin-left:5px; margin-right:5px;">|</span> 
              <a class="forgot_password_btn"  data-bs-toggle="modal" data-bs-target="#forgotUsernameModal">{{ __('Forgot username?') }}</a>
            </small>
          </div>
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

const inputs = document.querySelectorAll('input[id^="digit"]');
let emailVerification = null;

inputs.forEach(input => {
  input.addEventListener('keyup', () => {
    if(input.value.length === 1) {
      const index = parseInt(input.id.slice(-1))
      if(index < 6) {
        inputs[index].focus();  
      }
    }
  });
});

  $("#pageloader").fadeOut();

  if(window.location.href.indexOf('#login') != -1) {
    $('#loginModal').modal('show');
  }

  $("#show_hide_password a").on('click', function(event) {
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



  $("#code_form").submit(function(e) {
    e.preventDefault();
      $('#pageloader').show();
      var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';

      var formdata = $("#code_form").serializeArray();
      var code = $('#digit1').val() + $('#digit2').val() + $('#digit3').val() + $('#digit4').val() + $('#digit5').val() + $('#digit6').val();

      formdata.push({
        "name": "username",
        "value": emailVerification
      });
      formdata.push({
        "name": "code",
        "value": code
      });
      formdata.push({
        "name": "_token",
        "value": csrfToken
      });

      $.ajax({
        url: BASE_URL + '/verify-account-code',
        data: formdata,
        type: 'POST',
        dataType: 'json',
        async: false,
        encode: true,
        success: function(data) {
          status = data.status;
          $('#pageloader').hide();
          let timerInterval;
          Swal.fire({
          title: "Congratulations!",
          html: 'Your account is verified successfully.',
          timer: 3000,
          timerProgressBar: true,
          didOpen: () => {
              Swal.showLoading();
              const timer = Swal.getPopup().querySelector("b");
              timerInterval = setInterval(() => {
              timer.textContent = `${Swal.getTimerLeft()}`;
              }, 100);
          },
          willClose: () => {
              clearInterval(timerInterval);
          }
          }).then((result) => {
          /* Read more about handling dismissals below */
          if (result.dismiss === Swal.DismissReason.timer) {
              window.location.href = BASE_URL + '/permission-check';
          }
          });
        }, // sucess
        error: function(ts) {
          $('#pageloader').hide();
          errorModalCall('This code is not valid. Please check your email and try again.');
        }
      });
      
  });

  $("#resend_code_form").click(function(e) {
    e.preventDefault();
      $('#pageloader').show();
      var formdata = [];
      var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
      formdata.push({
        "name": "username",
        "value": emailVerification
      });
      formdata.push({
        "name": "_token",
        "value": csrfToken
      });
      $.ajax({
        url: BASE_URL + '/resend-account-code',
        data: formdata,
        type: 'POST',
        dataType: 'json',
        async: false,
        encode: true,
        success: function(data) {
          $('#pageloader').hide();
          status = data.status;
          Swal.fire({
            position: 'center',
            icon:'success',
            title: 'Code has been sent to your email.',
            showConfirmButton: false,
            timer: 3000
          });
        }, // sucess
        error: function(ts) {
          $('#pageloader').hide();
         console.log(ts);
          errorModalCall('This code is not valid. Please check your email and try again.');
        }
      });
  });


  function FirstLoginAfterResetPass() {

      var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';

      var status = 1;
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
          //errorModalCall(GetAppMessage('error_message_text'));

        }
      });
      if (status == 0) {
        //return true; //demo
        return true;
      } else {
        return false;
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
      //$("#loginModal").modal('hide');
      let loader = $('#pageloader');
      //loader.show("fast");
      if (FirstLoginAfterResetPass()) {
        loader.fadeOut("fast");
        document.getElementById("display_username").innerHTML = document.getElementById("login_username").value;
        document.getElementById("reset_username").value = document.getElementById("login_username").value;
        $("#loginModal").modal('hide');
        $("#resetModal").modal('show');

        return false;
      }

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
        //async: false,
        //encode: true,
        beforeSend: function (xhr) {
          loader.fadeIn("fast");
        },
        success: function(data) {
          if (data.status == 0) {
            $("#otp_div").hide();
            var username = $("#login_username").val();
            $("#loginModal").modal('hide');
            setTimeout(function() {
              window.location.href = data.login_url;
            }, 1000);
          } else {
            if (data.status == 2) {
              setTimeout(() => {
              loader.fadeOut("fast");
              emailVerification = data.username;
              $("#error_msg").html('<br>Please check your email for verification link');
              $("#otp_div").show();
             // errorModalCall('Information', "{{ __('Please check your email for verification link') }}");
            }, "900")
            } else {
              setTimeout(() => {
              loader.fadeOut("fast");
              $("#error_msg").html('Invalid username or password');
             // errorModalCall('Information', "{{ __('Invalid username or password') }}");
            }, "900")
            }
          }
        }, // sucess
        error: function(ts) {
          setTimeout(() => {
            loader.fadeOut("fast");
            $("#error_msg").html('Invalid username or password');
           // errorModalCall('Information', "{{ __('Invalid username or password') }}");
          }, "900")

        },
        complete: function() {
          /*setTimeout(() => {
            loader.fadeOut("fast");
          }, "1500");*/
        }
      });
      return false; // required to block normal submit since you used ajax

    }
  });
});
</script>
