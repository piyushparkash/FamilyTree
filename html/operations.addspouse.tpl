<div id="operation_addSpouse" class="modal hide">
    <div class="modal-header">
        Add Spouse
        <button type="button" class="close" data-dismiss="modal">×</button>
    </div>
    <div class="modal-body">
        <form method="post" onsubmit="return Vanshavali.addSpouse.submit()" class="form-horizontal" >
            <fieldset>
                <div class="control-group">
                    <label for="operation_addSpouse_name" class="control-label">Spouse Name:</label>
                    <div class="controls">
                        <input type="text" id="operation_addSpouse_name" />
                    </div>
                </div>
                <div class="control-group">
                    <label for="operation_addSpouse_otherSpousename" class="control-label">Spouse Of:</label>
                    <div class="controls">
                        <input type="text" disabled id="operation_addSpouse_otherSpousename" />
                        <input type="hidden" id="operation_addSpouse_otherSpouseID" />
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Add</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>