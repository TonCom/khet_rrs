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

try{
	$sql = "UPDATE user SET		
		PreName = :pname,
		FirstName = :name,
		LastName = :lastname,
		StaffLevel = :level";

		if(($_POST['password'])!="") {
			$sql .= ", Password = PASSWORD(:password)"; 
		}

		$sql .= " WHERE UserId = :setID";
		
	$setid = $_POST['Id'];
	settype($setid, "integer");
	$stmp = $db->prepare($sql);
	
	$stmp->bindValue("pname" , $_POST["pname"]);
	$stmp->bindValue("name" , $_POST["name"]);
	$stmp->bindValue("lastname" , $_POST["lastname"]);
	$stmp->bindValue("level" , $_POST["level"]);

		if(($_POST['password'])!="") {
			$stmp->bindValue("password" , $_POST["password"]); 
		}

	$stmp->bindValue("setID" , $setid);
	$stmp->execute();
		
	header('Location: index.php');
	}	
catch(PDOException $e) {
  echo $e->getMessage();
	} 
} } ?>