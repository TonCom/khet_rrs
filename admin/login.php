<?php	
	session_start();

	if(isset($_SESSION['UserId'])&&$_SESSION['UserTypeId']==0) {
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
			$error="ท่านไม่มีสิทธิ์การใช้งานในส่วนนี้";
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
        <title>เข้าสู่ระบบ | เจ้าหน้าที่</title>      
		
		 <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css">       
        <link rel="stylesheet" type="text/css" href="css/fonts/thsarabunnew.css">	
    </head>
    
    <style type="text/css">
  	body{ 
	   /* background: url("c.png") repeat;*/
	   	font-family: 'THSarabunNew', sans-serif; 
	   	font-size: 15px; } 	

   	.center-block {
   		float: none !important
   	}  

   	.black-ribbon {
	  position: fixed;
	  z-index: 9999;
	  width: 70px;
	}
	@media only all and (min-width: 768px) {
 	.black-ribbon { width: auto; }
	}

	.stick-right { right: 0; }
	.stick-bottom { bottom: 0; }  
    </style>
    <body>

		<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="bg">
		
			<div style="padding-top:20px; padding-bottom: 20px; text-align: center; font-size: 30px; font-weight: bold;"><img src="../images/psu1.png" width="85" height="100">&nbsp;&nbsp;ระบบบริหารจัดการการให้บริการห้องอบรม ศูนย์คอมพิวเตอร์
			</div>
		
		</div>
	</div>
</div>
		

		<div class="container-fluid" style="padding-top: 7%; padding-bottom: 7%; background-color: #fafafa; border-bottom:2px solid #e7e7e7; border-top: 2px solid #e7e7e7;">			
			<form role="form" action="../validation.php" method="post">	
				<div class="row">				
				<div class="col-md-4 center-block">	
								
					<div class="panel panel-default">
      				  <div class="panel-body">
						<h3 style="text-align: center"><b>เข้าสู่ระบบ</b></h3>
						<hr>
					
						<?php if(isset($_GET["error"])) { ?>
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <div style='padding-left: 1px'><b>พบข้อผิดพลาด:</b> <?=$error?></div>
					</div>
					<?php } ?>
      					
					<div class="form-group col-md-12" style="padding-left: 40px; padding-right: 40px; padding-top: 15px">  <div class="input-group" style="padding-bottom: 10px"><span class="input-group-addon"><span class="fa fa-user fa-lg"></span></span><input required type="email" class="form-control" name="username" placeholder="กรอกบัญชีผู้ใช้งาน..">
					    </div>
					     
					      <div class="input-group" style="padding-bottom: 15px"><span class="input-group-addon"><span class="fa fa-lock fa-lg"></span></span><input required type="password" class="form-control" name="password" placeholder="กรอกรหัสผ่าน..">
					    </div>
						
						 
						 <input type="hidden" class="form-control" name="admin" value="admin">
						<button type="submit" class="btn btn-primary btn-block" style="margin-bottom: 5px">เข้าสู่ระบบ</button>
					   	  </div>						   	  											
						</div>																	   	
						</div>    																	
				</div>	
			  </div>											  
			</form>		
		</div>
		
		
	<div class="container-fluid" style="text-align: center; padding-bottom: 30px; margin-top: 25px;">		
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="font-size: 14px;">	
ROOM RESEVATION SYSTEM&nbsp; | &nbsp;THE COMPUTER CENTER&nbsp; | &nbsp;PRINCE OF SONGKLA UNIVERSITY
		</div>		
	</div>

		
		<script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>                     		
    </body>
</html>