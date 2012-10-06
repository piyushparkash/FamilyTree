<div id="login" class="modal hide">
    <div class="modal-header">
        Login
        <button type="button" class="close" data-dismiss="modal">×</button>
    </div>
    <div class="modal-body">
        <form method="post" onsubmit="return login_submit()" class="form-horizontal well">
            <fieldset>
                <div class="control-group">
                    <label class="control-label" for="login_username">Username</label>
                    <div class="controls">
                        <input type="text" id="login_username" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="login_password">Password</label>
                    <div class="controls">
                        <input type="password" id="login_password" />
                        <span class="help-block hide" id="login_error">Wrong Username or Password</p>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Login</button>
                    <button class="btn" onclick="register()">Join Family</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>