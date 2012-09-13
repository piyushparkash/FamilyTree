<?php /* Smarty version Smarty-3.1.11, created on 2012-09-12 08:22:08
         compiled from "html\error_high.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1854650504630ae7308-62358009%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8155dc715d028bb09b99e3b55b1c6f8022d8213e' => 
    array (
      0 => 'html\\error_high.tpl',
      1 => 1347363468,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1854650504630ae7308-62358009',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'message' => 0,
    'file' => 0,
    'lineno' => 0,
    'context' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_50504630c00205_91845557',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50504630c00205_91845557')) {function content_50504630c00205_91845557($_smarty_tpl) {?><div class="alert alert-error">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>Error!</strong>Something went wrong! We'll get this fixed soon
    <br /><h4>Error Details</h4>
    Message:<?php echo $_smarty_tpl->tpl_vars['message']->value;?>
<br />
    File:<?php echo $_smarty_tpl->tpl_vars['file']->value;?>
<br />
    Line No:<?php echo $_smarty_tpl->tpl_vars['lineno']->value;?>
<br />
    Context:<?php echo $_smarty_tpl->tpl_vars['context']->value;?>

</div><?php }} ?>