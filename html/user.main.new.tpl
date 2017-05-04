<div class="navbar">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="#">Vanshavali</a>

            <div class="nav-collapse collapse">

                <ul class="nav">
                    <li class="active"><a href="#">Home</a></li>
                    {if $authenticated eq False}
                    <li><i class="icon-user icon-white"></i><a href="#" onclick="login()">Login</a></li>
                    {/if}
                    <li><a href="#" onclick="introJs().start()">How to use?</a></li>
                    <li><a href="#" onclick="search()">Search</a></li>
                    <li><a href="#" onclick="feedback()">Feedback</a></li>
                </ul>
                {if $authenticated}
                <ul class="nav pull-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-user icon-white"></i> {$membername}
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
                </ul>
                {/if}
            </div>
        </div>
    </div>
</div>