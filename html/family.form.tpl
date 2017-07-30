<form method="post" id="family_form" action="index.php?mode=setupAdmin&sub=firstfamilypost" class="single-dialog form-horizontal well well-large">
    <fieldset>
        <legend>
            Add name of you Family:
        </legend>
        <div class="form-group">
            <label for="family_name" class="control-label">Enter your family name:</label>
            <div class="controls">
                <input type="text" id="register_name" name="family_name" required/>
                <span  class="help-block">This name will be used sometimes when refering any member
                    of your Family such as Gupta's Family would enter Gupta</span>
            </div>
        </div>
        <div class="form-actions">
            <input type="submit" value="Add Family" name="family_submit" class="btn btn-large btn-primary" />
            <input type="reset" value="Reset" class="btn btn-large" />
        </div>
</form>
</div>
</div>

<script type="text/javascript">
//Function to display text with the input controls in the register form
    function success_display(id, display_text)
    {
        //hide the element, insert the text and the tick, and then show
        $("#" + id).parents(".form-group").addClass("success");
        $("#" + id).siblings(".help-text").css("display", "none").text(display_text)
                .fadeIn(500);
    }

    //Function to hide success message when value changed
    function success_hide(id)
    {
        $("#" + id).siblings("span.help-text").fadeIn("medium");
        $("#" + id).parents("div.form-group").removeClass("success");
    }

    //All checks performed now we can submit the form
    $("#family_form").submit(function () {

        //will remain true if everything is filled out
        var passed = true;


        var check = $("#family_name");
        $.each(check, function (key, value) {
            if (value.value == '') //if value of specified element is empty
            {
                value.focus(); //focus on that variable
                passed = false;  //set passed to false;
                return false;
            }
        });
        return passed;
    });

</script>
