<?php require 'assets/header.php'; ?>
   <div id='mth-mysql' class='doc'>
    <h1>Method: msyql.checkConnection</h1>
    Check the connection with MySQL.<br>
    <h3>Description</h3>
    <pre>
     <code>
     mysql.checkConnection(
        <a href='/docs/language/array' class='g'>array (object)</a> <lb>mysqlConnection</lb>
     ) <gg>return: <a href='/docs/language/string'>array</a> <lb>status</lb></gg>
     </code>
    </pre>
    <br><br>
    <h3>Example</h3>
    <pre>
     <code>
     get mysql from method mysql.connect('localhost', 'fox', 'mypassword', 'myDatabase')

     get mysqlIsConn from method mysql.checkConnection(mysql)
     </code>
    </pre>
   </div>
