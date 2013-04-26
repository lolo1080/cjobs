<?
function create_values($regdate,$ip,$ip_over_proxy,$refer_url,$request_url)
{
 global $smarty,$text_info,$usersettings,$payment_types;
	$date_format_str		= "(".$usersettings["dateformat_c_info"].")";

	$FormElements = array(
	array("flabel"=>show_cell_caption("regdate",true).'<br />'.$date_format_str, "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"regdate", "ereadonly"=>"readonly", "evalue"=>$regdate, "emaxlength"=>"8",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("ip1",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"ip", "ereadonly"=>"readonly", "evalue"=>$ip, "emaxlength"=>"15",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("ip_over_proxy",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"ip_over_proxy", "ereadonly"=>"readonly", "evalue"=>$ip_over_proxy, "emaxlength"=>"15",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("refer_url",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"refer_url", "ereadonly"=>"readonly", "evalue"=>$refer_url, "emaxlength"=>"15",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("request_url",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"request_url", "ereadonly"=>"readonly", "evalue"=>$request_url, "emaxlength"=>"15",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>"")
	);

	$smarty->assign("FormElements",$FormElements);
}
?>
