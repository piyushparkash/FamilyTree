<?php
/**
 * @author Piyush
 * @copyright 2011
 */
/*
  error codes
  1 no such father found
  2 no such son found
  3 already dead
 */
require("../header.php");
connecttodatabase();
$firstname = $_POST["firstname"];
$fatherfirstname = $_POST["fatherfirstname"];

if ($firstname != "" && $fatherfirstname != "") {
    $query = executequery("SELECT * FROM member WHERE membername LIKE '" . $firstname . "%'");
    while ($row = mysql_fetch_array($query)) {
        $fatherid = $row['sonof'];

        if ($fatherid != -1) {
            $query2 = executequery("select * from member where id=" . $fatherid);
            if (!stristr(mysql_result($query2, 0, "membername"), $fatherfirstname) == False) {
                ?>
                <table cellspacing="3" style="background-color: greenyellow" width="100%">
                    <tr><td><?= $row['membername'] ?></td></tr>
                    <tr><td><?= mysql_result($query2, 0, "membername") ?></td></tr>
                    <tr><td><button onclick="thisisme(<?= $row['id'] ?>,<?= mysql_result($query2, 0, "id") ?>,this)" >This is Me!</button></td></tr>
                </table>
                <?php
            }
        }
    }
}
?>
