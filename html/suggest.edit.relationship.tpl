<div class=card' suggest-id="{$suggestid}">
    <div class="card-block">
        <h5 class="card-text">
            <div class="row">
                <div class="span12">
                    {if $newvalue}   <!-- Hard coded value, assumes 1 is female -->
                        Is
                        <span class="membername_mention">{$suggested_to->data['membername']}</span>
                        married?
                    {else}
                        Is
                        <span class='membername_mention'>{$suggested_to->data['membername']}</span>
                        single?
                    {/if}
                </div>
            </div>
    </div>
    {if $approvedonly}
        {include file="suggestapproved.tpl"}
    {else}
        {include file="suggestprogress.tpl"}
    {/if}
</div>