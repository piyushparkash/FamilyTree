<div class="card" suggest-id="{$suggestid}">
    <div class="card-block">
        <h5>Is <span class='membername_mention'>{$newvalue}</span> {if !$sod}son{else}daughter{/if} of <span class='membername_mention'>{$suggested_to->data['membername']}</span>?</h5>
        <p class='card-text'>Is this correct?</p>
        {if $approvedonly}
        <div class="row">
            <div class="col-4">
                <span class="suggest_quest">Your Action:</span>
            </div>
            <div class="col-auto">
                {$userAction}
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <span class="suggest_quest">Suggestion Result:</span>
            </div>
            <div class="col-auto">
                {$suggestionResult}
            </div>
        </div>
        {else}
        <div class="row">
            <div class="col-4">
                <div class="progress progress-success">
                    <div class="bar" style="width: {$yespercent}%;"></div>
                </div>
            </div>
            <div class="col-2">
                <button class="btn btn-success" onclick="suggest_action(this, 1);">Yes</button>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <div class="progress progress-danger">
                    <div class="bar" style="width: {$nopercent}%;"></div>
                </div>
            </div>
            <div class="col-2">
                <button class="btn btn-danger" onclick="suggest_action(this, 0);">No</button>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <div class="progress progress-info">
                    <div class="bar" style="width: {$dontknowpercent}%;"></div>
                </div>
            </div>
            <div class="col-2">
                <button class="btn" onclick="suggest_action(this, 2);">Don't Know</button>
            </div>
        </div>
        {/if}
    </div>
</div>