<nav class="navbar navbar-toggleable-md navbar-light bg-faded">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportContent" aria-controls="navbarSupportContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">Vanshavali</a>

    <div class="collapse navbar-collapse" id="navbarSupportContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active"><a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a></li>
            {if $authenticated eq False}
            <li class="nav-item"><a href="#" onclick="login()" class="nav-link"><i class="icon-user"></i>Login</a></li>
            {/if}
            <li class="nav-item"><a class="nav-link" href="#" onclick="introJs().start()">How to use?</a></li>
            <li class="nav-item"><a class="nav-link" href="#" onclick="window.search()">Search</a></li>
            <li class="nav-item"><a class="nav-link" href="#" onclick="feedback()">Feedback</a></li>
        </ul>
        {if $authenticated}
        <ul class="navbar-nav pull-xs-right">
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-user icon-white"></i> <strong>{$membername}</strong>
                    <b class="caret"></b>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a href="logout.php" class="dropdown-item">Logout</a>
                </div>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="suggest(this)"><strong>Complete Family!</strong></a>
            </li>
            <script type="text/javascript">
                is_authenticated = true;
            </script>
        </ul>
        {/if}
    </div>
</nav>