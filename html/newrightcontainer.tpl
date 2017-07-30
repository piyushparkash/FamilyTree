<div id="right-container" class="card text-center" data-intro="Here you will see information about the selected member." data-step="3" data-position="left">
        <img src="assets/user_images/common.png" id="display_image" class="card-img img-fluid rounded" />
        <div class="card-block">
            <h4 class="card-title" id="display_name"></h4>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item hidden-md-up justify-content-center" id='rightcontainerhide'><button class="btn btn-primary">Hide</button>
            <li class="list-group-item justify-content-center list-group-item-action" onclick="editmember()"><span id="display_dob"></span></li>
            <li class="list-group-item justify-content-center list-group-item-action" onclick="editmember()"><span id="display_relationship"></span></li>
            <li class="list-group-item justify-content-center list-group-item-action" onclick="editmember()"><span id="display_alive"></span></li>
            <li class="list-group-item justify-content-center list-group-item-action" onclick="editmember()"><span id="display_gaon"></span></li>
            <li class="list-group-item justify-content-center list-group-item-action" onclick="editmember()"><span id="display_relation"></span></li>
            {if $authenticated}
            <div class="card-block">
                <div class="btn-group">
                    <div class="dropdown dropup">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="editMemberOptions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Edit Member
                        </button>
                        <div class="dropdown-menu" aria-labelledby="editMemberOptions">
                            <a class="dropdown-item" href="javascript:operation_addmember()">Add Child</a>
                            <a class="dropdown-item" href="javascript:deletemember()">Remove Member</a>
                            <a class="dropdown-item" href="javascript:editmember()">Edit Member</a>
                            <a class="dropdown-item" href="javascript:Vanshavali.addSpouse.showModal()" id="wifeoperation" style="display:none">Add Wife</a>
                            <a class="dropdown-item" href="javascript:Vanshavali.addSpouse.showModal()" id="husbandoperation" style="display:none">Add Husband</a>
                            <a class="dropdown-item" href="javascript:Vanshavali.addParents.showModal()" id="parentOperation">Add Parents</a>
                        </div>
                    </div>
                </div>
                <button class='btn btn-success' style="display: none;" onclick='viewfamily(this)' id="girlfamilybutton">View Family</button>

            </div>
            {/if}
        </ul>
</div>