<div class="container-fluid">
    <div class="row">
        <div class="offset-3 col-6 offset-3">
            <form method="post" id="register_form" action="register.php" class="bg-faded p-5">
                {if $is_admin }
                    <h4>Hi Admin, We need some basic information about you</h4>
                    <input type="hidden" value="1" id="is_admin" name="is_admin"/>
                {else}
                    <h4>Welcome to Vanshavali! Fill out the form to register.</h4>
                {/if}
                </legend>
                {if $familyid}
                    <input type="hidden" value="{$familyid}" name="familyid"/>
                {/if}
                <style>
                    body {
                        overflow: scroll;
                    }
                </style>
                <div class="form-group">
                    <label for="register_name" class="col-form-label">Your Name:</label>
                    <input type="text" id="register_name" name="register_name" class="form-control" validated="no"
                           required/>
                    <input type="hidden" id="register_id" name="register_id"/>
                    <span class="form-control-feedback"></span>
                </div>
                {if !$is_wordpress_enabled}
                    <div class="form-group">
                        <label for="register_username" class="col-form-label">Username</label>
                        <input type="text" id="register_username" name="register_username" validated="no" required
                               class="form-control"/>
                        <span class="form-control-feedback"></span>
                    </div>
                    <div class="form-group">
                        <label for="register_password" class="col-form-label">Password</label>
                        <input type="password" id="register_password" name="register_password" validated="no" required
                               class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="register_confirmpassword" class="col-form-label">Confirm Password</label>
                        <input type="password" id="register_confirmpassword" validated="no" required
                               class="form-control"/>
                        <span class="form-control-feedback"></span>
                    </div>
                {/if}
                <div class="form-group">
                    <label class="col-form-label" for="register_dob">Date Of Birth</label>
                    <input type="text" id="register_dob" name="register_dob" validated="no" required
                           class="form-control"/>
                    <span class="form-control-feedback">Format: dd-mm-yyyy</span>
                </div>
                <div class="form-group">
                    <label for="register_gender" class="col-form-label">Gender</label>
                    <select id="register_gender" name="register_gender" class="form-control">
                        <option selected="selected" value="0">
                            Male
                        </option>
                        <option value="1">
                            Female
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="register_relationship">Relationship Status</label>
                    <select id="register_relationship" name="register_relationship" class="form-control">
                        <option selected="selected" value="0">
                            Single
                        </option>
                        <option value="1">
                            Married
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="register_gaon" class="control-label">Village ( Gaon )</label>
                    <input type="text" id="register_gaon" name="register_gaon" required class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="register_email" class="col-form-label">Email Id:</label>
                    <input type="text" id="register_email" name="register_email" validated="no" class="form-control"
                           required/>
                    <span class="form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="register_about">Little about you</label>
                    <textarea placeholder="Tell us something..." name="register_about" class="form-control"></textarea>
                </div>

                <input type="submit" value="Register" name="register_submit" class="btn btn-large btn-primary"/>
                <input type="reset" value="Reset" class="btn btn-large"/>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    //Function to display text with the input controls in the register form
    function success_display(id, display_text) {
        //hide the element, insert the text and the tick, and then show
        $("#" + id).parents(".form-group").addClass("success");
        $("#" + id).siblings(".help-block").css("display", "none").text(display_text)
            .fadeIn(500);
    }

    //Function to hide success message when value changed
    function success_hide(id) {
        $("#" + id).siblings("span.help-block").fadeIn("medium").fadeOut(500).text("");
        $("#" + id).parents("div.form-group").removeClass("success");
    }

    //Function to show error
    function error_display(id, display_text) {
        $("#" + id).parents(".form-group").addClass('error');
        $("#" + id).siblings('.help-block').css("display", "none").text(display_text).fadeIn(500);
    }

    function error_hide(id) {
        $("#" + id).siblings('span.help-block').fadeIn("medium").fadeOut(500).text("");
        $("#" + id).siblings('div.form-group').removeClass('error');
    }

    function validated(id) {
        $("#" + id).attr('validated', 'yes');
    }

    function notValidated(id) {
        $("#" + id).attr('validated', 'no');
    }

    function isValidated(id) {
        if ($("#" + id).attr("validated") == "yes") {
            return true;
        }
        else {
            return false;
        }
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
            Vanshavali.successInput("#register_name", "Correct Name")
            return false;

        },
        focus: function () {
            return false;
        }
    });

    $("#register_name").focusout(function () {
        //Just confirm if the Name field is filled
        if (this.value == "") {
            Vanshavali.clearInput("#register_name");
            Vanshavali.errorInput("#register_name");
            notValidated("register_name");
        }
        else {
            Vanshavali.clearInput("#register_name");
            Vanshavali.successInput("#register_name");
            validated("register_name");
        }
    });

    //end of name related checks

    //Username related checks
    $("#register_username").focusout(function () //when user leaves the control
    {
        $.post("getdata.php", {
            action: "username_check",
            username: this.value
        }, function (data) {
            var json = $.parseJSON(data);
            if (json.yes === 1) //if reply is yes the username is already used 
            {
                Vanshavali.clearInput("#register_username");
                Vanshavali.errorInput("#register_username", "Username already taken");
                notValidated("register_username");


            } else if (json.yes === 0) //else not used and can be used
            {
                Vanshavali.clearInput("#register_username");
                Vanshavali.successInput("#register_username", "Valid Username");
                validated("register_username");
            }
            else if (json.yes === -1) {
                Vanshavali.clearInput("#register_username");
                Vanshavali.errorInput("#register_username", "Username is not in correct format");
                notValidated("register_username");
            }
        });

        return false;
    });
    //end of username related checks


    //Password related checks
    $("#register_confirmpassword").focusout(function () {
        var password = $("#register_password");
        if (this.value == password.val()) {
            Vanshavali.successInput("#register_confirmpassword", "Password Matched");
            validated("register_confirmpassword");
            validated("register_password");
        } else {
            Vanshavali.clearInput("#register_confirmpassword");
            Vanshavali.errorInput("#register_confirmpassword", "Password do not match.");
            notValidated("register_password");
            notValidated("register_confirmpassword");
        }
    });
    //end of password related checks

    //Date of Birth related checks
    //Initialize the date picker
    $("#register_dob").datepicker({
        maxDate: "-1d",
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true
    });

    //End of Date of Birth Related Checks


    //Email related checks
    $("#register_email").focusout(function () {
        x = this.value;
        var atpos = x.indexOf("@");
        var dotpos = x.lastIndexOf(".");
        if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length) {
            error_hide("register_email");
            success_hide("register_email");
            error_display("register_email", "Not a valid e-mail address");
            notValidated("register_email");
        } else {
            //display success text
            Vanshavali.clearInput("#register_email");
            Vanshavali.successInput("#register_email", "Valid Email Id");
            $.post("getdata.php", {
                action: "email_check",
                email: this.value
            }, function (data) {
                var json = $.parseJSON(data);
                if (json.yes) //if reply is yes the email is already used 
                {
                    Vanshavali.clearInput("#register_email");
                    Vanshavali.errorInput("#register_email", "Email is already registered. Try Forgot Password!");
                    notValidated("register_email");
                } else //else not used and can be used
                {
                    Vanshavali.clearInput("#register_email");
                    Vanshavali.successInput("#register_email", "Email is good to go");
                    validated("register_email");
                }
            });
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

        check2 = $("#register_name,#register_username,#register_password,#register_confirmpassword,#register_email");
        $.each(check2, function (index, val) {
            if (!isValidated(val.id)) {
                console.log("we could not validate everything" + val.id);
                passed = false;
            }
        });
        return passed;
    });

</script>
