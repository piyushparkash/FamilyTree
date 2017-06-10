<div class='card' suggest-id="{$suggestid}">
    <div class="card-block">
        <h5 class="card-text">
            <span class='membername_mention'>{$suggested_by->data['membername']}</span> corrected name of <span
                    class='membername_mention'>{$suggested_to->data['membername']}</span>
            <table class="table table-hover ">
                <tr>
                    <th>Old Name</th>
                    <th>Corrected Name</th>
                </tr>
                <tr>
                    <td>
                        <div class="alert alert-error">{$suggested_to->data['membername']}</div>
                    </td>
                    <td>
                        <div class="alert alert-success">{$newvalue}</div>
                    </td>
                </tr>
            </table>
        </h5>

        {if $approvedonly}
            {include file="suggestapproved.tpl"}
        {else}
            {include file="suggestprogress.tpl"}
        {/if}
    </div>
</div>