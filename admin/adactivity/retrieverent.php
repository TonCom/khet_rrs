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
	
// กรอก วันเวลาที่เริ่มต้น , วันเวลาที่สิ้นสุด , ห้อง --> หาว่า ขอใช้บริการ ได้ไหม?
if($_POST["startdate"]&&$_POST["enddate"]&&$_POST["roomid"]) {
	$startdate=$_POST["startdate"]; 
	$enddate=$_POST["enddate"];
	$roomid=$_POST["roomid"];

	if(isset($_POST["idactivity"])) {	
		$idactivity=$_POST["idactivity"];
	}

		$sql = "SELECT startdate,enddate,roomid from service 
		WHERE ((
  		(((DATE_FORMAT('".$startdate."' ,'%Y-%m-%d %H:%i:00')) BETWEEN startdate AND enddate)
		OR ((DATE_FORMAT('".$enddate."' ,'%Y-%m-%d %H:%i:00')) BETWEEN startdate AND enddate))	
	OR 
		((startdate BETWEEN (DATE_FORMAT('".$startdate."' ,'%Y-%m-%d %H:%i:00')) 
		AND (DATE_FORMAT('".$enddate."' ,'%Y-%m-%d %H:%i:00')))  
		OR (enddate BETWEEN (DATE_FORMAT('".$startdate."' ,'%Y-%m-%d %H:%i:00')) 
		AND (DATE_FORMAT('".$enddate."' ,'%Y-%m-%d %H:%i:00'))))
        ) 
    AND roomid=$roomid)";
		
		if(isset($_POST["idactivity"])) {	
			$sql .= " AND idactivity!=$idactivity";
		}

		$sql=$db->prepare($sql);
		$sql->execute();
		$sql->setFetchMode(PDO::FETCH_ASSOC);
			if ($row = $sql->fetch()) {
				return false;  } 
			else { 		
				$sql2 = $db->prepare("SELECT HolidayName from holiday 
				WHERE (HolidayDate BETWEEN (DATE_FORMAT('".$startdate."' ,'%Y-%m-%d')) 
				AND (DATE_FORMAT('".$enddate."' ,'%Y-%m-%d')))"); 
				$sql2->execute();
				$sql2->setFetchMode(PDO::FETCH_ASSOC);
				if ($row2 = $sql2->fetch()) {
					return false;  } 
				else { 	
					echo "<span class='label label-success'>พร้อมให้บริการ</span> ในช่วงวันเวลาและห้องดังกล่าว"; 
				}
			}
} } } ?>