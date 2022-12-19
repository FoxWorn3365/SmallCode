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

  public function __construct($module) {
    $this->module = $module;
    if (!empty($config)) {
      $this->config = $config;
    }
  }
  
  protected function isString($string) {
    if (!empty(explode("'", $string)[1])) {
      return true;
    }
    return false;
  }

  protected function cs($string) {
    return str_replace("'", "", $string);
  }

  protected function parseMethod($string, $row, $var) {
    $string = explode('(', $string)[0];
    $m = explode('.', $string); 
    if ($m[0] == 'array') {
      // LAVORIAMO CON GLI ARRAY
      if ($m[1] == 'getValue') {
        // Recuperiamo subito le funzioni
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $val = explode(', ', $com);
        // SINTASSI: array.getValue(<ARRAY>, <VALUE NAME>)
        if ($this->isString($val[1])) {
          $var[explode(" ", $row)[1]] = $var[$val[0]][$this->cs($val[1])];
        } else {
          $var[explode(" ", $row)[1]] = $var[$val[0]][$var[$val[1]]];
        }
      } elseif ($m[1] == 'push') {
        // Recuperiamo subito le funzioni
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $val = explode(', ', $com);
        if (!$this->isString($val[0])) {
          if ($this->isString($val[1])) {
            if ($this->isString($val[2])) {
              $var[$val[0]][$this->cs($val[1])] = $this->cs($val[2]);
            } else {
              $var[$val[0]][$this->cs($val[1])] = $var[$val[2]];
            }
          } else {
            if ($this->isString($val[2])) {
              $var[$val[0]][$var[$val[1]]] = $this->cs($val[2]);
            } else {
              $var[$val[0]][$var[$val[1]]] = $var[$val[2]];
            }
          }
        }
      } elseif ($m[1] == 'drop') {
        // Recuperiamo subito le funzioni
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $var[$com] = '';
      } elseif ($m[1] == 'count') {
        // Recuperiamo subito le funzioni
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $var[explode(" ", $row)[1]] = count($var[$com]);
      } elseif ($m[1] == 'print') {
        // Recuperiamo subito le funzioni
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        foreach ($var[$com] as $key => $element) {
          echo "$key => $element ";
        }
      }
    } elseif ($m[0] == 'loop') {
      if ($m[1] == 'getValue') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        if ($this->isString($com)) {
          $lCount = 0;
          foreach ($this->loopElement as $key => $va) {
            if ($lCount == $this->loopEach) {
              if ($this->cs($com) == 'key') {
                $var[explode(" ", $row)[1]] = $key;
              } elseif ($this->cs($com) == 'value') {
                $var[explode(" ", $row)[1]] = $va;
              }
            }
            $lCount++;
          }
        }
      }
    } elseif ($m[0] == 'file') {
      if ($m[1] == 'open' || $m[1] == 'get') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        if ($this->isString($com)) {
          $var[explode(" ", $row)[1]] = file_get_contents($this->cs($com));
        } else {
          $var[explode(" ", $row)[1]] = file_get_contenta($var[$com]);
        }
      } elseif ($m[1] == 'write') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $val = explode(', ', $com);
        if ($this->isString($val[0])) {
          if ($this->isString($val[1])) {
            file_put_contents($this->cs($val[0]), $this->cs($val[1]));
          } else {
            file_put_contents($this->cs($val[0]), $var[$val[1]]);
          } 
        } else {
          if ($this->isString($val[1])) {
            file_put_contents($var[$val[0]], $this->cs($val[1]));
          } else {
            file_put_contents($var[$val[0]], $var[$val[1]]);
          }
        }
      } 
    } elseif ($m[0] == 'json') {
      if ($m[1] == 'import') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $var[$com] = (array)json_decode($var[$com]);
      } elseif ($m[1] == 'export') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $var[$com] = json_encode($var[$com]);
      } elseif ($m[1] == 'getValue') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $val = explode(', ', $com);
        if (!$this->isString($val[0])) {
          if ($this->isString($val[1])) {
            $var[explode(" ", $row)[1]] = $var[$val[0]][$this->cs($val[1])];
          } else {
            $var[explode(" ", $row)[1]] = $var[$val[0]][$var[$val[1]]];
          }
        }
      }
    } elseif ($m[0] == 'mysql') {
      if ($m[1] == 'connect') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $val = explode(', ', $com);
        if ($this->isString($val[0]) && $this->isString($val[1]) && $this->isString($val[2])) {
          if ($this->isString($val[3])) {
            $var[explode(" ", $row)[1]] = new mysqli($this->cs($val[0]), $this->cs($val[1]), $this->cs($val[2]), $this->cs($val[3]));
          } else {
            $var[explode(" ", $row)[1]] = new mysqli($this->cs($val[0]), $this->cs($val[1]), $this->cs($val[2]), $var[$val[3]]);
          }
        }
      } elseif ($m[1] == 'checkConnection') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        if (!$this->isString($com)) {
          $var[explode(" ", $row)[1]] = $var[$com]->connect_error;
        }
      } elseif ($m[1] == 'cmd' || $m[1] == 'parse' || $m[1] == 'query') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $val = explode(', ', $com);
        if (!$this->isString($val[0])) {
          if ($this->isString($val[1])) {
            $cMySQL = $this->cs($val[1]);
          } else {
            $cMySQL = $var[$val[1]];
          }
          $res = $var[$val[0]]->query($cMySQL);
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
          $var[explode(" ", $row)[1]] = $returnArray;
        }
      }
    } elseif ($m[0] == 'HTTP') {
      if ($m[1] == 'get') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        if ($this->isString($com)) {
          $var[explode(" ", $row)[1]] = file_get_contents($this->cs($com));
        } else {
          $var[explode(" ", $row)[1]] = file_get_contents($var[$com]);
        }
      } elseif ($m[1] == 'post') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $val = explode(', ', $com);
        if ($this->isString($val[0])) { 
          $uri = $this->cs($val[0]);
        } else {
          $uri = $var[$val[0]];
        }
        $body = http_build_query($val[1]);
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => $body
            )
        );
        $context = stream_context_create($options);
        $var[explode(" ", $row)[1]] = file_get_contents($url, false, $context);
      }
    } elseif ($m[0] == 'session' && $m[1] == 'manager') {
      if ($m[2] == 'inizialize') {
        session_start();
      } elseif ($m[2] == 'kill') {
        session_destroy();
      } elseif ($m[2] == 'set' || $m[2] == 'define') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $val = explode(', ', $com);
        if (!$this->isString($val[0])) {
          if ($this->isString($val[1])) {
            $_SESSION[$var[$val[0]]] = $this->cs($val[1]);
          } else {
            $_SESSION[$var[$val[0]]] = $var[$val[1]];
          }
        } else {
          if ($this->isString($val[1])) {
            $_SESSION[$this->cs($val[0])] = $this->cs($val[1]);
          } else {
            $_SESSION[$this->cs($val[0])] = $var[$val[1]];
          }
        }
      } elseif ($m[2] == 'get') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        if ($this->isString($com)) {
          $var[explode(" ", $row)[1]] = $_SESSION[$this->cs($com)];
        } else {
          $var[explode(" ", $row)[1]] = $_SESSION[$var[$com]];
        }
      } elseif ($m[2] == 'check') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $val = explode(', ', $com);
        if ($this->isString($val[1])) {
          $red = $this->cs($val[1]);
        } else {
          $red = $var[$val[1]];
        }
        if ($this->isString($val[0])) {
          $st = $this->cs($val[0]);
        } else {
          $st = $var[$val[0]];
        }
        if (empty($_SESSION[$st])) {
          header("Location: {$red}");
        }
      }
    } elseif ($m[0] == 'redirect') {
      $b = explode('(', $row);
      $c = explode(')', $b[1]);
      $com = $c[0];
      if ($this->isString($com)) {
        $com = $this->cs($com);
      } else {
        $com = $var[$com];
      }
      header("Location: {$com}");
    } elseif ($m[0] == 'globals') {
      if ($m[1] == 'set' || $m[1] == 'define') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $val = explode(', ', $com);
        if ($this->isString($val[1])) {
          $string = $this->cs($val[1]);
        } else {
          $string = $var[$val[1]];
        }
        $GLOBALS[$this->cs($val[0])] = $string;
      } elseif ($m[1] == 'get') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $var[explode(" ", $row)[1]] = $GLOBALS[$this->cs($com)];
      }
    } elseif ($m[0] == 'hash') {
      if ($m[1] == 'combine') {
        $b = explode('(', $row);
        $c = explode(')', $b[1]);
        $com = $c[0];
        $val = explode(', ', $com);
        if ($this->isString($val[1])) {
          $v = $this->cs($val[1]);
        } else {
          $v = $var[$val[1]];
        }
        $var[explode(" ", $row)[1]] = hash($this->cs($val[0]), $v);
      }
    } elseif ($m[0] == 'random') {
      $b = explode('(', $row);
      $c = explode(')', $b[1]);
      $com = $c[0];
      $val = explode(', ', $com);
      $var[explode(" ", $row)[1]] = rand($this->cs($val[0]),  $this->cs($val[1]));
    }
    return $var;
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
      if (!$start && $row == 'Parsing:()[') {
        $start = true;
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
            }
          } elseif ($ll[0] == "get") {
            if ($ll[2] == "from") {
              if ($ll[3] == "method") {
                $var = $this->parseMethod($this->unificateString($ll, 4), $row, $var);
              } elseif ($ll[3] == 'array') {
                $arr = $ll[4];
                $sys = explode('.', $arr);
                $var[$ll[1]] = $var[$sys[0]][$sys[1]];
              }
            }
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
            foreach ($var as $el => $val) {
              $text = str_replace('{' . $el . '}', $val, $text);
            }
            $z = explode(' then ', $row);
            $var[$z[1]] = $text;
          } elseif ($ll[0] == "method") {
            $var = $this->parseMethod(str_replace('method ', '', $row), $row, $var);
          } elseif ($ll[0] == "->") {
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
            if ($ll[1] == 'string') {
              echo explode("'", $row)[1];
            } elseif ($ll[1] == 'var') {
              echo $var[$ll[2]];
            } elseif ($ll[1] == 'dump') {
              var_dump($var[$ll[2]]);
            }
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
