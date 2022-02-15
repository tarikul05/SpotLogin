<!-- Forgot Password Modal -->
<div class="modal fade login-signup-modal" id="forgotPasswordModal" tabindex="-1" aria-hidden="true" aria-labelledby="forgotPasswordModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-block text-center border-0">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title text-primary font-weight-bold" id="forgotPasswordModalLabel">Forgot Password</h3>
                
            </div>
            <div class="modal-body" style="max-width: 375px; margin: 0 auto;padding-top: 0;">
                <form id="forgot_password_form" name="forgot_password_form" method="POST" action="#">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Username" id="forgot_password_username" name="forgot_password_username" required>
                </div>
                <button type="submit" class="btn btn-lg btn-primary btn-block">Submit</button>
                </form>
                
            </div>
        </div>
    </div>
</div>