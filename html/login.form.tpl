<div id="login" class="modal hide">
    <div class="modal-header">
        Login
        <button type="button" class="close" data-dismiss="modal">×</button>
    </div>
    <div class="modal-body">
        {if $wp_login}
            <div class="text-center">
                <a href="oauthlogin.php" class="btn btn-large">Sign into Ratupar</a>
                <a href="register.php" class="btn btn-large btn-success">Join Family</a>
            </div>
        {else}
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
                    <div class="control-group">
                        <div class="controls">
                            <a href="javascript:forgotPassword()">Forgot Password?</a>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">Login</button>
                        <a class="btn btn-success" href="register.php">Join Family</a>
                    </div>
                </fieldset>
            </form>
        {/if}
    </div>
</div>