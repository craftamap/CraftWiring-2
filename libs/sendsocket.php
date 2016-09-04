<?php

class sendsocket {

  function __construct () {
    shell_exec("gpio export 17 out");
  }

  function send($h, $i, $s) {
    shell_exec("raspberry-remote/send -u -b ".$h." ".$i." ".$s);
  }

  function __destruct () {
    //shell_exec("gpio unexport 17 ");
  }

  function idCheck($a)
  {
    if(is_numeric($a))
    {
      return(true);
    }
  }
  /**
  *Checks if given homecode is valid
  *
  *@param String  $h   The homecode
  *
  *@return  boolean
  */
  function homecodeCheck($h)
  {
    if(strlen($h) == 5 AND is_numeric($h)){
      return(true);
    }
  }
}

header("Content-Type: application/json");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $s = new sendsocket();
  if($s->idCheck($_POST["id"])) {
    if($s->idCheck($_POST["status"])) {
      if($s->homecodeCheck($_POST["homecode"])) {
        $s->send($_POST["homecode"], $_POST["id"], $_POST["status"]);
        print(json_encode(array("status" => "success")));
      }
    }
  }
}



?>
