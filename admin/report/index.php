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
	require "../include/newId.php";

	//Set Path
	$isSubfolder = true;
	$activepage = "report";
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>รายงานการใช้บริการเช่าห้อง</title>
		<?php include "../include/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">		
    </head> 
    <style>   
   	th,td{ 
   		font-size: 14px; 		
   		text-align: center;
    }
    </style>   
    <body>
		<?php include "../include/banner.php"; ?>
		<?php include "../include/menu.php"; ?>	

			<div class="container-fluid">						
				<div class="row">				
					<div class="table-responsive" style="margin: 0 auto; width:80%">	
					<h3 style="padding-bottom: 5px"><b>รายงานการใช้บริการเช่าห้อง</b></h3>

					<div class="panel panel-default">
      				  <div class="panel-body">
					    
					    <div class="form-group">
					      <div class="col-md-2" style="padding-left: 15px"><label>วันที่เริ่มใช้บริการ:</label></div>
					      <div class="col-md-4" style="padding-right: 20px"><input id="startdate" type="text" class="form-control" name="startdate" placeholder="เลือกวันที่เริ่มใช้บริการ.."> </div>
 						
 						<div class="col-md-2" style="padding-left: 15px"><label>ประเภทการเช่า:</label></div>
					      <div class="col-md-4" style="padding-right: 10px"><select required class="form-control" name="activitytypeid" id="activitytypeid">
					      <option value="">เลือกประเภทการเช่า..</option>
							<?php
								$sql = $db->prepare("SELECT TypeId,TypeName FROM activitytype");
								$sql->execute();
								$sql->setFetchMode(PDO::FETCH_ASSOC);
								while ($row = $sql->fetch()) { ?>
									<option value="<?=$row['TypeId'] ?>"><?php echo $row["TypeName"]?></option>
							<?php } ?>  
						</select> </div>						
					    </div>

					    <div class="form-group" style="padding-top: 28px">
					      <div class="col-md-2" style="padding-left: 15px"><label>วันสิ้นสุดใช้บริการ:</label></div>
					      <div class="col-md-4" style="padding-right: 20px"><input id="enddate" type="text" class="form-control" name="enddate" placeholder="เลือกวันสิ้นสุดใช้บริการ.."> </div>
						
						<div class="col-md-2" style="padding-left: 15px"><label>ผู้ขอใช้บริการ:</label></div>
					      <div class="col-md-3" style="padding-right: 10px"><input type="text" class="form-control" name="customerid" id="customerId" style="letter-spacing: 1px;" placeholder="กรอกเลขบัตรประชาชนผู้ขอใช้บริการ.."></div>
					      <div class="col-md-1" style="text-align: right; padding-right: 10px">
					      	<button id="retrievecustomer" type="button" class="btn btn-success">ค้นหา</button>				
					      </div>
					    </div>

					    <div class="form-group" style="padding-top: 28px">
					      <div class="col-md-2" style="padding-left: 15px"><label>ห้องที่ใช้บริการ:</label></div>
					      <div class="col-md-4" style="padding-right: 20px"><select required class="form-control" name="roomid" id="roomid">
					      <option value="">เลือกห้องที่ใช้บริการ..</option>
							<?php
								$sql = $db->prepare("SELECT RoomId,RoomName FROM room");
								$sql->execute();
								$sql->setFetchMode(PDO::FETCH_ASSOC);
								while ($row = $sql->fetch()) { ?>
									<option value="<?=$row['RoomId'] ?>"><?php echo $row["RoomName"]?></option>
							<?php } ?>  
						</select></div>	
						
						<div class="searchresult" id="searchresult"></div>
						</div>

						 <div class="form-group">		
						  <div class="col-md-9" style="text-align: left; padding-left: 15px"><a href="index.php" class="btn btn-danger">ล้างข้อมูล</a></div>		
					      <div class="col-md-3" style="text-align: right; padding-right: 10px"><button id="retrievereport" type="button" class="btn btn-primary">สรุปรายงาน</button> <button id="retrieveprint" type="button" class="btn btn-warning">พิมพ์</button> <button id="retrieveexcel" type="button" class="btn btn-danger">Excel</button></div>
					    </div>
					    		    
					   </div>
					  </div> 
					</div>	


					<div class="table-responsive" id="searchresult1" style="margin: 0 auto; width:80%"></div>
				
			  </div>   			 					   				
			</div>	
							
				 															
        <?php include "../include/js.php"; ?>  
         <script src="../js/jquery.datetimepicker.full.min.js"></script>         
         <script>  
         $(document).ready(function () {        	
         	$("#retrievereport").prop('disabled', true);	
         	$("#retrieveprint").prop('disabled', true);	
         	$("#retrieveexcel").prop('disabled', true);		

         	$('#startdate,#enddate,#roomid,#activitytypeid,#customerId').change(function() {
    
    $("#retrieveprint").prop('disabled', true);	
    $("#retrieveexcel").prop('disabled', true);	

    if(($('#startdate').val() != '') || ($('#enddate').val() != '')) {
		$("#retrievereport").prop('disabled', true);
			if(($('#startdate').val() != '') && ($('#enddate').val() != '')) {		
				$("#retrievereport").prop('disabled', false);
			}	
	}
	
	if($('#roomid').val() != '') {
		$("#retrievereport").prop('disabled', false); 
			if ($('#roomid').val() != '' && (($('#startdate').val() != '') || ($('#enddate').val() != ''))) {
		        	$("#retrievereport").prop('disabled', true); 
				if ($('#roomid').val() != '' && (($('#startdate').val() != '') && ($('#enddate').val() != ''))){  
						$("#retrievereport").prop('disabled', false); 
				} 
		    } 
	}

	if($('#activitytypeid').val() != '') {
		$("#retrievereport").prop('disabled', false); 
			if ($('#activitytypeid').val() != '' && (($('#startdate').val() != '') || ($('#enddate').val() != ''))) {
		        	$("#retrievereport").prop('disabled', true); 
				if ($('#activitytypeid').val() != '' && (($('#startdate').val() != '') && ($('#enddate').val() != ''))){  
						$("#retrievereport").prop('disabled', false); 
				} 
		    } 
	}

	if($('#customerId').val() != '') {
		$("#retrievereport").prop('disabled', true); 
	}
		        
	if (($('#startdate').val() == '') && ($('#enddate').val() == '') 
	  && ($('#roomid').val() == '') && ($('#activitytypeid').val() == '')
		&& ($('#customerId').val() == '')) {
		        $("#retrievereport").prop('disabled', true); }	 						       
         	});
        });

         $(document).ready(function () {  
         $("#retrievecustomer").click(function(){		  			
				var customerid = $("#customerId").val();				

				if(customerid=="") {	
					response="<div class='col-md-2'></div><div class='col-md-4' style='padding-top: 7px'><span style=\"color:red\">* กรุณากรอกข้อมูล<b>เลขบัตรประชาชน</b>ให้ครบถ้วน</span></div>";
					$("#searchresult").html(response);										
				}
				else {					
					$.post("retrievecustomer.php",{ 							
						customerid:customerid						 
					},
				function(response){ 
					if(response == false) {
						response="<div class='col-md-2'></div><div class='col-md-4' style='padding-top: 7px'><span style=\"color:red\">* ไม่พบข้อมูลผู้ขอใช้บริการ</span> กรุณาตรวจสอบใหม่อีกครั้ง !</div>";
						$("#searchresult").html(response);	
						$("#retrievereport").prop('disabled', true);								
					}
					else{
						$("#searchresult").html(response);								
						$("#retrievereport").prop('disabled', false);				
					}							
				});	
			  }					
			 });
		}); 
                 	
         $(document).ready(function () {         			
		  	$("#retrievereport").click(function(){		  			
				var startdate = $("#startdate").val();
				var enddate = $("#enddate").val();
				var roomid = $("#roomid").val();
			    var actypeid = $("#activitytypeid").val();
				var customerid = $("#customerId").val();
				
					$.post("retrievereport.php",{ 							
						startdate:startdate,
						enddate:enddate,
						roomid:roomid, 
						actypeid:actypeid,
						customerid:customerid 
					},
				function(response){ 
					if(response == false) {
						response="<h4><span style=\"color:red\">* ไม่พบการใช้บริการ</span> กรุณาตรวจสอบใหม่อีกครั้ง !</h4>";
						$("#searchresult1").html(response);											
					}
					else{
						$("#searchresult1").html(response);
					}							
				});	
			  				
			});
		});               		    	      	
		</script>

		<script type="text/javascript">
			$(document).ready(function () {
				$("#retrieveprint").click(function(){
					var startdate = $("#startdate").val();
					var enddate = $("#enddate").val();
					var roomid = $("#roomid").val();
				    var actypeid = $("#activitytypeid").val();
					var customerid = $("#customerId").val();
					var se="", r="", a="", c="";
					
					if((startdate!="")&&(enddate!="")) {
						se= "startdate="+startdate+"&enddate="+enddate;
					} 

					if(roomid!="") {
						if((startdate!="")&&(enddate!="")) { r="&"; }
					  r=r+"roomid="+roomid; 
					}

					if(actypeid!="") {
						if(((startdate!="")&&(enddate!=""))||(roomid!="")) { a="&"; }
					  a=a+"actypeid="+actypeid; 
					}

					if(customerid!="") {
						if(((startdate!="")&&(enddate!=""))||(roomid!="")||(actypeid!="")) { c="&"; }
					  c=c+"customerid="+customerid; 
					}
				 									
		  			var url = "printreport.php?"+se+r+a+c;
					window.open(url,'','height=750,width=1000');					
		 		});							
			});
		</script> 

		<script type="text/javascript">
			$(document).ready(function () {
				$("#retrieveexcel").click(function(){
					var startdate = $("#startdate").val();
					var enddate = $("#enddate").val();
					var roomid = $("#roomid").val();
				    var actypeid = $("#activitytypeid").val();
					var customerid = $("#customerId").val();
					var se="", r="", a="", c="";
					
					if((startdate!="")&&(enddate!="")) {
						se= "startdate="+startdate+"&enddate="+enddate;
					} 

					if(roomid!="") {
						if((startdate!="")&&(enddate!="")) { r="&"; }
					  r=r+"roomid="+roomid; 
					}

					if(actypeid!="") {
						if(((startdate!="")&&(enddate!=""))||(roomid!="")) { a="&"; }
					  a=a+"actypeid="+actypeid; 
					}

					if(customerid!="") {
						if(((startdate!="")&&(enddate!=""))||(roomid!="")||(actypeid!="")) { c="&"; }
					  c=c+"customerid="+customerid; 
					}

					var url= "excelreport.php?"+se+r+a+c;
					window.open(url);
				});							
			});
		</script> 

         <script>
         jQuery(function(){
         	$.datetimepicker.setLocale('th');
 			jQuery('#startdate').datetimepicker({
			format: 'Y-m-d',
			timepicker: false,
			onShow:function( ct ){
			   this.setOptions({
			   maxDate:jQuery('#enddate').val()?jQuery('#enddate').val():false
			   })
			}				
 			});
 
			jQuery('#enddate').datetimepicker({
			format: 'Y-m-d',
			timepicker: false,
			onShow:function( ct ){
			   this.setOptions({
			   minDate:jQuery('#startdate').val()?jQuery('#startdate').val():false
			   })
			}
			});
		});
     </script>  

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js" type="text/javascript"></script>
        <script>
        $.mask.definitions['~']='[+-]';
		$('#customerId').mask('9-9999-99999-99-9'); 		
		</script> 
    </body>
</html>
<?php } } ?>