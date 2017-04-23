<div class="suggest-box" suggest-id="{$suggestid}">
    Are <span class="membername_mention">{$newvalue['fathername']}</span> and <span class="membername_mention">{$newvalue['mothername']}</span> parents of <span class="membername_mention">{$suggested_to->data['membername']}</span> ?
    <span class='suggest_quest'>Is this correct?</span>
    {if $approvedonly}
    <span class="suggest_quest">Your Action</span> {$suggestionResult}
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