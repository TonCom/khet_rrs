<?php

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	session_start();
	
	if(!isset($_SESSION['UserId'])||$_SESSION['UserTypeId']==0) {
		header('Location: ../../login.php?error=0'); 
	} else {        
        if(time() > $_SESSION['expire']) {
            session_destroy();
            header('Location: ../../login.php?error=4');              
        } else {
		
	require "../../admin/include/connect.php";
		
	$sql = ("DELETE FROM activity where ActivityId LIKE :Id");
	$stmt = $db->prepare($sql);
	$stmt->bindParam('Id', $_GET['Id'], PDO::PARAM_INT);
	$stmt->execute();

    header('Location: index.php'); 
} } ?>