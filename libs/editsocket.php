<?php
class editsocket {
  public $filelocation;
  public $filedata;
  public $filedataparsed;

  /**
  *Constructor, reads file
  *
  *
  *@return  boolean
  */
  function __construct() {
  $ini = parse_ini_file("location.ini");
  $this->filelocation = $ini['socket'];
  $this->filedata = file_get_contents($this->filelocation);
  $this->filedataparsed = json_decode($this->filedata, true);
  return(true);
  }
  /**
  *Edits the name of the given id
  *
  *@param String  $id   The socketadr
  *@param String  $name the new name
  *
  *@return  boolean
  */
  function editname($id, $name) {
    foreach ($this->filedataparsed['sockets'] as $k => $socket) {
      if($socket["socketadr"] == $id) {
        $this->filedataparsed["sockets"][$k]["name"] = $name;
        return(TRUE);
        //print_r($this->filedataparsed);
      }
    }
  }
  /**
  *Edits the homecode
  *
  *@param String  $h   The socketadr
  *
  *@return  boolean
  */
  function edithomecode($h) {
    $this->filedataparsed["homecode"] = $h;
    //print_r($this->filedataparsed);
    return(TRUE);
  }


  /**
  *Checks if obj is string
  *
  *@param String  $a  param to check
  *
  *@return  boolean
  */
  function StringCheck($a)
  {
    if(strlen($a) < 16 AND is_string($a)){
      return(true);
    }
  }
  /**
  *Checks if given id is valid
  *
  *@param String  $a   The socketadr
  *
  *@return  boolean
  */
  function idCheck($a)
  {
    if(ctype_digit($a)){
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
  function homecodecheck($h)
  {
    if(strlen($h) == 5 AND ctype_digit($h)){
      return(true);
    }
  }

  /**
  *Saves changes to file
  *
  *@return  boolean
  */
  function finish()
  {
    $this->filedata = json_encode($this->filedataparsed, JSON_PRETTY_PRINT);
    file_put_contents($this->filelocation, $this->filedata);
    return(true);
    //print_r($this->filedata);
  }


}

class deletesocket {

  public $filelocation;
  public $filedata;
  public $filedataparsed;

  /**
  *Constructor, reads file
  *
  *
  *@return  boolean
  */
  function __construct()
  {
    $ini = parse_ini_file("location.ini");
    $this->filelocation = $ini['socket'];
    $this->filedata = file_get_contents($this->filelocation);
    $this->filedataparsed = json_decode($this->filedata, true);
    return(true);
  }

  /**
  *Removes socket
  *
  *@param String  $i    The socketadr
  *
  *@return  boolean
  */
  function delete($i)
  {
    foreach ($this->filedataparsed['sockets'] as $k => $socket) {
      if($socket["socketadr"] == $i) {
        unset($this->filedataparsed["sockets"][$k]);
        //print_r($this->filedataparsed);
        return(TRUE);
      }
    }
  }

  /**
  *Checks if given id is valid
  *
  *@param String  $a   The socketadr
  *
  *@return  boolean
  */
  function idCheck($a)
  {
    if(ctype_digit($a)){
      return(true);
    }
  }

  /**
  *Saves changes to file
  *
  *@return  boolean
  */
  function finish()
  {
    $this->filedata = json_encode($this->filedataparsed, JSON_PRETTY_PRINT);
    file_put_contents($this->filelocation, $this->filedata);
    return(true);
    //print_r($this->filedata);
  }

}

class addsocket {
  /**
  *Constructor, reads file
  *
  *
  *@return  boolean
  */
  function __construct()
  {
    $ini = parse_ini_file("location.ini");
    $this->filelocation = $ini['socket'];
    $this->filedata = file_get_contents($this->filelocation);
    $this->filedataparsed = json_decode($this->filedata, true);
    return(true);
  }


  function checktaken($i) {
    foreach ($this->filedataparsed['sockets'] as $k => $socket) {
      if($socket["socketadr"] == $i) {
        return(FALSE);
      }
    }
    return(TRUE);
  }

  function StringCheck($a)
  {
    if(strlen($a) < 16 AND is_string($a)){
      return(true);
    }
  }
  /**
  *Checks if given id is valid
  *
  *@param String  $a   The socketadr
  *
  *@return  boolean
  */
  function idCheck($a)
  {
    if(ctype_digit($a)){
      return(true);
    }
  }

  function add($i, $n)
  {
    array_push($this->filedataparsed['sockets'], array("name" => $n, "socketadr" => $i));
    return(TRUE);
  }

  /**
  *Saves changes to file
  *
  *@return  boolean
  */
  function finish()
  {
    $this->filedata = json_encode($this->filedataparsed, JSON_PRETTY_PRINT);
    file_put_contents($this->filelocation, $this->filedata);
    return(true);
    //print_r($this->filedata);
  }
}


header("Content-Type: application/json");
if($_SERVER['REQUEST_METHOD'] == 'PUT') {
parse_str(file_get_contents("php://input"), $input);
  if($editsocket = new editsocket()) {
    if($input["action"] == "name") {
      if($editsocket->idCheck($input["id"]) AND $editsocket->StringCheck($input["name"])){
        if ($editsocket->editname($input["id"], $input["name"])){
          if($editsocket->finish()){
            print(json_encode(array("status" => "success")));
          }
        }
      } else {
        print(json_encode(array("status" => "fail", "data" => array("fail" =>"socketid (numeric string) or name (max 15 chars) is invalid "))));
      }
    } elseif ($input["action"] == "homecode") {
      if($editsocket->homecodecheck($input["homecode"])){
        if($editsocket->edithomecode($input["homecode"])) {
          if($editsocket->finish()){
            print(json_encode(array("status" => "success")));
          }
        }
      } else {
        print(json_encode(array("status" => "fail", "data" => array("fail" =>"no homecode given or homecode is not valid (5 digits)"))));
      }
    } elseif (empty($input)) {
      print(json_encode(array("status" => "fail", "data" => array("fail" =>"missing arguments"))));
    } else {
    print(json_encode(array("status" => "error", "error" =>"an error with the config files occurred")));
    }
  }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
  parse_str(file_get_contents("php://input"), $input);
  if($del = new deletesocket()){
    if($del->idCheck($input["id"])){
      if($del->delete($input["id"])) {
        if($del->finish()) {
          print(json_encode(array("status" => "success")));
        }
      }
    } else {
      print("id check failed");
    }
  } else {
    print(json_encode(array("status" => "error", "error" =>"an error with the config files occurred")));
  }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if($add = new addsocket()) {
    if ($add->idCheck($_POST['id'])) {
      if($add->checktaken($_POST['id'])) {
        if($add->StringCheck($_POST['name'])) {
          if($add->add($_POST['id'], $_POST['name'])) {
            if($add->finish()){
              print(json_encode(array("status" => "success")));
            }
          }else{print(d);}
        }else{print(c);}
      }else{print(b);}
    } else{print(a);}
  }
}


 ?>
