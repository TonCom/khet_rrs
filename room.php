<?php

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	session_start();
	
	require "admin/include/connect.php";
		
	$activepage = "room";

	function settypes($type){
		if($type=="labroom") { 
		 	return "ห้องอบรมคอมพิวเตอร์"; }
		else if($type=="seminaroom") { 
			return "ห้องสัมมนา"; }		
	}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ข้อมูลห้องที่ให้บริการ</title>      
		<?php include "admin/include/css.php"; ?>		
    </head>
    <style>     
   	.center-block {
   		float: none !important
   	}    
   	th,td{
   		text-align: center;
    }
    </style>
    <body>
		<?php include "customer/include/banner.php"; ?>
		<?php include "customer/include/menu.php"; ?>

		<div class="container">	
		<br><br>		
				<div class="row">
				<div class="col-md-12 center-block">

				<div class="panel panel-default">
      				<div class="panel-body">				
					<div class="col-md-12 center-block" style="padding-top: 10px; padding-bottom: 15px">	
				
      		<h3><b>ห้องที่ให้บริการ</b></h3><p style="font-size: 17px">มีทั้งหมดจำนวน 6 ห้อง แบ่งออกเป็น 2 ประเภท คือ <b>ห้องอบรมเชิงปฏิบัติการคอมพิวเตอร์</b> จำนวน 5 ห้อง และ<b>ห้องสัมมนา</b> จำนวน 1 ห้อง</p>	

			<table class="table table-bordered table-hover table-striped">
			<thead>
		      <tr bgcolor="#CCCCCC">		      	
		        <th rowspan="2">ชื่อห้อง</th>
		        <th rowspan="2">ความจุ</th>		        		              
		        <th rowspan="2">ประเภทห้อง</th>
				<th rowspan="1" colspan="2">อัตราค่าบริการ</th>			
		        <th rowspan="2">รายละเอียดห้อง</th>
		        <th rowspan="2">สถานะห้อง</th>		        
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
				        <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
				        </div>
				      </div>      
				    </div>
				  </div>

				<?php
				echo "</td>";
                echo "<td>" .($row["Status"]=="active"? "<span class='label label-success'>พร้อมให้บริการ</span>" : "<span class='label label-danger'>ไม่พร้อมให้บริการ</span>" )."</td>";
                echo "</tr>";
			}
		?>
			</tbody>
		</table>
		<hr style="margin-top: 25px">
			<h3><b>ภาพบรรยากาศห้องที่ให้บริการ</b></h3>
			<div align=" center">
				<a href="images/101.jpg" target="_blank"><img src="images/101.jpg" width="520" height="347"></a>&nbsp;&nbsp;&nbsp;<a href="images/106.jpg" target="_blank"><img src="images/106.jpg" width="520" height="347"></a>
				
					<div style="padding-top: 14px">					
				<a href="images/102(1).jpg" target="_blank"><img src="images/102(1).jpg" width="520" height="347"></a>&nbsp;&nbsp;&nbsp;<a href="images/102(2).jpg" target="_blank"><img src="images/102(2).jpg" width="520" height="347"></a>
					</div>
			</div>
		<hr style="margin-top: 25px">
			<div style="font-size: 17px; padding-left: 30px">
			 <div style="padding-bottom: 10px">
				<b>หมายเหตุ:</b>
				<ul class="fa-ul">
  					<li style="line-height: 30px"><i style="font-size:12px; padding-top: 6px" class="fa-li fa fa-circle"></i>
  					กรณีใช้บริการครึ่งวัน คิดอัตราค่าบริการเช่าห้อง 60% จากอัตราค่าบริการเช่าห้องปกติ</li>
  					<li style="line-height: 30px"><i style="font-size:12px; padding-top: 6px" class="fa-li fa fa-circle"></i>
  					กรณีใช้บริการนอกเวลาราชการ ค่าเจ้าหน้าที่ปฏิบัติงานนอกเวลาราชการ 1,260 บาท/วัน (จนท.ประสานงาน, จนท.โสตฯ, แม่บ้าน)</li>
  				</ul>
  			 </div>

  				<b>อัตราค่าบริการอื่นๆ:</b>
				<ul class="fa-ul">
  					<li style="line-height: 30px"><i style="font-size:12px; padding-top: 6px" class="fa-li fa fa-circle"></i>
  					ค่าอาหารว่าง และอาหารกลางวัน</li>
  					<li style="line-height: 30px"><i style="font-size:12px; padding-top: 6px" class="fa-li fa fa-circle"></i>
  					ค่าบริการระบบถ่ายทอดภาพและเสียง หน่วยงานภายใน ม.อ. 1,500 บาท/จุด, หน่วยงานอื่น 3,000 บาท/จุด
					<br>(กรณีใช้บริการนอกเวลาราชการ มีค่าตอบแทนเจ้าหน้าที่เครือข่าย 420 บาท/วัน)</li>
					<li style="line-height: 30px"><i style="font-size:12px; padding-top: 6px" class="fa-li fa fa-circle"></i>
  					ค่าเช่า Printer (รวมกระดาษ A4 จำนวน 1 รีม) 1,000 บาท/เครื่อง/วัน</li>
  					<li style="line-height: 30px"><i style="font-size:12px; padding-top: 6px" class="fa-li fa fa-circle"></i>
  					ค่าเช่าคอมพิวเตอร์นอกสถานที่ Notebook, Computer PC อัตรา 300 บาท/เครื่อง/วัน (ผู้ขอใช้บริการมารับและคืนเครื่องด้วยตนเอง)</li>
  					<li style="line-height: 30px"><i style="font-size:12px; padding-top: 6px" class="fa-li fa fa-circle"></i>
  					ค่าบริการที่จอดรถยนต์ (จอดที่ศูนย์กีฬา) 30 บาท/คัน/วัน</li>
  				</ul>
			</div>
		</div>	
			</div>											
				</div>	
			</div>											  
		</div>
		</div>
		
		<?php include "customer/include/footer.php"; ?>
		
        <?php include "admin/include/js.php"; ?>                           
    </body>
</html>