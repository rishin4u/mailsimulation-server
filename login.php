<?php
require_once 'handler.php';
$username = filter_input(INPUT_POST,'username');
$password = filter_input(INPUT_POST,'password');
$data = array('username'=>$username,'password'=>$password);
print(json_encode(login($data)));

?>
