<div  id="suggest{$suggestid}">
    According to {$suggestedby},<br>
    {$membername} is also a son of {$fathername}
    <br>

    Is this change correct?&nbsp;&nbsp;&nbsp;
    <div style="float:right"><button class="btn btn-success" onclick="suggest_action(this,1)">Yes</button>&nbsp;&nbsp;&nbsp;
        <button class="btn btn-danger" onclick="suggest_action(this,0)">No</button>
        <button class="btn" onclick="suggest_action(this,2)">Don't Know</button>
    </div>

</div>