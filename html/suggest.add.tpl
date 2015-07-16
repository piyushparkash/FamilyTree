<div class="suggest-box">
    Is {$newvalue} {if !$sod}son{else}daughter{/if} of {$suggested_to->data['membername']} ? Reply Fast and in yes or no
    <div><button class="btn btn-success btn-small" onclick="suggest_action(this, 1)">Yes</button>&nbsp;&nbsp;&nbsp;
        <button class="btn btn-danger btn-small" onclick="suggest_action(this, 0)">No</button>
        <button class="btn btn-small" onclick="suggest_action(this, 2)">Don't Know</button>
    </div>
</div>