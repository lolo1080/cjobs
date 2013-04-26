<?
function create_payment_egold($elist, &$my_error)
{
 global $db_tables,$parse_values,$text_info,$egold_info;
	$my_error = "";
	$qr_res = mysql_query("SELECT p.pid,p.uid,p.amount,p.payaccount,u.username,u.email FROM ".
		$db_tables["payment"]." p,".$db_tables["users"]." u WHERE p.status=0 and p.payway=2 and p.uid=u.uid and u.isnew=0 and u.isenable=1 and u.isdeleted=0 and p.pid='".$elist."'")
				or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) { $my_error = "<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". Can not find payment (E-Gold payment)."; return; }
	$myrow = mysql_fetch_array($qr_res);
	$Pid = $myrow["pid"];
	$Uid = $myrow["uid"];
	$Payee_Account = $myrow["payaccount"];
	$Amount = $myrow["amount"];
	$Email = $myrow["email"];
	$file = fopen("https://www.e-gold.com/acct/confirm.asp?AccountID=".$_SESSION["paymentsettings"]["egold_id"]."&PassPhrase=".$_SESSION["paymentsettings"]["egold_passphrase"]."&Payee_Account=$Payee_Account&Amount=$Amount&PAY_IN=".$egold_info["EGoldMetal"]."&WORTH_OF=Gold&Memo=&IGNORE_RATE_CHANGE=y&PAYMENT_ID=$Pid", "r"); 
	if (!$file)	die("<b>File:</b> ".__FILE__." <b>Line:</b> ".__LINE__."Can not connect to https://www.e-gold.com/acct/confirm.asp.");
	else {
		while (!feof($file)) {
			$rf .= fgets ($file, 1024);
		}
//		$rf = fread($file, 102400);
		$PAYEE_ACCOUNT = (preg_match("/<input.+?name=PAYEE_ACCOUNT.+?value=\"(.*?)\">/i",$rf,$matches)) ? $matches[1] : "";
		$PAYMENT_AMOUNT = (preg_match("/<input.+?name=PAYMENT_AMOUNT.+?value=\"(.*?)\">/i",$rf,$matches)) ? $matches[1] : "";
		$PAYMENT_ID = (preg_match("/<input.+?name=PAYMENT_ID.+?value=\"(.*?)\">/i",$rf,$matches)) ? $matches[1] : "";
		$PAYMENT_BATCH_NUM = (preg_match("/<input.+?name=PAYMENT_BATCH_NUM.+?value=\"(.*?)\">/i",$rf,$matches)) ? $matches[1] : "";
		$V2_HASH = (preg_match("/<input.+?name=V2_HASH.+?value=\"(.*?)\">/i",$rf,$matches)) ? $matches[1] : "";
		$SERVER_ERROR = (preg_match("/<input.+?name=ERROR.+?value=\"(.*?)\">/i",$rf,$matches)) ? $matches[1] : "";
		fclose($file);		
	}
	if (($PAYMENT_AMOUNT == $Amount) && ($PAYMENT_ID == $Pid) && ($PAYMENT_BATCH_NUM != "")) {
		//Processed...
		mysql_query("UPDATE ".$db_tables["payment"]." SET paytime=NOW(),batchnum='$PAYMENT_BATCH_NUM',status=1 WHERE pid='$Pid'")
			or query_die(__FILE__,__LINE__,mysql_error());
		//----Send mail---->>
		$parse_values["{*payment_system*}"] = $payment_systems_array["egold"];
		$parse_values["{*payment_info_text*}"] = "Payee account: $Payee_Account\n".
		"Payment id: $PAYMENT_ID\nPayment amount: $PAYMENT_AMOUNT\nPayment batch num: $PAYMENT_BATCH_NUM\n".
			"V2 hash: $V2_HASH";
		$parse_values["{*sys_value_payment_info_html*}"] = "Payee account: $Payee_Account<br>".
			"Payment id: $PAYMENT_ID<br>Payment amount: $PAYMENT_AMOUNT<br>Payment batch num: $PAYMENT_BATCH_NUM<br>".
			"V2 hash: $V2_HASH";
		$admin_email = get_admin_email_free();
		//----Send mail to member
		$subj	= get_mailsubject("admin_credited_notification");
		$htmlmessage = get_email_file("admin_credited_notification","html");
		$textmessage = get_email_file("admin_credited_notification","txt");
		$attach_files = get_mail_attach("admin_credited_notification");
		//E-mail to member
		create_and_send_email($Email,$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
		//E-mail to admin
		create_and_send_email($admin_email,$admin_email,$subj,$htmlmessage,$textmessage,$attach_files);
		smarty_create_message("error","info.gif",$text_info["i_payment_success"]);
		log_file("history",__FILE__,__LINE__,"PAYEE_ACCOUNT=$PAYEE_ACCOUNT\nPAYMENT_ID=$PAYMENT_ID\nPAYMENT_AMOUNT=$PAYMENT_AMOUNT\nPAYMENT_BATCH_NUM=$PAYMENT_BATCH_NUM\nV2_HASH=$V2_HASH");
		//----Send mail----<<
	}
	else {
		$my_error .= "E-gold Server error message: <br />$SERVER_ERROR<br />";
		log_file("errors",__FILE__,__LINE__,"E-Gold payment sytem error: INVALID response.\nPAYEE_ACCOUNT=$PAYEE_ACCOUNT\nPAYMENT_ID=$PAYMENT_ID\nPAYMENT_AMOUNT=$PAYMENT_AMOUNT\nPAYMENT_BATCH_NUM=$PAYMENT_BATCH_NUM\nV2_HASH=$V2_HASH");
	}
}

function create_payment_paypal_link($pid)
{
 global $db_tables,$text_info,$paypal_info,$SNAME,$SID;
	$my_error = "";
	//Get data
	$qr_res = mysql_query("SELECT p.pid,p.uid_pub,p.amount,p.payee_account,u.email,u.name ".
		"FROM ".$db_tables["payments_pub"]." p ".
		"LEFT JOIN ".$db_tables["users_publisher"]." u ON p.uid_pub=u.uid_pub ".
		"WHERE p.status=0 and u.isdeleted=0 and p.pid='$pid'")	or query_die(__FILE__,__LINE__,mysql_error());
	if (mysql_num_rows($qr_res) == 0) { $my_error = "<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". No payment (Paypal payment)."; return; }
	$myrow = mysql_fetch_array($qr_res);
	$Pid = $myrow["pid"];
	$Uid = $myrow["uid_pub"];
	$Payee_Account = $myrow["payee_account"];
	$Amount = sprintf("%01.2f",$myrow["amount"]);
	$Email = $myrow["email"];
	$base_url = end_url_slash($_SESSION["globsettings"]["site_url"]);
	//Insert into temp table
	$ptf = array_flip($payment_types);	
	$tmp_tbl_id = insert_data_to_tmp_payment_table($Pid,$Uid,$amount,$pft["PayPal"],'');
	//Creare payment form
	$req = '<form class="frm" name="mainform" method="POST" action="https://www.paypal.com/cgi-bin/webscr" target="_top" onSubmit="return window.confirm(\''.$text_info["i_autopay"].'\')"><input type="hidden" name="cmd" value="_xclick" /><input type="hidden" name="business" value="'.$Payee_Account.'" /><input type="hidden" name="item_name" value="'.$_SESSION["globsettings"]["site_title"].'" />'.
	'<input type="hidden" name="amount" value="'.$Amount.'" /><input type="hidden" name="currency_code" value="'.$paypal_info["PayPalCurrency"].'" /><input type="hidden" name="no_shipping" value="0" /><input type="hidden" name="rm" value="2" />'.
	'<input type="hidden" name="return" value="'.$base_url.$paypal_info["AdminReturnPayPalURL"].'" /><input type="hidden" name="cancel_return" value="'.$base_url.$paypal_info["AdminPayPalNoPayURL"].'" /><input type="hidden" name="custom" value="'.$Pid.'=;='.$Uid.'=;='.$tmp_tbl_id.'" />'.
	get_submit_img("autopay.gif",20,20,$text_info["c_payment"],get_js_action(3)).'</form>';
 return $req;
}
function insert_data_to_tmp_payment_table($pid,$uid_pub,$amount,$paytype,$payinfo)
{
 global $db_tables;
	mysql_query("INSERT INTO ".$db_tables["payments_tmp_adv"]." VALUES(NULL,'$pid','$uid_pub',NOW(),'$amount','$paytype','$payinfo')") or query_die(__FILE__,__LINE__,mysql_error());
	return mysql_insert_id();
}
?>
