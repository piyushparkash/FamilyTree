<div id="right-container" data-intro="Here you will see information about the selected member." data-step="3" data-position="left">

    <fieldset>
        <div class="username_container">
            <div class="span1" >
                <img src="assets/user_images/{$userimage}" style="width: 50px; height: 50px;" id="display_image"/>
            </div>
            <div class="span3" id="display_name">
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- The div of the username ends her. Now the div of the details -->

        <div class="otherdetails_container">
            <div class="span4">
                <div class="row" style="text-align: right">
                    <span class="span2">Date Of Birth:</span>
                    <div class="span2">
                        <span id="display_dob"></span>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="row" style="text-align: right">
                    <span class="span2">Relationship Status:</span>
                    <div class="span2">
                        <span id="display_relationship"></span>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="row" style="text-align: right">
                    <span class="span2">Living/Deceased:</span>
                    <div class="span2">
                        <span id="display_alive"></span>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="row" style="text-align:right">
                    <span class="span2">Village (Gaon):</span>
                    <div class="span2">
                        <span id="display_gaon"></span>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="row" style="text-align: right">
                    <span class="span2">Relation with You:</span>
                    <div class="span2">
                        <span id="display_relation"></span>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>      

        {if $authenticated}
            <div class='span4' style="text-align: center">
                <div class="btn-group">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" data-intro="Click on member you want to edit. And use this to add/remove/modify any member" data-step="4" data-position="left">
                        Edit Member
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:operation_addmember()">Add Son/Daughter</a></li>
                        <li><a href="javascript:deletemember()">Remove Member</a></li>
                        <li><a href="javascript:editmember()">Edit Member</a></li>
                        <li><a href="javascript:addwife()" id='wifeoperation' style="display:none" >Add Wife</a>
                        <li><a href="javascript:addhusband()" id="husbandoperation" style="display:none" > Add Husband</a></li>
                    </ul>
                </div>
            </div>


        {/if}

</fieldset>
<div style="text-align:center; margin-top:20px;" id='girlfamilybutton' class='hide'>
    <button class='btn btn-success' onclick='viewfamily(this)'>View Family</button>
</div>
{if $authenticated}
    <form method="post" onsubmit="return operation_addmember_submit()" class="well hide" style='margin:10px' id="operation_add" >
        <label for="operation_add_name" class="control-label">Name:</label>
        <input type="text" id="operation_add_name" />
        <label for="operation_add_sonof_name" class="control-label" >Child of:&nbsp;
            <span id='operation_add_sonof_name'></span>
        </label>
        <input type="hidden" id="operation_add_sonof_id" />
        <label for="operation_add_gender" class="control-label">Gender:</label>
        <select id="operation_add_gender">
            <option value="0">
                Male
            </option>
            <option value="1">
                Female
            </option>
        </select><br>
        <button class="btn btn-success" type="submit">Add</button>
        <button class="btn" onclick="$('#operation_add').slideUp()" type="button">Cancel</button>
        <button class="btn" type="reset">Reset</button>
    </form>

    <form method="post" onsubmit="return operation_addwife_submit()" class="well hide" style='margin:10px' id="operation_addwife" >
        <label for="operation_addwife_name" class="control-label">Name:</label>
        <input type="text" id="operation_addwife_name" />
        <label for="operation_addwife_husband_name" class="control-label" >Husband:&nbsp;&nbsp;
            <span id='operation_addwife_husband_name'></span>
        </label>
        <input type="hidden" id="operation_addwife_husband_id" /><br />
        <button class="btn btn-success" type="submit">Add</button>
        <button class="btn" onclick="$('#operation_addwife').slideUp()" type="button">Cancel</button>
        <button class="btn" type="reset">Reset</button>
    </form>


    <form method="post" onsubmit="return operation_addhusband_submit()" class="well hide" style='margin:10px' id="operation_addhusband" >
        <label for="operation_addhusband_name" class="control-label">Name:</label>
        <input type="text" id="operation_addhusband_name" />
        <label for="operation_addhusband_wife_name" class="control-label" >Wife:&nbsp;&nbsp;
            <span id='operation_addhusband_wife_name'></span>
        </label>
        <input type="hidden" id="operation_addhusband_wife_id" /><br />
        <button class="btn btn-success" type="submit">Add</button>
        <button class="btn" onclick="$('#operation_addhusband').slideUp()" type="button">Cancel</button>
        <button class="btn" type="reset">Reset</button>
    </form>

    <form method="post" onsubmit="return editmember_submit()" class="well hide" style='margin:10px' id="operation_edit" >
        <label for="operation_edit_name" class="control-label">Name:</label>
        <input type="text" id="operation_edit_name" />
        <input type="hidden" id="operation_edit_id" />
        <label for="operation_edit_gender" class="control-label">Gender:</label>
        <select id="operation_edit_gender">
            <option value="0">
                Male
            </option>
            <option value="1">
                Female
            </option>
        </select>
        <label for="operation_edit_relationship" class="control-label">Relationship Status:</label>
        <select id="operation_edit_relationship">
            <option value="0">
                Single
            </option>
            <option value="1">
                Married
            </option>
        </select>
        <label for="operation_edit_dob" class="control-label">Date of Birth</label>
        <input type="text" id="operation_edit_dob" />
        <label for="operation_edit_alive" class="control-label">Alive</label>
        <select id="operation_edit_alive">
            <option value="1">
                Yes
            </option>
            <option value="0">
                No
            </option>
        </select>
        <br>
        <button class="btn btn-success" type="submit">Add</button>
        <button class="btn" onclick="$('#operation_edit').slideUp()" type="button">Cancel</button>
    </form>
{/if}
{if $user_not_connected eq True}
    <div class="alert alert-info" style="margin-top: 10px">
        You are not connected to any member of the family. Please connect yourself to the family.Select Yourself in the Tree and click This is me.
    </div>
    <div style="text-align:center">
        <button class="btn btn-success" onclick="thisisme()">
            This is me
        </button>
    </div>
{/if}    
</div>