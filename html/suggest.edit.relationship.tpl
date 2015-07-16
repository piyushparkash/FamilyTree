<div class='suggest-box'>
    {if $newvalue}   <!-- Hard coded value, assumes 1 is female -->
        Is {$suggested_to->data['membername']} married?
    {else}
        Is {$suggested_to->data['membername']} single?
    {/if}
    <div>
        <button class="btn btn-success btn-small" onclick="suggest_action(this, 1);">Yes</button>
        <button class="btn btn-danger btn-small" onclick="suggest_action(this, 0);">No</button>
        <button class="btn btn-small" onclick="suggest_action(this, 2);">Don't Know</button>
    </div>
</div>