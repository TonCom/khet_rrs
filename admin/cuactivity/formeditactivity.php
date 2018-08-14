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
	$activepage = "listallrent";
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>แก้ไขข้อมูลการขอเช่าห้อง</title>
		<?php include "../include/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">
    </head>
    <body>
		<?php include "../include/banner.php"; ?>

		<?php include "../include/menu.php"; ?>				
		
		<?php
			$sql = "SELECT ActivityId,CustomerId,StartDate,EndDate,RoomId,ActivityName,Details,ActivityTypeId,IsBreak,FeeBreak,IsLunch,FeeLunch,FeeRoom,FeeOthers,AdminId,LabId,MaidId,RentStatus FROM activity WHERE ActivityId LIKE :Id";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':Id', $_GET['Id'], PDO::PARAM_STR);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			while ($row = $stmt->fetch()) {			 	
		?>

			<div class="container" >
			<h3 style="margin-left:43px"><b>แก้ไขข้อมูลการขอเช่าห้อง</b></h3><br>
			<form role="form" action="editactivity.php" method="post" onsubmit="return confirm('คุณต้องการแก้ไขข้อมูลการขอเช่าห้องนี้หรือไม่ ?');">	
				<div class="row" style="margin-left: 30px">				
					<div class="col-md-5">	
					
					<div class="form-group"><label>สัญญาเช่าเลขที่:</label>&nbsp;&nbsp;<?php echo $row["ActivityId"] ?></div>
					
					<?php $sql6 = $db->prepare("SELECT * FROM user WHERE UserId='".$row['CustomerId']."'");$sql6->execute();
						$sql6->setFetchMode(PDO::FETCH_ASSOC);
						while ($row6 = $sql6->fetch()) {  ?>
					<div class="form-group"><label>เลขบัตรประจำตัวประชาชน:</label>&nbsp;&nbsp;<?php echo $row6["CitizenId"] ?><br>

					<label>ผู้ขอใช้บริการ:</label>&nbsp;&nbsp;<?php echo $row6["PreName"].$row6["FirstName"]." ".$row6["LastName"];
							} ?></div>
												
					<div class="panel panel-default">
      				  <div class="panel-body">
					    <div class="form-group">
					      <div class="col-md-4" style="padding-left: 0"><label>วันเวลาที่เริ่ม:</label></div>
					      <div class="col-md-8" style="padding-right: 0"><input id="startdate" type="text" class="form-control" name="startdate" value="<?php echo date_format(date_create($row["StartDate"]), 'Y-m-d H:i') ?>"> </div>
					    </div>

					    <div class="form-group" style="padding-top: 28px">
					      <div class="col-md-4" style="padding-left: 0"><label>วันเวลาสิ้นสุด:</label></div>
					      <div class="col-md-8" style="padding-right: 0"><input id="enddate" type="text" class="form-control" name="enddate" value="<?php echo date_format(date_create($row["EndDate"]), 'Y-m-d H:i') ?>"> </div>
					    </div>

					    <div class="form-group" style="padding-top: 28px">
					      <div class="col-md-4" style="padding-left: 0"><label>ห้องที่ให้บริการ:</label></div>
					      <div class="col-md-8" style="padding-right: 0"><select required class="form-control" name="roomid" id="roomid">
					      <option value="">เลือกห้องที่ให้บริการ..</option>
							<?php
								$sql1 = $db->prepare("SELECT RoomId,RoomName FROM room WHERE RoomId='".$row["RoomId"]."' OR Status='active'");
								$sql1->execute();
								$sql1->setFetchMode(PDO::FETCH_ASSOC);
								while ($row1 = $sql1->fetch()) { ?>
								<option value="<?=$row1['RoomId'] ?>" <?=($row1["RoomId"]==$row["RoomId"]? "selected" : "" )?> ><?php echo $row1["RoomName"]?></option>									
							<?php } ?>  
						</select></div>					
					    </div>
				
				<input id="idactivity" type="hidden" class="form-control" value="<?=$_GET['Id']?>">

					    <div class="form-group" style="padding-top: 28px">
					      <div class="col-md-9" style="padding-left: 0; padding-top: 5px" class="searchresult" id="searchresult"></div>
					      <div class="col-md-3" style="text-align: right; padding: 0"><button id="retrieverent" type="button" class="btn btn-primary btn-sm">ตรวจสอบ</button></div>
					    </div>					    
					   </div>
					  </div> 

					<div class="panel panel-default">
      				  <div class="panel-body">					    
					    <div class="form-group">
					      <label>ชื่อโครงการ:</label>
					      <input type="text" class="form-control" name="activityname" 
					      value="<?php echo $row["ActivityName"] ?>">
					    </div>
					    
						<div class="form-group">
					       <label>ประเภทการเช่า:</label>
					      <select required class="form-control" name="activitytypeid">
					      <option value="">เลือกประเภทการเช่า..</option>
							<?php
								$sql2 = $db->prepare("SELECT TypeId,TypeName FROM activitytype");
								$sql2->execute();
								$sql2->setFetchMode(PDO::FETCH_ASSOC);
								while ($row2 = $sql2->fetch()) { ?>
								<option value="<?=$row2['TypeId'] ?>" <?=($row2["TypeId"]==$row["ActivityTypeId"]? "selected" : "" )?> ><?php echo $row2["TypeName"]?></option>
							<?php } ?>  
						</select>
					    </div>
					    
					      <label>รายละเอียดการเช่า:</label>					    
					      <textarea class="form-control" name="details" rows="4"><?php echo $row["Details"] ?></textarea>
					    </div>
					 </div>
				</div>
					
			
					<div class="col-md-5" style="margin-left: 30px">									  
				<div class="panel panel-default">
      				  <div class="panel-body">
					<div class="form-group">
					      <div class="col-md-8" style="padding-left: 0"><label>อาหารว่าง: &nbsp;</label>	<label class="radio-inline"><input required id="isbreak" type="radio" name="isbreak" <?=($row["IsBreak"]=="nothave")? 'checked' : ''; ?> value="nothave">ไม่มี</label>		
						  <label class="radio-inline"><input id="isbreak1" type="radio" name="isbreak" <?=($row["IsBreak"]=="have")? 'checked' : ''; ?> value="have">มี งบประมาณ</label></div> <div class="col-md-3" style="padding-left: 0">
					     	 	<input required id="feebreak" type="number" class="form-control" name="feebreak" value="<?php echo $row["FeeBreak"] ?>">
					     	</div><label>บาท</label>	
					     	</div>	

					     	<div class="form-group">
					      <div class="col-md-8" style="padding-left: 0"><label>อาหารเที่ยง: &nbsp;</label>	<label class="radio-inline"><input required id="islunch" type="radio" name="islunch" <?=($row["IsLunch"]=="nothave")? 'checked' : ''; ?> value="nothave">ไม่มี</label>		
						  <label class="radio-inline"><input id="islunch1" type="radio" name="islunch" <?=($row["IsLunch"]=="have")? 'checked' : ''; ?> value="have">มี งบประมาณ</label></div> <div class="col-md-3" style="padding-left: 0">
					     	 	<input required id="feelunch" type="number" class="form-control" name="feelunch" value="<?php echo $row["FeeLunch"] ?>">
					     	</div><label>บาท</label>	
					     	</div>
				
						<div class="form-group">
						<div class="col-md-4" style="padding-left: 0"><label>ค่าห้องที่ใช้บริการ:</label></div>
					     	<div class="col-md-7" style="padding-left: 0">
					     	 	<input type="number" class="form-control" name="feeroom" value="<?php echo $row["FeeRoom"] ?>">
					     	</div>
					     	<label>บาท</label>
 						</div>

 						<div class="form-group">
						<div class="col-md-4" style="padding-left: 0"><label>ค่าใช้จ่ายอื่นๆ:</label></div>
					     	<div class="col-md-7" style="padding-left: 0">
					     	 	<input type="number" class="form-control" name="feeothers" value="<?php echo $row["FeeOthers"] ?>">
					     	</div>
					     	<label>บาท</label>
 						</div></div></div>
					
					<div class="panel panel-default">
      				  <div class="panel-body">
						<div class="form-group">
					      <label>เจ้าหน้าที่โครงการ:</label>
					      <select required class="form-control" name="adminid">
					      <option value="">เลือกเจ้าหน้าที่โครงการ..</option>
							<?php
								$sql3 = $db->prepare("SELECT UserId,PreName,FirstName,LastName FROM user WHERE StaffLevel='admin'");
								$sql3->execute();
								$sql3->setFetchMode(PDO::FETCH_ASSOC);

							if($row["AdminId"]==NULL) {
								while ($row3 = $sql3->fetch()) { ?>
								<option value="<?=$row3['UserId'] ?>" <?=($row3["UserId"]==$_SESSION['UserId']? "selected" : "" )?> ><?php echo $row3["PreName"].$row3["FirstName"]." ".$row3["LastName"] ?></option>
							<?php }} else { 
								while ($row3 = $sql3->fetch()) { ?>
								<option value="<?=$row3['UserId'] ?>" <?=($row3["UserId"]==$row["AdminId"]? "selected" : "" )?> ><?php echo $row3["PreName"].$row3["FirstName"]." ".$row3["LastName"] ?></option>
							<?php }} ?>  
						</select>
					    </div>

					    <div class="form-group">
					      <label>เจ้าหน้าที่ห้องปฏิบัติการ:</label>
					      <select required class="form-control" name="labid">
					      <option value="">เลือกเจ้าหน้าที่ห้องปฏิบัติการ..</option>
							<?php
								$sql4 = $db->prepare("SELECT UserId,PreName,FirstName,LastName FROM user WHERE StaffLevel='lab'");
								$sql4->execute();
								$sql4->setFetchMode(PDO::FETCH_ASSOC);
								while ($row4 = $sql4->fetch()) { ?>
									<option value="<?=$row4['UserId'] ?>" <?=($row4["UserId"]==$row["LabId"]? "selected" : "" )?> ><?php echo $row4["PreName"].$row4["FirstName"]." ".$row4["LastName"] ?></option>
							<?php } ?>  
						</select>
					    </div>

					    <div class="form-group">
					      <label>แม่บ้าน:</label>
					      <select required class="form-control" name="maidid">
					      <option value="">เลือกแม่บ้าน..</option>
							<?php
								$sql5 = $db->prepare("SELECT UserId,PreName,FirstName,LastName FROM user WHERE StaffLevel='maid'");
								$sql5->execute();
								$sql5->setFetchMode(PDO::FETCH_ASSOC);
								while ($row5 = $sql5->fetch()) { ?>
									<option value="<?=$row5['UserId'] ?>" <?=($row5["UserId"]==$row["MaidId"]? "selected" : "" )?> ><?php echo $row5["PreName"].$row5["FirstName"]." ".$row5["LastName"] ?></option>
							<?php } ?>  
						</select>
					    </div></div></div>
						
					<div class="panel panel-default">
      				  <div class="panel-body">
					      <label>สถานะการเช่า:</label>
					      <select class="form-control" name="rentstatus" id="rentstatus">
						<?php if(($row["RentStatus"]=="reserve")||($row["RentStatus"]=="cancel")||
								($_GET['ctrl']=="reserve")||($_GET['ctrl']=="cancel")) { ?>
					    		<option value="reserve" <?=("reserve"==$row["RentStatus"]? "selected" : "" )?>>จอง</option> <?php } ?>
					    <?php if(($row["RentStatus"]=="reserve")||($row["RentStatus"]=="wconfirm")
					    ||($row["RentStatus"]=="cancel")||($_GET['ctrl']=="reserve")||($_GET['ctrl']=="wconfirm")||($_GET['ctrl']=="cancel")) { ?>
								<option value="wconfirm"  <?=("wconfirm"==$row["RentStatus"]? "selected" : "" )?>>รอยืนยัน</option> <?php } ?>
								<option value="confirm"  <?=("confirm"==$row["RentStatus"]? "selected" : "" )?>>ยืนยัน</option>	
								<option value="cancel"  <?=("cancel"==$row["RentStatus"]? "selected" : "" )?>>ยกเลิก</option>	
						  </select>
					    </div></div>

					    <input type="hidden" class="form-control" name="stdateorg" value="<?php echo date_format(date_create($row["StartDate"]), 'Y-m-d H:i') ?>">
					    <input type="hidden" class="form-control" name="endateorg" value="<?php echo date_format(date_create($row["EndDate"]), 'Y-m-d H:i') ?>">
					    <input type="hidden" class="form-control" name="roomidorg" value="<?php echo $row["RoomId"] ?>">
					    <input type="hidden" class="form-control" name="rentatusorg" value="<?php echo $row["RentStatus"] ?>">
					   <?php } ?>

					    <div style="text-align:center">
					    <input type="hidden" class="form-control" name="Id" value="<?php echo $_GET['Id']; ?>">
					    <input type="hidden" class="form-control" name="ctrl" value="<?php echo $_GET['ctrl']; ?>">
					   	  <button type="submit" class="btn btn-success" id="btnsubmit">บันทึก</button> <a href="<?=$_GET['ctrl']?>.php" class="btn btn-danger">ยกเลิก</a>
						</div>

					  </div>    							    					   				
					</div>	
				</form>				
				</div>	  
			
												
        <?php include "../include/js.php"; ?>  
         <script src="../js/jquery.datetimepicker.full.min.js"></script> 
         <script>  
         $(document).ready(function () { 
         	// var rentstatus = $("#rentstatus").val(); 
         	// if(rentstatus=="confirm") {       	
         	//    $("#btnsubmit").prop('disabled', false); }
         	// else { $("#btnsubmit").prop('disabled', true); }

         	if ($('#isbreak').is(':checked') == true){
	       		 $('#feebreak').val('').prop('disabled', true);}
	        else if ($('#isbreak1').is(':checked') == true){ 
	        		  $('#feebreak').prop('disabled', false); }

	        if ($('#islunch').is(':checked') == true){
	       		 $('#feelunch').val('').prop('disabled', true);}
	        else if ($('#islunch1').is(':checked') == true){ 
	        		  $('#feelunch').prop('disabled', false); }

	   	 $("#retrieverent").prop('disabled', true);

         	var startdate = $("#startdate").val();
         	var enddate = $("#enddate").val();
			var roomid = $("#roomid").val();
         	$('#startdate,#enddate,#roomid').change(function() {
       	 		if(($('#startdate').val() == startdate) &&
       	 		   ($('#enddate').val() == enddate) &&
       	 		   ($('#roomid').val() == roomid)) {
           			  $('#retrieverent').prop('disabled', true); 
           			  $("#btnsubmit").prop('disabled', false);
           		} 
           		else if(($('#startdate').val() == '') ||
       	 		   ($('#enddate').val() == '') ||
       	 		   ($('#roomid').val() == '')) {
           			  $('#retrieverent').prop('disabled', true); 
           			  $("#btnsubmit").prop('disabled', true);
           		}
           		else {
        			  $('#retrieverent').prop('disabled', false);
        			  $("#btnsubmit").prop('disabled', true);
        		}
     		}); 

     		$('#startdate').keyup(function () {
       			$('#startdate').val('');      			
        		alert('กรุณาเลือกข้อมูลวันเวลาจากปฏิทินค่ะ');
   			});

   			$('#enddate').keyup(function () {
       			$('#enddate').val('');      			
        		alert('กรุณาเลือกข้อมูลวันเวลาจากปฏิทินค่ะ');
   			});    	
         });
                 	
         $(document).ready(function () {         			
		  	$("#retrieverent").click(function(){		  			
				var startdate = $("#startdate").val();
				var enddate = $("#enddate").val();
				var roomid = $("#roomid").val();
				var idactivity = $("#idactivity").val();

				if(startdate==""||enddate==""||roomid=="") {	
					response="<span style=\"color:red\">* กรุณากรอกข้อมูลให้ครบถ้วน</span>";
					$("#searchresult").html(response);
					$("#btnsubmit").prop('disabled', true);	
				}
				else {					
					$.post("retrieverent.php",{ 							
						startdate:startdate,
						enddate:enddate,
						roomid:roomid, 
						idactivity:idactivity 
					},
				function(response){ 
					if(response == false) {
						response="<span class='label label-danger'>ไม่พร้อมให้บริการ</span> กรุณาตรวจสอบอีกครั้ง !";
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