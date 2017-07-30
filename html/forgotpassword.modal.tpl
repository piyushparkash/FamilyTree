<div id="forgotPassword" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Forgot Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" onsubmit="return forgotPassword_submit(this, event);" class="form-horizontal well
                    <div class=" form-group ">
                        <label for="emailoname ">Username or Email Address</label>
                        <input type="text " id="emailoname " name="emailoname " class="form-control " />
                        <div class="form-control-feedback hidden-xs-up">Success! You've done it.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit ">Reset Password</button>
            </div>
        </div>
    </div>
</div>