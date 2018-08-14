<?php

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	session_start();
$_SESSION['UserId'] ="xxx";
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
	require "../PHPMailer/class.phpmailer.php";
	
	//Set Path
	$isSubfolder = true;
	$activepage = "adlistallrent";

	function DateThai1($strDate){
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

	function setstatusmodal($status){
		if($status=="reserve"){ 
			return "<span class='label label-default'>จอง</span>"; }
		else if($status=="wconfirm"){ 
			return "<span class='label label-primary'>รอยืนยัน</span>"; }
		else if($status=="confirm"){ 
			return "<span class='label label-success'>ยืนยัน</span>"; }
		else if($status=="cancel"){ 
			return "<span class='label label-danger'>ยกเลิก</span>"; }
	}

	function setstatus($id,$status){
		if($status=="reserve"){ 
			return "<a href='?ActivityId=$id&RentStatus=$status&ctrl=editstatus' style='text-decoration: none' onclick=\"return confirm('คุณต้องการเปลี่ยนรายการเช่าห้องนี้เป็นสถานะ \' รอยืนยัน \' หรือไม่ ?');\">
			<div class='label label-default'>จอง</div></a>"; }
		else if($status=="wconfirm"){ 
			return "<a href='?ActivityId=$id&RentStatus=$status&ctrl=editstatus' style='text-decoration: none' onclick=\"return confirm('คุณต้องการเปลี่ยนรายการเช่าห้องนี้เป็นสถานะ \' ยืนยัน \' หรือไม่ ?');\">
			<div class='label label-primary'>รอยืนยัน</div></a>"; }			
		else if($status=="confirm"){ 
			return "<a href='?ActivityId=$id&RentStatus=$status&ctrl=editstatus' style='text-decoration: none' onclick=\"return confirm('คุณต้องการเปลี่ยนรายการเช่าห้องนี้เป็นสถานะ \' ยกเลิก \' หรือไม่ ?');\"><div class='label label-success'>ยืนยัน</div></a>"; }
		else if($status=="cancel"){ 
			return "<span class='label label-danger'>ยกเลิก</span>"; }
	}

if (isset($_GET["ctrl"])&&$_GET["ctrl"]=="editstatus") {
	$sql = "UPDATE activity SET 
		RentStatus =:Status
		WHERE ActivityId = :setID";
	$setid = $_GET['ActivityId'];
	settype($setid, "integer");
	$stmp = $db->prepare($sql);
		if($_GET["RentStatus"]=="reserve") {
			$stmp->bindValue("Status" , 'wconfirm'); }
	  	else if($_GET["RentStatus"]=="wconfirm") {
			$stmp->bindValue("Status" , 'confirm'); }
		else if($_GET["RentStatus"]=="confirm") {
			$stmp->bindValue("Status" , 'cancel'); }		
	$stmp->bindValue("setID" , $setid);

	if($stmp->execute()) {
		if($_GET["RentStatus"]=="wconfirm") {
		   $sql5 = $db->prepare("SELECT CustomerId,StartDate,EndDate,RoomId,ActivityName from activity 
				WHERE ActivityId ='".$_GET['ActivityId']."'");
			$sql5->execute();
			$sql5->setFetchMode(PDO::FETCH_ASSOC);
			if ($row5 = $sql5->fetch()) {
			
	// 		$sql6 = $db->prepare("SELECT PreName,FirstName,LastName,Username,RoomName FROM user,room 
	// 			WHERE UserId='".$row5['CustomerId']."' AND RoomId='".$row5['RoomId']."'");
	// 		$sql6->execute();						
	// 		$sql6->setFetchMode(PDO::FETCH_ASSOC);
	// 		if ($row6 = $sql6->fetch()) {

	// $mail1 = new PHPMailer();  
	// $mail1->SetLanguage( 'en', 'admin/PHPMailer/language/' ); 
	// $mail1->CharSet = "utf-8";
	// $mail1->IsHTML(true);
	// $mail1->IsSMTP();  
	// $mail1->Mailer = "smtp";
	// $mail1->SMTPSecure = "ssl"; //Gmail: ssl
	// $mail1->Host = "smtp.gmail.com"; //Gmail: smtp.gmail.com
	// $mail1->Port = 465; //Gmail: 465
	// $mail1->SMTPAuth = true; 

	// $mail1->Username = "          "; // Email Sender 
	// $mail1->Password = "          "; // Password Sender	 
	
	// $mail1->FromName = "Room Management System";
	// $mail1->AddAddress("thapanonz@hotmail.com", $row6['PreName'].$row6['FirstName']." ".$row6['LastName']);  //  $row5['Username']     Email Reciever
	 
	// $mail1->Subject  = "การขอเช่าห้องอยู่ในสถานะ \"ยืนยัน\" เรียบร้อยแล้ว";

	// $Body="<b>ผู้ขอใช้บริการ: </b>".$row6['PreName'].$row6['FirstName']." ".$row6['LastName'].
	// 	  "<br><b>วันที่เริ่มใช้บริการ: </b>".DateThai1($row5['StartDate']).
	// 	  "<br><b>วันสิ้นสุดใช้บริการ: </b>".DateThai1($row5['EndDate']).
	// 	  "<br><b>ห้องที่ใช้บริการ: </b>".$row6['RoomName'].
	// 	  "<br><b>ชื่อโครงการ: </b>".$row5['ActivityName'];

	// $mail1->AddEmbeddedImage("../../images/mapcc.jpg", "mapcc", "mapcc.jpg", "base64", "image/jpg");	
	// $Body.="<br><br>การขอเช่าห้องของท่านอยู่ในสถานะ <b>\"ยืนยัน\"</b> เรียบร้อยแล้ว 
	// 	  <br>หากต้องการข้อมูลเพิ่มเติม กรุณาติดต่อเจ้าหน้าที่ศูนย์คอมพิวเตอร์ 
	// 	  <br><br><img src='cid:mapcc' width='440' height='280'/>
	// 	  <br>_____________________________________________________________
	// 	  <br>กลุ่มงานบริการวิชาการ ศูนย์คอมพิวเตอร์ มหาวิทยาลัยสงขลานครินทร์
	// 	  <br>โทรศัพท์ 0-7428-2116 ,โทรสาร 0-7428-2070";
	// $mail1->Body = '<span style="font-family:Angsana New; font-size:17pt">'.$Body.'</span>';  
	 	 		
	// if(!$mail1->Send()) { echo $mail1->ErrorInfo; }	
	// 		}

		$sql1 = $db->prepare("SELECT ActivityId,CustomerId,StartDate,EndDate,RoomId,ActivityName from activity 
		WHERE (
	  		(('".$row5['StartDate']."' BETWEEN startdate AND enddate)
			OR ('".$row5['EndDate']."' BETWEEN startdate AND enddate))	
		OR 
			((startdate BETWEEN '".$row5['StartDate']."' AND '".$row5['EndDate']."')  
			OR (enddate BETWEEN '".$row5['StartDate']."' AND '".$row5['EndDate']."'))
	        ) 
		    AND RoomId='".$row5['RoomId']."' AND RentStatus!='confirm' AND RentStatus!='cancel'");
			$sql1->execute();
			$count = $sql1->rowCount();
			if($count>0){
			$sql1->setFetchMode(PDO::FETCH_ASSOC);
				while ($row1 = $sql1->fetch()) {

					$sql2 = "UPDATE activity SET RentStatus='cancel' WHERE ActivityId=:setactivityid";
					$setactivityid = $row1['ActivityId'];
					settype($setactivityid,"integer");

					$stmp2 = $db->prepare($sql2);
					$stmp2->bindValue("setactivityid" , $setactivityid);
					$stmp2->execute();

	// 			$sql4 = $db->prepare("SELECT PreName,FirstName,LastName,Username,RoomName FROM user,room 
	// 					WHERE UserId='".$row1['CustomerId']."' AND RoomId='".$row1['RoomId']."'");
	// 					$sql4->execute();						
	// 					$sql4->setFetchMode(PDO::FETCH_ASSOC);
	// 					if ($row4 = $sql4->fetch()) {

	// $mail = new PHPMailer();  
	// $mail->SetLanguage( 'en', 'admin/PHPMailer/language/' ); 
	// $mail->CharSet = "utf-8";
	// $mail->IsHTML(true);
	// $mail->IsSMTP();  
	// $mail->Mailer = "smtp";
	// $mail->SMTPSecure = "ssl"; //Gmail: ssl
	// $mail->Host = "smtp.gmail.com"; //Gmail: smtp.gmail.com
	// $mail->Port = 465; //Gmail: 465
	// $mail->SMTPAuth = true; 

	// $mail->Username = "            "; // Email Sender 
	// $mail->Password = "            "; // Password Sender
	 	
	// $mail->FromName = "Room Management System";
	// $mail->AddAddress("thapanonz@hotmail.com", $row4['PreName'].$row4['FirstName']." ".$row4['LastName']);  //        $row4['Username']     Email Reciever 	                
	 
	// $mail->Subject  = "การขอเช่าห้องอยู่ในสถานะ \"ยกเลิก\"";

	// $Body="<b>ผู้ขอใช้บริการ: </b>".$row4['PreName'].$row4['FirstName']." ".$row4['LastName'].
	// 	  "<br><b>วันที่เริ่มใช้บริการ: </b>".DateThai1($row1['StartDate']).
	// 	  "<br><b>วันสิ้นสุดใช้บริการ: </b>".DateThai1($row1['EndDate']).
	// 	  "<br><b>ห้องที่ใช้บริการ: </b>".$row4['RoomName'].
	// 	  "<br><b>ชื่อโครงการ: </b>".$row1['ActivityName'].
	// 	  "<br><br>การขอเช่าห้องของท่านอยู่ในสถานะ <b>\"ยกเลิก\"</b>
	// 	  <br>หากต้องการข้อมูลเพิ่มเติม กรุณาติดต่อเจ้าหน้าที่ศูนย์คอมพิวเตอร์ 
	// 	  <br>_____________________________________________________________
	// 	  <br>กลุ่มงานบริการวิชาการ ศูนย์คอมพิวเตอร์ มหาวิทยาลัยสงขลานครินทร์
	// 	  <br>โทรศัพท์ 0-7428-2116 ,โทรสาร 0-7428-2070";
	// $mail->Body = '<span style="font-family:Angsana New; font-size:17pt">'.$Body.'</span>';  
	 	 		
	// if(!$mail->Send()) { echo $mail->ErrorInfo; }
	// 	 }
	   }
	 }				
    }

  } 
 //  else if ($_GET["RentStatus"]=="confirm"){
 //  		$sql5 = $db->prepare("SELECT CustomerId,StartDate,EndDate,RoomId,ActivityName from activity 
	// 			WHERE ActivityId ='".$_GET['ActivityId']."'");
	// 	$sql5->execute();
	// 	$sql5->setFetchMode(PDO::FETCH_ASSOC);
	// 	if ($row5 = $sql5->fetch()) {
			
	// 		$sql6 = $db->prepare("SELECT PreName,FirstName,LastName,Username,RoomName FROM user,room 
	// 			WHERE UserId='".$row5['CustomerId']."' AND RoomId='".$row5['RoomId']."'");
	// 		$sql6->execute();						
	// 		$sql6->setFetchMode(PDO::FETCH_ASSOC);
	// 		if ($row6 = $sql6->fetch()) {
    
 //    $mail1 = new PHPMailer();  
	// $mail1->SetLanguage( 'en', 'admin/PHPMailer/language/' ); 
	// $mail1->CharSet = "utf-8";
	// $mail1->IsHTML(true);
	// $mail1->IsSMTP();  
	// $mail1->Mailer = "smtp";
	// $mail1->SMTPSecure = "ssl"; //Gmail: ssl
	// $mail1->Host = "smtp.gmail.com"; //Gmail: smtp.gmail.com
	// $mail1->Port = 465; //Gmail: 465
	// $mail1->SMTPAuth = true; 

	// $mail1->Username = "           "; // Email Sender 
	// $mail1->Password = "           "; // Password Sender	 
	
	// $mail1->FromName = "Room Management System";
	// $mail1->AddAddress("thapanonz@hotmail.com", $row6['PreName'].$row6['FirstName']." ".$row6['LastName']);  //  $row5['Username']     Email Reciever
	 
	// $mail1->Subject  = "การขอเช่าห้องอยู่ในสถานะ \"ยกเลิก\"";

	// $Body="<b>ผู้ขอใช้บริการ: </b>".$row6['PreName'].$row6['FirstName']." ".$row6['LastName'].
	// 	  "<br><b>วันที่เริ่มใช้บริการ: </b>".DateThai1($row5['StartDate']).
	// 	  "<br><b>วันสิ้นสุดใช้บริการ: </b>".DateThai1($row5['EndDate']).
	// 	  "<br><b>ห้องที่ใช้บริการ: </b>".$row6['RoomName'].
	// 	  "<br><b>ชื่อโครงการ: </b>".$row5['ActivityName'].
	// 	  "<br><br>การขอเช่าห้องของท่านอยู่ในสถานะ <b>\"ยกเลิก\"</b>
	// 	  <br>หากต้องการข้อมูลเพิ่มเติม กรุณาติดต่อเจ้าหน้าที่ศูนย์คอมพิวเตอร์ 
	// 	  <br>_____________________________________________________________
	// 	  <br>กลุ่มงานบริการวิชาการ ศูนย์คอมพิวเตอร์ มหาวิทยาลัยสงขลานครินทร์
	// 	  <br>โทรศัพท์ 0-7428-2116 ,โทรสาร 0-7428-2070";	
	// $mail1->Body = '<span style="font-family:Angsana New; font-size:17pt">'.$Body.'</span>';  
	 	 		
	// if(!$mail1->Send()) { echo $mail1->ErrorInfo; }	
	// 		}
	// 	}
 //  }
 }
}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>รายการเช่าห้องทั้งหมด | จองโดยเจ้าหน้าที่</title>
		<?php include "../include/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.min.css">
    </head>
    <style>   
   	th,td{ 
   		font-size: 14px; 		
   		text-align: center;
    }
    </style>
    <body>
		<?php include "../include/banner.php"; ?>	
			<?php include "../include/menu.php"; ?>	
				
			<div class="container-fluid">
				<div class="row">
					<div class="table-responsive" style="margin: 0 auto; width:85%">				
					
				<?php $sql = $db->prepare("SELECT COUNT(ActivityId) AS countacti FROM activity 
               	 	WHERE ReserveBy='admin'");
                    $sql->execute();
                    $sql->setFetchMode(PDO::FETCH_ASSOC);
                    if ($row = $sql->fetch()) { ?>                  	
						<h3><b>รายการเช่าห้อง จองโดย <span class='label label-info' 
						style="padding: 0 15px 0 15px">เจ้าหน้าที่</span> ทั้งหมด 
						<?=($row["countacti"]!=0? number_format($row["countacti"]) :  "0" )?> รายการ</b></h3> 
				<?php } ?>
			
			<table class="table table-bordered table-hover table-striped" id="TableActivity">
			<thead>
		      <tr bgcolor="#CCCCCC">
		        <th>#</th>	
		        <th>วันเวลาที่เริ่ม</th>	
		        <th>วันเวลาสิ้นสุด</th>	        
		        <th>ห้องที่ใช้บริการ</th>
		        <th>ชื่อโครงการ</th>
		        <th>ผู้ขอใช้บริการ</th>
		        <th>รายละเอียด</th>
		        <th>สถานะ</th>	       		        		        
		        <th>วันที่จอง</th>		        
		        <th></th>
		      </tr>
		    </thead>
			<tbody>
		
			<?php
			$sql = $db->prepare("SELECT ActivityId,CustomerId,RoomId,StartDate,EndDate,ActivityName,Details,ActivityTypeId,IsBreak,FeeBreak,IsLunch,FeeLunch,FeeRoom,FeeOthers,AdminId,LabId,MaidId,RentStatus,CreatedAt,UpdatedAt,ReserveBy FROM activity WHERE ReserveBy='admin' ORDER BY ActivityId DESC");
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			while ($row = $sql->fetch()) {
			echo "<tr>";
				echo "<td><b>" .$row["ActivityId"]."</b></td>";
				echo "<td>" .DateThai($row["StartDate"])."</td>";
				echo "<td>" .DateThai($row["EndDate"])."</td>";

				$sql1 = $db->prepare("SELECT * FROM room WHERE RoomId='".$row['RoomId']."'"); 
							$sql1->execute();
							$sql1->setFetchMode(PDO::FETCH_ASSOC);
							while ($row1 = $sql1->fetch()) { 
								echo "<td>".$row1["RoomName"]."</td>";
				
				echo "<td>" .$row["ActivityName"]."</td>";

				$sql2 = $db->prepare("SELECT * FROM user WHERE UserId='".$row['CustomerId']."'"); 
							$sql2->execute();
							$sql2->setFetchMode(PDO::FETCH_ASSOC);
							while ($row2 = $sql2->fetch()) { 

				echo "<td>"; ?> 
				<a href="#" data-toggle="modal" data-target="#<?php echo $row2["UserId"] ?>"><?=$row2["FirstName"]." ".$row2["LastName"] ?></a>

                <div class="modal fade" id="<?php echo $row2["UserId"] ?>" role="dialog">
				    <div class="modal-dialog">
				      <div class="modal-content">
				        <div class="modal-header">
				          <button type="button" class="close" data-dismiss="modal">&times;</button>
				          <h4 class="modal-title">ข้อมูลผู้ขอใช้บริการ</h4>
				        </div>
				        <div class="modal-body">
					        <div class="row">
					        	<div class="col-md-12" style="font-size: 16px; margin-left: 10px; text-align: left">
					        		<div class="form-group"><label>เลขบัตรประจำตัวประชาชน:</label> &nbsp;&nbsp;<?php echo $row2["CitizenId"] ?></div>
					        		<label>ชื่อ-นามสกุล:</label>&nbsp;&nbsp;<?php echo $row2["PreName"].$row2["FirstName"]." ".$row2["LastName"] ?><br>
					        		<label>เบอร์โทรศัพท์:</label>&nbsp;&nbsp;<?php echo $row2["Telephone"] ?><br>
					        		
					        		<label>ประเภทผู้เช่า:</label>&nbsp;&nbsp;<?php $sql3 = $db->prepare("SELECT * FROM usertype WHERE TypeId='".$row2['UserTypeId']."'"); 
							$sql3->execute();
							$sql3->setFetchMode(PDO::FETCH_ASSOC);
							while ($row3 = $sql3->fetch()) { 
					        		 echo $row3["TypeName"] ?><br> 
					     <?php } ?>

					        		<label>ชื่อหน่วยงาน:</label>&nbsp;&nbsp;<?php echo $row2["Company"] ?><br>
									 <label>ที่อยู่:</label><br> <textarea style="padding: 15px" readonly="readonly" rows="3" cols="63"><?php echo $row2["Address"] ?></textarea><br><br>

									 <label>บัญชีผู้ใช้งาน:</label>&nbsp;&nbsp;<a href="mailto:<?=$row2['Username'] ?>"><?=$row2["Username"] ?></a><br>								 
								</div>
							</div>
				        </div>
				        <div class="modal-footer">	
				        <div class="form-group">
				        <label>สร้างเมื่อวันที่</label>&nbsp;&nbsp;<?php echo DateThai($row2["CreatedAt"]) ?> น.<br>
				        <?php if ($row2["UpdatedAt"]!='0000-00-00 00:00:00') { ?>
				         <label>แก้ไขล่าสุดเมื่อวันที่</label>&nbsp;&nbsp;<?php echo DateThai($row2["UpdatedAt"]) ?> น.<br> <?php } ?>
				        
				        </div>
				        		<a href='../customer/formeditcustomer.php?Id=<?=$row['CustomerId']?>' class="btn btn-primary">แก้ไขข้อมูล</a>			     			        	
				          <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
				        </div>
				      </div>      
				    </div>
				  </div>
				
				<?php
			    echo "</td>";	

			    echo "<td>"; ?> 
				<a href="#" data-toggle="modal" data-target="#<?php echo $row["ActivityId"] ?>">ดูรายละเอียด</a>

                <div class="modal fade" id="<?php echo $row["ActivityId"] ?>" role="dialog">
				    <div class="modal-dialog modal-lg">
				      <div class="modal-content">
				        <div class="modal-header">
				          <button type="button" class="close" data-dismiss="modal">&times;</button>
				          <h4 class="modal-title">ข้อมูลการขอเช่าห้อง</h4>
				        </div>
				    <div class="modal-body">
					    <div class="row">
					        <div class="col-md-6" style="font-size: 16px; margin-left: 10px; text-align: left">
					        		<div class="form-group"><label>สัญญาเช่าเลขที่:</label> &nbsp;&nbsp;<?php echo $row["ActivityId"] ?><br>
					        		<label>ผู้ขอใช้บริการ:</label>&nbsp;&nbsp;<?php echo $row2["PreName"].$row2["FirstName"]." ".$row2["LastName"] ?></div>
					        		<label>วันเวลาที่เริ่ม:</label>&nbsp;&nbsp;<?php echo DateThai($row["StartDate"])." น." ?><br>
					        		<label>วันเวลาที่สิ้นสุด:</label>&nbsp;&nbsp;<?php echo DateThai($row["EndDate"])." น." ?><br>
					        		<label>ห้องที่ใช้บริการ:</label>&nbsp;&nbsp;<?php echo $row1["RoomName"] ?><br><br>
					        		
					        		<label>ชื่อโครงการ:</label>&nbsp;&nbsp;<?php echo $row["ActivityName"] ?><br>
					        		<label>ประเภทการเช่า:</label>&nbsp;&nbsp;<?php $sql4 = $db->prepare("SELECT * FROM activitytype WHERE TypeId='".$row['ActivityTypeId']."'"); 
									$sql4->execute();
									$sql4->setFetchMode(PDO::FETCH_ASSOC);
									while ($row4 = $sql4->fetch()) { 
							        		 echo $row4["TypeName"] ?><br>
							        <?php } ?>
									<label>รายละเอียดการเช่า:</label><br> <textarea style="padding: 15px" readonly="readonly" rows="2" cols="45"><?php echo $row["Details"] ?></textarea>
					        </div>
					        
					        <div class="col-md-5" style="font-size: 16px; margin-left: 10px; text-align: left">
					        		<label>อาหารว่าง:</label>&nbsp;&nbsp;<?php echo ($row["IsBreak"]=="nothave"? "<span class='label label-danger' style='padding: 0 8px 0 8px'>ไม่มี</span>" : "<span class='label label-success' style='padding: 0 8px 0 8px'>มี</span> งบประมาณ ".number_format($row["FeeBreak"])." บาท") ?><br>
					        		 <label>อาหารกลางวัน:</label>&nbsp;&nbsp;<?php echo ($row["IsLunch"]=="nothave"? "<span class='label label-danger' style='padding: 0 8px 0 8px'>ไม่มี</span>" : "<span class='label label-success' style='padding: 0 8px 0 8px'>มี</span> งบประมาณ ".number_format($row["FeeLunch"])." บาท") ?><br>
					        		 <label>ค่าห้องที่ใช้บริการ:</label>&nbsp;&nbsp;<?php echo ($row["FeeRoom"]==0? "<span class='label label-default' style='padding: 0 8px 0 8px'>ไม่พบการกรอกข้อมูล</span>" : number_format($row["FeeRoom"])." บาท" )?><br>
					        		 <label>ค่าใช้จ่ายอื่นๆ:</label>&nbsp;&nbsp;<?php echo ($row["FeeOthers"]==0? "<span class='label label-default' style='padding: 0 8px 0 8px'>ไม่พบการกรอกข้อมูล</span>" :  number_format($row["FeeOthers"])." บาท" )?><br><br><br>
																			
									<label>เจ้าหน้าที่โครงการ:</label>&nbsp;&nbsp;<?php $sql5 = $db->prepare("SELECT * FROM user WHERE UserId='".$row['AdminId']."'"); 
									$sql5->execute();
									$sql5->setFetchMode(PDO::FETCH_ASSOC);
									while ($row5 = $sql5->fetch()) { 
							        		 echo $row5["PreName"].$row5["FirstName"]." ".$row5["LastName"] ?><br> 
							     	<?php } ?>

							     	<label>เจ้าหน้าที่ห้องปฏิบัติการ:</label>&nbsp;&nbsp;<?php $sql6 = $db->prepare("SELECT * FROM user WHERE UserId='".$row['LabId']."'"); 
									$sql6->execute();
									$sql6->setFetchMode(PDO::FETCH_ASSOC);
									while ($row6 = $sql6->fetch()) { 
							        		 echo $row6["PreName"].$row6["FirstName"]." ".$row6["LastName"] ?><br> 
							     	<?php } ?>

							     	<label>แม่บ้าน:</label>&nbsp;&nbsp;<?php $sql7 = $db->prepare("SELECT * FROM user WHERE UserId='".$row['MaidId']."'"); 
									$sql7->execute();
									$sql7->setFetchMode(PDO::FETCH_ASSOC);
									while ($row7 = $sql7->fetch()) { 
							        		 echo $row7["PreName"].$row7["FirstName"]." ".$row7["LastName"] ?><br><br><br> 
							     	<?php } ?>
									
									<label>สถานะการเช่า:</label>&nbsp;&nbsp;<?php echo setstatusmodal($row["RentStatus"]) ?>					        										 
							</div>
					   	</div>
				    </div>
				        <div class="modal-footer">	
				        <div class="form-group">
				        <label>จองเมื่อวันที่</label>&nbsp;&nbsp;<?php echo DateThai($row["CreatedAt"]) ?> น.<br>
				        <?php if ($row["UpdatedAt"]!='0000-00-00 00:00:00') { ?>
				         <label>แก้ไขล่าสุดเมื่อวันที่</label>&nbsp;&nbsp;<?php echo DateThai($row["UpdatedAt"]) ?> น.<br> <?php } ?>
				        
				        </div>
				        		<a href='formeditactivity.php?Id=<?=$row['ActivityId']?>&ctrl=index' class="btn btn-primary">แก้ไขข้อมูล</a>			     			        	
				          <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
				        </div>
				      </div>      
				    </div>
				  </div>
				
				<?php
			    echo "</td>"; 
				} 
			}
										
				echo "<td>" .setstatus($row["ActivityId"],$row["RentStatus"])."</td>";
				echo "<td>" .DateThai($row["CreatedAt"])."</td>";				
				echo "<td style='padding: 5px'>";
            	   echo "<a href='formeditactivity.php?Id=".$row['ActivityId']."&ctrl=index' data-toggle='tooltip' title='แก้ไข'><i style='font-size:23px' class='fa fa-pencil' aria-hidden='true'></i></a>";               
            	   echo " <a href='deleteactivity.php?Id=".$row['ActivityId']."&ctrl=index' onclick=\"return confirm('คุณต้องการลบรายการเช่าห้องนี้หรือไม่ ?');\" data-toggle='tooltip' title='ลบ'><i style='font-size:23px' class='fa fa-trash-o text-danger' aria-hidden='true'></i></a>";
                echo "</td>";	
			 echo "</tr>";
			 
			} ?>
		
			</tbody>
		 </table>
			</div>
		</div>
	</div>
				
        <?php include "../include/js.php"; ?>    
        <script src="../js/jquery.dataTables.min.js"></script> 
        <script>
        	$(document).ready(function() {
        		$.fn.dataTable.ext.pager.numbers_length = 5;
		    	$('#TableActivity').DataTable( {
		    	  "aaSorting": [[ 0, "desc" ]],
		    	   
		    	   "language": {
		    	  	"sSearch": "ค้นหา:",
		    	  	"sZeroRecords": "ไม่พบข้อมูลที่ต้องการค้นหา", 
		    	  	"sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
		    	  	"sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 รายการ",
		    	   	"infoFiltered": "(กรองข้อมูลจากทั้งหมด _MAX_ รายการ)",
		    	   	"oPaginate": {  		    	   		   
				        "sPrevious": "ก่อนหน้า",
				        "sNext": "ถัดไป"				        
        				}  
		    	   },
		    	   
			       "dom": 'ftipr',
			       "aLengthMenu": [15],
	   		      // "lengthMenu": [ 15 ]

			       scrollY: "566px",			         
			       scrollCollapse: true,
			       scroller: true				        			            		   		   	
			 	});
			});
        </script>  
        <script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();   
		});
		</script> 
    </body>
</html>
<?php } } ?>