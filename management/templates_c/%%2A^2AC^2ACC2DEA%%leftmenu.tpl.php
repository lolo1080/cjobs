<?php /* Smarty version 2.6.19, created on 2013-05-01 01:22:57
         compiled from menu/leftmenu.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Left menu.</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>

<body bgColor="#ffffff" leftMargin="0" topMargin="0" text="#000000" marginheight="0" marginwidth="0" class="MenuLeftBk">

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu/leftmenu_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table align="center" border="0" cellspacing="0" cellpadding="3" width="<?php echo $this->_tpl_vars['lmtblwidth']; ?>
">
<tr>
	<td>
<!-- menu section -->
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['LeftMenuElements']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
<!-- menu header -->
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr><td><img width="1" height="15" src="images/spacer.gif" align="absmiddle" alt="" /></td></tr>
	<tr>
		<td width="162" height="24" class="TdMenuHead">
			<a class="MenuHead" href="<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['href']; ?>
"><?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['text']; ?>
</a>
		</td>
		<td class="TdMenuHeadRight">
			<a href="<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['href']; ?>
">
				<img src="images/<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['img']; ?>
" width="<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['img_w']; ?>
" height="<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['img_h']; ?>
" border="0" alt="<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['title']; ?>
" title="<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['title']; ?>
" />
			</a>
		</td>
	</tr>
</table>
<!-- menu header -->
<?php if ($this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['isdown']): ?>
<!-- menu body -->
<table border="0" cellspacing="0" cellpadding="0" width="100%" class="TblMenuBody">
	<tr><td height="10" class="TdMenuItem"><img width="4" height="10" src="images/spacer.gif" align="absmiddle" /></td></tr>
		<?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['Items']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?>
			<tr>
				<td height="18" class="TdMenuItem">
					<img src="images/<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['Items'][$this->_sections['j']['index']]['img']; ?>
" width="16" height="16" alt="<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['Items'][$this->_sections['j']['index']]['text']; ?>
" title="<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['Items'][$this->_sections['j']['index']]['text']; ?>
" border="0" align="absmiddle" />
					<img width="0" height="16" src="images/spacer.gif" align="absmiddle" />
					<a class="MenuItem" href="<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['Items'][$this->_sections['j']['index']]['href']; ?>
" target="<?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['Items'][$this->_sections['j']['index']]['target']; ?>
"><?php echo $this->_tpl_vars['LeftMenuElements'][$this->_sections['i']['index']]['Items'][$this->_sections['j']['index']]['text']; ?>
</a>
				</td>
			</tr>
		<?php endfor; endif; ?>
	<tr><td height="10" class="TdMenuItem"><img width="4" height="10" src="images/spacer.gif" align="absmiddle" alt="" /></td></tr>
</table>
<!-- menu body -->
<?php else: ?>
<table border="0" cellspacing="0" cellpadding="0" width="100%" class="TblMenuBody">
	<tr><td></td></tr>
</table>
<?php endif; ?>
<?php endfor; endif; ?>
<!-- menu section -->
	</td>
</tr>
</table>
 
</body>
</html>