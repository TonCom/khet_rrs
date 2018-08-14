<?php

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	session_start();

	if(!isset($_SESSION['UserId'])||$_SESSION['UserTypeId']!=0) {
		
		header('Location: ../login.php?error=0'); 
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
	$activepage = "service";

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

	if (isset($_GET["ctrl"])&&$_GET["ctrl"]=="cancel") {
		$sql = "UPDATE activity SET RentStatus = 'cancel'
			WHERE ActivityId = :activityid";	
		$stmp = $db->prepare($sql);
		$stmp->bindValue("activityid" , $_GET['ActivityId']); 
		$stmp->execute();

	// 	if($stmp->execute()) {
	// 		$sql1 = $db->prepare("SELECT CustomerId,StartDate,EndDate,RoomId,ActivityName from activity 
	// 			WHERE ActivityId ='".$_GET['ActivityId']."'");
	// 	    $sql1->execute();
	// 	    $sql1->setFetchMode(PDO::FETCH_ASSOC);
	// 	    if ($row1 = $sql1->fetch()) {
	// 	    	$sql5 = $db->prepare("SELECT PreName,FirstName,LastName,Username,RoomName FROM user,room 
	// 			WHERE UserId='".$row1['CustomerId']."' AND RoomId='".$row1['RoomId']."'");
	// 			$sql5->execute();						
	// 			$sql5->setFetchMode(PDO::FETCH_ASSOC);
	// 			if ($row5 = $sql5->fetch()) {

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

	// $mail1->Username = "           "; // Email Sender
	// $mail1->Password = "           "; // Password Sender	 
	
	// $mail1->FromName = "Room Management System";
	// $mail1->AddAddress("thapanonz@hotmail.com", $row5['PreName'].$row5['FirstName']." ".$row5['LastName']);  //  $row5['Username']     Email Reciever
	 
	// $mail1->Subject  = "การขอเช่าห้องอยู่ในสถานะ \"ยกเลิก\"";

	// $Body="<b>ผู้ขอใช้บริการ: </b>".$row5['PreName'].$row5['FirstName']." ".$row5['LastName'].
	// 	  "<br><b>วันที่เริ่มใช้บริการ: </b>".DateThai1($row1['StartDate']).
	// 	  "<br><b>วันสิ้นสุดใช้บริการ: </b>".DateThai1($row1['EndDate']).
	// 	  "<br><b>ห้องที่ใช้บริการ: </b>".$row5['RoomName'].
	// 	  "<br><b>ชื่อโครงการ: </b>".$row1['ActivityName'].
 // 		  "<br><br>การขอเช่าห้องของท่านอยู่ในสถานะ <b>\"ยกเลิก\"</b>
	// 	  <br>หากต้องการข้อมูลเพิ่มเติม กรุณาติดต่อเจ้าหน้าที่ศูนย์คอมพิวเตอร์ 
	// 	  <br>_____________________________________________________________
	// 	  <br>กลุ่มงานบริการวิชาการ ศูนย์คอมพิวเตอร์ มหาวิทยาลัยสงขลานครินทร์
	// 	  <br>โทรศัพท์ 0-7428-2116 ,โทรสาร 0-7428-2070";
	// $mail1->Body = '<span style="font-family:Angsana New; font-size:17pt">'.$Body.'</span>';  
	 	 		
	// if(!$mail1->Send()) { echo $mail1->ErrorInfo; }	  	
	// 			}
	// 	    }
	// 	}		
	}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ข้อมูลการใช้บริการเช่าห้อง</title>
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
						<h3><b>ข้อมูลการใช้บริการเช่าห้อง</b></h3>				
															
			<table class="table table-bordered table-hover table-striped" id="TableService">
			<thead>
		      <tr bgcolor="#CCCCCC">
		      	<th style='display:none;'></th>
		    <?php if ($_SESSION['StaffLevel']=="sadmin"||$_SESSION['StaffLevel']=="admin") { ?>
		        <th></th>
		    <?php } ?>			        	
		        <th>วันเวลาที่เริ่ม</th>	
		        <th>วันเวลาสิ้นสุด</th>	        
		        <th>ห้องที่ใช้บริการ</th>
		        <th>ชื่อโครงการ</th>
		        <th>อาหารว่าง</th>
		        <th>อาหารเที่ยง</th>	
		        <th>เจ้าหน้าที่</th>
		    <?php if ($_SESSION['StaffLevel']=="sadmin"||$_SESSION['StaffLevel']=="admin") { ?>
		        <th>จองโดย</th>
		    <?php } ?>	       
		      </tr>
		    </thead>
			<tbody>
		
			<?php
			$sql = $db->prepare("SELECT idactivity,startdate,enddate,roomname,activityname,isbreak,islunch,aprename,afirstname,alastname,lprename,lfirstname,llastname,mprename,mfirstname,mlastname,reserveby FROM service ORDER BY idactivity DESC");
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			while ($row = $sql->fetch()) {
			echo "<tr>";
			echo "<td style='display:none;'>".$row["startdate"]."</td>";
		if ($_SESSION['StaffLevel']=="sadmin"||$_SESSION['StaffLevel']=="admin") { 	
			echo "<td style='padding: 5px'>";					
			echo "<a href='?ActivityId=".$row["idactivity"]."&ctrl=cancel' onclick=\"return confirm('คุณต้องการยกเลิกรายการเช่าห้องนี้หรือไม่ ?');\" style='text-decoration: none'><div class='label label-danger'>ยกเลิก</div></a>";
			echo "</td>";
		}				
				echo "<td>" .DateThai($row["startdate"])."</td>";
				echo "<td>" .DateThai($row["enddate"])."</td>";
				echo "<td>" .$row["roomname"]."</td>";			
				echo "<td>" .$row["activityname"]."</td>";
				echo "<td>" .($row["isbreak"]=="have"? "<div class='label label-success'>&nbsp;มี&nbsp;</div>" : "<div class='label label-default'>&nbsp;ไม่มี&nbsp;</div>")."</td>";
				echo "<td>" .($row["islunch"]=="have"? "<div class='label label-success'>&nbsp;มี&nbsp;</div>" : "<div class='label label-default'>&nbsp;ไม่มี&nbsp;</div>")."</td>";	

				echo "<td>"; ?> 
				<a href="#" data-toggle="modal" data-target="#<?php echo $row["idactivity"] ?>">ดูรายละเอียด</a>

                <div class="modal fade" id="<?php echo $row["idactivity"] ?>" role="dialog">
				    <div class="modal-dialog">
				      <div class="modal-content">
				        <div class="modal-header">
				          <button type="button" class="close" data-dismiss="modal">&times;</button>
				          <h4 class="modal-title">เจ้าหน้าที่ที่เกี่ยวข้อง</h4>
				        </div>
				        <div class="modal-body">
					        <div class="row">
					        	<div class="col-md-12" style="font-size: 16px; margin-left: 10px; text-align: left">
					        		<label>วันเวลาที่เริ่ม:</label>&nbsp;&nbsp;<?php echo DateThai($row["startdate"]) ?><br>
					        		<label>วันเวลาสิ้นสุด:</label>&nbsp;&nbsp;<?php echo DateThai($row["enddate"]) ?><br>
					        		<label>ห้องที่ใช้บริการ:</label>&nbsp;&nbsp;<?php echo $row["roomname"] ?><br><br>

					        		<label>เจ้าหน้าที่โครงการ:</label>&nbsp;&nbsp;<?php echo $row["aprename"].$row["afirstname"]." ".$row["alastname"] ?><br>
					        		<label>เจ้าหน้าที่ห้องปฏิบัติการ:</label>&nbsp;&nbsp;<?php echo $row["lprename"].$row["lfirstname"]." ".$row["llastname"] ?><br>
					        		<label>แม่บ้าน:</label>&nbsp;&nbsp;<?php echo $row["mprename"].$row["mfirstname"]." ".$row["mlastname"] ?>					        	
					        	</div>
							</div>
				        </div>
				        <div class="modal-footer">	
				     			     			        	
				          <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
				        </div>
				      </div>      
				    </div>
				  </div>
				
				<?php
			    echo "</td>";
			if ($_SESSION['StaffLevel']=="sadmin"||$_SESSION['StaffLevel']=="admin") { 	
			    echo "<td style='padding: 0px'>".($row["reserveby"]=='admin'? "<span class='label label-info'>เจ้าหน้าที่</span>" : "<span class='label label-warning'>ผู้เช่า</span>")."</td>";
			}
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
		    	$('#TableService').DataTable( {
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
			} );
        </script>  
    </body>
</html>
<?php } } ?>