<?php
require_once 'handler.php';
$userid = validate_user();
$received_mail_id = filter_input(INPUT_POST,'rmailid');

print(json_encode(load_thread($received_mail_id,$userid)));

?>
