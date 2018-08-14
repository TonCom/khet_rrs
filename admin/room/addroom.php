<?php

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	session_start();

	if(!isset($_SESSION['UserId'])||$_SESSION['UserTypeId']!=0) {
		
		header('Location: ../login.php?error=0'); 
	} else if($_SESSION['StaffLevel']!="sadmin") {
		session_destroy();
        header('Location: ../login.php?error=3');
	} else {        
        if(time() > $_SESSION['expire']) {
            session_destroy();
            header('Location: ../login.php?error=4');              
        } else {
        	
	require "../include/connect.php";

try {
	$sql = $db->prepare("INSERT INTO room (RoomName,Unit,Details,Type,CostInt,CostExt,Status) 
		VALUES (:roomname,:unit,:details,:type,:costint,:costext,:status);");
	$sql->execute(array(
		"roomname" => $_POST["roomname"],
		"unit" => $_POST["unit"],
		"details" => $_POST["details"],
		"type" => $_POST["type"],
		"costint" => $_POST["costint"],
		"costext" => $_POST["costext"],
		"status" => $_POST["status"]
		)); 

	header('Location: index.php');
	}
catch(PDOException $e) {
  echo $e->getMessage();
	}	
} } ?> 