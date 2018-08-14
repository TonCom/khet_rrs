<?php

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	session_start();
	
	if(!isset($_SESSION['UserId'])||$_SESSION['UserTypeId']==0) {
		header('Location: ../../login.php?error=0'); 
	} else {        
        if(time() > $_SESSION['expire']) {
            session_destroy();
            header('Location: ../../login.php?error=4');              
        } else {

	require "../../admin/include/connect.php";
	
	$isSubfolder = false;
	
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>แก้ไขข้อมูลส่วนตัว</title>
        <?php include "../../admin/include/css.php"; ?>				
    </head>
    <style>     
   	.center-block {
   		float: none !important
   	}    
    </style>
    <body>
		<?php include "../include/banner.php"; ?>
		<?php include "../include/menu.php"; ?>

		<?php
			$sql = "SELECT UserId,CitizenId,PreName,FirstName,LastName,UserTypeId,Company,Address,Telephone,Username,Password FROM user WHERE UserId='".$_SESSION['UserId']."'";	
			$stmt = $db->prepare($sql);		
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			if ($row = $stmt->fetch()) {			 	
		?>

		<div class="container">
			<br><br>
			<form role="form" action="edituserdetails.php" method="post" onsubmit="return confirm('คุณต้องการยืนยันการแก้ไขข้อมูลส่วนตัวหรือไม่ ?');">	
				<div class="row">				
					<div class="col-md-9 center-block">				
				<div class="panel panel-default">
      				  <div class="panel-body">	

      			<h3 style="text-align: center"><b>แก้ไขข้อมูลส่วนตัว</b></h3>
				<hr>
					<?php if(isset($_GET["error"])&&($_GET["error"]==1)) { ?>
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<div style="padding-left: 16px"><b>พบข้อผิดพลาด:</b> กรอกรหัสผ่านปัจจุบันไม่ถูกต้อง กรุณาตรวจสอบใหม่อีกครั้ง !</div>
					</div>
					<?php } ?>
					
					<div class="panel panel-default">
      				  <div class="panel-body">
						<div class="form-group col-md-6">
					      <label>เลขบัตรประจำตัวประชาชน:</label>
					      <input disabled type="text" class="form-control" value="<?php echo $row["CitizenId"] ?>"> 
					    </div>

					    <div class="form-group col-md-6">
					      <label>ประเภทผู้เช่า:</label>
					      <select class="form-control" name="customertypeid">
					      <option>เลือกประเภทผู้เช่า..</option>
							<?php
								$sql1 = $db->prepare("SELECT TypeId,TypeName FROM usertype WHERE TypeId>0");
								$sql1->execute();
								$sql1->setFetchMode(PDO::FETCH_ASSOC);
								while ($row1 = $sql1->fetch()) { ?>
									<option value="<?=$row1['TypeId'] ?>" <?=($row1["TypeId"]==$row["UserTypeId"]? "selected" : "" )?> ><?php echo $row1["TypeName"]?></option>
							<?php } ?>  
						</select>
					    </div>
					  
						<div class="form-group col-md-4">
					      <label>คำนำหน้าชื่อ:</label>
					      <input required type="text" class="form-control" name="prename" 
					      value="<?php echo $row["PreName"] ?>">
					    </div>
					    <div class="form-group col-md-4">
					      <label>ชื่อ:</label>
					      <input required type="text" class="form-control" name="firstname" 
					      value="<?php echo $row["FirstName"] ?>">
					    </div>
					    <div class="form-group col-md-4">
					      <label>นามสกุล:</label>
					      <input required type="text" class="form-control" name="lastname" 
					      value="<?php echo $row["LastName"] ?>">
					    </div>  
 					 	
						<div class="form-group col-md-12">
					      <label>ที่อยู่:</label>
					      <textarea required class="form-control" name="address" rows="4"
					      ><?php echo $row["Address"] ?></textarea>
					    </div>	
						
						<div class="form-group col-md-6">
					      <label>เบอร์โทรศัพท์:</label>
					      <input required type="text" class="form-control" name="telephone" 
					      id="telephone" style="letter-spacing: 1px;" value="<?php echo $row["Telephone"] ?>">
					    </div>

						<div class="form-group col-md-6">
					      <label>ชื่อหน่วยงาน:</label>
					      <input required type="text" class="form-control" name="company" 
					      value="<?php echo $row["Company"] ?>">
					    </div>
 					 </div>
					</div>

					<div class="panel panel-default">
      				  <div class="panel-body">
      					<div class="form-group col-md-6">
					      <label>บัญชีผู้ใช้งาน:</label>
					      <div class="input-group"><span class="input-group-addon"><span class="fa fa-user fa-lg"></span></span><input disabled class="form-control" name="username" value="<?php echo $row["Username"] ?>">
					    </div>

					    <div style="padding-top: 15px"><label>รหัสผ่านปัจจุบัน:</label>
					      <div class="input-group"><span class="input-group-addon"><span class="fa fa-lock fa-lg"></span></span><input required type="password" class="form-control" name="password" placeholder="กรอกรหัสผ่านปัจจุบัน..">
					    </div></div>
						</div>
						
						<div class="form-group col-md-6">
					      <div style="color:red; padding-top: 20px">* กรุณาใส่รหัสผ่านปัจจุบันทุกครั้ง ก่อนบันทึกข้อมูล<br>
					      * หากต้องการเปลี่ยนรหัสผ่าน กรุณากรอกรหัสผ่านใหม่..
					      </div>
					    <div style="padding-top: 13px"><label>รหัสผ่านใหม่:</label>
					      <div class="input-group"><span class="input-group-addon"><span class="fa fa-lock fa-lg"></span></span><input type="password" class="form-control" name="newpassword" placeholder="หากต้องการเปลี่ยนรหัสผ่าน กรุณากรอกรหัสผ่านใหม่..">
					    </div></div>
						</div>									   
					   					     
					  </div>
    				</div>	
					 <?php } ?>
					 <div style="text-align:center">
					 <input type="hidden" class="form-control" name="Id" value="10">
					   	  <button type="submit" class="btn btn-success">บันทึก</button>	
					   	  <a href="index.php" class="btn btn-danger">ยกเลิก</a>
					</div>
					</div>	
			  </div>
				</div>	
			  </div>											  
			</form>		
		</div>
		
		<?php include "../include/footer.php"; ?>						

        <?php include "../../admin/include/js.php"; ?>  
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js" type="text/javascript"></script>
        <script>
        $.mask.definitions['~']='[+-]';		
		$('#telephone').mask('999-9999999'); 		
		</script>                        
    </body>
</html>
<?php } } ?>