<?
function create_confirm_values($email)
{
 global $smarty,$text_info;
	$FormElements = array(
	array("flabel"=>show_cell_caption("email",true,true), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"email", "ereadonly"=>"", "evalue"=>$email, "emaxlength"=>"80",
				"estyle"=>"width:300px", "isheadline"=>false, "edisabled"=>"")
	);
	$smarty->assign("FormElements",$FormElements);
	$smarty->assign("reginfo",$text_info["i_reginfo"]);
}

function signup_send_confirm_email($email,$emailkey,$type)
{
 global $parse_values;
	//Parse values
	$admin_email = get_admin_email_free();
	$parse_values["{*site_title*}"]	= $_SESSION["globsettings"]["site_title"];
	$parse_values["{*webmaster_email_txt*}"]	= $admin_email;
	$parse_values["{*site_url_txt*}"]					= $_SESSION["globsettings"]["site_url"];
	$parse_values["{*email_confirm_url_txt*}"]= $_SESSION["globsettings"]["site_url"].'management/m_email_confirm.php?type='.$type.'&key='.$emailkey;
	$parse_values["{*webmaster_email_html*}"]	= "<a href=\"mailto:".$parse_values["{*webmaster_email_txt*}"]."\">".$parse_values["{*webmaster_email_txt*}"]."</a>";
	$parse_values["{*site_url_html*}"]				= "<a href=\"".$parse_values["{*site_url_txt*}"]."\">".$parse_values["{*site_url_txt*}"]."</a>";
	$parse_values["{*email_confirm_url_html*}"]="<a href=\"".$parse_values["{*email_confirm_url_txt*}"]."\">".$parse_values["{*email_confirm_url_txt*}"]."</a>";
	//Sign Up confirmation 
	$subj	= get_mailsubject("sign_up_confirm");
	$htmlmessage = get_email_file("sign_up_confirm","html");
	$textmessage = get_email_file("sign_up_confirm","txt");
	$attach_files= get_mail_attach("sign_up_confirm");
	$subj = str_replace("{*site_title*}",$parse_values["{*site_title*}"],$subj);
	create_and_send_email($email,$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
}
?>