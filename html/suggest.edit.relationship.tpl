<div class='suggest-box' suggest-id="{$suggestid}">
    <div class="row">
        <div class="span12">
            {if $newvalue}   <!-- Hard coded value, assumes 1 is female -->
                Is <span class="membername_mention">{$suggested_to->data['membername']}</span> married?
            {else}
                Is <span class='membername_mention'>{$suggested_to->data['membername']}</span> single?
            {/if}
        </div>
    </div>
    <span class='suggest_quest'>Is this correct?</span>
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
</div>