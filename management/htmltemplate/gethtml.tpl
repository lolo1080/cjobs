<table class="TopSpan">
<!-- QUICK SEARCH LINKS -->
<tr align="center">
  <td><b>Your Quick search links</b><br />Use Quick search links to help people find what they need quickly.</td>
</tr>
<tr>
  <td><b>Quick search links example:</b><br />Here is linked keyword <a href="{*search_url*}?uid={*uid*}&keywords=Music">Music</a></td>
</tr>
<tr>
  <td><b>Code</b>(<small>Replace &quot;[KEYWORD]&quot; for any word and use this html code at any place in your site.</small>):
    <br />
    <textarea readonly rows="6" cols="90">
&lt;a href={*search_url*}?uid={*uid*}&keywords=[KEYWORD]&gt;[KEYWORD]&lt;/a&gt; 
    </textarea>
    <br />
    <b>Note:</b> You can use a link that call the &quot;I'm feeling luck&quot; function. Just add to link &quot;lucky=1&quot; parameter.<br />
  	<b>Code</b>(<small>Replace &quot;[KEYWORD]&quot; for any word and use this html code at any place in your site.</small>):
    <br />
    <textarea readonly rows="6" cols="90">
&lt;a href={*search_url*}?uid={*uid*}&keywords=[KEYWORD]&lucky=1&gt;[KEYWORD]&lt;/a&gt; 
    </textarea>
  </td>
</tr>

<tr><td><br /><hr /><br /></td></tr>

<!-- SIMPLE SEARCH BOX -->
<tr align="center">
  <td><b>Your Simple Search Box</b></td>
</tr>
<tr>
  <td><b>Simple Search Box example:</b><br />
      <form class="frm" method="POST" action="{*search_url*}">
        <input type="hidden" name="uid" value="{*uid*}" />
        <input type="text" name="keywords" size="30" />
        <input type="submit" name="submit" value="Search" />
      </form>
    <br />
    <b>Code</b>:
    <br />
    <textarea readonly rows="6" cols="90">
&lt;form method="POST" action="{*search_url*}"&gt;
  &lt;input type="hidden" name="uid" value="{*uid*}" /&gt;
  &lt;input type="text" name="keywords" size="30" /&gt;
  &lt;input type="submit" name="submit" value="Search" /&gt;
&lt;/form&gt;
    </textarea>
  </td>
</tr>

<tr><td><br /><hr /><br /></td></tr>

<!-- ADVANCED SEARCH BOX #1 -->
<tr align="center">
  <td><b>Your Advanced Search box 1</b></td>
</tr>
<tr>
  <td><b>Advanced Search box 1 example:</b><br />
      <form class="frm" method="POST" action="{*search_url*}">
        <input type="hidden" name="uid" value="{*uid*}" />
        <table border="0" cellpadding="0" cellspacing="0" width="468" height="60" background="{*special_img_url*}affimage1.gif">
        <tr>
          <td width="20">&nbsp;</td>
          <td>
            <a href="{*sign_up_url*}">
              <b><font face="Verdana" size="2" color="#002200">Earn money, JOIN NOW!</font></b>
            </a>
          </td>
        </tr>
        <tr>
          <td width="20">&nbsp;</td>
          <td><b><font face="Verdana" size="2" color="#002200">Frustrated? Find a solution . . . . .</font></b></td>
        </tr>
        <tr>
          <td width="20">&nbsp;</td>
          <td>
            <input type="text" name="keywords" size="30" />
            <input type="submit" name="submit" value="Search" />
          </td>
        </tr>
        </table>
      </form>
    <br />
    <b>Code</b>:
    <br />
    <textarea readonly rows="6" cols="90">
&lt;form method="POST" action="{*search_url*}"&gt;
  &lt;input type="hidden" name="uid" value="{*uid*}" /&gt;
  &lt;table border="0" cellpadding="0" cellspacing="0" width="468" height="60" background="{*special_img_url*}affimage1.gif"&gt;
  &lt;tr&gt;
    &lt;td width="20"&gt;&amp;nbsp;&lt;/td&gt;
    &lt;td&gt;
      &lt;a href="{*sign_up_url*}"&gt;
        &lt;b&gt;&lt;font face="Verdana" size="2" color="#002200"&gt;Earn money, JOIN NOW!&lt;/font&gt;&lt;/b&gt;
      &lt;/a&gt;
    &lt;/td&gt;
  &lt;/tr&gt;
  &lt;tr&gt;
    &lt;td width="20"&gt;&amp;nbsp;&lt;/td&gt;
    &lt;td&gt;&lt;b&gt;&lt;font face="Verdana" size="2" color="#002200"&gt;Frustrated? Find a solution . . . . .&lt;/font&gt;&lt;/b&gt;&lt;/td&gt;
  &lt;/tr&gt;
  &lt;tr&gt;
    &lt;td width="20"&gt;&amp;nbsp;&lt;/td&gt;
    &lt;td&gt;
      &lt;input type="text" name="keywords" size="30" /&gt;
      &lt;input type="submit" name="submit" value="Search" /&gt;
    &lt;/td&gt;
  &lt;/tr&gt;
  &lt;/table&gt;
&lt;/form&gt;
    </textarea>
  </td>
</tr>

<tr><td><br /><hr /><br /></td></tr>

<!-- ADVANCED SEARCH BOX #2 -->
<tr align="center">
  <td><b>Your Advanced Search box 2</b></td>
</tr>
<tr>
  <td><b>Advanced Search box 2 example:</b><br />
      <form class="frm" method="POST" action="{*search_url*}">
        <input type="hidden" name="uid" value="{*uid*}" />
        <table border="0" cellpadding="0" cellspacing="0" width="468" height="60" background="{*special_img_url*}affimage2.gif">
        <tr>
          <td width="20">&nbsp;</td>
          <td>
            <a href="{*sign_up_url*}">
              <b><font face="Verdana" size="2" color="#FFFFFF">Earn money, JOIN NOW!</font></b>
            </a>
          </td>
        </tr>
        <tr>
          <td width="20">&nbsp;</td>
          <td><b><font face="Verdana" size="2" color="#FFFFFF">Looking for a new guide, find it . . . . .</font></b></td>
        </tr>
        <tr>
          <td width="20">&nbsp;</td>
          <td>
            <input type="text" name="keywords" size="30" />
            <input type="submit" name="submit" value="Search" />
          </td>
        </tr>
        </table>
      </form>
    <br />
    <b>Code</b>:
    <br />
    <textarea readonly rows="6" cols="90">
&lt;form method="POST" action="{*search_url*}"&gt;
  &lt;input type="hidden" name="uid" value="{*uid*}" /&gt;
  &lt;table border="0" cellpadding="0" cellspacing="0" width="468" height="60" background="{*special_img_url*}affimage2.gif"&gt;
  &lt;tr&gt;
    &lt;td width="20"&gt;&amp;nbsp;&lt;/td&gt;
    &lt;td&gt;
      &lt;a href="{*sign_up_url*}"&gt;
        &lt;b&gt;&lt;font face="Verdana" size="2" color="#FFFFFF"&gt;Earn money, JOIN NOW!&lt;/font&gt;&lt;/b&gt;
      &lt;/a&gt;
    &lt;/td&gt;
  &lt;/tr&gt;
  &lt;tr&gt;
    &lt;td width="20"&gt;&amp;nbsp;&lt;/td&gt;
    &lt;td&gt;&lt;b&gt;&lt;font face="Verdana" size="2" color="#FFFFFF"&gt;Looking for a new guide, find it . . . . .&lt;/font&gt;&lt;/b&gt;&lt;/td&gt;
  &lt;/tr&gt;
  &lt;tr&gt;
    &lt;td width="20"&gt;&amp;nbsp;&lt;/td&gt;
    &lt;td&gt;
      &lt;input type="text" name="keywords" size="30" /&gt;
      &lt;input type="submit" name="submit" value="Search" /&gt;
    &lt;/td&gt;
  &lt;/tr&gt;
  &lt;/table&gt;
&lt;/form&gt;
    </textarea>
  </td>
</tr>

<tr><td><br /><hr /><br /></td></tr>

<tr>
<td>
    <b>Note:</b> You can use a seach box that call the &quot;I'm feeling luck&quot; function. Just add &lt;input type="hidden" name="lucky" value="1" /&gt; line between &lt;form ...&gt; and &lt;/form&gt; tags<br />
  	<b>Code</b>:
    <br />
    <textarea readonly rows="6" cols="90">
&lt;form ...&gt;
  ...
  &lt;input type="hidden" name="lucky" value="1" /&gt;
  ...
&lt;/form&gt;
    </textarea>
</td>
</tr>
</table>