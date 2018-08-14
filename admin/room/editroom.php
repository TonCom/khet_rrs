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
	$sql = "UPDATE room SET
		RoomName = :roomname,
		Unit = :unit,
		Details = :details,
		Type = :type,
		CostInt = :costint,
		CostExt = :costext,
		Status = :status
		WHERE RoomId = :setID";
		
	$setid = $_POST['Id'];
	settype($setid, "integer");
	$stmp = $db->prepare($sql);
	$stmp->bindValue("roomname" , $_POST["roomname"]);
	$stmp->bindValue("unit" , $_POST["unit"]);
	$stmp->bindValue("details" , $_POST["details"]);
	$stmp->bindValue("type" , $_POST["type"]);
	$stmp->bindValue("costint" , $_POST["costint"]);
	$stmp->bindValue("costext" , $_POST["costext"]);
	$stmp->bindValue("status" , $_POST["status"]);
	$stmp->bindValue("setID" , $setid);
	$stmp->execute();
		
	header('Location: index.php');
	}	
catch(PDOException $e) {
  echo $e->getMessage();
	} 
} } ?>