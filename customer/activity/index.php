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
	require "../../admin/include/fnDatethai.php";
	require "../../admin/PHPMailer/class.phpmailer.php";

	//Set Path
	$isSubfolder = false;
	$activepage = "listrent";

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

	if (isset($_GET["ctrl"])&&$_GET["ctrl"]=="cancel") {
		$sql = "UPDATE activity SET RentStatus = 'cancel'
			WHERE ActivityId = :activityid";	
		$stmp = $db->prepare($sql);
		$stmp->bindValue("activityid" , $_GET['ActivityId']); 
		$stmp->execute();

	// 	if($stmp->execute()) {
	// 		$sql5 = $db->prepare("SELECT CustomerId,StartDate,EndDate,RoomId,ActivityName,ActivityTypeId,IsBreak,FeeBreak,IsLunch,FeeLunch,UpdatedAt from activity 
	// 			WHERE ActivityId ='".$_GET['ActivityId']."'");
	// 	$sql5->execute();
	// 	$sql5->setFetchMode(PDO::FETCH_ASSOC);
	// 	if ($row5 = $sql5->fetch()) {
	// 		$sql6 = $db->prepare("SELECT CitizenId,PreName,FirstName,LastName,Telephone,RoomName,TypeName FROM user,room,activitytype WHERE UserId='".$row5['CustomerId']."' AND RoomId='".$row5['RoomId']."' AND TypeId='".$row5['ActivityTypeId']."'");
	// 		$sql6->execute();						
	// 		$sql6->setFetchMode(PDO::FETCH_ASSOC);
	// 		if ($row6 = $sql6->fetch()) {

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
	// $mail2->AddAddress("thapanonz@hotmail.com", "Admin :Room Mangement System");  // Email Admin 
	//               //  $row4['Username']
	 
	// $mail2->Subject  = "ผู้ขอใช้บริการ \"ยกเลิก\" ข้อมูลขอเช่าห้อง สัญญาเช่าเลขที่ ".$_GET['ActivityId'];

	// $Body="<b><u>ข้อมูลผู้ขอใช้บริการ</u></b>
	// 	  <br><b>เลขบัตรประจำตัวประชาชน: </b>".$row6['CitizenId']."
	// 	  <br><b>ชื่อ-นามสกุล: </b>".$row6['PreName'].$row6['FirstName']." ".$row6['LastName']."
	// 	  <br><b>เบอร์โทรศัพท์: </b>".$row6['Telephone']."

	// 	  <br><br><b><u>ข้อมูลการขอเช่าห้อง</u></b>
	// 	  <br><b>สัญญาเช่าเลขที่: </b>".$_GET['ActivityId']."
	// 	  <br><b>วันที่เริ่มใช้บริการ: </b>".DateThai1($row5['StartDate'])."
	// 	  <br><b>วันสิ้นสุดใช้บริการ: </b>".DateThai1($row5['EndDate'])."
	// 	  <br><b>ห้องที่ใช้บริการ: </b>".$row6['RoomName']."
	// 	  <br><b>ชื่อโครงการ: </b>".$row5["ActivityName"]."
	// 	  <br><b>ประเภทการเช่า: </b>".$row6['TypeName']."
	// 	  <br><b>อาหารว่าง: </b>".(($row5['IsBreak']=="nothave")&&($row5['FeeBreak']==0)? "ไม่มี" : "มี งบประมาณ ".$row5['FeeBreak']." บาท")."		  
	// 	  <br><b>อาหารกลางวัน: </b>".(($row5['IsLunch']=="nothave")&&($row5['FeeLunch']==0)? "ไม่มี" : "มี งบประมาณ ".$row5['FeeLunch']." บาท")."		  
	// 	  <br><br><b>ยกเลิกวันที่: </b>".DateThai1($row5['UpdatedAt'])."		  
	// 	  <br>_____________________________________________________________
	// 	  <br>กลุ่มงานบริการวิชาการ ศูนย์คอมพิวเตอร์ มหาวิทยาลัยสงขลานครินทร์
	// 	  <br>โทรศัพท์ 0-7428-2116 ,โทรสาร 0-7428-2070";
	// $mail2->Body = '<span style="font-family:Angsana New; font-size:17pt">'.$Body.'</span>';  
	 	 		
	// if(!$mail2->Send()) { echo $mail2->ErrorInfo; }	
	// 	}
	// }	
 //  }
}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>รายการขอเช่าห้อง</title>
		<?php include "../../admin/include/css.php"; ?>		
		<link rel="stylesheet" type="text/css" href="../../admin/css/jquery.dataTables.min.css">
    </head>
    <style>     
   	.center-block {
   		float: none !important
   	}    
   	th,td{ 
   		/*font-size: 14px;*/ 		
   		text-align: center;
    }
    a.disabled {
	   pointer-events: none;
	   cursor: default;
	}
    </style>
    <body>
		<?php include "../include/banner.php"; ?>
		<?php include "../include/menu.php"; ?>

		<div class="container-fluid">
		<br><br>				
			<div class="row">
					<div class="table-responsive" style="margin: 0 auto; width:90%">				
				
				<div class="panel panel-default">
      				<div class="panel-body">

     <?php $sql2 = $db->prepare("SELECT PreName,FirstName,LastName FROM user WHERE UserId='".$_SESSION['UserId']."'");	      
     		$sql2->execute();
			$sql2->setFetchMode(PDO::FETCH_ASSOC);
			if ($row2 = $sql2->fetch()) { ?>														
				<h3><b>ผู้ขอใช้บริการ : <?=$row2["PreName"].$row2["FirstName"]." ".$row2["LastName"] ?></b></h3> <?php } ?>

				<table class="table table-bordered table-hover table-striped" id="TableActivity">
			<thead>
		      <tr bgcolor="#CCCCCC">
		        <th style='display:none;'></th>	
		        <th>วันเวลาที่เริ่ม</th>	
		        <th>วันเวลาสิ้นสุด</th>	        
		        <th>ห้องที่ใช้บริการ</th>
		        <th>ชื่อโครงการ</th>		        
		        <th>รายละเอียด</th>
		        <th>สถานะ</th>		        	       		        		        
		        <th>เวลาที่จอง</th>
		       	<th></th>
		       	<th></th>       
		      </tr>
		    </thead>
			<tbody>
		
			<?php
			$sql = $db->prepare("SELECT ActivityId,CustomerId,RoomId,StartDate,EndDate,ActivityName,Details,ActivityTypeId,IsBreak,FeeBreak,IsLunch,FeeLunch,FeeRoom,FeeOthers,AdminId,LabId,MaidId,RentStatus,CreatedAt,UpdatedAt,ReserveBy FROM activity WHERE ReserveBy='cust' AND CustomerId='".$_SESSION['UserId']."'");
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			while ($row = $sql->fetch()) {
			echo "<tr>";
				echo "<td style='display:none;'>".$row["CreatedAt"]."</td>";
				echo "<td>" .DateThai($row["StartDate"])."</td>";
				echo "<td>" .DateThai($row["EndDate"])."</td>";

				$sql1 = $db->prepare("SELECT * FROM room WHERE RoomId='".$row['RoomId']."'"); 
							$sql1->execute();
							$sql1->setFetchMode(PDO::FETCH_ASSOC);
							while ($row1 = $sql1->fetch()) { 
								echo "<td>".$row1["RoomName"]."</td>";
				
				echo "<td>" .$row["ActivityName"]."</td>";
			
			    echo "<td>"; ?> 
				<a href="#" data-toggle="modal" data-target="#<?php echo $row["ActivityId"] ?>">ดูรายละเอียด</a>

                <div class="modal fade" id="<?php echo $row["ActivityId"] ?>" role="dialog">
				    <div class="modal-dialog">
				      <div class="modal-content">
				        <div class="modal-header">
				          <button type="button" class="close" data-dismiss="modal">&times;</button>
				          <h4 class="modal-title">ข้อมูลการขอเช่าห้อง</h4>
				        </div>
				    <div class="modal-body">
					    <div class="row">
					        <div class="col-md-12" style="font-size: 16px; margin-left: 15px; text-align: left">					        		
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
									<label>รายละเอียดการเช่า:</label><br> <textarea style="padding: 15px" readonly="readonly" rows="2" cols="62"><?php echo $row["Details"] ?></textarea><br><br>
					        
					        		<label>อาหารว่าง:</label>&nbsp;&nbsp;<?php echo ($row["IsBreak"]=="nothave"? "<span class='label label-danger' style='padding: 0 8px 0 8px'>ไม่มี</span>" : "<span class='label label-success' style='padding: 0 8px 0 8px'>มี</span> งบประมาณ ".number_format($row["FeeBreak"])." บาท") ?><br>
					        		 <label>อาหารกลางวัน:</label>&nbsp;&nbsp;<?php echo ($row["IsLunch"]=="nothave"? "<span class='label label-danger' style='padding: 0 8px 0 8px'>ไม่มี</span>" : "<span class='label label-success' style='padding: 0 8px 0 8px'>มี</span> งบประมาณ ".number_format($row["FeeLunch"])." บาท") ?><br><br>
					        		 																																			    							     										
									<div style="text-align: right; padding-right: 18px"><label>สถานะการเช่า:</label>&nbsp;&nbsp;<?php echo setstatusmodal($row["RentStatus"]) ?></div>					        						
								</div>
					   	</div>
				    </div>
				        <div class="modal-footer">	
				        <div class="form-group">
				        <label>จองเมื่อวันที่</label>&nbsp;&nbsp;<?php echo DateThai($row["CreatedAt"]) ?> น.<br>
				        <?php if ($row["UpdatedAt"]!='0000-00-00 00:00:00') { ?>
				         <label>แก้ไขล่าสุดเมื่อวันที่</label>&nbsp;&nbsp;<?php echo DateThai($row["UpdatedAt"]) ?> น.<br> <?php } ?>
				        
				        </div>
				        		<a href='formeditactivity.php?Id=<?=$row['ActivityId']?>' class="btn btn-primary <?php if (($row["RentStatus"]=="confirm")||($row["RentStatus"]=="cancel")){ echo "disabled"; }?>">แก้ไขข้อมูล</a>	
				        	<button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
				        </div>
				          
				    </div>
				  </div>
				
				<?php
			    echo "</td>"; 
				} 
			
				echo "<td>" .setstatusmodal($row["RentStatus"])."</td>";							
				echo "<td>" .DateThai($row["CreatedAt"])."</td>";
				echo "<td style='padding: 5px'>";				
				echo "<a href='?ActivityId=".$row["ActivityId"]."&ctrl=cancel' onclick=\"return confirm('คุณต้องการยกเลิกรายการเช่าห้องนี้หรือไม่ ?');\" style='text-decoration: none' class='".($row["RentStatus"]=="cancel" || $row["RentStatus"]=="confirm"? "disabled" : "")."'><div class='label label-danger' style='".($row["RentStatus"]=="cancel" || $row["RentStatus"]=="confirm"? "opacity: 0.5" : "")."'>ยกเลิก</div></a>";
				echo "</td>";
				echo "<td style='padding: 5px'>";
            	   echo "<a href='formeditactivity.php?Id=".$row['ActivityId']."' class='".($row["RentStatus"]=="confirm" || $row["RentStatus"]=="cancel"? "disabled" : "")."' data-toggle='tooltip' title='แก้ไข'><i style='font-size:22px; ".($row["RentStatus"]=="confirm" || $row["RentStatus"]=="cancel"? "opacity: 0.5" : "")."' class='fa fa-pencil' aria-hidden='true'></i></a>";               
            	   echo " <a href='deleteactivity.php?Id=".$row['ActivityId']."' onclick=\"return confirm('คุณต้องการลบรายการเช่าห้องนี้หรือไม่ ?');\" class='".($row["RentStatus"]=="wconfirm" || $row["RentStatus"]=="confirm"? "disabled" : "")."' data-toggle='tooltip' title='ลบ'><i style='font-size:22px; ".($row["RentStatus"]=="wconfirm" || $row["RentStatus"]=="confirm"? "opacity: 0.5" : "")."' class='fa fa-trash-o text-danger' aria-hidden='true'></i></a>";
                echo "</td>";	
			 echo "</tr>";
			 
			} ?>
		
			</tbody>
		 </table>

				</div>
			</div>			 
				</div>
			</div>												  				
		</div>

		<?php include "../include/footer.php"; ?>						

        <?php include "../../admin/include/js.php"; ?> 
         <script src="../../admin/js/jquery.dataTables.min.js"></script>          
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