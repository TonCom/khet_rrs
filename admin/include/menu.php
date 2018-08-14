<?php 

/**
 * @projectname	 Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

 	require "/connect.php";

 	if(isset($isSubfolder) && $isSubfolder == true) {
 		$_path = "../";
 	}
 	else{
 		$_path = "";
 	}

 ?>
<link rel="stylesheet" type="text/css" href="<?=$_path?>css/sidebar.css"> 

<nav class="navbar navbar-default sidebar" role="navigation">	
    <div class="container-fluid">
		<div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="<?=($activepage == 'home') ? 'active' : '';?>"><a href="<?=$_path?>index.php">หน้าแรก<span style="font-size:20px;" class="pull-right hidden-xs showopacity fa fa-home"></span></a></li>		
		
		<?php if(isset($_SESSION['UserId'])&&$_SESSION['UserTypeId']==0&&$_SESSION['StaffLevel']!="lab"&&$_SESSION['StaffLevel']!="maid") { ?>		
				<li class="<?=($activepage == 'searchrent') ? 'active' : '';?>"><a href="<?=$_path?>searchrent/index.php">สอบถามห้องว่าง<span style="font-size:20px;" class="pull-right hidden-xs showopacity fa fa-search"></span></a></li>
		<?php } ?>

				<li class="<?=($activepage == 'service') ? 'active' : '';?>"><a href="<?=$_path?>service/index.php">ข้อมูลการใช้บริการ<span style="font-size:20px;" class="pull-right hidden-xs showopacity fa fa-database"></span></a></li>
		
		<?php if(isset($_SESSION['UserId'])&&$_SESSION['UserTypeId']==0&&$_SESSION['StaffLevel']!="lab"&&$_SESSION['StaffLevel']!="maid") { ?>
				<li class="<?=($activepage == 'addactivity') ? 'active' : '';?>"><a href="<?=$_path?>adactivity/formaddactivity.php">ขอเช่าห้อง<span style="font-size:20px;" class="pull-right hidden-xs showopacity fa fa-floppy-o"></span></a></li>

				<li class="dropdown <?=($activepage == 'culistallrent' || $activepage == 'cureserve' || $activepage == 'cuwconfirm' || $activepage == 'cuconfirm' || $activepage == 'cucancel') ? 'open' : '';?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">รายการเช่า(ผู้เช่า) <span class="caret"></span><span style="font-size:20px;" class="pull-right hidden-xs showopacity fa fa-bullhorn"></span></a>
					<ul class="dropdown-menu forAnimate" role="menu">
						<li class="<?=($activepage == 'culistallrent') ? 'active' : '';?>"><a href="<?=$_path?>cuactivity/index.php">รายการเช่าทั้งหมด</a></li>	
						<li class="<?=($activepage == 'cureserve') ? 'active' : '';?>"><a href="<?=$_path?>cuactivity/reserve.php">สถานะจอง
					<?php $sql = $db->prepare("SELECT COUNT(ActivityId) AS countacti FROM activity 
               	 	WHERE ReserveBy='cust' AND RentStatus='reserve'");
                    $sql->execute();
                    $sql->setFetchMode(PDO::FETCH_ASSOC);
                    if ($row = $sql->fetch()) { 
                    	if ($row["countacti"]!=0) { ?>
						<span class="badge"><?=$row["countacti"] ?></span> <?php } } ?>
						</a></li>

						<li class="<?=($activepage == 'cuwconfirm') ? 'active' : '';?>"><a href="<?=$_path?>cuactivity/wconfirm.php">สถานะรอยืนยัน
					<?php $sql = $db->prepare("SELECT COUNT(ActivityId) AS countacti FROM activity 
               	 	WHERE ReserveBy='cust' AND RentStatus='wconfirm'");
                    $sql->execute();
                    $sql->setFetchMode(PDO::FETCH_ASSOC);
                    if ($row = $sql->fetch()) { 
                    	if ($row["countacti"]!=0) { ?>
						<span class="badge"><?=$row["countacti"] ?></span> <?php } } ?>
						</a></li>

						<li class="<?=($activepage == 'cuconfirm') ? 'active' : '';?>"><a href="<?=$_path?>cuactivity/confirm.php">สถานะยืนยัน
					<?php $sql = $db->prepare("SELECT COUNT(ActivityId) AS countacti FROM activity 
               	 	WHERE ReserveBy='cust' AND RentStatus='confirm'");
                    $sql->execute();
                    $sql->setFetchMode(PDO::FETCH_ASSOC);
                    if ($row = $sql->fetch()) { 
                    	if ($row["countacti"]!=0) { ?>
						<span class="badge"><?=$row["countacti"] ?></span> <?php } } ?>
						</a></li>

						<li class="<?=($activepage == 'cucancel') ? 'active' : '';?>"><a href="<?=$_path?>cuactivity/cancel.php">สถานะยกเลิก
					<?php $sql = $db->prepare("SELECT COUNT(ActivityId) AS countacti FROM activity 
               	 	WHERE ReserveBy='cust' AND RentStatus='cancel'");
                    $sql->execute();
                    $sql->setFetchMode(PDO::FETCH_ASSOC);
                    if ($row = $sql->fetch()) { 
                    	if ($row["countacti"]!=0) { ?>
						<span class="badge"><?=$row["countacti"] ?></span> <?php } } ?>
						</a></li>

					</ul>
				</li>	

				<li class="dropdown <?=($activepage == 'adlistallrent' || $activepage == 'adreserve' || $activepage == 'adwconfirm' || $activepage == 'adconfirm' || $activepage == 'adcancel' || $activepage == 'activitytype') ? 'open' : '';?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">รายการเช่า(เจ้าหน้าที่)<span class="caret"></span><span style="font-size:20px;" class="pull-right hidden-xs showopacity fa fa-bullhorn"></span></a>
					<ul class="dropdown-menu forAnimate" role="menu">
						<li class="<?=($activepage == 'adlistallrent') ? 'active' : '';?>"><a href="<?=$_path?>adactivity/index.php">รายการเช่าทั้งหมด</a></li>	
						<li class="<?=($activepage == 'adreserve') ? 'active' : '';?>"><a href="<?=$_path?>adactivity/reserve.php">สถานะจอง
					<?php $sql = $db->prepare("SELECT COUNT(ActivityId) AS countacti FROM activity 
               	 	WHERE ReserveBy='admin' AND RentStatus='reserve'");
                    $sql->execute();
                    $sql->setFetchMode(PDO::FETCH_ASSOC);
                    if ($row = $sql->fetch()) { 
                    	if ($row["countacti"]!=0) { ?>
						<span class="badge"><?=$row["countacti"] ?></span> <?php } } ?>
						</a></li>

						<li class="<?=($activepage == 'adwconfirm') ? 'active' : '';?>"><a href="<?=$_path?>adactivity/wconfirm.php">สถานะรอยืนยัน
					<?php $sql = $db->prepare("SELECT COUNT(ActivityId) AS countacti FROM activity 
               	 	WHERE ReserveBy='admin' AND RentStatus='wconfirm'");
                    $sql->execute();
                    $sql->setFetchMode(PDO::FETCH_ASSOC);
                    if ($row = $sql->fetch()) { 
                    	if ($row["countacti"]!=0) { ?>
						<span class="badge"><?=$row["countacti"] ?></span> <?php } } ?>
						</a></li>

						<li class="<?=($activepage == 'adconfirm') ? 'active' : '';?>"><a href="<?=$_path?>adactivity/confirm.php">สถานะยืนยัน
					<?php $sql = $db->prepare("SELECT COUNT(ActivityId) AS countacti FROM activity 
               	 	WHERE ReserveBy='admin' AND RentStatus='confirm'");
                    $sql->execute();
                    $sql->setFetchMode(PDO::FETCH_ASSOC);
                    if ($row = $sql->fetch()) { 
                    	if ($row["countacti"]!=0) { ?>
						<span class="badge"><?=$row["countacti"] ?></span> <?php } } ?>
						</a></li>

						<li class="<?=($activepage == 'adcancel') ? 'active' : '';?>"><a href="<?=$_path?>adactivity/cancel.php">สถานะยกเลิก
					<?php $sql = $db->prepare("SELECT COUNT(ActivityId) AS countacti FROM activity 
               	 	WHERE ReserveBy='admin' AND RentStatus='cancel'");
                    $sql->execute();
                    $sql->setFetchMode(PDO::FETCH_ASSOC);
                    if ($row = $sql->fetch()) { 
                    	if ($row["countacti"]!=0) { ?>
						<span class="badge"><?=$row["countacti"] ?></span> <?php } } ?>
						</a></li>

						<li class="<?=($activepage == 'activitytype') ? 'active' : '';?>"><a href="<?=$_path?>activitytype/index.php">ข้อมูลประเภทการเช่า</a></li>
					</ul>
				</li>	
				
				<li class="dropdown <?=($activepage == 'listallcustomer' || $activepage == 'custpending' || $activepage == 'customertype') ? 'open' : '';?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">ข้อมูลผู้เช่า <span class="caret"></span><span style="font-size:20px;" class="pull-right hidden-xs showopacity fa fa-user-circle-o"></span></a>
					<ul class="dropdown-menu forAnimate" role="menu">
						<li class="<?=($activepage == 'listallcustomer') ? 'active' : '';?>"><a href="<?=$_path?>customer/index.php">ข้อมูลผู้เช่าทั้งหมด</a></li>	
						<li class="<?=($activepage == 'custpending') ? 'active' : '';?>"><a href="<?=$_path?>customer/customerpending.php">ผู้เช่ารออนุมัติ 
						
			  <?php $sql = $db->prepare("SELECT COUNT(UserId) AS countactiveno FROM user 
               	 		WHERE UserTypeId!=0 AND Active='no'");
                    $sql->execute();
                    $sql->setFetchMode(PDO::FETCH_ASSOC);
                    if ($row = $sql->fetch()) { 
                    	if ($row["countactiveno"]!=0) { ?>
						<span class="badge"><?=$row["countactiveno"] ?></span> <?php } } ?>
						</a></li>		

						<li class="<?=($activepage == 'customertype') ? 'active' : '';?>"><a href="<?=$_path?>customertype/index.php">ข้อมูลประเภทผู้เช่า</a></li>
					</ul>
				</li>	
		<?php } ?>

				<li class="<?=($activepage == 'room') ? 'active' : '';?>"><a href="<?=$_path?>room/index.php">ข้อมูลห้องที่ให้บริการ<span style="font-size:20px;" class="pull-right hidden-xs showopacity fa fa-university"></span></a></li>	
		
		<?php if(isset($_SESSION['UserId'])&&$_SESSION['UserTypeId']==0&&$_SESSION['StaffLevel']!="lab"&&$_SESSION['StaffLevel']!="maid") { ?>
				<li class="<?=($activepage == 'holiday') ? 'active' : '';?>"><a href="<?=$_path?>holiday/index.php">รายการวันหยุด<span style="font-size:20px;" class="pull-right hidden-xs showopacity fa fa-font-awesome"></span></a></li>

				<li class="<?=($activepage == 'report') ? 'active' : '';?>"><a href="<?=$_path?>report/index.php">รายงาน<span style="font-size:20px;" class="pull-right hidden-xs showopacity fa fa-file-text-o"></span></a></li>
		<?php } ?>
				
		<?php if(isset($_SESSION['UserId'])&&$_SESSION['UserTypeId']==0&&$_SESSION['StaffLevel']=="sadmin") { ?>		
				<li class="<?=($activepage == 'staff') ? 'active' : '';?>"><a href="<?=$_path?>staff/index.php">ข้อมูลเจ้าหน้าที่<span style="font-size:20px;" class="pull-right hidden-xs showopacity fa fa-cog"></span></a></li>	
		<?php } ?>

			</ul>
		</div>
	</div>
</nav>