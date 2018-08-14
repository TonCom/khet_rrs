<?php 

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

?>

<style>     
 #bg { background: url("../logoad.png") no-repeat bottom left; }    
</style>

<div class="container-fluid" style="border-bottom:1px solid #e7e7e7;">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="bg" style="background-color: #27539c">
		<div class="col-xs-7 col-sm-10 col-md-9 col-lg-9" style="height: 123px">
			<div style="padding-top:15px; padding-left: 60px; font-size: 30px; font-weight: bold;"><img src="../../images/psu1.png" width="92" height="107">&nbsp;&nbsp;ระบบบริหารจัดการการให้บริการห้องอบรม ศูนย์คอมพิวเตอร์
			</div>
		</div>	

		<?php  
		$sql = $db->prepare("SELECT * FROM user WHERE UserId='".$_SESSION['UserId']."'");
              $sql->execute();
              $sql->setFetchMode(PDO::FETCH_ASSOC);                   
        if ($row = $sql->fetch()) { $name=$row['PreName'].$row['FirstName']." ".$row['LastName']; } ?>	         
         <div class="col-xs-5 col-sm-2 col-md-3 col-lg-3" style="color: white; font-size:16px; line-height: 27px; padding-top: 33px; text-align: right; padding-right: 70px"> 
               <img src="../../images/user.png" width="24" height="24"> &nbsp;<?=$name?><br><a href="../../logout.php?ctrl=admin" style="padding: 3px" class="btn btn-sm btn-danger">ออกจากระบบ</a>  	
         </div>  
		</div>
	</div>
</div>