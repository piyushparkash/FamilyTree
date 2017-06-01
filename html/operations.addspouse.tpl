<div id="operation_addSpouse" class="modal hide">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Spouse</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <div class="modal-body">
            <form method="post" onsubmit="return Vanshavali.addSpouse.submit()" class="form-horizontal" >
                    <div class="form-group">
                        <label for="operation_addSpouse_name" class="control-label">Spouse Name:</label>
                        <div class="controls">
                            <input type="text" id="operation_addSpouse_name" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="operation_addSpouse_otherSpousename" class="control-label">Spouse Of:</label>
                        <div class="controls">
                            <input type="text" disabled id="operation_addSpouse_otherSpousename" />
                            <input type="hidden" id="operation_addSpouse_otherSpouseID" />
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