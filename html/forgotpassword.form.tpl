{include file="header.tpl"}
<style type="text/css">
    label
    {
        font-weight: bold;
    }

    form#forgotpasswordform
    {
        margin: 5% auto;
        width: 35%;

    }
</style>
<form name="forgotpasswordform" id="forgotpasswordform" action="forgotpassword.php" method="post" class="container-fluid form-horizontal well">
    <fieldset>
        <legend>Reset your Password!</legend>
        {if $oldpassworderror}
        <div class="alert alert-error">Please enter correct Old Passwword!</div>
        {/if}
        <div class="form-group">
            <label for="old_password" class="control-label">Your Old Password:</label>
            <div class="controls">
                <input type="text" id="old_password" name="old_password"/>
                <input type="hidden" id="tokenforact" name="tokenforact" value="{$tokenforact}" />
                <span  class="help-block"></span>
            </div>
        </div>
        <div class="form-group">
            <label for="new_password" class="control-label">Type in your New Password:</label>
            <div class="controls">
                <input type="password" id="new_password" class="new_password" />
                <span class="help-block"></span>
            </div>
        </div>
        <div class="form-group">
            <label for="new_password_again" class="control-label">Type again your New Password:</label>
            <div class="controls">
                <input type="password" id="new_password_again" class="new_password_again" />
                <span class="help-block"></span>
            </div>
        </div>
        <div class="form-actions">
            <input type="submit" value="Change Password" name="password_submit" class="btn btn-large btn-primary" />
            <input type="reset" value="Reset" class="btn btn-large" />
        </div>
    </fieldset>
</form>
<script type="text/javascript">
    $("#forgotpasswordform").submit(function () {

        //Validate if each given input has ok attached to it.
        var passed = true;
        var allFields = $("#old_password, #new_password, #new_password_again");

        $.each(allFields, function (key, value)
        {
            if (value.attr("passed") != "ok")
            {
                passed = false;
            }
        });

        //Check the passed variable and then submit the form
        return passed;
    });

    //Blur element to each of the required fields and set passed ok when all done.
    $("old_password").blur(function () {

        //Check if the field is not empty
        if (this.value == '')
        {
            return false;
        }
        else
        {
            this.attr("passsed", "ok");
        }
    });
    
    $("#new_password").blur(function () {
        
        //check if the field is empty
        if (this.value == '')
        {
            return false;
        }
        else
        {
            this.attr("passed","ok");
        }
    });
    
    $("#new_password_again").blur(function () {
        
        //match it with above one
        
        origPassword = $("#new_password");
        
        if (this.value != '' && this.value == origPassword.value)
        {
            this.attr("passed", "ok");
        }
        else
        {
            return false;
        }
    });
</script>

{include file="footer.tpl"}
