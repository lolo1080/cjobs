Script : ES Job Search Engine
Version: 3.1
Author : EnergyScripts (http://www.energyscripts.com)
Site   : http://www.es-job-search-engine.com/
Email  : michael@energyscripts.com

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
DESCRIPTION

ES Job Search Engine is the most powerful, affordable and flexible meta job
search engine script.

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
REQUIREMENTS

Linux/Unix,
a web server (Apache 1.3+ recommended)
PHP 4.2.0+
MySQL 3.23+
Cron (which can be started from apache user)

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
FEATURES

* GENERAL *
- ES Job Search Engine is PHP and MySQL based software. So it is fast and easy
  to use. 
- ES Job Search Engine can be easily installed using installer script. 
- Fully customizable layout.
- Customize e-mail messages.
- Templates for Search area. 
- Free installation & free support.
- Payment Gateways support: PayPal, Credit Card, E-Gold.
- Build In Cache System.
- Millions of employment opportunities from 11 websites, job boards, newspapers,
  associations and company career pages.

* ADMIN AREA *
- Manage users: add / edit / delete advertisers, publishers and members.
- Manage users submissions: Advertisers, Publishers, Members, XML Feed, Sponsor
  Job ads.
- View detailed statistic: Users, Search, Clicks, Earn money, IP address.
- Set site settings: Global settings, Jobroll setting, Payment settings.
- Payment request.
- Payment history.
- IP Firewall.
- Manage page and e-mail templates.

* ADVERTISER AREA *
- Manage Advertisements: "keyword ad" and "jobs from my site ad".
- View detailed statistic: Clicks, Ad Views, CTR, Avg CPC, Cost, Avg Pos.
- Fund account.
- Fund account history.

* PUBLISHER AREA *
- Jobroll.
- Search Box.
- Text Link.
- XML feed.
- Traffic Summary.
- Payment request.
- Payment request history.

* FRONT-END *
- Simple Job Search.
- Search by Company, Keyword, City, State and Zip.
- Advanced Job Search.
- Search by Company and Keyword with next options: With all of these words,
  With the exact phrase, With at least one of these words, Without the words,
  With these words in the title, From this company.
- Search by Location(City, State, or Zip) with distance.
- Search by Jobs with next options: job category, job type, job salary.
- Jobs in Country feature.
- Jobs Categories.
- Filter By Job Category, Company, Title, Location, Job Type, Salary in search
  results.
- Sort Search results by relevance and date
- Save job and email job features

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
HOW TO INSTALL ES JOB SEARCH ENGINE

Note that this guide does not describe how to install a web server or PHP or a database 
server. See the documentation for these programs on how to install those. This
installation guide assumes that you already have a working web server with PHP support
and a MySQL database installed.

We are strongly recommend to install this software our support team. Please, contact
us. It is free of charge!

- Create folder for php scripts on the server.
- Customize values of variables in file "/management/consts.php". See comments in file.
  Note: It is obligatory to customize:
   1. database access values:
    $dbhost = "<MySQL DB host>";
    $dbuser = "<MySQL DB user>";
    $dbpassword = "<MySQL DB password>";
    $dbname = "<MySQL DB name>";
   1. time zone (please, see possible values here: http://php.net/manual/en/timezones.php):
    $usersettings["timezone_identifier"] = "America/New_York";
  You should set these values before start to use script.
- Copy all files, folders and subfolders to the server.
- Make the following directories writeable (which is called "chmod 777" in unix language)
  with your FTP client software you used for upload the script:
   /frontend/templates/
   /frontend/templates_c/
   /management/cache/
   /management/logs/
   /management/mail/attach/
   /management/templates_c/
- Make the following files anmd folders writeable (777):
  1. all files and folders under these directories:
   /frontend/templates/*.tpl (all files with tpl extension)
   /management/cache/*.* (all files and folders)
   /management/logs/*.txt (all files with txt extension)
   /management/mail/*.html (all files with html extension)
   /management/mail/*.txt (all files with txt extension)
  2. only files:
   /management/news/news.html
- Set correct URL to your website in front-end templates (/frontend/templates/*.tlp)
  You should replace "http://localhost/esjobsearchengine/" to "http://<your script location>/".
  Please, use any software which can replace date on many files. Note: If you are not sure
  how to do this, please contact us and will do this. It is free of charge.
- Run installation process ( use next link: http://<script location>/install/ )
   Note: when you click on "Install dump data" link new scritp will be loaded. Click "Start Import"
   link for data_cities.sql and wait for import. Then click browser back button and clicl "Start Import"
   link for site_config.sql
- Log in as Administrator:
   URL: http://<script location>/admin/
   user "admin"
   password "admin".
- Go to the "Settings - Global settings" page and change next values: Site title,
   Base site url.
  Note: You can customize any other value too.
- Go to the "Settings - Admin settings" page and change admin password.
   DO NOT FORGET DO THIS!!! This can be a serious security hole!
- Delete "install" folder from server.
   DO NOT FORGET DO THIS!!! This can be a serious security hole!
- Set next cron jobs:
  1. /management/cron/cache_cleaner_backend.php -- start each 4 hours. Clear old
  back-end cache files
  2. /management/cron/cache_cleaner_frontend.php -- start each 4 hours. Clear old
  front-end cache files
  3. /management/cron/send_job_alert.php -- start each 24 hours. Send job alerts
  to members.
  4. /management/cron/delete_dup_records.php A B -- Delete job duplicates.
  A and B is params:
  A - is a period of days for duplicate jobs search
  B - is a count records in block
  We recommend start in next way:
     /management/cron/delete_dup_records.php 30 5000 -- start each day
     /management/cron/delete_dup_records.php 1 100 -- start each 6 hours
  5. /management/cron/browse_jobs_keyword.php -- start each 1 week. Create
  "Browse Jobs" page.
  6. /management/cron/data_collection/index.php -- start each 6 hours. Collect
  jobs from external websites.
  7. /management/cron/db_cleaner.php -- start each week. Clear DB data collection log.
  8. rm /management/logs/application_errors.txt.* -- start each 2 days. Delete log files
  9. rm /management/logs/crawl_log.txt.* -- start each 2 days. Delete log files
- Edit RewriteBase value in .htaccess file on your site in script base folder.
  Replace "/esjobsearchengine/" with path under hosing domain. For example,
  1. if URL to your website: http://www.my-job-search/engine/
   then RewriteBase /engine/
  2. if URL to your website: http://www.my-best-job-search/
   then RewriteBase /
- SiteMap. If you want to have sitemap with jobs list, please do next steps.
  1. Copy all xml files from /sitemap/ folder document root
  2. Make the following files writeable (which is called "chmod 777" in unix language)
  in document root:
    /indexsitemap.xml
    /jobsmap1.xml
    /jobsmapX.xml (where X in [2, ..., 30])
  2. If you have not robots.txt in document root, please, copy robots.txt to document root
  3. Please, edit robots.txt and set corrent URL to sitemap/indexsitemap.xml.
    For example, "Sitemap: http://localhost/esjobsearchengine/sitemap/sitemapindex.xml"
  4. Set cron job: /management/cron/create_sitemap.php -- start each 1 day.
    This cron job will ceate a sitemap will all jobs list.

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
QUESTIONS AND BUGS

Email : michael@energyscripts.com
Site  : http://www.es-job-search-engine.com/

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
NEED A COMPLETE WEB SITE

EnergyScripts: http://www.energyscripts.com