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
	$activepage = "listalluser";
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>แก้ไขข้อมูลผู้เช่า</title>
		<?php include "../include/css.php"; ?>
    </head>
    <body>
		<?php include "../include/banner.php"; ?>

		<?php include "../include/menu.php"; ?>				
		
		<?php
			$sql = "SELECT UserId,CitizenId,PreName,FirstName,LastName,UserTypeId,Company,Address,Telephone,Username,Password FROM user WHERE UserId LIKE :Id";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':Id', $_GET['Id'], PDO::PARAM_INT);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			while ($row = $stmt->fetch()) {			 	
		?>

			<div class="container" >
			<h3 style="margin-left:43px"><b>แก้ไขข้อมูลผู้เช่า</b></h3><br>
			<form role="form" action="editcustomer.php" method="post">	
				<div class="row" style="margin-left: 30px">				
					<div class="col-md-5">

					<div class="panel panel-default">
      				  <div class="panel-body">													
					<div class="form-group">
					<label>เลขบัตรประจำตัวประชาชน:</label>&nbsp;&nbsp;<?php echo $row["CitizenId"] ?>
					</div>

					<div class="form-group">
					      <label>ประเภทผู้เช่า:</label>
					      <select required class="form-control" name="customertypeid">
					      <option value="">เลือกประเภทผู้เช่า..</option>
							<?php
								$sql1 = $db->prepare("SELECT TypeId,TypeName FROM usertype WHERE TypeId>0");
								$sql1->execute();
								$sql1->setFetchMode(PDO::FETCH_ASSOC);
								while ($row1 = $sql1->fetch()) { ?>
									<option value="<?=$row1['TypeId'] ?>" <?=($row1["TypeId"]==$row["UserTypeId"]? "selected" : "" )?> ><?php echo $row1["TypeName"]?></option>
							<?php } ?>  
						</select>
					    </div></div></div>		 
						
						<div class="panel panel-default">
      				  <div class="panel-body">				    
					    <div class="form-group">
					      <label>คำนำหน้าชื่อ:</label>
					      <input required type="text" class="form-control" name="prename" 
					     value="<?php echo $row["PreName"] ?>">
					    </div>
					    <div class="form-group">
					      <label>ชื่อ:</label>
					      <input required type="text" class="form-control" name="firstname" 
					      value="<?php echo $row["FirstName"] ?>">
					    </div>
					    <div class="form-group">
					      <label>นามสกุล:</label>
					      <input required type="text" class="form-control" name="lastname" 
					      value="<?php echo $row["LastName"] ?>">
					    </div>  
					    <div class="form-group">
					      <label>เบอร์โทรศัพท์:</label>
					      <input required type="text" class="form-control" name="telephone" 
					      id="telephone" style="letter-spacing: 1px;" value="<?php echo $row["Telephone"] ?>">
					    </div>
					</div>
					</div></div>

				
					<div class="col-md-5" style="margin-left: 30px">
					<div class="panel panel-default">
      				  <div class="panel-body">
						<div class="form-group">
					      <label>ที่อยู่:</label>
					      <textarea required class="form-control" name="address" rows="4"
					      ><?php echo $row["Address"] ?></textarea>
					    </div>	

					<div class="form-group">
					      <label>ชื่อหน่วยงาน:</label>
					      <input required type="text" class="form-control" name="company" 
					      value="<?php echo $row["Company"] ?>">
					    </div>
					 	</div></div>
					 	<br>

					<div class="panel panel-default">
      				  <div class="panel-body">
      					<div class="form-group">
					<label>บัญชีผู้ใช้งาน:</label>&nbsp;&nbsp;<?php echo $row["Username"] ?>
					</div>
					
					    <div class="form-group">
					      <label>รหัสผ่านใหม่:</label>
					      <div class="input-group"><span class="input-group-addon"><span class="fa fa-lock fa-lg"></span></span><input type="password" class="form-control" name="password" 
					      placeholder="หากต้องการเปลี่ยนรหัสผ่าน กรุณากรอกรหัสผ่านใหม่..">
					    </div></div>
					  </div>
    				</div>				    
					    <?php } ?>
						<div class="text-center">
						<input type="hidden" class="form-control" name="Id" value="<?php echo $_GET['Id']; ?>">
					   	  <button type="submit" class="btn btn-success">บันทึก</button>		
					   	  <a href="index.php" class="btn btn-danger">ยกเลิก</a>
						</div>
					</div>				
				</div>	  
			</form>		
			</div> 
						
						
        <?php include "../include/js.php"; ?>   
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js" type="text/javascript"></script>
        <script>
        $.mask.definitions['~']='[+-]';
		$('#citizenId').mask('9-9999-99999-99-9'); 
		$('#telephone').mask('999-9999999'); 		
		</script> 
    </body>
</html>
<?php } } ?>