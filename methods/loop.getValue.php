<?php require 'assets/header.php'; ?>
   <div id='mth-loop' class='doc'>
    <h1>Method: loop.getValue</h1>
    Retrieves the basic elements of a loop such as key and value.<br>
    <h3>Description</h3>
    <pre>
     <code>
     loop.getValue(
        <a href='/docs/language/string' class='g'>string</a> <y>key|value</y>
     ) <gg>return: <a href='/docs/language/string'>string</a></gg>
     </code>
    </pre>
    <br><br>
    <h3>Example</h3>
    <pre>
     <code>
     define myArray array |hello of 'hello' && uwu of 'mm'|
  
     for each (myArray per type) then
       get value from method loop.getValue('value') // Return (in the first iteration): hello, in the second: mm
     break
     </code>
    </pre>
   </div>
