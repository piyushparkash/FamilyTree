<div id="forgotPassword" class="modal hide">
    <div class="modal-header">
        Forgot Password
        <button type="button" class="close" data-dismiss="modal">×</button>
    </div>
    <div class="modal-body">
        <form method="post" onsubmit="return forgotPassword_submit(this)" class="form-horizontal well">
            <fieldset>
                <div class="control-group">
                    <label class="control-label" for="Email Or Username">Username or Email Address</label>
                    <div class="controls">
                        <input type="text" id="emailoname" name="emailoname"/>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Reset Password</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>