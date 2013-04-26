<script type="text/javascript">var cp = new ColorPicker();</script>
{include file="form/form.tpl"}
{literal}
<script type="text/javascript">cp.writeDiv();</script>

<script type="text/javascript">
function get_cssText(color)
{
	return "'cursor:pointer;width:18px;height:18px;background-color:'+color+';border:solid 1px #999;'";
}
function pickColor(color)
{
	ColorPicker_targetInput.value = color;
	eval('get_element("' + ColorPicker_targetInput.name + '_example").style.cssText = ' + get_cssText(color) + ';');
}
</script>
{/literal}