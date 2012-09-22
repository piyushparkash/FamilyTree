<div id="register" class="modal hide form-horizontal">
            <div class="modal-header">
                Register
            </div>
            <div class="modal-body">
                <form method="post" id="register_form" action="index.php">
                    <fieldset>
                        <legend>
                            Select Member
                        </legend>
                        <div class="control-group">
                            <label for="register_name" class="control-label">Your Name:</label>
                            <div class="controls">
                                <input type="text" id="register_name" />
                                <input type="hidden" id="register_id" name="register_id"/>
                                <span  class="help-block"></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="register_username" class="control-label">Username</label>
                            <div class="controls">
                                <input type="text" id="register_username" name="register_username"/>
                                <span class="help-block" ></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="register_password" class="control-label">Password</label>
                            <div class="controls">
                                <input type="password" id="register_password" name="register_password"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="register_confirmpassword" class="control-label">Confirm Password</label>
                            <div class="controls">
                                <input type="password" id="register_confirmpassword" />
                                <span class="help-block" ></span>
                            </div>
                        </div>
                    </fieldset>
                    <div class="control-group">
                        <label class="control-label" for="register_dob">Gender</label>
                        <div class="controls">
                            <input type="text" id="register_dob" name="register_dob"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="register_gender" class="control-label">Gender</label>
                        <div class="controls">
                            <select id="register_gender" name="register_gender">
                                <option selected="selected" value="0">
                                    Male
                                </option>
                                <option value="1">
                                    Female
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="register_relationship">Relationship Status</label>
                        <div class="controls">
                            <select id="register_relationship" name="register_relationship">
                                <option selected="selected" value="0">
                                    Single
                                </option>
                                <option value="1">
                                    Married
                                </option>
                            </select>
                        </div>
                        <div class="control-group">
                            <label for="register_gaon" class="control-label">Village ( gaon )</label>
                            <div class="controls">
                                <input type="text" id="register_gaon" name="register_gaon"/>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="register_email" class="control-label">Email Id:</label>
                        <div class="controls">
                            <input type="text" id="register_email" name="register_email"/>
                            <span class="help-block" ></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="register_about">Little about you</label>
                        <div class="controls">
                            <textarea style="font-family: monospace;" placeholder="Tell us something..." name="register_about" >
                            </textarea>
                        </div>
                    </div>
                    <div class="form-actions">
                        <input type="submit" value="Register" name="register_submit" />
                        <input type="reset" value="Reset" />
                    </div>
                </form>
            </div>

        </div>