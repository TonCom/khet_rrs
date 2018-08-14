<?php 

/**
 * @projectname  Room Management System <A Case Study: The Computer Center>
 * @author       Thapanon Thongnui <thapanon.t@hotmail.com>
**/

 	if(isset($isSubfolder) && $isSubfolder == false) {
 		$_path = "../../";
 	}
 	else{
 		$_path = "";
 	}

 ?> 

<style type="text/css" media="screen">
.navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus {
color: #000;  /*Sets the text hover color on navbar*/
}

.navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active >   
 a:hover, .navbar-default .navbar-nav > .active > a:focus {
color: white; /*BACKGROUND color for active*/
background-color: #292b2c;
font-weight: bold;
}

.navbar .navbar-nav {
  display: inline-block;
  float: none;
  vertical-align: top;
}

.navbar .navbar-collapse {
  text-align: center;
}
  .navbar-default {
    background-color: #448AFF;
    /*border-color: #030033;*/
    border-radius: 0px;
    border: 0px;
}

  .dropdown-menu > li > a:hover,
   .dropdown-menu > li > a:focus {
    color: #262626;
   text-decoration: none;
  background-color: #66CCFF;  /*change color of links in drop down here*/
   }

 .nav > li > a:hover,
 .nav > li > a:focus {
    text-decoration: none;
    background-color: silver; /*Change rollover cell color here*/
  }


  .navbar-default .navbar-nav > li > a {
   color: black; /*Change active text color here*/
    }
</style>


<nav class="navbar navbar-default" role="navigation">	
    <div class="container-fluid">
		<div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="<?=($activepage == 'index') ? 'active' : '';?>"><a href="<?=$_path?>index.php">หน้าแรก</a></li> 		

  <?php if(!isset($_SESSION['UserId'])||$_SESSION['UserTypeId']==0) { ?>				
				<li class="<?=($activepage == 'register') ? 'active' : '';?>"><a href="<?=$_path?>register.php">สมัครสมาชิก</a></li>

				<li class="<?=($activepage == 'login') ? 'active' : '';?>"><a href="<?=$_path?>login.php">เข้าสู่ระบบ</a></li>
  <?php } ?>      
    
  <?php if(isset($_SESSION['UserId'])&&$_SESSION['UserTypeId']!=0) { ?>
				<li class="<?=($activepage == 'searchrent') ? 'active' : '';?>"><a href="<?=$_path?>customer/searchrent/index.php">สอบถามห้องว่าง</a></li>											
	 	 
				<li class="<?=($activepage == 'addactivity') ? 'active' : '';?>"><a href="<?=$_path?>customer/activity/formaddactivity.php">ขอเช่าห้อง</a></li>	

				<li class="<?=($activepage == 'listrent') ? 'active' : '';?>"><a href="<?=$_path?>customer/activity/index.php">รายการขอเช่าห้อง</a></li>
  <?php } ?>  
									
				<li class="<?=($activepage == 'room') ? 'active' : '';?>"><a href="<?=$_path?>room.php">ข้อมูลห้องที่ให้บริการ</a></li>	
				<li class="<?=($activepage == 'contact') ? 'active' : '';?>"><a href="<?=$_path?>contact.php">ติดต่อเรา</a></li>	
			</ul>
		</div>
	</div>
</nav>