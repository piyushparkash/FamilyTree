<?php /* Smarty version Smarty-3.1.11, created on 2012-09-13 12:56:02
         compiled from "html\header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6262505055c2063cb6-20726943%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '46655248a542182a188b08f8e102d818e46c7e8e' => 
    array (
      0 => 'html\\header.tpl',
      1 => 1347538663,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6262505055c2063cb6-20726943',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_505055c20d8da7_76909794',
  'variables' => 
  array (
    'authenticated' => 0,
    'membername' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_505055c20d8da7_76909794')) {function content_505055c20d8da7_76909794($_smarty_tpl) {?><html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>
            Vanshavali - Place for famliy
        </title>
        <!-- CSS Files -->
        <link href="..\ajax\css\smoothness\jquery-ui-1.8.14.custom.css" rel="stylesheet" type="text/css" />
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
                <?php if ($_smarty_tpl->tpl_vars['authenticated']->value==false){?>
                <a class="btn btn-large" onclick="login()">Login</a>
                <?php }else{ ?>
                <script type="text/javascript">
                    is_authenticated = true;
                </script>
                <a class="btn btn-large">
                    <?php echo $_smarty_tpl->tpl_vars['membername']->value;?>

                </a>
                <?php }?>
            </div>
        </div>
    <?php }} ?>