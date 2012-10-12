<div  id="vanshavali_user" align="center">
    <div class="btn-group">
        <a class="btn btn-large">Vanshavali</a>
        {if $authenticated eq False}
            <a class="btn btn-large" onclick="login()">Login</a>
        {else}
            <script type="text/javascript">
                is_authenticated = true;
            </script>
            
                <a class="btn btn-large dropdown-toggle" data-toggle="dropdown" href="#" >
                    {$membername}
                    <span class="caret"></span>
                </a>
                    

                <ul class="dropdown-menu">
                    <a href="logout.php">Logout</a>
                </ul>
            

        {/if}
</div>
</div>
