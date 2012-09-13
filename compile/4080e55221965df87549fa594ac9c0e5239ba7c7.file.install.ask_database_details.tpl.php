<?php /* Smarty version Smarty-3.1.11, created on 2012-09-12 09:28:33
         compiled from "html\install.ask_database_details.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7953505055c1f0cd39-25673596%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4080e55221965df87549fa594ac9c0e5239ba7c7' => 
    array (
      0 => 'html\\install.ask_database_details.tpl',
      1 => 1347436303,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7953505055c1f0cd39-25673596',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_505055c20535b9_04248871',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_505055c20535b9_04248871')) {function content_505055c20535b9_04248871($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<form name="ask_database_name" method="post" class="well form-horizontal" action="index.php?mode=ask_database_name&sub=2">
    <div class="control-group">
        <label for="database_name" class="control_label">Database name:</label>
        <div class=controls>
            <input type="text" name="database_name" id="database_name" placeholder="Database Name" />
        </div>
    </div>
    <div class="control-group">
        <label for="database_host" class="control_label">Hostname:</label>
        <div class=controls>
            <input type="text" name="database_host" id="database_host" placeholder="Database Host" />
        </div>
    </div>
    <div class="control-group">
        <label for="database_username" class="control_label">Database Username</label>
        <div class=controls>
            <input type="text" name="database_username" id="database_username" placeholder="Database Username" />
        </div>
    </div>
    <div class="control-group">
        <label for="database_password" class="control_label">Database Password:</label>
        <div class=controls>
            <input type="password" name="database_password" id="database_name" placeholder="Database Password" />
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" name="ask_database_name" id="submit" class="btn btn-primary"/>
    </div>
</form>
<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>