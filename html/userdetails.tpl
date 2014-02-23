<div class="container">
    <div class="col-md-12">
        <div class="col-md-2">

        </div>
        <div class="col-md-8">
            <form class="form-horizontal well" id="userdetails" method="post" action="register.php?mode=userdetails&sub=2">
                <fieldset>

                    <!-- Form Name -->
                    <legend>Basic Details About You</legend>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="name">Name</label>  
                        <div class="col-md-5">
                            <input id="name" name="name" type="text" placeholder="Your Name" class="form-control input-md" required="">

                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="dob">Birthday</label>  
                        <div class="col-md-5">
                            <input id="dob" name="dob" type="text" placeholder="Birthdate" class="form-control input-md" required="">

                        </div>
                    </div>

                    <!-- Select Basic -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="gender">Gender</label>
                        <div class="col-md-5">
                            <select id="gender" name="gender" class="form-control">
                                <option value="0">Male</option>
                                <option value="1">Female</option>
                            </select>
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="village">Village</label>  
                        <div class="col-md-5">
                            <input id="village" name="village" type="text" placeholder="Village" class="form-control input-md" required="">

                        </div>
                    </div>

                    <!-- Textarea -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="aboutme">About Me</label>
                        <div class="col-md-4">                     
                            <textarea class="form-control" id="aboutme" name="aboutme">Tell us somethings about yourself...</textarea>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="userdetailsubmit"></label>
                        <div class="col-md-4">
                            <input type="submit" name="userdetailsubmit" id="userdetailsubmit" class="btn btn-primary" />
                        </div>
                    </div>

                </fieldset>
            </form>

        </div>
        <div class="col-md-2">

        </div>
    </div>
</div>
<script type='text/javascript'>
    $("#userdetails").validate();
    $("#dob").datepicker(
            {
                dateFormat: "dd/mm/yy",
                maxDate: '-10y'
            });
</script>