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
	require "../../admin/PHPMailer/class.phpmailer.php";

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

try {
	$sql = "UPDATE activity SET
		StartDate = :startdate,
		EndDate = :enddate,
		RoomId = :setroomid,
		ActivityName = :activityname,
		Details = :details,
		ActivityTypeId = :setactivitytypeid,
		isBreak = :isbreak,
		FeeBreak = :setfeebreak,
		isLunch = :islunch,
		FeeLunch = :setfeelunch				
		WHERE ActivityId = :setactivityid";

	$setactivityid = $_POST['Id'];
	settype($setactivityid, "integer");

	$setroomid = $_POST['roomid'];
	settype($setroomid, "integer");

	$setactivitytypeid = $_POST['activitytypeid'];
	settype($setactivitytypeid, "integer");

	$setfeebreak = $_POST['feebreak'];
	settype($setfeebreak, "integer");

	$setfeelunch = $_POST['feelunch'];
	settype($setfeelunch, "integer");	

	$stmp = $db->prepare($sql);
	$stmp->bindValue("setactivityid" , $setactivityid);
	$stmp->bindValue("startdate" , $_POST["startdate"]);
	$stmp->bindValue("enddate" , $_POST["enddate"]);
	$stmp->bindValue("setroomid" , $setroomid);
	$stmp->bindValue("activityname" , $_POST["activityname"]);
	$stmp->bindValue("details" , $_POST["details"]);
	$stmp->bindValue("setactivitytypeid" , $setactivitytypeid);
	$stmp->bindValue("isbreak" , $_POST["isbreak"]);
	$stmp->bindValue("setfeebreak" , $setfeebreak);
	$stmp->bindValue("islunch" , $_POST["islunch"]);
	$stmp->bindValue("setfeelunch" , $setfeelunch);	
	$stmp->execute();

// if($stmp->execute()) {

// 	$stdateorg=$_POST["stdateorg"]; 
// 	$endateorg=$_POST["endateorg"];
// 	$roomidorg=$_POST["roomidorg"];

// 	$startdate=$_POST["startdate"]; 
// 	$enddate=$_POST["enddate"];

// 	if(($stdateorg!=$startdate) || ($endateorg!=$enddate) || ($roomidorg!=$setroomid)) {

// 		$sql5 = $db->prepare("SELECT CustomerId,ActivityTypeId,RentStatus,UpdatedAt FROM activity 
// 				WHERE ActivityId='".$_POST['Id']."'");
// 			$sql5->execute();						
// 			$sql5->setFetchMode(PDO::FETCH_ASSOC);
// 			if ($row5 = $sql5->fetch()) {
// 		$sql6 = $db->prepare("SELECT CitizenId,PreName,FirstName,LastName,Telephone,RoomName,TypeName FROM user,room,activitytype WHERE UserId='".$row5['CustomerId']."' AND RoomId='".$setroomid."' AND TypeId='".$row5['ActivityTypeId']."'");
// 			$sql6->execute();						
// 			$sql6->setFetchMode(PDO::FETCH_ASSOC);
// 			if ($row6 = $sql6->fetch()) {

// 		$sql7 = $db->prepare("SELECT RoomName FROM room WHERE RoomId='".$roomidorg."'");
// 			$sql7->execute();						
// 			$sql7->setFetchMode(PDO::FETCH_ASSOC);
// 			if ($row7 = $sql7->fetch()) {
// 				$roomidorgname=$row7['RoomName'];
// 			}

// 	$mail2 = new PHPMailer();  
// 	$mail2->SetLanguage( 'en', 'admin/PHPMailer/language/' ); 
// 	$mail2->CharSet = "utf-8";
// 	$mail2->IsHTML(true);
// 	$mail2->IsSMTP();  
// 	$mail2->Mailer = "smtp";
// 	$mail2->SMTPSecure = "ssl"; //Gmail: ssl
// 	$mail2->Host = "smtp.gmail.com"; //Gmail: smtp.gmail.com
// 	$mail2->Port = 465; //Gmail: 465
// 	$mail2->SMTPAuth = true; 

// 	$mail2->Username = "          "; // Email Sender 
// 	$mail2->Password = "          "; // Password Sender	 
	
// 	$mail2->FromName = "Room Management System";
// 	$mail2->AddAddress("thapanonz@hotmail.com", "Admin :Room Mangement System");  // Email Admin 
// 	              //  $row4['Username']
	 
// 	$mail2->Subject  = "ผู้ขอใช้บริการ \"แก้ไข\" ข้อมูลขอเช่าห้อง สัญญาเช่าเลขที่ ".$_POST['Id'];

// 	$Body="<b><u>ข้อมูลผู้ขอใช้บริการ</u></b>
// 		  <br><b>เลขบัตรประจำตัวประชาชน: </b>".$row6['CitizenId']."
// 		  <br><b>ชื่อ-นามสกุล: </b>".$row6['PreName'].$row6['FirstName']." ".$row6['LastName']."
// 		  <br><b>เบอร์โทรศัพท์: </b>".$row6['Telephone']."

// 		  <br><br><b><u>ข้อมูลการขอเช่าห้อง</u></b>
// 		  <br><b>สัญญาเช่าเลขที่: </b>".$_POST['Id']."
// 		  <br><b>วันที่เริ่มใช้บริการ: </b>".DateThai1($_POST["startdate"]).(($stdateorg!=$startdate)? 
// 		  	" <span style='color: red'>(จากเดิมวันที่ ".DateThai1($stdateorg).")</span>" : "")."		  
// 		  <br><b>วันสิ้นสุดใช้บริการ: </b>".DateThai1($_POST["enddate"]).(($endateorg!=$enddate)? 
// 		  	" <span style='color: red'>(จากเดิมวันที่ ".DateThai1($endateorg).")</span>" : "")."
// 		  <br><b>ห้องที่ใช้บริการ: </b>".$row6['RoomName'].(($roomidorg!=$setroomid)? 
// 		  	" <span style='color: red'>(จากเดิม ".$roomidorgname.")</span>" : "")." 
		  
// 		  <br><b>ชื่อโครงการ: </b>".$_POST["activityname"]."
// 		  <br><b>ประเภทการเช่า: </b>".$row6['TypeName']."
// 		  <br><b>อาหารว่าง: </b>".(($_POST["isbreak"]=="nothave")&&($_POST['feebreak']==0)? "ไม่มี" : "มี งบประมาณ ".$_POST['feebreak']." บาท")."	
// 		  <br><b>อาหารกลางวัน: </b>".(($_POST["islunch"]=="nothave")&&($_POST['feelunch']==0)? "ไม่มี" : "มี งบประมาณ ".$_POST['feelunch']." บาท")."	
// 		  <br><br><b>สถานะการเช่า: </b>".($row5['RentStatus']=="reserve"? "จอง" : "รอยืนยัน")."
// 		  <br><b>แก้ไขวันที่: </b>".DateThai1($row5['UpdatedAt'])."		  
// 		  <br>_____________________________________________________________
// 		  <br>กลุ่มงานบริการวิชาการ ศูนย์คอมพิวเตอร์ มหาวิทยาลัยสงขลานครินทร์
// 		  <br>โทรศัพท์ 0-7428-2116 ,โทรสาร 0-7428-2070";
// 	$mail2->Body = '<span style="font-family:Angsana New; font-size:17pt">'.$Body.'</span>';  
	 	 		
// 	if(!$mail2->Send()) { echo $mail2->ErrorInfo; }	
// 			}
// 	}

// } 

// 	header('Location: index.php'); 	
//   }

  header('Location: index.php'); 
}	
catch(PDOException $e) {
  echo $e->getMessage();
	} 
} } ?>