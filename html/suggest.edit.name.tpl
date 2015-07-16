<div class='suggest-box'>
    {$suggested_by->data['membername']} corrected name of {$suggested_to->data['membername']}
    <table class="table-condensed">
        <tr>
            <th>Old Name</th>
            <th>Corrected Name</th>
        </tr>
        <tr>
            <td>
                {$suggested_to->data['membername']}
            </td>
            <td>
                {$newvalue}
            </td>
        </tr>
    </table>
            Is this correct?
    <div>
        <button class="btn btn-success btn-small" onclick="suggest_action(this, 1);">Yes</button>
        <button class="btn btn-danger btn-small" onclick="suggest_action(this, 0);">No</button>
        <button class="btn btn-small" onclick="suggest_action(this, 2);">Don't Know</button>
    </div>
</div>