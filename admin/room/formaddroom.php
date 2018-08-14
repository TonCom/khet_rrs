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
	$activepage = "room";
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>เพิ่มข้อมูลห้องที่ให้บริการ</title>
		<?php include "../include/css.php"; ?>
    </head>
    <body>
		<?php include "../include/banner.php"; ?>	
			<?php include "../include/menu.php"; ?>	
	
			<div class="container">
			<br><br>
				<div class="row">
					<div class="col-md-6" style="margin-left: 30px">				

						<div class="panel panel-default">     					
      				  <div class="panel-body">
      				  <h3 class="text-center"><b>เพิ่มข้อมูลห้องที่ให้บริการ</b></h3>
						<hr>
						<form role="form" action="addroom.php" method="post">						   <div class="form-group">
							<label>ชื่อห้อง:</label>
							<input required type="text" class="form-control" name="roomname" placeholder="กรอกชื่อห้อง เช่น ห้องอบรมคอมพิวเตอร์ 101">
						</div>

						<div class="form-group">
						<div class="col-md-5" style="padding-left: 0"><label>จำนวนรับผู้เข้าอบรม:</label></div>
					     	<div class="col-md-5" style="padding-left: 0">
					     	 	<input required type="number" class="form-control" name="unit">
					     	</div>
					     	<label>คน/ห้อง</label>
 						</div>
					   
						<div class="form-grop">
							<label>รายละเอียดของห้อง:</label>
					      <textarea class="form-control" name="details" rows="8" placeholder="กรอกรายละเอียดห้องที่ต้องการ.."></textarea>
						</div>
						<br>
						
						<div class="form-group">
						<div class="col-md-6" style="padding-left: 0"><label>อัตราค่าบริการหน่วยงานภายใน:</label></div>
					     	<div class="col-md-4" style="padding-left: 0">
					     	 	<input required type="number" class="form-control" name="costint">
					     	</div>
					     	<label>บาท/วัน</label>
 						</div>

 						<div class="form-group">
						<div class="col-md-6" style="padding-left: 0"><label>อัตราค่าบริการหน่วยงานภายนอก:</label></div>
					     	<div class="col-md-4" style="padding-left: 0">
					     	 	<input required type="number" class="form-control" name="costext">
					     	</div>
					     	<label>บาท/วัน</label>
 						</div>

						<div class="form-group">
					      <label>ประเภทห้อง:&nbsp;</label>
					      <label class="radio-inline"><input required type="radio" name="type" value="labroom">ห้องอบรมคอมพิวเตอร์</label>
						  <label class="radio-inline"><input type="radio" name="type" value="seminaroom">ห้องสัมมนา</label>
					    </div>	
						
						<div class="form-group">
					      <label>สถานะห้อง: &nbsp;</label>
					      <label class="radio-inline"><input required type="radio" name="status" value="active">พร้อมให้บริการ</label>
						  <label class="radio-inline"><input type="radio" name="status" value="inactive">ไม่พร้อมให้บริการ</label>
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