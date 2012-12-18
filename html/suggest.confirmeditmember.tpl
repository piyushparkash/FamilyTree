<div>
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
    <div style="float:right"><button class="btn btn-success">Yes</button>&nbsp;&nbsp;&nbsp;
        <button class="btn btn-danger">No</button></div>
</div>