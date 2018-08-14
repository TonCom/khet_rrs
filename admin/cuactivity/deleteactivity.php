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
	
	if ($_GET["ctrl"]=="index") {
		$page="index";
	}
	else {
		$sql1 = $db->prepare("SELECT RentStatus from activity WHERE ActivityId='".$_GET['Id']."'");
		$sql1->execute();
		$sql1->setFetchMode(PDO::FETCH_ASSOC);
			if ($row1 = $sql1->fetch()) { 
				$page=$row1["RentStatus"];
			}
	}

	$sql = ("DELETE FROM activity where ActivityId LIKE :Id");
	$stmt = $db->prepare($sql);
	$stmt->bindParam('Id', $_GET['Id'], PDO::PARAM_INT);
	$stmt->execute();

    header('Location: '.$page.'.php'); 
} } ?> 