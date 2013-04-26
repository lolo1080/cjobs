<div id="TEST">
    <?=basename($_SERVER['REQUEST_URI'])?>: prototype integration (form element)
</div>

<?include "contrib/init.php"?>
<script language="JavaScript" src="contrib/prototype.js"></script>
<script type="text/javascript" language="JavaScript" src="../../lib/JsHttpRequest/JsHttpRequest-prototype.js?<?=time()?>"></script>

<div id="FILE">
    <script>
    new Ajax.Request('contrib/loader.php', {
        parameters: { e: form().e_text },
        onSuccess: function(transport) {
            JsTest.write('text:\n' + transport.responseText);
            JsTest.write('md5: ' + transport.responseJS.md5);
            JsTest.analyze();
        }
    });    
    </script>
</div>

<pre id="EXPECT">
text: 
QUERY_STRING:  
Request method: POST 
Loader used: form 
Uploaded file size:  
_GET: Array 
( 
) 
_POST: Array 
( 
    [e] => abcd 
) 
_FILES: Array 
( 
) 
 
md5: d41d8cd98f00b204e9800998ecf8427e
</pre>