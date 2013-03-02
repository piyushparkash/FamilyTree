<div id="{$suggestid}suggest" class="suggest-box">
    {$suggestedby} changed the following details of {$membername}<br>
    {if $changed_name}
        Name:&nbsp;{$old_name}&nbsp;&nbsp;=>&nbsp;&nbsp;{$new_name}<br>
    {/if}
    {if $changed_gender}
        Gender:&nbsp;{$old_gender}&nbsp;&nbsp;=>&nbsp;&nbsp;{$new_gender}<br>
    {/if}
    {if $changed_relationship}
        Relationship Status:&nbsp{$old_relationship}&nbsp;&nbsp;=>&nbsp;&nbsp;{$new_relationship}<br>
    {/if}
    {if $changed_dob}
        Date of Birth:&nbsp;{$old_dob}&nbsp;&nbsp;=>&nbsp;&nbsp;{$new_dob}<br>
    {/if}
    {if $changed_alive}
        Alive:&nbsp;{$old_alive}&nbsp;&nbsp;=>&nbsp;&nbsp;{$new_alive}<br>
    {/if}
    Is this change correct?&nbsp;&nbsp;&nbsp;
    <div ><button class="btn btn-small btn-success" onclick="suggest_action(this,1)">Yes</button>&nbsp;&nbsp;&nbsp;
        <button class="btn btn-small btn-danger" onclick="suggest_action(this,0)">No</button>
        <button class="btn btn-small" onclick="suggest_action(this,2)">Don't Know</button>
    </div>
        
</div>