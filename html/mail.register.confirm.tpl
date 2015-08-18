{include file="header.tpl"}
<html>
    <body>
        <div class="well">
            <h3 align='center'>Welcome to Family!</h3><br>
            Hi,<br>
            Welcome to Family {$username}. This mail is just to notify that you have been registered into FamilyTree.
            You can now log in with your username and password. So you can start editing the Family and correct things that
            aren't correct. Any edit made in FamilyTree will only be applied If other member accept it. With now we are now 
            one step closer to make FamilyTree a ONE BIG HAPPY FAMILY.
            
            {if $not_connected}
                Looks like you are not connected in FamilyTree currently. Well, there is no hurry. You can do it whenever you want.
            {/if}    
            Here are your details:<br>
            Username: {$username}<br>
            Password: ********<br>
            <br><br>
            Thanks, Keep Visiting<br>
            Admin, FamilyTree
        </div>
    </body>
</html>
{include file="footer.tpl"}