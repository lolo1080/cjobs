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
//isconfirmed - ���⢥न� �� ����� ��� �����
//isenable - ��室���� � ࠡ�� ��� �������஢��
//isdeleted - ���짮��⥫� 㤠���

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
//isconfirmed - ���⢥न� �� ����� ��� �����
//isenable - ��室���� � ࠡ�� ��� �������஢��
//isdeleted - ���짮��⥫� 㤠���

//������ ������
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
//Publisher channels - ������ ��� Jobroll. ��������� � ���� "Create a Jobroll" ���-�����
//channel_id - id ������
//uid_pub - ���-�����, ���஬� �ਭ������� �����
//name - ��� ������

//������ ���⢥ত���� ��� e-mail-�� (�� ॣ����樨 ���짮��⥫��)
@mysql_query('drop table '.$db_tables["users_confirm_email"]);
mysql_query('
CREATE TABLE '.$db_tables["users_confirm_email"].' (
  uceid MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  confirm_id VARCHAR(33) NOT NULL default "",
  confirm_email VARCHAR(80) NOT NULL default "",
  INDEX (confirm_id(5)),
  PRIMARY KEY (uceid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//users_confirm_email - ���⢥ত���� e-mail ���짮�����

//������ ������ �� ��� ������ (XML Feed)
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

//������ job alert-��. ���짮��⥫� ����� ᮧ���� ��᪮�쪮 job alert-��
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
//job_alert - ��ப� � ���� XML � ��ࠬ��ࠬ� (��� Advanced Job Search �ଠ)
//senddate - ��� ��ࠢ�� � ��᫥���� ࠧ
//deliver - 1 (����� ����), 7 - ࠧ � ������

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
//�� Keyword ADs
//ad_id - id ��������. �������� ᮦ��ন� ᫮��
//status - ����� ������� (1=>active | 0=>disable)

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
//kads_id - id ᫮��
//ad_id - id �������� � ���ன �ਭ������� ᫮��
//ad_status - ����� �������� � ���ன �ਭ������� ᫮��. ��������� ��� ᪮��� ����⪠ �ᥣ�� ࠢ�� status �� ⠡�. "ads"
//kads_status - ����� ᫮�� (1=>active | 0=>disable)
//soptions - ���� ���᪠:
//		1: broad - broad match (SQL: "like")
//		2: exact - exact match (SQL: "=")
//		3: phrase - phrase match (SQL: "=", �� ��� �ࠧ�)
//		4: negative - negative match (SQL: "<>")
//keyword - ᫮�� ��� �ࠧ�

//�� Jobs ADs
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
//destination_url - ᠩ�� � ���ண� ���� ᯨ᮪ ࠡ��
//status - ����� ������� (1=>active | 0=>disable)



//*********************//
//* S T A T I S T I C *//
//*********************//

//����⨪� ����⥫��. ��� ⮫쪮 ����⥫� ��饫 �� ��࠭��� ��� ���襫 � ᠩ� ��������
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
//stat_vi ID ����⥫� � ��� IP (���� ip)

//�����᪠ �� ᫮��� ���᪠. ��� ⮫쪮 �ந��襫 ���� �� ᫮�� �� ᠩ� ��� xml
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
//keyword - ᫮�� ��� �ࠧ�, �-஥ �᪠��
//stat_vi - �� �᪠� (��뫪� �� ����⥫�)
//searchtime �६� ���᪠ �⮣� ᫮�� (�� ���������� � ⠡����)
//searchtype - ⨯ ���᪠ 1-html 2-xml
//uid_pub - �� ������ �������� �ந��襫 ����. �᫨ ��� - � ⠬ 0

//�����᪠ �� 㤠�� ᫮��� ���᪠. ��� ⮫쪮 �ந��襫 ���� �� ᫮�� �� ᠩ� ��� xml � ���� १���� ���᪠
//�㦭� ��� ��࠭��� Browse jobs - keyword
@mysql_query('drop table '.$db_tables["stats_search_success_keywords"]);
mysql_query('
CREATE TABLE '.$db_tables["stats_search_success_keywords"].' (
  stat_kid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  keyword VARCHAR(100) NOT NULL default "",
  INDEX (keyword(10)),
  PRIMARY KEY (stat_kid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//keyword - ᫮�� ��� �ࠧ�, �-஥ �᪠��

//�����᪠ �� ������. ��� ⮫쪮 �ந��襫 ���� �� ࠡ�� (�� ����� ���筮� ��� ४�����)
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
//jobid - id ࠡ��� �� ������ �����㫨
//click_type - ⨯ ����� (0 - �� ���筮� ࠡ��; 1 - �� ४������ ࠡ��(������ � �����); 2 - �� ४������ sponsored links(�ࠢ�))
//stat_kid - �� �� ���᪠ �� ⠡�. "stats_search_keywords"



//�����᪠ �� ������� ��� ���������⥫��. ��� ⮫쪮 �ந��襫 ���� �� ᫮�� ���஥ ४������� ४������⥫�
//����� �������� ��᪮�쪮 �������. �� ᯨ᮪ ��, ����� ���� ��������
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
//kads_id - ����� ४������ ᫮�� �ࠡ�⠫�
//ad_id - ����� ४������ �������� �ࠡ�⠫�
//uid_adv - ����� ४������⥫� ᮧ��� ��� �������� � ᫮��
//actiontime - �६� �����
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
//job_ads_id - ����� ४������ �������� �� ࠡ�� � ᠩ� �ࠡ�⠫�
//uid_adv - ����� ४������⥫� ᮧ��� ��� �������� � ࠡ��
//actiontime - �६� �����

//�����᪠ �� �������� ������� ��� ���������⥫��. ��� ⮫쪮 �ந��襫 ���� �� ᫮�� ���஥ ४������� ४������⥫�
//����� �������� ��᪮�쪮 �������. �� ᯨ᮪ ��, ����� ����� ��������. �� ���� �������� �� ��.
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
//kads_id - ����� ४������ ᫮�� �ࠡ�⠫�
//ad_id - ����� ४������ �������� �ࠡ�⠫�
//uid_adv - ����� ४������⥫� ᮧ��� ��� �������� � ᫮��
//page - ��࠭�� �� ���ன ����� �������� �� ��ꢫ����. �� 䠪�, �� ��� �㤥� ��������.
//actiontime - �६� �����
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
//job_ads_id - ����� ४������ �������� �� ࠡ�� � ᠩ� �ࠡ�⠫�
//uid_adv - ����� ४������⥫� ᮧ��� ��� �������� � ᫮��
//page - ��࠭�� �� ���ன ����� �������� �� ��ꢫ����. �� 䠪�, �� ��� �㤥� ��������.
//actiontime - �६� �����


//�����᪠ �� ������ ��� ���������⥫��. ��� ⮫쪮 �ந��襫 ���� �� ������� ४������⥫�
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
//kads_id - ����� ४������ ᫮�� �ࠡ�⠫�
//ad_id - ����� ४������ �������� �ࠡ�⠫�
//uid_adv - ����� ४������⥫� ᮧ��� ��� �������� � ᫮��
//cost - 業� �� ��� ����
//actiontime - �६� �����
//!����砭��. ��᫥ ���᪠ ����� �ࠡ���� ��᪮�쪮 ᫮� ���� � ⮣� �� ४������⥫�. �㤥� ������� ���� �� �� �� ᫮��
//            � �㤥� ��᪮�쪮 ����ᥩ � ⠡��� (��� ������� �� ᫮�)
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
//job_ads_id - ����� ४������ �������� �� ࠡ�� � ᠩ� �ࠡ�⠫�
//uid_adv - ����� ४������⥫� ᮧ��� ��� �������� � ᫮��
//cost - 業� �� ��� ����
//actiontime - �६� �����
//!����砭��. ��᫥ ���᪠ ����� �ࠡ���� ��᪮�쪮 ᫮� ���� � ⮣� �� ४������⥫�. �㤥� ������� ���� �� �� �� ᫮��
//            � �㤥� ��᪮�쪮 ����ᥩ � ⠡��� (��� ������� �� ᫮�)




//�����᪠ �� ������� ��� �������஢. ��� ⮫쪮 �ந��襫 ����� � ��������
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
//uid_adv - ����⥫쭮 ������ �������� �ந���� ��ᬮ��
//channel_id - �����
//actiontime - �६� �����

//�����᪠ �� ������ ��� �������஢. ��� ⮫쪮 �ந��襫 ���� �� ����⥫�, ��襤襣� �� ��������
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
//uid_pub - �������� � ���ண� ��襫 ����⥫�
//channel_id - �����
//stat_click - �� �����, �� ⠡���� "stats_clicks"


//�����᪠ �� ��ࠡ�⪠� ��� �������஢.
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
//uid_pub - ��������, ����� ��ࠡ�⠫ �����
//channel_id - �����
//stat_click - �� �����, �� ⠡���� "stats_clicks" (�� ����� ���� ��ࠡ�⠫)
//amount - ᪮�쪮 ��ࠡ�⠫


//�����᪠ �� IP ���ᠬ, ����� ��竨 ���죨 � ४������⥫� � �६� �⮣� ᮡ���.
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
//IpNum - IP ����, ����� ��祫 ���죨 � ४������⥫�
//uid_adv - ४������⥫� � ���ண� ��竨 ���죨
//amount - �㬬�, ������ ��竨
//actiontime - �६� ����⢨�



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

//������ ���⥦�� �� Advertiser-�� (㦥 ����� ���⥦ ��襫 - �.�. �⢥� �� �ࢥ�)
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
//paytype - ⨯ ���⥦� Credit card(1)
//status - 1 ����� �����襭�

//������ � �६����� ���ଠ樥� ��� ���⥦�� �� Advertiser-�� (��� ⮫쪮 �� ���⥫ ������)
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
//paytype - ⨯ ���⥦� Credit card(1)


//������ ���࠭����� �।���� ���� �� �� Advertiser-�� (㦥 ����� ���⥦ ��襫 - �.�. �⢥� �� �ࢥ�)
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
//cc_number - ����� ����� � ����஢����� ����
//cc_number_last4 ��᫥���� 4 ���� ����� �����
//payinfo - ������ ���ଠ�� � ���⥦�

//������ ����ᮢ ���⥦�� �� Publisher-�� (�� ����� ����� false, ��᫥ ��ࠡ�⪨ - ����� true)
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
//paytype - ⨯ ���⥦� Credit card(1)
//status - 1 ����� �����襭� (Pending | Processed)
//payee_account - ������ �㤠 ������. �᫨ �� Credit Catd - ����� �����, �᫨ PayPal - e-mail.

//������ � �६����� ���ଠ樥� ��� ���⥦�� �� Admin � Publisher (��� ⮫쪮 admin ���⥫ ������ publisher-�)
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
//paytype - ⨯ ���⥦� Credit card(1)



//*********************//
//* T E M P L A T E S *//
//*********************//
//������ 蠡�����
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
//title - �����᪮� ��� 蠡���
//diskname - ��᪮��� ��� 蠡���
//caution_level - �஢��� ������� (�� ᪮�쪮 ����� �������� � ���)
//show_type - ��� �����뢠��: 0 - � textarea, 1 - � ���㠫쭮� ।����
//issystem - 㪠��⥫� �� �, �� �� ��⥬�� 蠡���. ��� 㤠���� �����. �������, �� �㤥� ᮧ������ ����� � �����-���� - �� ��⥬��, �� ����� �㤥� ��� 㤠����
//template_type - ⨯ 蠡����: 0 - �ਬ�⨢ (����� 蠡���, �-�� �㤥� ����������� � ��⠫��); 1 - 蠡��� ��࠭��� (�᭮���� 蠡��� ��࠭���)
//php_file - php 䠩� ��ࠡ��뢠�騩 ��� 蠡���. �����⨢ ��� php 䠩� �㤥� �⤠� ��� 蠡���
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



//������ ��६����� ��� 蠡����� 蠡�����
@mysql_query('drop table '.$db_tables["template_values"]);
mysql_query('
CREATE TABLE '.$db_tables["template_values"].' (
  template_id SMALLINT UNSIGNED NOT NULL,
  template_vid SMALLINT UNSIGNED NOT NULL,
	INDEX(template_id),
	INDEX(template_vid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//template_vid - �� �몮���� 䠩��





//*****************************//
//* S E A R C H   E N G I N E *//
//*****************************//
//������ ���筨��� (�� ᠩ⮢ ��㤠 ��६ ࠡ���)
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

//feed_code - 㭨���쭮� ᫮�� - ��� ������� ���筨�� (�ਤ�뢠� ᠬ). �� ᡮ� ������ �㤥� �맢��� �㭪�� "feed_parse_<feed_code>"
//registered - ��� ॣ����樨� ������� ���筨��
//isactive - �⨢�� �� ��� ���筨�, �.�. ᮡ���� �� � ���� ���ଠ��
//isparsednow - ᮡ�ࠥ� �� ��㣮� ��⮪ � ����� ������ ���ଠ��?
//startparsed - ��᫥���� �६� ���� ���ᨭ��
//refresh_rate - ���� ���������� ������ � ������� ����� (��� ��� ���訢��� � ������ ����� �����) � ᥪ㭤��
//max_recursion_depths - ��㡨�� ��ᬮ�� - ᪮�쪮 ��࠭�� ����� ��ᬠ�ਢ��� �� ���᪥ �����ᨩ
//feed_type - ⨯ ���筨��: common ��� advertiser 
//uid_adv - advertiser id ��� feed_type == advertiser
//feed_format - �ଠ� ���筨��: xml1 ��� xml2 ��� html1 ��� html2
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

//��� �஭� �� ����� ࠡ���
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
//actiontime - �६� �����
//action - ᮡ�⨥ �� ���ᨢ� ᮡ�⨩
//status - �����: 0 ��� 1
//short_message - ���⪮� ⥪�⮢�� ᮮ�饭��
//long_message - ������� ⥪�⮢�� ᮮ�饭��
//detail_level - �஢��� ��⠫���樨 ᮮ�饭��: 0-3, 0 - �������� ��饥, 3 - �������� �����⭮�

//���᮪ e-mail-�� ��� ���뫪� ���� �஭� �� ����� ࠡ��� �� ����稨 �訡��
@mysql_query('drop table '.$db_tables["sites_feed_alert_emials"]);
mysql_query('
CREATE TABLE '.$db_tables["sites_feed_alert_emials"].' (
  email_id BIGINT UNSIGNED NOT NULL auto_increment,
  email VARCHAR(250) NOT NULL,
	INDEX(email(3)),
  PRIMARY KEY (email_id)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());



//������ ������ (ᮤ�ন� ᠬ� ���ଠ樨 �� ࠡ���)
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
//feed_id - �� ������ ���筨�� �� ࠡ��
//title - ���������: Programmer
//company_name - ��������: GENERAL DYNAMICS LAND SYSTEMS
//locId - ���������: 	�� ⠡���� ��த��
//url - URL � ������� ������.
//cat_id - ��⥣��� ࠡ���
//job_type - ⨯ ࠡ���: 1)Full-time, 2)Part-time 3)Contract, 4)Internship, 5)Temporary
//site_type - ⨯ ᠩ�: 1)Job boards only, 2)Employer web sites only
//isstaffing_agencies - ���� �� ��� ᠩ� isstaffing agencies
//salary - ��௫���
//registered - ��� � �६� ���ᥭ�� ������ � ����
//- - - -
//country | location info for location not found in DB (if allowed)
//region  | means we cannot find this location (city) in DB but
//city    | user want to see this job with this city name. Country is mandatory


//������ ������ (ᮤ�ন� ᠬ� ���ଠ樨 �� 㤠������ ࠡ���)
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
//feed_id - �� ������ ���筨�� �� ࠡ��
//title - ���������: Programmer
//company_name - ��������: GENERAL DYNAMICS LAND SYSTEMS
//locId - ���������: 	�� ⠡���� ��த��
//url - URL � ������� ������.
//cat_id - ��⥣��� ࠡ���
//job_type - ⨯ ࠡ���: 1)Full-time, 2)Part-time 3)Contract, 4)Internship, 5)Temporary
//site_type - ⨯ ᠩ�: 1)Job boards only, 2)Employer web sites only
//isstaffing_agencies - ���� �� ��� ᠩ� isstaffing agencies
//salary - ��௫���
//registered - ��� � �६� ���ᥭ�� ������ � ����
//- - - -
//country | location info for location not found in DB (if allowed)
//region  | means we cannot find this location (city) in DB but
//city    | user want to see this job with this city name. Country is mandatory



//������ ᮤ�ঠ�� ����⨪� �� ���������� �����
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
//feed_id - �� ������ ���筨�� �� ࠡ��
//registered - ��� � �६� ���ᥭ�� ������ � ����


//������ ������ �� ࠡ� � ᠩ� ४������⥫�. �� ४����� ᫮�� Jobs ADs
//�������  ⠪�� �� ��� � data_list, �� ���� feed_id ��뫠���� �� job_ads_id � ⠡��� "job_ads"!
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
//feed_id - �� ������ ���筨�� �� ࠡ��! �� ��뫪� �� job_ads_id � ⠡��� "job_ads"!
//title - ���������: Programmer
//company_name - ��������: GENERAL DYNAMICS LAND SYSTEMS
//locId - ���������: 	�� ⠡���� ��த��
//url - URL � ������� ������.
//cat_id - ��⥣��� ࠡ���
//job_type - ⨯ ࠡ���: 1)Full-time, 2)Part-time 3)Contract, 4)Internship, 5)Temporary
//site_type - ⨯ ᠩ�: 1)Job boards only, 2)Employer web sites only
//isstaffing_agencies - ���� �� ��� ᠩ� isstaffing agencies
//salary - ��௫���
//registered - ��� � �६� ���ᥭ�� ������ � ����
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
//title_run_phpcode_before - ����᪠�� �� php ��� ��। - 0;1
//title_phpcode_before ��뫪� �� ⠫��. common_php_code
//title_phpcode - php ��� ��� ������� ���� - PHP parsed data
//title_run_phpcode_after - ����᪠�� �� php ��� ��᫥ - 0;1
//title_phpcode_after ��뫪� �� ⠫��. common_php_code
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
//feed_id - ���筨�
//cat_id - id ��⥣�ਨ
//url - ᡮ� ������
//config_id - ���䨣����

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
//feed_id - ���筨�
//url - ᡮ� ������
//config_id - ���䨣����

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
//cat_id - id ��⥣�ਨ
//keywords - ���� ᫮�� ��� ������ ��⥣�ਨ

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
//feed_id - ���筨�
//cat_id - id ��⥣�ਨ
//url - ᡮ� ������
//config_id - ���䨣����

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
//feed_id - ���筨�
//url - ᡮ� ������
//config_id - ���䨣����

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
//title_run_phpcode_before - ����᪠�� �� php ��� ��। - 0;1
//title_phpcode_before ��뫪� �� ⠫��. common_php_code
//title_phpcode - php ��� ��� ������� ���� - PHP parsed data
//title_run_phpcode_after - ����᪠�� �� php ��� ��᫥ - 0;1
//title_phpcode_after ��뫪� �� ⠫��. common_php_code
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
//feed_id - ���筨�
//cat_id - id ��⥣�ਨ
//url - ᡮ� ������
//config_id - ���䨣����

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
//feed_id - ���筨�
//cat_id - id ��⥣�ਨ
//url - ᡮ� ������
//config_id - ���䨣����

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
//������ ��� ��࠭��� Browse keyword
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
//type - ⨯ (0 - root note[���孨� ����� �����], 1 - node[ᥪ��], 2 - link[��뫪� �� ���� ᫮��])
//link - ��뫪� ᥪ� ��� ᫢� ��� ��� ���奭�� �����

//Browse keyword - temp
//������ (�६�����) ��� ��࠭��� Browse keyword. �ᯮ������ ��� �࠭���� ������ �� �६� ����஥��� Browse keyword. ��⮬ ����� ��ॡ��뢠���� � "browse_keyword"
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
//type - ⨯ (0 - root note[���孨� ����� �����], 1 - node[ᥪ��], 2 - link[��뫪� �� ���� ᫮��])
//link - ��뫪� ᥪ� ��� ᫢� ��� ��� ���奭�� �����

//Browse keyword: Most Popular
//������ ��� Most Popular ᫮� ��� ��࠭��� Browse keyword
@mysql_query('drop table '.$db_tables["browse_keyword_most_popular"]);
mysql_query('
CREATE TABLE '.$db_tables["browse_keyword_most_popular"].' (
	kid BIGINT UNSIGNED NOT NULL,
	keyword VARCHAR(100) NOT NULL,
  INDEX (kid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//kid - �� �� ⠡���� browse_keyword

//Browse keyword: Most Popular - temp
//������ ��� Most Popular ᫮� ��� ��࠭��� Browse keyword - �६�����
@mysql_query('drop table '.$db_tables["browse_keyword_most_popular_temp"]);
mysql_query('
CREATE TABLE '.$db_tables["browse_keyword_most_popular_temp"].' (
	kid BIGINT UNSIGNED NOT NULL,
	keyword VARCHAR(100) NOT NULL,
  INDEX (kid)) ENGINE=MyISAM
') or die(__LINE__.mysql_error());
//kid - �� �� ⠡���� browse_keyword_temp

?>
</body>
<center><h1>Installation complite.</h1>
<h3><a href="index.html">Back to install page</h3></center>
</html>