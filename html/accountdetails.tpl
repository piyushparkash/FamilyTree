<div class="container">
    <div class="col-md-12">
        <div class="col-md-2">

        </div>
        <div class="col-md-8">
            <form class="form-horizontal well" method="post" action="register.php?mode=accountdetails&sub=2" id="accountdetails">
                <fieldset>

                    <!-- Form Name -->
                    <legend>Account Details</legend>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="username">Username</label>  
                        <div class="col-md-5">
                            <input id="username" name="username" type="text" placeholder="Username" class="form-control input-md" required="">

                        </div>
                    </div>

                    <!-- Password input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="password">Password</label>
                        <div class="col-md-5">
                            <input id="password" name="password" type="password" placeholder="Password" class="form-control input-md" required="">

                        </div>
                    </div>

                    <!-- Password input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="confirmpassword">Confirm Password</label>
                        <div class="col-md-5">
                            <input id="confirmpassword" name="confirmpassword" type="password" placeholder="Confirm Password" class="form-control input-md" required="">

                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="email">Email</label>  
                        <div class="col-md-5">
                            <input id="email" name="email" type="email" placeholder="Email" class="form-control input-md" required="">

                        </div>
                    </div>
                    {foreach from=$pastdata key=k item=v}
                        <input type="hidden" value="{$v}" name="{$k}" />
                    {/foreach}

                    <!-- Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="submit"></label>
                        <div class="col-md-4">
                            <input type="submit" id="accountdetailsubmit" name="accountdetailsubmit" class="btn btn-primary" value="Submit"/>
                        </div>
                    </div>

                </fieldset>
            </form>
        </div>
        <div class="col-md-2">

        </div>
    </div>
</div>
<script type="text/javascript">
    $("#accountdetails").validate({
        debug: false,
        rules: {
            username:{
                required: true,
                remote: {
                    url: "getdata.php",
                    type: "post",
                    data: {
                        action: "username_check"
                    }
                    
                }
            },
            password: {
                required: true,
            },
            confirmpassword: {
                required: true,
                equalTo: "#password"
            }
        },
        messages:{
            username: {
                required: "Enter a username",
            }
            
        }
    });
</script>