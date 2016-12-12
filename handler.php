<?php

require('constants.php');
require('utils.php');


function login($userdata){
	if(!validate_empty($userdata))
		return return_failure("Missing Fields",200);

	include("db.php");
	$query = sprintf(LOGIN_Q,$userdata['password'],$userdata['username']);
	$result = $conn->query($query);
	if(mysqli_num_rows($result)){
		$data = $result->fetch_array();
		$returndata = array('id'=>$data['id'],'login_name'=>$data['login_name'],'name'=>$data['name']);
		$token= initiate_session($returndata);
		if($token){
			$returndata['token']= $token;
			return return_success($returndata);
		}
		else{
			return return_failure("Internal Error",500);	
		}
	}
	else{
		return return_failure("User Not Found",200);

	}
}

function initiate_session($data){
	
	include("db.php");
	$token = str_replace('.','',uniqid('',true));	
	$query = sprintf(INSERT_SESSION_Q,$data['id'],$token,time());
	if($conn->query($query))
		return $token;
	else
		return NULL;

}

function validate_user(){

	$usertoken = $_SERVER['HTTP_USER_TOKEN'];
	if(!validate_empty($usertoken)){
                print(json_encode(return_failure("UNAUTHORISED ACCESS",401)));  
	        exit;
	}

	
	include("db.php");	
	$query = sprintf(FIND_USER_BY_SESSION_Q,$usertoken);
	$result = $conn->query($query);
        if(mysqli_num_rows($result)){
		$data = $result->fetch_array();
		return $data["id"];
	}
	else{
		print(json_encode(return_failure("UNAUTHORISED ACCESS",401)));  
                exit;

		
	}

}


function send_mail($data){
	include("db.php");
	if(!$data["thread_id"])
		$data["thread_id"]=insert_thread($data["subject"]);
	$query = sprintf(INSERT_MAIL_Q,$data['thread_id'],$data['userid'],$data['matter'],time(),$data['reply_of'],$data['forward_of'],$data['draft']);
	
	
        $result = $conn->query($query);
        if($result){
		
		$mailid = $conn->insert_id;
		save_attachments($mailid,$data['forward_of']);
		$returndata['mailid']=$mailid;
		if($data['draft'])
			return return_success($returndata);

		//$to = explode(",",$data['to']);
		foreach($data['to'] as $address){
			$toentry[] = sprintf(INSERT_RECEIVER_Q_VALUES,$address,$mailid,time());

		}
		$valueqry = implode(",",$toentry);
                
		$query = sprintf(INSERT_RECEIVER_Q,$valueqry);

	        $result = $conn->query($query);
		if($result)
			return return_success($returndata);

		else
			return return_failure("Failed to Add Mail",500);   

		

		}
        else
                return_error();
			

}

function insert_thread($subject){
	include("db.php");      
        $query = sprintf(INSERT_THREAD_Q,$subject);
        $result = $conn->query($query);
	if($result)
		return  $conn->insert_id;

	else
		return_error();
}


function save_attachments($mailid,$forwarded_id){
	include("db.php"); 
	if($_FILES){

			
        	foreach($_FILES['images']['name'] as $key => $val){

			$filepath = 'files/'.$_FILES['images']['name'][$key];
			if(move_uploaded_file($_FILES['images']['tmp_name'][$key],$filepath)){
				$query = sprintf(INSERT_ATTACHMENT_Q,$mailid,$filepath,$_FILES['images']['name'][$key]);
			        $result = $conn->query($query);

				
			}
			
			
		}
			
		
	}
	

	if($forwarded_id){
		
		$query = sprintf(INSERT_FORWARD_ATTACHMENT_Q,$mailid,$forwarded_id);
                $result = $conn->query($query);

		

	}


}

function inbox_mails($userid){
	include("db.php");
	$query = sprintf(GET_MAILS_OF_USER,$userid);
       	$results = $conn->query($query);
	while($row = $results->fetch_array()){
		$row['attachments'] = load_attachments($row['mailid']);
		$mails[]= $row;

	}
	return return_success($mails);

	
}


function load_attachments($mailid){
	include("db.php");
        $query = sprintf(GET_ATTACHMENTS_Q,$mailid);
        $results = $conn->query($query);
        while($row = $results->fetch_array()){
                $attachments[]= $row;
        }

	return $attachments;
}

function sent_mails($userid){
	include("db.php");
        $query = sprintf(GET_SENT_Q,$userid);
        $results = $conn->query($query);
        while($row = $results->fetch_array()){
                $row['attachments'] = load_attachments($row['id']);
                $mails[]= $row;

        }
        return return_success($mails);


}

function draft_mails($userid){
        include("db.php");
        $query = sprintf(GET_DRAFT_Q,$userid);
        $results = $conn->query($query);
        while($row = $results->fetch_array()){
                $row['attachments'] = load_attachments($row['id']);
                $mails[]= $row;

        }
        return return_success($mails);


}

function update_db($table,$conditions){

//
}


function load_thread($mailid,$userid){

	include("db.php");
        $query = sprintf(GET_MAIL_THREAD_Q,$mailid);
        $results = $conn->query($query);
        $row = $results->fetch_array();
	$returndata["thread"]= $row;
	
	$query = sprintf(GET_SINGLE_MAIL,$mailid);
        $results = $conn->query($query);
        $row = $results->fetch_array();
	$returndata["anchor_mail"]= $row;
	$row["anchor_mail"]["attachments"] = load_attachments($row['mailid']);
	
	$query = sprintf(GET_THREAD_MAILS,$userid,$row["id"]);
	$results = $conn->query($query);          
	while($row = $results->fetch_array()){
		$row['attachments'] = load_attachments($row['mailid']);

		$returndata["mails"][]= $row;		
	}
      
        return return_success($returndata);


}

?>
