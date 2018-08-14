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
	//require "../include/fnDatethai.php";

	function active($date,$sttime2,$entime2,$roomid){		
		echo "<td><a href='../activity/formaddactivity.php?Startdate=$date $sttime2&Enddate=$date $entime2&Roomid=$roomid' class='btn btn-default btn-sm' style='display:block;  border: 0px'>&nbsp;</a></td>"; 
	}

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

	function DateThai1($strDate){
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear";
	}


// กรอก วันเวลาที่เริ่มต้น , วันเวลาที่สิ้นสุด , ห้อง --> หาว่า ขอใช้บริการ ได้ไหม?
if($_POST["startdate"]&&$_POST["enddate"]&&$_POST["roomid"]) {
	$startdate=$_POST["startdate"]; 
	$enddate=$_POST["enddate"];
	$roomid=$_POST["roomid"];	
		$sql2 = $db->prepare("SELECT HolidayDate,HolidayName from holiday WHERE 
			(HolidayDate BETWEEN (DATE_FORMAT('".$startdate."' ,'%Y-%m-%d')) 
				AND (DATE_FORMAT('".$enddate."' ,'%Y-%m-%d'))) ORDER BY HolidayDate ASC");
		$sql2->execute(); 
		$count = $sql2->rowCount();
		if($count>0){
		$sql2->setFetchMode(PDO::FETCH_ASSOC); 
		while($row2 = $sql2->fetch()) { ?>
			<h4><b>วันที่:</b>&nbsp; <?=DateThai1($row2["HolidayDate"])?>&nbsp; <b>ตรงกับ:</b>&nbsp; <?=$row2["HolidayName"]?></h4>
	<?php } ?>
			<h4><b>อยู่ระหว่างวันที่:</b>&nbsp; <?=DateThai1($startdate)?> <b>ถึง</b> <?=DateThai1($enddate)?></h4>
			<br><h4><b>สถานะ:</b>&nbsp; <span class='label label-danger'>หยุดให้บริการ</span>&nbsp; กรุณาตรวจสอบใหม่อีกครั้ง !</h4>
  <?php } else { 
		$sql = $db->prepare("SELECT startdate,enddate,roomid,roomname from service 
  WHERE (
  		(((DATE_FORMAT('".$startdate."' ,'%Y-%m-%d %H:%i:00')) BETWEEN startdate AND enddate)
		OR ((DATE_FORMAT('".$enddate."' ,'%Y-%m-%d %H:%i:00')) BETWEEN startdate AND enddate))	
	OR 
		((startdate BETWEEN (DATE_FORMAT('".$startdate."' ,'%Y-%m-%d %H:%i:00')) 
		AND (DATE_FORMAT('".$enddate."' ,'%Y-%m-%d %H:%i:00')))  
		OR (enddate BETWEEN (DATE_FORMAT('".$startdate."' ,'%Y-%m-%d %H:%i:00')) 
		AND (DATE_FORMAT('".$enddate."' ,'%Y-%m-%d %H:%i:00'))))
        ) 
    AND roomid=$roomid");
		$sql->execute();
		$sql->setFetchMode(PDO::FETCH_ASSOC); ?>			
			<h4><b>วันเวลาที่เริ่ม:</b>&nbsp; <?php echo DateThai($startdate)?></h4>
			<h4><b>วันเวลาสิ้นสุด:</b>&nbsp; <?php echo DateThai($enddate)?></h4>
			<h4><b>ห้องที่ให้บริการ:</b>&nbsp; 
	<?php $sql1 = $db->prepare("SELECT RoomName from room WHERE RoomId=$roomid");
		$sql1->execute();
		$sql1->setFetchMode(PDO::FETCH_ASSOC); 
		if ($row1 = $sql1->fetch()) {
			echo $row1["RoomName"]?></h4>
			<?php if ($row = $sql->fetch()) { ?>				
					<br><h4><b>สถานะ:</b>&nbsp;&nbsp;<span class='label label-danger'>ไม่พร้อมให้บริการ</span>&nbsp;&nbsp;กรุณาตรวจสอบใหม่อีกครั้ง !</h4>
			<?php } else { ?>				
					<br><h4><b>สถานะ:</b>&nbsp;&nbsp;<span class='label label-success'>พร้อมให้บริการ</span>&nbsp;&nbsp;<a href='../activity/formaddactivity.php?Startdate=<?=$startdate?>&Enddate=<?=$enddate?>&Roomid=<?=$roomid?>'>ต้องการจอง คลิกที่นี่</a></h4>
			<?php	}
		}
} }
// กรอก วันเวลาที่เริ่มต้น , วันเวลาที่สิ้นสุด --> หา ห้องที่ว่าง *
else if($_POST["startdate"]&&$_POST["enddate"]) {
	$startdate=$_POST["startdate"]; 
	$enddate=$_POST["enddate"];
		$sql1 = $db->prepare("SELECT HolidayDate,HolidayName from holiday WHERE 
			(HolidayDate BETWEEN (DATE_FORMAT('".$startdate."' ,'%Y-%m-%d')) 
				AND (DATE_FORMAT('".$enddate."' ,'%Y-%m-%d'))) ORDER BY HolidayDate ASC");
		$sql1->execute(); 
		$count = $sql1->rowCount();
		if($count>0){
		$sql1->setFetchMode(PDO::FETCH_ASSOC); 
		while($row1 = $sql1->fetch()) { ?>
			<h4><b>วันที่:</b>&nbsp; <?=DateThai1($row1["HolidayDate"])?>&nbsp; <b>ตรงกับ:</b>&nbsp; <?=$row1["HolidayName"]?></h4>
	<?php } ?>
			<h4><b>อยู่ระหว่างวันที่:</b>&nbsp; <?=DateThai1($startdate)?> <b>ถึง</b> <?=DateThai1($enddate)?></h4>
			<br><h4><b>สถานะ:</b>&nbsp; <span class='label label-danger'>หยุดให้บริการ</span>&nbsp; กรุณาตรวจสอบใหม่อีกครั้ง !</h4>
  <?php } else { 
		$sql = $db->prepare("SELECT RoomId,RoomName FROM room WHERE Status='active' 
			AND RoomId NOT IN (SELECT roomid FROM service WHERE 
		(((DATE_FORMAT('".$startdate."' ,'%Y-%m-%d %H:%i:00')) BETWEEN startdate AND enddate)
		OR ((DATE_FORMAT('".$enddate."' ,'%Y-%m-%d %H:%i:00')) BETWEEN startdate AND enddate))	
	OR 
		((startdate BETWEEN (DATE_FORMAT('".$startdate."' ,'%Y-%m-%d %H:%i:00')) 
		AND (DATE_FORMAT('".$enddate."' ,'%Y-%m-%d %H:%i:00')))  
		OR (enddate BETWEEN (DATE_FORMAT('".$startdate."' ,'%Y-%m-%d %H:%i:00')) 
		AND (DATE_FORMAT('".$enddate."' ,'%Y-%m-%d %H:%i:00')))) )");
		$sql->execute(); ?>		
			<h4><b>วันเวลาที่เริ่ม:</b>&nbsp; <?php echo DateThai($startdate)?></h4>
			<h4><b>วันเวลาสิ้นสุด:</b>&nbsp; <?php echo DateThai($enddate)?></h4>			
		<?php $count = $sql->rowCount();
		if($count>0){
			//echo $count;
		$sql->setFetchMode(PDO::FETCH_ASSOC); ?>
		<!-- <h4><b>ห้องที่ให้บริการ:</b></h4> -->			
			<br><table class="table table-bordered table-hover table-striped" style="width: 75%">
			<thead>
		      <tr bgcolor="#CCCCCC">
		      	<th>ห้องที่ให้บริการ</th>
		        <th></th>		        
		      </tr>
		    </thead>
			<tbody>
		<?php while($row = $sql->fetch()) {							
					echo "<tr bgcolor='#FFFFFF'>";
					echo "<td>".$row["RoomName"]."</td>";
					echo "<td><a href='../activity/formaddactivity.php?Startdate=".$startdate."&Enddate=".$enddate."&Roomid=".$row['RoomId']."' class='btn btn-success btn-sm' style='padding: 5px 20px 5px 20px'>จอง</a></td>"; 
					echo "</tr>";								 
			} ?>
			</tbody>
		</table>
  <?php } else { ?>
			<h4><b>ห้องที่ให้บริการ:</b>&nbsp;&nbsp;<span class='label label-danger'>ไม่พบห้องที่พร้อมให้บริการ</span>&nbsp;&nbsp;กรุณาตรวจสอบใหม่อีกครั้ง !</h4>
		<?php }
} }
// กรอก วันที่ใช้บริการ , ห้อง --> หา เวลาที่ว่าง *          
else if($_POST["date"]&&$_POST["roomid"]) {
	$date=$_POST["date"];		
	$roomid=$_POST["roomid"];	
		$sql2 = $db->prepare("SELECT HolidayName from holiday WHERE HolidayDate='".$date."'");
		$sql2->execute(); 
		$sql2->setFetchMode(PDO::FETCH_ASSOC); 
		if($row2 = $sql2->fetch()) { ?>
			<h4><b>วันที่:</b>&nbsp; <?=DateThai1($date)?>&nbsp; <b>ตรงกับ:</b>&nbsp; <?=$row2["HolidayName"]?></h4>
			<br><h4><b>สถานะ:</b>&nbsp; <span class='label label-danger'>หยุดให้บริการ</span>&nbsp; กรุณาตรวจสอบใหม่อีกครั้ง !</h4>
  <?php } else { 
		$sql = $db->prepare("SELECT startdate,enddate FROM service WHERE 
			('".$date."' BETWEEN (DATE(startdate)) AND (DATE(enddate))) AND roomid=$roomid");
		$sql->execute(); ?>
		<h4><b>วันที่ให้บริการ:</b>&nbsp; <?php echo DateThai1($date)?></h4>
		<h4><b>ห้องที่ให้บริการ:</b>&nbsp;	
			<?php $sql1 = $db->prepare("SELECT RoomName from room WHERE RoomId=$roomid");
			$sql1->execute();
			$sql1->setFetchMode(PDO::FETCH_ASSOC); 
			if ($row1 = $sql1->fetch()) {
				echo $row1["RoomName"]?></h4>
			<?php }
		$count = $sql->rowCount(); 		
		if($count>0){ ?>			
		<h4><b>ช่วงเวลาที่ให้บริการ:</b>&nbsp;	
  <?php $inactive="<span class='label label-danger'>ไม่พบช่วงเวลาที่พร้อมให้บริการ</span>";
		$sql->setFetchMode(PDO::FETCH_ASSOC); 			
			 if($count<2){
				while($row = $sql->fetch()) {
					$startdate = date_create($row['startdate']);	
					$stdate = date_format($startdate, 'Y-m-d');						
					$sttime = date_format($startdate, 'H:i');

					$enddate = date_create($row['enddate']);	
					$endate = date_format($enddate, 'Y-m-d');					
					$entime = date_format($enddate, 'H:i');
											
					if(($date==$stdate)&&($date==$endate)) {
						if($sttime=="09:00"&&$entime=="16:00") {
						echo $inactive; }
						else if($sttime=="09:00"&&$entime=="12:00") {
							$sttime1="13:00"; $entime1="16:00";
						echo "ตั้งแต่เวลา 13.00 - 16.00 น."; }
						else if($sttime=="13:00"&&$entime=="16:00") {
							$sttime1="09:00"; $entime1="12:00";
						echo "ตั้งแต่เวลา 09.00 - 12.00 น."; }
					}	
					else if(($date==$stdate)||($date==$endate)||
						   (($date>$stdate)&&($date<$endate))) {
						if($date==$stdate) {
							if($sttime=="09:00") { echo $inactive; }
							else if($sttime=="13:00") { 
								$sttime1="09:00"; $entime1="12:00";
							echo "ตั้งแต่เวลา 09.00 - 12.00 น."; }
						}
						else if($date==$endate) {
							if($entime=="12:00") { 
								$sttime1="13:00"; $entime1="16:00";
							echo "ตั้งแต่เวลา 13.00 - 16.00 น."; }
							else if($entime=="16:00") { echo $inactive; }
						}
						else if(($date>$stdate)&&($date<$endate)) {
							echo $inactive;
						}
					}
				  if(isset($sttime1)&&isset($entime1)) {
				  	echo "<br><br><a href='../activity/formaddactivity.php?Startdate=$date $sttime1&Enddate=$date $entime1&Roomid=$roomid' style='padding-left: 165px'>ต้องการจอง คลิกที่นี่</a>";
				  }
				}
			} 
			else if($count==2){ 
			// ($starttime=="09:00"&&$endtime=="12:00")||($starttime=="13:00"&&$endtime=="16:00")
				echo $inactive;
			} ?> </h4>
			
  <?php } else { 
		  	//echo "ตั้งแต่เวลา 9.00 - 16.00 น. (ตลอดทั้งวัน)"; ?>
		  	<!-- <h4><b>ช่วงเวลาที่ให้บริการ:</b>&nbsp; ตั้งแต่เวลา 09.00 - 16.00 น. (ตลอดทั้งวัน)</h4> -->
  			<br><table class="table table-bordered table-hover table-striped" style="width: 75%">
			<thead>
		      <tr bgcolor="#CCCCCC">
		      	<th>ช่วงเวลาที่ให้บริการ</th>	
		      	<th></th>		 	        		        	 
		      </tr>
		    </thead>
			<tbody>
			  <tr bgcolor="#FFFFFF">
			  <td>09.00 - 12.00 น.</td>	
	<?php echo "<td><a href='../activity/formaddactivity.php?Startdate=$date 09:00&Enddate=$date 12:00&Roomid=$roomid' class='btn btn-success btn-sm' style='padding: 5px 20px 5px 20px'>จอง</a></td>"; ?>		      
	          </tr>
	          <tr bgcolor="#FFFFFF">
			  <td>13.00 - 16.00 น.</td>	
	<?php echo "<td><a href='../activity/formaddactivity.php?Startdate=$date 13:00&Enddate=$date 16:00&Roomid=$roomid' class='btn btn-success btn-sm' style='padding: 5px 20px 5px 20px'>จอง</a></td>"; ?>		      
	          </tr>
			  <tr bgcolor="#FFFFFF">
			  <td>09.00 - 16.00 น. (ทั้งวัน)</td>	
	<?php echo "<td><a href='../activity/formaddactivity.php?Startdate=$date 09:00&Enddate=$date 16:00&Roomid=$roomid' class='btn btn-success btn-sm' style='padding: 5px 20px 5px 20px'>จอง</a></td>"; ?>		      
	          </tr>
			</tbody>			
		    </table>
  <?php } 
} }
// กรอก วันที่ใช้บริการ  --> หา ห้อง เวลาที่ว่าง *  
else if($_POST["date"]) {
 	$date=$_POST["date"]; 		
		$sql2 = $db->prepare("SELECT HolidayName from holiday WHERE HolidayDate='".$date."'");
		$sql2->execute(); 
		$sql2->setFetchMode(PDO::FETCH_ASSOC); 
		if($row2 = $sql2->fetch()) { ?>
			<h4><b>วันที่:</b>&nbsp; <?=DateThai1($date)?>&nbsp; <b>ตรงกับ:</b>&nbsp; <?=$row2["HolidayName"]?></h4>
			<br><h4><b>สถานะ:</b>&nbsp; <span class='label label-danger'>หยุดให้บริการ</span>&nbsp; กรุณาตรวจสอบใหม่อีกครั้ง !</h4>
  <?php } else { ?>
 		<h4><b>วันที่ให้บริการ:</b>&nbsp; <?php echo DateThai1($date)?></h4> <?php
 		$sql = $db->prepare("SELECT RoomId,RoomName FROM room WHERE Status='active'");
		$sql->execute(); 
		$sql->setFetchMode(PDO::FETCH_ASSOC); ?>
		<br><table class="table table-bordered" style="width: 100%">
			<thead>
		      <tr bgcolor="#CCCCCC">
		      	<th>ห้อง  / ช่วงเวลาที่ให้บริการ</th>	
		      	<th>09.00-12.00</th>
		      	<th>13.00-16.00</th>
		      	<th>09.00-16.00</th>	        		        		
		      </tr>
		    </thead>
			<tbody>		  
  <?php while($row = $sql->fetch()) { ?>
  				<tr bgcolor="#FFFFFF">
        <?php $roomid=$row["RoomId"]; ?>
		      	<td bgcolor="#EEEEEE"><?=$row["RoomName"]?></td>
    <?php $sql1 = $db->prepare("SELECT startdate,enddate FROM service WHERE ('".$date."' BETWEEN (DATE(startdate)) AND (DATE(enddate))) AND roomid='".$row["RoomId"]."'");
			$sql1->execute();			 
			$count = $sql1->rowCount(); 
			if($count>0) { 
				$inactive="<td bgcolor='#d42e2e'></td>";  				 
			
				$sql->setFetchMode(PDO::FETCH_ASSOC); 			
			 	if($count<2){
					while($row1 = $sql1->fetch()) {
					$startdate = date_create($row1['startdate']);	
					$stdate = date_format($startdate, 'Y-m-d');						
					$sttime = date_format($startdate, 'H:i');

					$enddate = date_create($row1['enddate']);	
					$endate = date_format($enddate, 'Y-m-d');					
					$entime = date_format($enddate, 'H:i');
											
					if(($date==$stdate)&&($date==$endate)) {
						if($sttime=="09:00"&&$entime=="16:00") { 
							echo $inactive.$inactive; 
			    	 	} else if($sttime=="09:00"&&$entime=="12:00") { 
							echo $inactive; 
					      	echo active($date,"13:00","16:00",$roomid);   
						} else if($sttime=="13:00"&&$entime=="16:00") { 
							echo active($date,"09:00","12:00",$roomid);   
					      	echo $inactive;  
			     		} 
						echo $inactive;
				 	}

					else if(($date==$stdate)||($date==$endate)||
						   (($date>$stdate)&&($date<$endate))) {
						if($date==$stdate) {
							if($sttime=="09:00") { 
								echo $inactive.$inactive;
					 		} else if($sttime=="13:00") { 
								echo active($date,"09:00","12:00",$roomid);  
					      		echo $inactive; 
					  		}
						}
						else if($date==$endate) {
							if($entime=="12:00") { 
								echo $inactive;
						      	echo active($date,"13:00","16:00",$roomid);  
					 		} else if($entime=="16:00") { 
								echo $inactive.$inactive;
					  		}
						}
							else if(($date>$stdate)&&($date<$endate)) { 
								echo $inactive.$inactive;
					   	} 
						echo $inactive;
			   		}
				  }
				}
				else if($count==2){ 		
					echo $inactive.$inactive.$inactive;
		  		} 
			} else { 
				echo active($date,"09:00","12:00",$roomid);  
		      	echo active($date,"13:00","16:00",$roomid);  
		      	echo active($date,"09:00","16:00",$roomid);  
			} ?>								      	  		        		
		      </tr>
 	<?php } ?>
 	</tbody>
    </table>
<?php } }

else {
 	echo "<span style=\"color:red\">* กรุณากรอกข้อมูลให้ครบถ้วน</span>";
 } 
 } } ?>