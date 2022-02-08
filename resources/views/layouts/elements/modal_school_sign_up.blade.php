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
                          <option value="COACH">Coach</option>
                          <option value="SCHOOL">School</option>
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
                      <label><input type="checkbox" id="terms_condition" name="terms_condition" required> I agree with the terms and conditions</label>
                  </div>
                  <button type="submit" class="btn btn-lg btn-primary btn-block">Create an account</button>
              </form>
              
              <div style="text-align:center;margin-top:10px;">
                  <p>Already have an account? <a class="login_btn" href="#loginModal" data-bs-toggle="modal" data-bs-target="#loginModal">Sign in</a> now</p>
              </div>
          </div>
      </div>
  </div>
</div>