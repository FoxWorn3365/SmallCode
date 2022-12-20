<?php require 'assets/header.php' ?>
   <div id='combine' class='doc'>
    <h1>combine</h1>
    Join variables with strings. Legacy method. You can use <a href='/docs/functions/replace'>replace</a> because it's faster, more clean and simple<br><br>
    <h2>Example:</h2>
    <code>
    combine (<a href='/docs/language/var'>[VAR] (var 1)</a> <a href='/docs/language/var'>[VAR] (var 2)</a>) [<a href='/docs/language/string'>[STRING]</a> (with placeholder in numerical order (like {1} equal to the first var in (VAR1, VAR2, VAR3...).))] then <a href='/docs/language/var'>[VAR]</a> (result) 
    </code>
    <br>
    <h3 id='source'>Important</h3>
    <h4>Variables</h4>
    You need to separate each var with a ", " in the var array. There is no limit of variables.
    <h4>Code examples</h4>
    <pre>
     <code>
    // We define some vars
    define food string 'pizza'
    define name string 'federico'

    // Now let's combine!
    combine (food, name) [hello, my name is {1} and i like {0}] then output

    print var output // Result: hello, my name is federico and i like pizza
     </code>
    </pre>
   </div>