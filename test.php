<?php
if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    echo "this is a delete request\n";
    parse_str(file_get_contents("php://input"),$post_vars);
    echo $post_vars['fruit']." is the fruit\n";
    echo "I want ".$post_vars['quantity']." of them\n\n";
}



 ?>
