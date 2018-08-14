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

try{
	$sql = "UPDATE usertype SET
		TypeName = :customertype
		WHERE TypeId = :setID";
		
	$setid = $_POST['Id'];
	settype($setid, "integer");
	$stmp = $db->prepare($sql);
	$stmp->bindValue("customertype" , $_POST["customertype"]);
	$stmp->bindValue("setID" , $setid);
	$stmp->execute();
		
	header('Location: index.php');
	}	
catch(PDOException $e) {
  echo $e->getMessage();
	} 
} } ?>