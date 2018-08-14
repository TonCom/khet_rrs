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
        <title>เพิ่มข้อมูลเจ้าหน้าที่</title>
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
      				  <h3 class="text-center"><b>เพิ่มข้อมูลเจ้าหน้าที่</b></h3><hr>
						<form role = "form" action="addstaff.php" method="post">													
						<div class="form-grop">
							<label>คำนำหน้าชื่อ:</label>
							<input required type="text" class="form-control" name="pname" placeholder="กรอกคำนำหน้าชื่อ เช่น นาย นาง นางสาว..">
						</div>
						<div class="form-group">
							<label>ชื่อ:</label>
							<input required type="text" class="form-control" name="name" placeholder="กรอกชื่อผู้ใช้งาน..">
						</div>
						<div class="form-group">
							<label>นามสกุล:</label>
							<input required type="text" class="form-control" name="lastname" placeholder="กรอกนามสกุลผู้ใช้งาน..">
						</div>										
						<div class="form-group">
					      <label>ประเภทเจ้าหน้าที่:</label><br>
							<select required class="form-control" name="stafflevel">
						      <option value="">เลือกประเภทเจ้าหน้าที่..</option>
							  <option value="sadmin">ผู้ดูแลระบบ</option>
							  <option value="admin">เจ้าหน้าที่โครงการ</option>
							  <option value="lab">เจ้าหน้าที่ห้องปฏิบัติการ</option>
							  <option value="maid">แม่บ้าน</option>							
							</select>		 
					    </div>	
						<br>
					    <div class="form-group">
							<label>บัญชีผู้ใช้งาน:</label>
							<div class="input-group"><span class="input-group-addon"><span class="fa fa-user fa-lg"></span></span><input required type="email" class="form-control" name="user" placeholder="กรอกบัญชีผู้ใช้งาน เช่น thapanon@hotmail.com">
						</div></div>
						<div class="form-group">
							<label>รหัสผ่าน:</label>
							<div class="input-group"><span class="input-group-addon"><span class="fa fa-lock fa-lg"></span></span><input required type="password" class="form-control" name="password" placeholder="กรอกรหัสผ่าน ควรมีอย่างน้อย 8 ตัวอักษร..">
						</div></div>

						
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