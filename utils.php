<?php
function return_success($data){
        http_response_code(200);
        return array('data'=>$data,'status'=>1,'message'=>'success');

}


function return_failure($message,$statuscode){
        http_response_code($statuscode);
        return array('message'=>$message,'status'=>0);

}

function return_error($message="internal Error"){
	http_response_code(500);
        print json_encode(array('message'=>$message,'status'=>0));
	exit;

}

function validate_empty($data){
	if(is_array($data)){
        	$valid = 1;
        	foreach($data as $key=>$value){
                	if(!trim($value)){
                        	$valid=0;
                        	break;
                	}
        	}
	}
	else{
		if(trim($data)){
			$valid =1;
		}
		else{
			$valid=0;
		}
	}
        return $valid;
}

?>
