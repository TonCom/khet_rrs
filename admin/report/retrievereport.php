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
	} ?>

<style>
#table-scroll {
  height:333px;
  overflow:auto;  
}
</style>

<?php
// กรอก วันที่เริ่มต้น , วันที่สิ้นสุด , ห้อง , 
//      ประเภทการเช่า , ผู้ขอใช้บริการ --> หา การใช้บริการ ?
if($_POST["startdate"]||$_POST["enddate"]||$_POST["roomid"]
  ||$_POST["actypeid"]||$_POST["customerid"]) {   

	$startdate=$_POST["startdate"]; 
	$enddate=$_POST["enddate"];
	$roomid=$_POST["roomid"];	
    $actypeid=$_POST["actypeid"];
	$customerid=$_POST["customerid"];

		$sql = "SELECT idactivity,cprename,cfirstname,clastname,startdate,enddate,roomname,activityname,actypename from service"; 

		  	if($startdate||$enddate||$roomid||$actypeid||$customerid) {  		
  		$sql .= " WHERE "; 
    		}

   			if(($startdate!="")&&($enddate!="")) {
  		$sql .= "(
  		(('".$startdate."' BETWEEN (DATE_FORMAT(startdate ,'%Y-%m-%d')) AND (DATE_FORMAT(enddate ,'%Y-%m-%d')))
		OR ('".$enddate."' BETWEEN (DATE_FORMAT(startdate ,'%Y-%m-%d')) AND (DATE_FORMAT(enddate ,'%Y-%m-%d'))))	
	OR 
		(((DATE_FORMAT(enddate ,'%Y-%m-%d')) BETWEEN '".$startdate."' AND '".$enddate."')  
		OR ((DATE_FORMAT(enddate ,'%Y-%m-%d')) BETWEEN '".$startdate."' AND '".$enddate."'))
        )";
  			}

  			if($roomid!="") {
  				if(($startdate!="")&&($enddate!="")) { $A="AND";} else {$A="";} 	
  		$sql .= "$A roomid=$roomid ";
    		 }

  			if($actypeid!="") {
  				if((($startdate!="")&&($enddate!=""))||($roomid!="")) { $A="AND";} else {$A="";} 
     	$sql .= "$A actypeid=$actypeid ";
    		 }

    		if($customerid!="") {
  				if((($startdate!="")&&($enddate!=""))||($roomid!="")||($actypeid!="")) { $A="AND";} else {$A="";} 
     	$sql .= "$A idcustomer='".$customerid."'";
    		 }

    	$sql .=" ORDER BY idactivity ASC";

  		$sql=$db->prepare($sql);
		$sql->execute();
		$sql->setFetchMode(PDO::FETCH_ASSOC); 	
			
			if(($startdate!="")&&($enddate!="")) {?>
			  	<h4><b>วันที่</b> <?php echo DateThai1($startdate)?> <b>ถึง วันที่</b> <?php echo DateThai1($enddate)?></h4> <?php }
			if($roomid!="") {?>			  
				<h4><b>ห้องที่ใช้บริการ:</b> 
				<?php $sql1 = $db->prepare("SELECT RoomName from room WHERE RoomId=$roomid");
					$sql1->execute();
					$sql1->setFetchMode(PDO::FETCH_ASSOC); 
					if ($row1 = $sql1->fetch()) { echo $row1["RoomName"]?></h4> <?php }}
			if($actypeid!="") {?>			  
				<h4><b>ประเภทการเช่า:</b> 
				<?php $sql2 = $db->prepare("SELECT TypeName from activitytype WHERE TypeId=$actypeid");
					$sql2->execute();
					$sql2->setFetchMode(PDO::FETCH_ASSOC); 
					if ($row2 = $sql2->fetch()) { echo $row2["TypeName"]?></h4> <?php }}
			if($customerid!="") {?>			  
				<h4><b>ผู้ขอใช้บริการ:</b> 
				<?php $sql3 = $db->prepare("SELECT * from user WHERE CitizenId='".$customerid."'");
					$sql3->execute();
					$sql3->setFetchMode(PDO::FETCH_ASSOC); 
					if ($row3 = $sql3->fetch()) { echo $row3["PreName"].$row3["FirstName"]." ".$row3["LastName"]?></h4> <?php }}

		$count = $sql->rowCount(); 		
		if($count>0){ ?>	
  		<div id="table-scroll">
			<table class="table table-bordered table-hover table-striped">
				<thead>
			      <tr bgcolor="#CCCCCC">
			        <th>#</th>	
			    <?php if($customerid=="") {?> <th>ผู้ขอใช้บริการ</th> <?php }?>
			        <th>วันเวลาที่เริ่ม</th>	
			        <th>วันเวลาสิ้นสุด</th>	        
			    <?php if($roomid=="") {?> <th>ห้องที่ใช้บริการ</th> <?php }?>
			        <th>ชื่อโครงการ</th>		        
			    <?php if($actypeid=="") {?> <th>ประเภทการเช่า</th> <?php }?>	 		        	
			      </tr>
			    </thead>
			    <tbody>
	  <?php while ($row = $sql->fetch()) { 			
			echo "<tr bgcolor='#FFFFFF'>";
				echo "<td><b>" .$row["idactivity"]."</b></td>";
			if($customerid=="") { echo "<td>" .$row["cfirstname"]." ".$row["clastname"]."</td>"; }
				echo "<td>" .DateThai($row["startdate"])."</td>";
				echo "<td>" .DateThai($row["enddate"])."</td>";
			if($roomid=="") { echo "<td>" .$row["roomname"]."</td>"; }
				echo "<td>" .$row["activityname"]."</td>";
			if($actypeid=="") { echo "<td>" .$row["actypename"]."</td>"; }
			echo "</tr>";
		 } ?>
			    </tbody>
			</table>
		</div>

	<script> $("#retrieveprint").prop('disabled', false); 
	         $("#retrieveexcel").prop('disabled', false); </script>	
	<?php }
		else { 			
			echo "<br><h4><span style=\"color:red\">* ไม่พบการใช้บริการ</span> กรุณาตรวจสอบใหม่อีกครั้ง !</h4>"; ?>

			<script> $("#retrieveprint").prop('disabled', true); 
			         $("#retrieveexcel").prop('disabled', true); </script>
	<?php }		
}
else {
 	return false;
 } } } ?>		