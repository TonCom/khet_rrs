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

	$sql1 = $db->prepare("SELECT PASSWORD('".$_POST['password']."') AS Passwordinput,Password FROM user WHERE UserId='".$_SESSION['UserId']."'");
			$sql1->execute();
			$sql1->setFetchMode(PDO::FETCH_ASSOC);
			if ($row1 = $sql1->fetch()) {
				$password=$row1["Password"];
				$passwordinput=$row1["Passwordinput"];				
			}

if($passwordinput==$password) {

	$sql = "UPDATE user SET
		PreName = :prename,
		FirstName = :firstname,
		LastName = :lastname,
		UserTypeId = :setcustomertypeid,
		Company = :company,
		Address = :address,
		Telephone = :telephone";

		if(($_POST['newpassword'])!="") {
			$sql .= ", Password = PASSWORD(:newpassword)"; 
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

		if(($_POST['newpassword'])!="") {
			$stmp->bindValue("newpassword" , $_POST["newpassword"]);
		}

	$stmp->bindValue("Id" , $_SESSION['UserId']);	
	$stmp->execute();
		
	header('Location: index.php');
}	
else {
	header('Location: formedituserdetails.php?error=1');
}
} } ?>