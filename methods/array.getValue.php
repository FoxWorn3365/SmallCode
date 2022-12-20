<?php require 'assets/header.php'; ?>
   <div id='mth-array' class='doc'>
    <h1>Method: array.getValue</h1>
    Retrieves a value from an array. You can also use <a href='/docs/functions/take'>take</a>.<br>
    <h3>Description</h3>
    <pre>
     <code>
     array.getValue(
        <a href='/docs/language/array' class='g'>array</a> <lb>inputArray</lb>,
        <a href='/docs/dis/string_var' class='g'>string|var</a> <lb>value</lb>
     ) <gg>return: <a href='/docs/language/string'>string</a></gg>
     </code>
    </pre>
    <br><br>
    <h3>Example</h3>
    <pre>
     <code>
     define myArray array |hello of 'hello' && uwu of 'mm'|
  
     get helloValue from method array.getValue(myArray, 'hello')
     </code>
    </pre>
