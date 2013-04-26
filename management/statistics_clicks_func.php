<?
function create_values($regdate,$click_type,$keyword,$searchtype,$job_title)
{
 global $smarty,$text_info,$usersettings,$payment_types;
	$date_format_str		= "(".$usersettings["dateformat_c_info"].")";

	$FormElements = array(
	array("flabel"=>show_cell_caption("regdate",true).'<br />'.$date_format_str, "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"regdate", "ereadonly"=>"readonly", "evalue"=>$regdate, "emaxlength"=>"8",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("click_type",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"click_type", "ereadonly"=>"readonly", "evalue"=>$click_type, "emaxlength"=>"15",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("search_keyword",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"keyword", "ereadonly"=>"readonly", "evalue"=>$keyword, "emaxlength"=>"150",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("searchtype",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"searchtype", "ereadonly"=>"readonly", "evalue"=>$searchtype, "emaxlength"=>"15",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("clicked_job_title",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"job_title", "ereadonly"=>"readonly", "evalue"=>$job_title, "emaxlength"=>"15",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>"")
	);

	$smarty->assign("FormElements",$FormElements);
}
?>
