<?php require 'assets/header.php'; ?>
   <div id='mth-mysql' class='doc'>
    <h1>Method: msyql.query</h1>
    Send a command to MySQL.<br>
    <h3>Description</h3>
    <pre>
     <code>
     mysql.query(
        <a href='/docs/language/array' class='g'>array (object)</a> <lb>mysqlConnection</lb>,
        <a href='/docs/dis/string_var' class='g'>string|var</a> <lb>query</lb>
     ) <gg>return: <a href='/docs/language/string'>string</a> <lb>result</lb></gg>
     </code>
    </pre>
    <br><br>
    <h3>Example</h3>
    <pre>
     <code>
     get mysqlConn from method mysql.connect('localhost', 'fox', 'mypassword', 'myDatabase')

     get res from method mysql.query(mysqlConn, 'CREATE DATABASE users;')
     </code>
    </pre>
   </div>
