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
        <title>เพิ่มข้อมูลผู้เช่า</title>
		<?php include "../include/css.php"; ?>
    </head>
    <body>
		<?php include "../include/banner.php"; ?>

		<?php include "../include/menu.php"; ?>	

			<div class="container" >
			<br>
			<h3 style="margin-left:43px"><b>เพิ่มข้อมูลผู้เช่า</b></h3><br>
			<form role="form" action="addcustomer.php" method="post">	
				<div class="row" style="margin-left: 30px">				
					<div class="col-md-5">															

				<div class="panel panel-default">
      				  <div class="panel-body">	
					<div class="form-group">
					      <label>เลขบัตรประจำตัวประชาชน:</label>
					      <input required type="text" class="form-control" name="customerid" id="citizenId" style="letter-spacing: 1px;" placeholder="กรอกเลขบัตรประจำตัวประชาชน เช่น 1-8695-36437-52-6"> 
					</div>

					<div class="form-group">
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
						 </div>	</div>	
	
					<div class="panel panel-default">
      				  <div class="panel-body">	
					    <div class="form-group">
					      <label>คำนำหน้าชื่อ:</label>
					      <input required type="text" class="form-control" name="prename" 
					      placeholder="กรอกคำนำหน้าชื่อ เช่น นาย นาง นางสาว..">
					    </div>
					    <div class="form-group">
					      <label>ชื่อ:</label>
					      <input required type="text" class="form-control" name="firstname" 
					      placeholder="กรอกชื่อผู้เช่า..">
					    </div>
					    <div class="form-group">
					      <label>นามสกุล:</label>
					      <input required type="text" class="form-control" name="lastname" 
					      placeholder="กรอกนามสกุลผู้เช่า..">
					    </div>  
					    <div class="form-group">
					      <label>เบอร์โทรศัพท์:</label>
					      <input required type="text" class="form-control" name="telephone" 
					      id="telephone" style="letter-spacing: 1px;" placeholder="กรอกเบอร์โทรศัพท์ เช่น 087-5698563">
					    </div>
					</div>
					</div></div>


					<div class="col-md-5" style="margin-left: 30px">								

					<div class="panel panel-default">
      				  <div class="panel-body">		
					<div class="form-group">
					      <label>ที่อยู่:</label>
					      <textarea required class="form-control" name="address" rows="4"
					      placeholder="กรอกที่อยู่ของผู้เช่าที่สามารถติดต่อได้.."></textarea>
					    </div>	

					<div class="form-group">
					      <label>ชื่อหน่วยงาน:</label>
					      <input required type="text" class="form-control" name="company" 
					      placeholder="กรอกชื่อหน่วยงาน/คณะที่สังกัด เช่น คณะวิทยาศาสตร์">
					    </div></div></div>
					 <br>				   
					<div class="panel panel-default">
      				  <div class="panel-body">
      					<div class="form-group">
					      <label>บัญชีผู้ใช้งาน:</label>
					      <div class="input-group"><span class="input-group-addon"><span class="fa fa-user fa-lg"></span></span><input required type="email" class="form-control" name="username" placeholder="กรอกบัญชีผู้ใช้งาน เช่น thapanon@hotmail.com">
					    </div></div>	

					    <div class="form-group">
					      <label>รหัสผ่าน:</label>
					      <div class="input-group"><span class="input-group-addon"><span class="fa fa-lock fa-lg"></span></span><input required type="password" class="form-control" name="password" placeholder="กรอกรหัสผ่าน ควรมีอย่างน้อย 8 ตัวอักษร..">
					    </div></div>
					  </div>
    				</div>				    
					    
						<div class="text-center">
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