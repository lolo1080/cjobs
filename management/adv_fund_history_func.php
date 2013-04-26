<?
function key_in_ignore_list($val)
{
	//Ignore list
	$ignore_list = array("cc_number","cc_cvv2");
	return in_array($val,$ignore_list);
}

function preparse_val($key,$val)
{
 global $month_array;
	switch($key) {
		case "cc_number_last4": $val = 'XXXX-XXXX-XXXX-'.$val; break;
		case "cc_expiration_month": $val = $month_array[$val];	break;
		case "cc_expiration_year": $val = "20".$val;	break;
	}
 return $val;
}

function create_values($regdate,$amount,$paytype,$payinfo,$batchnum)
{
 global $smarty,$text_info,$usersettings,$payment_types;
	$date_format_str		= "(".$usersettings["dateformat_c_info"].")";

	$FormElements = array(
	array("flabel"=>show_cell_caption("regdate",true).'<br />'.$date_format_str, "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"regdate", "ereadonly"=>"readonly", "evalue"=>$regdate, "emaxlength"=>"8",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("amount",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"amount", "ereadonly"=>"readonly", "evalue"=>$amount, "emaxlength"=>"12",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("payment_system",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"payment_system", "ereadonly"=>"readonly", "evalue"=>$payment_types[$paytype], "emaxlength"=>"25",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("batchnum",true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"batchnum", "ereadonly"=>"readonly", "evalue"=>$batchnum, "emaxlength"=>"25",
				"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>"")
	);
	//Add additional payment info
	if ($c = preg_match_all("~<(.+?)>(.*?)</(.+?)>~si", $payinfo, $res)) {
		for ($i=0; $i<count($res[1]); $i++)
		{
			if ($res[2][$i] == "") continue;
			if (key_in_ignore_list($res[1][$i])) continue;
			$res[2][$i] = preparse_val($res[1][$i],$res[2][$i]);
			array_push($FormElements,
				array("flabel"=>show_cell_caption($res[1][$i],true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
							"ename"=>$res[1][$i], "ereadonly"=>"readonly", "evalue"=>$res[2][$i], "emaxlength"=>"150",
							"estyle"=>"width:300px;background-color:#EEEEE9;", "isheadline"=>false, "edisabled"=>"")
			);
		}
	}

	$smarty->assign("FormElements",$FormElements);
}
?>
