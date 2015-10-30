<div id="feedback_form" class="modal hide">
    <div class="modal-header">
        Feedback
    </div>
    <div class="modal-body form-horizontal">
        <div class="control-group">
            <label for="feedback_name" class="control-label">Name:</label>
            <div class="controls">
                <input type="text" id="feedback_name" />
            </div>
        </div>
        <div class="control-group">
            <label for="feedback_email" class="control-label">Email Id</label>
            <div class="controls">
                <input id="feedback_email" type="text" />
            </div>
        </div>
        <div class="control-group">
            <label for="feedback_text" class="control-label">Suggestion/Complaint</label>
            <div class="controls">
                <textarea id="feedback_text">
                </textarea>
            </div>
        </div>
        <div class="form-actions">
            <button onclick="submit_feedback()" class="btn">Submit</button>
        </div>
        </fieldset>
    </div>
</div>