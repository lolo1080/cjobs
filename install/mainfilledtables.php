<html>
<head></head>
<body>
<?php
/*
#################
# categories.php
# The categories list.
#################
*/

require "../management/connect.inc";
require "../management/consts.php";

doconnect();


@mysql_query('drop table '.$db_tables["jobcategories"]);
mysql_query('
CREATE TABLE '.$db_tables["jobcategories"].' (
  cat_id SMALLINT(5) UNSIGNED NOT NULL auto_increment,
  cat_name VARCHAR(150) NOT NULL,
  cat_key VARCHAR(150) NOT NULL,
	INDEX(cat_name(2)),
  PRIMARY KEY (cat_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());

set_time_limit(0);

mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Accounting & Finance", "Accounting-Finance")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Administrative", "Administrative")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Advertising &amp; PR", "Advertising-PR")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Retail", "Retail")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Legal", "Legal")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Customer Service", "Customer-Service")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Community & Government Relations", "Community-Government-Relations")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Education &amp; Training Jobs", "Education-Training")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Design & Construction", "Design-Construction")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Development &amp; Real Estate", "Development-Real-Estate")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Engineering &amp; Architecture", "Engineering-Architecture")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Management &amp; Consulting", "Management-Consulting")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Food &amp; Beverage", "Food-Beverage")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Restaurant &amp;Hotel", "Restaurant-Hotel")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Software", "Software")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Internet &amp; Computer", "Internet-Computer")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Manufacturing", "Manufacturing")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Healthcare &amp; Nursing", "Healthcare-Nursing")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Office Services", "Office-Services")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Banking", "Banking")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Sales & Marketing", "Sales-Marketing")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Security", "Security")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Transportation &amp; Logistics", "Transportation-Logistics")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Part-time &amp; Temporary", "Part-time-Temporary")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Seasonal &amp; Summer", "Seasonal-Summer")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["jobcategories"].' VALUES (NULL,"Human Resources", "Human-Resources")') or die(__LINE__.mysql_error());



@mysql_query('drop table '.$db_tables["country"]);
mysql_query('
CREATE TABLE '.$db_tables["country"].' (
  cid SMALLINT(5) UNSIGNED NOT NULL auto_increment,
	country_code2 CHAR(2) NOT NULL,
  cname VARCHAR(150) NOT NULL,
  PRIMARY KEY (cid,country_code2)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());

set_time_limit(0);

mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"--","--Unknown--")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"AF","Afghanistan")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"AL","Albania")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"DZ","Algeria")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"AS","American Samoa")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"AD","Andorra")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"AO","Angola")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"AG","Antigua and Barbuda")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"AR","Argentina")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"AM","Armenia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"AU","Australia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"AT","Austria")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"AZ","Azerbaijan")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BS","Bahamas")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BH","Bahrain")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BD","Bangladesh")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BB","Barbados")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BY","Belarus")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BE","Belgium")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BZ","Belize")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BJ","Benin")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BM","Bermuda")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BT","Bhutan")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BO","Bolivia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BA","Bosnia and Herzegovina")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BW","Botswana")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BR","Brazil")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"IO","British Indian Ocean Territory")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BN","Brunei Darussalam")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BG","Bulgaria")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BF","Burkina Faso")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"BI","Burundi")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"KH","Cambodia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CM","Cameroon")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CA","Canada")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CV","Cape Verde")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"KY","Cayman Islands")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CF","Central African Republic")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"TD","Chad")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CL","Chile")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CN","China")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CO","Colombia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"KM","Comoros")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CG","Congo")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CK","Cook Islands")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CR","Costa Rica")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CI","Cote D\'Ivoire")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"HR","Croatia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CU","Cuba")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CY","Cyprus")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CZ","Czech Republic")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"DK","Denmark")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"DJ","Djibouti")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"DO","Dominican Republic")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"TP","East Timor")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"EC","Ecuador")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"EG","Egypt")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SV","El Salvador")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GQ","Equatorial Guinea")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"ER","Eritrea")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"EE","Estonia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"ET","Ethiopia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"FK","Falkland Islands (Malvinas)")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"FO","Faroe Islands")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"FJ","Fiji")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"FI","Finland")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"FR","France")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"PF","French Polynesia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GA","Gabon")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GM","Gambia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GE","Georgia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"DE","Germany")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GH","Ghana")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GI","Gibraltar")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GR","Greece")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GL","Greenland")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GD","Grenada")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GP","Guadeloupe")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GU","Guam")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GT","Guatemala")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GN","Guinea")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GW","Guinea-Bissau")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"HT","Haiti")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"VA","Holy See (Vatican City State)")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"HN","Honduras")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"HK","Hong Kong")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"HU","Hungary")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"IS","Iceland")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"IN","India")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"ID","Indonesia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"IQ","Iraq")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"IE","Ireland")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"IR","Islamic Republic Of Iran")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"IL","Israel")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"IT","Italy")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"JM","Jamaica")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"JP","Japan")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"JO","Jordan")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"KZ","Kazakhstan")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"KE","Kenya")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"KI","Kiribati")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"KW","Kuwait")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"KG","Kyrgyzstan")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"LA","Lao People\'S Democratic Republic")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"LV","Latvia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"LB","Lebanon")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"LS","Lesotho")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"LR","Liberia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"LY","Libyan Arab Jamahiriya")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"LI","Liechtenstein")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"LT","Lithuania")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"LU","Luxembourg")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MO","Macao")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MG","Madagascar")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MW","Malawi")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MY","Malaysia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MV","Maldives")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"ML","Mali")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MT","Malta")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MQ","Martinique")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MR","Mauritania")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MU","Mauritius")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MX","Mexico")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MC","Monaco")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MN","Mongolia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MA","Morocco")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MZ","Mozambique")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MM","Myanmar")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"NA","Namibia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"NR","Nauru")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"NP","Nepal")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"NL","Netherlands")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"AN","Netherlands Antilles")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"NC","New Caledonia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"NZ","New Zealand")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"NI","Nicaragua")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"NE","Niger")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"NG","Nigeria")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MP","Northern Mariana Islands")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"NO","Norway")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"OM","Oman")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"PK","Pakistan")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"PW","Palau")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"PS","Palestinian Territory, Occupied")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"PA","Panama")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"PG","Papua New Guinea")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"PY","Paraguay")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"PE","Peru")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"PH","Philippines")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"PL","Poland")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"PT","Portugal")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"PR","Puerto Rico")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"QA","Qatar")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"KR","Republic Of Korea")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MD","Republic Of Moldova")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"RE","Reunion")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"RO","Romania")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"RU","Russian Federation")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"RW","Rwanda")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"WS","Samoa")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SM","San Marino")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"ST","Sao Tome and Principe")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SA","Saudi Arabia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SN","Senegal")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CS","Serbia and Montenegro")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"YU","Serbia and Montenegro")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SC","Seychelles")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SL","Sierra Leone")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SG","Singapore")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SK","Slovakia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SI","Slovenia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SB","Solomon Islands")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SO","Somalia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"ZA","South Africa")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"ES","Spain")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"LK","Sri Lanka")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SD","Sudan")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SR","Suriname")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SZ","Swaziland")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SE","Sweden")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CH","Switzerland")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"SY","Syrian Arab Republic")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"TW","Taiwan")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"TJ","Tajikistan")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"TH","Thailand")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"CD","The Democratic Republic Of The Congo")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"MK","The Former Yugoslav Republic Of Macedonia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"TG","Togo")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"TK","Tokelau")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"TO","Tonga")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"TT","Trinidad and Tobago")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"TN","Tunisia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"TR","Turkey")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"TM","Turkmenistan")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"TV","Tuvalu")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"UG","Uganda")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"UA","Ukraine")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"AE","United Arab Emirates")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"GB","United Kingdom")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"TZ","United Republic Of Tanzania")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"US","United States")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"UY","Uruguay")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"UZ","Uzbekistan")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"VU","Vanuatu")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"VE","Venezuela")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"VN","Viet Nam")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"VG","Virgin Islands, British")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"VI","Virgin Islands, U.S.")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"EH","Western Sahara")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"YE","Yemen")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"ZM","Zambia")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["country"].' VALUES (NULL,"ZW","Zimbabw")') or die(__LINE__.mysql_error());


//Таблица штатов и их гоегрофических координат
@mysql_query('drop table '.$db_tables["state"]);
mysql_query('
CREATE TABLE '.$db_tables["state"].' (
  state_id SMALLINT(5) UNSIGNED NOT NULL auto_increment,
  state_name varchar(200) NOT NULL,
	latitude DECIMAL(10,4) NOT NULL default "0",
	latitude1 DECIMAL(10,4) NOT NULL default "0",
	latitude2 DECIMAL(10,4) NOT NULL default "0",
	longitude DECIMAL(10,4) NOT NULL default "0",
	longitude1 DECIMAL(10,4) NOT NULL default "0",
	longitude2 DECIMAL(10,4) NOT NULL default "0",
	INDEX(state_name(2)),
	INDEX(latitude,latitude1,latitude2),
	INDEX(longitude,longitude1,longitude2),
  PRIMARY KEY (state_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//latitude, longitude - avarage latitude and longitude
//latitude1, longitude1 - min latitude and longitude
//latitude2, longitude2 - max latitude and longitude

mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"AK","61.3850","51.0333","71.8333","-152.2683","-130.0000","-172.0000")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"AL","32.7990","30.1833","35.0000","-86.8073","-88.4666","-84.8833")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"AR","34.9513","33.0000","36.5000","-92.3809","-94.6166","-89.0650")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"AS","14.2417","14.2417","14.2417","-170.7197","-170.7197","-170.7197")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"AZ","33.7712","31.3333","37.0000","-111.3877","-114.8166","-109.0500")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"CA","36.1700","32.5333","42.0000","-119.7462","-124.4333","-114.1333")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"CO","39.0646","37.0000","41.0000","-105.3272","-109.0500","-102.0500")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"CT","41.5834","40.9666","42.0500","-72.7622","-73.7333","-71.7833")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"DC","38.8964","38.8964","38.8964","-77.0262","-77.0262","-77.0262")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"DE","39.3498","38.4500","39.8333","-75.5148","-75.7833","-75.0500")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"FL","27.8333","24.4500","31.0000","-81.7170","-87.6333","-80.0333")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"GA","32.9866","30.3565","34.9855","-83.6487","-85.6055","-80.840")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"HI","21.1098","18.9166","28.4500","-157.5311","-178.3666","-154.8000")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"IA","42.0046","40.3833","43.5000","-93.2140","-96.6333","-90.1333")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"ID","44.2394","42.0000","49.0000","-114.5103","-117.2500","-111.0500")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"IL","40.3363","36.9666","42.5000","-89.0022","-91.5166","-87.5000")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"IN","39.8647","37.7666","41.7666","-86.2604","-88.1000","-84.7833")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"KS","38.5111","37.0000","40.0000","-96.8005","-102.0500","-94.5833")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"KY","37.6690","36.5000","39.1500","-84.6514","-89.5666","-81.9600")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"LA","31.1801","28.9555","33.0166","-91.8749","-94.0500","-88.8166")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"MA","42.2373","41.2333","42.8833","-71.5314","-73.5000","-69.9333")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"MD","39.0724","37.8833","39.7166","-76.7902","-79.4833","-75.0500")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"ME","44.6074","44.6074","44.6074","-69.3977","-71.0833","-66.9500")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"MI","43.3504","41.6833","48.3000","-84.5603","-90.4166","-82.1166")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"MN","45.7326","43.5000","49.3800","-93.9196","-97.2333","-89.4833")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"MO","38.4623","36.0000","40.6166","-92.3020","-95.7666","-89.1000")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"MP","14.8058","14.8058","14.8058","145.5505","145.5505","145.5505")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"MS","32.7673","30.2000","35.0000","-89.6812","-91.6500","-88.1000")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"MT","46.9048","44.3500","49.0000","-110.3261","-116.0500","-104.03333")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"NC","35.6411","33.8333","36.5833","-79.8431","-84.3166","-75.4666")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"ND","47.5362","45.9333","49.0000","-99.7930","-104.0500","-96.5500")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"NE","41.1289","40.0000","43.0000","-98.2883","-104.0500","-95.3166")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"NH","43.4108","42.7000","45.3000","-71.5653","-72.5500","-70.6000")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"NJ","40.3140","38.9333","41.3500","-74.5089","-75.5666","-73.9000")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"NM","34.8375","31.9333","37.0000","-106.2371","-109.0500","-103.0000")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"NV","38.4199","35.0000","42.0000","-117.1219","-120.0000","-114.0333")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"NY","42.1497","40.5000","45.0166","-74.9384","-79.7666","-71.8500")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"OH","40.3736","38.4000","41.9833","-82.7755","-84.8166","-80.5166")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"OK","35.5376","33.6166","37.0000","-96.9247","-103.0000","-94.4333")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"OR","44.5672","42.0000","46.2500","-122.1269","-124.5000","-116.7500")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"PA","40.5773","39.7166","42.0000","-77.2640","-80.5166","-74.1766")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"PR","18.2766","18.2766","18.2766","-66.3350","-66.3350","-66.3350")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"RI","41.6772","41.3000","42.0166","-71.5101","-71.8833","-71.1333")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"SC","33.8191","32.0666","35.200","-80.9066","-83.3333","-78.5000")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"SD","44.2853","42.4800","45.9333","-99.4632","-104.0500","-98.4666")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"TN","35.7449","35.0000","36.6833","-86.7489","-90.4666","-81.6166")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"TX","31.1060","25.8333","36.5000","-97.6475","-106.6333","-93.5166")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"UT","40.1135","37.0000","42.0000","-111.8535","-114.0000","-109.0000")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"VA","37.7680","36.6166","39.6166","-78.2057","-83.6166","-75.2166")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"VI","18.0001","18.0001","18.0001","-64.8199","-64.8199","-64.8199")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"VT","44.0407","42.7333","45.0000","-72.7093","-73.4333","-71.4666")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"WA","47.3917","45.5333","49.0000","-121.5708","-124.8000","-116.9500")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"WI","44.2563","42.5000","47.0500","-89.6385","-92.9000","-86.8166")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"WV","38.4680","37.1666","40.6666","-80.9696","-82.6666","-77.6666")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"WY","42.7475","41.0000","45.0000","-107.2085","-111.0500","-104.0500")') or die(__LINE__.mysql_error());

mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"AB","54.5000","49.0000","60.0000","-112.0330","-114.0667","-110.0000")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"BC","54.5000","49.0000","60.0000","-126.5580","-139.0500","-114.0667")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"MB","54.3083","49.8667","58.7500","-97.9583","-101.8500","-94.0667")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"NB","46.6583","45.3167","48.0000","-66.5083","-68.3333","-64.6833")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"NF","50.4667","47.6167","53.3167","-56.5833","-60.4167","-52.7500")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"NT","67.3667","60.0167","74.7167","-101.017","-133.4833","-68.5500")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"NU","60.0000","60.0000","60.0000","-102.0000","-102.0000","-102.0000")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"NS","45.0000","43.8333","46.1667","-63.06673","-66.0833","-60.0500")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"ON","46.3333","42.2667","49.8000","-84.5583","-94.3667","-74.7500")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"PE","46.3583","46.2833","46.4333","-63.4833","-63.8333","-63.1333")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"QC","47.7417","45.2667","50.2167","-72.0250","-77.7833","-66.2667")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"SK","51.1417","49.0667","53.2167","-105.3580","-108.2500","-102.4667")') or die(__LINE__.mysql_error());
mysql_query('INSERT INTO '.$db_tables["state"].' VALUES (NULL,"YT","60.7166","60.7166","60.7166","-135.0666","-135.0666","-135.0666")') or die(__LINE__.mysql_error());

?>
</body>
<center><h1>Filled tables. Installation complite.</h1>
<h3><a href="index.html">Back to install page</h3></center>
</html>