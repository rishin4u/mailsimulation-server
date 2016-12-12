<?php
require_once 'handler.php';
$userid = validate_user();
print(json_encode(inbox_mails($userid)));

