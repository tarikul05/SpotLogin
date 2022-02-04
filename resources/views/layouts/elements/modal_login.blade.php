<!-- Modal -->
<div class="modal fade login-signup-modal" id="loginModal" tabindex="-1" aria-hidden="true" aria-labelledby="loginModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block text-center border-0">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
        <h3 class="modal-title light-blue-txt gilroy-bold" id="loginModalLabel">Sign in</h3>
        <p class="mb-0">Welcome back!</p>
      </div>
      <div class="modal-body" style="max-width: 375px; margin: 0 auto;padding-top: 0;">
        <form id="login_form" name="login_form" method="POST" action="#">

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
          <div style="margin-bottom:10px;"><small><a class="forgot_password_btn" data-toggle="modal" data-target="#forgotPasswordModal">Forgot password?</a></small></div>
          <button type="submit" class="btn btn-lg btn-primary btn-block">Sign in</button>
        </form>
        <!--
              <div style="text-align:center;margin-top:10px;">
                  <p>If you don't have an account, please <a class="signup_btn" href="#signupModal" data-toggle="modal">Sign Up</a>
                  </p>
              </div>
    -->
      </div>
    </div>
  </div>
</div>