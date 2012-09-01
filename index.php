<?php
require 'header.php';
connecttodatabase();

// Global variable to test if user has filled registration form
$posted = false;

// If pressed submit button
if (isset($_POST['register_submit'])) {
    //Make global variable true
    $posted = true;
    //get all the posted info
    $id = $_POST['register_id'];
    $username = $_POST['register_username'];
    $password = $_POST['register_password'];
    //convert date into time stamp
    preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})/", $_POST['register_dob'], $matches);
    $dob = mktime(0, 0, 0, $matches[2], $matches[1], $matches[3]);
    $gender = $_POST['register_gender'];
    $relation = $_POST['register_relationship'];
    $gaon = $_POST['register_gaon'];
    $email = $_POST['register_email'];
    $about = $_POST['register_about'];

    //Generate a token key for activation
    $token = generate_token();

    //Prepare the sql statement
    $sql = "update member set username='$username',password='$password',dob=$dob,gender=$gender,relationship_status=$relation,gaon='$gaon',
	emailid='$email',alive=1,aboutme='$about',joined=" . time() . ",tokenforact='$token' where id=$id";
    // If successful query then
    if (executequery($sql)) {

        // Mail user for activation and Mail confirmation
        vanshavali_mail($email, "Welcome to Vanshavali | Email Confirmation", "
						<html>
						<body>
						<h3 align='center'>Welcome to Family!</h3><br>
						Hi,<br>
						We welcome you to the family. Please click  on the link below to confirm your email address.
						Here are your details:<br>
						Username:$username<br>
						Password:********<br>
						<a href='www.vanshavali.co.cc/activate.php?token=$token&emailid=$email'>Click here to activate your account</a>
						<br><br>
						Thanks, Keep Visiting<br>
						Admin, Vanshavali.co.cc
						</body>
						</html>
						");
    } else {
        die("Some error occurred.Please try again");
    }
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>
            Vanshavali - Place for famliy
        </title>
        <!-- CSS Files -->
        <link href="../ajax\css\smoothness\jquery-ui-1.8.14.custom.css" rel="stylesheet" type="text/css" />
        <link href="style.css" rel="stylesheet" type="text/css" />
        <link type="text/css" href="css/Spacetree.css" rel="stylesheet" />
        <link type="text/css" href="assets/css/bootstrap.css" rel="stylesheet" />

        <!--[if IE]>
                <script language="javascript" type="text/javascript" src="../../Extras/excanvas.js">
                </script>
        <![endif]-->
        <!-- JIT Library File -->
        <script type="text/javascript" src="../ajax/jquery.js">
        </script>
        <script type="text/javascript" src="../ajax/jquery-ui.js">
        </script>
        <script type="text/javascript" src="assets/js/bootstrap-modal.js"></script>
        <script type="text/javascript" src="jit.js">
        </script>
        <script type="text/javascript" src="working.js">
        </script>
        <script type="text/javascript" src="example1.js">
        </script>
    </head>
    <body onload="init();" >
        <?php if ($posted) { ?>
            <div style="display: none;" id="firstlogininfo">
                A confirmation email has been sent to you. Please check your email and click on the link to activate your account.
            </div>
            <script type="text/javascript">
                                                                                		
            </script>
            <?php
        }
        ?>
        <div id="firsttimeinfo" class="modal hide">
            <div class="modal-header">
                Welcome    
            </div>
            <div class="modal-body">
                Family Tree shows all the ancestors of our Family. Click on a member to view their relatives. You can also drag the page to adjust the view.
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn">Close</button>
            </div>
        </div>


        <div id="operation" align="center">
            <div class="btn-group">
                <a class="btn btn-large" onclick="operation_addmember()">Add Member</a>
                <a class="btn btn-large" onclick="deleltemember">Remove Member</a>
                <a class="btn btn-large" >Edit Member</a>
                <a class="btn btn-large" onclick="search()">Search</a>
                <a class="btn btn-large" onclick="feedback()">Feedback</a>
            </div>
        </div>

        <div align="center" id="vanshavali_user">
            <div class="btn-group">
                <a class="btn btn-large">Vanshavali</a>
                <?php if (!is_authenticated()) { ?>
                    <a class="btn btn-large" onclick="login()">Login</a>
                <?php } else { ?>
                    <script type="text/javascript">
                        is_authenticated = true;
                    </script>
                    <a class="btn btn-large">
                        <?= $_COOKIE['membername'] ?>
                    </a>
                <?php } ?>
            </div>
        </div>

        <div id="search" class="modal hide">
            <div class="modal-header">
                Search
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <form onsubmit="return" class="well">
                    <label>Member Name:</label>
                    <input type="text" placeholder="Type name here.." id="search_term" class="span3" />
                    <span class="help-block">Type the name and click search</span>
                    <input type="button" value="Search" class="btn"/>
                </form>
            </div>
        </div>

        <div id="login" class="modal hide">
            <div class="modal-header">
                Login
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <form method="post" onsubmit="return login_submit()" class="form-horizontal well">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="login_username">Username</label>
                            <div class="controls">
                                <input type="text" id="login_username" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="login_password">Password</label>
                            <div class="controls">
                                <input type="password" id="login_password" />
                                <span class="help-block hide" id="login_error">Wrong Username or Password</p>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button class="btn btn-primary" type="submit">Login</button>
                            <button class="btn" onclick="register()">Join Family</button>
                        </div>
                    </fieldset>
                </form>
            </div>

        </div>

        <div id="operation_add" class="modal hide">
            <div class="modal-header">
                Add Member
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <form method="post" onsubmit="return operation_addmember_submit()" class="form-horizontal" >
                    <fieldset>
                        <div class="control-group">
                            <label for="operation_add_name" class="control-label">Name:</label>
                            <div class="controls">
                                <input type="text" id="operation_add_name" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="operation_add_sonof_name" class="control-label">Sonof:</label>
                            <div class="controls">
                                <input type="hidden" id="operation_add_sonof_id" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="operation_add_gender" class="control-label">Gender:</label>
                            <div class="controls">
                                <select id="operation_add_gender">
                                    <option value="1">
                                        Male
                                    </option>
                                    <option value="0">
                                        Female
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button class="btn-primary" type="submit">Add</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div id="feedback_form" class="modal hide">
            <div class="modal-header">
                Feedback
            </div>
            <div class="modal-body form-horizontal">
                <div class="control-group">
                    <label for="feedback_name" class="control-label">Name:</label>
                    <div class="controls">
                        <input type="text" id="feedback_name" />
                    </div>
                </div>
                <div class="control-group">
                    <label for="feedback_email" class="control-label">Email Id</label>
                    <div class="controls">
                        <input id="feedback_email" type="text" />
                    </div>
                </div>
                <div class="control-group">
                    <label for="feedback_text" class="control-label">Suggestion/Complaint</label>
                    <div class="controls">
                        <textarea id="feedback_text">
                        </textarea>
                    </div>
                </div>
                <div class="form-actions">
                    <button onclick="submit_feedback()" class="btn">Submit</button>
                </div>
                </fieldset>
            </div>
        </div>
        <div id="infovis">
        </div>

        <div id="right-container">
            
            <fieldset>
                <div class="row" >
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

                </div>


            </fieldset>
        </div>


        <div id="register" class="modal hide form-horizontal">
            <div class="modal-header">
                Register
            </div>
            <div class="modal-body">
                <form method="post" id="register_form" action="index.php">
                    <fieldset>
                        <legend>
                            Select Member
                        </legend>
                        <div class="control-group">
                            <label for="register_name" class="control-label">Your Name:</label>
                            <div class="controls">
                                <input type="text" id="register_name" />
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
                        <label class="control-label" for="register_dob">Gender</label>
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
                        <div class="control-group">
                            <label for="register_gaon" class="control-label">Village ( gaon )</label>
                            <div class="controls">
                                <input type="text" id="register_gaon" name="register_gaon"/>
                            </div>
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
                            <textarea style="font-family: monospace;" placeholder="Tell us something..." name="register_about" >
                            </textarea>
                        </div>
                    </div>
                    <div class="form-actions">
                        <input type="submit" value="Register" name="register_submit" />
                        <input type="reset" value="Reset" />
                    </div>
                </form>
            </div>

        </div>

        <div style="display: none;" id="operation_edit">
            <table>
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
        </div>
        <div style="display: none;" id="operation_remove">
            Are you sure that <span id="operation_remove_son"></span> is not child of <span id="operation_remove_father"></span>?
        </div>
    </body>

</html>