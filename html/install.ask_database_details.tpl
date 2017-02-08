<style type="text/css">
    body
    {
        overflow: scroll;
    }
</style>
<form name="ask_database_name" method="post" class="single-dialog form-horizontal well" action="index.php?mode=ask_database_name&sub=2">
    {if isset($error)}
        {include file="error_low.tpl"}
    {/if}
    <legend>Database Details</legend>
    <div class="control-group">
        <label for="database_name" class="control-label">Database name*</label>
        <div class=controls>
            <input type="text" name="database_name" id="database_name" placeholder="Database Name" required />
        </div>
    </div>
    <div class="control-group">
        <label for="database_host" class="control-label">Hostname*</label>
        <div class=controls>
            <input type="text" name="database_host" id="database_host" placeholder="Database Host" required/>
        </div>
    </div>
    <div class="control-group">
        <label for="database_username" class="control-label">Database Username*</label>
        <div class=controls>
            <input type="text" name="database_username" id="database_username" placeholder="Database Username" required/>
        </div>
    </div>
    <div class="control-group">
        <label for="database_password" class="control-label">Database Password*</label>
        <div class=controls>
            <input type="password" name="database_password" id="database_name" placeholder="Database Password" required/>
        </div>
    </div>
    <div class="control-group">
        <label for="admin_email" class="control-label">Admin Email*</label>
        <div class="controls">
            <input type="email" name="admin_email" id="admin_email" placeholder="Admin Email" required/>
        </div>
    </div>

    <legend>Wordpress Details (Optional)</legend>
    <div class="control-group">
        <label for="consumer_key" class="control-label">Consumer Key</label>
        <div class="controls">
            <input type="text" name="consumer_key" id="consumer_key" placeholder="Wordpress Consumer Key" />
        </div>
    </div>
    <div class="control-group">
        <label for="consumer_key_secret" class="control-label">Consumer Key Secret</label>
        <div class="controls">
            <input type="text" name="consumer_key_secret" id="consumer_key_secret" placeholder="Wordpress Consumer Key Secret" />
        </div>
    </div>
    <div class="control-group">
        <label for="end_point" class="control-label">Wordpress End Point</label>
        <div class="controls">
            <input type="url" name="end_point" id="end_point" placeholder="Wordpress End Point" />
        </div>
    </div>
    <div class="control-group">
        <label for="auth_end_point" class="control-label">Auth End Point</label>
        <div class="controls">
            <input type="url" name="auth_end_point" id="auth_end_point" placeholder="Auth End Point" />
        </div>
    </div>
    <div class="control-group">
        <label for="access_end_point" class="control-label">Access End Point</label>
        <div class="controls">
            <input type="url" name="access_end_point" id="access_end_point" placeholder="Access End Point" />
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" name="ask_database_name" id="submit" class="btn btn-primary" />
    </div>
</form>
{include file="footer.tpl"}