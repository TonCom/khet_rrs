<?php

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	session_start();

	if(!isset($_SESSION['UserId'])||$_SESSION['UserTypeId']!=0) {
		
		header('Location: ../login.php?error=0'); 
	} else if($_SESSION['StaffLevel']!="sadmin") {
		session_destroy();
        header('Location: ../login.php?error=3');
	} else {        
        if(time() > $_SESSION['expire']) {
            session_destroy();
            header('Location: ../login.php?error=4');              
        } else {

	require "../include/connect.php";
	require "../include/fnDatethai.php";
	
	//Set Path
	$isSubfolder = true;
	$activepage = "staff";

	function setlevel($level){
		if($level=="lab") { 
		 	return "เจ้าหน้าที่ห้องปฏิบัติการ"; }
		else if($level=="maid") { 
			return "แม่บ้าน"; }	
		else if($level=="admin") { 
			return "เจ้าหน้าที่โครงการ"; }	
		else if($level=="sadmin") { 
			return "ผู้ดูแลระบบ"; }		
	}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ข้อมูลเจ้าหน้าที่ทั้งหมด</title>
		<?php include "../include/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.min.css">
    </head>
    <style>
   	th,td{
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
					<div class="table-responsive" style="padding-left: 40px; width:75%">				
					<div class="panel panel-default">     					
      				  <div class="panel-body">	
							<a href="formaddstaff.php" class="btn btn-primary">เพิ่มข้อมูลเจ้าหน้าที่</a>
			
			<table class="table table-bordered table-hover table-striped" id="TableStaff">
			<thead>
		      <tr bgcolor="#CCCCCC">
		    	<th style='display:none;'></th>	
		      	<th>ชื่อ - นามสกุล</th>
		        <th>บัญชีผู้ใช้งาน</th>		        		        		        		       
		        <th>ประเภทเจ้าหน้าที่</th>
		        <th>เวลาเข้าใช้งานล่าสุด</th>	
		        <th></th>
		      </tr>
		    </thead>
			<tbody>
		<?php
			$sql = $db->prepare("SELECT UserId,Username,Password,PreName,FirstName,LastName,StaffLevel,LastLogin FROM user WHERE UserTypeId=0");
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			while ($row = $sql->fetch()) {
				echo "<tr bgcolor='#FFFFFF'>";
				echo "<td style='display:none;'>".$row["StaffLevel"]."</td>";		
				echo "<td>" .$row["PreName"].$row["FirstName"]." ".$row["LastName"]."</td>";
			    echo "<td>" .$row["Username"]."</td>";			               
                echo "<td>" .setlevel($row["StaffLevel"])."</td>";              
                echo "<td>" .($row["LastLogin"]==NULL? "<span class='label label-default'>ไม่พบการเข้าใช้งาน</span>" : DateThai($row["LastLogin"]))."</td>";
                echo "<td>";
            	   echo "<a href='formeditstaff.php?Id=".$row['UserId']."' data-toggle='tooltip' title='แก้ไข'><i style='font-size:25px' class='fa fa-pencil' aria-hidden='true'></i></a>"; 

            $sql1 = $db->prepare("SELECT COUNT(ActivityId) AS countact FROM activity 
            	WHERE AdminId='".$row['UserId']."' 
            	   OR LabId='".$row['UserId']."' 
            	   OR MaidId='".$row['UserId']."'");
			$sql1->execute();
			$sql1->setFetchMode(PDO::FETCH_ASSOC);
			if ($row1 = $sql1->fetch()) {								               
            	   echo " <a href='deletestaff.php?Id=".$row['UserId']."' class='".
            	   ($row1["countact"]==0? "" : "disabled")."' onclick=\"return confirm('คุณต้องการลบบัญชีผู้ใช้นี้หรือไม่ ?');\" data-toggle='tooltip' title='ลบ'><i style='font-size:25px; ".($row1["countact"]==0? "" : "opacity: 0.5")."' class='fa fa-trash-o text-danger' aria-hidden='true'></i></a>"; }
                echo "</td>";
                echo "</tr>";
			}
		?>
			</tbody>
		</table>
		</div>
				</div>
					</div>
				</div>
			</div>
				
        <?php include "../include/js.php"; ?>  
        <script src="../js/jquery.dataTables.min.js"></script>
        <script>
        	$(document).ready(function() {
        		$.fn.dataTable.ext.pager.numbers_length = 5;
		    	$('#TableStaff').DataTable( {
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
			       "aLengthMenu": [10],	 // ปรับตอนที่แต่ละ Record เป็นบรรทัดเดียว !		     

			       scrollY: "539px", // ปรับตอนที่แต่ละ Record เป็น หลายบรรทัด			         
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