<?php require 'assets/header.php'; ?>
   <div id='mth-http' class='doc'>
    <h1>Method: HTTP.post</h1>
    Send a POST request to an URL.<br>
    <h3>Description</h3>
    <pre>
     <code>
     HTTP.post(
        <a href='/docs/dis/string_var' class='g'>string|var</a> <lb>URL</lb>,
        <a href='/docs/language/array' class='g'>array</a> <lb>body</lb>,
        <a href='/docs/language/array' class='g'>array</a> <y>NULLABLE</y> <lb>headers</lb>
     ) <gg>return: <a href='/docs/language/string'>string</a></gg>
     </code>
    </pre>
    <br><br>
    <h3>Example</h3>
    <pre>
     <code>
     define body array |number of '1234567' && name of 'Federico'|
  
     get response from method HTTP.post('https://example.com/contacts/form/post', body)
     </code>
    </pre>
   </div>
