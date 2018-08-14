<?php

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	session_start();

	if(!isset($_SESSION['UserId'])||$_SESSION['UserTypeId']!=0) {
		
		header('Location: ../login.php?error=0'); 
	} else if($_SESSION['StaffLevel']!="sadmin"&&$_SESSION['StaffLevel']!="admin") {
		session_destroy();
        header('Location: ../login.php?error=3');
	} else {        
        if(time() > $_SESSION['expire']) {
            session_destroy();
            header('Location: ../login.php?error=4');              
        } else {

	require "../include/connect.php";

try {
	$sql = $db->prepare("INSERT INTO activitytype (TypeName) VALUES (:activitytype);");
	$sql->execute(array(
		"activitytype" => $_POST["activitytype"]		
		)); 

	header('Location: index.php');
	}
catch(PDOException $e) {
  echo $e->getMessage();
	}	
} } ?>