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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Report Print</title>
	<meta name="description" content="jQuery Print Area" />
	<meta name="keywords" content="jQuery Print Area" />
	<meta http-equiv="imagetoolbar" content="no" />
	<link href="../css/core.css" rel="stylesheet" media="screen" type="text/css" />
	<link href="../css/core.css" rel="stylesheet" media="print" type="text/css" />	
	
	<link rel="stylesheet" type="text/css" href="../css/fonts/thsarabunnew.css">
</head>
<style type="text/css" media="print">
	h4 { padding: 0px; text-align:center; }
	th, td { text-align: center; vertical-align: top; padding-top: 3px; }

@page { 
    size: auto;   
    margin: 0mm;
} 	

body {  
   	font-family: 'THSarabunNew'; 
   	font-size: 10pt;
   	margin: 0mm; 
}
</style>
<body>

<div id="wrapper">
	<div id="content">

<?php	
	$startdate=isset($_GET["startdate"]); 
	$enddate=isset($_GET["enddate"]);
	$roomid=isset($_GET["roomid"]);	
    $actypeid=isset($_GET["actypeid"]);
	$customerid=isset($_GET["customerid"]);

if($startdate||$enddate||$roomid||$actypeid||$customerid) { ?>  
	
	<br><div>
		<h2 style="text-align:center;">รายงานรายละเอียดการใช้บริการเช่าห้อง</h2>

  <?php if(($startdate!="")&&($enddate!="")) {?>
		<h4>วันที่ <?=DateThai($_GET["startdate"])?> ถึง วันที่ <?=DateThai($_GET["enddate"])?></h4> <?php } 		
  	    if($roomid!="") {?>
		<h4>ห้องที่ใช้บริการ: 
		<?php $sql1 = $db->prepare("SELECT RoomName from room WHERE RoomId='".$_GET['roomid']."'");
					$sql1->execute();
					$sql1->setFetchMode(PDO::FETCH_ASSOC); 
					if ($row1 = $sql1->fetch()) { echo $row1["RoomName"]?></h4> <?php }} 
        if($actypeid!="") {?>
		<h4>ประเภทการเช่า: 
		<?php $sql2 = $db->prepare("SELECT TypeName from activitytype WHERE TypeId='".$_GET['actypeid']."'");
					$sql2->execute();
					$sql2->setFetchMode(PDO::FETCH_ASSOC); 
					if ($row2 = $sql2->fetch()) { echo $row2["TypeName"]?></h4> <?php }} 
        if($customerid!="") {?>
		<h4>ผู้ขอใช้บริการ: 
		<?php $sql3 = $db->prepare("SELECT * from user WHERE CitizenId='".$_GET['customerid']."'");
					$sql3->execute();
					$sql3->setFetchMode(PDO::FETCH_ASSOC); 
					if ($row3 = $sql3->fetch()) { echo $row3["PreName"].$row3["FirstName"]." ".$row3["LastName"]?></h4> <?php }} ?>		
	</div><br><br>

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
  		<div>
			<table cellspacing="0" width="100%">				 
			      <tr>
			        <th style="border-bottom:2px solid #000;" >#</th>
			        <th style="border-bottom:2px solid #000;" >ActID</th>
			    <?php if($customerid=="") {?><th style="border-bottom:2px solid #000;" width="18%">CustomerID</th> <?php }?>
			        <th style="border-bottom:2px solid #000;" width="13%">StartDate</th>
			        <th style="border-bottom:2px solid #000;" width="13%">EndDate</th>
			    <?php if($roomid=="") {?><th style="border-bottom:2px solid #000;" width="1%">Room</th> <?php }?>
			        <th style="border-bottom:2px solid #000;">ActivityName</th>
			    <?php if($actypeid=="") {?><th style="border-bottom:2px solid #000;">Type</th> <?php }?>	 
			      </tr>
			  			    
	  <?php while ($row = $sql->fetch()) { 			
			echo "<tr>";
			 	echo "<td><b>" .$num."</b></td>";
				echo "<td>".$row["idactivity"]."</td>";
			if($customerid=="") { echo "<td>".$row["idcustomer"]."</td>"; }
				echo "<td>".DateThai1($row["startdate"])."</td>";
				echo "<td>".DateThai1($row["enddate"])."</td>";
			if($roomid=="") { echo "<td>".substr($row["roomname"],-3)."</td>"; }
				echo "<td>".$row["activityname"]."</td>";
		    if($actypeid=="") { echo "<td>".$row["actypename"]."</td>"; }
			echo "</tr>";
			
			$num++;
		 } 
		  echo "<tr>";
		  echo "<td style='border-top:2px solid #000;' colspan=\"8\">&nbsp;</td>"; 
		  echo "</tr>";
	 ?>			    
			</table>
		</div>	
	</div>
</div>		
	<?php }
		else { 			
			echo "<h3 style=\"color:red; text-align: center\">* ไม่พบการใช้บริการ กรุณาตรวจสอบใหม่อีกครั้ง !</h3>";
		}	?>

<?php } ?>		

 	<script src="../js/jquery-1.6.2.min.js"></script>
	<script src="../js/jquery.PrintArea.js_4.js"></script>
	<script src="../js/core.js"></script>
	<script type='text/javascript'>       
        $(document).ready(function() {                           
                window.print();
        });
    </script>
</body>
</html>
<?php } } ?>