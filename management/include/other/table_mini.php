<?
function get_type_checkbox($cname,$cvalue,$cchecked)
{ return '<input type="checkbox" name="'.$cname.'" class="checkbox_data" value="'.$cvalue.'" '.$cchecked.' />'; }
function get_std_checkbox_list($sel_grop_text)
{ return '<input type="checkbox" class="checkbox_data" name="allcheckbox" align="absmiddle" onclick="group_check(1,this.checked)" />'.get_img("arrow_down.gif",9,12,$sel_grop_text); }
function get_js_action($num)
{ return 'onMouseover="img_flat(this,true,'.$num.')" onMouseout="img_flat(this,false,'.$num.')"'; }
//Create calendar
function get_calendar($inputField,$dateFormat,$button,$spanname)
{
global $text_info;
return '
<span name="'.$spanname.'" id="'.$spanname.'" class="frm"><a href="#"><img id="'.$button.'" name="'.$button.'" class="frm" src="images/show-calendar1.gif" width="17" height="19" border="0" align="absmiddle" title="'.$text_info["calendar_help"].'" /></a></span>
<script type="text/javascript">
	Calendar.setup(
	{
		inputField: "'.$inputField.'",
		ifFormat	: "'.$dateFormat.'",
		button		: "'.$button.'"
	}
	);
</script>
';
}
//Create html editor (fckeditor)
function get_html_editor($textarea_name,$width,$height,$baseURL)
{
return '
				var sBasePath = "'.$baseURL.'fckeditor/";
				var oFCKeditor = new FCKeditor( \''.$textarea_name.'\' );
				oFCKeditor.BasePath	= sBasePath;
				oFCKeditor.Config["CustomConfigurationsPath"] = sBasePath + "fckconfig_main.js";
				oFCKeditor.Width	= '.$width.';
				oFCKeditor.Height	= '.$height.';
				oFCKeditor.ReplaceTextarea();
';
}
//			oFCKeditor.Config["CustomConfigurationsPath"] = sBasePath + "fckconfig_main.js?" + ( new Date() * 1 ) ;
?>