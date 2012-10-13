<div id="operation_remove" class="modal hide">
    <div class="modal-header">
        Delete Member
    </div>
    <div class="modal-body">
        Are you sure that <span id="operation_remove_son"></span> is not child of <span id="operation_remove_father"></span>?
        <div class="alert"><strong>Warning!</strong> All members below it will be removed!</div>
        <input type="hidden" id="operation_remove_son_id" />
    </div>
    <div class="modal-footer">
        <button class="btn btn-danger" onclick="deletemember_submit()">Delete</button>
        <button class='btn' onclick='$("#operation_remove").modal("hide")'>Cancel</button>
    </div>
    
</div>