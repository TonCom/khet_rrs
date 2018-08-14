<?php	

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	require "admin/include/connect.php";

if(isset($_POST["username"])&&isset($_POST["password"])) {
			
	//$username = mysql_real_escape_string($_POST["username"]);
	//$password = mysql_real_escape_string($_POST["password"]);
	$username = $_POST["username"];
	$password = $_POST["password"];

	if(isset($_POST["admin"])&&$_POST["admin"]=="admin") {
		$sql5 = $db->prepare("SELECT * FROM user WHERE UserTypeId=0 AND Username='$username'");
		$sql5->execute();
		$sql5->setFetchMode(PDO::FETCH_ASSOC);
		if ($row5 = $sql5->fetch()) { 

			//$sql6 = $db->prepare("SELECT * FROM user WHERE UserTypeId=0 AND Username='$username' AND Password=PASSWORD('$password')");
			$sql6 = $db->prepare("SELECT * FROM user WHERE UserTypeId=0 AND Username='$username'");
			$sql6->execute();
			$sql6->setFetchMode(PDO::FETCH_ASSOC);
			if ($row6 = $sql6->fetch()) { 
				session_start();
				session_destroy();
	
				$sql7= "UPDATE user SET LastLogin = NOW() WHERE UserId = :userid";
				$stmp = $db->prepare($sql7);				
				$stmp->bindValue("userid" , $row6['UserId']);
				if ($stmp->execute()) {

					session_start();
					$_SESSION['UserId'] = $row6['UserId'];
					$_SESSION['UserTypeId'] = 0;
					$_SESSION['StaffLevel'] = $row6['StaffLevel'];

					$_SESSION['start'] = time(); 
				//In minutes : (30 * 60) = 30 นาที
                //In days : (n * 24 * 60 * 60 ) n = no of days 
            		$_SESSION['expire'] = $_SESSION['start'] + (1*24*60*60);

				session_write_close();
				header('Location: admin/index.php');
				}
			}
			else {
				header('Location: admin/login.php?error=2');	// บัญชีผู้ใช้หรือรหัสผ่าน ไม่ถูกต้อง
			}
		}
		else {
			header('Location: admin/login.php?error=1');	// ไม่พบผู้ใช้งาน	
		}

	} else {

	$sql = $db->prepare("SELECT * FROM user WHERE UserTypeId!=0 AND Username='$username'");
	$sql->execute();
	$sql->setFetchMode(PDO::FETCH_ASSOC);
	if ($row = $sql->fetch()) { 

		//$sql2 = $db->prepare("SELECT * FROM user WHERE UserTypeId!=0 AND Username='$username' AND Password=PASSWORD('$password')");
		$sql2 = $db->prepare("SELECT * FROM user WHERE UserTypeId!=0 AND Username='$username' ");
		$sql2->execute();
		$sql2->setFetchMode(PDO::FETCH_ASSOC);
		if ($row2 = $sql2->fetch()) { 

			//$sql3 = $db->prepare("SELECT * FROM user WHERE UserTypeId!=0 AND Username='$username' AND Password= PASSWORD('$password') AND Active='yes'");
			$sql3 = $db->prepare("SELECT * FROM user WHERE UserTypeId!=0 AND Username='$username'  AND Active='yes'");
			$sql3->execute();
			$sql3->setFetchMode(PDO::FETCH_ASSOC);
			if ($row3 = $sql3->fetch()) { 
				session_start();
				session_destroy();

				$sql4= "UPDATE user SET LastLogin = NOW() WHERE UserId = :userid";
				$stmp = $db->prepare($sql4);				
				$stmp->bindValue("userid" , $row3['UserId']);
				if ($stmp->execute()) {

					session_start();
					$_SESSION['UserId'] = $row3['UserId'];
					$_SESSION['UserTypeId'] = $row3['UserTypeId'];
					// $_SESSION['Username'] = $_POST["username"];
					// $_SESSION['UserType'] = $row3['UserTypeId'];

					$_SESSION['start'] = time(); 
				//In minutes : (30 * 60) = 30 นาที
                //In days : (n * 24 * 60 * 60 ) n = no of days 
            		$_SESSION['expire'] = $_SESSION['start'] + (1*24*60*60);
			
				session_write_close();
				header('Location: index.php'); 
				}
			}
			else {
				header('Location: login.php?error=3');	// ยังไม่ได้รับการ อนุมัติ
			}
		}
		else {
			header('Location: login.php?error=2');	// บัญชีผู้ใช้งานหรือรหัสผ่าน ไม่ถูกต้อง
		}		
	}
	else {
		header('Location: login.php?error=1');	// ไม่พบผู้ใช้งาน	
	}
}
	mysql_close();

} else if(isset($_POST["email"])) {
	function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
   		 return $randomString;
	}

	$sql = $db->prepare("SELECT * FROM user WHERE UserTypeId!=0 AND Username='".$_POST["email"]."'");
	$sql->execute();
	$sql->setFetchMode(PDO::FETCH_ASSOC);
	if ($row = $sql->fetch()) { 

		$sql2 = $db->prepare("SELECT * FROM user WHERE UserTypeId!=0 AND Username='".$_POST["email"]."' AND Active='yes'");
		$sql2->execute();
		$sql2->setFetchMode(PDO::FETCH_ASSOC);
		if ($row2 = $sql2->fetch()) { 

			$resetpass=generateRandomString();
			$sql4= "UPDATE user SET Password= PASSWORD(:resetpass) WHERE Username = :username";
				$stmp = $db->prepare($sql4);				
				$stmp->bindValue("username" , $_POST["email"]);
				$stmp->bindValue("resetpass" , $resetpass);
				$stmp->execute();

	// 			if($stmp->execute()) {

	// require "admin/PHPMailer/class.phpmailer.php";	
					
	// $mail2 = new PHPMailer();  
	// $mail2->SetLanguage( 'en', 'admin/PHPMailer/language/' ); 
	// $mail2->CharSet = "utf-8";
	// $mail2->IsHTML(true);
	// $mail2->IsSMTP();  
	// $mail2->Mailer = "smtp";
	// $mail2->SMTPSecure = "ssl"; //Gmail: ssl
	// $mail2->Host = "smtp.gmail.com"; //Gmail: smtp.gmail.com
	// $mail2->Port = 465; //Gmail: 465
	// $mail2->SMTPAuth = true; 

	// $mail2->Username = "           "; // Email Sender 
	// $mail2->Password = "           "; // Password Sender	 
	
	// $mail2->FromName = "Room Management System";
	// $mail2->AddAddress("thapanonz@hotmail.com", $row2['PreName'].$row2['FirstName']." ".$row2['LastName']);  // Email ผู้รับรหัสผ่านใหม่              
	 
	// $mail2->Subject  = "ระบบแจ้งรีเซ็ตรหัสผ่านใหม่";

	// $Body="<b>ท่านสามารถใช้ข้อมูลต่อไปนี้ในการเข้าสู่ระบบได้ ดังนี้</b>
	// 	  <br><br><b>บัญชีผู้ใช้งาน: </b>".$_POST["email"]."
	// 	  <br><b>รหัสผ่านใหม่: </b>".$resetpass."
		    		  	  
	// 	  <br>_____________________________________________________________
	// 	  <br>กลุ่มงานบริการวิชาการ ศูนย์คอมพิวเตอร์ มหาวิทยาลัยสงขลานครินทร์
	// 	  <br>โทรศัพท์ 0-7428-2116 ,โทรสาร 0-7428-2070";
	// $mail2->Body = '<span style="font-family:Angsana New; font-size:17pt">'.$Body.'</span>';  
	 	 		
	// if(!$mail2->Send()) { echo $mail2->ErrorInfo; 
	// } else { header('Location: recover.php?action=0'); }
	// 			}
			  header('Location: recover.php?action=0');
		}
		else {
			header('Location: recover.php?error=2');	// ยังไม่ได้รับการ อนุมัติ
		}
	}
	else {
		header('Location: recover.php?error=1');	// ไม่พบผู้ใช้งาน	
	}
}
?>


