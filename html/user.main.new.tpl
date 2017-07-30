<nav class="navbar navbar-toggleable-md navbar-light bg-faded">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
            data-target="#navbarSupportContent" aria-controls="navbarSupportContent" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="index.php">Vanshavali</a>

    <div class="collapse navbar-collapse" id="navbarSupportContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active"><a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            {if $authenticated eq False}
                <ul class="navbar-nav pull-xs-right">
                    <form class="form-inline mr-2 mb-sm-2 mb-2 mb-lg-0 mb-xl-0" onsubmit="login(); return false;">
                        <button class="btn btn-primary">Login</button>
                    </form>
                    <form class="form-inline mr-2 mr-2 mb-sm-2 mb-2 mb-lg-0 mb-xl-0" action="register.php">
                        <button class="btn btn-success">Register</button>
                    </form>
                </ul>
            {/if}
            <form class="form-inline mr-2 mb-sm-2 mb-2 mb-lg-0 mb-xl-0">
                <input type="text" placeholder="Search" id="search_term" class="form-control mr-sm-2"/>
            </form>
        </ul>
        {if $authenticated}
            <ul class="navbar-nav pull-xs-right">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" id="navbarDropdownMenuLink"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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