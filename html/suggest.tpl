<div id="suggest" class="modal hide">
    <div class="modal-header">
        <b>Complete Your Family!</b>
    </div>
    <div class="modal-body">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#suggest-data" data-toggle="tab">Pending Approval</a></li>
            <li><a href="#approvedsuggest-data" onclick="approvedSuggestion()" data-toggle="tab">My Approvals</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="suggest-data">
                <p>These are my pending approvals</p>
            </div>
            <div class="tab-pane" id="approvedsuggest-data">
                <p>These are my approved ones!</p>
            </div>
        </div>
        <div class="modal-footer">
            <a href="" class="btn" data-dismiss="modal">Close</a>
        </div>
    </div>