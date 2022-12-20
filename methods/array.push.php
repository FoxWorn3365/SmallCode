<?php require 'assets/header.php'; ?>
   <div id='mth-array' class='doc'>
    <h1>Method: array.push</h1>
    Set values in an array. You can also use <a href='/docs/functions/put'>put</a>.<br>
    <h3>Description</h3>
    <pre>
     <code>
     array.push(
        <a href='/docs/language/array' class='g'>array</a> <lb>inputArray</lb>,
        <a href='/docs/dis/string_var' class='g'>string|var</a> <lb>valueName</lb>,
        <a href='/docs/dis/string_var' class='g'>string|var</a> <lb>valueContent</lb>
     ) <gg>return: null</gg>
     </code>
    </pre>
    <br><br>
    <h3>Example</h3>
    <pre>
     <code>
     define myArray array |hello of 'hello' && uwu of 'mm'|
  
     method array.push(myArray, 'hello', 'no hello :(')
     </code>
    </pre>
   </div>
