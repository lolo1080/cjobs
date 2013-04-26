<?
require_once "email_message.php";

function get_file($file)
{
 return implode("",file("$file"));
}

function release_content_parse_values(&$content)
{
 global $parse_values;
	foreach($parse_values as $pk => $pv) {
		$content = str_replace("$pk","$pv",$content);
	}
}
function release_parse_values($fpath)
{
 global $parse_values;
	$file_str = get_file($fpath);
	foreach($parse_values as $pk => $pv) {
		$file_str = str_replace("$pk","$pv",$file_str);
	}
 return $file_str;
}
//Get file body
function get_email_file($mail,$type)
{
 global $mail_dir,$mail_array;
	$filename = $mail_dir.$mail_array[$mail];
	$html_fbody = release_parse_values($filename);
	$txt_fbody = release_parse_values($filename.".txt");
	$txt_fbody = str_replace("<br />","",$txt_fbody);
	$txt_fbody = str_replace("<BR>","",$txt_fbody);
	switch($type) {
		case "html":
			 return "<html><head></head><body>\n".$html_fbody."<hr />\n</body></html>";
		case "txt": 
			return $txt_fbody;
	}
}
//Get mail attach
function get_mail_attach($mailname)
{
 global	$db_tables;
	$attach_files = array();
	$qr_res = mysql_query("SELECT path FROM ".$db_tables["attach"]." WHERE mailname='$mailname'") or die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". ".mysql_error().".<br />");
	if (mysql_num_rows($qr_res) > 0) {
		while ($myrow = mysql_fetch_array($qr_res))
		{
			$attach_files[] = $myrow["path"];
		}
	}
 return $attach_files;
}

function create_and_send_email($emailto,$emailfrom,$subj,&$htmlmessage,&$textmessage,&$attach_files)
{
 global $base_mail_dir,$email_sending_error;
	$email_sending_error = "";
	$base_mail_dir = (isset($base_mail_dir) && (strlen($base_mail_dir) > 0)) ? $base_mail_dir : "";

	$from_address = $emailfrom;
	$reply_address= $emailfrom;
	$error_delivery_address = $emailfrom;
	$to_address = $emailto;
	$to_name = $from_name = $reply_name = "";

	$subject = $subj;

	$email_message = new email_message_class;
	$email_message->SetEncodedEmailHeader("To",$to_address,$to_name);
	$email_message->SetEncodedEmailHeader("From",$from_address,$from_name);
	$email_message->SetEncodedEmailHeader("Reply-To",$reply_address,$reply_name);
	$email_message->SetHeader("Sender",$from_address);

	if (defined("PHP_OS") && strcmp(substr(PHP_OS,0,3),"WIN")) $email_message->SetHeader("Return-Path",$error_delivery_address);

	$email_message->SetEncodedHeader("Subject",$subject);

	$email_message->CreateQuotedPrintableHTMLPart($htmlmessage,"",$html_part);

	$email_message->CreateQuotedPrintableTextPart($email_message->WrapText($textmessage),"",$text_part);

	$alternative_parts=array(
		$text_part,
		$html_part
	);
	$email_message->AddAlternativeMultipart($alternative_parts);

	for ($i=0; $i<count($attach_files); $i++) {
		$attachment=array(
			"FileName"=>$base_mail_dir.$attach_files[$i],
			"Content-Type"=>"automatic/name",
			"Disposition"=>"attachment"
		);
		$email_message->AddFilePart($attachment);
	}
	$email_sending_error = $email_message->Send();
}
?>