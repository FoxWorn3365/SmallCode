<?php require 'assets/header.php'; ?>
   <div id='lan-arr' class='doc'>
    <h1>Language: Array</h1>
    SmallCode only offers defining and using, if not imported with a json, associative arrays (which can still be iterated though).<br>
    <h3>Syntax of an array</h3>
    We remind you that the values ​​are not bound by a maximum number but are almost infinite.<br>
    <code>define myArray array |ciao of 'myNameIsFederico' && test of 'myNameIsBoh' && uwu of var|</code><br>
    This would show up in json as:<br>
    <code>{"ciao":"myNameIsFederico", "test":"myNameIsBoh", "uwu":"[VAR VALUE]"}</code><br><br>
    So we can immediately generalize:<br>
    <code>|[VALUE (string without ')] of [VALUE (string|var)] && [VALUE (STRING)] of [VALUE (string|var)] && .... |</code>
    <h3>Access</h3>
    To access an array we recommend the <a href='/docs/functions/put'>put</a> function to insert a value and <a href='/docs/functions/take'>take</a> to extract it
   </div>