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
        <script src="<?=$_path?>js/jquery.min.js"></script>
        <script src="<?=$_path?>js/bootstrap.min.js"></script>
        <script src="<?=$_path?>js/sidebar.js"></script> 
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

			prepare("SELECT COUNT(RoomId) AS countroom FROM room WHERE Status='active'");
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
						
		
		<?php include "customer/include/footer.php"; ?>
		
        <?php include "admin/include/js.php"; ?> 

<script>
	/*
		<!-- khet, 2018-08-28, example class click
		<img src="edit.png" title="edit" class="showEdt" data-evalz="(eyCKeVua/TNF117)" />
		$('.showEdt').each(function () {
		    var $this = $(this);
		    $this.on("click", function () {
		        alert($(this).data('evalz'));
		    });
		});
		.mousedown()
	*/

	//$(document).ready(function () {
      //$('.dhx_save_btn div').click(function (e) {
      //   alert("button save click");
      //});
      $('.dhx_save_btn div').click(function (e) {
         alert("button save click");
      });

      //$('#input_1535428520123').click(function (e) {
      //var inp = document.getElementById("input_1535428520123");
      //inp.click(function(){
      //   alert("input text click");
      //});

      
    function test_save(lll){
      	// alert('fn: test_save');   
      	//var txt_area = $('#textarea_description');   	
      	var txt_area = document.getElementById("textarea_description");      	
        //alert(txt_area.innerHTML);
        //alert(txt_area.value);
        var desc = txt_area.value;
    
    // example code
    /*
        $.ajax({
        	url: 'response_insert.php',
        	type: 'post',
        	dataType: 'json',
        	data: {
        		name: namejs,
        		email: emailjs,
        		password: passjs,
        		gender: selected_gender
        	}
        	success: function(response){
	            console.log(response); //here is response from your php 
	        },
	        error: function(error){ 
	        	console.log(error.responseText);
	        }
	    });
	*/
		$.ajax({
        	url: 'response_insert.php',
        	type: 'post',
        	dataType: 'json',
        	data: {
        		descript: desc
        	},
        	success: function(response){
	            //console.log(response); //here is response from your php 
	            alert(response);
	            //alert('บันทึกข้อมูลสำเร็จ');
	        },
	        error: function(error){ 
	        	//console.log(error.responseText);
	        	alert(error.responseText);
	        	//alert('ไม่สามารถบันทึกข้อมูลได้');
	        }
	    });
    } 

	//});      

</script>                        
    </body>

</html>