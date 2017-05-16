<div id="right-container-something" data-intro="Here you will see information about the selected member." data-step="3" data-position="left">
    <div class="thumbnail">
        <img src="assets/user_images/{$userimage}" id="display_image" />
        <div class="caption">
            <h3 id="display_name" class="text-center"></h3>
                <div class="row-fluid">
                    <div class="span6">
                        Date Of Birth:
                    </div>
                    <div class="span6">
                        <span id="display_dob"></span>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        Relationship Status:
                    </div>
                    <div class="span6">
                        <span id="display_relationship"></span>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        Living/Deceased:
                    </div>
                    <div class="span6">
                        <span id="display_alive"></span>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        Village(Gaon):
                    </div>
                    <div class="span6">
                        <span id="display_gaon"></span>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        Relation With You:
                    </div>
                    <div class="span6">
                        <span id="display_relation"></span>
                    </div>
                </div>
                {if $authenticated}
                    <div class="row-fluid">
                        <div class='span6' style="text-align: center">
                            <div class="btn-group dropup">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" data-intro="Click on member you want to edit. And use this to add/remove/modify any member"
                                   data-step="4" data-position="left">
                                    Edit Member
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:operation_addmember()">Add Son/Daughter</a></li>
                                    <li><a href="javascript:deletemember()">Remove Member</a></li>
                                    <li><a href="javascript:editmember()">Edit Member</a></li>
                                    <li><a href="javascript:addwife()" id='wifeoperation' style="display:none">Add Wife</a></li>
                                    <li><a href="javascript:addhusband()" id="husbandoperation" style="display:none"> Add Husband</a></li>
                                    <li><a href="javascript:Vanshavali.addParents.showModal()" id="parentOperation">Add Parents</a></li>
                                </ul>
                            </div>
                        </div>
                        <div id='girlfamilybutton' class="span6" >
                            <button class='btn btn-success' onclick='viewfamily(this)'>View Family</button>
                        </div>
                    </div>
                {/if}
            
        </div>
    </div>
</div>