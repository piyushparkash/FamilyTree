<div class="navbar">
    <div class="navbar-inner">
        <a class="brand" href="#">Vanshavali</a>
        <ul class="nav">
            <li class="active"><a href="#">Home</a></li>
                {if $authenticated eq False}
                <li onclick="login()"><i class="icon-user icon-white"></i>Login</li>
                {else}
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-user icon-white"></i>
                        {$membername}
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li href="logout.php"><a href="#">Logout</a></li>
                    </ul>
                </li>
                <li><a href="#" onclick="suggest()">Complete Family!</a></li>
                <script type="text/javascript">
                    is_authenticated = true;
                </script>

            {/if}
            <li><a href="#" onclick="introJs().start()">How to use?</a></li>
            <li><a href="#" onclick="search()">Search</a></li>
            <li><a href="#" onclick="feedback()">Feedback</a></li>
        </ul>
    </div>
</div>
