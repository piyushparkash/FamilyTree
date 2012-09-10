<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>
            Vanshavali - Place for famliy
        </title>
        <!-- CSS Files -->
        <link href="../ajax\css\smoothness\jquery-ui-1.8.14.custom.css" rel="stylesheet" type="text/css" />
        <link href="style.css" rel="stylesheet" type="text/css" />
        <link type="text/css" href="css/Spacetree.css" rel="stylesheet" />
        <link type="text/css" href="assets/css/bootstrap.css" rel="stylesheet" />

        <!--[if IE]>
                <script language="javascript" type="text/javascript" src="../../Extras/excanvas.js">
                </script>
        <![endif]-->
        <!-- JIT Library File -->
        <script type="text/javascript" src="../ajax/jquery.js">
        </script>
        <script type="text/javascript" src="../ajax/jquery-ui.js">
        </script>
        <script type="text/javascript" src="assets/js/bootstrap-modal.js"></script>
        <script type="text/javascript" src="jit.js">
        </script>
        <script type="text/javascript" src="working.js">
        </script>
        <script type="text/javascript" src="example1.js">
        </script>
    </head>
    <body>

        <div align="center" id="vanshavali_user">
            <div class="btn-group">
                <a class="btn btn-large">Vanshavali</a>
                {if $authenticated eq False}
                <a class="btn btn-large" onclick="login()">Login</a>
                {else}
                <script type="text/javascript">
                    is_authenticated = true;
                </script>
                <a class="btn btn-large">
                    {$membername}
                </a>
                {/if}
            </div>
        </div>
    