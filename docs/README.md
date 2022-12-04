# SmallCode - Documentation
The official documentation & code specification for SmallCode

## Variables management
SmallCode is a mildly typed language and you always need to specify what type the variable is.<br>
Obviously it is possible to use the `auto` constant which calls an auto-detector to decide how to treat it.
There are 3 ways to work with variables:
### Define
```
define a string 'mamma mia marcello'            // In this case we define the variable "a" as a string
define b var a                                  // In this case we define the variable "b" = "a"
define c array |1 of 'hello' && 2 of 'jack'|    // In this case we define the variable "c" as an associative array. There are no other types of arrays
```
### Import
```
import a from module module.input.value         // We import the variable "a" from an array given at the start (in this case the "value" of the array)
import b from file version.txt                  // We import the variable "b" from the file "version.txt"
import c from HTTP https://example.com          // We import the variable "c" from an HTTP request
```
### Export
```
export a to file version.txt                    // We export the variable "a" to the file "version.txt"
export b to HTTP https://example.com?name={var} // We export the variable "b" to an HTTP request where {var} is "b"
export c to HTTP https://a.it?n={var} toVar b   // In this case we export the variable "c" to HTTP and save the response in the var b
```
### Inizialitation
It is not necessary to initialize the variables but the associative arrays yes.

## Statements
If, else, elseif
```
define a string 'uwu baka'
define b string 'major'

es a as ''uwu-baka'' so       // a == 'uwu-baka'
  // do something but isn't true
or a as ''uwu baka'' so       // a == 'uwu baka'
  // do something
but
  // do something if "if" and "elseif" are not true
catch  // Finish the statement
```
### Equal
```
es <VAR> as <TEXT> so
```
```
es <VAR> as <VAR> so
```
### Disequal
```
es <VAR> not <TEXT> so
```
```
es <VAR> not <VAR> so
```
### Higher
```
es <VAR> maj <TEXT> so
```
```
es <VAR> maj <VAR> so
```
### Lower
```
es <VAR> min <TEXT> so
```
```
es <VAR> min <VAR> so
```

## Managing Arrays
```
define b string 'lol'
define a array |1 of 'banana' && 2 of b|

// Getting array values
// METHOD
get var from function program.getArrayValue(a, 'value')
// Classic
get var from array a.1

// Pushing arrays
program.pushArrayValue(a, 'value', 'content')

// Dropping array
program.dropArray(a)
```
