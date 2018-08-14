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

	function DateThai($strDate){
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear เวลา $strHour:$strMinute น.";
	}	
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ข้อมูลส่วนตัว</title>
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

		<div class="container">
			<br><br>
				
				<div class="row">				
					<div class="col-md-7 center-block">

					
			
	<?php $sql = $db->prepare("SELECT UserId,CitizenId,PreName,FirstName,LastName,UserTypeId,Company,Address,Telephone,Username,Password,CreatedAt,UpdatedAt FROM user WHERE UserId='".$_SESSION['UserId']."'");
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			if ($row = $sql->fetch()) { 
				 $sql1 = $db->prepare("SELECT TypeName FROM usertype WHERE TypeId='".$row['UserTypeId']."'"); 
							$sql1->execute();
							$sql1->setFetchMode(PDO::FETCH_ASSOC);
							if ($row1 = $sql1->fetch()) { ?>

					<div class="panel panel-default">
      				  <div class="panel-body">
      				  <div class="row">      				  
      				  
      				  <h3 style="padding-left: 35px"><b>ข้อมูลส่วนตัว</b></h3>					   	
      				  <hr>
      				  <div style="text-align:right; padding-right: 40px">
					   <a href="formedituserdetails.php" class="btn btn-warning">แก้ไขข้อมูล</a>
					</div>
						<div class="col-md-12" style="font-size: 17px; margin: 15px 0 10px 20px; text-align: left">
							
						<div class="form-group"><label>เลขบัตรประจำตัวประชาชน:</label>&nbsp;&nbsp;<?php echo $row["CitizenId"] ?></div>
					        		<label>ชื่อ-นามสกุล:</label>&nbsp;&nbsp;<?php echo $row["PreName"].$row["FirstName"]." ".$row["LastName"] ?><br>
					        		<label>เบอร์โทรศัพท์:</label>&nbsp;&nbsp;<?php echo $row["Telephone"] ?><br>
					        		<label>ประเภทผู้เช่า:</label>&nbsp;&nbsp;<?php echo $row1["TypeName"] ?><br> <?php } ?>
					        		<label>ชื่อหน่วยงาน:</label>&nbsp;&nbsp;<?php echo $row["Company"] ?><br>
									 <label>ที่อยู่:</label><br> <textarea style="padding: 15px" readonly="readonly" rows="3" cols="66"><?php echo $row["Address"] ?></textarea><br><br>

									 <label>บัญชีผู้ใช้งาน:</label>&nbsp;&nbsp;<a href="mailto:<?=$row['Username'] ?>"><?=$row["Username"] ?></a><br>
									 <hr width="92%" align="left">
									 <label>สมัครวันที่</label>&nbsp;&nbsp;<?php echo DateThai($row["CreatedAt"]) ?><br>
									 <label>แก้ไขล่าสุดวันที่</label>&nbsp;&nbsp;<?php echo DateThai($row["UpdatedAt"]) ?>
				<?php	} ?>
 					</div>
 					 </div>	
		 			</div>
					</div>									
				</div>	
			 </div>											  			
		</div>
		
		<?php include "../include/footer.php"; ?>						

        <?php include "../../admin/include/js.php"; ?>                         
    </body>
</html>
<?php } } ?>