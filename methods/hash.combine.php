<?php require 'assets/header.php'; ?>
   <div id='mth-hash' class='doc'>
    <h1>Method: hash.combine</h1>
    Hash a string with.<br>
    <h3>Description</h3>
    <pre>
     <code>
     hash.combine(
        <a href='/docs/language/string' class='g'>string</a> <lb>hashType</lb> <y>ES: sha512</y>,
        <a href='/docs/dis/string_var' class='g'>string|var</a> <lb>value</lb>
     ) <gg>return: <a href='/docs/language/string'>string</a></gg>
     </code>
    </pre>
    <br><br>
    <h3>Example</h3>
    <pre>
     <code>
     method hash.combine('sha512', 'UWUWUWUWUW');
     </code>
    </pre>
   </div>
