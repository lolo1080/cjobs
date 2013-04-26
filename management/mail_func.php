<?
function do_upload($mail,&$my_error)
{
 global $Error_messages, $db_tables;
	//Get file info...
	$frealname = $_FILES['userfile']['name'];
	$fname = $_FILES['userfile']['tmp_name'];
	$fsize = $_FILES['userfile']['size'];
	//Get file type
	if ($fsize == 0) { $my_error .= $Error_messages["no_size"]; return; }
	$path = "mail/attach/".$frealname;
	$qr_res = mysql_query("SELECT aid FROM ".$db_tables["attach"]." WHERE path='$path'") or die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". ".mysql_error().".<br />");
	if (mysql_num_rows($qr_res) > 0) { $my_error .= $Error_messages["file_exist"]; return; }
  mysql_query("INSERT INTO ".$db_tables["attach"]." VALUES(NULL,'$mail','tmp')") or die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". ".mysql_error().".<br />");
	$aid = mysql_insert_id();
	if (copy($fname,$path)) {
		mysql_query("UPDATE ".$db_tables["attach"]." SET path='$path' WHERE aid=$aid") or die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". ".mysql_error().".<br />");
	}
	else {
		$my_error .= $Error_messages["copy"];
		mysql_query("DELETE FROM ".$db_tables["attach"]." WHERE aid=$aid") or die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". ".mysql_error().".<br />");
	}
}

function delete_attach($darr)
{
 global $db_tables;
	for($i=0; $i<count($darr); $i++) {
		$qr_res = mysql_query("SELECT * FROM ".$db_tables["attach"]." WHERE aid=".$darr[$i]) or die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". ".mysql_error().".<br />");
		while($myrow = mysql_fetch_array($qr_res))
		{
			@unlink($myrow["path"]);
			$aid = $myrow["aid"];
		}
		if ($aid != "") mysql_query("DELETE FROM ".$db_tables["attach"]." WHERE aid=".$darr[$i]) or die("<b>File:</b> ".__FILE__.". <b>Line:</b> ".__LINE__.". ".mysql_error().".<br />");
	}
}
?>