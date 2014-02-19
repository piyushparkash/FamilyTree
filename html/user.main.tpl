<div  id="vanshavali_user" align="center">
    <div class="btn-group">
        <a class="btn btn-large btn-primary" href="index.php" data-intro="Welcome to Family Tree! Let me introduce you around." data-step="1">Vanshavali</a>
        {if $authenticated eq False}
            <a class="btn btn-large btn-success" onclick="login()"><i class="icon-user icon-white"></i> Login/Register</a>
        {else}
            <script type="text/javascript">
                is_authenticated = true;
            </script>
            
                <a class="btn btn-large btn-success dropdown-toggle" data-toggle="dropdown" href="#" ><i class="icon-user icon-white"></i> 
                    {$membername}
                    <span class="caret"></span>
                </a>
                    

                <ul class="dropdown-menu">
                    <a href="#" onclick="suggest()">Complete Family!</a>
                    <a href="logout.php">Logout</a>
                </ul>
            

        {/if}
</div>
</div>
