<?php	

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	$activepage = "login";

	session_start();
	if(isset($_SESSION['UserId'])&&$_SESSION['UserTypeId']!=0) {
		header('Location: index.php'); 
	}
	else if(isset($_GET["error"])) {
		if($_GET["error"]==0) {
			$error="กรุณาเข้าสู่ระบบ !";
		}
		else if($_GET["error"]==1) {
			$error="ไม่พบบัญชีผู้ใช้งาน กรุณาตรวจสอบใหม่อีกครั้ง !";
		}
		else if($_GET["error"]==2) {
			$error=" บัญชีผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง !";
		}
		else if($_GET["error"]==3) {
			$error="บัญชีผู้ใช้งานยังไม่ได้รับการอนุมัติ กรุณาติดต่อเจ้าหน้าที่ !";
		}
		else if($_GET["error"]==4) {
			$error="เซสชันของท่านหมดอายุแล้ว โปรดเข้าสู่ระบบอีกครั้ง !";
		}
	}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>เข้าสู่ระบบ</title>      
		<?php include "admin/include/css.php"; ?>		
    </head>
    <style>     
   	.center-block {
   		float: none !important
   	}    
    </style>
    <body>
		<?php include "customer/include/banner.php"; ?>
		<?php include "customer/include/menu.php"; ?>

		<div class="container">
			<br><br>
			<form role="form" action="validation.php" method="post">	
				<div class="row">				
					<div class="col-md-6 center-block">	
								
					<div class="panel panel-default">
      				  <div class="panel-body">
						<h3 style="text-align: center"><b>เข้าสู่ระบบ</b></h3>
						<hr>
						<?php if(isset($_GET["error"])) { ?>
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <div style='padding-left: 1px'><b>พบข้อผิดพลาด:</b> <?=$error?></div>
					</div>
					<?php } ?>
      					<div class="form-group col-md-12">
					      <label>บัญชีผู้ใช้งาน:</label>
					      <div class="input-group"><span class="input-group-addon"><span class="fa fa-user fa-lg"></span></span><input required type="email" class="form-control" name="username" placeholder="กรอกบัญชีผู้ใช้งาน..">
					    </div></div>

					    <div class="form-group col-md-12">
					     <label>รหัสผ่าน:</label>
					      <div class="input-group"><span class="input-group-addon"><span class="fa fa-lock fa-lg"></span></span><input required type="password" class="form-control" name="password" placeholder="กรอกรหัสผ่าน..">
					    </div></div>
						
						 <div style="text-align:center">
					   	  <button type="submit" class="btn btn-success">เข้าสู่ระบบ</button>	
					   	  <a href="index.php" class="btn btn-danger">ยกเลิก</a>
					</div>
							<div style="font-size: 14px; text-align: right; padding-top: 30px">
							<a href="register.php">สมัครสมาชิก</a> | <a href="recover.php">ลืมรหัสผ่าน?</a>
							</div>
						</div>																	   	
						</div>    																	
				</div>	
			  </div>											  
			</form>		
		</div>
		
		<?php include "customer/include/footer.php"; ?>
		
        <?php include "admin/include/js.php"; ?>                           
    </body>
</html>