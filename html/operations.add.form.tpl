<div id="operation_add" class="modal hide">
    <div class="modal-header">
        Add Member
        <button type="button" class="close" data-dismiss="modal">×</button>
    </div>
    <div class="modal-body">
        <form method="post" onsubmit="return operation_addmember_submit()" class="form-horizontal" >
            <fieldset>
                <div class="control-group">
                    <label for="operation_add_name" class="control-label">Name:</label>
                    <div class="controls">
                        <input type="text" id="operation_add_name" />
                    </div>
                </div>
                <div class="control-group">
                    <label for="operation_add_sonof_name" class="control-label">Sonof:</label>
                    <div class="controls">
                        <input type="hidden" id="operation_add_sonof_id" />
                    </div>
                </div>
                <div class="control-group">
                    <label for="operation_add_gender" class="control-label">Gender:</label>
                    <div class="controls">
                        <select id="operation_add_gender">
                            <option value="1">
                                Male
                            </option>
                            <option value="0">
                                Female
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn-primary" type="submit">Add</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>