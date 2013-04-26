<?
session_start();

require_once "js/JsHttpRequest/lib/JsHttpRequest/JsHttpRequest.php";

// Init JsHttpRequest and specify the encoding. It's important!
$JsHttpRequest =& new JsHttpRequest("windows-1251");
$GLOBALS['_RESULT'] = array("new_data"=>0, "value"=>0, "name"=>"");

require_once "consts.php";
require_once "app_errors_handler.php";
require_once "language.php";
require_once "connect.inc";
require_once "functions.php";
require_once "functions_mini.php";
require_once "consts_smarty.php";
require_once "include/functions/functions_smartry.php";
require_once "topmenu_func.php";
check_access(array(2));


doconnect();

$action = data_addslashes(get_get_post_value("action",""));
$channel_name = data_addslashes(get_get_post_value("channel_name",""));
$channel_id = data_addslashes(get_get_post_value("channel_id",""));

if ($action == "") { echo $Error_messages["no_action"]; exit; }
if (!in_array($action, array("add","delete"))) { echo $Error_messages["invalid_action"]; exit; }

if ($action == "add") {
	if ($channel_name == "") { echo $Error_messages["no_channel_name"]; exit; }

	$qr_res = mysql_query("SELECT * FROM ".$db_tables["users_publisher_channels"]." WHERE uid_pub='{$_SESSION["sess_userid"]}'") or query_die(__FILE__,__LINE__,mysql_error());
	while ($myrow = mysql_fetch_array($qr_res))
	{
		if ($channel_name == $myrow["name"]) { echo $Error_messages["channel_name_exist"]; exit; }
	}

	mysql_query("INSERT INTO ".$db_tables["users_publisher_channels"]." VALUES(NULL,'{$_SESSION["sess_userid"]}','$channel_name')") or query_die(__FILE__,__LINE__,mysql_error());

	$GLOBALS['_RESULT'] = array("new_data"=>1, "value"=>mysql_insert_id(), "name"=>$channel_name);
}
elseif ($action == "delete") {
	if ($channel_id == "") { echo $Error_messages["no_channel_id"]; exit; }
	if (!check_int($channel_id)) { echo $Error_messages["invalid_channel_id"]; exit; }

	$qr_res = mysql_query("SELECT * FROM ".$db_tables["users_publisher_channels"]." WHERE uid_pub='{$_SESSION["sess_userid"]}' and channel_id='$channel_id'") or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) { echo $Error_messages["invalid_channel_id"]; exit; }

	$myrow = mysql_fetch_array($qr_res);

	mysql_query("DELETE FROM ".$db_tables["users_publisher_channels"]." WHERE uid_pub='{$_SESSION["sess_userid"]}' and channel_id='$channel_id'") or query_die(__FILE__,__LINE__,mysql_error());

	$GLOBALS['_RESULT'] = array("new_data"=>1);
}
?>