<div class='suggest-box container-fluid' suggest-id="{$suggestid}">
    Was <span class='membername_mention'>{$suggested_to->data['membername']}</span> born on {$newvalue}?
    <p class='suggest_quest'>Is this correct?</p>
    {if $approvedonly}
        <div class="row-fluid">
            <div class="span4">
                <span class="suggest_quest">Your Action:</span>
            </div>
            <div class="span4">
                {$userAction}
            </div>
        </div>

        <div class="row-fluid">
            <div class="span4">
                <span class="suggest_quest">Suggestion Result:</span>
            </div>
            <div class="span4">
                {$suggestionResult}
            </div>
        </div>
    {else}
        <div class="row">
            <div class="span4">
                <div class="progress progress-success">
                    <div class="bar" style="width: {$yespercent}%;"></div>
                </div>
            </div>
            <div class="span2">
                <button class="btn btn-success" onclick="suggest_action(this, 1);">Yes</button>
            </div>
        </div>
        <div class="row">
            <div class="span4">
                <div class="progress progress-danger">
                    <div class="bar" style="width: {$nopercent}%;"></div>
                </div>
            </div>
            <div class="span2">
                <button class="btn btn-danger" onclick="suggest_action(this, 0);">No</button>
            </div>
        </div>

        <div class="row">
            <div class="span4">
                <div class="progress progress-info">
                    <div class="bar" style="width: {$dontknowpercent}%;"></div>
                </div>
            </div>
            <div class="span2">
                <button class="btn" onclick="suggest_action(this, 2);">Don't Know</button>
            </div>
        </div>
    {/if}
</div>