<form method="post" id="register_form" action="register.php" class="single-dialog form-horizontal well well-large">
    <fieldset>
        <legend>
            {if $is_admin }
                Hi Admin, We need some basic information about you
                <input type="hidden" value="1" id="is_admin" name="is_admin" />
            {else}
                Welcome to Vanshavali! Fill out the form to register.
            {/if}
        </legend>
        {if $familyid}
            <input type="hidden" value="{$familyid}" name="familyid" />
        {/if}
        <style>
            body
            {
                overflow: scroll;
            }
        </style>
        <div class="control-group">
            <label for="register_name" class="control-label">Your Name:</label>
            <div class="controls">
                <input type="text" id="register_name" name="register_name"/>
                <input type="hidden" id="register_id" name="register_id"/>
                <span  class="help-block"></span>
            </div>
        </div>
        <div class="control-group">
            <label for="register_username" class="control-label">Username</label>
            <div class="controls">
                <input type="text" id="register_username" name="register_username"/>
                <span class="help-block" ></span>
            </div>
        </div>
        <div class="control-group">
            <label for="register_password" class="control-label">Password</label>
            <div class="controls">
                <input type="password" id="register_password" name="register_password"/>
            </div>
        </div>
        <div class="control-group">
            <label for="register_confirmpassword" class="control-label">Confirm Password</label>
            <div class="controls">
                <input type="password" id="register_confirmpassword" />
                <span class="help-block" ></span>
            </div>
        </div>
    </fieldset>
    <div class="control-group">
        <label class="control-label" for="register_dob">Date Of Birth</label>
        <div class="controls">
            <input type="text" id="register_dob" name="register_dob"/>
        </div>
    </div>
    <div class="control-group">
        <label for="register_gender" class="control-label">Gender</label>
        <div class="controls">
            <select id="register_gender" name="register_gender">
                <option selected="selected" value="0">
                    Male
                </option>
                <option value="1">
                    Female
                </option>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="register_relationship">Relationship Status</label>
        <div class="controls">
            <select id="register_relationship" name="register_relationship">
                <option selected="selected" value="0">
                    Single
                </option>
                <option value="1">
                    Married
                </option>
            </select>
        </div>

    </div>
    <div class="control-group">
        <label for="register_gaon" class="control-label">Village ( gaon )</label>
        <div class="controls">
            <input type="text" id="register_gaon" name="register_gaon"/>
        </div>
    </div>

    <div class="control-group">
        <label for="register_email" class="control-label">Email Id:</label>
        <div class="controls">
            <input type="text" id="register_email" name="register_email"/>
            <span class="help-block" ></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="register_about">Little about you</label>
        <div class="controls">
            <textarea placeholder="Tell us something..." name="register_about" ></textarea>
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" value="Register" name="register_submit" class="btn btn-large btn-primary" />
        <input type="reset" value="Reset" class="btn btn-large" />
    </div>
</form>
</div>

</div>

<script type="text/javascript">
//Function to display text with the input controls in the register form
    function success_display(id, display_text)
    {
        //hide the element, insert the text and the tick, and then show
        $("#" + id).parents(".control-group").addClass("success");
        $("#" + id).siblings(".help-text").css("display", "none").text(display_text)
                .fadeIn(500);
    }

    //Function to hide success message when value changed
    function success_hide(id)
    {
        $("#" + id).siblings("span.help-text").fadeIn("medium");
        $("#" + id).parents("div.control-group").removeClass("success");
    }


    //Initialize constraints and checks
    //Name related checks
    $("#register_name").autocomplete({
        source: function (request, response) {
            var newarray = new Array(); //This array will hold all the results


            $.getJSON("register_username.php?action=register&pt=" + request.term, "", function (data) {
                $.each(data, function (key, value) {
                    //Convert to jquery-ui obect
                    var obj = {
                        label: value,
                        value: key
                    };
                    //push it in the final array
                    newarray.push(obj);

                });

                response(newarray);
            });
        },
        select: function (e, ui) {
            //Set the value to the key instead of value
            this.value = ui.item.label;

            //save the selected member id in the hidden input
            $("#register_id").val(ui.item.value);

            //show the success text
            success_display("register_name", "Correct Name")
            return false;

        },
        focus: function () {
            return false;
        }
    });

    //end of name related checks

    //Username related checks
    $("#register_username").blur(function () //when user leaves the control
    {
        $.post("getdata.php", {
            action: "username_check",
            username: this.value
        }, function (data)

        {
            var json = $.parseJSON(data);
            if (json.yes) //if reply is yes the username is already used 
            {
                alert("Username is already used. Try something else");

                //Cannot use this, pointing to some ajax function
                $("#register_username").focus();
                //hide any previous success message
                success_hide("register_username");
            }
            else //else not used and can be used
            {
                success_display("register_username", "Valid Username");
            }
        });
    });
    //end of username related checks


    //Password related checks
    $("#register_confirmpassword").blur(function ()
    {
        var password = $("#register_password");
        if (this.value == password.val())
        {
            success_display("register_confirmpassword", "Password Matched");
        }
        else
        {
            alert("Password donot match. Please check again.");
            password.focus();

            //hide any previous success message
            success_hide("register_confirmpassword");
        }
    });
    //end of password related checks

    //Date of Birth related checks
    //Initialize the date picker
    $("#register_dob").datepicker({
        maxDate: "-1d",
        dateFormat: "dd/mm/yy"
    });

    //End of Date of Birth Related Checks


    //Email related checks
    $("#register_email").blur(function () {
        x = this.value;
        var atpos = x.indexOf("@");
        var dotpos = x.lastIndexOf(".");
        if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length)
        {
            success_hide("register_email");
            alert("Not a valid e-mail address");
            $(this).focus();
        }
        else
        {
            //display success text
            success_display("register_email", "Valid Email Id");
        }
    });
    //end of email related check

    //All checks performed now we can submit the form
    $("#register_form").submit(function () {

        //will remain true if everything is filled out
        var passed = true;

        //If register id is null the let it be and let the user in
        //var user_id=$("#register_id").val();
        //if (user_id=='' || user_id==null)
        //  {
        //    alert("Please select a user");
        //  $("#register_name").focus();
        //return false;
        //}


        var check = $("#register_name,#register_username,#register_password,#register_confirmpassword,#register_dob,#register_email");
        $.each(check, function (key, value) {
            if (value.value == '') //if value of specified element is empty
            {
                value.focus(); //focus on that variable
                passed = false;  //set passed to false;
                return false;
            }
        });
        return passed;
    });

</script>
