<?php require 'assets/header.php' ?>
   <div id='export' class='doc'>
    <h1>export</h1>
    Export a variable to a selected source. In most cases this is deprecated.<br><br>
    <h2>Example:</h2>
    <code>
    export <a href='/docs/language/var'>[VAR]</a> to <a href='#source'>[SOURCE-TYPE]</a> [SOURCE-DATA]
    </code>
    <br>
    <h4 id='source'>source</h4>
    <code>HTTP</code>: Export var with an HTTP request ([SOURCE-DATA] is the URL - you can use in the url {var} and this placeholder will be replaced with the exported var).<br>
    <code>file</code>: Export var to a file (write file) ([SOURCE-DATA] is the DIR).<br><br><br>
    <h4>Code examples</h4>
    <pre>
     <code>
    // Export a var to an HTTP url
    export myVar to HTTP https://example.com/api/v1/?name={var}
    
    // Export a var to file 'text.uwu'
    export myVar to file text.uwu
     </code>
    </pre>
   </div>