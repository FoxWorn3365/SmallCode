<?php require 'assets/header.php' ?>
   <div id='get' class='doc'>
    <h1>get</h1>
    Get a variable from a <a href='/docs/language/method'>method</a>. You can use only for <a href='/docs/language/method'>methods</a>.<br><br>
    <h2>Example:</h2>
    <code>
    get <a href='/docs/language/var'>[VAR]</a> from method <a href='/docs/language/method'>[METHOD]</a>
    </code>
    <br><br>
    <h4>Code examples</h4>
    <pre>
     <code>
    // Import a json
    get importedJson from method json.import(myPureJson)
    
    // Get an array value
    get arrayValue from array myArray.myValue
     </code>
    </pre>
   </div>