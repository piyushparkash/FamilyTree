<div id="right-container">

    <fieldset>
        <div class="row" >
            <div class="span4" style="text-align: center;" >
                <img src="" style="width: 50px; height: 50px;" id="display_image"/>
            </div>
            <div class="span4">
                <h3 id="display_name" style="text-align: center; width: 100%;"></h3>
            </div>
            <div class="span4">
                <div class="row">
                    <span class="span2">Date Of Birth</span>
                    <div class="span2">
                        <span id="display_dob"></span>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="row">
                    <span class="span2">Relationship Status</span>
                    <div class="span2">
                        <span id="display_relationship"></span>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="row">
                    <span class="span2">Alive</span>
                    <div class="span2">
                        <span id="display_alive"></span>
                    </div>
                </div>
            </div>
            {if $authenticated}
                <div class='span4' style="text-align: center">
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            Edit Member
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:operation_addmember()">Add Son</a></li>
                            <li><a href="javascript:deletemember()">Remove Member</a></li>
                            <li><a >Edit Member</a></li>
                        </ul>
                    </div>
                </div>


            {/if}
        </div>
    </fieldset>
    {if $authenticated}
        <form method="post" onsubmit="return operation_addmember_submit()" class="well hide" style='margin:10px' id="operation_add" >
            <label for="operation_add_name" class="control-label">Name:</label>
            <input type="text" id="operation_add_name" />
            <label for="operation_add_sonof_name" class="control-label" >Sonof:&nbsp;&nbsp;
                <span id='operation_add_sonof_name'></span>
            </label>
            <input type="hidden" id="operation_add_sonof_id" />
            <label for="operation_add_gender" class="control-label">Gender:</label>
            <select id="operation_add_gender">
                <option value="1">
                    Male
                </option>
                <option value="0">
                    Female
                </option>
            </select><br>
            <button class="btn btn-success" type="submit">Add</button>
            <button class="btn" onclick="$('#operation_add').slideUp()" type="button">Cancel</button>
            <button class="btn" type="reset">Reset</button>
        </form>
    {/if}
</div>