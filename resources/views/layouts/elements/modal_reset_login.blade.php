<!-- RESET Modal after first logged in-->
<div class="modal fade reset-pass-modal" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="resetModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block text-center border-0">
        <h4 class="modal-title light-blue-txt gilroy-bold" id="resetModalLabel">Reset Password</h4>
        <h6 class="mb-0">Welcome!</h6>
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
              <input class="form-control" type="password" placeholder="Old Password" id="old_password" name="old_password" required>
              <div class="input-group-addon">
                <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>


          <div class="form-group">
            <div class="input-group" id="show_hide_password">
              <input class="form-control" type="password" placeholder="New Password" id="new_password" name="new_password" required>
              <div class="input-group-addon">
                <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="input-group" id="show_hide_password">
              <input class="form-control" type="password" placeholder="Confirm Password" id="confirm_password" name="confirm_password" required>
              <div class="input-group-addon">
                <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-lg btn-primary btn-block">Reset Password</button>
        </form>
      </div>
    </div>
  </div>
</div>