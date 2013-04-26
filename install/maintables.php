<html>
<head></head>
<body>
<?php
/*
#################
# maintables.php
# The main installation file.
# (Main)
#################
*/

//Admin info
$admin_name = "admin";
$admin_password = "admin";
$admin_email = "admin1@somedomain.com";
//Common info
$SiteTitle			= "ES Job Search Engine";
$SiteURL				= "http://localhost/esjobsearchengine/";

require "../management/connect.inc";
require "../management/consts.php";

doconnect();

@mysql_query('drop table '.$db_tables["admins"]);
mysql_query('
CREATE TABLE '.$db_tables["admins"].' (
  admid TINYINT UNSIGNED NOT NULL auto_increment,
  admname VARCHAR(70) NOT NULL,
  admpass VARCHAR(70) NOT NULL,
  admemail VARCHAR(70) NOT NULL,
  PRIMARY KEY  (admid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());

mysql_query('
INSERT INTO '.$db_tables["admins"].' VALUES (1,"'.$admin_name.'",password("'.$admin_password.'"),"'.$admin_email.'")
') or die(__LINE__.mysql_error());

@mysql_query('drop table '.$db_tables["attach"]);
mysql_query('
CREATE TABLE '.$db_tables["attach"].' (
  aid SMALLINT UNSIGNED NOT NULL auto_increment,
  mailname VARCHAR(50) NOT NULL,
  path VARCHAR(150) default NULL,
  PRIMARY KEY  (aid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());

@mysql_query('drop table '.$db_tables["mailsubject"]);
mysql_query('
CREATE TABLE '.$db_tables["mailsubject"].' (
  msid SMALLINT(5) UNSIGNED NOT NULL auto_increment,
  mailkey VARCHAR(50) NOT NULL,
  mailsubject VARCHAR(100) NOT NULL,
	INDEX(mailkey(2)),
  PRIMARY KEY  (msid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());

mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (1,"sign_up_confirm","Email confirmation for sign up from {*site_title*}")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (2,"sign_up_welcome_adv","Advertiser, welcome to {*site_title*}")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (3,"sign_up_welcome_pub","Publisher, welcome to {*site_title*}")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (4,"credited_notification","Account credited notification from {*site_title*}")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (5,"request_payment","Affiliate payment request")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (6,"email_job","{*job_title*} job from {*site_title*}")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (7,"forgotpass","Password recovering from {*site_title*}")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (8,"job_search_alert","Job Search Alert from {*site_title*}")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (9,"sign_up_welcome_mem","Member, welcome to {*site_title*}")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (10,"sign_up_appoved","Your account on {*site_title*} was approved")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (11,"member_job_alert","Jobs alert from {*site_title*}")
') or die(__LINE__.mysql_error());


/*
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (5,"admin_credited_notification","Admin Account credited notification from {*site_title*}")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (6,"orderevaded_it","it Admin switch the order status from pending to completed")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (7,"reminder_en","en Sent X days before the annual donation expiry")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (8,"reminder_it","it Sent X days before the annual donation expiry")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["mailsubject"].' VALUES (10,"forgotpass_it","it Password recovering")
') or die(__LINE__.mysql_error());
*/

@mysql_query('drop table '.$db_tables["users_advertiser"]);
mysql_query('
CREATE TABLE '.$db_tables["users_advertiser"].' (
  uid_adv MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(100) NOT NULL,
  pass VARCHAR(100) NOT NULL,
  company VARCHAR(150) NOT NULL,
  name VARCHAR(250) NOT NULL,
  phone VARCHAR(50),
  fax VARCHAR(50),
  site VARCHAR(200),
  address1 VARCHAR(200),
  address2 VARCHAR(200),
	country_id SMALLINT(5) UNSIGNED NOT NULL default 0,
  city VARCHAR(80),
  state VARCHAR(50),
  zipcode VARCHAR(50),
  promotioncode VARCHAR(50),
	balance DECIMAL(12,2) NOT NULL default "0",
	regdate DATE NOT NULL,
	deldate DATE default NULL,
  isconfirmed BOOL NOT NULL,
  isenable BOOL NOT NULL,
  isdeleted BOOL NOT NULL,
	INDEX(name(2)),
	INDEX(city(2)),
	INDEX(state(2)),
	INDEX(regdate,deldate),
	INDEX(country_id),
	INDEX(balance),
	INDEX(isconfirmed,isenable,isdeleted),
  UNIQUE(email),
  PRIMARY KEY (uid_adv)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//isconfirmed - подтвердил ли админ его админ
//isenable - находится в работе или заблокирован
//isdeleted - пользователь удален

@mysql_query('drop table '.$db_tables["users_publisher"]);
mysql_query('
CREATE TABLE '.$db_tables["users_publisher"].' (
  uid_pub MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(100) NOT NULL,
  pass VARCHAR(100) NOT NULL,
  company VARCHAR(150) NOT NULL,
  name VARCHAR(250) NOT NULL,
  phone VARCHAR(50),
  fax VARCHAR(50),
  site VARCHAR(200) NOT NULL,
  address1 VARCHAR(200) NOT NULL,
  address2 VARCHAR(200),
	country_id SMALLINT(5) UNSIGNED NOT NULL,
  city VARCHAR(80) NOT NULL,
  state VARCHAR(50) NOT NULL,
  zipcode VARCHAR(50) NOT NULL,
  promotioncode VARCHAR(50) NOT NULL,
  ssn VARCHAR(16) NOT NULL,
	balance DECIMAL(12,2) NOT NULL default "0",
	regdate DATE NOT NULL,
	deldate DATE default NULL,
  isconfirmed BOOL NOT NULL,
  isenable BOOL NOT NULL,
	isxmlfeed_enable BOOL NOT NULL,
  isdeleted BOOL NOT NULL,
	INDEX(name(2)),
	INDEX(city(2)),
	INDEX(state(2)),
	INDEX(regdate,deldate),
	INDEX(balance),
	INDEX(country_id),
	INDEX(isconfirmed,isenable,isxmlfeed_enable,isdeleted),
  UNIQUE(email),
  PRIMARY KEY (uid_pub)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//isconfirmed - подтвердил ли админ его админ
//isenable - находится в работе или заблокирован
//isdeleted - пользователь удален

//Таблица мембера
@mysql_query('drop table '.$db_tables["users_member"]);
mysql_query('
CREATE TABLE '.$db_tables["users_member"].' (
  uid_mem MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(100) NOT NULL,
  pass VARCHAR(100) NOT NULL,
  first_name VARCHAR(250) NOT NULL,
  last_name VARCHAR(250) NOT NULL,
  site VARCHAR(250) NOT NULL,
	country_id SMALLINT(5) UNSIGNED NOT NULL,
  city VARCHAR(80) NOT NULL,
  state VARCHAR(50) NOT NULL,
  zipcode VARCHAR(50) NOT NULL,
	regdate DATE NOT NULL,
  isconfirmed BOOL NOT NULL,
	INDEX(first_name(5)),
	INDEX(last_name(5)),
	INDEX(city(2)),
	INDEX(state(2)),
	INDEX(regdate),
	INDEX(country_id),
	INDEX(isconfirmed),
  UNIQUE(email),
  PRIMARY KEY (uid_mem)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());

@mysql_query('drop table '.$db_tables["users_advertiser_settings"]);
mysql_query('
CREATE TABLE '.$db_tables["users_advertiser_settings"].' (
  uid_adv MEDIUMINT UNSIGNED NOT NULL,
  shownews BOOL NOT NULL,
	INDEX(uid_adv,shownews)
  ) ENGINE=MyISAM
') or die(__LINE__.mysql_error());

@mysql_query('drop table '.$db_tables["users_publisher_settings"]);
mysql_query('
CREATE TABLE '.$db_tables["users_publisher_settings"].' (
  uid_pub MEDIUMINT UNSIGNED NOT NULL,
  shownews BOOL NOT NULL,
	INDEX(uid_pub,shownews)
  ) ENGINE=MyISAM
') or die(__LINE__.mysql_error());

@mysql_query('drop table '.$db_tables["users_publisher_channels"]);
mysql_query('
CREATE TABLE '.$db_tables["users_publisher_channels"].' (
  channel_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  uid_pub MEDIUMINT UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL default "",
	INDEX(uid_pub),
  PRIMARY KEY (channel_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//Publisher channels - каналы для Jobroll. Создаются в меню "Create a Jobroll" веб-мастера
//channel_id - id канала
//uid_pub - веб-мастер, которому принадлежит канал
//name - имя канала

//Таблица подтверждений для e-mail-ов (при регистрации пользователей)
@mysql_query('drop table '.$db_tables["users_confirm_email"]);
mysql_query('
CREATE TABLE '.$db_tables["users_confirm_email"].' (
  uceid MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  confirm_id VARCHAR(33) NOT NULL default "",
  confirm_email VARCHAR(80) NOT NULL default "",
  INDEX (confirm_id(5)),
  PRIMARY KEY (uceid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//users_confirm_email - подтверждение e-mail пользоватетля

//Таблица запрососв на доп ресурсы (XML Feed)
@mysql_query('drop table '.$db_tables["users_submissions"]);
mysql_query('
CREATE TABLE '.$db_tables["users_submissions"].' (
  us MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  uid MEDIUMINT UNSIGNED NOT NULL,
  usertype VARCHAR(5) NOT NULL default "",
  restype VARCHAR(5) NOT NULL default "",
  INDEX (uid),
  INDEX (usertype(1)),
  INDEX (restype(1)),
  PRIMARY KEY (us)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());

@mysql_query('drop table '.$db_tables["users_member_settings"]);
mysql_query('
CREATE TABLE '.$db_tables["users_member_settings"].' (
  uid_mem MEDIUMINT UNSIGNED NOT NULL,
  shownews BOOL NOT NULL,
	INDEX(uid_mem,shownews)
  ) ENGINE=MyISAM
') or die(__LINE__.mysql_error());

//Таблица job alert-ов. Пользователь может создать несколько job alert-ов
@mysql_query('drop table '.$db_tables["member_job_alerts"]);
mysql_query('
CREATE TABLE '.$db_tables["member_job_alerts"].' (
  ja_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  uid_mem MEDIUMINT UNSIGNED NOT NULL,
  name VARCHAR(80) NOT NULL default "",
	job_alert TEXT NOT NULL,
	regdate DATETIME NOT NULL,
	senddate DATETIME NOT NULL,
	deliver TINYINT NOT NULL,
  status BOOL NOT NULL,
	INDEX(uid_mem),
	INDEX(regdate),
	INDEX(senddate),
  INDEX(name(5)),
	INDEX(deliver),
	INDEX(status),
  PRIMARY KEY (ja_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//job_alert - строка в виде XML с параметрами (как Advanced Job Search форма)
//senddate - дата отправки в последний раз
//deliver - 1 (каждый день), 7 - раз в неделю

@mysql_query('drop table '.$db_tables["globsettings"]);
mysql_query('
CREATE TABLE '.$db_tables["globsettings"].' (
  settings_name VARCHAR(100) NOT NULL default "",
  settings_value VARCHAR(100) NOT NULL default ""
  ) ENGINE=MyISAM
') or die(__LINE__.mysql_error());

mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("site_title","'.$SiteTitle.'")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("site_url","'.$SiteURL.'")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("adv_start_balance","0.00")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("min_adv_cost_per_click","0.25")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("amount_of_listings","45")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("amount_of_adv_sponsor_jobs_top","2")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("amount_of_adv_sponsor_jobs_bottom","2")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("amount_of_adv_keyword_ads","3")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("max_adv_headline_length","35")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("max_adv_line1_length","40")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("max_adv_line2_length","40")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("member_approved","1")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("xml_pub_approved","1")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("window_target","_blank")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("pub_start_balance","0.00")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("pub_referal_percent","20")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("use_stats_cache","1")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("cache_actualtime_admin","900")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("cache_actualtime_adv","900")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("cache_actualtime_pub","900")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("use_frontend_cache","1")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("cache_frontend_actualtime_pages","900")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("cache_frontend_actualtime_primitives","960")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("earn_ip_protection","2")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("allow_cities_in_db","1")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("allow_cities_not_in_db","0")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["globsettings"].' VALUES ("jobs_without_city","0")
') or die(__LINE__.mysql_error());



@mysql_query('drop table '.$db_tables["jobrollsettings"]);
mysql_query('
CREATE TABLE '.$db_tables["jobrollsettings"].' (
  settings_name VARCHAR(100) NOT NULL default "",
  settings_value VARCHAR(100) NOT NULL default ""
  ) ENGINE=MyISAM
') or die(__LINE__.mysql_error());

mysql_query('
INSERT INTO '.$db_tables["jobrollsettings"].' VALUES ("job_set_colors_bg","#FFFFFF")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["jobrollsettings"].' VALUES ("job_set_colors_title","#000000")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["jobrollsettings"].' VALUES ("job_set_colors_border","#AAAAAA")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["jobrollsettings"].' VALUES ("job_set_colors_job_title","#0000CC")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["jobrollsettings"].' VALUES ("job_set_colors_text","#000000")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["jobrollsettings"].' VALUES ("job_set_colors_company","#000000")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["jobrollsettings"].' VALUES ("job_set_colors_link","#0000CC")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["jobrollsettings"].' VALUES ("job_set_colors_source","#008800")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["jobrollsettings"].' VALUES ("job_set_colors_accent","#FF6600")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["jobrollsettings"].' VALUES ("job_set_colors_location","#666666")
') or die(__LINE__.mysql_error());


@mysql_query('drop table '.$db_tables["ipfirewall"]);
mysql_query('
CREATE TABLE '.$db_tables["ipfirewall"].' (
  ipid MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  ip CHAR(15) NOT NULL default "",
  INDEX (ip),
  PRIMARY KEY (ipid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());

@mysql_query('drop table '.$db_tables["ads"]);
mysql_query('
CREATE TABLE '.$db_tables["ads"].' (
  ad_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  uid_adv MEDIUMINT UNSIGNED NOT NULL,
	ad_name VARCHAR(150) NOT NULL default "",
	headline VARCHAR(100) NOT NULL default "",
	line_1 VARCHAR(100) NOT NULL default "",
	line_2 VARCHAR(100) NOT NULL default "",
	display_url VARCHAR(250) NOT NULL default "",
	destination_url VARCHAR(250) NOT NULL default "",
	max_cpc DECIMAL(12,2) NOT NULL default "0",
	daily_budget DECIMAL(12,2) NOT NULL default "0",
	monthly_budget DECIMAL(12,2) NOT NULL default "0",
  status BOOL NOT NULL,
	INDEX (uid_adv),
	INDEX (ad_name(5)),
	INDEX (max_cpc),
	INDEX (daily_budget),
	INDEX (monthly_budget),
	INDEX (status),
  INDEX (ad_name(10)),
  PRIMARY KEY (ad_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//Это Keyword ADs
//ad_id - id компании. компания сожедржит слова
//status - статус комании (1=>active | 0=>disable)

@mysql_query('drop table '.$db_tables["keyword_ads"]);
mysql_query('
CREATE TABLE '.$db_tables["keyword_ads"].' (
  kads_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  ad_id MEDIUMINT UNSIGNED NOT NULL,
  ad_status BOOL NOT NULL,
  kads_status BOOL NOT NULL,
  soptions SMALLINT UNSIGNED NOT NULL,
  keyword CHAR(100) NOT NULL,
	INDEX (ad_id),
	INDEX (ad_status),
	INDEX (kads_status),
	INDEX (soptions),
  INDEX (keyword(10)),
  PRIMARY KEY (kads_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//kads_id - id слова
//ad_id - id компании к которой принадлежит слово
//ad_status - статус компании к которой принадлежит слово. добавлено для скорости поистка всегда равно status из табл. "ads"
//kads_status - статус слова (1=>active | 0=>disable)
//soptions - опция поиска:
//		1: broad - broad match (SQL: "like")
//		2: exact - exact match (SQL: "=")
//		3: phrase - phrase match (SQL: "=", но для фразы)
//		4: negative - negative match (SQL: "<>")
//keyword - слова или фраза

//Это Jobs ADs
@mysql_query('drop table '.$db_tables["job_ads"]);
mysql_query('
CREATE TABLE '.$db_tables["job_ads"].' (
  job_ads_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  uid_adv MEDIUMINT UNSIGNED NOT NULL,
	ad_name VARCHAR(150) NOT NULL default "",
	destination_url VARCHAR(250) NOT NULL default "",
	max_cpc DECIMAL(12,2) NOT NULL default "0",
	daily_budget DECIMAL(12,2) NOT NULL default "0",
	monthly_budget DECIMAL(12,2) NOT NULL default "0",
  status BOOL NOT NULL,
	INDEX (uid_adv),
	INDEX (max_cpc),
	INDEX (daily_budget),
	INDEX (monthly_budget),
	INDEX (status),
  INDEX (ad_name(10)),
  PRIMARY KEY (job_ads_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//destination_url - сайст с которого брать список работ
//status - статус комании (1=>active | 0=>disable)



//*********************//
//* S T A T I S T I C *//
//*********************//

//Статистика посетителей. Как только посетитель защел на страницу или перешел с сайта вебмастера
@mysql_query('drop table '.$db_tables["stats_visitor_info"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_visitor_info"].' (
  stat_vi BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	entertime DATETIME NOT NULL,
	ip VARCHAR(15) NOT NULL default "",
  ip_over_proxy VARCHAR(15) NOT NULL default "",
  refer_url VARCHAR(255) NOT NULL,
  request_url VARCHAR(255) NOT NULL,
  INDEX (entertime),
  INDEX (ip(7)),
  PRIMARY KEY (stat_vi)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//stat_vi ID посетителя и его IP (поле ip)

//Статистска по словам поиска. Как только произошел поиск по слову на сайте или xml
@mysql_query('drop table '.$db_tables["stats_search_keywords"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_search_keywords"].' (
  stat_kid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  keyword VARCHAR(100) NOT NULL default "",
  stat_vi BIGINT UNSIGNED NOT NULL,
	searchtime DATETIME NOT NULL,
  searchtype TINYINT UNSIGNED NOT NULL,
	uid_pub MEDIUMINT UNSIGNED NOT NULL,
  INDEX (keyword(10)),
  INDEX (stat_vi),
  INDEX (searchtime),
  INDEX (searchtype),
  INDEX (uid_pub),
  PRIMARY KEY (stat_kid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//keyword - слово или фраза, к-рое искали
//stat_vi - кто искал (ссылка на посетителя)
//searchtime время поиска этого слова (при добавлении в таблицу)
//searchtype - тип поиска 1-html 2-xml
//uid_pub - от какого вебмастера произошел поиск. Если нет - то там 0

//Статистска по удачным словам поиска. Как только произошел поиск по слову на сайте или xml и есть результат поиска
//Нужно для страницы Browse jobs - keyword
@mysql_query('drop table '.$db_tables["stats_search_success_keywords"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_search_success_keywords"].' (
  stat_kid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  keyword VARCHAR(100) NOT NULL default "",
  INDEX (keyword(10)),
  PRIMARY KEY (stat_kid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//keyword - слово или фраза, к-рое искали

//Статистска по кликам. Как только произошел клик на работе (не важно обычной или рекламой)
@mysql_query('drop table '.$db_tables["stats_clicks"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_clicks"].' (
  stat_click BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  jobid BIGINT UNSIGNED NOT NULL,
	click_type TINYINT UNSIGNED NOT NULL,
  stat_kid BIGINT UNSIGNED NOT NULL,
	clicktime DATETIME NOT NULL,
  INDEX (jobid),
  INDEX (click_type),
  INDEX (stat_kid),
  INDEX (clicktime),
  PRIMARY KEY (stat_click)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//jobid - id работы на которую кликнули
//click_type - тип клика (0 - на обычной работе; 1 - на рекламной работе(вверху и внизу); 2 - на рекламной sponsored links(справа))
//stat_kid - на ИД поиска из табл. "stats_search_keywords"



//Статистска по показам для Рекламодателей. Как только произошел поиск по слову которое рекламирует рекламодатель
//можно показать несколько объявлений. Это список тех, которые БЫЛИ ПОКАЗАНЫ
@mysql_query('drop table '.$db_tables["stats_adv_pageview_keywords"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_adv_pageview_keywords"].' (
  stat_adv_kid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  kads_id MEDIUMINT UNSIGNED NOT NULL,
  ad_id MEDIUMINT UNSIGNED NOT NULL,
  uid_adv MEDIUMINT UNSIGNED NOT NULL,
	actiontime DATETIME NOT NULL,
  INDEX (kads_id),
  INDEX (ad_id),
  INDEX (uid_adv),
  INDEX (actiontime),
  PRIMARY KEY (stat_adv_kid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//kads_id - какое рекламное слово сработало
//ad_id - какая рекламная компания сработала
//uid_adv - какой рекламодатель создал эту компанию и слово
//actiontime - время записи
@mysql_query('drop table '.$db_tables["stats_adv_pageview_jobs"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_adv_pageview_jobs"].' (
  stat_adv_jobid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  job_ads_id MEDIUMINT UNSIGNED NOT NULL,
  uid_adv MEDIUMINT UNSIGNED NOT NULL,
	actiontime DATETIME NOT NULL,
  INDEX (job_ads_id),
  INDEX (uid_adv),
  INDEX (actiontime),
  PRIMARY KEY (stat_adv_jobid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//job_ads_id - какая рекламная компания по работе с сайта сработала
//uid_adv - какой рекламодатель создал эту компанию о работе
//actiontime - время записи

//Статистска по возможным показам для Рекламодателей. Как только произошел поиск по слову которое рекламирует рекламодатель
//можно показать несколько объявлений. Это список тех, которые МОЖНО ПОЗАЗАТЬ. Но будут показаны не все.
@mysql_query('drop table '.$db_tables["stats_adv_maybe_pageview_keywords"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_adv_maybe_pageview_keywords"].' (
  stat_adv_kid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  kads_id MEDIUMINT UNSIGNED NOT NULL,
  ad_id MEDIUMINT UNSIGNED NOT NULL,
  uid_adv MEDIUMINT UNSIGNED NOT NULL,
	page MEDIUMINT UNSIGNED NOT NULL,
	actiontime DATETIME NOT NULL,
  INDEX (kads_id),
  INDEX (ad_id),
  INDEX (uid_adv),
  INDEX (page),
  INDEX (actiontime),
  PRIMARY KEY (stat_adv_kid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//kads_id - какое рекламное слово сработало
//ad_id - какая рекламная компания сработала
//uid_adv - какой рекламодатель создал эту компанию и слово
//page - страница на которой можно показать это объвление. Не факт, что оно будет показано.
//actiontime - время записи
@mysql_query('drop table '.$db_tables["stats_adv_maybe_pageview_jobs"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_adv_maybe_pageview_jobs"].' (
  stat_adv_jid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  job_ads_id MEDIUMINT UNSIGNED NOT NULL,
  uid_adv MEDIUMINT UNSIGNED NOT NULL,
	page MEDIUMINT UNSIGNED NOT NULL,
	actiontime DATETIME NOT NULL,
  INDEX (job_ads_id),
  INDEX (uid_adv),
  INDEX (page),
  INDEX (actiontime),
  PRIMARY KEY (stat_adv_jid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//job_ads_id - какая рекламная компания по работе с сайта сработала
//uid_adv - какой рекламодатель создал эту компанию и слово
//page - страница на которой можно показать это объвление. Не факт, что оно будет показано.
//actiontime - время записи


//Статистска по кликам для Рекламодателей. Как только произошел клик на объявлении рекламодателя
@mysql_query('drop table '.$db_tables["stats_adv_click_keywords"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_adv_click_keywords"].' (
  stat_adv_cid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  kads_id MEDIUMINT UNSIGNED NOT NULL,
  ad_id MEDIUMINT UNSIGNED NOT NULL,
  uid_adv MEDIUMINT UNSIGNED NOT NULL,
  cost DECIMAL(12,2) NOT NULL,
	actiontime DATETIME NOT NULL,
  INDEX (kads_id),
  INDEX (ad_id),
  INDEX (uid_adv),
  INDEX (cost),
  INDEX (actiontime),
  PRIMARY KEY (stat_adv_cid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//kads_id - какое рекламное слово сработало
//ad_id - какая рекламная компания сработала
//uid_adv - какой рекламодатель создал эту компанию и слово
//cost - цена за этот клик
//actiontime - время записи
//!Замечание. После поиска могут сработать несколько слов одно и того же рекламодателя. Будет считаться клик на все эти слова
//            и будет несколько записей в таблице (для каждого из слов)
@mysql_query('drop table '.$db_tables["stats_adv_click_jobs"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_adv_click_jobs"].' (
  stat_adv_jid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  job_ads_id MEDIUMINT UNSIGNED NOT NULL,
  uid_adv MEDIUMINT UNSIGNED NOT NULL,
  cost DECIMAL(12,2) NOT NULL,
	actiontime DATETIME NOT NULL,
  INDEX (job_ads_id),
  INDEX (uid_adv),
  INDEX (cost),
  INDEX (actiontime),
  PRIMARY KEY (stat_adv_jid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//job_ads_id - какая рекламная компания по работе с сайта сработала
//uid_adv - какой рекламодатель создал эту компанию и слово
//cost - цена за этот клик
//actiontime - время записи
//!Замечание. После поиска могут сработать несколько слов одно и того же рекламодателя. Будет считаться клик на все эти слова
//            и будет несколько записей в таблице (для каждого из слов)




//Статистска по показам для Вебмастеров. Как только произошел показ у вебмастера
@mysql_query('drop table '.$db_tables["stats_pub_pageview"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_pub_pageview"].' (
  stat_pub_pv BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	uid_pub MEDIUMINT UNSIGNED NOT NULL,
	channel_id MEDIUMINT UNSIGNED NOT NULL,
	actiontime DATETIME NOT NULL,
  INDEX (uid_pub),
  INDEX (channel_id),
  INDEX (actiontime),
  PRIMARY KEY (stat_pub_pv)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//uid_adv - посетительно какого вебмастера произвел просмотр
//channel_id - канал
//actiontime - время записи

//Статистска по кликам для Вебмастеров. Как только произошел клик от посетителя, пришедшего от вебмастера
@mysql_query('drop table '.$db_tables["stats_pub_click_keywords"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_pub_click_keywords"].' (
  stat_pub_cid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  uid_pub MEDIUMINT UNSIGNED NOT NULL,
	channel_id MEDIUMINT UNSIGNED NOT NULL,
	stat_click BIGINT UNSIGNED NOT NULL,
	actiontime DATETIME NOT NULL,
  INDEX (uid_pub),
  INDEX (stat_click),
  INDEX (actiontime),
  PRIMARY KEY (stat_pub_cid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//uid_pub - вебмастер с которого пришел посетитель
//channel_id - канал
//stat_click - ид клика, из таблицы "stats_clicks"


//Статистска по заработкам для Вебмастеров.
@mysql_query('drop table '.$db_tables["stats_pub_earn_clicks"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_pub_earn_clicks"].' (
  stat_pub_ecid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  uid_pub MEDIUMINT UNSIGNED NOT NULL,
	channel_id MEDIUMINT UNSIGNED NOT NULL,
	stat_click BIGINT UNSIGNED NOT NULL,
	amount DECIMAL(12,2) NOT NULL default "0",
	actiontime DATETIME NOT NULL,
  INDEX (uid_pub),
  INDEX (stat_click),
  INDEX (amount),
  INDEX (actiontime),
  PRIMARY KEY (stat_pub_ecid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//uid_pub - вебмастер, который заработал денег
//channel_id - канал
//stat_click - ид клика, из таблицы "stats_clicks" (за какой клик заработал)
//amount - сколько заработал


//Статистска по IP адресам, которые вычли деньги с рекламодателя и время этого события.
@mysql_query('drop table '.$db_tables["stats_earned_ips"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_earned_ips"].' (
  stat_earned_ip BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	IpNum INT(10) UNSIGNED NOT NULL,
  uid_adv MEDIUMINT UNSIGNED NOT NULL,
	amount DECIMAL(12,2) NOT NULL default "0",
	actiontime DATETIME NOT NULL,
  INDEX (IpNum),
  INDEX (uid_adv),
  INDEX (amount),
  INDEX (actiontime),
  PRIMARY KEY (stat_earned_ip)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//IpNum - IP адрес, который вычел деньги с рекламодателя
//uid_adv - рекламодатель с которого вычли деньги
//amount - сумма, котрую вычли
//actiontime - время действия



//*****************//
//* P A Y M E N T *//
//*****************//
@mysql_query('drop table '.$db_tables["paymentsettings"]);
mysql_query('
CREATE TABLE '.$db_tables["paymentsettings"].' (
	credit_card_accept TINYINT UNSIGNED NOT NULL default "1",
	credit_card_login VARCHAR(80) NOT NULL default "",
	credit_card_minwithdraw DECIMAL(12,2) NOT NULL default "10",
	credit_card_mindeposit DECIMAL(12,2) NOT NULL default "10",
	paypal_accept TINYINT UNSIGNED NOT NULL default "1",
	paypal_email VARCHAR(80) NOT NULL default "",
	paypal_minwithdraw DECIMAL(12,2) NOT NULL default "10",
	paypal_mindeposit DECIMAL(12,2) NOT NULL default "10",
	egold_accept TINYINT UNSIGNED NOT NULL default "1",
	egold_id VARCHAR(80) NOT NULL default "",
	egold_passphrase VARCHAR(80) NOT NULL default "",
	egold_minwithdraw DECIMAL(12,2) NOT NULL default "10",
	egold_mindeposit DECIMAL(12,2) NOT NULL default "10",
	2checkout_accept TINYINT UNSIGNED NOT NULL default "1",
	2checkout_id VARCHAR(80) NOT NULL default "",
	2checkout_minwithdraw DECIMAL(12,2) NOT NULL default "10",
	2checkout_mindeposit DECIMAL(12,2) NOT NULL default "10",
	2checkout_url VARCHAR(200) NOT NULL default "https://www.2checkout.com/cgi-bin/sbuyers/cartpurchase.2c",
	2checkout_test BOOL NOT NULL default "0") ENGINE=MyISAM
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["paymentsettings"].' VALUES ("1","","10","10","1","","10","10","0","","","10","10","0","","10","10","https://www.2checkout.com/cgi-bin/sbuyers/cartpurchase.2c","0")
') or die(__LINE__.mysql_error());

//Таблица платежей от Advertiser-ов (уже когда платеж прошел - т.е. отвер от сервера)
@mysql_query('drop table '.$db_tables["payments_adv"]);
mysql_query('
CREATE TABLE '.$db_tables["payments_adv"].' (
	pid INT UNSIGNED NOT NULL auto_increment,
	uid_adv MEDIUMINT UNSIGNED NOT NULL,
	regtime DATETIME NOT NULL,
	amount DECIMAL(12,2) NOT NULL,
	paytype TINYINT UNSIGNED NOT NULL,
	payinfo TEXT,
	batchnum VARCHAR(20),
  status TINYINT UNSIGNED NOT NULL,
	INDEX(uid_adv),
	INDEX(regtime),
	INDEX(amount),
	INDEX(batchnum(5)),
	INDEX(status),
  PRIMARY KEY  (pid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//paytype - тип платежа Credit card(1)
//status - 1 оплата завершена

//Таблица с временной информацией для платежей от Advertiser-ов (как только он захотел платить)
@mysql_query('drop table '.$db_tables["payments_tmp_adv"]);
mysql_query('
CREATE TABLE '.$db_tables["payments_tmp_adv"].' (
	pid INT UNSIGNED NOT NULL auto_increment,
	uid_adv MEDIUMINT UNSIGNED NOT NULL,
	regtime DATETIME NOT NULL,
	amount DECIMAL(12,2) NOT NULL,
	paytype TINYINT UNSIGNED NOT NULL,
	payinfo TEXT,
	INDEX(uid_adv),
  PRIMARY KEY (pid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//paytype - тип платежа Credit card(1)


//Таблица псохраненных кредитных карт от от Advertiser-ов (уже когда платеж прошел - т.е. отвер от сервера)
@mysql_query('drop table '.$db_tables["payments_adv_stored_cc"]);
mysql_query('
CREATE TABLE '.$db_tables["payments_adv_stored_cc"].' (
	sccid INT UNSIGNED NOT NULL auto_increment,
	uid_adv MEDIUMINT UNSIGNED NOT NULL,
	cc_number VARCHAR(200),
	cc_number_last4 VARCHAR(5),
	cc_expiration_month VARCHAR(2),
	cc_expiration_year VARCHAR(2),
	payinfo TEXT,
	INDEX(uid_adv),
	INDEX(cc_number(10)),
  PRIMARY KEY (sccid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//cc_number - номер карты в зашифрованном виде
//cc_number_last4 последние 4 цифры номера карты
//payinfo - полная информация о платеже

//Таблица запросов платежей от Publisher-ов (при запросе статус false, после обработки - статус true)
@mysql_query('drop table '.$db_tables["payments_pub"]);
mysql_query('
CREATE TABLE '.$db_tables["payments_pub"].' (
	pid INT UNSIGNED NOT NULL auto_increment,
	uid_pub MEDIUMINT UNSIGNED NOT NULL,
	regtime DATETIME NOT NULL,
	paytime DATETIME,
	amount DECIMAL(12,2) NOT NULL,
	paytype TINYINT UNSIGNED NOT NULL,
	payee_account VARCHAR(200),
	payinfo TEXT,
	batchnum VARCHAR(20),
  status TINYINT UNSIGNED NOT NULL,
	INDEX(uid_pub),
	INDEX(regtime),
	INDEX(amount),
	INDEX(batchnum(5)),
	INDEX(status),
  PRIMARY KEY  (pid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//paytype - тип платежа Credit card(1)
//status - 1 оплата завершена (Pending | Processed)
//payee_account - аккаунт куда платить. Если это Credit Catd - номер карты, если PayPal - e-mail.

//Таблица с временной информацией для платежей от Admin к Publisher (как только admin захотел платить publisher-у)
@mysql_query('drop table '.$db_tables["payments_tmp_pub"]);
mysql_query('
CREATE TABLE '.$db_tables["payments_tmp_pub"].' (
	tmp_pid INT UNSIGNED NOT NULL auto_increment,
	pid INT UNSIGNED NOT NULL,
	uid_pub MEDIUMINT UNSIGNED NOT NULL,
	regtime DATETIME NOT NULL,
	amount DECIMAL(12,2) NOT NULL,
	paytype TINYINT UNSIGNED NOT NULL,
	payinfo TEXT,
	INDEX(pid),
	INDEX(uid_pub),
  PRIMARY KEY (tmp_pid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//paytype - тип платежа Credit card(1)



//*********************//
//* T E M P L A T E S *//
//*********************//
//Таблица шаблонов
@mysql_query('drop table '.$db_tables["templates"]);
mysql_query('
CREATE TABLE '.$db_tables["templates"].' (
  template_id SMALLINT UNSIGNED NOT NULL auto_increment,
  title VARCHAR(250) NOT NULL,
  diskname VARCHAR(250) NOT NULL,
  description TEXT NOT NULL,
  caution_level TINYINT UNSIGNED NOT NULL,
  show_type TINYINT UNSIGNED NOT NULL,
  issystem BOOL NOT NULL,
  template_type TINYINT UNSIGNED NOT NULL,
  php_file VARCHAR(250) NOT NULL,
  PRIMARY KEY (template_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//title - логическое имя шаблна
//diskname - дисковое имя шаблна
//caution_level - уровень важности (на сколько критичны изменеия в нем)
//show_type - как показывать: 0 - в textarea, 1 - в визуальном редакторе
//issystem - указатель на то, что это системный шаблон. Его удалить нельзя. Шаблоны, что будет создавать админ в админ-зоне - не системные, их можно будет ему удалить
//template_type - тип шаблона: 0 - примитив (частичный шаблон, к-рый будет подключаться к остальным); 1 - шаблон страницы (основной шаблон страницы)
//php_file - php файл обрабатывающий этот шаблон. Запустив этот php файл будет отдан этот шаблон
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (1,"Homepage","homepage.tpl","Homepage template",3,0,1,1,"index.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (2,"Main Header","main_header.tpl","Main header template",1,0,1,0,"")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (3,"Main Footer","main_footer.tpl","Main footer template",1,0,1,0,"")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (7,"Advanced Search","advanced_searchpage.tpl","Advanced Job Search",3,1,1,1,"advanced_search.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (8,"Advanced Search Form","advanced_searchform.tpl","Advanced Search Form template",1,0,1,0,"")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (9,"Advanced Search Error","advanced_searchpage_error.tpl","Advanced Job Search Error",3,1,1,1,"advanced_search.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (10,"Simple Search Error","simple_searchpage_error.tpl","Simple Job Search Error",3,0,1,1,"index.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (11,"Search results page","searchpage.tpl","Search results page",3,1,1,1,"search.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (12,"Page navigation","navigation.tpl","Page navigation line",4,0,1,0,"")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (13,"Search result item","searchresult_item.tpl","Search result item",4,0,1,0,"")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (14,"Filter column","filter_column.tpl","Search filter column (left column on search result page)",4,0,1,0,"")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (15,"Ads column","ads_column.tpl","Search Ads column (right column on search result page)",4,0,1,0,"")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (16,"Empty Search results page","searchpage_empty.tpl","Empty Search results page",3,1,1,1,"search.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (17,"Empty Jobroll page","jobrollpage_empty.tpl","Empty Jobroll page",4,0,1,0,"adsshowjobs.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (18,"Jobroll page","jobrollpage.tpl","Jobroll page",4,0,1,0,"adsshowjobs.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (19,"IP blocked page","ipblocked.tpl","IP blocked page",3,1,1,1,"ipfw.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (20,"Advertisers page","advertisers.tpl","Advertisers page",3,1,1,1,"advertisers.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (21,"Publishers page","publishers.tpl","Publishers page",3,1,1,1,"publishers.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (22,"My Jobs page","myjobspage.tpl","My Jobs page",4,0,1,0,"myjobs.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (23,"My Jobs menu","myjobs_menu.tpl","My Jobs left menu",4,0,1,0,"")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (24,"My Jobs saved items","myjobs_item.tpl","My Jobs saved job items list",4,0,1,0,"")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (25,"Browse Jobs page","browse_jobs.tpl","Browse Jobs page",3,1,1,1,"browse_jobs.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (26,"Browse Keyword list","browse_keword.tpl","Browse keyword list template",4,0,1,0,"")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (27,"Select Country page","select_country.tpl","Select Country page template",4,0,1,0,"select_country.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (28,"My Area page","myarea.tpl","My Area page",4,0,1,0,"myarea.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (29,"One job page","onejob.tpl","One job page",3,1,1,1,"onejob.php")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["templates"].' VALUES (30,"One job item","onejob_item.tpl","One job item",4,0,1,0,"")
') or die(__LINE__.mysql_error());



//Таблица переменных для шаблонов шаблонов
@mysql_query('drop table '.$db_tables["template_values"]);
mysql_query('
CREATE TABLE '.$db_tables["template_values"].' (
  template_id SMALLINT UNSIGNED NOT NULL,
  template_vid SMALLINT UNSIGNED NOT NULL,
	INDEX(template_id),
	INDEX(template_vid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//template_vid - из языкового файла





//*****************************//
//* S E A R C H   E N G I N E *//
//*****************************//
//Таблица источников (тех сайтов откуда берем работу)
@mysql_query('drop table '.$db_tables["sites_feed_list"]);
mysql_query('
CREATE TABLE '.$db_tables["sites_feed_list"].' (
  feed_id SMALLINT UNSIGNED NOT NULL auto_increment,
  feed_code VARCHAR(100) NOT NULL,
  title VARCHAR(250) NOT NULL,
  description TEXT NOT NULL,
  url VARCHAR(253) NOT NULL,
	registered DATETIME NOT NULL,
	refresh_rate BIGINT UNSIGNED NOT NULL,
	max_recursion_depths SMALLINT UNSIGNED NOT NULL,
  isactive BOOL NOT NULL,
  isparsednow BOOL NOT NULL,
	startparsed DATETIME NOT NULL,
	feed_type VARCHAR(15) NOT NULL DEFAULT "common",
	job_ads_id MEDIUMINT UNSIGNED NOT NULL,
	feed_format VARCHAR(15) NOT NULL DEFAULT "xml1",
	UNIQUE(feed_code),
	INDEX(isactive),
	INDEX(isparsednow),
	INDEX(startparsed),
  PRIMARY KEY (feed_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());

//feed_code - уникальное слово - код данного источника (придумываю сам). При сборе данных будет вызвана функция "feed_parse_<feed_code>"
//registered - дата регистрациии данного источника
//isactive - ативен ли этот источник, т.е. собирать ли с него информацию
//isparsednow - собирает ли другой поток в данный момент информацию?
//startparsed - последнее время старта парсинга
//refresh_rate - частота обновления данных с данного ресурса (как часто опрашивать и парсить данный ресурс) в секундах
//max_recursion_depths - глубина просмотра - сколько страниц вглубь просматривать при поиске вакансий
//feed_type - тип осточника: common или advertiser 
//uid_adv - advertiser id для feed_type == advertiser
//feed_format - формат источника: xml1 или xml2 или html1 или html2
mysql_query('
INSERT INTO '.$db_tables["sites_feed_list"].' VALUES (NULL,"reviewjournal","Review-Journal","Review-Journal. Jobs Today online.","http://www.reviewjournal.com/employment/",NOW(),86400,10,1,0,"0000-00-00","common",0,"html")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["sites_feed_list"].' VALUES (NULL,"jobvertise","Jobvertise","Jobvertise Power Search","http://www.jobvertise.com/search/",NOW(),86400,10,1,0,"0000-00-00","common",0,"html")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["sites_feed_list"].' VALUES (NULL,"renogazettejournal","Reno Gazette Journal","Reno Jobs from rgj.com, Reno Gazette-Journal and CareerBuilder.com","http://rgj.gannettonline.com/careerbuilder/",NOW(),43200,10,1,0,"0000-00-00","common",0,"html")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["sites_feed_list"].' VALUES (NULL,"jobnugget","JobNugget.com","JobNugget.com Jobs - The Largest Free Job Posting and Job Search Employment Career Site Online.","http://www.jobnugget.com/",NOW(),43200,10,0,0,"0000-00-00","common",0,"html")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["sites_feed_list"].' VALUES (NULL,"indeed","Indeed.com","Job Search by Indeed. One search. All  jobs.","http://www.indeed.com/",NOW(),43200,10,1,0,"0000-00-00","common",0,"html")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["sites_feed_list"].' VALUES (NULL,"thejobspider","JobSpider","JobSpider - job search engine, free job posting, free resume posting.","http://www.thejobspider.com/",NOW(),86400,10,1,0,"0000-00-00","common",0,"html")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["sites_feed_list"].' VALUES (NULL,"jobbind","Jobbind.com","Jobbind.com - job search engine.","http://www.jobbind.com/country/",NOW(),86400,10,1,0,"0000-00-00","common",0,"html")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["sites_feed_list"].' VALUES (NULL,"simplyhired","SimplyHired","SimplyHired.Com Job Search Made Simpl.","http://www.simplyhired.com/",NOW(),43200,10,1,0,"0000-00-00","common",0,"html")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["sites_feed_list"].' VALUES (NULL,"indeed_xml","Indeed.com XML","Job Search by Indeed RSS. One search. All  jobs.","http://www.indeed.com/",NOW(),43200,10,1,0,"0000-00-00","common",0,"xml1")
') or die(__LINE__.mysql_error());
mysql_query('
INSERT INTO '.$db_tables["sites_feed_list"].' VALUES (NULL,"simplyhired_xml","SimplyHired XML ","SimplyHired.Com RSS Job Search Made Simpl.","http://www.simplyhired.com/",NOW(),43200,10,1,0,"0000-00-00","common",0,"xml1")
') or die(__LINE__.mysql_error());

//Лог крона по поиску работы
@mysql_query('drop table '.$db_tables["sites_feed_log"]);
mysql_query('
CREATE TABLE '.$db_tables["sites_feed_log"].' (
  log_id BIGINT UNSIGNED NOT NULL auto_increment,
	actiontime DATETIME NOT NULL,
  action SMALLINT UNSIGNED NOT NULL,
  status BOOL NOT NULL,
  detail_level TINYINT UNSIGNED NOT NULL,
  short_message TEXT NOT NULL,
  long_message TEXT NOT NULL,
	INDEX(actiontime),
	INDEX(action),
	INDEX(status),
	INDEX(detail_level),
  PRIMARY KEY (log_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//actiontime - время записи
//action - событие из массива событий
//status - статус: 0 или 1
//short_message - короткое текстовое сообщение
//long_message - длинное текстовое сообщение
//detail_level - уровень детализации сообщения: 0-3, 0 - наиболее общее, 3 - наиболее конкретное

//Список e-mail-ов для рассылки лога крона по поиску работы при наличии ошибок
@mysql_query('drop table '.$db_tables["sites_feed_alert_emials"]);
mysql_query('
CREATE TABLE '.$db_tables["sites_feed_alert_emials"].' (
  email_id BIGINT UNSIGNED NOT NULL auto_increment,
  email VARCHAR(250) NOT NULL,
	INDEX(email(3)),
  PRIMARY KEY (email_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());



//Таблица данных (содержит саму информации про работу)
@mysql_query('drop table '.$db_tables["data_list"]);
mysql_query('
CREATE TABLE '.$db_tables["data_list"].' (
  data_id BIGINT UNSIGNED NOT NULL auto_increment,
  feed_id SMALLINT UNSIGNED NOT NULL,
  title VARCHAR(100) NOT NULL,
	company_name VARCHAR(100) NOT NULL,
  locId MEDIUMINT NOT NULL,
  description VARCHAR(160) NOT NULL,
  url TEXT NOT NULL,
	cat_id SMALLINT(5) UNSIGNED NOT NULL,
	job_type VARCHAR(20) NOT NULL,
	site_type VARCHAR(20) NOT NULL,
	isstaffing_agencies BOOL NOT NULL,
	salary DECIMAL(12,2) NOT NULL default "0",
	registered DATETIME NOT NULL,
  source varchar(100) NOT NULL default "",
  dateinsert datetime NOT NULL default "0000-00-00 00:00:00",
  country char(2) NOT NULL default "",
  region char(40) NOT NULL default "",
  city char(40) NOT NULL default "",
	INDEX(feed_id),
	FULLTEXT(title,company_name,description),
	FULLTEXT(title),
	FULLTEXT(company_name),
	INDEX(locId),
	INDEX(cat_id),
	INDEX(job_type(3)),
	INDEX(site_type(3)),
	INDEX(isstaffing_agencies),
	INDEX(salary),
	INDEX(registered),
	INDEX(dateinsert),
  INDEX(country),
  INDEX(region),
  INDEX(city),
  PRIMARY KEY (data_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//feed_id - от какого источника эта работа
//title - заголовок: Programmer
//company_name - компания: GENERAL DYNAMICS LAND SYSTEMS
//locId - положение: 	из таблицы городов
//url - URL к данному ресурсу.
//cat_id - категория работы
//job_type - тип работы: 1)Full-time, 2)Part-time 3)Contract, 4)Internship, 5)Temporary
//site_type - тип сайта: 1)Job boards only, 2)Employer web sites only
//isstaffing_agencies - является ли этот сайт isstaffing agencies
//salary - зарплатат
//registered - дата и время внесения данных в базу
//- - - -
//country | location info for location not found in DB (if allowed)
//region  | means we cannot find this location (city) in DB but
//city    | user want to see this job with this city name. Country is mandatory


//Таблица данных (содержит саму информации про удаленную работу)
@mysql_query('drop table '.$db_tables["data_list_deleted"]);
mysql_query('
CREATE TABLE '.$db_tables["data_list_deleted"].' (
  data_id BIGINT UNSIGNED NOT NULL auto_increment,
  feed_id SMALLINT UNSIGNED NOT NULL,
  title VARCHAR(100) NOT NULL,
	company_name VARCHAR(100) NOT NULL,
  locId MEDIUMINT NOT NULL,
  description VARCHAR(160) NOT NULL,
  url TEXT NOT NULL,
	cat_id SMALLINT(5) UNSIGNED NOT NULL,
	job_type VARCHAR(20) NOT NULL,
	site_type VARCHAR(20) NOT NULL,
	isstaffing_agencies BOOL NOT NULL,
	salary DECIMAL(12,2) NOT NULL default "0",
	registered DATETIME NOT NULL,
  source varchar(100) NOT NULL default "",
  dateinsert datetime NOT NULL default "0000-00-00 00:00:00",
  country char(2) NOT NULL default "",
  region char(40) NOT NULL default "",
  city char(40) NOT NULL default "",
	INDEX(feed_id),
	FULLTEXT(title,company_name,description),
	FULLTEXT(title),
	FULLTEXT(company_name),
	INDEX(locId),
	INDEX(cat_id),
	INDEX(job_type(3)),
	INDEX(site_type(3)),
	INDEX(isstaffing_agencies),
	INDEX(salary),
	INDEX(registered),
  INDEX(country),
  INDEX(region),
  INDEX(city),
  PRIMARY KEY (data_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//feed_id - от какого источника эта работа
//title - заголовок: Programmer
//company_name - компания: GENERAL DYNAMICS LAND SYSTEMS
//locId - положение: 	из таблицы городов
//url - URL к данному ресурсу.
//cat_id - категория работы
//job_type - тип работы: 1)Full-time, 2)Part-time 3)Contract, 4)Internship, 5)Temporary
//site_type - тип сайта: 1)Job boards only, 2)Employer web sites only
//isstaffing_agencies - является ли этот сайт isstaffing agencies
//salary - зарплатат
//registered - дата и время внесения данных в базу
//- - - -
//country | location info for location not found in DB (if allowed)
//region  | means we cannot find this location (city) in DB but
//city    | user want to see this job with this city name. Country is mandatory



//Таблица содержащая статистику по добавленным данным
@mysql_query('drop table '.$db_tables["data_list_stats"]);
mysql_query('
CREATE TABLE '.$db_tables["data_list_stats"].' (
  stats_data_id INT UNSIGNED NOT NULL auto_increment,
  feed_id SMALLINT UNSIGNED NOT NULL,
  added_count INT UNSIGNED NOT NULL,
	registered DATETIME NOT NULL,
	INDEX(feed_id),
	INDEX(registered),
  PRIMARY KEY (stats_data_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//feed_id - от какого источника эта работа
//registered - дата и время внесения данных в базу


//Таблица данных про рабу с сайта рекламодателя. Это рекламные слова Jobs ADs
//Структура  такая же как в data_list, но поле feed_id ссылается на job_ads_id в таблице "job_ads"!
@mysql_query('drop table '.$db_tables["data_list_advertiser"]);
mysql_query('
CREATE TABLE '.$db_tables["data_list_advertiser"].' (
  data_id BIGINT UNSIGNED NOT NULL auto_increment,
  feed_id MEDIUMINT UNSIGNED NOT NULL,
  title VARCHAR(100) NOT NULL,
	company_name VARCHAR(100) NOT NULL,
  locId MEDIUMINT NOT NULL,
  description VARCHAR(160) NOT NULL,
  url TEXT NOT NULL,
	cat_id SMALLINT(5) UNSIGNED NOT NULL,
	job_type VARCHAR(20) NOT NULL,
	site_type VARCHAR(20) NOT NULL,
	isstaffing_agencies BOOL NOT NULL,
	salary DECIMAL(12,2) NOT NULL default "0",
	registered DATETIME NOT NULL,
  dateinsert datetime NOT NULL default "0000-00-00 00:00:00",
  country char(2) NOT NULL default "",
  region char(40) NOT NULL default "",
  city char(40) NOT NULL default "",
	INDEX(feed_id),
	FULLTEXT(title,company_name,description),
	FULLTEXT(title),
	FULLTEXT(company_name),
	INDEX(locId),
	INDEX(cat_id),
	INDEX(job_type(3)),
	INDEX(site_type(3)),
	INDEX(isstaffing_agencies),
	INDEX(salary),
	INDEX(registered),
	INDEX(dateinsert),
  INDEX(country),
  INDEX(region),
  INDEX(city),
  PRIMARY KEY (data_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//feed_id - от какого источника эта работа! Это ссылка на job_ads_id в таблице "job_ads"!
//title - заголовок: Programmer
//company_name - компания: GENERAL DYNAMICS LAND SYSTEMS
//locId - положение: 	из таблицы городов
//url - URL к данному ресурсу.
//cat_id - категория работы
//job_type - тип работы: 1)Full-time, 2)Part-time 3)Contract, 4)Internship, 5)Temporary
//site_type - тип сайта: 1)Job boards only, 2)Employer web sites only
//isstaffing_agencies - является ли этот сайт isstaffing agencies
//salary - зарплатат
//registered - дата и время внесения данных в базу
//- - - -
//country | location info for location not found in DB (if allowed)
//region  | means we cannot find this location (city) in DB but
//city    | user want to see this job with this city name. Country is mandatory


@mysql_query('drop table '.$db_tables["xml_feeds_configuration"]);
mysql_query('
CREATE TABLE '.$db_tables["xml_feeds_configuration"].' (
  config_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  config_name VARCHAR(100) NOT NULL,
  config_url TEXT NOT NULL,
  title_status SMALLINT UNSIGNED NOT NULL,
  title_field VARCHAR(200) NOT NULL,
  title_run_phpcode_before BOOL NOT NULL,
  title_phpcode_before MEDIUMINT UNSIGNED NULL,
  title_phpcode TEXT NULL,
  title_run_phpcode_after BOOL NOT NULL,
  title_phpcode_after MEDIUMINT UNSIGNED NULL,
  company_name_status SMALLINT UNSIGNED NOT NULL,
  company_name_field VARCHAR(200) NOT NULL,
  company_name_run_phpcode_before BOOL NOT NULL,
  company_name_phpcode_before MEDIUMINT UNSIGNED NULL,
  company_name_phpcode TEXT NULL,
  company_name_run_phpcode_after BOOL NOT NULL,
  company_name_phpcode_after MEDIUMINT UNSIGNED NULL,
  locId_status SMALLINT UNSIGNED NOT NULL,
  locId_field VARCHAR(200) NOT NULL,
  locId_run_phpcode_before BOOL NOT NULL,
  locId_phpcode_before MEDIUMINT UNSIGNED NULL,
  locId_phpcode TEXT NULL,
  locId_run_phpcode_after BOOL NOT NULL,
  locId_phpcode_after MEDIUMINT UNSIGNED NULL,
  description_status SMALLINT UNSIGNED NOT NULL,
  description_field VARCHAR(200) NOT NULL,
  description_run_phpcode_before BOOL NOT NULL,
  description_phpcode_before MEDIUMINT UNSIGNED NULL,
  description_phpcode TEXT NULL,
  description_run_phpcode_after BOOL NOT NULL,
  description_phpcode_after MEDIUMINT UNSIGNED NULL,
  url_status SMALLINT UNSIGNED NOT NULL,
  url_field VARCHAR(200) NOT NULL,
  url_run_phpcode_before BOOL NOT NULL,
  url_phpcode_before MEDIUMINT UNSIGNED NULL,
  url_phpcode TEXT NULL,
  url_run_phpcode_after BOOL NOT NULL,
  url_phpcode_after MEDIUMINT UNSIGNED NULL,
  job_type_status SMALLINT UNSIGNED NOT NULL,
  job_type_field VARCHAR(200) NOT NULL,
  job_type_run_phpcode_before BOOL NOT NULL,
  job_type_phpcode_before MEDIUMINT UNSIGNED NULL,
  job_type_phpcode TEXT NULL,
  job_type_run_phpcode_after BOOL NOT NULL,
  job_type_phpcode_after MEDIUMINT UNSIGNED NULL,
  site_type_status SMALLINT UNSIGNED NOT NULL,
  site_type_field VARCHAR(200) NOT NULL,
  site_type_run_phpcode_before BOOL NOT NULL,
  site_type_phpcode_before MEDIUMINT UNSIGNED NULL,
  site_type_phpcode TEXT NULL,
  site_type_run_phpcode_after BOOL NOT NULL,
  site_type_phpcode_after MEDIUMINT UNSIGNED NULL,
  isstaffing_agencies_status SMALLINT UNSIGNED NOT NULL,
  isstaffing_agencies_field VARCHAR(200) NOT NULL,
  isstaffing_agencies_run_phpcode_before BOOL NOT NULL,
  isstaffing_agencies_phpcode_before MEDIUMINT UNSIGNED NULL,
  isstaffing_agencies_phpcode TEXT NULL,
  isstaffing_agencies_run_phpcode_after BOOL NOT NULL,
  isstaffing_agencies_phpcode_after MEDIUMINT UNSIGNED NULL,
  salary_status SMALLINT UNSIGNED NOT NULL,
  salary_field VARCHAR(200) NOT NULL,
  salary_run_phpcode_before BOOL NOT NULL,
  salary_phpcode_before MEDIUMINT UNSIGNED NULL,
  salary_phpcode TEXT NULL,
  salary_run_phpcode_after BOOL NOT NULL,
  salary_phpcode_after MEDIUMINT UNSIGNED NULL,
  category_status SMALLINT UNSIGNED NOT NULL,
  category_field VARCHAR(200) NOT NULL,
  category_run_phpcode_before BOOL NOT NULL,
  category_phpcode_before MEDIUMINT UNSIGNED NULL,
  category_phpcode TEXT NULL,
  category_run_phpcode_after BOOL NOT NULL,
  category_phpcode_after MEDIUMINT UNSIGNED NULL,
	registered DATETIME NOT NULL,
	feed_format VARCHAR(15) NOT NULL DEFAULT "xml1",
  PRIMARY KEY (config_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//title_status - 1 - Return XML field; 2 - Return PHP parsed XML field
//title_run_phpcode_before - запускать ли php код перед - 0;1
//title_phpcode_before ссылка на талбл. common_php_code
//title_phpcode - php код для данного поля - PHP parsed data
//title_run_phpcode_after - запускать ли php код после - 0;1
//title_phpcode_after ссылка на талбл. common_php_code
//feed_format: "xml1","xml2"

@mysql_query('drop table '.$db_tables["xml_feeds_data"]);
mysql_query('
CREATE TABLE '.$db_tables["xml_feeds_data"].' (
  fdata_id BIGINT UNSIGNED NOT NULL auto_increment,
  feed_id MEDIUMINT UNSIGNED NOT NULL,
	cat_id SMALLINT(5) UNSIGNED NOT NULL,
	url TEXT NOT NULL,
	config_id MEDIUMINT UNSIGNED NOT NULL,
	INDEX(feed_id),
	INDEX(cat_id),
	INDEX(config_id),
  PRIMARY KEY (fdata_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//feed_id - источник
//cat_id - id категории
//url - сбора данных
//config_id - конфигурация

@mysql_query('drop table '.$db_tables["xml2_feeds_data"]);
mysql_query('
CREATE TABLE '.$db_tables["xml2_feeds_data"].' (
  fdata_id BIGINT UNSIGNED NOT NULL auto_increment,
  feed_id MEDIUMINT UNSIGNED NOT NULL,
	url TEXT NOT NULL,
	config_id MEDIUMINT UNSIGNED NOT NULL,
	INDEX(feed_id),
	INDEX(config_id),
  PRIMARY KEY (fdata_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//feed_id - источник
//url - сбора данных
//config_id - конфигурация

@mysql_query('drop table '.$db_tables["xml2_feeds_category_keywords"]);
mysql_query('
CREATE TABLE '.$db_tables["xml2_feeds_category_keywords"].' (
  fck_id BIGINT UNSIGNED NOT NULL auto_increment,
	config_id MEDIUMINT UNSIGNED NOT NULL,
	cat_id SMALLINT(5) UNSIGNED NOT NULL,
	keywords TEXT,
	INDEX(config_id),
	INDEX(cat_id),
  PRIMARY KEY (fck_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//config_id - "xml_feeds_configuration"
//cat_id - id категории
//keywords - ключ слова для данной категории

/*Temporary table for "check parsing" button. The same structure as xml_feeds_data*/
@mysql_query('drop table '.$db_tables["xml_feeds_data_temp"]);
mysql_query('
CREATE TABLE '.$db_tables["xml_feeds_data_temp"].' (
  fdata_id BIGINT UNSIGNED NOT NULL auto_increment,
  feed_id MEDIUMINT UNSIGNED NOT NULL,
	cat_id SMALLINT(5) UNSIGNED NOT NULL,
	url TEXT NOT NULL,
	config_id MEDIUMINT UNSIGNED NOT NULL,
	INDEX(feed_id),
	INDEX(cat_id),
	INDEX(config_id),
  PRIMARY KEY (fdata_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//feed_id - источник
//cat_id - id категории
//url - сбора данных
//config_id - конфигурация

/*Temporary table for "check parsing" button. The same structure as xml2_feeds_data*/
@mysql_query('drop table '.$db_tables["xml2_feeds_data_temp"]);
mysql_query('
CREATE TABLE '.$db_tables["xml2_feeds_data_temp"].' (
  fdata_id BIGINT UNSIGNED NOT NULL auto_increment,
  feed_id MEDIUMINT UNSIGNED NOT NULL,
	url TEXT NOT NULL,
	config_id MEDIUMINT UNSIGNED NOT NULL,
	INDEX(feed_id),
	INDEX(config_id),
  PRIMARY KEY (fdata_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//feed_id - источник
//url - сбора данных
//config_id - конфигурация

@mysql_query('drop table '.$db_tables["html_feeds_configuration"]);
mysql_query('
CREATE TABLE '.$db_tables["html_feeds_configuration"].' (
  config_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  config_name VARCHAR(100) NOT NULL,
  config_url TEXT NOT NULL,
	html_parse_regular_expression TEXT NOT NULL,
  title_status SMALLINT UNSIGNED NOT NULL,
  title_field VARCHAR(200) NOT NULL,
  title_run_phpcode_before BOOL NOT NULL,
  title_phpcode_before MEDIUMINT UNSIGNED NULL,
  title_phpcode TEXT NULL,
  title_run_phpcode_after BOOL NOT NULL,
  title_phpcode_after MEDIUMINT UNSIGNED NULL,
  company_name_status SMALLINT UNSIGNED NOT NULL,
  company_name_field VARCHAR(200) NOT NULL,
  company_name_run_phpcode_before BOOL NOT NULL,
  company_name_phpcode_before MEDIUMINT UNSIGNED NULL,
  company_name_phpcode TEXT NULL,
  company_name_run_phpcode_after BOOL NOT NULL,
  company_name_phpcode_after MEDIUMINT UNSIGNED NULL,
  locId_status SMALLINT UNSIGNED NOT NULL,
  locId_field VARCHAR(200) NOT NULL,
  locId_run_phpcode_before BOOL NOT NULL,
  locId_phpcode_before MEDIUMINT UNSIGNED NULL,
  locId_phpcode TEXT NULL,
  locId_run_phpcode_after BOOL NOT NULL,
  locId_phpcode_after MEDIUMINT UNSIGNED NULL,
  description_status SMALLINT UNSIGNED NOT NULL,
  description_field VARCHAR(200) NOT NULL,
  description_run_phpcode_before BOOL NOT NULL,
  description_phpcode_before MEDIUMINT UNSIGNED NULL,
  description_phpcode TEXT NULL,
  description_run_phpcode_after BOOL NOT NULL,
  description_phpcode_after MEDIUMINT UNSIGNED NULL,
  url_status SMALLINT UNSIGNED NOT NULL,
  url_field VARCHAR(200) NOT NULL,
  url_run_phpcode_before BOOL NOT NULL,
  url_phpcode_before MEDIUMINT UNSIGNED NULL,
  url_phpcode TEXT NULL,
  url_run_phpcode_after BOOL NOT NULL,
  url_phpcode_after MEDIUMINT UNSIGNED NULL,
  job_type_status SMALLINT UNSIGNED NOT NULL,
  job_type_field VARCHAR(200) NOT NULL,
  job_type_run_phpcode_before BOOL NOT NULL,
  job_type_phpcode_before MEDIUMINT UNSIGNED NULL,
  job_type_phpcode TEXT NULL,
  job_type_run_phpcode_after BOOL NOT NULL,
  job_type_phpcode_after MEDIUMINT UNSIGNED NULL,
  site_type_status SMALLINT UNSIGNED NOT NULL,
  site_type_field VARCHAR(200) NOT NULL,
  site_type_run_phpcode_before BOOL NOT NULL,
  site_type_phpcode_before MEDIUMINT UNSIGNED NULL,
  site_type_phpcode TEXT NULL,
  site_type_run_phpcode_after BOOL NOT NULL,
  site_type_phpcode_after MEDIUMINT UNSIGNED NULL,
  isstaffing_agencies_status SMALLINT UNSIGNED NOT NULL,
  isstaffing_agencies_field VARCHAR(200) NOT NULL,
  isstaffing_agencies_run_phpcode_before BOOL NOT NULL,
  isstaffing_agencies_phpcode_before MEDIUMINT UNSIGNED NULL,
  isstaffing_agencies_phpcode TEXT NULL,
  isstaffing_agencies_run_phpcode_after BOOL NOT NULL,
  isstaffing_agencies_phpcode_after MEDIUMINT UNSIGNED NULL,
  salary_status SMALLINT UNSIGNED NOT NULL,
  salary_field VARCHAR(200) NOT NULL,
  salary_run_phpcode_before BOOL NOT NULL,
  salary_phpcode_before MEDIUMINT UNSIGNED NULL,
  salary_phpcode TEXT NULL,
  salary_run_phpcode_after BOOL NOT NULL,
  salary_phpcode_after MEDIUMINT UNSIGNED NULL,
  nextpage_status SMALLINT UNSIGNED NOT NULL,
  nextpage_field VARCHAR(200) NOT NULL,
  nextpage_run_phpcode_before BOOL NOT NULL,
  nextpage_phpcode_before MEDIUMINT UNSIGNED NULL,
  nextpage_phpcode TEXT NULL,
  nextpage_run_phpcode_after BOOL NOT NULL,
  nextpage_phpcode_after MEDIUMINT UNSIGNED NULL,
	registered DATETIME NOT NULL,
	feed_format VARCHAR(15) NOT NULL DEFAULT "html1",
  PRIMARY KEY (config_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//title_status - 1 - Return HTML field; 2 - Return PHP parsed HTML field
//title_run_phpcode_before - запускать ли php код перед - 0;1
//title_phpcode_before ссылка на талбл. common_php_code
//title_phpcode - php код для данного поля - PHP parsed data
//title_run_phpcode_after - запускать ли php код после - 0;1
//title_phpcode_after ссылка на талбл. common_php_code
//feed_format: "xml1","xml2"

@mysql_query('drop table '.$db_tables["html_feeds_data"]);
mysql_query('
CREATE TABLE '.$db_tables["html_feeds_data"].' (
  fdata_id BIGINT UNSIGNED NOT NULL auto_increment,
  feed_id MEDIUMINT UNSIGNED NOT NULL,
	cat_id SMALLINT(5) UNSIGNED NOT NULL,
	url TEXT NOT NULL,
	config_id MEDIUMINT UNSIGNED NOT NULL,
	INDEX(feed_id),
	INDEX(cat_id),
	INDEX(config_id),
  PRIMARY KEY (fdata_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//feed_id - источник
//cat_id - id категории
//url - сбора данных
//config_id - конфигурация

/*Temporary table for "check parsing" button. The same structure as html_feeds_data*/
@mysql_query('drop table '.$db_tables["html_feeds_data_temp"]);
mysql_query('
CREATE TABLE '.$db_tables["html_feeds_data_temp"].' (
  fdata_id BIGINT UNSIGNED NOT NULL auto_increment,
  feed_id MEDIUMINT UNSIGNED NOT NULL,
	cat_id SMALLINT(5) UNSIGNED NOT NULL,
	url TEXT NOT NULL,
	config_id MEDIUMINT UNSIGNED NOT NULL,
	INDEX(feed_id),
	INDEX(cat_id),
	INDEX(config_id),
  PRIMARY KEY (fdata_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//feed_id - источник
//cat_id - id категории
//url - сбора данных
//config_id - конфигурация

@mysql_query('drop table '.$db_tables["common_php_code"]);
mysql_query('
CREATE TABLE '.$db_tables["common_php_code"].' (
  code_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  code_name VARCHAR(100) NOT NULL,
  phpcode TEXT NULL,
  PRIMARY KEY (code_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());



//******************************//
//* B R O W S E  K E Y W O R D *//
//******************************//

//Browse keyword
//Таблица для страницы Browse keyword
@mysql_query('drop table '.$db_tables["browse_keyword"]);
mysql_query('
CREATE TABLE '.$db_tables["browse_keyword"].' (
	kid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	type TINYINT UNSIGNED NOT NULL,
	level INT(10) UNSIGNED NOT NULL,
	parent INT(10) UNSIGNED NOT NULL,
	path VARCHAR(255) NOT NULL,
	link VARCHAR(255) NOT NULL,
  INDEX (path(10)),
  INDEX (type),
  INDEX (level),
  PRIMARY KEY (kid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//type - тип (0 - root note[верхний элемент блока], 1 - node[секция], 2 - link[ссылка на поиск слова])
//link - ссылка секци или слва или имя верхенго блока

//Browse keyword - temp
//Таблица (временная) для страницы Browse keyword. Используется для хранения данных во время постороения Browse keyword. Потом данные перебрасываются в "browse_keyword"
@mysql_query('drop table '.$db_tables["browse_keyword_temp"]);
mysql_query('
CREATE TABLE '.$db_tables["browse_keyword_temp"].' (
	kid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	type TINYINT UNSIGNED NOT NULL,
	level INT(10) UNSIGNED NOT NULL,
	parent INT(10) UNSIGNED NOT NULL,
	path VARCHAR(255) NOT NULL,
	link VARCHAR(255) NOT NULL,
  INDEX (path(10)),
  INDEX (type),
  INDEX (level),
  PRIMARY KEY (kid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//type - тип (0 - root note[верхний элемент блока], 1 - node[секция], 2 - link[ссылка на поиск слова])
//link - ссылка секци или слва или имя верхенго блока

//Browse keyword: Most Popular
//Таблица для Most Popular слов для страницы Browse keyword
@mysql_query('drop table '.$db_tables["browse_keyword_most_popular"]);
mysql_query('
CREATE TABLE '.$db_tables["browse_keyword_most_popular"].' (
	kid BIGINT UNSIGNED NOT NULL,
	keyword VARCHAR(100) NOT NULL,
  INDEX (kid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//kid - ид из таблицы browse_keyword

//Browse keyword: Most Popular - temp
//Таблица для Most Popular слов для страницы Browse keyword - Временная
@mysql_query('drop table '.$db_tables["browse_keyword_most_popular_temp"]);
mysql_query('
CREATE TABLE '.$db_tables["browse_keyword_most_popular_temp"].' (
	kid BIGINT UNSIGNED NOT NULL,
	keyword VARCHAR(100) NOT NULL,
  INDEX (kid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//kid - ид из таблицы browse_keyword_temp

?>
</body>
<center><h1>Installation complite.</h1>
<h3><a href="index.html">Back to install page</h3></center>
</html>