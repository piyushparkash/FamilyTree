<table class="table table-bordered">
    <tr>
        <th>Suggested By</th>
        <th>Suggested To</th>
        <th>Suggestion Type</th>
        <th>Old Value</th>
        <th>New Value</th>
        <th>Date Added</th>
        <th>Approved</th>
    </tr>

    {foreach from=$results key=myid item=i}
        <tr>
            <td>{$i.suggested_by}</td>
            <td>{$i.suggested_to}</td>
            <td>{$i.typesuggest}</td>
            <td>{$i.old_value}</td>
            <td>{$i.new_value}</td>
            <td>{$i.ts}</td>
            <td>{$i.approved}</td>
        </tr>
    {/foreach}

</table>