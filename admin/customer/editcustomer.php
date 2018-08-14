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
	$sql = "UPDATE user SET
		PreName = :prename,
		FirstName = :firstname,
		LastName = :lastname,
		UserTypeId = :setcustomertypeid,
		Company = :company,
		Address = :address,
		Telephone = :telephone";

		if(($_POST['password'])!="") {
			$sql .= ", Password = PASSWORD(:password)"; 
		}

		$sql .= " WHERE UserId = :Id";
		
	$setcustomertypeid = $_POST['customertypeid'];
	settype($setcustomertypeid, "integer");

	$stmp = $db->prepare($sql);
	$stmp->bindValue("prename" , $_POST["prename"]);
	$stmp->bindValue("firstname" , $_POST["firstname"]);
	$stmp->bindValue("lastname" , $_POST["lastname"]);
	$stmp->bindValue("setcustomertypeid" , $setcustomertypeid);
	$stmp->bindValue("company" , $_POST["company"]);
	$stmp->bindValue("address" , $_POST["address"]);
	$stmp->bindValue("telephone" , $_POST["telephone"]);

		if(($_POST['password'])!="") {
			$stmp->bindValue("password" , $_POST["password"]);
		}

	$stmp->bindValue("Id" , $_POST["Id"]);	
	$stmp->execute();
		
	header('Location: index.php');
	}	
catch(PDOException $e) {
  echo $e->getMessage();
	} 
} } ?>