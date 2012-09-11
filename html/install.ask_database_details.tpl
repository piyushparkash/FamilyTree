{include file="header.tpl"}
<form name="ask_database_name" method="post" class="well form-horizontal" action="install.php?mode=ask_database_name&sub=2">
    <div class="control-group">
        <label for="database_name" class="control_label">Database name:</label>
        <div class=controls>
            <input type="text" name="database_name" id="database_name" placeholder="Database Name" />
        </div>
    </div>
    <div class="control-group">
        <label for="database_host" class="control_label">Hostname:</label>
        <div class=controls>
            <input type="text" name="database_host" id="database_host" placeholder="Database Host" />
        </div>
    </div>
    <div class="control-group">
        <label for="database_username" class="control_label">Database Username</label>
        <div class=controls>
            <input type="text" name="database_username" id="database_username" placeholder="Database Username" />
        </div>
    </div>
    <div class="control-group">
        <label for="database_password" class="control_label">Database Password:</label>
        <div class=controls>
            <input type="password" name="database_password" id="database_name" placeholder="Database Password" />
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" name="ask_database_name" id="submit" class="btn btn-primary"/>
    </div>
</form>
{include file="footer.tpl"}