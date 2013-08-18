var is_authenticated = false;
$(document).ready(function () {
    //$("#firstlogininfo").modal();
    //$("#firsttimeinfo").modal();
    //Adjust the size of the canvas according to size of the screen
    var opti_width=.65*(screen.availWidth);
    var opti_height=.90*screen.availHeight;
    $("#infovis").css({
        "width":opti_width,
        "height":opti_height
    });
}
);

//Search Functions
function search() {
    
    //Show the dialog
    $("#search").modal();
    
    //Initialize the autocomplete widget
    $("#search_term").autocomplete({
        source: function(request, response) {
            var newarray = new Array() //This array will hold all the results
            
            
            $.getJSON("register_username.php?action=search&pt=" + request.term, "", function(data) {
                $.each(data, function(key, value) {
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
        select: function(e, ui) {
            //Set the value to the key instead of value
            this.value = ui.item.label;
            
            //select member on the tree
            showUser(ui.item.value);
            
            //reset search value to "" and close the dialog
            $("#search_term").val("");
            $("#search").modal("hide");
            return false;
            
        },
        focus: function() {
            return false;
        }
    });
}
//End of search related functions


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
    }, function(data) {
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
        if (parseInt(data.error) == 2)
        {
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
        action:"feedback",
        name: name,
        email: email,
        message: message
    }, function(data) {
        if (ajaxError(data))
        {
            alert("Some error Occured. Pleae try again");
            window.location.reload();
        }
        else if (ajaxSuccess(data))
        {
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
    father_name=(tree.graph.getNode(selected_member)).name;
    father_id=(tree.graph.getNode(selected_member)).id;
    
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
        action:"operation_add",
        type: "child",
        name: name.val(),
        gender: gender.val(),
        sonof: sonof.val()
    }, function(data) {
        //Check if AJAX error occured
        if (ajaxError(data))
        {
            alert("Some Error Occured. Please try again");
            $("#operation_add_name").removeAttr("disabled").val("");
            $("#operation_add_gender").removeAttr("disabled");
            $("#operation_add").slideUp();
            return false;
        }
        else if (ajaxSuccess(data))
        {
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
    member=tree.graph.getNode(selected_member);
    
    //Collect all the members
    x=$("#operation_edit_name,#operation_edit_gender,#operation_edit_relationship,\n\
        #operation_edit_dob,#operation_edit_alive");
    
    //Set their default values
    x[0].value=member.name;
    x[3].value=member.data.dob;
    
    //Find from values and select the given element
    options=x[1].options;
    for (var i=0; i<x[1].options.length; i++ )
    {
        if (options[i].value==parseInt(member.data.gender))
        {
            x[1].selectedIndex=i;
        }
    }
    options=x[2].options;
    for (i=0; i<x[2].options.length; i++ )
    {
        if (options[i].value==parseInt(member.data.relationship_status_id))
        {
            x[2].selectedIndex=i;
        }
    }
    
    $("#operation_edit_dob").datepicker(
    {
        dateFormat:"dd/mm/yy",
        maxDate:'-10y'
    });
    
    options=x[4].options;
    for (i=0; i<x[4].options.length; i++ )
    {
        if (options[i].value==parseInt(member.data.alive_id))
        {
            x[4].selectedIndex=i;
        }
    }
    
    $("#operation_edit_id").val(parseInt(member.id));
    $("#operation_edit").slideDown();
}

function editmember_submit()
{
    //Collect variables values
    x=$("#operation_edit_name,#operation_edit_gender,#operation_edit_relationship,\n\
        #operation_edit_dob,#operation_edit_alive,#operation_edit_id");
    
    if (x[0].value=="")
    {
        alert("Name cannot be left blank");
        x[0].focus();
        return false;
    }
    
    
    //Validate date of birth only if user has entered any
    if (x[4].value!="")
    {      
        var regex= /([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})/.test(x[4].value);
        if (!regex)
        {
            alert("Please enter a valid Date of Birth");
            x[4].focus();
            return false;
        }
    }

    //post the data
    $.post("getdata.php",{
        type:"edit",
        action:"operation_edit",
        "name":x[0].value,
        "gender":x[2].value,
        "relationship":x[3].value,
        "dob":x[4].value,
        "alive":x[5].value,
        memberid:x[1].value
        
    },function (data)
    {
        //Check for AJAX Error
        if (ajaxError(data))
        {
            alert("Some Error Occured. Please try again.");
            return false;
        }
        else if (ajaxSuccess(data))
        {
            var x=$("#operation_edit_name,#operation_edit_gender,#operation_edit_relationship,\n\
        #operation_edit_dob,#operation_edit_alive,#operation_edit_id");
        
            //Set the canvas variables
            member=tree.graph.getNode($("#operation_edit_id").val());
            member.name=x[0].value;
            member.data.relationship_status_id=x[3].value;
            member.data.alive_id=x[5].value;
            member.data.gender=x[2].value;
            member.data.dob=x[4].value;
        
            //Change the displayed data on the screen
            member.data.relationship_status=member.data.relationship_status_id==0 ? "Single" : "Married";
            member.data.alive=member.data.alive_id==0 ?"No" :"Yes";
        
            display_data(member.name,member.data.dob,member.data.relationship_status,member.data.alive,"");
        
            //hide the form
            $("#operation_edit").slideUp();
        
            alert("This information will be displayed once it is accepted by other members.");

        }
    }
    
    );
    
    //Stop redirect
    return false;
}

function modify_details(id,name,dob,gender,relationship,alive)
{
    member=tree.graph.getNode(parseInt(id));
    member.name=name;
    member.data.dob=dob;
    member.data.gender=gender;
    member.data.relationship_status_id=relationship;
    member.data.alive_id=alive;
}

function deletemember() {
    //Details of member
    member=(tree.graph.getNode(selected_member));
    //Details of father
    father=member.getParents();
    
    if (father.length==0)
    {
        //No Parent huh? I am Root!!
        alert("This member cannot be removed");
        return;
    }
        
    $("#operation_remove_son").text(member.name);
    $("#operation_remove_father").text(father[0].name);
    $("#operation_remove_son_id").val(member.id);
    $("#operation_remove").modal();
}

function deletemember_submit()
{
    $("#operation_remove").modal("hide");
    member_id=$("#operation_remove_son_id").val();
    $.post("getdata.php",{
        action:"operation_remove",
        type:"remove",
        "memberid":member_id
    },
    function (data) {
        //Check for Ajax Error
        if (ajaxError(data))
        {
            alert("Some Error Occured. Please try again");
            return false;
        }
        else if (ajaxSuccess(data))
        {
            alert("Member will be removed once it is confirmed by other members");
            return true;
        }
        
    });
}

function suggest()
{
    $.post("getdata.php",{
        action:"getsuggestions"
    },function (data)

    {
            $("#suggest").modal().children(".modal-body").html(data);
        });
    
}

function suggest_action(e,actionid)
{
    var x=$(e).parents("div");
    
    //Get the id of the suggest
    var id=parseInt(x[1].id);
    //perform the suggestion ajax action
    $.post("getdata.php",{
        action:"suggestionapproval",
        suggestid:id,
        suggest_action:actionid
    },function (data)

    {
            //Check for Ajax error
            if (ajaxError(data))
            {
                alert("Some error occured. Please try again");
                return false;
            }
            else if (ajaxSuccess(data))
            {
                data=$.parseJSON(data);
                //Success now hide the suggestion
                $("#"+data.data.suggestid+"suggest").hide("medium");
            }
        });

    
}
function ajaxSuccess(response)
{
    var json;
    if (!(json=$.parseJSON(response)))
    {
        alert("Error Occured while parsing response JSON");
        return false;
    }
    else
    {
        if (json.success==1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

function ajaxError(response)
{
    var json;
    if (!(json=$.parseJSON(response)))
    {
        alert("Error Occured while parsing response JSON");
        return false;
    }
    else
    {
        if (json.success==0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}


function thisisme()
{
    if (!selected_member)
    {
        alert("Please Select a member on the Tree and then clock on This is me.");
        return false;
    }
    else
    {
        var res=confirm("Are you sure?");
        if (res)
        {
            $.post("getdata.php",{
                action:"checkregistered",
                id:selected_member
            },function (data)
            {
                if (ajaxSuccess(data))
                {
                    window.location.assign("thisisme.php?id="+selected_member);
                    return true;
                }
                else if (ajaxError(data))
                {
                                    
                    alert("Someone is already registered with that name");
                    return false;
                }
            });
        }
    }
}

function addwife()
{
    //Get the member whose wife is to be added
    var member=tree.graph.getNode(selected_member);
    
    //Fill in the details of the husband
    $("#operation_addwife_husband_name").text(member.name);
    $("#operation_addwife_husband_id").val(member.id);
    $("#operation_addwife").slideDown();
}


function operation_addwife_submit()
{
    var name = $("#operation_addwife_name").attr("disabled", "yes");
    var husband=$("#operation_addwife_husband_id");
    
    
    //post the information in the suggestion table
    $.post("getdata.php", {
        action:"operation_addwife",
        type: "wife",
        name: name.val(),
        husband: husband.val()
    }, function(data) {
        //Check if AJAX error occured
        if (ajaxError(data))
        {
            alert("Some Error Occured. Please try again");
            $("#operation_addwife_name").removeAttr("disabled").val("");
            $("#operation_addwife_dob").removeAttr("disabled");
            $("#operation_addwife_husband_id").val("");
            $("#operation_addwife_husband_name").text("");
            $("#operation_addwife").slideUp();
            return false;
        }
        else if (ajaxSuccess(data))
        {
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

function addhusband()
{
    //Get the member whose wife is to be added
    var member=tree.graph.getNode(selected_member);
    
    //Fill in the details of the husband
    $("#operation_addhusband_wife_name").text(member.name);
    $("#operation_addhusband_wife_id").val(member.id);
    $("#operation_addhusband").slideDown();
}


function operation_addhusband_submit()
{
    var name = $("#operation_addhusband_name").attr("disabled", "yes");
    var wife=$("#operation_addhusband_wife_id");
    
    
    //post the information in the suggestion table
    $.post("getdata.php", {
        action:"operation_addhusband",
        type: "husband",
        name: name.val(),
        wife: wife.val()
    }, function(data) {
        //Check if AJAX error occured
        if (ajaxError(data))
        {
            alert("Some Error Occured. Please try again");
            $("#operation_addhusband_name").removeAttr("disabled").val("");
            $("#operation_addhusband_dob").removeAttr("disabled");
            $("#operation_addhusband_wife_id").val("");
            $("#operation_addhusband_wife_name").text("");
            $("#operation_addhusband").slideUp();
            return false;
        }
        else if (ajaxSuccess(data))
        {
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