<script type="text/javascript">
var feed_id = {$Configuration.feed_id};
{literal}
var field_names = new Array(); //the list of field name
var field_titles = new Array(); //the list of field titles
{/literal}
{section name=i loop=$Configuration.fields}
	field_names[{$smarty.section.i.index}] = '{$Configuration.fields[i].name}';
	field_titles[{$smarty.section.i.index}] = '{$Configuration.fields[i].title}';
{/section}
{literal}

/*Common functions*/
function hide_feed_error()
{
	$("#error_box").hide();
}

function show_feed_error(msg)
{
	var msgstr = '';
	for (var i=0; i<msg.length; i++)
	{
		msgstr += msg[i];
	}
	$("#error_box").val(msgstr);
	$("#error_box").show();
}

/*Get data from URL functions*/
function get_data_from_xml_feed(url)
{
	hide_feed_error();

	$.ajax({
		type		: 'POST',
		url			: 'feeds_xml2_ajax.php',
		data		: { 'cmd': 'get', 'url': url },
		cache		: false,
		success	: function(res) {
			var obj = jQuery.parseJSON(res);
			$("#xml_rawdata").val(obj.xml_rawdata);
			if (obj.status == 'error') {
				show_feed_error(obj.errormsg);
				if ($("#debug_mode:checked")) $("#xml_rawdata").value(obj.xml_rawdata);
				return;
			}
			var pdata = '<select class="data" name="xml_parsedfields" id="xml_parsedfields" style="width:900px;" size="20" onClick="get_field_data(this)">' + obj.xml_parsedfields + '</select>';
			$("#div_xml_parsedfields").html(pdata);
		}
	});
}

/*Coinfiguration functions*/
function save_configuration()
{
	var data = "a=0";
	var id = "";
	var idd = "";

	if ( $("#url").val() != "" ) data += "&url=" + $.URLEncode($("#url").val());
	else { alert('Please enter Data URL'); return; }
	for (var i=0; i<field_names.length; i++)
	{
//		if (field_names[i] == 'category') continue;
		//Return status
		id = 'return_status\\['+field_names[i]+'\\]';
		idd = 'return_status['+field_names[i]+']';
		var val = $("input:radio[name='"+id+"']:checked").val();
		//val = $("input[@name='"+id+"']:checked").val()
		if (val == "") { alert('Please enter "'+field_titles[i]+'" return type'); return; }
		else data += "&"+idd+"="+val;

		//Return XML field
		if (val == '1') {
			id = 'return_box\\['+field_names[i]+'\\]';
			idd = 'return_box['+field_names[i]+']';
			val = $("#"+id).val();
			if (val == "") { alert('Please enter "'+field_titles[i]+'" XML field'); return; }
			else data += "&"+idd+"="+val;
		}

		else { //Return Parsed fields data

			id = 'return_phpcode_before\\['+field_names[i]+'\\]';
			idd = 'return_phpcode_before['+field_names[i]+']';
			val = ( $("input[@name='"+id+"']:checked") ) ? 1 : 0;
			data += "&"+idd+"="+val;

			//if PHP code checked, then get code name
			if ( val ) {
				id = 'return_phpcodetext_before\\['+field_names[i]+'\\]';
				idd = 'return_phpcodetext_before['+field_names[i]+']';
				val = $("input[@name='"+id+"']:selected").val();
				if (val == "") { alert('Please enter "'+field_titles[i]+'" before PHP code name'); return; }
				data += "&"+idd+"="+val;	
			}

			id = 'return_parsed_data\\['+field_names[i]+'\\]';
			idd = 'return_parsed_data['+field_names[i]+']';
			val = $("#"+id).val();
			if (val == "") { alert('Please enter "'+field_titles[i]+'" PHP parsed data'); return; }
			data += "&"+idd+"="+$.URLEncode(val);	

			id = 'return_phpcode_after\\['+field_names[i]+'\\]';
			idd = 'return_phpcode_after['+field_names[i]+']';
			val = ( $("input[@name='"+id+"']:checked") ) ? 1 : 0;
			data += "&"+idd+"="+val;

			//if PHP code checked, then get code name
			if ( val ) {
				id = 'return_phpcodetext_after\\['+field_names[i]+'\\]';
				idd = 'return_phpcodetext_after['+field_names[i]+']';
				val = $("input[@name='"+id+"']:selected").val();
				if (val == "") { alert('Please enter "'+field_titles[i]+'" after PHP code name'); return; }
				data += "&"+idd+"="+val;	
			}
		}
	}
	if ( $("#name").val() != "" ) data += "&name=" + $("#name").val();
	else { alert('Please enter Name'); return; }

	if ( $("#mode").val() != "" ) data += "&mode=" + $("#mode").val();
	if ( $("#mode_data").val() != "" ) data += "&mode_data=" + $("#mode_data").val();


	$(".category").each(function(i, selected) {
		data += '&'+$(selected).attr('name')+'='+$(selected).val();
	});

	data += '&cmd=save_configurtion';

	$.ajax({
		type		: 'POST',
		url			: 'feeds_xml2_ajax.php',
		data		: data,
		cache		: false,
		success	: function(res) {
			var obj = jQuery.parseJSON(res);
			if (obj.status == 'error') {
				show_feed_error(obj.errormsg); return;
			}
			else {
				if (obj.cmd != 'add') {
					//save configuration
					if (obj.cmd == 'save_configurtion') {
						$('#available_configuration_name'+obj.config_id).html(obj.config_name);
						$(".category_config option").each(function(i, selected) {
							var val = $(selected).val();
							if (val == obj.config_id) $(selected).text(obj.config_name);
						});
						return;
					}
					return;
				}
				else {
					//add line to Available Configurations table
					var str = '<tr><td style="height:20px;padding-top:2px;">'+obj.config_name+'</td>'+
								'<td style="height:20px;padding-top:2px;width:100px;"><a href="javascript:edit_configuration('+obj.config_id+');void(0);">Edit</a>'+
								'&nbsp;&nbsp;|&nbsp;&nbsp;'+
								'<a href="javascript:delete_configuration('+obj.config_id+',this);" id="delete_config'+obj.config_id+'">Delete</a></td></tr>';
					$('#avalilable_configurations_tbl > tbody:last').append(str);
					//add option to select boxes
					$(".category_config").each(function(i, selected) {
						$(selected).append('<option value="'+obj.config_id+'">'+obj.config_name+'</option>');
					});
				}
			}
		}
	});
}

function get_field_data(el)
{
	var selval = $('#'+el.id+' option:selected').val();
	if ((selval == undefined) || (selval == '')) return;
	$("#selected_field_template").html('<input type="text" name="selected_field_template_text" id="selected_field_template_text" value="{*'+selval+'*}" style="width:300px;" readonly /> Replace variable part with "?" and copy to XML field');
}

function edit_configuration(id)
{
	data = 'config_id='+id+'&cmd=edit';
	$.ajax({
		type		: 'POST',
		url			: 'feeds_xml2_ajax.php',
		data		: data,
		cache		: false,
		success	: function(res) {
			var resobj = jQuery.parseJSON(res);
			if (resobj.status == 'error') {
				show_feed_error(resobj.errormsg); return;
			}
			obj = resobj.config_data.configuration;
			catobj = resobj.config_data.categories;
			//Fill fields
			$('#mode').val('edit');
			$('#mode_data').val(id);
			$('#url').val(obj.config_url);
			$('#name').val(obj.config_name);
			{/literal}
			{section name=i loop=$Configuration.fields}
				if (obj.{$Configuration.fields[i].name}_status == '1') {ldelim}
					$('#return_status1\\[{$Configuration.fields[i].name}\\]').attr('checked', 'checked');
					$('#return_box\\[{$Configuration.fields[i].name}\\]').val(obj.{$Configuration.fields[i].name}_field);
				{rdelim}
				else {ldelim}
					$('#return_status2\\[{$Configuration.fields[i].name}\\]').attr('checked', 'checked');
					$('#return_box\\[{$Configuration.fields[i].name}\\]').val('');
				{rdelim}

				if (obj.{$Configuration.fields[i].name}_run_phpcode_before == '1') $('#return_phpcode_before\\[{$Configuration.fields[i].name}\\]').attr('checked', 'checked');
				else $('#return_phpcode_before\\[{$Configuration.fields[i].name}\\]').attr('checked', '');

				$('#return_phpcodetext_before\\[{$Configuration.fields[i].name}\\]').val(obj.{$Configuration.fields[i].name}_phpcode_before);

				if (obj.{$Configuration.fields[i].name}_phpcode != '0') $('#return_parsed_data\\[{$Configuration.fields[i].name}\\]').val(obj.{$Configuration.fields[i].name}_phpcode);
				else {ldelim}
					if (obj.{$Configuration.fields[i].name}_status == '2') $('#return_parsed_data\\[{$Configuration.fields[i].name}\\]').val('0');
					else $('#return_parsed_data\\[{$Configuration.fields[i].name}\\]').val('');
				{rdelim}

				if (obj.{$Configuration.fields[i].name}_run_phpcode_after == '1') $('#return_phpcode_after\\[{$Configuration.fields[i].name}\\]').attr('checked', 'checked');
				else $('#return_phpcode_after\\[{$Configuration.fields[i].name}\\]').attr('checked', '');

				$('#return_phpcodetext_after\\[{$Configuration.fields[i].name}\\]').val(obj.{$Configuration.fields[i].name}_phpcode_after);
			{/section}
			{literal}

			for (var i=0; i<catobj.length; i++)
			{
				$('#return_category\\['+catobj[i].cat_id+'\\]').val(catobj[i].keywords);
			}
		}
	});
}

function delete_configuration(id,elem)
{
	$.ajax({
		type		: 'POST',
		url			: 'feeds_xml2_ajax.php',
		data		: { 'cmd': 'delete', 'config_id': id },
		cache		: false,
		success	: function(res) {
			var obj = jQuery.parseJSON(res);
			if (obj.status == 'error') {
				show_feed_error(obj.errormsg);
				return;
			}
			$('#delete_config'+id).parent().parent().remove();			
		}
	});
}

function create_new_configuration()
{
	//Set blank fields
	$('#mode').val('add');
	$('#mode_data').val('');
	$('#url').val('');
	$('#name').val('');
	{/literal}
	{section name=i loop=$Configuration.fields}
		$('#return_status1\\[{$Configuration.fields[i].name}\\]').attr('checked', 'checked');
		$('#return_box\\[{$Configuration.fields[i].name}\\]').val('');
		$('#return_phpcode_before\\[{$Configuration.fields[i].name}\\]').attr('checked', '');
		$('#return_phpcodetext_before\\[{$Configuration.fields[i].name}\\]').val('');
		$('#return_parsed_data\\[{$Configuration.fields[i].name}\\]').val('');
		$('#return_phpcode_after\\[{$Configuration.fields[i].name}\\]').attr('checked', '');
		$('#return_phpcodetext_after\\[{$Configuration.fields[i].name}\\]').val('');
	{/section}
	{literal}
}

/*Data functions*/
/*
function get_row_num($btn)
{
	var $clonedRow = $btn.parent().parent().clone();
	var $clonedRow_id = $clonedRow.attr('id')
	var $clonedRow_nums = $clonedRow_id.split('_');
	var $clonedRow_num = $clonedRow_nums[1];
	return $clonedRow_num;
}
*/
$('.actionbutton_add').live('click', function() {
	var $btn = $(this);
	var $clonedRow = $btn.parent().parent().clone();
/*
	var $clonedRow_id = $clonedRow.attr('id')
	var $clonedRow_nums = $clonedRow_id.split('_');
	var $clonedRow_num = $clonedRow_nums[1];
*/
	var str_url = $btn.parent().parent().find('.category_url').val();
	$clonedRow.insertAfter('#data_items_tbl tbody>tr.data_items_tbl_tr:last');
	$clonedRow.find('.category_url').val(str_url);
	unlock_first_del_btn();
});
$('.actionbutton_delete').live('click', function() {
	var $btn = $(this);
//	var $clonedRow_num = get_row_num($btn);
	var $clonedRow = $btn.parent().parent().clone();
	if ($('#data_items_tbl tbody>tr.data_items_tbl_tr').length <= 1) return;
	$btn.parent().parent().remove();
	lock_first_del_btn();
});

function lock_first_del_btn()
{
	$(".data_items_table").each(function(i, selected) {
		var tbl_id = $(selected).attr('id')
		if ($('#'+tbl_id+' tbody>tr.data_items_tbl_tr').length == 1) $('#'+tbl_id+' tbody>tr.data_items_tbl_tr:first :button.actionbutton_delete').attr("disabled",true);
	});
}
function unlock_first_del_btn()
{
	$(".data_items_table").each(function(i, selected) {
		var tbl_id = $(selected).attr('id')
		if ($('#'+tbl_id+' tbody>tr.data_items_tbl_tr').length > 1) {
			$('#'+tbl_id+' tbody>tr.data_items_tbl_tr:first :button.actionbutton_delete').attr("disabled",false);
			$('#'+tbl_id+' tbody>tr.data_items_tbl_tr:last :button.actionbutton_delete').attr("disabled",false);
		}
	});
}

function check_data_parsing(btn)
{
	var url = $(btn).parent().find('.category_url').val();
	var config_id = $(btn).parent().find('.category_config option:selected').val();
	if (url == '') { alert('Please enter category URL'); return; }
	if (config_id == '') { alert('Please enter category ID'); return; }
	window.open('feeds_xml2_check.php?feed_id='+feed_id+'&config_id='+config_id+'&url='+$.URLEncode(url));
}

$(document).ready(function() {
	$("#url_send").bind('click', function(e) {
		var url = $("#url").val();
		if (url == "") alert('{/literal}{$Configuration.err_no_url|strip_tags}{literal}');
		else get_data_from_xml_feed(url);
	});
	lock_first_del_btn();
});
{/literal}
</script>
<div><h4 style="padding-left:10px;">Data section {if $Configuration.feed_name != ''}: {$Configuration.feed_name}{/if}</h4></div>
<div style="padding-left:20px;">
{if ( ($Configuration.acttype != 'new') && ($Configuration.save.eroror) )}<div style="color:#ff0000">{$Configuration.save.eroror}</div>{/if}
<form name="feed_data" action="feeds_xml2.php" method="POST">
	{section name=i loop=$FormHidden}
		<input type="hidden" name="{$FormHidden[i].fname}" value="{$FormHidden[i].fvalue}" />
	{/section}
	<table width="900" class="complex_data_table" cellpadding="0" cellspacing="0">
	<tr>
		<td class="tbl_td_head" style="height:30px;padding-top:5px;">Parser information</td>
	</tr>

	<tr>

		<td>
			<table width="100%" id="data_items_tbl" class="data_items_table" cellpadding="2" cellspacing="2">
				{if $Configuration.acttype == 'new'}

					<tr id="datarow[]" class="data_items_tbl_tr">
						<td>
							URL:<input type="text" name="category_url[]" id="category_url[]" class="category_url" value="" style="width:300px;" />
							Config:<select name="category_id[]" id="category_id[]" class="category_config" style="width:190px;">
								{section name=j loop=$Configuration.configuration_list}
									<option value="{$Configuration.configuration_list[j].config_id}" {if $smarty.section.j.first}selected{/if}>{$Configuration.configuration_list[j].config_name}</option>
								{/section}
							</select>
							<input type="button" value="check parsing" onClick="check_data_parsing(this);" />
						</td>
						<td nowrap style="padding-top:2px;"><input type="button" value="+" title="Add new record" class="actionbutton_add" style="font-size:16px;width:22px;height:25px;cursor:pointer;" />&nbsp;<input type="button" value="-" title="Delete current record" class="actionbutton_delete" style="font-size:16px;width:22px;height:25px;cursor:pointer;" /></td> {*Class actionbutton_AAA uses in JavaScript above*}
					</tr>

				{else}

					{foreach name=category_url_item key=key1 item=item1 from=$Configuration.save.category_url}
					<tr id="datarow[]" class="data_items_tbl_tr">
						<td>
							URL:<input type="text" name="category_url[]" id="category_url[]" class="category_url" value="{$item1}" style="width:300px;" />
							Config:<select name="category_id[]" id="category_id[]" class="category_config" style="width:190px;">
								{section name=j loop=$Configuration.configuration_list}
									<option value="{$Configuration.configuration_list[j].config_id}" {if ($Configuration.save.category_id[$key1]==$Configuration.configuration_list[j].config_id)} selected{/if}>{$Configuration.configuration_list[j].config_name}</option>
								{/section}
							</select>
							<input type="button" value="check parsing" onClick="check_data_parsing(this);" />
						</td>
						<td nowrap style="padding-top:2px;"><input type="button" value="+" title="Add new record" class="actionbutton_add" style="font-size:16px;width:22px;height:25px;cursor:pointer;" />&nbsp;<input type="button" value="-" title="Delete current record" class="actionbutton_delete" style="font-size:16px;width:22px;height:25px;cursor:pointer;" /></td> {*Class actionbutton_AAA uses in JavaScript above*}
					</tr>
					{/foreach}

				{/if}
			</table>
		</td>
	</tr>

	<tr>
		<td colspan="2" align="right" style="padding:10px 2px 10px 0px">
			<input type="submit" name="save" value="Save" />
		</td>
	</tr>
	</table>
</form>
</div>

<div><h4 style="padding-left:10px;">Configurations section</h4></div>

<div><h5 style="padding-left:10px;margin-top:0px;margin-bottom:3px;">Avalilable configurations (<a href="javascript:create_new_configuration();void(0);" class="BlackMenu">create new configuration</a>)</h5></div>
<div style="padding-left:20px;">
<table width="900" class="complex_data_table" cellpadding="0" cellspacing="0" id="avalilable_configurations_tbl">
	<tr>
		<td class="tbl_td_head" style="height:30px;padding-top:5px;">Configuration name</td>
		<td class="tbl_td_head" style="height:30px;padding-top:5px;">Action</td>
	</tr>
	{section name=i loop=$Configuration.configuration_list}
	{assign var="config_id" value=$Configuration.configuration_list[i].config_id}
	<tr>
		<td style="height:20px;padding-top:2px;" id="available_configuration_name{$config_id}">{$Configuration.configuration_list[i].config_name}</td>
		<td style="height:20px;padding-top:2px;width:100px;">
			<a href="javascript:edit_configuration({$config_id});void(0);">Edit</a>&nbsp;&nbsp;|&nbsp;&nbsp;
			<a href="javascript:delete_configuration({$config_id},this);" id="delete_config{$config_id}">Delete</a>
		</td>
	</tr>
	{/section}
</table>
</div>

<div><h5 style="padding-left:10px;margin-top:3px;margin-bottom:3px;">Current {$Configuration.headtitle}</h5></div>

<div style="font-family: arial; font-size: 12px;">
 <div style="padding-left:20px;">
	{$Configuration.dataurl}:
	<input type="text" name="url" id="url" value="" style="width:500px;" />
	<input type="hidden" name="mode" id="mode" value="add" />
	<input type="hidden" name="mode_data" id="mode_data" value="" />
	<input type="button" name="url_send" id="url_send" value="{$Configuration.sendtitle}" />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="checkbox" name="debug_mode" id="debug_mode" value="{$Configuration.debugmodetitle}" />debug mode<br />
	<div id="error_box" style="display:none;"><br /></div>
	<div>
		<textarea class="data" name="xml_rawdata" id="xml_rawdata" style="width:900px;height:200px;">{$Configuration.xml_rawdata}</textarea>
	</div>
	<div id="div_xml_parsedfields">
		{$Configuration.div_xml_parsedfields}
	</div>
	<div style="float:left;">
		<span>{$Configuration.selectedfieldtitle}:</span><br />
		<span id="selected_field_template"></span>
	</div>
	<div style="clear:both;">
 </div>

	<table width="900" class="complex_data_table" cellpadding="0" cellspacing="0">
	{section name=i loop=$Configuration.fields}
		{if ($Configuration.fields[i].name == "category")}
		<tr>
			<td width="20%">{$Configuration.fields[i].title}<br /><small>{$Configuration.fields[i].note}</small></td>
			<td>
					<i>Parsed field information:</i><br />
					<input type="radio" name="return_status[{$Configuration.fields[i].name}]" id="return_status1[{$Configuration.fields[i].name}]" value="1" checked />Return XML field:
					<input type="text" name="return_box[{$Configuration.fields[i].name}]" id="return_box[{$Configuration.fields[i].name}]" style="width:350px;" /><br />
						<span style="padding-left:22px;">
						<small>Use expression from above list&nbsp;&nbsp;For example: [ {ldelim}*[rss][channel][0][item][?][title][0][#text]*{rdelim} ]</small>
						</span><br />
					<input type="radio" name="return_status[{$Configuration.fields[i].name}]" id="return_status2[{$Configuration.fields[i].name}]" value="2" />Return PHP parsed XML field
					<div>
						<!--
						<input type="checkbox" name="return_phpcode_before[{$Configuration.fields[i].name}]" id="return_phpcode_before[{$Configuration.fields[i].name}]" />Run PHP code 
						<select name="return_phpcodetext_before[{$Configuration.fields[i].name}]" id="return_phpcodetext_before[{$Configuration.fields[i].name}]">
							<option value="1" selected>test</option>
						</select> before<br />
						-->

						<span style="padding-left:22px;">PHP parsed data*:</span><br />
						<small>
							<ul style="padding:0; padding-left:20px; margin:0;">
								<li>To return constant string just put it.<br />&nbsp;&nbsp;For example: [ 0 ]</li>
								<li>You can use next constructions '{ldelim}*&lt;XML field here&gt;*{rdelim}'</li>
								<li>To return calculated string just put to $result value.<br />&nbsp;&nbsp;For example: [ $result = trim( {ldelim}*[rss][channel][0][item][?][source][0][#text]*{rdelim} ); ]</li>
								<li>Do not override next values: $feed_row, $log_actions, $FeedName, $url, $Result</li>
							</ul>
						</small>
						<textarea class="data" name="return_parsed_data[{$Configuration.fields[i].name}]" id="return_parsed_data[{$Configuration.fields[i].name}]" style="width:700px;height:120px;">{$Configuration.fields[i].content}</textarea><br />

						<i>Keywords information:</i><br />
						<table width="100%">
							<tr>
								<td>Category name</td>
								<td>Category keywords:<br />
<!--
									<small>
										<ul style="padding:0; padding-left:20px; margin:0;">
											<li>'*' means 0 or more symbols.<br />&nbsp;&nbsp;For example: [ program* ]</li>
											<li>'?' means any 1 symbol.<br />&nbsp;&nbsp;For example: [ program*er ]</li>
										</ul>
									</small>
-->
								</td>
							</tr>
							{section name=c_i loop=$categories}
							<tr>
								<td valign="top" style="padding:0px; margin:0px;">{$categories[c_i].cat_name}</td>
								<td>
									<textarea class="data category" name="return_category[{$categories[c_i].cat_id}]" id="return_category[{$categories[c_i].cat_id}]" style="width:460px;height:100px;"></textarea>
								</td>
							</tr>
							{/section}
						</table>
					</div>
			</td>
		</tr>
		{else}
		<tr>
			<td width="20%">{$Configuration.fields[i].title}<br /><small>{$Configuration.fields[i].note}</small></td>
			<td>
					<input type="radio" name="return_status[{$Configuration.fields[i].name}]" id="return_status1[{$Configuration.fields[i].name}]" value="1" checked />Return XML field:
					<input type="text" name="return_box[{$Configuration.fields[i].name}]" id="return_box[{$Configuration.fields[i].name}]" style="width:350px;" /><br />
						<span style="padding-left:22px;">
						<small>Use expression from above list&nbsp;&nbsp;For example: [ {ldelim}*[rss][channel][0][item][?][title][0][#text]*{rdelim} ]</small>
						</span><br />
					<input type="radio" name="return_status[{$Configuration.fields[i].name}]" id="return_status2[{$Configuration.fields[i].name}]" value="2" />Return PHP parsed XML field
					<div>
						<!--
						<input type="checkbox" name="return_phpcode_before[{$Configuration.fields[i].name}]" id="return_phpcode_before[{$Configuration.fields[i].name}]" />Run PHP code 
						<select name="return_phpcodetext_before[{$Configuration.fields[i].name}]" id="return_phpcodetext_before[{$Configuration.fields[i].name}]">
							<option value="1" selected>test</option>
						</select> before<br />
						-->

						<span style="padding-left:22px;">PHP parsed data*:</span><br />
						<small>
							<ul style="padding:0; padding-left:20px; margin:0;">
								<li>To return constant string just put it.<br />&nbsp;&nbsp;For example: [ 0 ]</li>
								<li>You can use next constructions '{ldelim}*&lt;XML field here&gt;*{rdelim}'</li>
								<li>To return calculated string just put to $result value.<br />&nbsp;&nbsp;For example: [ $result = trim( {ldelim}*[rss][channel][0][item][?][source][0][#text]*{rdelim} ); ]</li>
								<li>Do not override next values: $feed_row, $log_actions, $FeedName, $url, $Result</li>
							</ul>
						</small>
						<textarea class="data" name="return_parsed_data[{$Configuration.fields[i].name}]" id="return_parsed_data[{$Configuration.fields[i].name}]" style="width:700px;height:120px;">{$Configuration.fields[i].content}</textarea><br />

						<!--
						<input type="checkbox" name="return_phpcode_after[{$Configuration.fields[i].name}]" if="return_phpcode_after[{$Configuration.fields[i].name}]" />Run PHP code
						<select name="return_phpcodetext_after[{$Configuration.fields[i].name}]" id="return_phpcodetext_after[{$Configuration.fields[i].name}]">
							<option value="1" selected>test</option>
						</select> after
						-->
					</div>
			</td>
		</tr>
		{/if}
	{/section}
	</table>
	<table width="900" class="complex_data_table" cellpadding="0" cellspacing="0">
	<tr>
		<td>Name: <input type="text" name="name" id="name" value="" style="width:250px;" /></td>
		<td align="right"><input type="button" name="save_configuration" id="save_configuration" value="{$Configuration.save_btn}" onClick="save_configuration();" /></td>
	</tr>
	</table>
</div>