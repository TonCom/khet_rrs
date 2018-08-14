<?php	

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

	session_start();
	
	require "admin/include/connect.php";
	
	$activepage = "contact";			
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ติดต่อเรา</title>
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
				<div class="panel panel-default">
      				<div class="panel-body">     				  
						<div class="col-md-12" style="font-size: 17px; padding-top: 15px; padding-bottom: 15px; text-align: center">
						<p style="line-height: 30px;">กลุ่มงานบริการวิชาการ ศูนย์คอมพิวเตอร์ มหาวิทยาลัยสงขลานครินทร์  
						<br><b>โทรศัพท์</b> 0-7428-2116 <b>โทรสาร</b> 0-7428-2070
						<br><b>วันเวลาทำการ:</b> วันจันทร์-ศุกร์ (เว้นวันหยุดราชการ) เวลา 08:30-16:30 น. 
						<br><i style="font-size:22px" class="fa fa-facebook-official" aria-hidden="true"></i> <a href="https://www.facebook.com/ccserve" target="_blank">กลุ่มงานบริการวิชาการ ศูนย์คอมพิวเตอร์ ม.อ.</a> 
						<br><i style="font-size:23px" class="fa fa-map-marker" aria-hidden="true"></i> <a href="https://www.google.co.th/maps/place/ศูนย์คอมพิวเตอร์+มหาวิทยาลัยสงขลานครินทร์+(Computer+Center)/@7.0091004,100.4983552,16.25z/data=!4m5!3m4!1s0x304d29a51a0f6199:0x321018be7a59ffe5!8m2!3d7.0089158!4d100.4979367?hl=th" target="_blank">ที่ตั้ง ศูนย์คอมพิวเตอร์ ม.อ.</a></p>
						<hr>
						<a href="images/mapcc.jpg" target="_blank"><img src="images/mapcc.jpg" width="610" height="482"></a>														</div>					  	
		 			</div>
				</div>									
				</div>	
			</div>											  			
		</div>
		
		<?php include "customer/include/footer.php"; ?>
		
        <?php include "admin/include/js.php"; ?>                           
    </body>
</html>