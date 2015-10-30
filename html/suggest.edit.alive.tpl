<div class='suggest-box'>
    {if !$newvalue}
        Is {$suggested_to->data['membername']} still alive?
    {else}
        Is {$suggested_to->data['membername']} deceased?
    {/if}
    <div>
        <button class="btn btn-success btn-small" onclick="suggest_action(this, 1);">Yes</button>
        <button class="btn btn-danger btn-small" onclick="suggest_action(this, 0);">No</button>
        <button class="btn btn-small" onclick="suggest_action(this, 2);">Don't Know</button>
    </div>
</div>