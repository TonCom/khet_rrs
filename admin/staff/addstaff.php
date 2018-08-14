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
	$sql = $db->prepare("INSERT INTO user (Username,Password,PreName,FirstName,LastName,UserTypeId,StaffLevel) 
		VALUES (:user,PASSWORD(:password),:pname,:name,:lastname,0,:stafflevel);");
	$sql->execute(array(
		"user" => $_POST["user"],
		"password" => $_POST["password"], //  md5($_POST["password"])
		"pname" => $_POST["pname"],
		"name" => $_POST["name"],
		"lastname" => $_POST["lastname"],
		"stafflevel" => $_POST["stafflevel"]
		)); 

	header('Location: index.php');
	}
catch(PDOException $e) {
  echo $e->getMessage();
	}	
} } ?> 