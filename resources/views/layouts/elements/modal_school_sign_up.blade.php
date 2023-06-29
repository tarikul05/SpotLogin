<!-- Modal -->
<div class="modal fade login-signup-modal" id="schoolsignupModal" tabindex="-1" aria-hidden="true" aria-labelledby="schoolsignupModalLabel">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
          <div class="modal-header d-block text-center border-0">
              <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
              <h3 class="modal-title light-blue-txt gilroy-bold" id="signupModalLabel">{{ __('Sign up') }}</h3>
              <p class="mb-0">{{ __('Please fill in this form to create an account!') }}</p>
          </div>
          <div class="modal-body" style="padding-top: 0;">
            
            <div class="row">

                <div class="col-lg-6 p-3">
                <form id="signup_form" name="signup_form" method="POST" action="#">
                    <div class="form-group custom-selection">
                        <select class="selectpicker" id="school_type" name="school_type" required>
                            <option value="COACH">{{ __('Single coach') }}</option>
                            <option value="SCHOOL" disabled="true">{{ __('School') }} (coming soon)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="{{ __('Account name (full name / Name of the school)') }}" id="fullname" name="fullname"
                            required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="{{ __('Choose an ID') }}" id="username" name="username"
                            required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email" id="email" name="email"
                            required>
                    </div>
                    <div class="form-group custom-selection">
                        <select class="selectpicker" data-live-search="true" id="country_code" name="country_code" required>
                            <option value="">{{ __('Select Country')}}</option>
                            @foreach ($countries as $key => $country)
                                <option 
                                value="{{ $country->code }}"
                                >  {{ $country->name }}</option>
                            @endforeach

                        </select>
                    </div>
                        <div class="form-group custom-selection">
                            <select class="selectpicker" data-live-search="true" id="timezone" name="timezone" required>
                                <option value="">{{ __('Select Timezone')}}</option>
                                @foreach ($timezones as $key => $value)
                                <option 
                                value="{{ $key }}"
                                >  {{ $value }}</option>
                                @endforeach

                            </select>
                        </div>
                    <div class="form-group">
                        <div class="input-group" id="show_hide_password">
                                <input class="form-control" autocomplete="on" type="password" id="password" placeholder="{{ __('password') }}" name="password"> 
                                <div class="input-group-addon">
                                    <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                        </div>
                    </div>
               
                        <br>
                    <div class="checkbox text-center">
                        <label><input type="checkbox" id="terms_condition" name="terms_condition" required> {{ __('I agree with the terms and conditions') }}</label>
                    </div>
                    <br>
                    <div class="text-center">
                        <button type="submit" id="signup_form_button" class="btn btn-lg btn-primary btn-block">{{ __('Create an account') }}</button>
                    </div>
                </form>
                </div>
                <div class="col-lg-6 bg-tertiary text-center p-3">
                    <br><br>
                <h4>{{ __('Free Trial') }}</h4>
                <h5 class="light-blue-txt">{{ __('Sign up now and take advantage of a 90-free-day trial period') }}</h5>
                    <br><br>
                    <small id="" class="password_hint bg-tartiary card pt-1 mt-2">
                        <strong>{{ __('Password Must') }}:</strong></br>
                            > {{ __('Be more than 7 Characters') }}</br>
                            > {{ __('An Uppercase Character') }}</br>
                            > {{ __('A Lowercase Character') }}</br>
                            > {{ __('A Number') }}</br>
                            > {{ __('A Special character') }}</br>
                        </small>
                </div>

             </div>
              
              <div style="text-align:center;margin-top:10px;">
                  <p>{{ __('Already have an account?')}} <a class="login_btn" href="#loginModal" data-bs-toggle="modal" data-bs-target="#loginModal">{{ __('Sign in') }}</a> {{ __('now') }}</p>
              </div>
          </div>
      </div>
  </div>
</div>

<script>

$(document).ready(function () {

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
                minlength: 8
            }
        },
        // Specify validation error messages
        messages: {
            terms_condition: "{{__('This field is required.')}}",
            school_type: "{{__('Please select type')}}",
            fullname: "{{ __('Please enter your full name')}}",
            username: "{{ __('Please enter your username')}}",
            country_code: "{{ __('Please select country')}}",
            password: {
                required: "{{ __('Please provide a password')}}",
                minlength: "{{ __('Your password must be at least 8 characters long')}}"
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
        submitHandler: function (form) {
            var loader = $('#pageloader');
            var Validate_User_Name = ValidateUserName();
                console.log('Validate_User_Name =' + Validate_User_Name);

            if (Validate_User_Name != 0) {

                errorModalCall("{{__('Username already exists...')}}");
                loader.hide("fast");

                return false;
            } else {

                var formdata = $("#signup_form").serializeArray();

                var csrfToken = "{{ csrf_token() }}";


            
                formdata.push({
                    "name": "_token",
                    "value": "{{ csrf_token() }}"
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
                    //async: false,
                    //encode: true,
                    headers: {'X-CSRF-TOKEN': csrfToken},
                    beforeSend: function (xhr) {
                        loader.show("fast");
                    },
                    success: function(data) {

                        if (data.status) {

                            $("#schoolsignupModal").modal('hide');
                            //$("#successModal").modal('show');
                            successModalCall(data.message);
                            

                            //$("#loginModal").modal('show');
                        } else {
                            errorModalCall(GetAppMessage('error_message_text'));

                        }

                    }, // sucess
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
            }
            return false; // required to block normal submit since you used ajax
        }

    });

    function ValidateUserName() {
        var loader = $('#pageloader');
        loader.show();
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