<?
/*
###########################
# leftmenu_top.php
# Create left menu elements
###########################
*/

session_start();

require_once "consts.php";
require_once "leftmenu_const.php";
require_once "consts_smarty.php"; //Main settings for Smarty
require_once "language.php";
require_once "functions.php";

//Create menu sections
function get_leftmenu_array($userstatus)
{
 global $SLINE,$menu_list,$active_menu,$text_info,$menu_images,$menu_links,$menu_target;
$menu_list = $menu_list[$userstatus];
$active_menu = $active_menu[$userstatus];
$LeftMenuElements = array();
for($i=0; $i<count($menu_list); $i++) {
	$m = $menu_list[$i];
	if (isset($_SESSION["sess_curmenu_status"][$m])) {
		$LeftMenuElements[$i]["img"] = ($_SESSION["sess_curmenu_status"][$m]) ? "m_head_btn_up.gif" : "m_head_btn_down.gif";
		$LeftMenuElements[$i]["img_w"] = 23;
		$LeftMenuElements[$i]["img_h"] = 25;
		$LeftMenuElements[$i]["href"] = "leftmenu_top.php?menu=$m&$SLINE";
		$LeftMenuElements[$i]["text"] = $text_info[$m];
		$LeftMenuElements[$i]["title"] = $text_info[$LeftMenuElements[$i]["img"]];
		if ($_SESSION["sess_curmenu_status"][$m]) {
			$LeftMenuElements[$i]["isdown"] = true;
			for($j=0; $j<count($active_menu[$m]); $j++) {
				$mitem = $active_menu[$m][$j];
				$LeftMenuElements[$i]["Items"][$j]["img"] = $menu_images[$mitem][0];
				$LeftMenuElements[$i]["Items"][$j]["img_w"] = $menu_images[$mitem][1];
				$LeftMenuElements[$i]["Items"][$j]["img_h"] = $menu_images[$mitem][2];
				$LeftMenuElements[$i]["Items"][$j]["text"] = $text_info[$mitem];
				$LeftMenuElements[$i]["Items"][$j]["href"] = $menu_links[$mitem]."&$SLINE";
				$LeftMenuElements[$i]["Items"][$j]["target"] = $menu_target[$mitem];
			}
		}
		else $LeftMenuElements[$i]["isdown"] = false;
	}
}
 return $LeftMenuElements;
}

//Query for change menu sections status
$change_menu = get_get_value("menu","");
if ( ($change_menu != "") && (isset($_SESSION["sess_curmenu_status"][$change_menu])) ) 
	$_SESSION["sess_curmenu_status"][$change_menu] = (1 - $_SESSION["sess_curmenu_status"][$change_menu]);

//Create left menu header
$smarty->assign("lmtopcolor","#CFCFCF"); //dce1e6 f3f7fb
$smarty->assign("lmtopimg","dotted.gif");
$smarty->assign("lmtblwidth",165);
$smarty->assign("lmtopimg_h",5);
$smarty->assign("lmtopimg_w",5);
$smarty->assign("lmtoptext",$text_info["adm_cc"]);

//Left menu elements
$smarty->assign("LeftMenuElements",get_leftmenu_array($_SESSION["sess_user"]));

$smarty->display('menu/leftmenu.tpl');
?>