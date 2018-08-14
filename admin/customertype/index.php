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
	
	//Set Path
	$isSubfolder = true;
	$activepage = "customertype";
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ข้อมูลประเภทผู้เช่า</title>
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
				
			<div class="container">
				<div class="row">
					<div class="col-md-6" style="margin: 30px 0  0 30px">				
					<div class="panel panel-default">     					
      				  <div class="panel-body">	
						<a href="formaddcustomertype.php" class="btn btn-primary">เพิ่มประเภทผู้เช่า</a>
			
			<table class="table table-bordered table-hover table-striped" id="TableCustomerType">
			<thead>
		      <tr bgcolor="#CCCCCC">
		      	<th style='display:none;'></th>
		        <th>#</th>
		        <th>ชื่อประเภทผู้เช่า</th>		        		        
		        <th></th>
		      </tr>
		    </thead>
			<tbody>
		<?php
			$num=1;
			$sql = $db->prepare("SELECT TypeId,TypeName FROM usertype WHERE TypeId>0");
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			while ($row = $sql->fetch()) {
				echo "<tr bgcolor='#FFFFFF'>";
				echo "<td style='display:none;'>".$row["TypeId"]."</td>";
			    echo "<td>" .$num."</td>";
			    echo "<td>" .$row["TypeName"]."</td>";                                     
			    echo "<td>";
            	   echo "<a href='formeditcustomertype.php?Id=".$row['TypeId']."' data-toggle='tooltip' title='แก้ไข'><i style='font-size:25px' class='fa fa-pencil' aria-hidden='true'></i></a>";

        	$sql1 = $db->prepare("SELECT COUNT(UserId) AS countus FROM user 
            	WHERE UserTypeId='".$row['TypeId']."'");
			$sql1->execute();
			$sql1->setFetchMode(PDO::FETCH_ASSOC);
			if ($row1 = $sql1->fetch()) {								               
            	   echo " <a href='deletecustomertype.php?Id=".$row['TypeId']."' class='".
            	   ($row1["countus"]==0? "" : "disabled")."' onclick=\"return confirm('คุณต้องการลบประเภทผู้เช่านี้หรือไม่ ?');\" data-toggle='tooltip' title='ลบ'><i style='font-size:25px; ".($row1["countus"]==0? "" : "opacity: 0.5")."' class='fa fa-trash-o text-danger' aria-hidden='true'></i></a>"; }
            	echo "</td>";
                echo "</tr>";
            $num++;
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
		    	$('#TableCustomerType').DataTable( {
		    	  "aaSorting": [[ 0, "asc" ]],
		    	   
		    	   "language": {
		    	  	"sSearch": "ค้นหา:",
		    	  	"sZeroRecords": "ไม่พบข้อมูลที่ต้องการค้นหา", 
		    	  	"sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
		    	  	"sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 รายการ",
		    	   	"infoFiltered": "",
		    	   	"oPaginate": {     
				        "sPrevious": "ก่อนหน้า",
				        "sNext": "ถัดไป"
        				}  
		    	   },

		    	   pagingType: "simple",
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