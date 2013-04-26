<?php

// BigDump ver. 0.32 from 2010-04-03
// Staggered import of an large MySQL Dump (like phpMyAdmin 2.x Dump)
// Even through the webservers with hard runtime limit and those in safe mode
// Works fine with Internet Explorer 7.0 and Firefox 2.x

// Author:       Alexey Ozerov (alexey at ozerov dot de) 
//               AJAX & CSV functionalities: Krzysiek Herod (kr81uni at wp dot pl) 
// Copyright:    GPL (C) 2003-2010
// More Infos:   http://www.ozerov.de/bigdump.php

// This program is free software; you can redistribute it and/or modify it under the
// terms of the GNU General Public License as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version.

// THIS SCRIPT IS PROVIDED AS IS, WITHOUT ANY WARRANTY OR GUARANTEE OF ANY KIND

// USAGE

// 1. Adjust the database configuration in this file
// 2. Remove the old tables on the target database if your dump doesn't contain "DROP TABLE"
// 3. Create the working directory (e.g. dump) on your web server
// 4. Upload bigdump.php and your dump files (.sql, .gz) via FTP to the working directory
// 5. Run the bigdump.php from your browser via URL like http://www.yourdomain.com/dump/bigdump.php
// 6. BigDump can start the next import session automatically if you enable the JavaScript
// 7. Wait for the script to finish, do not close the browser window
// 8. IMPORTANT: Remove bigdump.php and your dump files from the web server

// If Timeout errors still occure you may need to adjust the $linepersession setting in this file

// LAST CHANGES

// *** Change fopen mode to "r" as workaround for Windows issues

// Database configuration

require "../../management/consts.php";

$db_server   = $dbhost;
$db_name     = $dbname;
$db_username = $dbuser;
$db_password = $dbpassword;

echo "Db server : " . $db_server;
echo "Db name : " . $db_name;

$link = mysql_connect($dbhost,$dbuser,''); 
if (!$link) { 
	die('Could not connect to MySQL: ' . mysql_error()); 
} 
echo 'Connection OK'; mysql_close($link); 

?>
