<div id="suggest" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complete Your Family!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs nav-fill" role="tablist">
                    <li class="nav-item"><a href="#suggest-data" class="nav-link active" data-toggle="tab" role="tab">Pending Approval</a></li>
                    <li class="nav-item"><a href="#approvedsuggest-data" onclick="approvedSuggestion()" class="nav-link" data-toggle="tab" role="tab">My Approvals</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel" id="suggest-data">
                        <p>These are my pending approvals</p>
                    </div>
                    <div class="tab-pane" id="approvedsuggest-data" role="tabpanel">
                        <p>These are my approved ones!</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="" class="btn" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>