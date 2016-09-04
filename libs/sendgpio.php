<?php

function bettersleep($time)
{
    usleep($time * 1000000);
}


class sendgpio {


  function __construct($p) {
    if(is_numeric($p)) {
      shell_exec("gpio export ".$p." out");
      return(true);
    }

  }


  function idCheck($a)
  {
    if(is_numeric($a))
    {
      return(true);
    }
  }

  function send($p, $s) {
    shell_exec('gpio -g write ' . $p . " " . $s);
  }

  function sendlatch($p) {
    shell_exec('gpio -g write ' . $p . " 1");
    bettersleep(0.5);
    shell_exec('gpio -g write ' . $p . " 0");
  }

}

header("Content-Type: application/json");
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  if($s = new sendgpio($_POST["pin"])) {
    if($s->idCheck($_POST["mode"])) {
      if(intval($_POST["mode"]) == 1) {
        if($s->idCheck($_POST["pin"])) {
          if($s->idCheck($_POST["status"])) {
            $s->send($_POST["pin"], $_POST["status"]);
            print(json_encode(array("status" => "success")));
          }
        }
      } elseif(intval($_POST["mode"]) == 2) {
        if($s->idCheck($_POST["pin"])) {
          $s->sendlatch($_POST["pin"]);
          print(json_encode(array("status" => "success")));
        }
      }
    }
  }
}


 ?>
