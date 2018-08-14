<?php

?>
   <script src="../../../../rentroom/calend/codebase/dhtmlxscheduler.js" type="text/javascript" charset="utf-8"></script>
   <link rel="stylesheet" href="../../../../rentroom/calend/codebase/dhtmlxscheduler_material.css" type="text/css" charset="utf-8">

	<script src="../../../../rentroom/calend/codebase/ext/dhtmlxscheduler_minical.js" type="text/javascript" charset="utf-8"></script>

<!--<style type="text/css" >
   html, body{
      margin:0px;
      padding:0px;
      height:100%;
      overflow:hidden;
   }   
</style>-->

<script type="text/javascript" charset="utf-8">
	function init() {
		scheduler.config.multi_day = true;
		
		scheduler.config.xml_date="%Y-%m-%d %H:%i";
		scheduler.init('scheduler_here',new Date(2018,0,10),"week");
		scheduler.load("../../../../rentroom/calend/samples/common/events.json", "json")


	}
	
	function show_minical(){
		if (scheduler.isCalendarVisible())
			scheduler.destroyCalendar();
		else
			scheduler.renderCalendar({
				position:"dhx_minical_icon",
				date:scheduler._date,
				navigation:true,
				handler:function(date,calendar){
					scheduler.setCurrentView(date);
					scheduler.destroyCalendar()
				}
			});
	}


    
</script>

<body onload="init();">
   <div id="scheduler_here" class="dhx_cal_container" style='height:768px;'>
      <div class="dhx_cal_navline">
         <div class="dhx_cal_prev_button">&nbsp;</div>
         <div class="dhx_cal_next_button">&nbsp;</div>
         <div class="dhx_cal_today_button"></div>
         <div class="dhx_cal_date"></div>
         <div class="dhx_minical_icon" id="dhx_minical_icon" onclick="show_minical()">&nbsp;</div>
         <div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
         <div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
         <div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
      </div>
      <div class="dhx_cal_header">
      </div>
      <div class="dhx_cal_data">
      </div>
   </div>
</body>
