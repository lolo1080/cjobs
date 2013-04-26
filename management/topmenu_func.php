<?
require_once "leftmenu_const.php";

//Create menu sections
function get_leftmenu_array($userstatus)
{
 global $SLINE,$menu_list,$active_menu,$text_info,$menu_images,$menu_links,$menu_target,$menu_split;
$menu_list = $menu_list[$userstatus];
$active_menu = $active_menu[$userstatus];
$LeftMenuElements = array();
for($i=0; $i<count($menu_list); $i++) {
	$m = $menu_list[$i];
	$LeftMenuElements[$i]["text"] = $text_info[$m];
		for($j=0; $j<count($active_menu[$m]); $j++) {
			$mitem = $active_menu[$m][$j];
			$LeftMenuElements[$i]["Items"][$j]["img"] = $menu_images[$mitem][0];
			$LeftMenuElements[$i]["Items"][$j]["text"] = $text_info[$mitem];
			$LeftMenuElements[$i]["Items"][$j]["href"] = $menu_links[$mitem]."&$SLINE";
			$LeftMenuElements[$i]["Items"][$j]["target"] = $menu_target[$mitem];
			$LeftMenuElements[$i]["Items"][$j]["split"] = $menu_split[$mitem];
		}
	}
 return $LeftMenuElements;
}

$smarty->assign("topmenucolor","#CfCfCf");
$smarty->assign("menucopyright",$text_info["copyright"]);

//Left menu elements
@$smarty->assign("LeftMenuElements",get_leftmenu_array($_SESSION["sess_user"]));
?>
