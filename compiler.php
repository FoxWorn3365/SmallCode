<?php
// +-----------------------------------+
// |          SmallCode v1             |
// +-----------------------------------+
// | Author: FoxWorn3365               |
// | Email: foxworn3365@gmail.com      |
// | License: MIT                      |
// | github.com/FoxWorn3365/SmallCode  |
// +-----------------------------------+

namespace FoxWorn3365;

class SmallCode {
  public string $module;
  protected bool $inizializedIf = false;
  protected bool $startedIf = false;
  protected bool $ifEsau = false;
  protected bool $inLoop = false;
  protected int $countLoop = 0;
  protected $loopEach;
  protected $loopOpenLine = array();
  protected $loopMax;
  protected bool $loopOpen = false;
  protected $loopElement;
  protected $index = 0;
  protected $config;
  protected $spaceTracker = array();
  protected $tempVar;
  protected $methods = array();
  protected string $returnCallFunction;
  protected string $activeMethodDefinition;
  protected $execMethod;
  protected bool $nextStop = false;
  protected bool $calledFromMethodClass = false;

  public function __construct($module) {
    $this->module = $module;
    if (!empty($config)) {
      $this->config = $config;
    }
  }

  protected function isSingleQuoted($string) {
    $a = explode("'", $string);
    if (empty($a[0]) && empty($a[count($a)-1])) {
      return true;
    }
    return false;
  }

  protected function isDoubleQuoted($string) {
    $a = explode('"', $string);
    if (empty($a[0]) && empty($a[count($a)-1])) {
      return true;
    }
    return false;
  }

  
  protected function isString($string) {
    if ($this->isSingleQuoted($string) || $this->isDoubleQuoted($string)) {
      return true;
    }
    return false;
  }

  protected function defineGetVar($v) {
    $this->tempVar = $v; 
  }

  protected function get($string, $var = false) {
    if (!$var) {
      $var = $this->tempVar;
    }
    if ($this->isString($string)) {
      return $this->cs($string);
    } elseif (!$this->isString($string) && stripos($string, 'method ') !== false) {
      $this->tempVar = $this->parseMethod(str_replace('method ', '', $string), $var, 'tempFoxReturnNotNull');
      return $this->tempVar['tempFoxReturnNotNull'];
    } elseif (!$this->isString($string) && stripos($string, 'method ') === false) {
      if ($this->calledFromMethodClass) {
        return $string;
      } else {
        return $var[$string];
      }
    } else {
      return NULL;
    }
  }

  protected function cs($string) {
    return str_replace("'", "", $string);
  }

  protected function parseMethod($string, $var, $setVar = 'tempFoxIntReturnNull') {
    $this->calledFromMethodClass = true;
    $this->defineGetVar($var);
    $str = explode('(', $string);
    $m = explode('.', $str[0]); 
    $sendArg = explode(')', $str[1]);
    $ccc = count($sendArg);
    $sas = $sendArg[$ccc-2];
    $arg = explode(', ', $sas);
    if ($m[0] == 'array') {
      // LAVORIAMO CON GLI ARRAY
      if ($m[1] == 'getValue' || $m[1] == 'get') {
        // SINTASSI: array.getValue(<ARRAY>, <VALUE NAME>)
        $var[$setVar] = $var[$this->get($arg[0])][$this->get($arg[1])];
      } elseif ($m[1] == 'push') {
        // Recuperiamo subito le funzioni
        $var[$this->get($arg[0])][$this->get($arg[1])] = $this->get($arg[2]);
      } elseif ($m[1] == 'drop') {
        // Recuperiamo subito le funzioni
        $var[$this->get($arg[0])] = NULL;
      } elseif ($m[1] == 'count') {
        // Recuperiamo subito le funzioni
        $var[$setVar] = count($var[$this->get($arg[0])]);
      } elseif ($m[1] == 'print') {
        foreach ($var[$this->get($arg[0])] as $key => $element) {
          echo "$key => $element ";
        }
      }
    } elseif ($m[0] == 'loop') {
      if ($m[1] == 'getValue') {
        $lCount = 0;
        foreach ($this->loopElement as $key => $va) {
          if ($lCount == $this->loopEach) {
            if ($this->get($arg[0]) == 'key') {
              $var[$setVar] = $key;
            } elseif ($this->get($arg[0]) == 'value') {
              $var[$setVar] = $va;
            }
          }
          $lCount++;
        }
      }
    } elseif ($m[0] == 'file') {
      if ($m[1] == 'open' || $m[1] == 'get') {
        $var[$setVar] = file_get_contents($this->get($arg[0]));
      } elseif ($m[1] == 'write' || $m[1] == 'set') {
        file_put_contents($this->get($arg[0]), $this->get($arg[1]));
      }
    } elseif ($m[0] == 'json') {
      if ($m[1] == 'import') {
        $var[$this->get($arg[0])] = (array)json_decode($var[$this->get($arg[0])]);
      } elseif ($m[1] == 'export') {
        $var[$this->get($arg[0])] = json_encode($var[$this->get($arg[0])]);
      } elseif ($m[1] == 'getValue') { 
        $var[$setVar] = $var[$this->get($arg[0])][$this->get($arg[1])];
      }
    } elseif ($m[0] == 'mysql') {
      if ($m[1] == 'connect') {
        $var[$setVar] = new mysqli($this->get($arg[0]), $this->get($arg[1]), $this->get($arg[2]), $this->get($arg[3]));
      } elseif ($m[1] == 'checkConnection') {
        if ($this->get($arg[0])) {
          $var[$setVar] = $var[$com]->connect_error;
        }
      } elseif ($m[1] == 'cmd' || $m[1] == 'parse' || $m[1] == 'query') {
        $res = $var[$this->get($arg[0])]->query($this->get($arg[0]));
        $returnArray = array();
        if ($result->num_rows > 0) {
          $count = 0;
          while($row = $result->fetch_assoc()) {
            $count++;
            $returnArray[$count] = $row;
          }
        } else {
          $returnArray[0] = false;
        }
        $var[$setVar] = $returnArray;
      }
    } elseif ($m[0] == 'HTTP') {
      if ($m[1] == 'get') {
        $var[$setVar] = file_get_contents($var[$this->get($arg[0])]);
      } elseif ($m[1] == 'post') {
        $body = http_build_query($arg[1]);
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => $body
            )
        );
        $context = stream_context_create($options);
        $var[$setVar] = file_get_contents($this->get($arg[0]), false, $context);
      }
    } elseif ($m[0] == 'session' && $m[1] == 'manager') {
      if ($m[2] == 'inizialize' || $m[2] == 'initialize') {
        session_start();
      } elseif ($m[2] == 'kill') {
        session_destroy();
      } elseif ($m[2] == 'set' || $m[2] == 'define') {
        $_SESSION[$this->get($arg[0])] = $this->get($arg[1]);
      } elseif ($m[2] == 'get') {
        $var[$setVar] = $_SESSION[$this->get($arg[0])];
      } elseif ($m[2] == 'check') {
        if (empty($_SESSION[$this->get($arg[0])])) {
          header("Location: " . $this->get($arg[1]));
        }
      }
    } elseif ($m[0] == 'redirect') {
      header("Location: " . $this->get($arg[0]));
    } elseif ($m[0] == 'globals') {
      if ($m[1] == 'set' || $m[1] == 'define') {
        $GLOBALS[$this->get($arg[0])] = $this->get($arg[1]);
      } elseif ($m[1] == 'get') {
        $var[$setVar] = $GLOBALS[$this->get($arg[0])];
      }
    } elseif ($m[0] == 'hash') {
      if ($m[1] == 'combine') {
        $var[$setVar] = hash($this->get($arg[0]), $this->get($arg[1]));
      }
    } elseif ($m[0] == 'random') {
      $var[$setVar] = rand($this->get($arg[0]),  $this->get($arg[1]));
    } elseif (in_array(implode('.', $m), (array)$this->methods)) {
      $var = $this->callCustomMethod(implode('.', $m), $var, $arg);
    }
    $this->calledFromMethodClass = false;
    return $var;
  }

  protected function getInternalMethodFunction($method, $name) {
    return $this->methods->list->{$method}->args->{$name};
  }

  protected function callCustomMethod($method, $var, $arg) {
    $this->execMethod = (object)array('method' => $method, 'started' => true);
    $m = $this->methods->list->{$method}; 
    // System mapping
    $protectedCounting = 0;
    foreach ($m->args as $ar => $key) {
      $m->args->{$ar} = $var[$this->get($arg[$protectedCounting])];
      $protectedCounting++;
    }
    $this->execMethod->startLine = $m->start;
    $this->execMethod->endLine = $m->end;
    $this->returnCallFunction = $this->index;
    $this->index = ($m->start - 1); 
  }

  protected function unificateString($arr, $v) {
    $string;
    for ($a = $v; $a < count($arr); $a++) {
      $string .= $arr[$a] . ' ';
    }
    return $string;
  }

  protected function log($msg, $i = 0) {
    if ($i == 1) {
      $a = '[#ERROR]';
    } elseif ($i == 2) {
      $a = '[#LOG]';
    } else {
      $a = '[#INFO]';
    }
   
    file_put_contents('parsecode.log', file_get_contents('parsecode.log') . "\n[FoxCloud]|[ParseCode]{$a}: {$msg}");
  }

  public function returnFormattedOutput($values = '') {
    // Impostiamo subito un valore
    $this->methods = (object)array('enabled' => true, 'list' => (object)array());
    $m = file_get_contents($this->module);
    if (empty($m)) {
      echo "ParseCode ERROR: Module {$this->module} doesn't exists";
      exit;
    }
    // Impostiamo delle variabili iniziali
    $return = '';
    $started = false;
    $finished = false;
    $moduleInfoStart = false;
    $moduleInfoFinish = false;
    $start = false;
    $moduleInfo;
    $progen;
    $ll;
    $nextNot;
    $progenEnd = false;
    $parseTh = true;
    $var = array();
    // Procediamo con la sintassi
    $rows = preg_split('/\r\n|\r|\n/', $m);
    // Procediamo con il parsing
    for ($this->index = 0; $this->index < count($rows); $this->index++) {
      if (stripos($rows[$this->index], ' ') !== false) {
        $this->spaceTracker[$this->index] = substr_count(explode('<', $rows[$this->index])[0], ' ');
      }
      $row = str_replace('  ', '', $rows[$this->index]);
      $row = str_replace('||', '  ', $row);
      $ll = explode(' ', $row);
      $line = ($this->index + 1);
      if ($ll[0] == "//" || $ll[0] == "#") {
        continue;
      }

      if (!empty($this->activeMethodDefinition) && $ll[0] != 'end') {
        continue;
      }

      if (!$start && $row == 'Parsing:()[') {
        $start = true;
      }
 
      if ($this->nextStop) {
        $this->index = $this->returnCallFunction+2;
        $this->execMethod = '';
        $this->nextStop = false;
        continue;
      }


      if (is_object($this->execMethod) && $this->execMethod->started && $rows[$this->index+1] == 'end') {
        $this->nextStop = true;
      }

      if ($start && $row == ']//,') {
        $start = false;
        continue;
      }

      if ($start) {
        if ($start && !$this->inizializedIf) {
          $parseTh = true;
        } elseif ($start && $this->inizializedIf && $this->startedIf) {
          $parseTh = true;
        } else {
          $parseTh = false;
        }

        if ($parseTh) {
          if ($this->startedIf && (explode(' ', $rows[$line])[0] == 'but' || explode(' ', $rows[$line])[0] == 'or')) {
            $this->startedIf = false;
            $this->inizializedIf = true;
            $this->ifEsau = true;
          }

          // inizio parsing
          if ($ll[0] == 'import') {
            $nameVar = $ll[1];
            if ($ll[2] == "from") {
              if ($ll[3] == "module") {
                $m = explode('.', $ll[4]);
                if ($m[1] == "input") {
                  // Importiamo dal modulo
                  $var[$nameVar] = $values[$m[2]];
                } elseif ($m[1] == 'url') {
                  if ($m[2] == 'post') {
                    if (empty($m[3])) {
                      $var[$nameVar] = (array)$_POST;
                    } else {
                      $var[$nameVar] = $_POST[$m[3]];
                    }
                  } elseif ($m[2] == 'get') {
                   if (empty($m[3])) {
                      $var[$nameVar] = (array)$_GET;
                    } else {
                      $var[$nameVar] = $_GET[$m[3]];
                    }
                  } elseif ($m[2] == 'server') {
                   if (empty($m[3])) {
                      $var[$nameVar] = (array)$_SERVER;
                    } else {
                      $var[$nameVar] = $_SERVER[$m[3]];
                    }
                  }
                }  
              } elseif ($ll[3] == "file" || $ll[3] == 'HTTP') {
                $var[$nameVar] == file_get_contents($ll[4]);
              } elseif ($ll[3] == 'method' && $ll[4] == 'value') {
                $m = $this->execMethod->method;
                $var[$nameVar] = $this->getInternalMethodFunction($m, $ll[5]);
                var_dump($var);
              }
            }
          } elseif ($ll[0] == "export") {
            $v = $var[$ll[1]];
            if ($ll[2] == "to") {
              if ($ll[3] == "file") {
                file_put_contents($ll[4], $var[$ll[1]]);
              } elseif ($ll[3] == "HTTP") {
                $res = file_get_contents(str_replace("{var}", $var[$ll[1]], $ll[4]));
                if ($ll[5] == "saveTo") {
                  if ($ll[6] == "file") {
                    file_put_contents($ll[7], $res);
                  } elseif ($ll[6] == "var") {
                    $var[$ll[7]] = $res;
                  }
                }
              }
            }
          } elseif ($ll[0] == "define") {
            if ($ll[2] == "string") {
              $str = explode("'", $row);
              $var[$ll[1]] = $str[1];
            } elseif ($ll[2] == "var") {
              $var[$ll[1]] = $var[$ll[2]];
            } elseif ($ll[2] == "array") {
              $a = array();
              $b = explode("|", $row);
              $b = explode(" && ", $b[1]);
              foreach ($b as $val) {
                $val = explode(' of ', $val);
                if ($this->isString($val[1])) {
                  $a[$val[0]] = explode("'", $val[1])[1];
                } else {
                  $a[$val[0]] = $var[$val[1]];
                }
              }
              $var[$ll[1]] = $a;
            } elseif ($ll[2] == 'json') {
              $a = explode('{', $row);
              $b = explode('}', $row);
              $str = str_replace($a[0], '', str_replace(end($b), '}', $row));
              $var[$ll[1]] = json_decode($str, true);
            } elseif ($ll[2] == 'int') {
              $var[$ll[1]] = $ll[3];
            }
          } elseif ($ll[0] == "get") {
            if ($ll[2] == "from") {
              if ($ll[3] == "method") {
                $vv = explode(' ', $row)[1];
                $var = $this->parseMethod($this->unificateString($ll, 4), $var, $vv);
              } elseif ($ll[3] == 'array') {
                $arr = $ll[4];
                $sys = explode('.', $arr);
                $var[$ll[1]] = $var[$sys[0]][$sys[1]];
              }
            }
          } elseif ($ll[0] == 'make') {
            if ($ll[1] == 'method') {
              // make method test.lol (var1, var2, var3) {
              $name = $ll[2];
              $values = explode(')', explode('(', $row)[1])[0];
              $arg = explode(', ', $values);
              $this->methods->list->{$name} = (object)array('type' => 'method_main1.0');
              $this->methods->list->{$name}->definitionRowContent = $rows[$this->index];
              $this->methods->list->{$name}->start = $this->index+1;
              $this->methods->list->{$name}->args = (object)array();
              foreach ($arg as $key) {
                $this->methods->list->{$name}->args->{$key} = 'NULL';
              }
              $this->methods->list->{$name}->ended = false;
              $this->activeMethodDefinition = $name;
            }
          } elseif ($ll[0] == 'end') {
            if (!empty($this->activeMethodDefinition) && !$this->methods->list->{$this->activeMethodDefinition}->ended) {
               $this->methods->list->{$this->activeMethodDefinition}->end = $this->index-1;
               $this->methods->list->{$this->activeMethodDefinition}->ended = true;
               $this->activeMethodDefinition = '';
            }
            continue;
          } elseif ($ll[0] == 'take') {
            $arr = $ll[2];
            $sys = explode('.', $arr);
            $var[$ll[1]] = $var[$sys[0]][$sys[1]];
          } elseif ($ll[0] == 'put') {
            $arr = $ll[1];
            $sys = explode('.', $arr);
            $str = explode("'", $row)[1];
            if (!$this->isStrig($ll[1])) {
              $var[$sys[0]][$sys[1]] = $var[$ll[1]];
            } else {
              $var[$sys[0]][$sys[1]] = $this->cs($str[1]);
            }
          } elseif ($ll[0] == "combine") {
            $a = explode('(', $row);
            $b = explode(')', $a[1])[0];
            $c = explode(', ', $b);
            $text = explode(']', explode('[', $row)[1])[0];
            $count = 0;
            foreach ($c as $el) {
              $count++;
              $text = str_replace('{' . $count . '}', $var[$el], $text);
            }
            $z = explode(' then ', $row);
            $var[$z[1]] = $text;
          } elseif ($ll[0] == "replace") {
            $text = explode(']', explode('[', $row)[1])[0];
            $tempRow = $row;
            foreach ($var as $el => $val) {
              if (!is_array($val)) {
                $text = str_replace('{' . $el . '}', $val, $text);
              }
            }
            $z = explode(' then ', $tempRow);
            $var[$z[1]] = $text;
          } elseif ($ll[0] == "method") {
            $var = $this->parseMethod(str_replace('method ', '', $row), $var);
          } elseif ($ll[0] == "->" || $ll[0] == 'return') {
            // Return statement
            if ($ll[1] == "toArray") {
              $returnArray = array();
              $r = explode(' && ', $row);
              $rc = str_replace("-> toArray ", "", $r[0]);
              foreach ($r as $in) {
                $a = explode('=>', $in);
                $b = explode(' ', $a); 
                if ($b[0] == 'string') {
                   $returnArray[$a[0]] = $b[1];
                } elseif ($b[0] == 'var') {
                   $returnArray[$a[0]] = $var[$b[1]];
                }
              }
              return $returnArray;
            } elseif ($ll[1] == "toString") {
              $return .= explode("'", $row)[1];
            } elseif ($ll[1] == "toVar") {
              $return .= $var[$ll[2]];
            }
          } elseif ($ll[0] == 'say') {
            if ($ll[1] == 'string') {
              $return .= explode("'", $row)[1];
            } elseif ($ll[1] == 'var') {
              $return .= $var[$ll[2]];
            }
          } elseif ($ll[0] == 'print') {
            echo $this->get(str_replace('print ', '', $row), $var);
          } elseif ($ll[0] == 'dump') {
            var_dump($this->get(str_replace('print ', '', $row), $var));
          } elseif ($ll[0] == 'call') {
            if (!$this->isString($ll[1])) {
              require $var[$ll[1]];
            } else {
              require explode("'", $row)[1];
            }
          } elseif ($ll[0] == 'parse') {
            if (!$this->isString($ll[1])) {
              $p = new SmallCode($var[$ll[1]]);
            } else {
              $p = new SmallCode(explode("'", $row)[1]);
            }
            if ($ll[2] == 'with') {
              echo $p->returnFormattedOutput($var[$ll[3]]);
            } else {
              echo $p->returnFormattedOutput();
            }
          } elseif ($ll[0] == 'quit') {
            die();
          }
        }

        if ($ll[0] == "catch" && $start && $this->inizializedIf) {
          $this->inizializedIf = false;
        } elseif ($ll[0] == "but" && $this->inizializedIf && !$this->startedIf && !$this->ifEsau && $start) {
           $this->startedIf = true;
           continue;
        } elseif (($ll[0] == "if" || $ll[0] == "or") && $start) {
          if ($ll[0] == "or") {
            $this->startedIf = false;
          }
          if ($this->isString($ll[3])) {
            $str = explode("'", $row)[1];
          } else {
            $str = $var[$ll[3]];
          } 
 
          $this->inizializedIf = true;
          if ($ll[2] == 'as' || $ll[2] == 'is') {
            if ($var[$ll[1]] == $str) {
              $this->startedIf = true;
            }
          } elseif ($ll[2] == 'not') {
            if ($var[$ll[1]] != $str) {
              $this->startedIf = true;
            }
          } elseif ($ll[2] == 'maj') {
            if ($var[$ll[1]] > $str) {
              $this->startedIf = true;
            }
          } elseif ($ll[2] == 'min') {
            if ($var[$ll[1]] < $str) {
              $this->startedIf = true;
            }
          } elseif ($ll[2] == 'empty') {
            if (empty($var[$ll[1]])) {
              $this->startedIf = true;
            }
          }
        } elseif ($ll[0] == 'for') {
          if ($ll[1] == 'each') {
            $this->countLoop++;
            // IF FOREACH
            $candidates = explode(')', explode('(', $row)[1])[0];
            $c = explode(' per ', $candidates);
            if (!$this->isString($c[0])) {
              $array = $var[$c[0]];
              // Procediamo con il loop
              $this->loopElement = $array;
              $this->inLoop = true;
              $this->loopEach = 0;
              $this->loopOpen = true;
              $this->loopMax = count($array);
              $this->loopOpenLine[$this->countLoop] = $this->index;
            }
          }
        } elseif ($ll[0] == 'break') {
          $this->loopOpen = false;
        }
      } else {
        if ($this->spaceTracker[$this->index] !== false) {
          for ($i = 0; $i < $this->spaceTracker[$this->index]; $i++) {
            echo ' ';
          }
        }
        echo "{$row}\n";
      }
      
      if ($this->inLoop && !$this->loopOpen && $this->loopEach == $this->loopMax - 1) {
        $this->inLoop = false;
      } elseif ($this->inLoop && !$this->loopOpen) {
        $this->loopEach++;
      }
      if ($this->inLoop && !$this->loopOpen && $this->loopEach) {
        $this->index = $this->loopOpenLine[$this->countLoop];
      }
    }
    return $return;
  }
}
