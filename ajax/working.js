var Vanshavali = {};

//Defining some constants
Vanshavali.MALE = 0;
Vanshavali.FEMALE = 1;
Vanshavali.SINGLE = 0;
Vanshavali.MARRIED = 1
Vanshavali.LIVING = 1;
Vanshavali.DECEASED = 0;

Vanshavali.makeAJAX = function (URL, data, success, error) {
    simpleError = function (response) {
        //Just pass the response text
        error(response.responseText);
    }

    simpleSuccess = function (response) {
        //Just pass the response text
        success(response.responseText);
    }
    $.ajax(URL, {
        data: data,
        error: simpleError,
        success: simpleSuccess,
        method: 'POST'
    });
}

Vanshavali.statusModal = {};

Vanshavali.statusModal.show = function (text, isError) {
    //function to show alert to the user
}

//The Basic API's to used all over Vanshavali
Vanshavali.Operation = {};

Vanshavali.Operation.addChild = function (name, gender, father, success, error) {
    //We have parameters, send Ajax Call
    Vanshavali.makeAJAX('getdata.php', {
            action: "operationAdd",
            name: name,
            gender: gender,
            sonof: father
        },
        function () {
            Vanshavali.statusModal.show("Your changes will be permnantly visible on FamilyTree once it is approved by other members. Thank you for your contribution");
        },
        function () {
            Vanshavali.statusModal.show('Oops! There was an error while adding new member. Please try again.', true)
        }
    );
}

Vanshavali.Operation.addParent = function (fathername, mothername, child) {
    //Make the AJAX request
    Vanshavali.makeAJAX('getdata.php', {
            action: "operationAddParents",
            fathername: fathername,
            mothername: mothername,
            parentsof: child
        },
        function () {
            Vanshavali.statusModal.show("Changes will be visible once it approved by other members. Thank you for your contribution");
        },
        function () {
            Vanshavali.statusModal.show("Oops! Something went wrong. Please try again", true);
        });
}

Vanshavali.Operation.addSpouse = function (name, otherSpouse) {
    //Make the AJAX request
    Vanshavali.makeAJAX('getdata.php', {
            action: 'operationAddSpouse',
            name: name,
            otherSpouse: otherSpouse
        },
        function () {
            Vanshavali.statusModal.show("Changes will be visible once it is approved by other members. Thank you for your contribution");
        },
        function () {
            Vanshavali.statusModal.show("Oops! Something went wrong. Pleae try again");
        });
}


var is_authenticated = false;

//Search Functions
function search() {

    //Show the dialog
    $("#search").modal();

    $("#search").children(".modal-body").children("form").submit(function (e) {
        e.preventDefault();
        return false;
    });

    //Initialize the autocomplete widget
    $("#search_term").autocomplete({
        source: function (request, response) {
            var newarray = new Array() //This array will hold all the results
            var helpblock = $("#search").children(".modal-body").children("form").children(".help-block")[0];

            $.getJSON("register_username.php?action=search&pt=" + request.term, "", function (data) {
                $.each(data, function (key, value) {
                    //Convert to jquery-ui obect
                    var obj = {
                        label: value,
                        value: key
                    };
                    //push it in the final array
                    newarray.push(obj);

                });

                //if newarray is empty
                if (newarray.length === 0) {
                    //Tell user there is no one with this name
                    helpblock.innerHTML = "No User found with this name";
                    console.log("We got empty response. No user with this name");
                } else {
                    helpblock.innerHTML = "Type the name and click search";
                }

                response(newarray);
            });
        },
        select: function (e, ui) {
            //Set the value to the key instead of value
            this.value = ui.item.label;

            //select member on the tree
            showUser(ui.item.value);

            //reset search value to "" and close the dialog
            $("#search_term").val("");
            $("#search").modal("hide");
            return false;

        },
        focus: function () {
            return false;
        }
    });
}
//End of search related functions

function validateEmail(emailID) {
    var atpos = emailID.indexOf("@");
    var dotpos = emailID.lastIndexOf(".");
    if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= emailID.length) {
        return false;
    } else {
        return true;
    }
}

//Forgot Password section

function forgotPassword() {
    //Hide the Login Modal first
    $("#login").modal('hide');
    $("#forgotPassword").modal();
}

function forgotPassword_submit(ohtml, e) {

    //Disable the event from happening first
    e.preventDefault();

    //Disable the reset button
    $(ohtml).children("div.form-actions button").attr("disabled", "yes");

    //Validate the Email.
    userInfo = $("#emailoname").attr("disabled", "yes").val();
    isEmail = validateEmail(userInfo);


    var type;
    if (isEmail) {
        type = "email";
    } else {
        type = "username";
    }


    //Send a request to the site for forgotpassword link
    $.post("getdata.php", {
            action: "forgotpassword",
            type: type,
            data: userInfo
        },
        function (data) {
            if (ajaxSuccess(data)) {
                //Tell the user, email has been sent.
                $("#emailoname").siblings(".help-block").val("Reset Password link has been sent to your Email ID");
            } else if (ajaxError(data)) {
                //Oops now what to do
                //read out the error cause

                var datajson = $.parseJSON(data);


                if (datajson.message == "NO_USER") {
                    $("#emailoname").siblings(".help-block").text("No User with given Email/Username");
                } else if (datajson.message == "NO_MAIL") {
                    alert($("#emailoname").siblings(".help-block").length)
                    $("#emailoname").siblings(".help-block").text("We could not sent mail. Sorry about that! Please retry");
                }

            }
        });

    //All things done. Lets Reenable the controls
    $(ohtml).children("div.form-actions button").removeAttr("disabled");
    $("#emailoname").removeAttr("disbaled");

    //Prevent the form from submitting
    return false;

}
//login Form
function login() {
    $("#login").modal();
}

function login_submit() {
    //get the username and password
    var username = $("#login_username").attr("disabled", "yes").val();
    var password = $("#login_password").attr("disabled", "yes").val();

    //Post username and password to login.php
    $.post("login.php", {
        username: username,
        password: password
    }, function (data) {
        data = $.parseJSON(data);


        //if not able to login
        if (parseInt(data.error) == 1) {
            $("#login_error").html("Login Failed! Please check your username and password and try again!");
            $("#login_username").removeAttr("disabled").val("");
            $("#login_password").removeAttr("disabled").val("");

            //Show the error block which is hidden
            $("#login_error").removeClass("hide");

            //positon the dialog in the center as #login_error is now visible
            $("#login").modal();
            return false;

            // if successfull login
        }
        if (parseInt(data.error) == 2) {
            $("#login_error").html("Please activate your account by with the link sent to you on you email");
            //$("#login_error").html("Login Failed! Please check your username and password and try again!");
            $("#login_username").removeAttr("disabled").val("");
            $("#login_password").removeAttr("disabled").val("");

            $("#login_error").removeClass("hide");

            //positon the dialog in the center as #login_error is now visible
            $("#login").modal();
            return false;
        }

        window.location = "index.php";

    });

    //Stop form submit and remain on the same page
    return false;
}
//Feedback Form
function feedback() {
    $("#feedback_form").modal();
}
//Feedback Submission
function submit_feedback() {
    var name = $("#feedback_name").attr("disabled", "yes").val();
    var email = $("#feedback_email").attr("disabled", "yes").val();
    var message = $("#feedback_text").attr("disabled", "yes").val();
    $.post("getdata.php", {
        action: "feedback",
        name: name,
        email: email,
        message: message
    }, function (data) {
        if (ajaxError(data)) {
            alert("Some error Occured. Pleae try again");
            window.location.reload();
        } else if (ajaxSuccess(data)) {
            $("#feedback_form").modal("hide");
            alert("Thank you for contributing!");
        }

    });
}
////////////////////////////////////////////////////////
// Member Operation functions
function operation_addmember() {
    $("#operation_add").slideDown();

    //name and id of parent from tree.graph instance
    father_name = (tree.graph.getNode(selected_member)).name;
    father_id = (tree.graph.getNode(selected_member)).id;

    //add to the form
    //alert($("#operation_add_sonof_id").length);
    $("#operation_add_sonof_name").html(father_name);
    $("#operation_add_sonof_id").val(father_id);
}

function operation_addmember_submit() {
    //get the values and disable the controls
    var name = $("#operation_add_name").attr("disabled", "yes");
    var gender = $("#operation_add_gender").attr("disabled", "yes");
    var sonof = $("#operation_add_sonof_id");

    //post the information in the suggestion table
    $.post("getdata.php", {
        action: "operation_add",
        type: "child",
        name: name.val(),
        gender: gender.val(),
        sonof: sonof.val()
    }, function (data) {
        //Check if AJAX error occured
        if (ajaxError(data)) {
            alert("Some Error Occured. Please try again");
            $("#operation_add_name").removeAttr("disabled").val("");
            $("#operation_add_gender").removeAttr("disabled");
            $("#operation_add").slideUp();
            return false;
        } else if (ajaxSuccess(data)) {
            //enable the controls and set the value to ""
            $("#operation_add_name").removeAttr("disabled").val("");
            $("#operation_add_gender").removeAttr("disabled");

            //remove any previous text node from #operation_add_sonof_name and reset
            $("#operation_add_sonof_name").html("<input type='hidden' id='operation_add_sonof_id' />");


            alert("The changes will be viewed permanently in the Family Tree once it is confirmed by other members. Thankyou for your contribution");
            $("#operation_add").slideUp();
        }


    });

    //stop the form from redirecting
    return false;
}

function editmember() {
    //get the member from the tree to retreive data
    member = tree.graph.getNode(selected_member);

    //Collect all the members
    x = $("#operation_edit_name,#operation_edit_gender,#operation_edit_relationship,\n\
        #operation_edit_dob,#operation_edit_alive");

    //Set their default values
    x[0].value = member.name;
    x[3].value = member.data.dob;

    //Find from values and select the given element
    options = x[1].options;
    for (var i = 0; i < x[1].options.length; i++) {
        if (options[i].value == parseInt(member.data.gender)) {
            x[1].selectedIndex = i;
        }
    }
    options = x[2].options;
    for (i = 0; i < x[2].options.length; i++) {
        if (options[i].value == parseInt(member.data.relationship_status_id)) {
            x[2].selectedIndex = i;
        }
    }

    $("#operation_edit_dob").datepicker({
        dateFormat: "dd/mm/yy",
        maxDate: '-10y'
    });

    options = x[4].options;
    for (i = 0; i < x[4].options.length; i++) {
        if (options[i].value == parseInt(member.data.alive_id)) {
            x[4].selectedIndex = i;
        }
    }

    $("#operation_edit_id").val(parseInt(member.id));
    $("#operation_edit").slideDown();
}

function editmember_submit() {
    //Collect variables values
    x = $("#operation_edit_name,#operation_edit_gender,#operation_edit_relationship,\n\
        #operation_edit_dob,#operation_edit_alive,#operation_edit_id");

    if (x[0].value == "") {
        alert("Name cannot be left blank");
        x[0].focus();
        return false;
    }


    //Validate date of birth only if user has entered any
    if (x[4].value != "") {
        var regex = /([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})/.test(x[4].value);
        if (!regex) {
            alert("Please enter a valid Date of Birth");
            x[4].focus();
            return false;
        }
    }

    //post the data
    $.post("getdata.php", {
            type: "edit",
            action: "operation_edit",
            "name": x[0].value,
            "gender": x[2].value,
            "relationship": x[3].value,
            "dob": x[4].value,
            "alive": x[5].value,
            memberid: x[1].value

        }, function (data) {
            //Check for AJAX Error
            if (ajaxError(data)) {
                alert("Some Error Occured. Please try again.");
                return false;
            } else if (ajaxSuccess(data)) {
                var x = $("#operation_edit_name,#operation_edit_gender,#operation_edit_relationship,\n\
        #operation_edit_dob,#operation_edit_alive,#operation_edit_id");

                //Set the canvas variables
                member = tree.graph.getNode($("#operation_edit_id").val());
                member.name = x[0].value;
                member.data.relationship_status_id = x[3].value;
                member.data.alive_id = x[5].value;
                member.data.gender = x[2].value;
                member.data.dob = x[4].value;

                //Change the displayed data on the screen
                member.data.relationship_status = member.data.relationship_status_id == 0 ? "Single" : "Married";
                member.data.alive = member.data.alive_id == 0 ? "No" : "Yes";

                display_data(member);

                //hide the form
                $("#operation_edit").slideUp();

                alert("This information will be displayed once it is accepted by other members.");

            }
        }

    );

    //Stop redirect
    return false;
}

function modify_details(id, name, dob, gender, relationship, alive) {
    member = tree.graph.getNode(parseInt(id));
    member.name = name;
    member.data.dob = dob;
    member.data.gender = gender;
    member.data.relationship_status_id = relationship;
    member.data.alive_id = alive;
}

function deletemember() {
    //Details of member
    member = (tree.graph.getNode(selected_member));
    //Details of father
    father = member.getParents();

    if (father.length == 0) {
        //No Parent huh? I am Root!!
        alert("This member cannot be removed");
        return;
    }

    $("#operation_remove_son").text(member.name);
    $("#operation_remove_father").text(father[0].name);
    $("#operation_remove_son_id").val(member.id);
    $("#operation_remove").modal();
}

function deletemember_submit() {
    $("#operation_remove").modal("hide");
    member_id = $("#operation_remove_son_id").val();
    $.post("getdata.php", {
            action: "operation_remove",
            type: "remove",
            "memberid": member_id
        },
        function (data) {
            //Check for Ajax Error
            if (ajaxError(data)) {
                alert("Some Error Occured. Please try again");
                return false;
            } else if (ajaxSuccess(data)) {
                alert("Member will be removed once it is confirmed by other members");
                return true;
            }

        });
}

function suggest() {
    $.post("getdata.php", {
            action: "getsuggestions"
        }, function (data)

        {
            //Update the modal with data first
            $("#suggest-data").html(data);

            //Check if we have datato show

            if (!data.trim()) {
                $("#suggest-data").html("<div class='alert alert-success'>Wohoo! You have completed all your suggestions</div>")
            }

            //Show the modal now
            $("#suggest").modal();


        });

}

function approvedSuggestion() {
    $("#approvedsuggest-data").html("<div class='alert alert-success'>Loading...</div>");

    $.post("getdata.php", {
        action: "getapprovedsuggestion"
    }, function (data) {
        //Update the modal with the data
        $("#approvedsuggest-data").html(data);

        //Check if there is no data to show
        if (!data.trim()) {
            $("#approvedsuggest-data").html("<div class='alert alert-success'>You have not approved or rejected any suggestion</div>");
        }

        $("#suggest").modal();

    });
}

function suggest_action(e, actionid) {
    var x = $(e).parents("div.suggest-box");

    //Get the id of the suggest
    var id = parseInt(x.attr("suggest-id"));

    //perform the suggestion ajax action
    $.post("getdata.php", {
            action: "suggestionapproval",
            suggestid: id,
            suggest_action: actionid
        }, function (data)

        {
            //Check for Ajax error
            if (ajaxError(data)) {
                alert("Some error occured. Please try again");
                return false;
            } else if (ajaxSuccess(data)) {
                data = $.parseJSON(data);
                //Success now hide the suggestion
                $("[suggest-id=" + data.data.suggestid + "]").hide("medium");
            }
        });


}

function ajaxSuccess(response) {
    var json;
    if (!(json = $.parseJSON(response))) {
        alert("Error Occured while parsing response JSON");
        return false;
    } else {
        if (json.success == 1) {
            return true;
        } else {
            return false;
        }
    }
}

function ajaxError(response) {
    var json;
    if (!(json = $.parseJSON(response))) {
        alert("Error Occured while parsing response JSON");
        return false;
    } else {
        if (json.success == 0) {
            return true;
        } else {
            return false;
        }
    }
}


function thisisme() {
    if (!selected_member) {
        alert("Please Select a member on the Tree and then clock on This is me.");
        return false;
    } else {
        var res = confirm("Are you sure?");
        if (res) {
            $.post("getdata.php", {
                action: "checkregistered",
                id: selected_member
            }, function (data) {
                if (ajaxSuccess(data)) {
                    window.location.assign("thisisme.php?id=" + selected_member);
                    return true;
                } else if (ajaxError(data)) {

                    alert("Someone is already registered with that name");
                    return false;
                }
            });
        }
    }
}
//Declaring the basic Family APIS
Vanshavali.addParents = {};
Vanshavali.addSpouse = {};
Vanshavali.addChild = {};
Vanshavali.removeChild = {};
Vanshavali.removeParents = {};
Vanshavali.removeChild = {};
Vanshavali.modifyMember = {};

//Add Spouse Code
Vanshavali.addSpouse.showModal = function () {
    //Get the member whose wife is to be added
    var member = tree.graph.getNode(selected_member);

    //Fill in the details of the husband
    $("#operation_addSpouse_otherSpousename").text(member.name);
    $("#operation_addSpouse_otherSpouseID").val(member.id);
    $("#operation_addSpouse").slideDown();
}

Vanshavali.addSpouse.hideModal = function () {
    //Code to hide the modal
    $("#operation_addSpouse").slideUp();

    //Remove values from the field
    $("#operation_addSpouse_otherSpousename").val('');
    $("#operation_addSpouse_otherSpouseID").val('');
    $("#operation_addSpouse_name").val('');
}

Vanshavali.addSpouse.submit = function () {
    //Code to read from the form and submit
    var name = $("#operation_addSpouse_name").val();
    var otherSpouse = $("#operation_addSpouse_otherSpouseID").val('');
    Vanshavali.Operation.addSpouse(name, otherSpouse);
    Vanshavali.Operation.hideModal();
}

//End of AppSpouse Code

//Add Parent Code

Vanshavali.addParents.showModal = function () {
    $("#operation_addParents").slideDown();

    //Set the parents of value of the field
    var currentMember = tree.graph.getNode(selected_member);
    $("#operation_addParents_parentsof").hide().show().val(currentMember.name);
    $("#operation_addParents_parentsofid").val(currentMember.id);
}

Vanshavali.addParents.submit = function (e) {
    //Code to read from the form and submit
    var fathername = $("#operation_addParents_Fathername");
    var mothername = $("#operation_addParents_Mothername");

    //Whose parents are they
    var parentsof = $("#operation_addParents_parentsofid");

    //set the request to Vanshavali API
    Vanshavali.Operation.addParent(fathername.val(), mothername.val(), parentsof.val());

    //Hide the modal
    Vanshavali.addParents.hideModal();

    //Always return false, as it handles forms
    return false;
}

Vanshavali.addParents.hideModal = function () {
    $("#operation_addParents").slideUp();

    //Remove previous parents of values from the field
    $("#operation_addParents_parentsof").val('');
    $("#operation_addParents_parentsofid").val('');
    $("#operation_addParents_Fathername").val('');
    $("#operation_addParents_Mothername").val('');
}

function addwife() {

}


function operation_addwife_submit() {
    var name = $("#operation_addwife_name").attr("disabled", "yes");
    var husband = $("#operation_addwife_husband_id");


    //post the information in the suggestion table
    $.post("getdata.php", {
        action: "operation_addwife",
        type: "wife",
        name: name.val(),
        husband: husband.val()
    }, function (data) {
        //Check if AJAX error occured
        if (ajaxError(data)) {
            alert("Some Error Occured. Please try again");
            $("#operation_addwife_name").removeAttr("disabled").val("");
            $("#operation_addwife_dob").removeAttr("disabled");
            $("#operation_addwife_husband_id").val("");
            $("#operation_addwife_husband_name").text("");
            $("#operation_addwife").slideUp();
            return false;
        } else if (ajaxSuccess(data)) {
            //enable the controls and set the value to ""
            $("#operation_addwife_name").removeAttr("disabled").val("");
            $("#operation_addwife_dob").removeAttr("disabled");

            //remove any previous text node from #operation_add_sonof_name and reset
            $("#operation_addwife_husband_name").html("<input type='hidden' id='operation_addwife_husband_name' />");


            alert("The changes will be viewed permanently in the Family Tree once it is confirmed by other members. Thankyou for your contribution");
            $("#operation_addwife").slideUp();
        }

        return false;
    });

    //stop the form from redirecting
    return false;
}


//Functions to add husband to the daughter

function addhusband() {
    //Get the member whose wife is to be added
    var member = tree.graph.getNode(selected_member);

    //Fill in the details of the husband
    $("#operation_addhusband_wife_name").text(member.name);
    $("#operation_addhusband_wife_id").val(member.id);
    $("#operation_addhusband").slideDown();
}


function operation_addhusband_submit() {
    var name = $("#operation_addhusband_name").attr("disabled", "yes");
    var wife = $("#operation_addhusband_wife_id");


    //post the information in the suggestion table
    $.post("getdata.php", {
        action: "operation_addhusband",
        type: "husband",
        name: name.val(),
        wife: wife.val()
    }, function (data) {
        //Check if AJAX error occured
        if (ajaxError(data)) {
            alert("Some Error Occured. Please try again");
            $("#operation_addhusband_name").removeAttr("disabled").val("");
            $("#operation_addhusband_dob").removeAttr("disabled");
            $("#operation_addhusband_wife_id").val("");
            $("#operation_addhusband_wife_name").text("");
            $("#operation_addhusband").slideUp();
            return false;
        } else if (ajaxSuccess(data)) {
            //enable the controls and set the value to ""
            $("#operation_addhusband_name").removeAttr("disabled").val("");
            $("#operation_addhusband_dob").removeAttr("disabled");

            //remove any previous text node from #operation_add_sonof_name and reset
            $("#operation_addhusband_wife_name").html("<input type='hidden' id='operation_addhusband_wife_name' />");


            alert("The changes will be viewed permanently in the Family Tree once it is confirmed by other members. Thankyou for your contribution");
            $("#operation_addhusband").slideUp();
        }

        return false;
    });

    //stop the form from redirecting
    return false;
}