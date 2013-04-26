<?
	if (!isset($_SESSION["sess_lang"])) $_SESSION["sess_lang"] = $default_language;
	$rlang = $_SESSION["sess_lang"];
	require_once (file_exists(dirname(__FILE__)."/frontend/lang/lang_$rlang.php")) ? dirname(__FILE__)."/frontend/lang/lang_$rlang.php" : die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". Fatal error. Language file not found (lang_$rlang.php).<br />");
?>