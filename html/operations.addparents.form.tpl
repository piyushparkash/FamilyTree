<form method="post" id="operation_addParents" onsubmit="return Vanshavali.addParents.submit(this)" class="hide well">
    <label for="operation_addParents_Fathername" class="control-label">Father's Name:</label>
    <input type="text" required id="operation_addParents_Fathername" />
    <label for="operation_addParents_Mothersname" class="control-label">Mother's Name</label>
    <input type="text" required id="operation_addParents_Mothername" />
    <label for="operation_addParents_parentsof" class="control-label">Parents of:</label>
    <input type="text" disabled id="operation_addParents_parentsof" />
    <input type="hidden" id="operation_addParents_parentsofid" />
    <br />
    <button class="btn btn-primary" type="submit">Save</button>
    <button class="btn btn-primary" type="reset">Reset</button>
    <button class="btn btn-primary" onclick='javascript:Vanshavali.addParents.hideModal()' type="button">Cancel</button>
</form>