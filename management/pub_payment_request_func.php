<?
function create_values($account,$amount,$paytype_val,$paytype_sel,$paytype_capt,$payee_account)
{
 global $smarty,$text_info,$common_payment_systems_info;
	$FormElements = array(
	array("flabel"=>show_cell_caption("account"), "before_html"=>"<b>$".$account."</b>",	"after_html"=>"",	"etype"=>"",
				"ename"=>"",	"ereadonly"=>"",	"evalue"=>"<b>".$account."</b>",	"emaxlength"=>"",
				"estyle"=>"", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("currency"), "before_html"=>$common_payment_systems_info["Currency"],	"after_html"=>"",	"etype"=>"",
				"ename"=>"", "ereadonly"=>"", "evalue"=>"",	"emaxlength"=>"",
				"estyle"=>"", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("request"), "before_html"=>"",	"after_html"=>"",	"etype"=>"text",
				"ename"=>"amount",	"ereadonly"=>"",	"evalue"=>$amount,	"emaxlength"=>"8",
				"estyle"=>"width:200px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("payment_system"), "before_html"=>"", "after_html"=>"",	"etype"=>"select",
				"ename"=>"paytype",	"edisabled"=>"",	"evalue"=>$paytype_val,	"eselected"=>$paytype_sel, 
				"ecaption"=>$paytype_capt, "jscipt"=>"onchange=\"add_text_comment(this);\"", "multiple"=>"",
				"estyle"=>"width:200px", "isheadline"=>false, "edisabled"=>""),
	array("flabel"=>show_cell_caption("payee_account").'<br /><small name="add_pay_comment" id="add_pay_comment" style="color:#550000"></small>', "before_html"=>"", "after_html"=>"",	"etype"=>"text",
				"ename"=>"payee_account",	"ereadonly"=>"",	"evalue"=>$payee_account,	"emaxlength"=>"80",
				"estyle"=>"width:200px", "isheadline"=>false, "edisabled"=>""),
	);
	$smarty->assign("FormElements",$FormElements);
}


function check_data()
{
 global $my_error,$Error_messages,$amount,$payment_type_val,$paytype,$payee_account,$user_info;
	$amount = html_chars(get_post_value("amount",""));
	$paytype = html_chars(get_post_value("paytype",""));
	$payee_account = html_chars(get_post_value("payee_account",""));

	if ($amount == "") $my_error .= $Error_messages["no_amount"];
	elseif (!check_float($amount)) $my_error .= $Error_messages["invalid_amount"];
	elseif ($amount <= 0) $my_error .= $Error_messages["small_amount"];
	elseif ($amount > $user_info["balance"]) $my_error .= $Error_messages["too_many_request"];

	if ($paytype == "") $my_error .= $Error_messages["no_payment_system"];
	elseif (!in_array($paytype,$payment_type_val)) $my_error .= $Error_messages["invalid_payment_system"];
	
	if ($payee_account == "") $my_error .= $Error_messages["no_payee_account"];

	//If no errors - next page
	if ($my_error == "") return true;
	else return false;
}
?>