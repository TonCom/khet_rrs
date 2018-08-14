<?php

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	require "admin/include/connect.php";
	
if(isset($_POST['g-recaptcha-response'])){
    $captcha=$_POST['g-recaptcha-response'];
}
    if(!$captcha){        
        header('Location: register.php?error=1');
    exit;
        }
		$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdeaxcUAAAAAHb6TtwqKX7P9kvXdd52NoTgUnhN&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
       if ($response == false){          
          header('Location: register.php?error=2');  }
       else {

	$sql = $db->prepare("INSERT INTO user (CitizenId,PreName,FirstName,LastName,UserTypeId,
		Company,Address,Telephone,Username,Password,Active) VALUES (:customerid,:prename,:firstname,:lastname,:customertypeid,:company,:address,:telephone,:username,PASSWORD(:password),'no');");
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

	header('Location: index.php?action=1');		
    }
?>