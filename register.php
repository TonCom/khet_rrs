<?php

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	session_start();
	
	require "admin/include/connect.php";
		
	$activepage = "register";

	if(isset($_GET["error"])) {
		if($_GET["error"]==1) {
			$error="<div style='padding-left: 16px'><b>พบข้อผิดพลาด:</b>  
						กรุณาเลือกติ๊กถูก <b>“I’m not a robot”</b> ก่อนกดสมัครสมาชิก</div> 
						<div style='padding-left: 121px'>เพื่อยืนยันว่า ไม่มีโปรแกรมหุ่นยนต์ใดๆ เกี่ยวข้องต่อการสมัครสมาชิกที่กำลังเกิดขึ้น</div>";
		}
		else if($_GET["error"]==2) {
			$error="<div style='padding-left: 16px'><b>You are spammer ! Get the @$%K out</b></div>";
		}
	}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>สมัครสมาชิก</title>
        <script src='https://www.google.com/recaptcha/api.js'></script>
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
			<form role="form" action="addcustomer.php" method="post" onsubmit="return confirm('คุณต้องการยืนยันการสมัครสมาชิกหรือไม่ ?');">	
				<div class="row">				
					<div class="col-md-9 center-block">	
					<div class="panel panel-default">
      				  <div class="panel-body">

				<h3 style="text-align: center"><b>สมัครสมาชิก</b></h3>
				<hr>
				<?php if(isset($_GET["error"])) { ?>
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <?=$error?>
					</div>
					<?php } ?>
					<div class="panel panel-default">
      				  <div class="panel-body">
						<div class="form-group col-md-6">
					      <label>เลขบัตรประจำตัวประชาชน:</label>
					      <input required type="text" class="form-control" name="customerid" id="citizenId" style="letter-spacing: 1px;" placeholder="กรอกเลขบัตรประจำตัวประชาชน.."> 
					    </div>

					    <div class="form-group col-md-6">
					      <label>ประเภทผู้เช่า:</label>
					      <select required class="form-control" name="customertypeid">
					      <option value="">เลือกประเภทผู้เช่า..</option>
							<?php
								$sql = $db->prepare("SELECT TypeId,TypeName FROM usertype WHERE TypeId>0");
								$sql->execute();
								$sql->setFetchMode(PDO::FETCH_ASSOC);
								while ($row = $sql->fetch()) { ?>
									<option value="<?=$row['TypeId'] ?>"><?php echo $row["TypeName"]?></option>
							<?php } ?>  
						</select>
					    </div>
					  
						<div class="form-group col-md-4">
					      <label>คำนำหน้าชื่อ:</label>
					      <input required type="text" class="form-control" name="prename" 
					      placeholder="กรอกคำนำหน้าชื่อ..">
					    </div>
					    <div class="form-group col-md-4">
					      <label>ชื่อ:</label>
					      <input required type="text" class="form-control" name="firstname" 
					      placeholder="กรอกชื่อผู้เช่า..">
					    </div>
					    <div class="form-group col-md-4">
					      <label>นามสกุล:</label>
					      <input required type="text" class="form-control" name="lastname" 
					      placeholder="กรอกนามสกุลผู้เช่า..">
					    </div>  
 					 	
						<div class="form-group col-md-12">
					      <label>ที่อยู่:</label>
					      <textarea required class="form-control" name="address" rows="4"
					      placeholder="กรอกที่อยู่ของผู้เช่าที่สามารถติดต่อได้.."></textarea>
					    </div>	
						
						<div class="form-group col-md-6">
					      <label>เบอร์โทรศัพท์:</label>
					      <input required type="text" class="form-control" name="telephone" 
					      id="telephone" style="letter-spacing: 1px;" placeholder="กรอกเบอร์โทรศัพท์ เช่น 087-5698563">
					    </div>

						<div class="form-group col-md-6">
					      <label>ชื่อหน่วยงาน:</label>
					      <input required type="text" class="form-control" name="company" 
					      placeholder="กรอกชื่อหน่วยงาน/คณะที่สังกัด เช่น คณะวิทยาศาสตร์">
					    </div>
 					 </div>
					</div>

					<div class="panel panel-default">
      				  <div class="panel-body">
      					<div class="form-group col-md-7">
					      <label>บัญชีผู้ใช้งาน:</label>
					      <div class="input-group"><span class="input-group-addon"><span class="fa fa-user fa-lg"></span></span><input required type="email" class="form-control" name="username" placeholder="กรอกบัญชีผู้ใช้งาน เช่น thapanon@hotmail.com">
					    </div>

					    <div style="padding-top: 15px"><label>รหัสผ่าน:</label>
					      <div class="input-group"><span class="input-group-addon"><span class="fa fa-lock fa-lg"></span></span><input required type="password" class="form-control" name="password" placeholder="กรอกรหัสผ่าน ควรมีอย่างน้อย 8 ตัวอักษร..">
					    </div></div>
						</div>
												
					    <div class="g-recaptcha form-group col-md-5" data-sitekey="6LdeaxcUAAAAALToSmL3bN6F0DhEETrKwQjBm9AG" style="padding-top: 25px"></div>
					   					     
					  </div>
    				</div>	
				
					 <div style="text-align:center">
					   	  <button type="submit" class="btn btn-success">สมัครสมาชิก</button>	
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
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js" type="text/javascript"></script>
        <script>
        $.mask.definitions['~']='[+-]';
		$('#citizenId').mask('9-9999-99999-99-9'); 
		$('#telephone').mask('999-9999999'); 		
		</script>                  
    </body>
</html>