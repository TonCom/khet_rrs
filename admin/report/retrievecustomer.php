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
	require "../include/fnDatethai.php";
	
// กรอก วันเวลาที่เริ่มต้น , วันเวลาที่สิ้นสุด , ห้อง --> หาว่า ขอใช้บริการ ได้ไหม?
if($_POST["customerid"]) {
	$customerid=$_POST["customerid"]; 		
		$sql = $db->prepare("SELECT UserId,CitizenId,PreName,FirstName,LastName,UserTypeId,Company,Address,Telephone,Username,Password,CreatedAt,UpdatedAt from user WHERE CitizenId='".$customerid."' AND Active='yes'");
		$sql->execute();
		$sql->setFetchMode(PDO::FETCH_ASSOC);
			if ($row = $sql->fetch()) { ?>
				<div class="col-md-2" style="padding-left: 15px; padding-top: 7px"><label>ชื่อ - นามสกุล:</label></div> <div class="col-md-4" style="padding-top: 7px"><a href="#" data-toggle="modal" data-target="#<?php echo $row["UserId"] ?>"><?=$row["PreName"].$row["FirstName"]." ".$row["LastName"] ?></a></div>

				 <div class="modal fade" id="<?php echo $row["UserId"] ?>" role="dialog">
				    <div class="modal-dialog">
				      <div class="modal-content">
				        <div class="modal-header">
				          <button type="button" class="close" data-dismiss="modal">&times;</button>
				          <h4 class="modal-title" style="text-align: center">ข้อมูลผู้ขอใช้บริการ</h4>
				        </div>
				        <div class="modal-body">
					        <div class="row">
					        	<div class="col-md-12" style="font-size: 16px; margin-left: 10px; text-align: left">
					        		<div class="form-group"><label>เลขบัตรประจำตัวประชาชน:</label> &nbsp;&nbsp;<?php echo $row["CitizenId"] ?></div>
					        		<label>ชื่อ-นามสกุล:</label>&nbsp;&nbsp;<?php echo $row["PreName"].$row["FirstName"]." ".$row["LastName"] ?><br>
					        		<label>เบอร์โทรศัพท์:</label>&nbsp;&nbsp;<?php echo $row["Telephone"] ?><br>
					        		
					        		<label>ประเภทผู้เช่า:</label>&nbsp;&nbsp;<?php $sql3 = $db->prepare("SELECT * FROM usertype WHERE TypeId='".$row['UserTypeId']."'"); 
							$sql3->execute();
							$sql3->setFetchMode(PDO::FETCH_ASSOC);
							while ($row3 = $sql3->fetch()) { 
					        		 echo $row3["TypeName"] ?><br> 
					     <?php } ?>

					        		<label>ชื่อหน่วยงาน:</label>&nbsp;&nbsp;<?php echo $row["Company"] ?><br>
									 <label>ที่อยู่:</label><br> <textarea style="padding: 15px" readonly="readonly" rows="3" cols="63"><?php echo $row["Address"] ?></textarea><br><br>

									 <label>บัญชีผู้ใช้งาน:</label>&nbsp;&nbsp;<?php echo $row["Username"] ?><br>									
								</div>
							</div>
				        </div>
				        <div class="modal-footer">	
				        <div class="form-group">
				        <label>สร้างเมื่อวันที่</label>&nbsp;&nbsp;<?php echo DateThai($row["CreatedAt"]) ?> น.<br>
				        <?php if ($row["UpdatedAt"]!='0000-00-00 00:00:00') { ?>
				         <label>แก้ไขล่าสุดเมื่อวันที่</label>&nbsp;&nbsp;<?php echo DateThai($row["UpdatedAt"]) ?> น.<br> <?php } ?>				        
				        </div>
				        		<!-- <a href='../customer/formeditcustomer.php?Id=<?=$row['UserId']?>' class="btn btn-primary">แก้ไขข้อมูล</a> -->			     			        	
				          <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
				        </div>
				      </div>      
				    </div>
				  </div>
		
		
				 <?php } 
			else { 				
			return false;
		}
} } } ?>

