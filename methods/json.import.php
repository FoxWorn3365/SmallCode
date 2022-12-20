<?php require 'assets/header.php'; ?>
   <div id='mth-json' class='doc'>
    <h1>Method: json.import</h1>
    Import a JSON transforming it into an array.<br>
    <h3>Description</h3>
    <pre>
     <code>
     json.import(
        <a href='/docs/language/string' class='g'>string</a> <r>only formatted json</r> <lb>jsonInString</lb>
     ) <gg>return: <a href='/docs/language/array'>array</a></gg>
     </code>
    </pre>
    <br><br>
    <h3>Example</h3>
    <pre>
     <code>
     define test string '{"ilove":"pizza", "ilike":"PHP"}'

     get arrayJson from method json.import(test)
     </code>
    </pre>
   </div>
