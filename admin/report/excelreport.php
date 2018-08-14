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
	
	date_default_timezone_set('Asia/Bangkok');
	$xls_filename = 'Roomreport_'.date('d.m.Y').'.xls'; 
	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=$xls_filename");
	header("Pragma: no-cache");
	header("Expires: 0");

	function DateThai($strDate){
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

	function DateThai1($strDate){
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("m",strtotime($strDate));
		$strDay= date("d",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		return "$strDay/$strMonth/$strYear $strHour:$strMinute";
	}
?>

<html xmlns:o="urn:schemas-microsoft-com:office:office"xmlns:x="urn:schemas-microsoft-com:office:excel"xmlns="http://www.w3.org/TR/REC-html40">

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			
<style>
	h4, th, td { text-align: center; vertical-align: top; }
	
	.setfont { font-family: 'TH Sarabun New'; }
	.seth { font-size: 18pt; }
	.setth { font-size: 16pt; font-weight: bold; }
	.settd { font-size: 16pt; }
</style>
</head>
<body>

<?php	
	$startdate=isset($_GET["startdate"]); 
	$enddate=isset($_GET["enddate"]);
	$roomid=isset($_GET["roomid"]);	
    $actypeid=isset($_GET["actypeid"]);
	$customerid=isset($_GET["customerid"]);

if($startdate||$enddate||$roomid||$actypeid||$customerid) { ?>  
	
	<div align=center x:publishsource="Excel" class=setfont>	
		<div style="font-size: 26pt; font-weight: bold;">รายงานรายละเอียดการใช้บริการเช่าห้อง</div>

  <?php if(($startdate!="")&&($enddate!="")) {?>
		<div class='seth'>วันที่ <?=DateThai($_GET["startdate"])?> ถึง วันที่ <?=DateThai($_GET["enddate"])?></div> <?php } 		
  	    if($roomid!="") {?>
		<div class='seth'>ห้องที่ใช้บริการ: 
		<?php $sql1 = $db->prepare("SELECT RoomName from room WHERE RoomId='".$_GET['roomid']."'");
					$sql1->execute();
					$sql1->setFetchMode(PDO::FETCH_ASSOC); 
					if ($row1 = $sql1->fetch()) { echo $row1["RoomName"]?></div> <?php }} 
        if($actypeid!="") {?>
		<div class='seth'>ประเภทการเช่า: 
		<?php $sql2 = $db->prepare("SELECT TypeName from activitytype WHERE TypeId='".$_GET['actypeid']."'");
					$sql2->execute();
					$sql2->setFetchMode(PDO::FETCH_ASSOC); 
					if ($row2 = $sql2->fetch()) { echo $row2["TypeName"]?></div> <?php }} 
        if($customerid!="") {?>
		<div class='seth'>ผู้ขอใช้บริการ: 
		<?php $sql3 = $db->prepare("SELECT * from user WHERE CitizenId='".$_GET['customerid']."'");
					$sql3->execute();
					$sql3->setFetchMode(PDO::FETCH_ASSOC); 
					if ($row3 = $sql3->fetch()) { echo $row3["PreName"].$row3["FirstName"]." ".$row3["LastName"]?></div> <?php }} ?>		
	<br><br>

	<?php 
		$num=1;
		$sql = "SELECT idactivity,idcustomer,cprename,cfirstname,clastname,startdate,enddate,roomname,activityname,actypename from service"; 

		  	if($startdate||$enddate||$roomid||$actypeid||$customerid) {  		
  		$sql .= " WHERE "; 
    		}

   			if(($startdate!="")&&($enddate!="")) {
  		$sql .= "(
  		(('".$_GET['startdate']."' BETWEEN (DATE_FORMAT(startdate ,'%Y-%m-%d')) AND (DATE_FORMAT(enddate ,'%Y-%m-%d')))
		OR ('".$_GET['enddate']."' BETWEEN (DATE_FORMAT(startdate ,'%Y-%m-%d')) AND (DATE_FORMAT(enddate ,'%Y-%m-%d'))))	
	OR 
		(((DATE_FORMAT(enddate ,'%Y-%m-%d')) BETWEEN '".$_GET['startdate']."' AND '".$_GET['enddate']."')  
		OR ((DATE_FORMAT(enddate ,'%Y-%m-%d')) BETWEEN '".$_GET['startdate']."' AND '".$_GET['enddate']."'))
        )";
  			}

  			if($roomid!="") {
  				if(($startdate!="")&&($enddate!="")) { $A="AND";} else {$A="";} 	
  		$sql .= "$A roomid='".$_GET['roomid']."' ";
    		 }

  			if($actypeid!="") {
  				if((($startdate!="")&&($enddate!=""))||($roomid!="")) { $A="AND";} else {$A="";} 
     	$sql .= "$A actypeid='".$_GET['actypeid']."' ";
    		 }

    		if($customerid!="") {
  				if((($startdate!="")&&($enddate!=""))||($roomid!="")||($actypeid!="")) { $A="AND";} else {$A="";} 
     	$sql .= "$A idcustomer='".$_GET['customerid']."'";
    		 }

    	$sql .=" ORDER BY idactivity ASC";

  		$sql=$db->prepare($sql);
		$sql->execute();
		$sql->setFetchMode(PDO::FETCH_ASSOC); 				
		$count = $sql->rowCount(); 		
		if($count>0){ ?>	
  		
			 <table x:str border=1 cellpadding=0 cellspacing=1 style="border-collapse:collapse">		 <tr>
			        <th class='setth'>#</th>
			        <th class='setth'>ActID</th>
			    <?php if($customerid=="") {?><th class='setth'>CustomerID</th> <?php }?>
			        <th class='setth'>StartDate</th>
			        <th class='setth'>EndDate</th>
			    <?php if($roomid=="") {?><th class='setth'>Room</th> <?php }?>
			        <th class='setth'>ActivityName</th>
			    <?php if($actypeid=="") {?><th class='setth'>Type</th> <?php }?>	 
			      </tr>
			  			    
	  <?php while ($row = $sql->fetch()) { 			
			echo "<tr>";
			 	echo "<td class='settd'><b>" .$num."</b></td>";
				echo "<td class='settd'>".$row["idactivity"]."</td>";
			if($customerid=="") { echo "<td class='settd'>".$row["idcustomer"]."</td>"; }
				echo "<td class='settd'>".DateThai1($row["startdate"])."</td>";
				echo "<td class='settd'>".DateThai1($row["enddate"])."</td>";
			if($roomid=="") { echo "<td class='settd'>".$row["roomname"]."</td>"; }
				echo "<td class='settd'>".$row["activityname"]."</td>";
		    if($actypeid=="") { echo "<td class='settd'>".$row["actypename"]."</td>"; }
			echo "</tr>";
			
			$num++;
		 } ?>			    
			</table>
			
		
	<?php }
		else { 			
			echo "<div class='seth' style='color:red; font-weight:bold'>* ไม่พบการใช้บริการ กรุณาตรวจสอบใหม่อีกครั้ง !</div>";
		}	?>

<?php } ?>	
	</div>	
	
 	<script>
	window.onbeforeunload = function(){return false;};
	setTimeout(function(){window.close();}, 10000);
	</script>
</body>
</html>
<?php } } ?>