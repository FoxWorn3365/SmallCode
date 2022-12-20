<?php require 'assets/header.php' ?>
   <div id='import' class='doc'>
    <h1>import</h1>
    Import a value from an external method. In most cases this is deprecated.<br><br>
    <h2>Example:</h2>
    <code>
    import <a href='/docs/language/var'>[VAR]</a> from <a href='#source'>[SOURCE-TYPE]</a> [SOURCE-DATA]
    </code>
    <br>
    <h4 id='source'>source</h4>
    <code><a href='/docs/language/module'>module</a></code>: Import the var from a module.<br>
    <code>HTTP</code>: Import var from an HTTP request ([SOURCE-DATA] is the URL).<br>
    <code>file</code>: Import var from a file (read content) ([SOURCE-DATA] is the DIR).<br><br><br>
    <h4>Code examples</h4>
    <pre>
     <code>
    // Import a var from a GET value called "test"
    import myVar from module module.url.get.test
    
    // Import a var from file 'text.uwu'
    import myVar from file text.uwu

    // Import a var from HTTP request
    import myVar from HTTP https://example.com/page.html
     </code>
    </pre>
   </div>