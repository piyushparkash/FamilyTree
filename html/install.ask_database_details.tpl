<style type="text/css">
    body {
        overflow: scroll;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col display-4 text-center mb-2">
            Family Tree Installation
        </div>
    </div>
    <div class="row">
        <div class="offset-3 col-6 offset-3">
            <form name="ask_database_name" method="post" class="bg-faded p-3"
                  action="index.php?mode=ask_database_name&sub=2">
                {if isset($error)}
                    {include file="error_low.tpl"}
                {/if}
                <h4>Database Details</h4>
                <div class="form-group">
                    <label for="database_name" class="col-form-label">Database name*</label>
                    <input type="text" name="database_name" class="form-control" id="database_name"
                           placeholder="Database Name" required/>
                </div>
                <div class="form-group">
                    <label for="database_host" class="col-form-label">Hostname*</label>
                    <input type="text" name="database_host" class="form-control" id="database_host"
                           placeholder="Database Host" required/>
                </div>
                <div class="form-group">
                    <label for="database_username" class="col-form-label">Database Username*</label>
                    <input type="text" name="database_username" class="form-control" id="database_username"
                           placeholder="Database Username" required/>
                </div>
                <div class="form-group">
                    <label for="database_password" class="col-form-label">Database Password*</label>
                    <input type="password" name="database_password" class="form-control" id="database_name"
                           placeholder="Database Password" required/>
                </div>
                <div class="form-group">
                    <label for="admin_email" class="col-form-labels">Admin Email*</label>
                    <input type="email" name="admin_email" id="admin_email" class="form-control"
                           placeholder="Admin Email" required/>
                </div>

                <h4>Wordpress Details (Optional)</h4>
                <div class="form-group">
                    <label for="consumer_key" class="col-form-label">Consumer Key</label>
                    <input type="text" name="consumer_key" id="consumer_key" class="form-control"
                           placeholder="Wordpress Consumer Key"/>
                </div>
                <div class="form-group">
                    <label for="consumer_key_secret" class="col-form-label">Consumer Key Secret</label>
                    <input type="text" name="consumer_key_secret" id="consumer_key_secret" class="form-control"
                           placeholder="Wordpress Consumer Key Secret"/>
                </div>
                <div class="form-group">
                    <label for="end_point" class="control-label">Wordpress End Point</label>
                    <input type="url" name="end_point" id="end_point" class="form-control"
                           placeholder="Wordpress End Point"/>
                </div>
                <div class="form-group">
                    <label for="auth_end_point" class="col-form-label">Auth End Point</label>
                    <input type="url" name="auth_end_point" id="auth_end_point" class="form-control"
                           placeholder="Auth End Point"/>
                </div>
                <div class="form-group">
                    <label for="access_end_point" class="col-form-label">Access End Point</label>
                    <input type="url" name="access_end_point" id="access_end_point" class="form-control"
                           placeholder="Access End Point"/>
                </div>

                <input type="submit" name="ask_database_name" id="submit" class="btn btn-primary"/>

            </form>
        </div>
    </div>
</div>

{include file="footer.tpl"}