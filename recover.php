<?php	

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	session_start();
				
	if(isset($_GET["error"])) {		
		if($_GET["error"]==1) {
			$error="ไม่พบบัญชีผู้ใช้งาน กรุณาตรวจสอบใหม่อีกครั้ง !";
		}		
		else if($_GET["error"]==2) {
			$error="บัญชีผู้ใช้งานยังไม่ได้รับการอนุมัติ กรุณาติดต่อเจ้าหน้าที่ !";
		}		
	}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ลืมรหัสผ่าน</title>      
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
						<h3 style="text-align: center"><b>ลืมรหัสผ่าน</b></h3>
						<hr>
						<?php if(isset($_GET["error"])) { ?>
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <div style='padding-left: 1px'><b>พบข้อผิดพลาด:</b> <?=$error?></div>
					</div>
					<?php } 
					 else if(isset($_GET["action"])&&($_GET["action"]==0)) { ?>
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <div style='padding-left: 1px'><b>แจ้งเตือน:</b> ระบบได้ส่งรหัสผ่านใหม่ไปที่อีเมลของท่านเรียบร้อยแล้ว</div>
					</div>
					<?php } ?>

					<div class="form-group col-md-12">
					      <div style="padding-bottom: 15px">* หลังจากกรอกอีเมลที่ท่านสมัครสมาชิก 
					      <br>* ระบบจะ<b>รีเซ็ตรหัสผ่านใหม่</b>และส่งไปที่อีเมลของท่าน
					     </div>
					     				      
					      <div class="input-group"><span class="input-group-addon"><span class="fa fa-user fa-lg"></span></span><input required type="email" class="form-control" name="email" placeholder="กรอกอีเมลที่ท่านสมัครสมาชิก..">
					    </div>
					</div>
					    						
					<div style="text-align:center">
					   	  <button type="submit" class="btn btn-success">ตกลง</button>	
					   	  <a href="index.php" class="btn btn-danger">ยกเลิก</a>
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