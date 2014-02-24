<div class="well" id="{$id}suggest" {if $force}data-forceful='1'{else}data-forceful='0'{/if}>
    {$content}
    <hr>
    <p>
        Is this correct?
    </p>
    <p>
        <button class="btn btn-primary" onclick='suggest_action(this,1)'>Yes</button>
        <button class="btn btn-primary" onclick='suggest_action(this,0)'>No</button>
    </p>
</div>