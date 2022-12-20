<?php require 'assets/header.php' ?>
   <div id='replace' class='doc'>
    <h1>replace</h1>
    Replace variables placeholder with var in a string.<br><br>
    <h2>Example:</h2>
    <code>
    replace [<a href='/docs/language/string'>[STRING]</a> (with var name placeholder like {test})] then <a href='/docs/language/var'>[VAR]</a> (output)
    </code>
    <br>
    <h3 id='source'>Using placeholder</h3>
    Unlike the "combine" method, this method allows you to directly use the variable names as placeholders, so if you put {helo} it will be replaced with the value of the variable helo (if it exists).
    <h4>Code examples</h4>
    <pre>
     <code>
    // We define some vars
    define food string 'pizza'
    define name string 'federico'

    // Now let's replace!
    replace [hello, my name is {name} and i like {food}] then result

    print result  // Result: hello, my name is federico and i like pizza
     </code>
    </pre>
   </div>