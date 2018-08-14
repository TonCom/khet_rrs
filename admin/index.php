<?php
	session_start();

	if(!isset($_SESSION['UserId'])||$_SESSION['UserTypeId']!=0) {
		
		header('Location: login.php?error=0'); 
	} else {        
        if(time() > $_SESSION['expire']) {
            session_destroy();
            header('Location: login.php?error=4');              
        } else {

	require "include/connect.php";	
	$activepage = "home";

	function DateThai($strDate){
		$strYear = substr(date("Y",strtotime($strDate))+543,-2);
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค","ก.พ","มี.ค","เม.ย","พ.ค","มิ.ย","ก.ค","ส.ค","ก.ย","ต.ค","พ.ย","ธ.ค");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai, $strHour:$strMinute";
	};
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>หน้าแรก</title>
      		      	
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css">       
        <link rel="stylesheet" type="text/css" href="css/fonts/thsarabunnew.css">

	<link rel="stylesheet" type="text/css" href="fullcalendar/fullcalendar.min.css">
	<link rel="stylesheet" type="text/css" media="print" href="fullcalendar/fullcalendar.print.css"> 
	</head>
    <style type="text/css">
  	body{ 
	   /* background: url("c.png") repeat;*/
	   	font-family: 'THSarabunNew', sans-serif; 
	   	font-size: 15px; }
	
	#bg { background: url("logoad.png") no-repeat bottom left; }

	.black-ribbon {
	  position: fixed;
	  z-index: 9999;
	  width: 70px;
	}
	@media only all and (min-width: 768px) {
	  .black-ribbon { width: auto; }
	}

	.stick-left { left: 0; }
	.stick-bottom { bottom: 0; }

	#calendar {		
		max-width: 1254px;
		margin: 15px 0 0 20px;
		font-size: 16px;
	}
	
	.holiday{ background-color: #9E9E9E; border-color: #9E9E9E; } /* holiday เทา */
	.r1{ background-color: #5cb85c; border-color: #5cb85c; } /* 101 เขียว */
	.r2{ background-color: #9370DB; border-color: #9370DB; } /* 103 ม่วง */
	.r3{ background-color: #292b2c; border-color: #292b2c; } /* 105 ดำ */
	.r4{ background-color: #0275d8; border-color: #0275d8; } /* 106 น้ำงเิน */
	.r5{ background-color: #EF5350; border-color: #EF5350; } /* 107 แดง */
	.r6{ background-color: #BDB76B; border-color: #BDB76B; } /* 102 น้ำตาล */
	</style>
    <body>
    
	<div class="container-fluid" style="border-bottom:0px solid #e7e7e7;">
	<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="bg" style="background-color: #27539c">
		<div class="col-xs-7 col-sm-10 col-md-9 col-lg-9" style="height: 123px">
			<div style="padding-top:15px; padding-left: 60px; font-size: 30px; font-weight: bold;"><img src="../images/psu1.png" width="92" height="107">&nbsp;&nbsp;ระบบบริหารจัดการการให้บริการห้องอบรม ศูนย์คอมพิวเตอร์
			</div>
		</div>	

		<?php  
		$sql = $db->prepare("SELECT * FROM user WHERE UserId='".$_SESSION['UserId']."'");
              $sql->execute();
              $sql->setFetchMode(PDO::FETCH_ASSOC);                   
        if ($row = $sql->fetch()) { $name=$row['PreName'].$row['FirstName']." ".$row['LastName']; } ?>	         
         <div class="col-xs-5 col-sm-2 col-md-3 col-lg-3" style="color: white; font-size:16px; line-height: 27px; padding-top: 33px; text-align: right; padding-right: 70px"> 
               <img src="../images/user.png" width="24" height="24"> &nbsp;<?=$name?><br><a href="../logout.php?ctrl=admin" style="padding: 3px" class="btn btn-sm btn-danger">ออกจากระบบ</a>  	
         </div>  
	</div>
	</div>
	</div>	
			<?php include "/include/menu.php"; ?>	

	  <?php $array=array(); $i=0;
			$sql = $db->prepare("SELECT * FROM service");
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			while ($row = $sql->fetch()) {
				$array[$i]['id']=$row['idactivity'];
		        $array[$i]['title']=$row['activityname'];
		        $array[$i]['start']=$row['startdate'];
		        $array[$i]['end']=$row['enddate'];	

		        $array[$i]['className']="r".$row['roomid'];	

		        //$array[$i]['color']=bgroom($row['roomid']);	

					// $std = date_format(date_create($row['startdate']), 'd');				
					// $end = date_format(date_create($row['enddate']), 'd');	

					$stdate = date_format(date_create($row['startdate']), 'Y-m-d');				
					$endate = date_format(date_create($row['enddate']), 'Y-m-d');

					$stti= date_format(date_create($row['startdate']), 'H:i');
					$enti=date_format(date_create($row['enddate']), 'H:i');

				if($stdate==$endate) {					
					$date=$stti."-".$enti; 
				}
				else {
					$date=DateThai($row['startdate'])."-".DateThai($row['enddate']);				
				}
						
				$array[$i]['description']=$date; 	       		  		        		        
	         $i++;
    		}


    		// แสดง วันที่หยุดใช้บริการ
    		$array1=array(); $i=0;
			$sql1 = $db->prepare("SELECT * FROM holiday");
			$sql1->execute();
			$sql1->setFetchMode(PDO::FETCH_ASSOC);
			while ($row1 = $sql1->fetch()) {
				$array1[$i]['id']=$row1['HolidayId'];
		        $array1[$i]['title']=$row1['HolidayName'];
		        $array1[$i]['start']=$row1['HolidayDate'];		        	
		        $array1[$i]['className']="holiday";	
		        $array1[$i]['description']=""; 
		     $i++;
    		} ?>

			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12" style="margin: 0 auto; width:86%">	
					
						<div id='calendar'></div>					
					</div>							
				</div>
			</div>
				      	
        <script src="js/bootstrap.min.js"></script>
        <script src="js/sidebar.js"></script> 

    <script src="fullcalendar/jquery.min.js"></script>
	<script src="fullcalendar/moment.min.js"></script>   
	<script src="fullcalendar/fullcalendar.min.js"></script>  
	<script src="fullcalendar/th.js"></script> 
        <script>
  		$(document).ready(function() {      
	        $('#calendar').fullCalendar({
	            header: {
	                left: 'prev,next today',
	                center: 'title',
	                right: 'month,agendaWeek,agendaDay'
	            },

	          	height: 730,
	            eventLimit: true, 
	            allDaySlot: false,
	            lang: 'th',	            

	            // events : <?php echo json_encode($array); ?>,
	            eventSources: [ <?=json_encode($array) ?>, 
	                            <?=json_encode($array1) ?> ],

	            eventRender: function(event, element) { 
		           element.find(".fc-time").remove();
	               element.find(".fc-title").remove();

	               element.append("<center>"+event.description+" "+event.title+"</center>"); 
        		} 
	        });       
  		  });
		</script>
    </body>
</html>
<?php } } ?>