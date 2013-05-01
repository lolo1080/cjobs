<?php /* Smarty version 2.6.19, created on 2013-05-01 01:36:13
         compiled from page_jobrollsettings.tpl */ ?>
<script type="text/javascript">var cp = new ColorPicker();</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '
<script type="text/javascript">cp.writeDiv();</script>

<script type="text/javascript">
function get_cssText(color)
{
	return "\'cursor:pointer;width:18px;height:18px;background-color:\'+color+\';border:solid 1px #999;\'";
}
function pickColor(color)
{
	ColorPicker_targetInput.value = color;
	eval(\'get_element("\' + ColorPicker_targetInput.name + \'_example").style.cssText = \' + get_cssText(color) + \';\');
}
</script>
'; ?>