Folders and files structure (brief description). If you need more complex description, please contact to us e-mail: michael@energyscripts.com.
We provide free support.

- Common structure:
    /admin - admin login folder
    /advertiser - advertiser login folder
    /docs - document folder
    /frontend - front-end file here
    /install - installation folder
    /management - management folder (admin area, advertiser area, publisher area, member area)
    /publisher - publisher login folder
    /*.* - front-end files

- Front-end structure:
		Language constants:
      /frontend/lang/*.php
    Templates:
      /frontend/templates/*.tpl
        ads_column.tpl - right column with Goolge ads and Sponsored links
        advanced_searchform.tpl - advanced search form
        advanced_searchpage.tpl - advanced search page
        advanced_searchpage_error.tpl - error advanced search page
        advertisers.tpl - advertisers page
        browse_jobs.tpl - browse jobs page
        browse_keword.tpl - browse jobs data (keywords)
        filter_column.tpl - left filter column
        homepage.tpl - homepage
        ipblocked.tpl - ip blocked page
        jobrollpage.tpl - jobroll page
        jobrollpage_empty.tpl - empty jobroll page
        mail_member_jobs_alert_items.tpl - member jobs alert template
        main_footer.tpl - footer template
        main_header.tpl - header template
        myarea.tpl - myarea page
        myjobs_item.tpl - my jobs list on my area page
        myjobs_menu.tpl - my jobs menu on my area page
        myjobspage.tpl - my jobs page on my area page
        navigation.tpl - page navigation template
        publishers.tpl - publishers page
        searchpage.tpl - search results page
        searchpage_empty.tpl - empty search results page
        searchresult_item.tpl - search result item
        select_country.tpl - select country page
        simple_searchpage_error.tpl - search error page

- Back-end structure:
		Language constants:
      /management/lang/*.php
		Cache files:
      /management/cache/*.*
		Cron files:
      /management/cron/*.*
		Log files:
      /management/logs/*.*
		E-mail templates:
      /management/mail/*.*
		Back-end pages templates:
      /management/templates/*.*