<?
function create_values($email)
{
 global $smarty,$text_info,$SLINE;
	$FormElements = array(
	array("flabel"=>show_cell_caption("email"), "before_html"=>"", "after_html"=>"", "etype"=>"text",
				"ename"=>"email", "ereadonly"=>"", "evalue"=>$email, "emaxlength"=>"150",
				"estyle"=>"width:300px;", "isheadline"=>false, "edisabled"=>"")
	);
	$smarty->assign("FormElements",$FormElements);
}

function recovery_password($user_type,&$result)
{
 global $parse_values;
	//----Send mail---->>
	$admin_email = get_admin_email_free($user_type);
	$parse_values["{*site_title*}"]				= $_SESSION["globsettings"]["site_title"];
	$parse_values["{*site_url_txt*}"]			= $_SESSION["globsettings"]["site_url"];
	$parse_values["{*login_url_txt*}"]		= $_SESSION["globsettings"]["site_url"].$user_type.'/';
	$parse_values["{*username*}"]					= $result["email"];
	$parse_values["{*password*}"]					= $result["password"];
	//----Send mail to visitor (E-mail job to to yourself or a friend)
	$subj	= get_mailsubject("forgotpass");
	release_content_parse_values($subj);
	$htmlmessage = get_email_file("forgotpass","html");
	$textmessage = get_email_file("forgotpass","txt");
	$attach_files = get_mail_attach("forgotpass");
	create_and_send_email($result["email"],$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
	//----Send mail----<<
}
?>