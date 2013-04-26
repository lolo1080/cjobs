<?
	if (!isset($_SESSION["sess_lang"])) $_SESSION["sess_lang"] = $default_language;
	$rlang = $_SESSION["sess_lang"];
	require_once (file_exists($admin_dir_path."lang/templates_$rlang.php")) ? $admin_dir_path."lang/templates_$rlang.php" : die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". Fatal error. Template values file not found (templates_$rlang.php).<br />");
?>