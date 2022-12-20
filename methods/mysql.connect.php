<?php require 'assets/header.php'; ?>
   <div id='mth-mysql' class='doc'>
    <h1>Method: msyql.connect</h1>
    Connect to a MySQL database.<br>
    <h3>Description</h3>
    <pre>
     <code>
     mysql.connect(
        <a href='/docs/language/string' class='g'>string</a> <lb>dbHost</lb>,
        <a href='/docs/language/string' class='g'>string</a> <lb>dbUsername</lb>,
        <a href='/docs/language/string' class='g'>string</a> <lb>dbPassword</lb>,
        <a href='/docs/dis/string_var' class='g'>string|var</a> <lb>dbDatabase</lb>
     ) <gg>return: (object)<a href='/docs/language/array'>array</a> <lb>connection</lb></gg>
     </code>
    </pre>
    <br><br>
    <h3>Example</h3>
    <pre>
     <code>
     get mysqlConn from method mysql.connect('localhost', 'fox', 'mypassword', 'myDatabase')
     </code>
    </pre>
   </div>
