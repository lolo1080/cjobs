<?php /* Smarty version 2.6.19, created on 2013-05-01 01:06:07
         compiled from s_content_top.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>ES Job Search Engine Control Center</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/main.css" rel="stylesheet" type="text/css" />
	<link href="css/filter.css" rel="stylesheet" type="text/css" />
	<script src="js/content.js" language="JavaScript"></script>
	<script src="js/JSCookMenu/JSCookMenu.js" language="JavaScript"></script>
	<link href="js/JSCookMenu/ThemeOffice/theme.css" rel="stylesheet" type="text/css" />
	<script src="js/JSCookMenu/ThemeOffice/theme.js" language="JavaScript"></script>
	<?php if ($this->_tpl_vars['LoadCalendarScript']): ?>
		<style type="text/css">@import url(jscalendar/calendar-win2k-1.css);</style>
		<script type="text/javascript" src="jscalendar/calendar.js"></script>
		<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
		<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['LoadEditorScript']): ?>
		<script type="text/javascript" src="fckeditor/fckeditor.js"></script>
		<script type="text/javascript">
			window.onload = function()
			{
				<?php echo $this->_tpl_vars['AddHTMLEditorBody']; ?>

			}
		</script>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['LoadColorPickerScript']): ?>
		<script type="text/javascript" src="js/ColorPicker/ColorPicker2.js"></script>
		<script type="text/javascript" src="js/ColorPicker/AnchorPosition.js"></script>
		<script type="text/javascript" src="js/ColorPicker/PopupWindow.js"></script>
		<script type="text/javascript" src="js/JsHttpRequest/lib/JsHttpRequest/JsHttpRequest.js"></script>
		<script type="text/javascript" src="js/content_jobroll.js"></script>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['LoadFeedsScript']): ?>
		<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
		<script type="text/javascript" src="js/ajax_error_handler.js"></script>
		<script type="text/javascript" src="js/urlEncode.js"></script>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['LoadHTML2FeedScript']): ?>
		<?php echo '
		<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
		<script type="text/javascript" src="js/ajax_error_handler.js"></script>
		<script type="text/javascript" src="js/urlEncode.js"></script>
		<link rel="stylesheet" type="text/css" href="js/treeview/jquery.treeview.css" />
		<style>
			.tag { color: blue; }
			.attr { color: #990033; }
		</style>
		<script type="text/javascript" src="js/treeview/jquery.treeview.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){	
			$("#html_tree").treeview({
					control:"#sidetreecontrol",
					collapsed: true,
					prerendered: true
				});
			});
		</script>
		'; ?>

	<?php endif; ?>
</head>

<body bgColor="#f8f5f8" text="#000000" leftMargin="0" topMargin="0" marginheight="0" marginwidth="0" <?php echo $this->_tpl_vars['AddBodyScript']; ?>
>

		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu/topmenu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>	
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>	
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "spacer_img.tpl", 'smarty_include_vars' => array('sheight' => '2','swidth' => '1')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>  
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "line1.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>	
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "spacer_img.tpl", 'smarty_include_vars' => array('sheight' => '2','swidth' => '1')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>	
				<table align="center" border="0" cellPadding="0" cellSpacing="0" height="94%" width="100%">
		<tr valign="top">
			<td align="middle">
				<img src="images/spacer.gif" width="5" height="1"   border="0" alt="" />
			</td>
			<td width="100%">

			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu/graymenu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "table_data.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>		
			</td>
		</tr>
		</table>

</body>
</html>