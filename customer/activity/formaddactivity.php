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
	
	//Set Path
	$isSubfolder = false;
	$activepage = "addactivity";
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>บันทึกข้อมูลการขอเช่าห้อง</title>
		<?php include "../../admin/include/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="../../admin/css/jquery.datetimepicker.css">
    </head>
    <style>     
   	.center-block {
   		float: none !important
   	} 
   	</style>
    <body>
		<?php include "../include/banner.php"; ?>
		<?php include "../include/menu.php"; ?>

		<div class="container">
		<br><br>
			
			<form role="form" action="addactivity.php" method="post" onsubmit="return confirm('คุณต้องการบันทึกข้อมูลการขอเช่าห้องนี้หรือไม่ ?');">	
				<div class="row">				
					<div class="col-md-7 center-block">	
					<div class="panel panel-default">
      				<div class="panel-body"> 
      				<h3 style="text-align: center"><b>บันทึกข้อมูลการขอเช่าห้อง</b></h3>					
      				<hr>		

      <?php	$sql = $db->prepare("SELECT * FROM user WHERE UserId='".$_SESSION['UserId']."'");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);                   
    if ($row = $sql->fetch()) { $name=$row['PreName'].$row['FirstName']." ".$row['LastName']; } ?>					
					<div style="padding-left: 16px; padding-bottom: 7px"><label>ผู้ขอใช้บริการ:</label>&nbsp;&nbsp;&nbsp;<?=$name?></div>
					
					 <div class="panel panel-default">
      				  <div class="panel-body">
					    <div class="form-group">
					      <div class="col-md-4" style="padding-left: 0"><label>วันเวลาที่เริ่ม:</label></div>
					      <div class="col-md-8" style="padding-right: 0"><input id="startdate" type="text" class="form-control" name="startdate" placeholder="เลือกวันเวลาที่เริ่ม.." value="<?php if(isset($_GET['Startdate'])) { echo $_GET['Startdate']; }?>"> </div>
					    </div>

					    <div class="form-group" style="padding-top: 28px">
					      <div class="col-md-4" style="padding-left: 0"><label>วันเวลาสิ้นสุด:</label></div>
					      <div class="col-md-8" style="padding-right: 0"><input id="enddate" type="text" class="form-control" name="enddate" placeholder="เลือกวันเวลาสิ้นสุด.."
					      value="<?php if(isset($_GET['Enddate'])) { echo $_GET['Enddate']; }?>"> </div>
					    </div>

					    <div class="form-group" style="padding-top: 28px">
					      <div class="col-md-4" style="padding-left: 0"><label>ห้องที่ให้บริการ:</label></div>
					      <div class="col-md-8" style="padding-right: 0"><select required class="form-control" name="roomid" id="roomid">
					      <option value="">เลือกห้องที่ให้บริการ..</option>
							<?php
								$sql = $db->prepare("SELECT RoomId,RoomName FROM room WHERE Status='active'");
								$sql->execute();
								$sql->setFetchMode(PDO::FETCH_ASSOC);
								while ($row = $sql->fetch()) { ?>
									<option value="<?=$row['RoomId'] ?>" <?php if (isset($_GET['Roomid'])) { if ($_GET['Roomid']==$row['RoomId']) { echo "selected"; }}?>><?php echo $row["RoomName"]?></option>
							<?php } ?>  
						</select></div>					
					    </div>

					    <div class="form-group" style="padding-top: 28px">
					      <div class="col-md-9" style="padding-left: 0; padding-top: 5px" class="searchresult" id="searchresult"></div>
					      <div class="col-md-3" style="text-align: right; padding: 0"><a id="retrieverent" class="btn btn-primary btn-sm">ตรวจสอบ</a></div>
					    </div>					    
					   </div>
					  </div> 
					
					<div class="panel panel-default">
      				  <div class="panel-body">					    
					    <div class="form-group">
					      <label>ชื่อโครงการ:</label>
					      <input type="text" class="form-control" name="activityname" 
					      placeholder="กรอกชื่อโครงการ..">
					    </div>
					    
						<div class="form-group">
					       <label>ประเภทการเช่า:</label>
					      <select required class="form-control" name="activitytypeid">
					      <option value="">เลือกประเภทการเช่า..</option>
							<?php
								$sql = $db->prepare("SELECT TypeId,TypeName FROM activitytype");
								$sql->execute();
								$sql->setFetchMode(PDO::FETCH_ASSOC);
								while ($row = $sql->fetch()) { ?>
									<option value="<?=$row['TypeId'] ?>"><?php echo $row["TypeName"]?></option>
							<?php } ?>  
						</select>
					    </div>
				    	
					      <label>รายละเอียดการเช่า:</label>					    
					      <textarea class="form-control" name="details" rows="4"
					      placeholder="กรอกรายละเอียดที่ต้องการ.."></textarea>
					    </div>
					</div>

					<div class="panel panel-default">
      				  <div class="panel-body">	
					<div class="form-group">
					      <div class="col-md-7" style="padding-left: 0"><label>อาหารว่าง: &nbsp;</label>	<label class="radio-inline"><input required id="isbreak" type="radio" name="isbreak" value="nothave">ไม่มี</label>		
						  <label class="radio-inline"><input id="isbreak1" type="radio" name="isbreak" value="have">มี งบประมาณ</label></div> <div class="col-md-4" style="padding-left: 0">
					     	 	<input required id="feebreak" type="number" class="form-control" name="feebreak">
					     	</div><label>บาท</label>	
					     	</div>	

					     	<div class="form-group">
					      <div class="col-md-7" style="padding-left: 0"><label>อาหารเที่ยง: &nbsp;</label>	<label class="radio-inline"><input required id="islunch" type="radio" name="islunch" value="nothave">ไม่มี</label>		
						  <label class="radio-inline"><input id="islunch1" type="radio" name="islunch" value="have">มี งบประมาณ</label></div> <div class="col-md-4" style="padding-left: 0">
					     	 	<input required id="feelunch" type="number" class="form-control" name="feelunch">
					     	</div><label>บาท</label>	
					     	</div>									
 						</div>
 					 </div>
					


						
					

					 <div style="text-align:center">
					   	  <button type="submit" class="btn btn-success" id="btnsubmit">บันทึก</button>	
					   	  <a href="../../index.php" class="btn btn-danger">ยกเลิก</a>
					</div>
					</div>
				</div>	
				</div>
				</div>												  
			</form>		
		</div>

		<?php include "../include/footer.php"; ?>						

        <?php include "../../admin/include/js.php"; ?>      
         <script src="../../admin/js/jquery.datetimepicker.full.min.js"></script> 
         <script>  
         $(document).ready(function () {
         	$("#feebreak").prop('disabled', true);
         	$("#feelunch").prop('disabled', true);
         	$("#btnsubmit").prop('disabled', true);

         	$('#startdate,#enddate,#roomid').change(function() {
       	 		$("#btnsubmit").prop('disabled', true);
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
                 	
         $(document).ready(function () {         			
		  	$("#retrieverent").click(function(){		  			
				var startdate = $("#startdate").val();
				var enddate = $("#enddate").val();
				var roomid = $("#roomid").val();

				if(startdate==""||enddate==""||roomid=="") {	
					response="<span style=\"color:red\">* กรุณากรอกข้อมูลให้ครบถ้วน</span>";
					$("#searchresult").html(response);
					$("#btnsubmit").prop('disabled', true);	
				}
				else {					
					$.post("retrieverent.php",{ 							
						startdate:startdate,
						enddate:enddate,
						roomid:roomid 
					},
				function(response){ 
					if(response == false) {
						response="<span class='label label-danger'>ไม่พร้อมให้บริการ</span> กรุณาตรวจสอบใหม่อีกครั้ง !";
						$("#searchresult").html(response);
						$("#btnsubmit").prop('disabled', true);							
					}
					else{
						$("#searchresult").html(response);
						$("#btnsubmit").prop('disabled', false);
					}							
				});	
			  }					
			});
		});           

		$('#isbreak,#isbreak1').change(function(){
	    	if ($('#isbreak').is(':checked') == true){
	       		 $('#feebreak').val('').prop('disabled', true);}
	        else if ($('#isbreak1').is(':checked') == true){ 
	        		  $('#feebreak').val('').prop('disabled', false); }    
     	 });  

     	 $('#islunch,#islunch1').change(function(){
	    	if ($('#islunch').is(':checked') == true){
	       		 $('#feelunch').val('').prop('disabled', true);}
	        else if ($('#islunch1').is(':checked') == true){ 
	        		  $('#feelunch').val('').prop('disabled', false); }    
     	 });      	
		</script>
         <script>
         jQuery(function(){
         	$.datetimepicker.setLocale('th');
 			jQuery('#startdate').datetimepicker({
			format: 'Y-m-d H:i',
			onShow:function( ct ){
			   this.setOptions({
			   minDate:0,
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