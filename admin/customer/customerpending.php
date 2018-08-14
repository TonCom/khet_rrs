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
	
	//Set Path
	$isSubfolder = true;
	$activepage = "custpending";

	if (isset($_GET["ctrl"])&&$_GET["ctrl"]=="editactive") {
		$sql = "UPDATE user SET Active = 'yes'
			WHERE UserId = :customerid";	
		$stmp = $db->prepare($sql);
		$stmp->bindValue("customerid" , $_GET['CustomerId']); 
		$stmp->execute();
	}

	if (isset($_GET["ctrl"])&&$_GET["ctrl"]=="deletecust") {
		$sql = ("DELETE FROM user where UserId LIKE :customerid");
		$stmt = $db->prepare($sql);
		$stmt->bindParam('customerid', $_GET['CustomerId'], PDO::PARAM_INT);
		$stmt->execute();
	}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ข้อมูลผู้เช่ารออนุมัติ</title>
		<?php include "../include/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.min.css">
    </head>
    <style>
   	th,td{
   		text-align: center;
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
					<h3><b>ข้อมูลผู้เช่ารออนุมัติ</b></h3>	

			<table class="table table-bordered table-hover table-striped" id="TableCustomer">
			<thead>
		      <tr bgcolor="#CCCCCC">
		        <th style='display:none;'></th>
		        <th>เลขบัตรประชาชน</th>	        
		        <th>ชื่อ - นามสกุล</th>	
		        <th>เบอร์โทรศัพท์</th>	       
		        <th>ประเภทผู้เช่า</th>	
		        <th>รายละเอียดผู้เช่า</th>
		        <th>วันที่สมัคร</th>
		        <th></th>
		      </tr>
		    </thead>
			<tbody>
		<?php
			$sql = $db->prepare("SELECT UserId,CitizenId,PreName,FirstName,LastName,UserTypeId,Company,Address,Telephone,Username,Password,CreatedAt,UpdatedAt FROM user WHERE UserTypeId!=0 AND Active='no'");
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			while ($row = $sql->fetch()) {
				echo "<tr>";
				echo "<td style='display:none;'>".$row["CreatedAt"]."</td>";
				echo "<td>" .$row["CitizenId"]."</td>";
				echo "<td>" .$row["PreName"].$row["FirstName"]." ".$row["LastName"]."</td>";
				echo "<td>" .$row["Telephone"]."</td>";

					$sql1 = $db->prepare("SELECT TypeName FROM usertype WHERE TypeId='".$row['UserTypeId']."'"); 
							$sql1->execute();
							$sql1->setFetchMode(PDO::FETCH_ASSOC);
							while ($row1 = $sql1->fetch()) { 
								echo "<td>".$row1["TypeName"]."</td>";
							
			   
			    echo "<td>"; ?> 
				<a href="#" data-toggle="modal" data-target="#<?php echo $row["UserId"] ?>">ดูรายละเอียด</a>

                <div class="modal fade" id="<?php echo $row["UserId"] ?>" role="dialog">
				    <div class="modal-dialog">
				      <div class="modal-content">
				        <div class="modal-header">
				          <button type="button" class="close" data-dismiss="modal">&times;</button>
				          <h4 class="modal-title">ข้อมูลของผู้เช่า</h4>
				        </div>
				        <div class="modal-body">
					        <div class="row">
					        	<div class="col-md-12" style="font-size: 16px; margin-left: 10px; text-align: left">
					        		<div class="form-group"><label>เลขบัตรประจำตัวประชาชน:</label> &nbsp;&nbsp;<?php echo $row["CitizenId"] ?></div>
					        		<label>ชื่อ-นามสกุล:</label>&nbsp;&nbsp;<?php echo $row["PreName"].$row["FirstName"]." ".$row["LastName"] ?><br>
					        		<label>เบอร์โทรศัพท์:</label>&nbsp;&nbsp;<?php echo $row["Telephone"] ?><br>
					        		<label>ประเภทผู้เช่า:</label>&nbsp;&nbsp;<?php echo $row1["TypeName"] ?><br> <?php } ?>
					        		<label>ชื่อหน่วยงาน:</label>&nbsp;&nbsp;<?php echo $row["Company"] ?><br>
									 <label>ที่อยู่:</label><br> <textarea style="padding: 15px" readonly="readonly" rows="3" cols="63"><?php echo $row["Address"] ?></textarea><br><br>
									
									 <label>บัญชีผู้ใช้งาน:</label>&nbsp;&nbsp;<a href="mailto:<?=$row['Username'] ?>"><?=$row["Username"] ?></a><br>																		 
								</div>
							</div>
				        </div>
				        <div class="modal-footer">	
				        <div class="form-group">
				        <label>สมัครเมื่อวันที่</label>&nbsp;&nbsp;<?php echo DateThai($row["CreatedAt"]) ?> น.<br>
				        <?php if ($row["UpdatedAt"]!='0000-00-00 00:00:00') { ?>
				         <label>แก้ไขล่าสุดเมื่อวันที่</label>&nbsp;&nbsp;<?php echo DateThai($row["UpdatedAt"]) ?> น.<br> <?php } ?>
				        
				        </div>
				        		<!-- <a href='formeditcustomer.php?Id=<?=$row['UserId']?>' class="btn btn-primary">แก้ไขข้อมูล</a> -->			     			        	
				          <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
				        </div>
				      </div>      
				    </div>
				  </div>
				
				<?php
			    echo "</td>";
				echo "<td>" .DateThai($row["CreatedAt"])."</td>";		
			    echo "<td>";
            	   echo "<a href='?CustomerId=".$row["UserId"]."&ctrl=editactive' class='btn btn-success btn-sm' onclick=\"return confirm('คุณต้องการอนุมัติข้อมูลผู้เช่านี้หรือไม่ ?');\">อนุมัติ</a>";               
            	   echo " <a href='?CustomerId=".$row["UserId"]."&ctrl=deletecust' onclick=\"return confirm('คุณต้องการลบข้อมูลผู้เช่านี้หรือไม่ ?');\" class='btn btn-danger btn-sm'>ลบ</a>";
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
		    	$('#TableCustomer').DataTable( {
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

			       scrollY: "568px", // ปรับตอนที่แต่ละ Record เป็น หลายบรรทัด			         
			       scrollCollapse: true,
			       scroller: true				        			            		   		   	
			 	});
			});
        </script>
    </body>
</html>
<?php } } ?>