<?php
/**
 * @author Piyush
 * @copyright 2011
 */
require("../header.php");
connecttodatabase();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<head>
    <link href="../style.css" rel="stylesheet" type="text/css" />
    <link href="../../ajax/css/smoothness/jquery-ui-1.8.14.custom.css" rel="stylesheet" type="text/css" />
    <title>Bansavali</title>
    <script type="text/javascript" src="../../ajax/jquery.js"></script>
    <script type="text/javascript" src="../../ajax/jquery-ui.js"></script>
    <script type="text/javascript" src="../inputbox.js"></script>
    <script type="text/javascript" src="../leftoption.js"></script>
    <script type="text/javascript" src="../working.js"></script>
    <style type="text/css">
    </style>
    <script type="text/javascript">
        function validateemail(x)
        {
            var atpos=x.indexOf("@");
            var dotpos=x.lastIndexOf(".");
            if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
            {
                alert("Not a valid e-mail address");
                return false;
            }
            else
            {
                return true;
            }
        }
        function validateform()
        {
            if (validateemail(document.adminapproval.emailid.value))
            {
                document.adminapproval.submit();
            }
            else
            {
                return false;
            }
                
        }
    </script>
</head>
<body>
    <?php
    if (!$_POST['done']) {
        $sonid = $_GET['sonid'];
        $fatherid = $_GET["fatherid"];
        ?>
        <table style="height: 100%; width: 100%; margin-top: 15px;">
            <tr>
                <td style="background-color: #9FE7FA; padding: 15px;">
                    <span style=" padding: 5px; font: 35px normal;">Wait for Admin approval!</span>
                </td></tr>
            <tr><td>
                    <form action="adminapproval.php" method="post" enctype="multipart/form-data" name="adminapproval" onsubmit="return validateform()">
                        <table align="center" cellspacing="7">
                            <tr><td colspan="2" align="center" style="font-size: 30px;">Information Provided by you

                                </td>
                            </tr>
                            <tr>
                                <?php
                                $query = executequery("Select * from member where id=" . $sonid);
                                $query2 = executequery("Select * from member where id=" . $fatherid);
                                if (mysql_result($query, 0, "sonof") != $fatherid) {
                                    die("Some Error Occured. I will fix this soon.");
                                }
                                ?>
                                <td>
                                    Name:
                                </td>
                                <td>
                                    <?= mysql_result($query, 0, "membername") ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Father's Name:
                                </td>
                                <td>
                                    <?= mysql_result($query2, 0, "membername") ?>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    You can also provide your photo If you want. You can upload it here:
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="file" name="pic" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    Add a personal message, It will help the admin to recognize you
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <textarea id="personalmessage" name="personalmessage" rows="4" cols="50"></textarea>
                                </td>
                            </tr>
                            <tr><td>
                                    Email Id:
                                </td>
                                <td>
                                    <input name="emailid" type="text"/>
                                </td>
                            <tr><td>
                                    <input type="hidden" value="<?= $_GET['sonid'] ?>" name="sonid"/>
                                    <input type="hidden" value="<?= $_GET['fatherid'] ?>" name="fatherid" />
                                    <input type="submit" value="Done" name="done"/>
                                </td></tr>

                        </table>
                    </form>
                </td>
            </tr>

        </table>
        <?php
    } else {
        $sonid = $_POST['sonid'];
        $fatherid = $_POST['fatherid'];
        $emailid = $_POST['emailid'];
        $personalmessage = $_POST['personalmessage'];
        $filename = $sonid . "_" . $_FILES['pic']['name'];
        $fileext = fileext($_FILES['pic']['name'], false);
        $token=generate_token();
        if ($sonid && $fatherid) {
            //upload pic
            if ($_FILES["pic"]['error'] == 0 && ($_FILES['pic']["type"] == "image/jpeg" || $_FILES['pic']["type"] == "image/gif" || $_FILES['pic']["type"] == "image/png")) {
                if (executequery("insert into joinrequest (formember,pic,personalmessage,emailid) Values ($sonid,'$filename','$personalmessage','$emailid')")) {
                    move_uploaded_file($_FILES['pic']['tmp_name'], "pic/$filename");
                }
                ?>
                <table style="height: 100%; width: 100%; margin-top: 15px;">
                    <tr>
                        <td style="background-color: #9FE7FA; padding: 15px;">
                            <span style=" padding: 5px; font: 35px normal;">Thank You!</span>
                        </td></tr>
                    <tr><td align="center" style="background-color: lightcyan">
                            <h4>You will soon be informed via your email whenever administrator responds to your request. In the mean time you can explore the family tree and find more of your family members<br><a href="../main.php">Home</a></h4>
                        </td>
                    </tr>
                </table>
                <?php
            } else {
                ?>
                <table style="height: 100%; width: 100%; margin-top: 15px;">
                    <tr>
                        <td style="background-color: #9FE7FA; padding: 15px;">
                            <span style=" padding: 5px; font: 35px normal;">Sorry!</span>
                        </td></tr>
                    <tr><td align="center" style="background-color: lightcyan">
                            <h4>Sorry but an error occured while uploading the file. Please try again. :(<br><a href="../main.php">Home</a></h4>
                        </td>
                    </tr>
                </table>
                <?php
            }
        }
    }
    ?>