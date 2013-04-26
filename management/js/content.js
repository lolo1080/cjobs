var Images_array = new Array('infview.gif', 'check_on.gif', 'reject.gif', 'autopay.gif', 'manualpay.gif', 'page_go.gif', 'infedit.gif','ledon.gif','ledoff.gif','campaings.gif','arrow.gif','up.gif','mail_confirm.gif','mail_not_confirm.gif','preview.gif','reject2.gif');
var Sel_Images_array = new Array('infview_sel.gif', 'check_on_sel.gif','reject_sel.gif', 'autopay_sel.gif', 'manualpay_sel.gif', 'page_go_sel.gif', 'infedit_sel.gif','ledon_sel.gif','ledoff_sel.gif','campaings_sel.gif','arrow_sel.gif','up_sel.gif','mail_confirm_sel.gif','mail_not_confirm_sel.gif','preview_sel.gif','reject2_sel.gif');
var Images = [];
var Sel_Images = [];

for (var i=0; i<Images_array.length; i++)
{
	Images[i] = new Image();
	Sel_Images[i] = new Image();
	Images[i].src = "images/"+Images_array[i];
	Sel_Images[i].src = "images/"+Sel_Images_array[i];
}

function img_flat(cur_img, isflat, num)
{
 cur_img.src = (isflat) ? Sel_Images[num].src : Images[num].src;
}

function group_check(fnum,check)
{ 
	for (var i=0; i<document.forms[fnum].elements.length; i++) {
		if ( (document.forms[fnum].elements[i].type == "checkbox") && 
				(document.forms[fnum].elements[i].name != "filter_field[]") &&
				(document.forms[fnum].elements[i].name != "none") )
			document.forms[fnum].elements[i].checked = check;
	}
} 

function submit_form(act,form_name)
{
	eval("document."+form_name+".action.value = '"+act+"';");
	eval("document."+form_name+".submit();");
}

function date_from_to_status(sel_elem)
{
 var elem_status = false;
	if (sel_elem.options[sel_elem.selectedIndex].value != "c") {
		elem_status = true; calendar_status = 'hidden'; elem_class_name = 'data_disable';
	}
	else {
		elem_status = false; calendar_status = 'visible'; elem_class_name = 'data';
	}
	document.frmperiodpage.date_from.disabled = elem_status;
	document.frmperiodpage.date_from.className = elem_class_name;
	document.frmperiodpage.date_to.disabled = elem_status;
	document.frmperiodpage.date_to.className = elem_class_name;
	span_from.style.visibility = calendar_status;
	span_to.style.visibility = calendar_status;
}

function payment_amount_status(sel_elem,cvalue)
{
 var elem_status = false;
	if (sel_elem.options[sel_elem.selectedIndex].value == cvalue) elem_status = true;
	else elem_status = false;
	document.mainform.amount.disabled = elem_status;
	document.mainform.amount_cb.disabled = !elem_status;
}

function OpenMyWindow(url)
{
	MyWindow = window.open(url,'','toolbar=no,scrollbars=no,directories=no,resizable=no,width=200,height=200');
 return false;
}

function get_element(s_id)
{
	return (document.all ? document.all[s_id] : (document.getElementById ? document.getElementById(s_id) : null));
}

function c_p(v)
{
	v = String(c_r(v));
	var p = v.indexOf(".");
	if( p == -1 ) {
		v = v + ".00";
	}
	else {
		v = v + "00";
		v = v.substring(0, p + 3);
	}
 return v;
}

function c_r(v)
{
	v = Number(v);
	if (isNaN(v) || v == 0) { return( 0 ); }
	return (Math.round(v*100)/100);
}

function calc_payment_amount(sel_elem)
{
	var payment_amout = get_element("payment_amout");
	payment_amout.value = c_p(period_prices[sel_elem.options[sel_elem.selectedIndex].value]);
}

function change_cc_active_fields($pos)
{
 var val_hide = new Array();
 var val_show = new Array();
	function make_change($status,$color)
	{
		elem.disabled = $status;
		var str = elem.style.cssText;
		var res = str.match(/width:\s*(\d+)px/i);
		if (res) elem.style.cssText = "width:" + res[1] + "px;background-color:" + $color + ";";
	}

	if ($pos == '0') {
		val_hide = cc_enter_new_field_list;
		val_show = cc_stored_field_list;
	}
	else {
		val_hide = cc_stored_field_list;
		val_show = cc_enter_new_field_list;
	}
	for (var i=0; i<val_hide.length; i++)
	{
		elem = get_element(val_hide[i]);
		make_change(true,'#EEEEE9');
	}
	for (var i=0; i<val_show.length; i++)
	{
		elem = get_element(val_show[i]);
		make_change(false,'#FFFFFF');
	}
}

function add_text_comment(sel_elem)
{
	elem = get_element('add_pay_comment');
	if (!elem) return;
	switch(sel_elem.options[sel_elem.selectedIndex].value) {
		case "credit_card": elem.innerHTML = "Enter your Credit Card number"; break;
		case "paypal": elem.innerHTML = "Enter your PayPal e-mail"; break;
		case "egold": elem.innerHTML = "Enter your E-Gold account number"; break;
		case "2checkout": elem.innerHTML = "Enter your 2checkout account number"; break;
		default: elem.innerHTML = "";
	}
}

function toggle_tpl_type_table_autoload()
{
	toggle_tpl_type_table(get_element('template_type'));
}

function toggle_tpl_type_table(sel_elem)
{
	var tpl_type_table = get_element('php_file_table');

	if (sel_elem.options[sel_elem.selectedIndex].value == "0") tpl_type_table.style.display = 'none';
	else tpl_type_table.style.display = '';
}

function autogenerate_script(base_url)
{
	var title = get_element('title');
	var title_val = title.value;
	var php_file = get_element('php_file');

	if (title_val == "") {
		alert('Please, fill title field first');
		return;
	}
	title_val = title_val.replace(/\W/g, "_");
	php_file.value = base_url + title_val + ".php";
}

function fill_td(from,to,before,after)
{
	from_elem = get_element(from);
	to_elem = get_element(to);
	to_elem.innerHTML = before + from_elem.value + after;
}