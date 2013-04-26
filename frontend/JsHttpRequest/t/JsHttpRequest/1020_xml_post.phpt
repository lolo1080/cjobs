<div id="TEST">
    <?=basename($_SERVER['REQUEST_URI'])?>: POST method
</div>


<?include "contrib/init.php"?>
<div id="FILE">
    <script>
    doQuery('xml', 'post', 123);
    </script>
</div>


<pre id="EXPECT">
MD5("123") = "202cb962ac59075b964b07152d234b70" 
Zero loading ID: yes 
QUERY_STRING:  
Request method: POST 
Loader used: xml 
Uploaded file size:  
_GET: Array 
( 
) 
_POST: Array 
( 
    [q] => 123 
) 
_FILES: Array 
( 
) 
</pre>


<pre id="EXPECT_noxml" style="display:none">
JsHttpRequest: Cannot use XMLHttpRequest or ActiveX loader: not supported
</pre>