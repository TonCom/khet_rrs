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
	$activepage = "searchrent";

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
        <title>สอบถามวันเวลาและห้องว่าง</title>
		<?php include "../include/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.min.css">
    </head>
    <style>
   	th,td{
   		text-align: center;
    }
    #TableHoliday {
    	font-size: 14px;
    }
    </style>
    <body>
		<?php include "../include/banner.php"; ?>	
			<?php include "../include/menu.php"; ?>	
				
			<div class="container">
				<div class="row">
					<div class="col-md-5" style="margin-left: 30px">
					<h3 style="padding-bottom: 5px"><b>สอบถามวันเวลาและห้องว่าง</b></h3>
					<div class="panel panel-default">     					
      				  <div class="panel-body">
						<div class="form-group">
					      <label>วันที่ให้บริการ:</label>
					       <div class="input-group"><input id="date" type="text" class="form-control" name="startdate" placeholder="เลือกวันที่ให้บริการ.."><span class="input-group-addon"><span class="fa fa-calendar"></span></span>
					    </div></div>

						<div class="form-group">
					      <label>วันเวลาที่เริ่ม:</label>
					      <div class="input-group"><input id="startdate" type="text" class="form-control" name="startdate" placeholder="เลือกวันเวลาที่เริ่ม.."><span class="input-group-addon"><span class="fa fa-calendar"></span></span>
					    </div></div>
					    
					    <div class="form-group">
					      <label>วันเวลาสิ้นสุด:</label>
					      <div class="input-group"><input id="enddate" type="text" class="form-control" name="enddate" placeholder="เลือกวันเวลาสิ้นสุด.."><span class="input-group-addon"><span class="fa fa-calendar"></span></span>
					    </div></div>									    

					    <div class="form-group">
					      <label>ห้องที่ให้บริการ:</label>
					      <select class="form-control" name="roomid" id="roomid">
					      <option value="">เลือกห้องที่ให้บริการ..</option>
							<?php
								$sql = $db->prepare("SELECT RoomId,RoomName FROM room WHERE Status='active'");
								$sql->execute();
								$sql->setFetchMode(PDO::FETCH_ASSOC);
								while ($row = $sql->fetch()) { ?>
									<option value="<?=$row['RoomId'] ?>"><?php echo $row["RoomName"]?></option>
							<?php } ?>  
						</select>
					    </div>
						
						<div style="text-align: center">
					      	<button id="retrieverent" type="button" class="btn btn-primary">ค้นหา</button>	
					      </div>	
					  </div>
					 </div>	

		 <table class="table table-bordered table-hover table-striped" id="TableHoliday">
			<thead>
		      <tr bgcolor="#CCCCCC">
		      	<th style='display:none;'></th>		        
		        <th>วันที่หยุดให้บริการ</th>
		        <th>ชื่อวันหยุดให้บริการ</th>		        		        		        
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
			    echo "</tr>";
			} ?>
			</tbody>
		</table>					 					    
					</div>		

					<div class="col-md-6" style="margin-left: 40px; padding-top: 60px">
						<div class="searchresult" id="searchresult"></div>
					</div>
				</div>
			</div>
				
        <?php include "../include/js.php"; ?>     
        <script src="../js/jquery.datetimepicker.full.min.js"></script> 
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
			       "aLengthMenu": [5],	 // ปรับตอนที่แต่ละ Record เป็นบรรทัดเดียว !		     

			       scrollY: "190px", // ปรับตอนที่แต่ละ Record เป็น หลายบรรทัด			         
			       scrollCollapse: true,
			       scroller: true				        			            		   		   	
			 	});
			});
        </script>  
         <script>        	
         	$(document).ready(function () { 
         		$('#roomid').val('').prop('disabled', true);
				$("#retrieverent").prop('disabled', true);

         		$('#date').change(function() {
         			if($('#date').val() != '') {
		         		$('#startdate').val('').prop('disabled', true);
		         		$('#enddate').val('').prop('disabled', true);
		         		$('#roomid').val('').prop('disabled', false);
		         		$("#retrieverent").prop('disabled', false); }
	         		else if($('#date').val() == '') {
		         		$('#startdate').val('').prop('disabled', false);
		         		$('#enddate').val('').prop('disabled', false); 
		         		$('#roomid').val('').prop('disabled', true);
						$("#retrieverent").prop('disabled', true);
		         	}
         		});

         		$('#startdate,#enddate').change(function() {
         			if(($('#startdate').val() != '') || 
         			  ($('#enddate').val() != '')) {
		         		$('#date').val('').prop('disabled', true);
		         		$('#roomid').val('').prop('disabled', false);
		         		$("#retrieverent").prop('disabled', true);
		         			if(($('#startdate').val() != '') && 
		         			  ($('#enddate').val() != '')) {		
		         				$("#retrieverent").prop('disabled', false);
		         		 	}	
		         	}
	         		else if(($('#startdate').val() == '') && 
	         		  ($('#enddate').val() == '')) {
		         		$('#date').val('').prop('disabled', false);
		         		$('#roomid').val('').prop('disabled', true);
		         		$("#retrieverent").prop('disabled', true);
		         	}
         		});
         	
         	
		  		$("#retrieverent").click(function(){
		  			var date = $("#date").val();
					var startdate = $("#startdate").val();
					var enddate = $("#enddate").val();
					var roomid = $("#roomid").val();						
						$.post("retrieverent.php",{ 
							date:date,
							startdate:startdate,
							enddate:enddate,
							roomid:roomid 
						},
						function(response){ 							
							$("#searchresult").html(response);									
						});	

					$("#date").val('').prop('disabled', false);
					$("#startdate").val('').prop('disabled', false);
					$("#enddate").val('').prop('disabled', false);
					$("#roomid").val('').prop('disabled', true);	
					$("#retrieverent").prop('disabled', true);				
				});

		  		$('#date').keyup(function () {
	       			$('#date').val('');      			
	        		alert('กรุณาเลือกข้อมูลวันเวลาจากปฏิทิน');
	   			});

				$('#startdate').keyup(function () {
	       			$('#startdate').val('');      			
	        		alert('กรุณาเลือกข้อมูลวันเวลาจากปฏิทิน');
	   			});

	   			$('#enddate').keyup(function () {
	       			$('#enddate').val('');      			
	        		alert('กรุณาเลือกข้อมูลวันเวลาจากปฏิทิน');
	   			});
			});
         </script> 
         <script>
         jQuery(function(){
         	$.datetimepicker.setLocale('th');
         	jQuery('#date').datetimepicker({
			  format: 'Y-m-d',						  
			  timepicker: false,
			  step: 1,
 			});

 			jQuery('#startdate').datetimepicker({
			format: 'Y-m-d H:i',
			onShow:function( ct ){
			   this.setOptions({
			   maxDate:jQuery('#enddate').val()?jQuery('#enddate').val():false
			   })
			},	
			defaultTime:'09:00',					  
			allowTimes:['09:00', '13:00']
 			});
 
			jQuery('#enddate').datetimepicker({
			format: 'Y-m-d H:i',
			onShow:function( ct ){
			   this.setOptions({
			   minDate:jQuery('#startdate').val()?jQuery('#startdate').val():false
			   })
			},
			defaultTime:'16:00',
			allowTimes:['12:00', '16:00']
			});
		});
     </script>     
    </body>
</html>
<?php } } ?>