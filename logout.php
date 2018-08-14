<?php

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	session_start();	
	session_destroy();

	if(isset($_GET["ctrl"])&&$_GET["ctrl"]=="admin") { 
		header('Location: admin/login.php'); 
	} else {
		header('Location: index.php');
	}
?>