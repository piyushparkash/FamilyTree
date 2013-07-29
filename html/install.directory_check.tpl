<div class='single-dialog well'>
    <h3>Directory Permissions</h3>
    <p>Please provide write permission to the following directory. </p>
    <table class='table table-bordered'>
        <tr>
            <td>{$dir}</td>
            <td>
            {if $main}
                <div class="alert alert-success">Writable</div>
            {else}
                <div class="alert alert-error">Unwritable</div>
            {/if}
            </td>
            <td>Give Permission to the main Family Tree Directory</td>
        </tr>
        <tr>
            <td>cache/</td>
            <td>
            {if $cache}
                <div class="alert alert-success">Writable</div>
            {else}
                <div class="alert alert-error">Unwritable</div>
            {/if}
            </td>
            <td>Give Permission to Template Cache directory (template/cache)</td>
        </tr>
        <tr>
            <td>compile/</td>
            <td>
            {if $compile}
                <div class="alert alert-success">Writable</div>
            {else}
                <div class="alert alert-error">Unwritable</div>
            {/if}            
            </td>
            <td>Give Permission to Template Compilation directory (template/compile)</td>
        </tr>
    </table>
            <div class='form-actions'><a href='index.php?ask_database_name' class='btn btn-primary'>Next</a></div>
</div>
{include file="footer.tpl"}