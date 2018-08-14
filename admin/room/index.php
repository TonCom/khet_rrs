<?php

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	session_start();

	if(!isset($_SESSION['UserId'])||$_SESSION['UserTypeId']!=0) {
		
		header('Location: ../login.php?error=0'); 
	} else {        
        if(time() > $_SESSION['expire']) {
            session_destroy();
            header('Location: ../login.php?error=4');              
        } else {

	require "../include/connect.php";	
	
	//Set Path
	$isSubfolder = true;
	$activepage = "room";

	function settypes($type){
		if($type=="labroom") { 
		 	return "ห้องอบรมคอมพิวเตอร์"; }
		else if($type=="seminaroom") { 
			return "ห้องสัมมนา"; }		
	}

	function setstatus($id,$status){
		if($status=="active"){ 
			return "<a href='?RoomId=$id&Status=$status&ctrl=editstatus' style='text-decoration: none'>
			<div class='label label-success'>พร้อมให้บริการ</div></a>"; }
		else if($status=="inactive"){ 
			return "<a href='?RoomId=$id&Status=$status&ctrl=editstatus' style='text-decoration: none'><div class='label label-danger'>ไม่พร้อมให้บริการ</div></a>"; }
	}

	function setstatus1($id,$status){
		if($status=="active"){ 
			return "<div class='label label-success'>พร้อมให้บริการ</div>"; }
		else if($status=="inactive"){ 
			return "<div class='label label-danger'>ไม่พร้อมให้บริการ</div>"; }
	}

	if (isset($_GET["ctrl"])&&$_GET["ctrl"]=="editstatus") {
	$sql = "UPDATE room SET 
		Status =:Status
		WHERE RoomId = :setID";
	$setid = $_GET['RoomId'];
	settype($setid, "integer");
	$stmp = $db->prepare($sql);
		if($_GET["Status"]=="active") {
			$stmp->bindValue("Status" , 'inactive'); }
		else if($_GET["Status"]=="inactive") {
			$stmp->bindValue("Status" , 'active'); }
	$stmp->bindValue("setID" , $setid);
	$stmp->execute();
	}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ข้อมูลห้องที่ให้บริการ</title>
		<?php include "../include/css.php"; ?>
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

      			<?php if ($_SESSION['StaffLevel']=="sadmin") { ?>
							<a href="formaddroom.php" class="btn btn-primary">เพิ่มข้อมูลห้องที่ให้บริการ</a><br><br>
				<?php } else { 
					echo "<h3 style='padding-bottom: 5px'><b>ข้อมูลห้องที่ให้บริการ</b></h3>";
				 } ?>

			<table class="table table-bordered table-hover table-striped">
			<thead>
		      <tr bgcolor="#CCCCCC">		      	
		        <th rowspan="2">ชื่อห้อง</th>
		        <th rowspan="2">ความจุ</th>		        		              
		        <th rowspan="2">ประเภทห้อง</th>
				<th rowspan="1" colspan="2">อัตราค่าบริการ</th>			
		        <th rowspan="2">รายละเอียดห้อง</th>
		        <th rowspan="2">สถานะห้อง</th>
		    <?php if ($_SESSION['StaffLevel']=="sadmin") { ?>
		        <th rowspan="2"></th>
		    <?php } ?>
		       		<tr bgcolor="#CCCCCC"> 
						<th rowspan="1" colspan="1">หน่วยงานภายใน</th>
						<th rowspan="1" colspan="1">หน่วยงานภายนอก</th>				
		        	</tr>
		      </tr>		        		   		        
		    </thead>
			<tbody>
		<?php
			$sql = $db->prepare("SELECT RoomId,RoomName,Unit,Details,Type,CostInt,CostExt,Status FROM room");
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			while ($row = $sql->fetch()) {
				echo "<tr bgcolor='#FFFFFF'>";				
			    echo "<td>" .$row["RoomName"]."</td>";
			    echo "<td>" .$row["Unit"]." คน/ห้อง</td>";               
                echo "<td>" .settypes($row["Type"])."</td>";  
                echo "<td>" .number_format($row["CostInt"])." บาท/วัน</td>";
                echo "<td>" .number_format($row["CostExt"])." บาท/วัน</td>";
                echo "<td>"; ?>
                <a href="#" data-toggle="modal" data-target="#<?php echo $row["RoomId"] ?>">ดูรายละเอียด</a>

                <div class="modal fade" id="<?php echo $row["RoomId"] ?>" role="dialog">
				    <div class="modal-dialog">
				      <div class="modal-content">
				        <div class="modal-header">
				          <button type="button" class="close" data-dismiss="modal">&times;</button>
				          <h4 class="modal-title"><?php echo $row["RoomName"] ?></h4>
				        </div>
				        <div class="modal-body">
					        <div class="row">
					        	<div class="col-md-12" style="font-size: 16px; margin-left: 10px; text-align: left">
									 <label>รายละเอียดห้อง:</label><br> <textarea style="padding: 15px" readonly="readonly" rows="19" cols="63"><?php echo ($row["Details"]!=NULL? $row["Details"] : "- ไม่พบการกรอกข้อมูลรายละเอียดห้อง -") ?></textarea>
								</div>
							</div>
				        </div>
				        <div class="modal-footer">	
				        <a href='formeditroom.php?Id=<?=$row['RoomId']?>' class="btn btn-primary">แก้ไขข้อมูล</a>			     			        	
				          <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
				        </div>
				      </div>      
				    </div>
				  </div>

				<?php
				echo "</td>";
		if ($_SESSION['StaffLevel']=="sadmin") {  
                echo "<td>" .setstatus($row["RoomId"],$row["Status"])."</td>"; 
        } else { echo "<td>" .setstatus1($row["RoomId"],$row["Status"])."</td>"; } 
        if ($_SESSION['StaffLevel']=="sadmin") {             
				echo "<td>";
            	   echo "<a href='formeditroom.php?Id=".$row['RoomId']."' data-toggle='tooltip' title='แก้ไข'><i style='font-size:25px' class='fa fa-pencil' aria-hidden='true'></i></a>";   

            $sql1 = $db->prepare("SELECT COUNT(ActivityId) AS countact FROM activity 
            	WHERE RoomId='".$row['RoomId']."'");
			$sql1->execute();
			$sql1->setFetchMode(PDO::FETCH_ASSOC);	 
			if ($row1 = $sql1->fetch()) {              
            	   echo " <a href='deleteroom.php?Id=".$row['RoomId']."' class='".
            	   ($row1["countact"]==0? "" : "disabled")."' onclick=\"return confirm('คุณต้องการลบข้อมูลห้องเช่านี้หรือไม่ ?');\" data-toggle='tooltip' title='ลบ'><i style='font-size:25px; ".($row1["countact"]==0? "" : "opacity: 0.5")."' class='fa fa-trash-o text-danger' aria-hidden='true'></i></a>"; }
                echo "</td>";
        }
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
        <script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();   
		});
		</script>      
    </body>
</html>
<?php } } ?>