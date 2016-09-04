<?php



class editgpio {

  /**
  *Constructor, reads file
  *
  *
  *@return  boolean
  */
  function __construct()
  {
    $ini = parse_ini_file("location.ini");
    $this->filelocation = $ini['gpio'];
    $this->filedata = file_get_contents($this->filelocation);
    $this->filedataparsed = json_decode($this->filedata, true);
    return(true);
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

  /**
  *Checks if obj is string
  *
  *@param String  $a  param to check
  *
  *@return  boolean
  */
  function StringCheck($a)
  {
    if(strlen($a) < 16 AND is_string($a))
    {
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
    if(ctype_digit($a))
    {
      return(true);
    }
  }

  function modeCheck($m) {
    if(is_numeric($m))
    {
      return(true);
    }
  }

  /**
  *Edits the name of the given id
  *
  *@param String  $id   The socketadr
  *@param String  $name the new name
  *
  *@return  boolean
  */
  function editname($id, $name)
  {
    foreach ($this->filedataparsed as $k => $gpio) {
      if($gpio["pin"] == $id) {
        $this->filedataparsed[$k]["name"] = $name;
        //print_r($this->filedataparsed);
        return(TRUE);
      }
    }
  }

  /**
  *Edits the mode of the given id
  *
  *@param String  $id   The socketadr
  *@param input   $name the new mode
  *
  *@return  boolean
  */
  function editmode($id, $mode)
  {
    foreach ($this->filedataparsed as $k => $gpio)
    {
      if($gpio["pin"] == $id)
      {
        $this->filedataparsed[$k]["mode"] = $mode;
        //print_r($this->filedataparsed);
        return(TRUE);
      }
    }
    return(FALSE);
  }

}

class deletegpio {
  /**
  *Constructor, reads file
  *
  *
  *@return  boolean
  */
  function __construct()
  {
    $ini = parse_ini_file("location.ini");
    $this->filelocation = $ini['gpio'];
    $this->filedata = file_get_contents($this->filelocation);
    $this->filedataparsed = json_decode($this->filedata, true);
    return(true);
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

  /**
  *Checks if given id is valid
  *
  *@param String  $a   The socketadr
  *
  *@return  boolean
  */
  function idCheck($a)
  {
    if(ctype_digit($a))
    {
      return(true);
    }
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
    foreach ($this->filedataparsed as $k => $socket)
    {
      if($socket["pin"] == $i)
      {
        unset($this->filedataparsed[$k]);
        //print_r($this->filedataparsed);
        return(TRUE);
      }
    }
  }


}


class addgpio {
  /**
  *Constructor, reads file
  *
  *
  *@return  boolean
  */
  function __construct()
  {
    $ini = parse_ini_file("location.ini");
    $this->filelocation = $ini['gpio'];
    $this->filedata = file_get_contents($this->filelocation);
    $this->filedataparsed = json_decode($this->filedata, true);
    return(true);
  }

  function checktaken($i) {
    foreach ($this->filedataparsed as $k => $gpio) {
      if($gpio["pin"] == $i) {
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

  function add($i, $n, $m)
  {
    array_push($this->filedataparsed, array("name" => $n, "pin" => $i, "mode" => $m));
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

  function modeCheck($m) {
    if(is_numeric($m))
    {
      return(true);
    }
  }


}


header("Content-Type: application/json");
//PUT == EDIT
if($_SERVER['REQUEST_METHOD'] == 'PUT') {
  parse_str(file_get_contents("php://input"), $input);
  if($edit = new editgpio()) {
    if($edit->idCheck($input["id"])) {

      //NAME UPDATE
      if($edit->StringCheck($input["name"])) {
        if($edit->editname($input["id"], $input["name"])) {
          //MODE UPDATE
          if($edit->modeCheck($input["mode"])) {
            if($edit->editmode($input["id"], intval($input["mode"]))) {
              if($edit->finish()) {
                print(json_encode(array("status" => "success")));
              }else {print("d");}
            } else {print("c");}
          }else {print("b");}
        } else {print("a");}
        } else {print("c");}
      }else {print("b");}
    } else {print("a");}


} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') { //DELETE
  parse_str(file_get_contents("php://input"), $input);
  if($del = new deletegpio()){
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
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') { //POST == CREATE
  if($add = new addgpio()) {
    if ($add->idCheck($_POST['id'])) {
      if($add->checktaken($_POST['id'])) {
        if($add->StringCheck($_POST['name'])) {
          if($add->modeCheck($_POST['mode']))
            if($add->add($_POST['id'], $_POST['name'], intval($_POST['mode']))) {
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
