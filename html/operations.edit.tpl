<div class="modal fade" id="operation_edit">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Edit Member
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form method="post" onsubmit="return editmember_submit()">

                    <div class="form-group">
                        <label for="operation_edit_name" class="col-form-label">Name:</label>
                        <input type="text" id="operation_edit_name" class="form-control" />
                        <input type="hidden" id="operation_edit_id" />
                    </div>

                    <div class="form-group">
                        <label for="operation_edit_gender" class="col-form-label">Gender:</label>
                        <select id="operation_edit_gender" class="form-control">
                            <option value="0">
                                Male
                            </option>
                            <option value="1">
                                Female
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="operation_edit_relationship" class="col-form-label">Relationship Status:</label>
                        <select id="operation_edit_relationship" class="form-control">
                            <option value="0">
                                Single
                            </option>
                            <option value="1">
                                Married
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="operation_edit_dob" class="col-form-label">Date of Birth</label>
                        <input type="text" id="operation_edit_dob" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label for="operation_edit_alive" class="col-form-label">Alive</label>
                        <select id="operation_edit_alive" class="form-control">
                            <option value="1">
                                Yes
                            </option>
                            <option value="0">
                                No
                            </option>
                        </select>
                    </div>


                    <button class="btn btn-success" type="submit">Add</button>
                    <button class="btn" onclick="$('#operation_edit').modal('hide')" type="button">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>