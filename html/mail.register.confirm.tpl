{include file="header.tpl"}
<html>
    <body>
        <h3 align='center'>Welcome to Family!</h3><br>
        Hi,<br>
        Welcome to Family. Please click  on the link below to confirm your email address.
        Here are your details:<br>
        Username:{$username}<br>
        Password:********<br>
        <a href='www.vanshavali.co.cc/activate.php?token={$token}&emailid={$email}' class="btn">
            Click here to activate your account
        </a>
        <br><br>
        Thanks, Keep Visiting<br>
        Admin, Vanshavali.co.cc
    </body>
</html>
{include file="footer.tpl"}