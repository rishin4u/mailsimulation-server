<?php
require_once 'handler.php';
$data['userid'] = validate_user();
$data['matter'] = filter_input(INPUT_POST,'matter');
$data['to'] = $_REQUEST['to'];
if(!validate_empty($data['to'])||!validate_empty($data['matter'])){
                print(json_encode(return_failure("MISSING DATA",401)));  
                exit;
        }


$data['thread_id'] = filter_input(INPUT_POST,'thread_id');
$data['replyto'] = filter_input(INPUT_POST,'reply_to');
$data['forwardof'] = filter_input(INPUT_POST,'forward_of');
$data['subject']=filter_input(INPUT_POST,'subject');
$data['draft'] = filter_input(INPUT_POST,'draft');
print( json_encode(send_mail($data)));

?>
