<?php require 'assets/header.php'; ?>
   <div id='mth-json' class='doc'>
    <h1>Method: json.getValue</h1>
    Get a value of an array. <r>THIS METHOD IS NOT SAFE AND NOT USEFUL. USE <a href='/docs/functions/take'>take</a> INSTEAD OF THIS</r><br>
    <h3>Description</h3>
    <pre>
     <code>
     json.getValue(
        <a href='/docs/language/array' class='g'>array</a> <lb>arrayOfValue</lb>,
        <a href='/docs/dis/string_var' class='g'>string|var</a> <lb>valueName</lb>
     ) <gg>return: <a href='/docs/language/string'>string</a></gg>
     </code>
    </pre>
    <br><br>
    <h3>Example</h3>
    <pre>
     <code>
     define test array |ilove of 'pizza' && ilike of 'PHP'|

     get whatILike from method json.getValue(test, 'ilike')
     </code>
    </pre>
   </div>
