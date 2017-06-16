<div id="operation_addSpouse" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Spouse</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form method="post" onsubmit="return Vanshavali.addSpouse.submit(event)" >
                    <div class="form-group">
                        <label for="operation_addSpouse_name" class="col-form-label">Spouse Name:</label>
                        <input type="text" id="operation_addSpouse_name" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="operation_addSpouse_otherSpousename" class="col-form-label">Spouse Of:</label>
                        <input type="text" disabled id="operation_addSpouse_otherSpousename" class="form-control" />
                        <input type="hidden" id="operation_addSpouse_otherSpouseID" />
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Add Spouse</button>
                    </div>
            </form>
        </div>
        </div>
    </div>
</div>
</div>