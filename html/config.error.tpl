<div class="single-dialog alert alert-error">
    <button type="button" class="close" data-dismiss="alert">x</button>
    Please set the permissions for the config.php file using the following command:<br />
    $ chmod 644 config.php<br>
    {if $done eq 1}
        config.php is Working. Permissions are ok.<br>
        <script type="text/javascript">
            setTimeout(function ()
            {
                window.location.reload();
            },3000);
        </script>
    {else}
        config.php still not working. Check the permissions of the file using $ ls -l config.php<br>
    {/if}
    Just refresh the page after setting the permissions
</div>