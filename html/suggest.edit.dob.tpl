<div class='card' suggest-id="{$suggestid}">
    <div class="card-block">
        <h5 class="card-text">
            Was <span class='membername_mention'>{$suggested_to->data['membername']}</span> born on {$newvalue}?
        </h5>
        {if $approvedonly}
            {include file="suggestapproved.tpl"}
        {else}
            {include file="suggestprogress.tpl"}
        {/if}
    </div>
</div>