<!-- Modal -->
<div class="modal fade login-signup-modal" id="schoolsignupModal" tabindex="-1" aria-hidden="true"
        aria-labelledby="schoolsignupModalLabel">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header d-block text-center border-0">
              <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
              <h3 class="modal-title light-blue-txt gilroy-bold" id="signupModalLabel">{{ __('Sign up') }}</h3>
              <p class="mb-0">{{ __('Please fill in this form to create an account!') }}</p>
          </div>
          <div class="modal-body" style="max-width: 375px; margin: 0 auto;padding-top: 0;">
              
              <form id="signup_form" name="signup_form" method="POST" action="#">
                  <div class="form-group custom-selection">
                      <select class="selectpicker" id="school_type" name="school_type" required>
                          <option value="COACH">{{ __('Coach') }}</option>
                          <option value="SCHOOL">{{ __('School') }}</option>
                      </select>
                  </div>
                  <div class="form-group">
                      <input type="text" class="form-control" placeholder="Fullname" id="fullname" name="fullname"
                          required>
                  </div>
                  <div class="form-group">
                      <input type="text" class="form-control" placeholder="Username" id="username" name="username"
                          required>
                  </div>
                  <div class="form-group">
                      <input type="email" class="form-control" placeholder="Email" id="email" name="email"
                          required>
                  </div>
                  <div class="form-group custom-selection">
                      <select class="selectpicker" id="country_id" name="country_id" required>
                          <option value="">Select Country</option>
                          @foreach ($countries as $key => $country)
                            <option 
                            value="{{ $country->id }}"
                            >  {{ $country->name }}</option>
                          @endforeach

                      </select>
                  </div>
                  <div class="form-group">
                      <div class="input-group" id="show_hide_password">
                          <input class="form-control" type="password" id="password" name="password"> 
                          <div class="input-group-addon">
                              <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                          </div>
                      </div>
                  </div>
                  <div class="checkbox">
                      <label><input type="checkbox" id="terms_condition" name="terms_condition" required>{{ __('I agree with the terms and conditions') }}</label>
                  </div>
                  <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('Create an account') }}</button>
              </form>
              
              <div style="text-align:center;margin-top:10px;">
                  <p>Already have an account? <a class="login_btn" href="#loginModal" data-bs-toggle="modal" data-bs-target="#loginModal">Sign in</a> now</p>
              </div>
          </div>
      </div>
  </div>
</div>

<script>
$(document).ready(function() {

    $("#signup_form").submit(function(e) {
        e.preventDefault();
    }).validate({
        // Specify validation rules
        rules: {
            terms_condition:"required",
            school_type: "required",
            fullname: "required",
            username: "required",
            country_id: "required",
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        // Specify validation error messages
        messages: {
            terms_condition: "{{__('This field is required.')}}",
            school_type: "{{__('Please select type')}}",
            fullname: "{{ __('Please enter your full name')}}",
            username: "{{ __('Please enter your username')}}",
            country_id: "{{ __('Please select country')}}",
            password: {
            required: "{{ __('Please provide a password')}}",
            minlength: "{{ __('Your password must be at least 6 characters long')}}"
            },
            email: "{{ __('Please enter a valid email address')}}"
        },
        errorPlacement: function(error, element) {
            if (element.attr("type") == "checkbox") {
                $(element).parents('.checkbox').append(error);
            } else {
                $(element).parents('.form-group').append(error);
            }
        },

        submitHandler: function(form) {
            var Validate_User_Name = ValidateUserName();
            console.log('Validate_User_Name =' + Validate_User_Name);
            if (Validate_User_Name != 0) {

                errorModalCall("{{__('Username already exists...')}}");


                return false;
            } else {
            //console.log('Username is valid.'); 

            var formdata = $("#signup_form").serializeArray();

            var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';


        
            formdata.push({
                "name": "_token",
                "value": csrfToken
            });
            //console.log(formdata);
            formdata.push({
                "name": "type",
                "value": "signup_submit"
            });

            $.ajax({
                url: BASE_URL + '/signup',
                data: formdata,
                type: 'POST',
                dataType: 'json',
                async: false,
                encode: true,
                success: function(data) {

                    if (data.status) {

                        $("#signupModal").modal('hide');
                        $("#successModal").modal('show');

                        //$("#loginModal").modal('show');
                    } else {
                        errorModalCall(GetAppMessage('error_message_text'));

                    }

                }, // sucess
                error: function(ts) {
                    errorModalCall(GetAppMessage('error_message_text'));

                }
            });

            }
        }

    });

    function ValidateUserName() {
        var v_cnt;
        var username = $('#username').val();

        var formdata = [];
        var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';


        formdata.push({
            "name": "type",
            "value": "validate_username"
        });
        formdata.push({
            "name": "p_username",
            "value": username
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
            success: function(result) {
                v_cnt = result.cnt;
            }, // sucess
            error: function(ts) {
                errorModalCall(GetAppMessage('error_message_text'));
                return false;
            }
        }); //ajax-type
        
        return v_cnt;
    }
});
</script>