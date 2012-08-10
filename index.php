<?php require 'header.php';
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
    preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})/", $_POST['register_dob'],
        $matches);
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
			<link type="text/css" href="css/base.css" rel="stylesheet" />
			<link type="text/css" href="css/Spacetree.css" rel="stylesheet" />
			<link href="../ajax\css\smoothness\jquery-ui-1.8.14.custom.css" rel="stylesheet" type="text/css" />
			<link href="style.css" rel="stylesheet" type="text/css" />
			<!--[if IE]>
				<script language="javascript" type="text/javascript" src="../../Extras/excanvas.js">
				</script>
			<![endif]-->
			<!-- JIT Library File -->
			<script type="text/javascript" src="../ajax/jquery.js">
			</script>
			<script type="text/javascript" src="../ajax/jquery-ui.js">
			</script>
			<script type="text/javascript" src="jit.js">
			</script>
			<script type="text/javascript" src="working.js">
			</script>
			<script type="text/javascript" src="example1.js">
			</script>
		</head>
		<body onload="init();" >
		<?php
if ($posted) { ?>
		<div style="display: none;" id="firstlogininfo">
		A confirmation email has been sent to you. Please check your email and click on the link to activate your account.
		</div>
		<script type="text/javascript">
		
		</script>
		<?php
}
?>
			<div id="firsttimeinfo" style="display:none">
				Welcome,
				<br />
				Family Tree shows all the ancestors of our Family. Click on a member to view their relatives. You can also drag the page to adjust the view.
			</div>
			<div id="operation" align="center">
				<ul style="list-style-type:none; margin: 0px; padding-left: 0px;">
					<li class="operation_options" onclick="operation_addmember()">
						Add Member
					</li>
					<li class="operation_options" onclick="deletemember()">
						Remove Member
					</li>
					<li class="operation_options">
						Edit Member
					</li>
					<li class="operation_options" onclick="search()">
						Search
					</li>
					<li class="operation_options" onclick="feedback()">
						Feedback
					</li>
				</ul>
			</div>
			<div id="vanshavali_user" align="center">
				<ul style="list-style-type:none; margin: 0px; padding-left: 0px;">
					<li class="operation_options" style="font-size:20px">
						Vanshavali
					</li>
					<?php if (!is_authenticated()) { ?>
						<li class="operation_options" style="font-size:20px" onclick="login()">
							Login
						</li>
						<?php } else { ?>
							<script type="text/javascript">
								is_authenticated = true;
							</script>
							<li class="operation_options" style="font-size:20px">
								<?= $_COOKIE['membername'] ?>
									</li>
									<?php } ?>
										</ul>
										</div>
										<div id="search">
											<form onsubmit="return">
												<ul class="pagination">
													<li style="width: 100%;">
														Please enter name of member to search.
													</li>
													<li>
														Name:&nbsp;&nbsp;
														<input type="edit" id="search_term" />
													</li>
													<li style="text-align: center;">
														<input type="button" value="Search" />
													</li>
													<li id="search_result">
													</li>
												</ul>
											</form>
										</div>
										<div id="login">
											<form method="post" onsubmit="return login_submit()">
												<table>
													<tr>
														<td colspan="2" align="center" id="login_error" style="color:red">
														</td>
													</tr>
													<tr>
														<td>
															Username:
														</td>
														<td>
															<input type="edit" id="login_username" />
														</td>
													</tr>
													<tr>
														<td>
															Password:
														</td>
														<td>
															<input type="password" id="login_password" />
														</td>
													</tr>
													<tr>
														<td colspan="2" align="center">
															<input type="submit" value="login"/>
														</td>
													</tr>
													<tr>
														<td colspan="2" align="center">
															Not a member?
															<input type="button" value="Join Family" onclick="register()" />
														</td>
													</tr>
												</table>
											</form>
										</div>
										<div id="operation_add" style="display:none;">
											<form method="post" onsubmit="return operation_addmember_submit()">
												<table cellpadding="5" cellspacing="5">
													<tr>
														<td>
															Name:
														</td>
														<td>
															<input type="edit" id="operation_add_name" />
														</td>
													</tr>
													<tr>
														<td>
															Son of:
														</td>
														<td id="operation_add_sonof_name">
															<input type="hidden" id="operation_add_sonof_id" />
														</td>
													</tr>
													<tr>
														<td>
															Gender:
														</td>
														<td>
															<select id="operation_add_gender">
																<option value="1">
																	Male
																</option>
																<option value="0">
																	Female
																</option>
															</select>
														</td>
													</tr>
													<tr>
														<td colspan="2">
															<input type="submit" value="Add" />
														</td>
													</tr>
												</table>
											</form>
										</div>
										<div id="feedback_form" style="display:none;">
											<table>
												<tr>
													<td colspan="2">
													You can submit any complaint or suggestion here. Just fill out the form.
													</td>
												</tr>
												<tr>
													<td>
														Name:
													</td>
													<td>
														<input type="edit" id="feedback_name" />
													</td>
												</tr>
												<tr>
													<td>
														E-mail id:
													</td>
													<td>
														<input id="feedback_email" type="edit" />
													</td>
												</tr>
												<tr>
													<td>
														Suggestion/Complaint:
													</td>
													<td>
														<textarea id="feedback_text" rows="10" cols="50">
														</textarea>
													</td>
												</tr>
												<tr>
													<td align="center" colspan="2">
														<input type="button" value="Submit" onclick="submit_feedback()"/>
													</td>
												</tr>
											</table>
										</div>
										<div id="infovis">
										</div>
										<div id="right-container">
											<h3 id="display_name" style="text-align: center; width: 100%;">
											</h3>
											<table>
												<tr>
													<td>
														Date Of Birth:
													</td>
													<td id="display_dob">
													</td>
												</tr>
												<tr>
													<td>
														Relationship Status:
													</td>
													<td id="display_relationship">
													</td>
												</tr>
												<tr>
													<td>
														Alive:
													</td>
													<td id="display_alive">
													</td>
												</tr>
											</table>
										</div>
										<div id="register" style="display: none;">
											<form method="post" id="register_form" action="index.php">
												<fieldset>
													<legend>
														Select Member
													</legend>
													<table style="width: 100%;">
														<tr>
															<td>
																Your Name:
															</td>
															<td>
																<input type="text" id="register_name" />
																<input type="hidden" id="register_id" name="register_id"/>
															</td>
															<td id="register_name_check" class="successbox small">
																
															</td>
														</tr>
													</table>
												</fieldset>
												<fieldset>
													<legend>
														Login Details
													</legend>
													<table>
														<tr>
															<td>
																Username:
															</td>
															<td>
																<input type="text" id="register_username" name="register_username"/>
															</td>
															<td id="register_username_check" class="small successbox">
																
															</td>
														</tr>
														<tr>
															<td>
																Password:
															</td>
															<td>
																<input type="password" id="register_password" name="register_password"/>
															</td>
														</tr>
														<tr>
															<td>
																Confirm Password:
															</td>
															<td>
																<input type="password" id="register_confirmpassword" />
															</td>
															<td class="successbox small" id="register_confirmpassword_check">
																
															</td>
														</tr>
													</table>
												</fieldset>
												<fieldset>
													<legend>
														Personal Details
													</legend>
													<table>
														<tr>
															<td>
																Date Of Birth:
															</td>
															<td>
																<input type="text" id="register_dob" name="register_dob"/>
															</td>
														</tr>
														<tr>
															<td>
																Gender:
															</td>
															<td>
																<select id="register_gender" name="register_gender">
																	<option selected="selected" value="0">
																		Male
																	</option>
																	<option value="1">
																		Female
																	</option>
																</select>
															</td>
														</tr>
														<tr>
															<td>
																Relationship Status:
															</td>
															<td>
																<select id="register_relationship" name="register_relationship">
																	<option selected="selected" value="0">
																		Single
																	</option>
																	<option value="1">
																		Married
																	</option>
																</select>
															</td>
														</tr>
														<tr>
															<td>
																Village( Gaon ):
															</td>
															<td>
																<input type="text" id="register_gaon" name="register_gaon"/>
															</td>
														</tr>
														<tr>
															<td>
																Email Id:
															</td>
															<td>
																<input type="text" id="register_email" name="register_email"/>
															</td>
															<td class="successbox small" id="register_email_check">
																
															</td>
														</tr>
														<tr>
															<td>
																Litte About you (BIO):
															</td>
															<td>
																<textarea style="font-family: monospace;" rows="8" placeholder="Tell us something..." name="register_about" >
																</textarea>
															</td>
														</tr>
														<tr>
															<td colspan="3" style="text-align: center;">
																<input type="submit" value="Register" name="register_submit" />
															</td>
														</tr>
													</table>
												</fieldset>
											</form>
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