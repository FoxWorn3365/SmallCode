<?php require 'assets/header.php'; ?>
   <div id='mth-array' class='doc'>
    <h1>Method: array.count</h1>
    Counts the number of elements in an array. Remember it starts at 1.<br>
    <h3>Description</h3>
    <pre>
     <code>
     array.count(
        <a href='/docs/language/array' class='g'>array</a> <lb>inputArray</lb>
     ) <gg>return: <a href='/docs/language/string'>string</a> (number)</gg>
     </code>
    </pre>
    <br><br>
    <h3>Example</h3>
    <pre>
     <code>
     define myArray array |hello of 'hello' && uwu of 'mm'|
  
     get cc from method array.count(myArray)
 
     print var cc   // Return: 2
     </code>
    </pre>
   </div>
