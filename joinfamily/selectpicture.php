<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require("../header.php");
connecttodatabase();
if ($_POST['chack']) {
    if ($_POST['usesame'] != 1) {
        $filename = $_POST['id'] . "_" . $_FILES['profilepic']['name'];

        if ($_FILES["profilepic"]['error'] == 0 && ($_FILES['profilepic']["type"] == "image/jpeg" || $_FILES['profilepic']["type"] == "image/gif" || $_FILES['profilepic']["type"] == "image/png")) {
            if (executequery("update member set profilepic='" . $filename . "' where id=" . $_POST['id'])) {
                move_uploaded_file($_FILES['profilepic']['tmp_name'], "../images/user/$filename");
                $query = executequery("select * from member where id=" . $_POST['id']);
                $row = mysql_fetch_array($query);
                authenticateuser($_POST['id'], $row['membername']);
                header("Location:../main.php");
            } else {
                die("error occured");
            }
        }
    } else {
        $query = executequery("select * from member where id=" . $_POST['id']);
        $row = mysql_fetch_array($query);

        if (copy($_SERVER['DOCUMENT_ROOT'] . "/joinfamily/pic/" . str_replace($row['profilepic'], " ", "%20"),  $_SERVER['DOCUMENT_ROOT'] . "/images/user/" . str_replace($row['profilepic'], " ", "%20"))) {
            unlink("pic/" . $row['profilepic']);
        } else {
            echo urlencode($_SERVER['DOCUMENT_ROOT'] . "/joinfamily/pic/" . $row['profilepic']);
            echo "<br>";
            echo urlencode( $_SERVER['DOCUMENT_ROOT'] . "/images/user/" . $row['profilepic']);
            die("For some reason not able to upload your profile pic");
        }
        header("Location:../main.php");
    }
}
$query = executequery("select * from member where id=" . $_GET['id']);
$row = mysql_fetch_array($query);
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
            function chosepic()
            {
                document.selectpicture.usesame.value=1;
                document.selectpicture.profilepic="";
                document.selectpicture.submit();
            }
        
            function submitfrm()
            {
                if (document.selectpicture.profilepic.value=="") 
                { 
                    alert("Please select a image");
                }
                else
                {
                    document.selectpicture.submit();
                }
            
            }
        </script>
    </head>
    <body>
        <form name="selectpicture" method="post" action="selectpicture.php" enctype="multipart/form-data">
            <table align="center" style="margin-top: 50px;" cellpadding="3" cellspacing="5">
                <tr><td colspan="2" align="center">
                        <img src="../images/bansavali.png" />
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <span style="font-weight: bold;">Use This Image</span>
                    </td>
                    <td align="center">
                        <span style="font-weight: bold;">OR Upload a Image</span>
                    </td>

                </tr>
                <tr>
                    <td style="border-right:#51a7b6 3px solid;" align="center">
                        <a href="javascript:chosepic()" style="border:none" ><img src="pic/<?= $row['profilepic'] ?>" width="300px" height="300px" onclick="chosepic()" /></a>
                    </td>
                    <td align="center">
                        <input type="file" name="profilepic" id="profilepic"  align="center"/>
                        <br />
                        <br />
                        <br />
                        <input type="button" onclick="submitfrm()" name="done" id="done"  value="Upload"/>
                        <input type="hidden" name="usesame" id="usesame" value="0" />
                        <input type="hidden" name="chack" id="chack" value="500" />
                        <input value="<?= $_GET['id'] ?>" name="id"  type="hidden" id="id"/>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>