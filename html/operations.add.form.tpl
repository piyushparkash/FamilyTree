<div id="operation_add" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" onsubmit="return operation_addmember_submit()">
                    <div class="form-group">
                        <label for="operation_add_name" class="col-form-label">Name:</label>
                        <input type="text" id="operation_add_name" class="form-control" />
                    </div>
                    <div class="form-group">
                        Sonof:
                        <label for="operation_add_sonof_name" class="col-form-label">
                            <span id="operation_add_sonof_name"></span>
                        </label>
                        <input type="hidden" id="operation_add_sonof_id" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="operation_add_gender" class="col-form-label">Gender:</label>
                        <select id="operation_add_gender" class="form-control">
                            <option value="0">
                                Male
                            </option>
                            <option value="1">
                                Female
                            </option>
                            </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Add</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>