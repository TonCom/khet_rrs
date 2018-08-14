<?php


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
	$activepage = "holiday";

	function DateThai($strDate)
	{
		$strYear = substr(date("Y",strtotime($strDate))+543,-2);
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear";
	}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>รายการวันที่หยุดให้บริการ</title>
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
				
			<div class="container">
				<div class="row">
					<div class="col-md-6" style="margin: 30px 0  0 30px">				
						<div class="panel panel-default">     					
      				  <div class="panel-body">
						<a href="formaddholiday.php" class="btn btn-primary">เพิ่มข้อมูลวันหยุดให้บริการ</a>
			
			<table class="table table-bordered table-hover table-striped" id="TableHoliday">
			<thead>
		      <tr bgcolor="#CCCCCC">
		      	<th style='display:none;'></th>		        
		        <th>วันที่หยุดให้บริการ</th>
		        <th>ชื่อวันหยุดให้บริการ</th>		        		        
		        <th></th>
		      </tr>
		    </thead>
			<tbody>
		<?php
			$sql = $db->prepare("SELECT HolidayId,HolidayDate,HolidayName FROM holiday");
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			while ($row = $sql->fetch()) {
				echo "<tr>";
				echo "<td style='display:none;'>".$row["HolidayDate"]."</td>";			    
			    echo "<td>" .DateThai($row["HolidayDate"])."</td>";  
			    echo "<td>" .$row["HolidayName"]."</td>";                                   
			    echo "<td>";
            	   echo "<a href='formeditholiday.php?Id=".$row['HolidayId']."' data-toggle='tooltip' title='แก้ไข'><i style='font-size:25px' class='fa fa-pencil' aria-hidden='true'></i></a>";
            	    echo " <a href='deleteholiday.php?Id=".$row['HolidayId']."' onclick=\"return confirm('คุณต้องการลบข้อมูลวันหยุดนี้หรือไม่ ?');\" data-toggle='tooltip' title='ลบ'><i style='font-size:25px' class='fa fa-trash-o text-danger' aria-hidden='true'></i></a>";
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
		    	$('#TableHoliday').DataTable( {
		    	  "aaSorting": [[ 0, "desc" ]],
		    	   
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