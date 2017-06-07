<div id="operation_remove" class="modal fade">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Delete Member </h5>
    </div>
    <div class="modal-body">
        <p>Are you sure that <span id="operation_remove_son"></span> is not child of <span id="operation_remove_father"></span>?</p>
        <div class="alert alert-danger"><strong>Warning!</strong> All members below it will be removed!</div>
        <input type="hidden" id="operation_remove_son_id" />
    </div>
    <div class="modal-footer">
        <button class="btn btn-danger" onclick="deletemember_submit()">Delete</button>
        <button class='btn' onclick='$("#operation_remove").modal("hide")'>Cancel</button>
    </div>
    </div>
    </div>
</div>