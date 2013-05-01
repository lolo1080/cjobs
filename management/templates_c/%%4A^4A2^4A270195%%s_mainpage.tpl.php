<?php /* Smarty version 2.6.19, created on 2013-05-01 01:22:42
         compiled from s_mainpage.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title><?php echo $this->_tpl_vars['SiteTitle']; ?>
 Control Center</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
</head>
<frameset cols="181,*" borderColor="#6f6fcb" frameborder="0" border="0"  marginwidth="0" marginheight="0">
	<frameset rows="*,2">
		<frame border="0" frameborder="0" name="leftmenu_top" marginwidth="0" marginheight="0" scrolling="auto"
			src="leftmenu_top.php?<?php echo $this->_tpl_vars['SLINE']; ?>
" />
		<frame border="0" frameborder="0" name="leftmenu_bottom" scrolling="no" noResize
			marginwidth="0" marginheight="0" 
			src="templates/menu/leftmenu_bottom.html" />
	</frameset>
	<frameset rows="*,2">
		<frame border="0" frameborder="no" name="content_top" marginwidth="0" marginheight="0" 
			src="content_top.php?<?php echo $this->_tpl_vars['SLINE']; ?>
" />
		<frame border="0" frameborder="0" name="content_bottom" scrolling="no" noResize 
			marginwidth="0" marginheight="0"
			src="templates/menu/content_bottom.html" />
	</frameset>
</frameset>
<noframes>
	<body>
		<h1 align="center">This site use frames. Update your browser.</h1>
	</body>
</noframes>
</html>