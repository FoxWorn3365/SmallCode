<?php require 'assets/header.php'; ?>
   <div id='mth-session' class='doc'>
    <h1>Method: session.manager.check</h1>
    Check if a session exists. If the session entered does not exist, the system will redirect the user to the URL indicated in the second arg.<br>
    <h3>Description</h3>
    <pre>
     <code>
     session.manager.check(
        <a href='/docs/dis/string_var' class='g'>string|var</a> <lb>sessionName</lb>,
        <a href='/docs/language/string' class='g'>string</a> <lb>redirectUrl</lb>
     ) <gg>return: null</gg>
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
