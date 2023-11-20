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
            <form id="signup_form" name="signup_form" method="POST" action="#">

            <div class="row">

                <div class="col-lg-6 p-3">


                    <div class="form-group custom-selection">
                        <select class="selectpicker" id="school_type" name="school_type" required onchange="changePlaceholder()">
                            <option value="COACH">{{ __('I am a Single coach') }}</option>
                            <!--<option value="SCHOOL">{{ __('School') }} (for testing)</option>-->
                            <option value="SCHOOL" disabled="true">{{ __('School') }} ({{ __('Coming Soon') }})</option>
                        </select>
                    </div>
                    <div class="form-group custom-selection">
                        <select class="selectpicker selectDiscipline" id="disciplineSelect" name="discipline" onchange="changeInputVisibility()">
                            <option value="">{{ __('Choose an activity') }}</option>
                            <option value="ice-skating">Ice-skating</option>
                            <option value="hockey">Hockey</option>
                            <option value="soccer">Soccer</option>
                            <option value="other-discipline">{{ __('Other') }} ?</option>
                        </select>
                        <input type="text" class="form-control inputDiscipline" placeholder="Enter a discipline" name="discipline2" id="disciplineInput" style="display: none;">
                    </div>



                    <div class="card bg-tertiary p-2 mb-3">
                        <small class="pb-2 light-blue-txt">{{ __('Personnal information') }}</small>

                    <div class="form-group">
                        <label for="email">{{ __('Firstname') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('Firstname') }}" id="firstname" name="firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="email">{{ __('Lastname') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('Lastname') }}" id="lastname" name="lastname" required>
                    </div>
                    <div id="welcome-message"></div>

                    <div class="form-group">
                        <input type="hidden" class="form-control" id="fullname" name="fullname" required>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('Email') }}</label>
                        <input type="email" class="form-control" placeholder="Email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('Confirm Email') }}</label>
                        <input type="email" class="form-control" placeholder="Confirm Email Address" id="email_confirm" name="email_confirm" required>
                    </div>

                    </div>


                    <div class="card bg-tertiary p-2 mb-3">
                        <small class="pb-2 light-blue-txt">{{ __('Login credentials') }}</small>
                        <div class="form-group">
                            <small>{{ __('Choose an username as your login ID') }}</small>
                            <input type="text" class="form-control" placeholder="{{ __('Username') }}" id="username" name="username" required onkeyup="checkUsername(this.value)">
                            <small class="text-danger" style="display: block;" id="username_feedback"></small>
                            <small class="text-success" id="username_available"></small>
                            <small class="text-danger" id="username_already_exist"></small>
                        </div>
                        <div class="form-group">
                            <div class="input-group" id="show_hide_password">
                                    <input class="form-control" autocomplete="on" type="password" id="password" placeholder="{{ __('password') }}" name="password">
                                    <div class="input-group-addon">
                                        <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                    </div>
                            </div>
                        </div>
                    </div>




                    <div class="card bg-tertiary p-2 mb-3">
                        <small class="pb-2 light-blue-txt">Localisation</small>
                        <div class="form-group custom-selection">
                            <select class="selectpicker" data-live-search="true" id="country_code" name="country_code" required>
                                <option value="">{{ __('Select Country')}}</option>
                                @foreach ($countries as $key => $country)
                                    <option
                                    value="{{ $country->code }}">{{ $country->name }}</option>
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
                    </div>




                </div>
                <div class="col-lg-6 bg-tertiary text-center p-3">
                <div class="d-none d-sm-block"><br><br></div>
                <h5 class="d-none d-sm-block">{{ __('Free Trial') }}</h5>
                <h5 class="d-none d-sm-block light-blue-txt">{{ __('Sign up now and take advantage of a 30-free-day trial period') }}</h5>
                <div class="d-none d-sm-block"><br><br></div>
                    <small id="" class="password_hint bg-tartiary card pt-1 mt-2 pb-2">
                        <strong>{{ __('Password Must') }}:</strong>
                        > {{ __('Be more than 7 Characters') }}</br>
                        > {{ __('A Lowercase Character') }}</br>
                        > {{ __('A Number') }}</br>
                    </small>

                    <div class="alert alert-warning">{{ __('This sign-up form is only for Coachs and Schools. If you are a') }} <b>{{ __('student') }}</b> {{ __('invited by your school or your teacher, please') }}
                        <a class="login_btn" href="#modalStudent" data-bs-toggle="modal" data-bs-target="#modalStudent">{{ __('click here') }}</a>
                    </div>

                        <br>
                        <div class="checkbox text-center">
                            <label><input type="checkbox" id="terms_condition" name="terms_condition" required> {{ __('I agree with the') }} <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal2">{{ __('terms and conditions') }}</a></label>
                        </div>
                        <br>
                        <div class="text-center">
                            <button type="submit" id="signup_form_button" class="btn btn-lg btn-primary btn-block">{{ __('Create an account') }}</button>
                        </div>

                </div>

             </div>

              <div style="text-align:center;margin-top:10px; padding-top:15px; border-top:1px solid #EEE;">
                  <p>{{ __('Already have an account?')}} <span class="d-block d-sm-none"><br></span> <a class="login_btn" href="#loginModal" data-bs-toggle="modal" data-bs-target="#loginModal">{{ __('Sign in') }}</a> {{ __('now') }}</p>
              </div>

            </form>

          </div>
      </div>
  </div>
</div>

<script>
    function changeInputVisibility() {
        var selectElement = document.getElementById("disciplineSelect");
        var inputElement = document.getElementById("disciplineInput");

        if (selectElement.value === "other-discipline") {
            inputElement.style.display = "block";
            inputElement.placeholder = "Enter a discipline";
        } else {
            inputElement.style.display = "none";
            //selectElement.placeholder = "Choose a discipline";
        }
    }
</script>



<script>
// Sélectionnez les éléments "firstname" et "lastname" par leur ID
var firstnameInput = document.getElementById('firstname');
var lastnameInput = document.getElementById('lastname');
var fullnameInput = document.getElementById('fullname');
var username = document.getElementById('username');
var welcomeMessage = document.getElementById('welcome-message');

// Ajoutez un gestionnaire d'événements "input" aux champs "firstname" et "lastname"
firstnameInput.addEventListener('input', updateFullName);
lastnameInput.addEventListener('input', updateFullName);

// Fonction pour mettre à jour le champ "fullname"
function updateFullName() {
    var firstnameValue = firstnameInput.value.trim();
    var lastnameValue = lastnameInput.value.trim();

    // Concaténez les valeurs de "firstname" et "lastname" pour obtenir le nom complet
    var fullNameValue = firstnameValue + ' ' + lastnameValue.trim();

    // Mettez à jour la valeur du champ "fullname"
    fullnameInput.value = fullNameValue.trim();

    if(fullNameValue.length > 2) {
        setTimeout(() => {
            welcomeMessage.innerHTML = 'Fullname : <b>' + fullNameValue.trim()+'</b>';
            var newUsername = fullNameValue.trim();
            username.value = newUsername.replace(/[^a-zA-Z0-9]/g, '');
            checkUsername(newUsername.replace(/[^a-zA-Z0-9]/g, ''));
        }, 500);
    } else {
        welcomeMessage.textContent = '';
    }
}

// Appelez la fonction initiale pour remplir "fullname" si les champs "firstname" et "lastname" ont déjà des valeurs
updateFullName();
    </script>

<script>
    function checkUsername(username) {
        if(username.length>0) {

            // Regex to check for special characters
            var regex = /^[a-zA-Z0-9]+$/;

            if(regex.test(username)) {
            // No special characters
            document.getElementById("username_feedback").innerHTML = "";
            username = username;

            } else {
            document.getElementById("username_feedback").innerHTML = "Username can only contain letters and numbers";
            // Special characters detected
            username = username.replace(/[^a-zA-Z0-9]/g, '');
            }

            // Inject sanitized username back into input field
            document.getElementById("username").value = username;

            checkUsernameIfExist(username)

        } else {
            document.getElementById("username_feedback").innerHTML = "";
            document.getElementById("username_available").innerHTML = "";
            document.getElementById("username_already_exist").innerHTML = "";
        }

    }


function checkUsernameIfExist(username) {
    if(username.length>0) {
    fetch('/check-username/' + username)
        .then(response => response.json())
        .then(data => {
            if(data.available) {
            console.log('username available')
            document.getElementById("username_available").innerHTML = "Username available";
            document.getElementById("username_already_exist").innerHTML = "";
            } else {
                document.getElementById("username_already_exist").innerHTML = "{{ __('Username already registered') }}";
                document.getElementById("username_available").innerHTML = "";
            }
        });
    }
}
</script>

<script>

document.getElementById('email_confirm').onpaste = function(){
    //alert('Merci de ne pas copier/coller');
    return false;
};
</script>


<script>
    changePlaceholder()
    function changePlaceholder() {
      var selectElement = document.getElementById("school_type");
      var fullNameInput = document.getElementById("fullname");
      var emailInput = document.getElementById("email");

      if (selectElement.value === "COACH") {
        fullNameInput.placeholder = "{{ __('Coach Name') }}";
        emailInput.placeholder = "{{ __('Coach Email Address') }}";
      } else if (selectElement.value === "SCHOOL") {
        fullNameInput.placeholder = "{{ __('School Name') }}";
        emailInput.placeholder = "{{ __('School Email Address') }}";
      }
    }
  </script>

<script>



$(document).ready(function () {
    $("#signup_form").submit(function(e) {
    e.preventDefault();
}).validate({
    // Specify validation rules
    rules: {
        terms_condition: "required",
        school_type: "required",
        fullname: "required",
        username: "required",
        country_id: "required",
        email: {
            required: true,
            email: true
        },
        email_confirm: {
            required: true,
            email: true,
            equalTo: "#email"
        },
        password: {
            required: true,
            minlength: 7
        }
    },
    // Specify validation error messages
    messages: {
        terms_condition: "{{__('Please accept terms and conditions')}}",
        school_type: "{{__('Please select type')}}",
        fullname: "{{ __('Please enter your full name')}}",
        username: "{{ __('Please enter your username')}}",
        country_code: "{{ __('Please select country')}}",
        password: {
            required: "{{ __('Please provide a password')}}",
            minlength: "{{ __('Your password must be at least 7 characters long')}}"
        },
        email: "{{ __('Please enter a valid email address')}}",
        email_confirm: "{{ __('Email addresses don\'t match')}}"
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

                errorModalCall('Information', "{{__('This Username was already registered by another user. Please choose an other username for your login credentials.')}}");
                loader.fadeOut("fast");

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
                        loader.fadeIn("fast");
                    },
                    success: function(data) {

                        if (data.status) {

                            $("#schoolsignupModal").modal('hide');
                            //$("#successModal").modal('show');
                            //successModalCall(data.message);
                            Swal.fire(
                            'Congratulations!',
                            data.message,
                            'success'
                            )

                            //$("#loginModal").modal('show');
                        } else {
                            //errorModalCall(GetAppMessage('error_message_text'));
                            Swal.fire(
                            'Information',
                            data.message,
                            'error'
                            )
                        }

                    }, // sucess
                    error: function (reject) {
                        loader.fadeOut("fast");
                        let errors = $.parseJSON(reject.responseText);
                        errors = errors.errors;
                        $.each(errors, function (key, val) {
                            //$("#" + key + "_error").text(val[0]);
                            errorModalCall(val[0]+ ' '+GetAppMessage('error_message_text'));
                        });
                    },
                    complete: function() {
                        loader.fadeOut("fast");
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
