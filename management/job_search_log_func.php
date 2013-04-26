<?
function create_values($log_id,$actiontime,$action,$status,$detail_level,$short_message,$long_message)
{
 global $smarty,$text_info,$SLINE,$log_actions_text;
	$status = ($status) ? $text_info["f_normal"] : $text_info["f_error"];
	$FormElements = array(
	array("flabel"=>show_cell_caption("actiontime"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"actiontime", "ereadonly"=>"readonly", "evalue"=>$actiontime, "emaxlength"=>"100",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("action"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"action", "ereadonly"=>"readonly", "evalue"=>$log_actions_text[$action], "emaxlength"=>"150",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("status"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"status", "ereadonly"=>"readonly", "evalue"=>$status, "emaxlength"=>"150",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("detail_level"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"detail_level", "ereadonly"=>"readonly", "evalue"=>$detail_level, "emaxlength"=>"150",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("short_message"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"short_message", "ereadonly"=>"readonly", "evalue"=>$short_message, "emaxlength"=>"150",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("long_message"), "before_html"=>"", "after_html"=>"", "etype"=>"textarea",
				"ename"=>"long_message", "ereadonly"=>"readonly", "evalue"=>$long_message,
				"estyle"=>"width:300px;height:200px;background-color:#EEEEE9;", "isheadline"=>false)
	);
	$smarty->assign("FormElements",$FormElements);
}
?>
