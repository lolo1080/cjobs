<?php

function doconnect()
{
 global $dbhost, $dbuser, $dbpassword, $dbname;
	$errormessage = "<center><h2>Sorry.</h2><br />Service unavailable, please contact to the administrator.</center>";
//	$db = @mysql_connect("$dbhost", "$dbuser", "$dbpassword") or die ($errormessage);
	$db = @mysql_connect($dbhost, $dbuser, $dbpassword ) or die ($errormessage);
	@mysql_select_db($dbname,$db) or die ("No database:".$dbname);
}
?>