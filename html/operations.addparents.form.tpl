<div id="operation_addParents"  class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Parents</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" onsubmit="return Vanshavali.addParents.submit(this)">
                    <div class="form-group">
                        <label for="operation_addParents_Fathername" class="col-form-label">Father's Name:</label>
                        <input type="text" required id="operation_addParents_Fathername" class="form-control"/>
                    </div>

                    <div class="form-group">
                        <label for="operation_addParents_Mothersname" class="col-form-label">Mother's Name</label>
                        <input type="text" required id="operation_addParents_Mothername" class="form-control"/>
                    </div>

                    <div class="form-group">
                        <label for="operation_addParents_parentsof" class="col-form-label">Parents of:</label>
                        <input type="text" disabled id="operation_addParents_parentsof" class="form-control" />
                        <input type="hidden" id="operation_addParents_parentsofid" />
                    </div>

                    <button class="btn btn-primary" type="submit">Save</button>
                    <button class="btn btn-primary" type="reset">Reset</button>
                    <button class="btn btn-primary" onclick='javascript:Vanshavali.addParents.hideModal()' type="button">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>