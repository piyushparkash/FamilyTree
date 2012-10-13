var is_authenticated = false;
$(document).ready(function () {
    $("#firstlogininfo").modal();
    $("#firsttimeinfo").modal();
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
            tree.select(ui.item.value);
            
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
    $.post("feedback.php", {
        name: name,
        email: email,
        message: message
    }, function(data) {
        $("#feedback_form").modal("hide");
        alert("Thank you for contributing!");
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
    }, function() {
        
        //enable the controls and set the value to ""
        $("#operation_add_name").removeAttr("disabled").val("");
        $("#operation_add_gender").removeAttr("disabled");
        
        //remove any previous text node from #operation_add_sonof_name and reset
        $("#operation_add_sonof_name").html("<input type='hidden' id='operation_add_sonof_id' />");
        
        
        alert("The changes will be viewed permanently in the Family Tree once it is confirmed by other members. Thankyou for your contribution");
        $("#operation_add").slideUp();
    });
    
    //stop the form from redirecting
    return false;
}
function editmember() {
    if (!is_authenticated)
    {
        //if not authenticated return login form
        login();
        return;
    }
    
    
}
function deletemember() {
    if (!is_authenticated) {
        login();
        return;
    }
    $("#operation_remove").modal();
}