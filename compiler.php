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
  public $smallcode = array('version' => '0.9', 'author' => 'FoxWorn3365', 'github' => 'https://github.com/FoxWorn3365/SmallCode', 'website' => 'https://smallcode.cf');
  public $config = array('events' => array('timeout' => 10, 'checkspeed' => 0.2));
  public string $module;
  protected $if;
  protected $istances = array();
  protected $index = 0;
  protected $spaceTracker = array();
  protected $tempVar;
  protected $methods = array();
  protected string $returnCallFunction;
  protected string $activeMethodDefinition;
  protected $execMethod;
  protected bool $nextStop = false;
  protected bool $calledFromMethodClass = false;
  protected $single = array();
  protected $variableMethodCall = array();
  protected $eventsActive = array();
  protected $loop = array();

  public function __construct($module) {
    $this->module = $module;
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

  protected function get($string, $var = false, $internal = true) {
    if (!$var) {
      $var = $this->tempVar;
    }
    if ($this->isString($string)) {
      return $this->cs($string);
    } elseif (!$this->isString($string) && stripos($string, 'method ') !== false) {
      $this->tempVar = $this->parseMethod(str_replace('method ', '', $string), $var, 'tempFoxReturnNotNull');
      return $this->tempVar['tempFoxReturnNotNull'];
    } elseif (!$this->isString($string) && stripos($string, 'method ') === false) {
      if ($this->calledFromMethodClass && $internal) {
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
    $this->calledFromMethodClass = false;
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
        $this->calledFromMethodClass = true;
        // SINTASSI: array.getValue(<ARRAY>, <VALUE NAME>)
        $var[$setVar] = $var[$this->get($arg[0])][$this->get($arg[1])];
      } elseif ($m[1] == 'push') {
        $this->calledFromMethodClass = true;
        // Recuperiamo subito le funzioni
        $var[$this->get($arg[0])][$this->get($arg[1], false, false)] = $var[$this->get($arg[2])];
      } elseif ($m[1] == 'drop') {
        // Recuperiamo subito le funzioni
        $var[$this->get($arg[0])] = NULL;
      } elseif ($m[1] == 'count') {
        // Recuperiamo subito le funzioni
        $var[$setVar] = count($var[$this->get($arg[0])]);
      } elseif ($m[1] == 'print') {
        foreach ($this->get($arg[0]) as $key => $element) {
          echo "$key => $element ";
        }
      }
    } elseif ($m[0] == 'loop') {
      if ($m[1] == 'getValue') {
        if ($arg[0] == 'value' || $arg[0] == "'value'") {
          $var[$setVar] = $this->loop->extractArgumentFromActiveLoop->value;
        } elseif ($arg[0] == 'key' || $arg[0] == "'key'") {
          $var[$setVar] = $this->loop->extractArgumentFromActiveLoop->key;
        }
      } elseif ($m[1] == 'localStorage') {
        if ($m[2] == 'set') {
          $this->loop->localStorage->{$this->get($arg[0])} = $this->get($arg[1]);
        } elseif ($m[2] == 'get') {
          $var[$setVar] = $this->loop->localStorage->{$this->get($arg[0])};
        } elseif ($m[2] == 'merge') {
          foreach ($this->loop->localStorage as $v) {
            $var[$v] = $this->loop->localStorage->{$v};
          }
        }
      }
    } elseif ($m[0] == 'file') {
      if ($m[1] == 'open' || $m[1] == 'get') {
        $var[$setVar] = file_get_contents($this->get($arg[0]));
      } elseif ($m[1] == 'write' || $m[1] == 'set') {
        file_put_contents($this->get($arg[0]), $this->get($arg[1]));
      } elseif ($m[1] == 'delete' || $m[1] == 'unlink') {
        @unlink($this->get($arg[0]));
      } elseif ($m[1] == 'copy') {
        copy($this->get($arg[0]), $this->get($arg[1]));
      } elseif ($m[1] == 'rename' || $m[1] == 'move') {
        rename($this->get($arg[0]), $this->get($arg[1]));
      }
    } elseif ($m[0] == 'dir' || $m[0] == 'directory') {
      if ($m[1] == 'create') {
        @mkdir($this->get($arg[0]));
      } elseif ($m[1] == 'delete' || $m[1] == 'remove') {
        rmdir($this->get($arg[0]));
      } elseif ($m[1] == 'scan') {
        $var[$setVar] = scandir($this->get($arg[0]));
      } elseif ($m[1] == 'get') {
        $var[$setVar] = glob($this->get($arg[0]));
      }
    } elseif ($m[0] == 'storage') {
      if ($m[1] == 'define') {
        $this->istances[$this->get($arg[0])] = array();
      } elseif ($m[1] == 'get') {
        $var[$setVar] = $this->istances[$this->get($arg[0])][$this->get($arg[1])];
      } elseif ($m[1] == 'set') {
        $this->istances[$this->get($arg[0])][$this->get($arg[1])] = $this->get($arg[2]);
      }
    } elseif ($m[0] == 'math') {
      if ($m[1] == 'operation') {
        $operator = $this->get($arg[1]);
        if ($operator == '+' || $operator == 'plus') {
          $var[$setVar] = $this->get($arg[0]) + $this->get($arg[2]);
        } elseif ($operator == '-' || $operator == 'minus') {
          $var[$setVar] = $this->get($arg[0]) - $this->get($arg[2]);
        } elseif ($operator == '*' || $operator == 'per') {
          $var[$setVar] = $this->get($arg[0]) * $this->get($arg[2]);
        } elseif ($operator == '/' || $operator == 'div') {
          $var[$setVar] = $this->get($arg[0]) / $this->get($arg[2]);
        } 
      } elseif ($m[1] == 'complex') {
        $var[$setVar] = eval('return: ' . $this->get($arg[0]));
      }
    } elseif ($m[0] == 'type') {
      if ($m[1] == 'set') {
        $type = $this->get($arg[1]);
        if ($type == 'int') {
          $var[$setVar] = (integer)$var[$arg[0]];
        } elseif ($type == 'bool') {
          $var[$setVar] = (boolean)$var[$arg[0]];
        } elseif ($type == 'string') {
          $var[$setVar] = (string)$var[$arg[0]];
        } elseif ($type == 'array') {
          $var[$setVar] = (array)$var[$arg[0]];
        } elseif ($type == 'object') {
          $var[$setVar] = (object)$var[$arg[0]];
        }
      } elseif ($m[1] == 'get') {
        $var[$setVar] = gettype($var[$arg[0]]);
      }
    } elseif ($m[0] == 'json') {
      $this->calledFromMethodClass = true;
      if ($m[1] == 'import') {
        $var[$this->get($arg[0])] = (array)json_decode($var[$this->get($arg[0])]);
      } elseif ($m[1] == 'export') {
        $var[$this->get($arg[0])] = json_encode($var[$this->get($arg[0])]);
      } elseif ($m[1] == 'getValue') { 
        $var[$setVar] = $var[$this->get($arg[0])][$this->get($arg[1])];
      } elseif ($m[1] == 'getValueFromObject') {
        $var[$setVar] = $var[$this->get($arg[0])]->{$this->get($arg[1])};
      }
    } elseif ($m[0] == 'mysql') {
      if ($m[1] == 'connect') {
        $var[$setVar] = new mysqli($this->get($arg[0]), $this->get($arg[1]), $this->get($arg[2]), $this->get($arg[3]));
      } elseif ($m[1] == 'checkConnection') {
        if ($this->get($arg[0])) {
          $var[$setVar] = $var[$com]->connect_error;
        }
      } elseif ($m[1] == 'cmd' || $m[1] == 'parse' || $m[1] == 'query') {
        $this->calledFromMethodClass = true;
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
    } elseif ($m[0] == 'headers') {
      if ($m[1] == 'set') {
        header($this->get($arg[0]) . ': ' . $this->get($arg[1]));
      } elseif ($m[1] == 'get') {
        $var[$setVar] = $_SERVER['HTTP_'. $this->get($arg[0])];
      }
    } elseif ($m[0] == 'HTTP') {
      if ($m[1] == 'get') {
        $var[$setVar] = file_get_contents($this->get($arg[0]));
      } elseif ($m[1] == 'post') {
        $body = http_build_query($this->get($arg[1]));
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
    } elseif ($m[0] == 'native') {
        $this->smallcode = (object)$this->smallcode;
      if ($m[1] == 'version') {
        $var[$setVar] = $this->smallcode->version;
      } elseif ($m[1] == 'author') {
        $var[$setVar] = $this->smallcode->author;
      } elseif ($m[1] == 'website') {
        $var[$setVar] = $this->smallcode->website;
      } elseif ($m[1] == 'busetto') {
        $var[$setVar] = file_get_contents('https://api.fcosma.it/credits/busetto.txt');
      }
    } elseif ($m[0] == 'linux') {
      if ($m[1] == 'exec') {
        $var[$setVar] = shell_exec($this->get($arg[0]));
      }
    } elseif ($m[0] == 'session' && $m[1] == 'manager') {
      if ($m[2] == 'inizialize' || $m[2] == 'initialize') {
        session_start();
      } elseif ($m[2] == 'kill') {
        session_destroy();
      } elseif ($m[2] == 'set' || $m[2] == 'define') {
        $this->calledFromMethodClass = true;
        $_SESSION[$this->get($arg[0])] = $var[$this->get($arg[1])];
      } elseif ($m[2] == 'get') {
        $this->calledFromMethodClass = true;
        $var[$setVar] = $_SESSION[$this->get($arg[0])];
      } elseif ($m[2] == 'check') {
        if (empty($_SESSION[$this->get($arg[0])])) {
          header("Location: " . $this->get($arg[1]));
        }
      }
    } elseif ($m[0] == 'string') {
      if ($m[1] == 'replace') {
        $var[$setVar] = str_replace($this->get($arg[0]), $this->get($arg[1]), $this->get($arg[2]));
      } elseif ($m[1] == 'split') { 
        $var[$setVar] = explode($this->get($arg[0]), $this->get($arg[1]));
      } elseif ($m[1] == 'join') {
        $var[$setVar] = explode($this->get($arg[0]), $this->get($arg[1]));
      }
    } elseif ($m[0] == 'redirect') {
      header("Location: " . $this->get($arg[0]));
    } elseif ($m[0] == 'globals') {
      if ($m[1] == 'set' || $m[1] == 'define') {
        $this->calledFromMethodClass = true;
        $GLOBALS[$this->get($arg[0])] = $var[$this->get($arg[1])];
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
      $this->callCustomMethod(implode('.', $m), $var, $arg);
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
    return $var;
  }

  protected function callLoopMethod($id) {
    $this->index = $this->loop->methods->{$id}->start;
    $iter = $this->loop->methods->{$id}->for;
    $array = $this->loop->methods->{$id}->array;
    $this->loop->extractArgumentFromActiveLoop->value = $array[$iter];
    $this->loop->extractArgumentFromActiveLoop->key = $array[$iter];
    $this->loop->started = true;
    // continue;
  }

  protected function unificateString($arr, $v) {
    $string = '';
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
    // Avviamo gli object interessati
    $this->methods = (object)array('enabled' => true, 'list' => (object)array());
    $this->single = (object)array();
    $this->variableMethodCall = (object)array();
    $this->eventsActive = (object)array();
    $this->loop = (object)$this->loop;
    $this->loop->extractArgumentFromActiveLoop = (object)array();
    $this->loop->methods = (object)array();
    $this->loop->started = false;
    $this->loop->definition = false;
    $this->loop->quit = NULL;
    $this->if = (object)array('active' => false, 'reasoned' => NULL, 'shutdown' => NULL);
    $this->loop->localStorage = (object)array();

    $m = file_get_contents($this->module);
    if (empty($m)) {
      echo "ParseCode ERROR: Module {$this->module} doesn't exists";
      exit;
    }

    // Impostiamo delle variabili iniziali
    $return = '';
    $started = false;
    $finished = false;
    $start = false;
    $ll;
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
      $clearcommand = str_replace($ll[0] . ' ', '', $row);
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

      if ($this->if->active && !$this->if->reasoned && ($row != 'but' && $row != 'or' && $row != 'catch')) {
        continue;
      }

/*
      if ($this->if->active && !$this->if->reasoned && !$this->if->shutdown && $row == 'but') {
        $this->reasoned = true;
      }

      if ($this->if->active) {
        $start = true;
      }
*/

      if ($start && $row == ']//,') {
        $start = false;
        continue;
      }
   
      $parseTh = false;
      if ($start) {
        if ($start && !$this->if->active) {
          $parseTh = true;
        } elseif ($start && $this->if->active && $this->if->reasoned) {
          $parseTh = true;
        } else {
          $parseTh = false;
        }

        if ($parseTh) {
          if ($this->if->active && ($ll[0] == 'but' || $ll[0] == 'or')) {
            $this->if->reasoned = false;
            $this->if->active = true;
            $this->if->shutdown = true;
            continue;
          }

         if ($this->loop->started && $this->loop->quit == $this->index && $rows[$this->index] == 'break') {
           $this->loop->started = false;
           $this->loop->quit = NULL;
           continue;
         }

         if ($this->loop->definition && $ll[0] != 'break') {
           continue;
         } elseif ($this->loop->definition && $ll[0] == 'break') {
           $this->loop->definition = false;
           $this->loop->externalVar = $var;
           $looop = $this->loop->active;
           $var = $this->callLoopMethod($looop);
           $this->loop->methods->{$looop}->for++;
           continue;
         }

          // Procediamo con l'assegnazione corretta delle variabili pointer
          foreach ($this->single as $key => $tempV) {
            $var[$key] = $var[$tempV];
          }

          // Procediamo con l'analisi 
          if (!empty($this->variableMethodCall) && !empty($var[explode('.', $ll[0])[0]])) {
            $vv = $var[explode('.', $ll[0])[0]];
            $a1 = explode('(', $row);
            $a2 = explode(')', $a1[1]);
            $ar = explode(', ', $a2[0]);
            $this->callCustomMethod(str_replace($vv . '.', '', $row), $var, $ar);
          } 

          // Procediamo con l'elaborazione degli eventi sincroni
          if (is_object($this->eventsActive) && !empty($this->eventsActive)) {
            foreach ($this->eventsActive as $key => $sync) {
              if (empty($sync->value)) {
                if ($sync->type == 'change:file') {
                  $this->eventsActive->{$key}->value = file_exists($sync->src);
                } elseif ($sync->type == 'change:content' || $sync->type == 'change:http') {
                  $this->eventsActive->{$key}->value = file_get_contents($sync->src);
                }
              } else {
                if ($sync->type == 'change:file') {
                  if ($sync->value == file_exists($sync->src)) {
                    $var = $this->parseMethod($sync->callback, $var, 'tempFoxReturnNotNull');
                  }
                } elseif ($sync->type == 'change:content' || $sync->type == 'change:http') {
                  if ($sync->value == file_get_contents($sync->src)) {
                    $var = $this->parseMethod($sync->callback, $var, 'tempFoxReturnNotNull');
                  }
                }
              }
            }
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
              } elseif ($ll[3] == 'varchar') {
                $var[$nameVar] = $this->loop->externalVar[$ll[4]];
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
            } elseif ($ll[2] == 'int') {
              $var[$ll[1]] = (int)$ll[3];
            } elseif ($ll[2] == "var") {
              $var[$ll[1]] = $var[$ll[3]];
            } elseif ($ll[2] == "array") {
              if ($ll[3] == 'empty') {
                $var[$ll[1]] = array();
              }

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
          } elseif ($ll[0] == 'event') {
            // event change:quellocheè async file/input callback
            if ($ll[2] == 'async') {
              // Non lo metto nell'event loader, lo carico direttamente qua
              $localExitVar = '';
              $timenow = strtotime('now');
              while (empty($localExitVar)) {
                $status = false;
                $input = $var[$ll[3]];
                if ($ll[1] == 'change:file') {
                  if ($status == false) {
                    $status = file_exists($input);
                  } elseif ($status != file_exists($input)) {
                    $localExitVar = true;
                  }
                } elseif ($ll[1] == 'change:content' || $ll[1] == 'change:http') {
                  if ($status == 'false') {
                    $status = file_get_contents($input);
                  } elseif ($status != file_get_contents($input)) {
                    $localExitVar = true;
                  }
                }
                // Check the time
                if ((strtotime('now') - $timenow) >= $this->config['events']['timeout']) {
                  $localExitVar = true;
                }

                sleep($this->config['events']['checkspeed']);
              } 
              $var = $this->parseMethod(str_replace($ll[0] . ' ' . $ll[1] . ' ' . $ll[2] . ' ' . $ll[3] . ' ', '', $row), $var, 'tempFoxReturnNotNull');
            } elseif ($ll[2] == 'sync') {
              $input = $var[$ll[3]];
              $c = rand(0, 15555);
              $this->eventsActive->{$c}->type = $ll[1];
              $this->eventsActive->{$c}->source = $input;
              $this->eventsActive->{$c}->callback = str_replace($ll[0] . ' ' . $ll[1] . ' ' . $ll[2] . ' ' . $ll[3] . ' ', '', $row);
            }
          } elseif ($ll[0] == 'link' && $ll[2] == 'with') {
            $this->single->{$ll[1]} = $ll[3];
            $var[$ll[1]] = $ll[2];
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
              if (!is_array($val) && !is_object($val)) {
                $text = str_replace('{' . $el . '}', $val, $text);
              }
            }
            $z = explode(' then ', $tempRow);
            $var[$z[1]] = $text;
          } elseif ($ll[0] == "method") { 
            $var = $this->parseMethod(str_replace('method ', '', $row), $var);
          } elseif ($ll[0] == "->" || $ll[0] == 'return') {
            return $this->get($clearcommand);
          } elseif ($ll[0] == 'say') {
            if ($ll[1] == 'string') {
              $return .= explode("'", $row)[1];
            } elseif ($ll[1] == 'var') {
              $return .= $var[$ll[2]];
            }
          } elseif ($ll[0] == 'print') {
            echo $this->get(str_replace('print ', '', $row), $var);
          } elseif ($ll[0] == 'dump') {
            var_dump($this->get(str_replace('dump ', '', $row), $var));
          } elseif ($ll[0] == 'math') {
            if ($ll[1] == 'increase') {
              $var[$ll[2]]++;
            } elseif ($ll[1] == 'decrease') {
              $var[$ll[2]]--;
            }
          } elseif ($ll[0] == 'wait') {
            sleep($ll[1]);
          } elseif ($ll[0] == 'call') {
            if (!$this->isString($ll[1])) {
              require $var[$ll[1]];
            } else {
              require explode("'", $row)[1];
            }
/*
          } elseif ($ll[0] == 'next') {
            $this->loop->methods->{$this->loop->active}->for++;
*/
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

        if ($ll[0] == "catch" && $start && $this->if->active) {
          $this->if->active = false;
        } elseif ($ll[0] == "but" && $this->if->active && !$this->if->reasoned && !$this->if->shutdown && $start) {
           $this->if->reasoned = true;
           continue;
        } elseif (($ll[0] == "if" || $ll[0] == "or") && $start) {
          if ($ll[0] == "or") {
            $this->if->reasoned = false;
          }
          $str = $this->get($ll[3]);
 
          $this->if->active = true;
          $this->if->reasoned = false;
          if ($ll[2] == 'as' || $ll[2] == 'is') {
            if ($var[$ll[1]] == $str) {
              $this->if->reasoned = true;
            }
          } elseif ($ll[2] == 'not') {
            if ($var[$ll[1]] != $str) {
              $this->if->reasoned = true;
            }
          } elseif ($ll[2] == 'maj') {
            if ($var[$ll[1]] > $str) {
              $this->if->reasoned = true;
            }
          } elseif ($ll[2] == 'min') {
            if ($var[$ll[1]] < $str) {
              $this->if->reasoned = true;
            }
          } elseif ($ll[2] == 'empty') {
            if (empty($var[$ll[1]])) { 
              $this->if->reasoned = true;
            }
          } else {
            $this->if->active = false;
          }
        } elseif ($ll[0] == 'for' && $ll[1] == 'each') {
          $id = rand(1, 1000);
          $this->loop->active = $id;
          $this->loop->definition = true;
          $this->loop->methods->{$id} = (object)array();
          $this->loop->methods->{$id}->arrayName = $ll[2];
          $this->loop->methods->{$id}->array = $var[$ll[2]];
          $this->loop->methods->{$id}->start = $this->index;
          $this->loop->methods->{$id}->for = 0;
          $this->loop->methods->{$id}->count = count($var[$ll[2]]);
        } elseif ($ll[0] == 'break') {
          $id = $this->loop->active;
          if ($this->loop->methods->{$id}->count == 0) {
            $this->loop->active = NULL;
            $this->loop->started = false;
            continue;
          }
          if (!empty($this->loop->active) && !$this->loop->definition && $this->loop->methods->{$id}->for == $this->loop->methods->{$id}->count) {
            $this->loop->active = NULL;
            $this->loop->started = false;
            continue;
/*
          } elseif (!empty($id) && $this->loop->definition) {
            $this->loop->definition = false;
            $name = $this->loop->methods->{$id}->arrayName;
            $this->loop->methods->{$id}->end = $this->index-1;
            $this->loop->methods->{$id}->count = count($var[$name]);
            $var = $this->callLoopMethod($id);
            $this->loop->methods->{$id}->for++;
*/
          } elseif (!$this->loop->definition && $this->loop->started) {
            $var = $this->callLoopMethod($id);
            $this->loop->methods->{$id}->for++;
          }         
        }
      } else {
        if ($this->spaceTracker[$this->index] !== false) {
          for ($i = 0; $i < $this->spaceTracker[$this->index]; $i++) {
            echo ' ';
          }
        }
        // Prima di stampare verifichiamo che non ci sia un if attivo
        if (!$this->if->active) {
          echo "{$row}\n";
        } elseif ($this->if->active && $this->if->reasoned) {
          echo "{$row}\n";
        } else {
          continue;
        }
      }
    }
    return $return;
  }
}
