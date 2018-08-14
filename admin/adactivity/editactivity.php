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
	require "../PHPMailer/class.phpmailer.php";

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
		FeeLunch = :setfeelunch,
		FeeRoom = :setfeeroom,
		FeeOthers = :setfeeothers,
		AdminId = :setadminid,
		LabId = :setlabid,
		MaidId = :setmaidid,
		RentStatus = :rentstatus
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

	$setfeeroom = $_POST['feeroom'];
	settype($setfeeroom, "integer");

	$setfeeothers = $_POST['feeothers'];
	settype($setfeeothers, "integer");

	$setadminid = $_POST['adminid'];
	settype($setadminid, "integer");

	$setlabid = $_POST['labid'];
	settype($setlabid, "integer");

	$setmaidid = $_POST['maidid'];
	settype($setmaidid, "integer");

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
	$stmp->bindValue("setfeeroom" , $setfeeroom);
	$stmp->bindValue("setfeeothers" , $setfeeothers);
	$stmp->bindValue("setadminid" , $setadminid);
	$stmp->bindValue("setlabid" , $setlabid);
	$stmp->bindValue("setmaidid" , $setmaidid);
	$stmp->bindValue("rentstatus" , $_POST["rentstatus"]);

if($stmp->execute()) { 
	
	$stdateorg=$_POST["stdateorg"]; 
	$endateorg=$_POST["endateorg"];
	$roomidorg=$_POST["roomidorg"];
	$rentatusorg=$_POST["rentatusorg"];

	$startdate=$_POST["startdate"]; 
	$enddate=$_POST["enddate"];

	if((($rentatusorg!="confirm") && ($_POST["rentstatus"]=="confirm")) ||
	((($rentatusorg=="confirm") && ($_POST["rentstatus"]=="confirm")) && 
	 (($stdateorg!=$startdate) || ($endateorg!=$enddate) || ($roomidorg!=$setroomid)))) {
	 	
	// $sql5 = $db->prepare("SELECT CustomerId,StartDate,EndDate,RoomId,ActivityName from activity 
	// 			WHERE ActivityId ='".$_POST['Id']."'");
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
	// 	}
	
	$sql1 = $db->prepare("SELECT ActivityId,CustomerId,StartDate,EndDate,RoomId,ActivityName from activity 
		WHERE (
	  		(((DATE_FORMAT('".$startdate."' ,'%Y-%m-%d %H:%i:00')) BETWEEN startdate AND enddate)
			OR ((DATE_FORMAT('".$enddate."' ,'%Y-%m-%d %H:%i:00')) BETWEEN startdate AND enddate))	
		OR 
			((startdate BETWEEN (DATE_FORMAT('".$startdate."' ,'%Y-%m-%d %H:%i:00')) 
			AND (DATE_FORMAT('".$enddate."' ,'%Y-%m-%d %H:%i:00')))  
			OR (enddate BETWEEN (DATE_FORMAT('".$startdate."' ,'%Y-%m-%d %H:%i:00')) 
			AND (DATE_FORMAT('".$enddate."' ,'%Y-%m-%d %H:%i:00'))))
	        ) 
		    AND RoomId=$setroomid AND RentStatus!='confirm' AND RentStatus!='cancel'");
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

	// $mail->Username = "           "; // Email Sender 
	// $mail->Password = "           "; // Password Sender
	 	
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
	// 			}
			}
	}

} 
// else if (($rentatusorg!="cancel") && ($_POST["rentstatus"]=="cancel")) {
// 	$sql5 = $db->prepare("SELECT CustomerId,StartDate,EndDate,RoomId,ActivityName from activity 
// 				WHERE ActivityId ='".$_POST['Id']."'");
// 		$sql5->execute();
// 		$sql5->setFetchMode(PDO::FETCH_ASSOC);
// 		if ($row5 = $sql5->fetch()) {
			
// 			$sql6 = $db->prepare("SELECT PreName,FirstName,LastName,Username,RoomName FROM user,room 
// 				WHERE UserId='".$row5['CustomerId']."' AND RoomId='".$row5['RoomId']."'");
// 			$sql6->execute();						
// 			$sql6->setFetchMode(PDO::FETCH_ASSOC);
// 			if ($row6 = $sql6->fetch()) {
    
//     $mail1 = new PHPMailer();  
// 	$mail1->SetLanguage( 'en', 'admin/PHPMailer/language/' ); 
// 	$mail1->CharSet = "utf-8";
// 	$mail1->IsHTML(true);
// 	$mail1->IsSMTP();  
// 	$mail1->Mailer = "smtp";
// 	$mail1->SMTPSecure = "ssl"; //Gmail: ssl
// 	$mail1->Host = "smtp.gmail.com"; //Gmail: smtp.gmail.com
// 	$mail1->Port = 465; //Gmail: 465
// 	$mail1->SMTPAuth = true; 

// 	$mail1->Username = "           "; // Email Sender 
// 	$mail1->Password = "           "; // Password Sender	 
	
// 	$mail1->FromName = "Room Management System";
// 	$mail1->AddAddress("thapanonz@hotmail.com", $row6['PreName'].$row6['FirstName']." ".$row6['LastName']);  //  $row5['Username']     Email Reciever
	 
// 	$mail1->Subject  = "การขอเช่าห้องอยู่ในสถานะ \"ยกเลิก\"";

// 	$Body="<b>ผู้ขอใช้บริการ: </b>".$row6['PreName'].$row6['FirstName']." ".$row6['LastName'].
// 		  "<br><b>วันที่เริ่มใช้บริการ: </b>".DateThai1($row5['StartDate']).
// 		  "<br><b>วันสิ้นสุดใช้บริการ: </b>".DateThai1($row5['EndDate']).
// 		  "<br><b>ห้องที่ใช้บริการ: </b>".$row6['RoomName'].
// 		  "<br><b>ชื่อโครงการ: </b>".$row5['ActivityName'].
// 		  "<br><br>การขอเช่าห้องของท่านอยู่ในสถานะ <b>\"ยกเลิก\"</b>
// 		  <br>หากต้องการข้อมูลเพิ่มเติม กรุณาติดต่อเจ้าหน้าที่ศูนย์คอมพิวเตอร์ 
// 		  <br>_____________________________________________________________
// 		  <br>กลุ่มงานบริการวิชาการ ศูนย์คอมพิวเตอร์ มหาวิทยาลัยสงขลานครินทร์
// 		  <br>โทรศัพท์ 0-7428-2116 ,โทรสาร 0-7428-2070";	
// 	$mail1->Body = '<span style="font-family:Angsana New; font-size:17pt">'.$Body.'</span>';  
	 	 		
// 	if(!$mail1->Send()) { echo $mail1->ErrorInfo; }	
// 			}
// 		}

// } else if (($rentatusorg!="reserve") && ($_POST["rentstatus"]=="reserve")) {
// 	$sql5 = $db->prepare("SELECT CustomerId,StartDate,EndDate,RoomId,ActivityName from activity 
// 				WHERE ActivityId ='".$_POST['Id']."'");
// 		$sql5->execute();
// 		$sql5->setFetchMode(PDO::FETCH_ASSOC);
// 		if ($row5 = $sql5->fetch()) {
			
// 			$sql6 = $db->prepare("SELECT PreName,FirstName,LastName,Username,RoomName FROM user,room 
// 				WHERE UserId='".$row5['CustomerId']."' AND RoomId='".$row5['RoomId']."'");
// 			$sql6->execute();						
// 			$sql6->setFetchMode(PDO::FETCH_ASSOC);
// 			if ($row6 = $sql6->fetch()) {
    
//     $mail1 = new PHPMailer();  
// 	$mail1->SetLanguage( 'en', 'admin/PHPMailer/language/' ); 
// 	$mail1->CharSet = "utf-8";
// 	$mail1->IsHTML(true);
// 	$mail1->IsSMTP();  
// 	$mail1->Mailer = "smtp";
// 	$mail1->SMTPSecure = "ssl"; //Gmail: ssl
// 	$mail1->Host = "smtp.gmail.com"; //Gmail: smtp.gmail.com
// 	$mail1->Port = 465; //Gmail: 465
// 	$mail1->SMTPAuth = true; 

// 	$mail1->Username = "            "; // Email Sender 
// 	$mail1->Password = "            "; // Password Sender	 
	
// 	$mail1->FromName = "Room Management System";
// 	$mail1->AddAddress("thapanonz@hotmail.com", $row6['PreName'].$row6['FirstName']." ".$row6['LastName']);  //  $row5['Username']     Email Reciever

// 	$mail1->Subject  = "การขอเช่าห้องอยู่ในสถานะ \"จอง\"";

// 	$Body="<b>ผู้ขอใช้บริการ: </b>".$row6['PreName'].$row6['FirstName']." ".$row6['LastName'].
// 		  "<br><b>วันที่เริ่มใช้บริการ: </b>".DateThai1($row5['StartDate']).
// 		  "<br><b>วันสิ้นสุดใช้บริการ: </b>".DateThai1($row5['EndDate']).
// 		  "<br><b>ห้องที่ใช้บริการ: </b>".$row6['RoomName'].
// 		  "<br><b>ชื่อโครงการ: </b>".$row5['ActivityName'].
// 		  "<br><br>การขอเช่าห้องของท่านอยู่ในสถานะ <b>\"จอง\"</b>
// 		  <br>หากต้องการข้อมูลเพิ่มเติม กรุณาติดต่อเจ้าหน้าที่ศูนย์คอมพิวเตอร์ 
// 		  <br>_____________________________________________________________
// 		  <br>กลุ่มงานบริการวิชาการ ศูนย์คอมพิวเตอร์ มหาวิทยาลัยสงขลานครินทร์
// 		  <br>โทรศัพท์ 0-7428-2116 ,โทรสาร 0-7428-2070";
// 	$mail1->Body = '<span style="font-family:Angsana New; font-size:17pt">'.$Body.'</span>';  
	 	 		
// 	if(!$mail1->Send()) { echo $mail1->ErrorInfo; }	
// 		}
// 	}
// }
	

	if ($_POST["ctrl"]=="index") {	
		$page="index"; }
	else if ($_POST["ctrl"]=='reserve') {	
		$page="reserve"; }
	else if ($_POST["ctrl"]=='wconfirm') {	
		$page="wconfirm"; }
	else if ($_POST["ctrl"]=='confirm') {	
		$page="confirm"; }
	else if ($_POST["ctrl"]=='cancel') {	
		$page="cancel"; }

	header('Location: '.$page.'.php'); 
	}
}	
catch(PDOException $e) {
  echo $e->getMessage();
	} 
} } ?>