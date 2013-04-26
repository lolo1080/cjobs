<?php
/*
#######################################
# lang_settings_en.php
# Language file with settings (English)
#######################################
*/

//Use space before selected: " selected"
$radius_array = array(
"0" =>	array("caption"=>"only in", "selected"=>""),
"5" =>	array("caption"=>"within 5 miles of", "selected"=>""),
"10" =>	array("caption"=>"within 10 miles of", "selected"=>""),
"15" =>	array("caption"=>"within 15 miles of", "selected"=>""),
"25" =>	array("caption"=>"within 25 miles of", "selected"=>" selected"),
"50" =>	array("caption"=>"within 50 miles of", "selected"=>""),
"100" =>array("caption"=>"within 100 miles of", "selected"=>"")
);
$radius_array_default = 25;
$jobs_type_array = array(
"all"				=> array("caption"=>"All job types", "selected"=>" selected"),
"fulltime"	=> array("caption"=>"Full-time", "selected"=>""),
"parttime"	=> array("caption"=>"Part-time", "selected"=>""),
"contract"	=> array("caption"=>"Contract", "selected"=>""),
"internship"=> array("caption"=>"Internship", "selected"=>""),
"temporary"	=> array("caption"=>"Temporary", "selected"=>"")
);
$jobs_type_array_default = "all";
$jobs_from_array = array(
"all"	=> array("caption"=>"All web sites", "selected"=>" selected"),
"jobboard"	=> array("caption"=>"Job boards only", "selected"=>""),
"employer"	=> array("caption"=>"Employer web sites only", "selected"=>""),
);
$jobs_from_array_default = "all";
$jobs_published_array = array(
"any"	=> array("caption"=>"Anytime", "selected"=>""),
"90"	=> array("caption"=>"Within 90 days", "selected"=>""),
"30"	=> array("caption"=>"Within 30 days", "selected"=>" selected"),
"15"	=> array("caption"=>"Within 15 days", "selected"=>""),
"7"		=> array("caption"=>"Within 7 days", "selected"=>""),
"3"		=> array("caption"=>"Within 3 days", "selected"=>""),
"1"		=> array("caption"=>"Since yesterday", "selected"=>""),
"last"=> array("caption"=>"Since my last visit", "selected"=>"")
);
$jobs_published_default = "30";
$number_results_array = array(
"10"	=> array("caption"=>"10", "selected"=>""),
"20"	=> array("caption"=>"20", "selected"=>" selected"),
"30"	=> array("caption"=>"30", "selected"=>""),
"40"	=> array("caption"=>"40", "selected"=>""),
"50"	=> array("caption"=>"50", "selected"=>"")
);
$number_results_default = "20";
$usa_states = array(
'AL'=>'ALABAMA',
'AK'=>'ALASKA',
'AZ'=>'ARIZONA',
'AR'=>'ARKANSAS',
'CA'=>'CALIFORNIA',
'CO'=>'COLORADO',
'CT'=>'CONNECTICUT',
'DC'=>'D.C.',
'DE'=>'DELAWARE',
'FL'=>'FLORIDA',
'GA'=>'GEORGIA',
'HI'=>'HAWAII',
'ID'=>'IDAHO',
'IL'=>'ILLINOIS',
'IN'=>'INDIANA',
'IA'=>'IOWA',
'KS'=>'KANSAS',
'KY'=>'KENTUCKY',
'LA'=>'LOUISIANA',
'ME'=>'MAINE',
'MD'=>'MARYLAND',
'MA'=>'MASSACHUSETTS',
'MI'=>'MICHIGAN',
'MN'=>'MINNESOTA',
'MS'=>'MISSISSIPPI',
'MO'=>'MISSOURI',
'MT'=>'MONTANA',
'NE'=>'NEBRASKA',
'NV'=>'NEVADA',
'NH'=>'NEW HAMPSHIRE',
'NJ'=>'NEW JERSEY',
'NM'=>'NEW MEXICO',
'NY'=>'NEW YORK',
'NC'=>'NORTH CAROLINA',
'ND'=>'NORTH DAKOTA',
'OH'=>'OHIO',
'OK'=>'OKLAHOMA',
'OR'=>'OREGON',
'PA'=>'PENNSYLVANIA',
'RI'=>'RHODE ISLAND',
'SC'=>'SOUTH CAROLINA',
'SD'=>'SOUTH DAKOTA',
'TN'=>'TENNESSEE',
'TX'=>'TEXAS',
'UT'=>'UTAH',
'VT'=>'VERMONT',
'VA'=>'VIRGINIA',
'WA'=>'WASHINGTON',
'WV'=>'WEST VIRGINIA',
'WI'=>'WISCONSIN',
'WY'=>'WYOMING'
);
$canada_provinces = array(
'AB'=>'ALBERTA',
'BC'=>'BRITISH COLUMBIA',
'MB'=>'MANITOBA',
'NB'=>'NEW BRUNSWICK',
'NF'=>'NEWFOUNDLAND',
'NT'=>'NORTHWEST TERRITORIES',
'NU'=>'NUNAVUT',
'NS'=>'NOVA SCOTIA',
'ON'=>'ONTARIO',
'PE'=>'PRINCE EDWARD ISLAND',
'QC'=>'QUEBEC',
'SK'=>'SASKATCHEWAN',
'YT'=>'YUKON'
);
$jobs_in_country_info = array(
'all'=>array(
	"image"=>"us.gif",
	"message"=>"Jobs in United States"
	),
'us'=>array(
	"image"=>"us.gif",
	"message"=>"Jobs in United States"
	),
'ca'=>array(
	"image"=>"ca.gif",
	"message"=>"Jobs in Canada"
	),
'au'=>array(
	"image"=>"au.gif",
	"message"=>"Jobs in Australia"
	)
);
?>