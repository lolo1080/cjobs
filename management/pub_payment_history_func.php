<?
function create_values($regdate,$paydate,$amount,$paytype,$payee_account,$payinfo,$batchnum,$status)
{
 global $smarty,$text_info,$usersettings,$payment_types;

	$date_format_str		= "(".$usersettings["dateformat_c_info"].")";
	$paytime = (!$paydate) ? "" : $paydate;
	$status = ($status) ? $text_info["f_Processed"]: $text_info["f_Pending"];

	$FormElements = array(
	array("flabel"=>show_cell_caption("regdate",true).'<br />'.$date_format_str, "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"regdate", "ereadonly"=>"readonly", "evalue"=>$regdate, "emaxlength"=>"8",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("amount",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"amount", "ereadonly"=>"readonly", "evalue"=>$amount, "emaxlength"=>"12",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("paydate",true).'<br />'.$date_format_str, "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"paydate", "ereadonly"=>"readonly", "evalue"=>$paydate, "emaxlength"=>"8",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("payment_system",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"payment_system", "ereadonly"=>"readonly", "evalue"=>$payment_types[$paytype], "emaxlength"=>"25",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("payee_account",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"payee_account", "ereadonly"=>"readonly", "evalue"=>$payee_account, "emaxlength"=>"55",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("batchnum",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"batchnum", "ereadonly"=>"readonly", "evalue"=>$batchnum, "emaxlength"=>"25",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("status",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"status", "ereadonly"=>"readonly", "evalue"=>$status, "emaxlength"=>"25",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("payinfo",true), "before_html"=>"", "after_html"=>"", "etype"=>"textarea",
				"ename"=>"htmlbody", "ereadonly"=>"readonly", "evalue"=>$payinfo,
				"estyle"=>"width:300px;height:60px;background-color:#EEEEE9;", "isheadline"=>false)
	);
	$smarty->assign("FormElements",$FormElements);
}
?>