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
//	register();
}
);
//Register Related Functions
function register()
{  
    //Show the register dialog
    $("#register").modal();
    
    
    //Function to display text with the input controls in the register form
    function success_display(id,display_text)
    {
        //hide the element, insert the text and the tick, and then show
        $("#"+id).parents(".control-group").addClass("success");
        $("#"+id).siblings(".help-text").css("display","none").innerHTML(display_text)
        .fadeIn(500);
    }
    
    //Function to hide success message when value changed
    function success_hide(id)
    {
        $("#"+id).siblings("span.help-text").fadeIn("medium");
        $("#"+id).parents("div.control-group").removeClass("success");
    }
    
    
    //Initialize constraints and checks
    //Name related checks
    $("#register_name").autocomplete({
        source: function(request, response) {
            var newarray = new Array() //This array will hold all the results
            
            
            $.getJSON("register_username.php?action=register&pt=" + request.term, "", function(data) {
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
            
            //save the selected member id in the hidden input
            $("#register_id").val(ui.item.value);
			
            //show the success text
            success_display("register_name","Correct Name")
            return false;
            
        },
        focus: function() {
            return false;
        }
    });
	
    //end of name related checks
	
    //Username related checks
    $("#register_username").blur(function () //when user leaves the control
    {
        $.post("getdata.php",{
            action:"username_check",
            username:this.value
            },function (data)

            {
            var json=$.parseJSON(data);
            if (json.yes) //if reply is yes the username is already used 
            {
                alert("Username is already used. Try something else");
                $(this).focus();
                //hide any previous success message
                success_hide("register_username");
            }
            else //else not used and can be used
            {
                success_display("register_username","Valid Username");
            }
        });
    });
    //end of username related checks
	
	
    //Password related checks
    $("#register_confirmpassword").blur(function ()
    {
        var password=$("#register_password");
        if (this.value==password.val())
        {
            success_display("register_confirmpassword","Password Matched");
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
        maxDate:"-1d",
        dateFormat:"dd/mm/yy"	
    });
	
    //End of Date of Birth Related Checks
	
	
    //Email related checks
    $("#register_email").blur(function () {
        x=this.value;
        var atpos=x.indexOf("@");
        var dotpos=x.lastIndexOf(".");
        if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
        {
            success_hide("register_email");
            alert("Not a valid e-mail address");
            $(this).focus();
        }
        else
        {
            //display success text
            success_display("register_email","Valid Email Id");
        }
    });
    //end of email related check
	
    //All checks performed now we can submit the form
    $("#register_form").submit(function () {
		
        //will remain true if everything is filled out
        var passed=true;
        var check=$("#register_name,#register_username,#register_password,#register_confirmpassword,#register_dob,#register_email");
        $.each(check,function (key,value) {
            if (value.value=='') //if value of specified element is empty
            {
                value.focus(); //focus on that variable
                passed=false;  //set passed to false;
                return false;
            }
        });
        return passed;
    });
	
}

//end of register relateed Functions

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
            //positon the dialog in the center as #login_error is now visible
            $("#login").modal();
            
            
        // if successfull login
        }
        if (parseInt(data.error) == 2)
        {
            $("#login_error").html("Please activate your account by with the link sent to you email");
            $("#login_error").html("Login Failed! Please check your username and password and try again!");
            $("#login_username").removeAttr("disabled").val("");
            $("#login_password").removeAttr("disabled").val("");
            //positon the dialog in the center as #login_error is now visible
            $("#login").dialog();
        } else {
            if (selected_member) { //if member is selected
                window.location.assign("index.php#" + selected_member);
            } else { //else if no member is selected ie selected_memberis null
                window.location = "index.php";
            }
        }
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
    if (!is_authenticated) {
        login(); //on authenticated members allowed
        return;
    }
    
    //name and id of parent from tree.graph instance
    father_name=(tree.graph.getNode(selected_member)).name;
    father_id=(tree.graph.getNode(selected_member)).id;
    v=document.createTextNode(father_name);
    
    //add to the form
    $("#operation_add_sonof_name").append(v);
    $("#operation_add_sonof_id").val(father_id);
    
    //show the dialog
    $("#operation_add").modal();
	
}
function operation_addmember_submit() {
    //get the values and disable the controls
    var name = $("#operation_add_name").attr("disabled", "yes");
    var gender = $("#operation_add_gender").attr("disabled", "yes");
    var sonof = $("#operation_add_sonof_id");
    
    //post the information in the suggestion table
    $.post("suggestion/suggest.php", {
        type: "child",
        name: name.val(),
        gender: gender.val(),
        sonof: sonof.val()
    }, function() {
	   
       
        //close the dialog
        $("#operation_add").modal("hide");
        
        //enable the controls and set the value to ""
        $("#operation_add_name").removeAttr("disabled").val("");
        $("#operation_add_gender").removeAttr("disabled");
        
        //remove any previous text node from #operation_add_sonof_name and reset
        $("#operation_add_sonof_name").html("<input type='hidden' id='operation_add_sonof_id' />");
        
        
        alert("The changes will be viewed permanently in the Family Tree once it is confirmed by other members. Thanks for contributing");
    
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