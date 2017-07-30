<div class="card" suggest-id="{$suggestid}">
    <div class="card-block">
        <h5 class="card-text">
            Are <span class="membername_mention">{$newvalue['fathername']}</span> and <span
                    class="membername_mention">{$newvalue['mothername']}</span> parents of <span
                    class="membername_mention">{$suggested_to->data['membername']}</span>?
        </h5>
        {if $approvedonly}
            <div class="row">
                <div class="col d-flex justify-content-end">
                    <span class="suggest_quest">Your Action:</span>
                </div>
                <div class="col d-flex justify-content-start">
                    <span class="badge badge-default"> {$userAction}</span>
                </div>
                <div class="col d-flex justify-content-center">
                    <span class="suggest_quest">Suggestion Result:</span>
                </div>
                <div class="col d-flex justify-content-start">
                    <span class="badge badge-default">{$suggestionResult}</span>
                </div>
            </div>
        {else}
            <div class="row">
                <div class="col-10">
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {$yespercent}%;" role="progressbar"
                             aria-valuenow="{$yespercent}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-2">
                    <button class="btn btn-success" onclick="suggest_action(this, 1);">Yes</button>
                </div>
            </div>
            <div class="row">
                <div class="col-10">
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {$nopercent}%;" role="progressbar"
                             aria-valuenow="{$nopercent}" aria-valuemin="0" aria-valuemax="100">{$nopercent}</div>
                    </div>
                </div>
                <div class="col-2">
                    <button class="btn btn-danger" onclick="suggest_action(this, 0);">No</button>
                </div>
            </div>
            <div class="row">
                <div class="col-10">
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: {$dontknowpercent}%;" role="progressbar"
                             aria-valuenow="{$dontknowpercent}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-2">
                    <button class="btn" onclick="suggest_action(this, 2);">Don't Know</button>
                </div>
            </div>
        {/if}
    </div>
</div>