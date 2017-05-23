<div id="login" class="modal fade align-items-center">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
    </div>
    <div class="modal-body">
        {if $wp_login}
            <div class="text-center">
                <a href="oauthlogin.php" class="btn btn-large">Sign into Ratupar</a>
                <a href="register.php" class="btn btn-large btn-success">Join Family</a>
            </div>
        {else}
            <form method="post" onsubmit="return login_submit()">
                    <div class="form-group">
                        <label class="col-form-label" for="login_username">Username</label>
                        <input class="form-control" type="text" id="login_username" />
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="login_password">Password</label>
                        <input class="form-control" type="password" id="login_password" />
                        <span class="form-control-feedback hidden-xs-up" id="login_error">Wrong Username or Password</p>
                    </div>
                    <div class="form-group">
                        <a href="javascript:forgotPassword()">Forgot Password?</a>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Login</button>
                        <a class="btn btn-success" href="register.php">Join Family</a>
                    </div>
            </form>
        {/if}
        </div>
        </div>
    </div>
</div>