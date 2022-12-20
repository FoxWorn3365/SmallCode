<?php require 'assets/header.php'; ?>
   <div id='mth-http' class='doc'>
    <h1>Method: HTTP.get</h1>
    Get content with an HTTP GET request. You can also use <a href='/docs/functions/import'>import</a> but it is unsafe and useless.<br>
    <h3>Description</h3>
    <pre>
     <code>
     HTTP.get(
        <a href='/docs/dis/string_var' class='g'>string|var</a> <lb>URL</lb>
     ) <gg>return: <a href='/docs/language/string'>string</a></gg>
     </code>
    </pre>
    <br><br>
    <h3>Example</h3>
    <pre>
     <code>
     get res from method HTTP.get('https://example.com/weather/today')
     </code>
    </pre>
   </div>
