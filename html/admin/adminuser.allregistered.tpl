<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
    </tr>

    {foreach from=$results key=myid item=i}
        <tr>
            <td>{$i.membername}</td>
            <td>{$i.username}</td>
            <td>{$i.emailid}</td>
        </tr>
    {/foreach}

</table>