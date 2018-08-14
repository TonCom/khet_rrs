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
	$activepage = "customertype";
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>เพิ่มข้อมูลประเภทผู้เช่า</title>
		<?php include "../include/css.php"; ?>
    </head>
    <body>
		<?php include "../include/banner.php"; ?>	
			<?php include "../include/menu.php"; ?>	
	
			<div class="container">
			<br><br>
				<div class="row">
					<div class="col-md-5" style="margin-left: 30px">				
						
						<div class="panel panel-default">     					
      				  <div class="panel-body">
      				  <h3 class="text-center"><b>เพิ่มประเภทผู้เช่า</b></h3>	
      				  <hr>
						<form role = "form" action="addcustomertype.php" method="post">				

						<div class="form-group">
							<label>ชื่อประเภทผู้เช่า:</label>
							<input required type="text" class="form-control" name="customertype" placeholder="กรอกชื่อประเภทผู้เช่า เช่น บุคคลภายนอก..">
						</div>
												
						<div style="text-align:center"><br>
							<button type="submit" class="btn btn-success">บันทึก</button>
							<a href="index.php" class="btn btn-danger">ยกเลิก</a>
						</div>
						
						</form>						
					</div>	
				</div>
			</div>
			</div>
			</div>
			
        <?php include "../include/js.php"; ?>      
    </body>
</html>
<?php } } ?>