<?php


if(isset($isSubfolder) && $isSubfolder == false) {
 		$_path = "../../";
 	}
else{
 		$_path = "";
 	}

	date_default_timezone_set('Asia/Bangkok');
 	function ThDate() {
		$ThDay = array ( "อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัส", "ศุกร์", "เสาร์" );
		$ThMonth = array ( "มกรามก", "กุมภาพันธ์", "มีนาคม", "เมษายน","พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม","กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม" );
 
		$week = date( "w" ); 
		$months = date( "m" )-1; 
		$day = date( "j" ); 
		$years = date( "Y" )+543; 
		$hour= date("H");
		$minute= date("i");
 
		return "วัน$ThDay[$week] ที่ $day $ThMonth[$months] พ.ศ.$years เวลา $hour:$minute น.";
	}	
 ?>

<style>     
 #bg { background: url("<?=$_path?>images/logo.png") no-repeat; }    
</style>

<div class="container-fluid" style="border-bottom:1px solid #292b2c;">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="bg" style="background-color: #27539c">
		<div class="col-xs-7 col-sm-10 col-md-9 col-lg-9" style="height: 112px">
			<div style="padding-top:2px; padding-left: 80px; font-size: 27px; font-weight: bold;"><img src="<?=$_path?>images/psu1.png" width="83" height="98">&nbsp;&nbsp;ระบบบริหารจัดการการให้บริการห้องอบรม ศูนย์คอมพิวเตอร์
			</div>
		</div>	

	<?php if(!isset($_SESSION['UserId'])||$_SESSION['UserTypeId']==0) {        
			// session_destroy(); ?>
			
		 <div class="col-xs-5 col-sm-2 col-md-3 col-lg-3" style="color: white; padding-top: 12px; text-align: right; font-size: 12px"> 
			<form role="form" class="col-xs-9" action="validation.php" method="post">
				<div style="padding-bottom: 3px">							
                        <input required type="email" class="form-control input-sm" name="username" placeholder="บัญชีผู้ใช้งาน">
                    
                        <input required type="password" class="form-control input-sm" name="password" placeholder="รหัสผ่าน"></div> 
                        &nbsp;&nbsp;<a href="register.php" style="color: white; text-decoration: none">สมัครสมาชิก</a> | <a href="recover.php" style="color: white; text-decoration: none">ลืมรหัสผ่าน?</a> &nbsp;&nbsp;&nbsp;&nbsp; <button type="submit" style="padding: 3px" class="btn btn-sm btn-warning">เข้าสู่ระบบ</button>
            </form>
		</div> 

<?php } else if(isset($_SESSION['UserId'])&&$_SESSION['UserTypeId']!=0) { 
		$sql = $db->prepare("SELECT * FROM user WHERE UserId='".$_SESSION['UserId']."'");
              $sql->execute();
              $sql->setFetchMode(PDO::FETCH_ASSOC);                   
        if ($row = $sql->fetch()) { $name=$row['PreName'].$row['FirstName']." ".$row['LastName']; } ?>	         
         <div class="col-xs-5 col-sm-2 col-md-3 col-lg-3" style="color: white; font-size:16px; line-height: 24px; padding-top: 28px; text-align: right; padding-right: 110px"> 
               <img src="<?=$_path?>images/user.png" width="24" height="24"> &nbsp;<?=$name?><br><a href="<?=$_path?>customer/userdetails/index.php" style="padding: 3px" class="btn btn-sm btn-success">ข้อมูลส่วนตัว</a><a href="<?=$_path?>logout.php" style="padding: 3px" class="btn btn-sm btn-danger">ออกจากระบบ</a>  	
         </div> 
 <?php } ?>
		</div>
	</div>
</div>
