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
		<script src="<?=$_path?>js/jquery.min.js"></script>
        <script src="<?=$_path?>js/bootstrap.min.js"></script>
        <script src="<?=$_path?>js/sidebar.js"></script> 