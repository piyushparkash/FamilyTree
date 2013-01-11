<div  id="{$suggestid}suggest" class="suggest-box">
    According to {$suggestedby},<br>
    {$membername} is also a {$mnf} of {$fathername}
    <br>

    Is this change correct?&nbsp;&nbsp;&nbsp;
    <div ><button class="btn btn-success btn-small" onclick="suggest_action(this,1)">Yes</button>&nbsp;&nbsp;&nbsp;
        <button class="btn btn-danger btn-small" onclick="suggest_action(this,0)">No</button>
        <button class="btn btn-small" onclick="suggest_action(this,2)">Don't Know</button>
    </div>

</div>