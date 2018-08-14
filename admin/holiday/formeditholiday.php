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
	//Set Path
	$isSubfolder = true;
	$activepage = "holiday";
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>แก้ไขข้อมูลวันหยุดให้บริการ</title>
		<?php include "../include/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">
    </head>
    <body>
		<?php include "../include/banner.php"; ?>
			<?php include "../include/menu.php"; ?>	

		<?php
			$Id=$_GET['Id'];
			$sql = $db->prepare("SELECT HolidayDate,HolidayName FROM holiday WHERE HolidayId=$Id");
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			while ($row = $sql->fetch()) { 
		?>
			<div class="container">
			<br><br>
				<div class="row">
					<div class="col-md-5" style="margin-left: 30px">				
						
						<div class="panel panel-default">     					
      				  <div class="panel-body">
      				  <h3 class="text-center"><b>แก้ไขข้อมูลวันหยุดให้บริการ</b></h3><hr>
						<form role = "form" action="editholiday.php" method="post">							
						<div class="form-group">
							<label>วันที่หยุดให้บริการ:</label>
							<input required id="date" type="text" class="form-control" name="holidaydate" value="<?php echo $row["HolidayDate"] ?>">
						</div>

						<div class="form-group">
							<label>ชื่อวันหยุดให้บริการ:</label>
							<input required type="text" class="form-control" name="holidayname" value="<?php echo $row["HolidayName"] ?>">
						</div>

		<?php } ?>
						<div style="text-align: center">
							<input type="hidden" class="form-control" name="Id" value="
							<?php echo $Id ?>">
						
						    <br><button type="submit" class="btn btn-success">บันทึก</button>		   <a href="index.php" class="btn btn-danger">ยกเลิก</a>
					   </div>		   
					    </form>			
					</div>					
				</div>
			</div>	
			</div>
			</div>
        <?php include "../include/js.php"; ?>  
         <script src="../js/jquery.datetimepicker.full.min.js"></script>
        <script>
         jQuery(function(){
         	$.datetimepicker.setLocale('th');
         	jQuery('#date').datetimepicker({
			  format: 'Y-m-d',						  
			  timepicker: false,
			  step: 1,
 			});
 		});
     </script>      
    </body>
</html>
<?php } } ?>