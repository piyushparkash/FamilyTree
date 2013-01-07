<div  id="{$suggestid}suggest" class="suggest-box">
    According to {$suggestedby},<br>
    {$membername} is not a son of {$fathername}
    <br>

    Is {$membername} son of {$fathername}
    <div><button class="btn btn-success btn-small" onclick="suggest_action(this,1)">Yes</button>&nbsp;&nbsp;&nbsp;
        <button class="btn btn-dangerbtn-small" onclick="suggest_action(this,0)">No</button>
        <button class="btn btn-small" onclick="suggest_action(this,2)">Don't Know</button>
    </div>

</div>