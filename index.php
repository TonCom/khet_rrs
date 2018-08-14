<?php


	session_start();
	
	require "admin/include/connect.php";

	$activepage = "index";			
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>หน้าแรก</title>
        <?php include "admin/include/css.php"; ?>				
    </head>
    <style>     
   	.center-block {
   		float: none !important
   	}    
    </style>
    <body>
		<?php include "customer/include/banner.php"; ?>
		<?php include "customer/include/menu.php"; ?>

		<div class="container">
			<!-- <h1 style="text-align: center">ติดต่อเรา</h1> --><br><br>
				
			<div class="row">				
				<div class="col-md-9 center-block">
				
				<?php if(isset($_GET["action"])&&($_GET["action"]==1)) { ?>
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<div style="padding-left: 16px"><b>แจ้งเตือน:</b> ท่านได้สมัครสมาชิกเรียบร้อยแล้ว ! ต้องการเข้าสู่ระบบ <a href="login.php">คลิกที่นี่</a></div>
					</div>
					<?php } ?>

				<!--<div class="panel panel-default">
      				<div class="panel-body">
      				  <div class="row">
						<div class="col-md-12" style="font-size: 17px; padding: 15px 50px 15px 50px">
						<div style="text-align: center; line-height: 30px">
							กลุ่มงานบริการวิชาการ ศูนย์คอมพิวเตอร์ มหาวิทยาลัยสงขลานครินทร์ 
							<br><b>มีการให้บริการเช่าห้องอบรมเชิงปฏิบัติการคอมพิวเตอร์ ห้องสัมมนา</b> 
							<br>แก่หน่วยงานภายนอก หน่วยงานภายในและนักศึกษามหาวิทยาลัยสงขลานครินทร์ 
							<br>มีพื้นที่กว้างขวาง หรูหรา ทันสมัย พร้อมอุปกรณ์การนำเสนอครบครัน 
						</div>
				<br> -->
					<!--<div style="font-size: 19px; text-align: center">ห้องที่พร้อมให้บริการจำนวน 
	    <?php $sql = $db->prepare("SELECT COUNT(RoomId) AS countroom FROM room WHERE Status='active'");
              $sql->execute();
              $sql->setFetchMode(PDO::FETCH_ASSOC); ?>
                    <span class='label label-success' style="font-size: 19px; padding:0 12px 0 12px">
        <?php if ($row = $sql->fetch()) { 
         if ($row["countroom"]!=0) { echo $row["countroom"]; } else { echo "0"; }} ?></span> ห้อง</div> -->
						<hr>
						<div class='col-sm-12' align=" center">
							<?php include "calend/samples/calendar/select.php"; ?>
						</div>
						<hr>
<input type="submit" name="Submit2" value="+">
						<!--<b>ข้อตกลงการใช้บริการ:</b>		
						<ul class="fa-ul">
  					<li style="line-height: 30px"><i style="font-size:12px; padding-top: 6px" class="fa-li fa fa-circle"></i>
  					<b>การชำระเงิน</b> ชำระก่อนการใช้บริการไม่น้อยกว่า 5 วัน
  					<br><b>วิธีที่ 1 :</b> ชำระเงินโดยเงินสด
  					<br><b>วิธีที่ 2 :</b> ชำระเงินโดยโอนเงินเข้าบัญชีออมทรัพย์ "ศูนย์คอมพิวเตอร์ 2" ธนาคารไทยพาณิชย์ จำกัด 
  					<br><div style="padding-left: 63px">
  					สาขา มหาวิทยาลัยสงขลานครินทร์ เลขที่บัญชี 565-2-45084-7
  					<br>และส่งสำเนาใบโอนเงินทาง โทรสาร 074-282070, 074-282111 
  					<br>(กรุณาระบุชื่อหน่วยงานในสำเนาใบโอนเงินด้วย)</div></li>

  					<li style="line-height: 30px"><i style="font-size:12px; padding-top: 6px" class="fa-li fa fa-circle"></i>
					<b>การใช้งานซอฟต์แวร์เฉพาะ ที่นอกเหนือจากซอฟต์แวร์หลักของห้องปฏิบัติการ</b>
					<br>ผู้ขอใช้บริการจัดหาซอฟต์แวร์ และติดตั้งซอฟต์แวร์เอง 
					<br>หากให้ศูนย์ฯ ดำเนินการติดตั้ง มีค่าใช้จ่าย 1,000 บาท ต่อ 1 โปรแกรม ต่อ 25 เครื่อง</li>

					<li style="line-height: 30px"><i style="font-size:12px; padding-top: 6px" class="fa-li fa fa-circle"></i>
					<b>กรณีมีความต้องการติดตั้งอุปกรณ์เพิ่มเติม</b> 
					<br>ต้องแจ้งให้ศูนย์ ฯ ทราบล่วงหน้าก่อนใช้บริการอย่างน้อย 1 สัปดาห์ 
					<br>มิฉะนั้นศูนย์ฯ ขอสงวนสิทธิ์ไม่รับผิดชอบในความไม่พร้อมที่จะเกิดขึ้น</li>
						</ul>											
 						</div>
 					  </div>	
		 			</div>
				</div>									
				</div>	
			</div>											  			
		</div>-->
		
		<?php include "customer/include/footer.php"; ?>
		
        <?php include "admin/include/js.php"; ?>                           
    </body>
</html>