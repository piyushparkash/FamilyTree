<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require("../header.php");
connecttodatabase();
global $query, $row;
if ($_GET['id']) {
    $query = executequery("select * from joinrequest where id=" . $_GET['id']);
    $row = mysql_fetch_array($query);
} elseif ($_POST['submitform']) {
    $membername = $_POST['membername'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fathername = $_POST['fathername'];
    preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})/", $_POST['dob'], $matches);
    $dob = mktime(0, 0, 0, $matches[2], $matches[1], $matches[3]);
    $gender = $_POST['gender'];
    $aboutme = $_POST['aboutme'];
    $emailid = $_POST['emailid'];
    $profilepic = $_POST['profilepic'];
    $formember = $_POST['formember'];
    $query=executequery("select * from member where id=$formember");
    $row=  mysql_fetch_array($query);
    executequery("update member set membername='$fathername' where id=".$row['sonof']);
    $query = executequery("Update member set username='$username',password='$password',membername='$membername',gender=$gender,emailid='$emailid',aboutme='$aboutme',dob=$dob,profilepic='$profilepic' where id=$formember");
    if ($query) {
        executequery("delete from joinrequest where id=" . $_POST['id']);
    }
    header("Location:selectpicture.php?id=$formember");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <link href="../style.css" rel="stylesheet" type="text/css" />
        <link href="../../ajax/css/smoothness/jquery-ui-1.8.14.custom.css" rel="stylesheet" type="text/css" />
        <title>Bansavali</title>
        <script type="text/javascript" src="../../ajax/jquery.js"></script>
        <script type="text/javascript" src="../../ajax/jquery-ui.js"></script>
        <script type="text/javascript" src="../inputbox.js"></script>
        <script type="text/javascript" src="../leftoption.js"></script>
        <style type="text/css">
            .error
            {
                font-size: small;
                color: red;
            }
        </style>
        <script type="text/javascript">
            var errno=0;
            function validateform()
            {
                clearerrors();
                formelement=document.registerform;
                if (!document.registerform.membername.value)
                {
                    raiseerror(document.registerform.membername,"Please enter your name");
                    return;
                }
                if (!document.registerform.username.value)
                {
                    raiseerror(document.registerform.username,"Please enter a username");
                    return;
                }
                if (document.registerform.username.value.indexOf(" ")>0)
                {
                    raiseerror(document.registerform.username,"Username cannot contain spaces");
                    return;
                }
                patt=/^[A-Za-z0-9._]$/
                var usr=document.registerform.username.value;
                if (patt.test(usr))
                {
                    raiseerror(document.registerform.username,"Username can only contain A-Z, a-z, 0-9, . and _");
                    return;
                }
                else
                {
                    // check for availabilty
                }
                if (!document.registerform.password.value)
                {
                    raiseerror(document.registerform.password,"Please enter a password");
                    return;
                }
                if (document.registerform.password.value!=document.registerform.confirmpassword.value)
                {
                    raiseerror(document.registerform.confirmpassword,"Password do not match");
                    return;
                }
                if (!document.registerform.fathername.value)
                {
                    raiseerror(document.registerform.fathername,"Please enter your father's name");
                    return;
                }
                if (!document.registerform.dob.value)
                {
                    alert("I am here");
                    raiseerror(document.registerform.dob,"Please enter your Date of Birth");
                    return;
                }
                var dob=document.registerform.dob.value;
                var patt=/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4,4}/
                if (!patt.test(dob))
                {
                    raiseerror(document.registerform.dob,"Please check format ( dd/mm/yyyy )");
                    return;
                }
                if (!document.registerform.aboutme.value)
                {
                    raiseerror(document.registerform.aboutme,"please tell us about yourself");
                    return;
                }
                
                //Cleared all the validation now can submit the form
                document.registerform.submit();
            }
                
        
            function raiseerror(errelement,text)
            {
                errno+=1;
                errelement.parentNode.nextSibling.nextSibling.innerHTML="<span class='error' name='error'>"+text+"</span>";
            }
            
            function clearerrors()
            {
                errno=0;
                elements=document.getElementsByName("error");
                alert($(".error").length);
                $(".error").hide("medium").remove();
            }
        </script>
    </head>
    <body>
        <form action="registeruser.php" method="post" name="registerform"  onsubmit="validateform()">
            <table align="center" style="margin-top: 50px" cellpadding="6" cellspacing="6">
                <tr>
                    <td colspan="3" align="center">
                        <img src="../images/bansavali.png" align="top" />
                    </td>
                </tr>
                <tr>
                    <td>
                        Full Name:
                    </td>
                    <td>
                        <input type="edit" name="membername" id="membername"/>
                    </td>
                    <td>

                    </td>
                </tr>

                <tr>
                    <td>
                        Username:
                    </td>
                    <td>
                        <input type="edit" name="username" id="username"/>
                    </td>
                    <td>

                    </td>

                </tr>
                <tr>
                    <td>
                        Password:
                    </td>
                    <td>
                        <input type="password" name="password" id="password"/>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        Confirm Password:
                    </td>
                    <td>
                        <input type="password" name="confirmpassword" id="confirmpassword"/>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        Father's Name:
                    </td>
                    <td>
                        <?php
                        $query2 = executequery("select * from member where id=$row[1]");
                        $row2 = mysql_fetch_array($query2);
                        $query2 = executequery("select * from member where id=" . $row2['sonof']);
                        $row2 = mysql_fetch_array($query2);
                        if (is_null($row2['lastlogin'])) {
                            ?>
                            <input type="edit" name="fathername" value="<?= $row2['membername'] ?>" id="fathername"/>
                            <?php
                        } else {
                            ?>
                            <input type="hidden" value="<?= $row2['membername'] ?>" name="fathername" id="fathername"/><?= $row2['membername'] ?>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Date Of Birth:
                    </td>
                    <td>
                        <input type="edit" name="dob" id="dob"/>
                    </td>
                    <td>
                        Format (dd/mm/yyyy)
                    </td>
                </tr>
                <tr>
                    <td>
                        Gender:
                    </td>
                    <td>
                        <select name="gender" id="gender">
                            <option value="0">Male</option>
                            <option value="1">Female</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        About Yourself:
                    </td>
                    <td>
                        <textarea row="30" cols="45" name="aboutme" id="aboutme">
                        </textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="center"><input type="button" value="Done" name="registerform" onclick="validateform()"/><input type="hidden" name="submitform" id="submitform" value="yes"/>
                        <input type="hidden" value="<?= $row['pic'] ?>" name="profilepic" /><input type="hidden" value="<?= $row['emailid'] ?>" name="emailid"/>
                        <input type="hidden" value="<?= $row['formember'] ?>" name="formember" />
                        <input type="hidden" value="<?= $_GET['id'] ?>" name="id" /></td>
                </tr>
            </table>
        </form>
    </body>