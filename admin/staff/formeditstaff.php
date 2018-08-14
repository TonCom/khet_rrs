<?php

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	session_start();

	if(!isset($_SESSION['UserId'])||$_SESSION['UserTypeId']!=0) {
		
		header('Location: ../login.php?error=0'); 
	} else if($_SESSION['StaffLevel']!="sadmin") {
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
	$activepage = "staff";
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>แก้ไขข้อมูลเจ้าหน้าที่</title>
		<?php include "../include/css.php"; ?>
    </head>
    <body>
		<?php include "../include/banner.php"; ?>
			<?php include "../include/menu.php"; ?>	

		<?php
			$Id=$_GET['Id'];
			$sql = $db->prepare("SELECT Username,Password,PreName,FirstName,LastName,StaffLevel FROM user WHERE UserId=$Id");
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
      				  <h3 class="text-center"><b>แก้ไขข้อมูลเจ้าหน้าที่</b></h3><hr>
						<form role = "form" action="editstaff.php" method="post">													
						<div class="form-grop">
							<label>คำนำหน้าชื่อ:</label>
							<input required type="text" class="form-control" name="pname" value="<?php echo $row["PreName"] ?>">
						</div>
						<div class="form-group">
							<label>ชื่อ:</label>
							<input required type="text" class="form-control" name="name" value="<?php echo $row["FirstName"] ?>">
						</div>
						<div class="form-group">
							<label>นามสกุล:</label>
							<input required type="text" class="form-control" name="lastname" value="<?php echo $row["LastName"] ?>">
						</div>										
						<div class="form-group">
					      <label>ประเภทเจ้าหน้าที่:</label><br>
							<select required class="form-control" name="level">
						      <option value="">เลือกประเภทเจ้าหน้าที่..</option>
							  <option value="sadmin" <?=($row["StaffLevel"]=='sadmin'? "selected" : "" )?>>ผู้ดูแลระบบ</option>
							  <option value="admin" <?=($row["StaffLevel"]=='admin'? "selected" : "" )?>>เจ้าหน้าที่โครงการ</option>
							  <option value="lab" <?=($row["StaffLevel"]=='lab'? "selected" : "" )?>>เจ้าหน้าที่ห้องปฏิบัติการ</option>
							  <option value="maid" <?=($row["StaffLevel"]=='maid'? "selected" : "" )?>>แม่บ้าน</option>						
							</select>		 
					    </div>	
						<br>
					    <div class="form-group">
							<label>บัญชีผู้ใช้งาน:</label>&nbsp;&nbsp;<?php echo $row["Username"] ?>
						</div>
												
						<div class="form-group">
							<label>รหัสผ่านใหม่:</label>
							<div class="input-group"><span class="input-group-addon"><span class="fa fa-lock fa-lg"></span></span><input type="password" class="form-control" name="password" placeholder="หากต้องการเปลี่ยนรหัสผ่าน กรุณากรอกรหัสผ่านใหม่..">
						</div></div>

				<?php } ?>
					   
						<div style="text-align:center"><br>
						<input type="hidden" class="form-control" name="Id" value="<?php echo $Id ?>">
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