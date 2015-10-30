<?php
/**
 * @author Piyush
 * @copyright 2011
 */
require("../header.php");
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
        function find_member()
        {
            membername=$("#firstname").val();
            fathername=$("#fatherfirstname").val();
    
            if (membername=="" && fathername=="")
            {
                return;
            }
            $("#result").text("Loading...").css({"text-align":"center","font-size":"24px"}).animate({
                backgroundColor:"#FAB4B4",
        
            },"medium");
    
            $.post("findmember.php",{"firstname":membername,"fatherfirstname":fathername},function (data) {
                if (data!="")
                {
                    $("#result").css({"text-align":"left","font-size":"15px"}).html(data);
                }
                else
                {
                        $("#result").css({"text-align":"left","font-size":"15px"}).html("No matching member! Only insert your firstname and your father's firstname.");
                }
    
            });
        }
        function thisisme(sonid,fatherid,button) //to be executed after the son is found
        {
            button.disabled=true;
            window.location="adminapproval.php?sonid="+sonid+"&fatherid="+fatherid;
        }
        

    </script>
    <link href="../style.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
    body
    {
        margin: 0;
    }
    </style>
</head>
<body>
<div class="title">Vanshavali</div>
<div class="alone_container">
    <table>
        <tr>
            <td class="alone_title">
                <span style=" padding: 5px; font: 35px normal;">Identify Yourself!</span>
            </td></tr>
        <tr>
            <td class="alone_body">
                <span style="font-size: 20px; ">Please Identify yourself by giving the following details before entering The Family. You will not be able to enter the family until the admin approves you.</span>
                <form action="javascript:find_member()" method="post" style="font-size: 20px;">
                    <table align="center" style="margin-top: 50px; " cellspacing="10">
                        <tr><td style="text-align: right;">
                                First Name:</td><td style="text-align: left;"><input type="text" id="firstname"/></td></tr>
                        <tr><td style="text-align: right;">Father's First Name:</td><td style="text-align: left;"><input type="text" id="fatherfirstname"/></td></tr>
                        <tr><td colspan="2" align="center"><input type="button" value="Search" onclick="find_member()" /></td></tr>
                        <tr><td style="text-align: center;" colspan="2"><a href="../mainbody.php">Back to Home</a></td></tr></table></form>
            </td></tr></table>
</div>
    

</body>
