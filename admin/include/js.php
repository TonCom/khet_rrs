 <?php 
 
/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

 	if(isset($isSubfolder) && $isSubfolder == true) {
 		$_path = "../";
 	}
 	else if (isset($isSubfolder) && $isSubfolder == false) {
 		$_path = "../../admin/";
 	}
 	else{
 		$_path = "admin/";
 	}
 ?>	
