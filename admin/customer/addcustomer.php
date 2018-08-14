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
	$sql = $db->prepare("INSERT INTO user (CitizenId,PreName,FirstName,LastName,UserTypeId,
		Company,Address,Telephone,Username,Password,Active) VALUES (:customerid,:prename,:firstname,:lastname,:customertypeid,:company,:address,:telephone,:username,PASSWORD(:password),'yes');");
	$sql->execute(array(
		"customerid" => $_POST["customerid"],	
		"prename" => $_POST["prename"],
		"firstname" => $_POST["firstname"],
		"lastname" => $_POST["lastname"],	
		"customertypeid" => $_POST["customertypeid"],
		"company" => $_POST["company"],
		"address" => $_POST["address"],
		"telephone" => $_POST["telephone"],
		"username" => $_POST["username"],
		"password" => $_POST["password"]
		)); 

	header('Location: index.php');
	}
catch(PDOException $e) {
  echo $e->getMessage();
	}	
} } ?>