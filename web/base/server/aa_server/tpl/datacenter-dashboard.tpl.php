<!--
/*
    htvcenter Enterprise developed by HTBase Corp.

    All source code and content (c) Copyright 2015, HTBase Corp unless specifically noted otherwise.

    This source code is released under the htvcenter Enterprise Server and Client License, unless otherwise agreed with HTBase Corp.

    By using this software, you acknowledge having read this license and agree to be bound thereby.

                http://www.htbase.com

    Copyright 2015, HTBase Corp <contact@htbase.com>
*/
-->
<script type="text/javascript">

var mainomaino = true;
//<![CDATA[
var lang_inventory_servers = "{lang_inventory_servers}";
var lang_inventory_storages = "{lang_inventory_storages}";


$(document).ready(function(){
var flagmain = true;
var sizes = ["{mempercent}%", "{swappercent}%", "{hddpercent}%"];
var esxstorages = "{esxstoragespercent}";


	// index page actions:	
	if (flagmain == true) {


		 $('.progress-bar').each(function(i) {
		 	i.target.css('width', sizes[i]);
		 });

		// --- end progress animation ---
	}
});

//]]>
</script>

  <style>
        #demo-set-btn {
            display: none;
        }
   </style>

    <script>
    var diagramshow = true;
    var d = new Date();
    var month = d.getMonth(); 
    var numyear = d.getYear();
    var year = d.getFullYear();
    var monthcurrentname = '';
    var monthlastname = '';
    var yearcurrent = '';
    var yearold = '';


givedashboard(month, year);
function givedashboard(month, year, user) {

    
    month = parseInt(month)

    switch(month) {
        case 0:
             monthcurrentname = 'January';
             monthlastname = 'December';
             monthcurrentnameajax = 'Jan';
             monthlastnameajax = 'Dec';
             yearcurrent = year;
             yearold = parseInt(year) - 1;
             
        break;
        
        case 1:
            console.log('here');
            monthcurrentname = 'February';
            monthlastname = 'January';
            monthcurrentnameajax = 'Feb';
            monthlastnameajax = 'Jan';
            yearcurrent = year;
            yearold = year;
        break;
        case 2:
            monthcurrentname = 'March';
            monthlastname = 'February';
            monthcurrentnameajax = 'Mar';
            monthlastnameajax = 'Feb';
            yearcurrent = year;
            yearold = year;
        break;
        case 3:
            monthcurrentname = 'April';
            monthlastname = 'March';
             monthcurrentnameajax = 'Apr';
            monthlastnameajax = 'Mar';
            yearcurrent = year;
            yearold = year;
        break;
        case 4:
            monthcurrentname = 'May';
            monthlastname = 'April';
            monthcurrentnameajax = 'May';
            monthlastnameajax = 'Apr';
            yearcurrent = year;
            yearold = year;
        break;
        case 5:
            monthcurrentname = 'June';
            monthlastname = 'May';
            monthcurrentnameajax = 'Jun';
            monthlastnameajax = 'May';
            yearcurrent = year;
            yearold = year;
        break;
        case 6:
            monthcurrentname = 'July';
            monthlastname = 'June';
            monthcurrentnameajax = 'Jul';
            monthlastnameajax = 'Jun';
            yearcurrent = year;
            yearold = year;
        break;
        case 7:
            monthcurrentname = 'August';
            monthlastname = 'July';
            monthcurrentnameajax = 'Aug';
            monthlastnameajax = 'Jul';
            yearcurrent = year;
            yearold = year;
        break;
        case 8:
            monthcurrentname = 'September';
            monthlastname = 'August';
            monthcurrentnameajax = 'Sep';
            monthlastnameajax = 'Aug';
            yearcurrent = year;
            yearold = year;
        break;
        case 9:
            monthcurrentname = 'October';
            monthlastname = 'September';
            monthcurrentnameajax = 'Oct';
            monthlastnameajax = 'Sep';
            yearcurrent = year;
            yearold = year;
        break;
        case 10:
            monthcurrentname = 'November';
            monthlastname = 'October';
            monthcurrentnameajax = 'Nov';
            monthlastnameajax = 'Oct';
            yearcurrent = year;
            yearold = year;
        break;
        case 11:
            monthcurrentname = 'December';
            monthlastname = 'November';
            monthcurrentnameajax = 'Dec';
            monthlastnameajax = 'Nov';
            yearcurrent = year;
            yearold = year;
        break;
    }

    renderdash(user);

 }


 function renderdash(user) {

    $('#donutrender').html('');
    $('#barcharts').html('');
    
    $('#cloud-content').css('min-height', '400px');
    //var legendo = ''; 

    
    
    //var legendo = [{'label':'testone', 'value':60}, {'label':'testsecond', 'value':40}];

    console.log(yearcurrent);
    console.log(monthcurrentnameajax);
    var url = '/cloud-fortis/user/index.php?report=yes';
    var dataval = 'year='+yearcurrent+'&month='+monthcurrentnameajax+'&priceonly=0&detailcategory=1&userdash='+user;
    var category = '';
    
        $.ajax({
                url : url,
                type: "POST",
                data: dataval,
                cache: false,
                async: false,
                dataType: "html",
                success : function (data) {
                    
                    if (data != 'none') {
                        category = data;
                    } 
                    
                }
            });  


        category = JSON.parse(category);

       
    var legendonut = [];
    legendonut.push({'label':'Networking', 'value':category.network});
    legendonut.push({'label':'Virtualisation', 'value':category.virtualisation});
    legendonut.push({'label':'Memory', 'value':category.memory});
    legendonut.push({'label':'CPU', 'value':category.cpu});
    legendonut.push({'label':'Storage', 'value':category.storage});
     
    
    var priceold = '';
    var pricethis = '';

    var url = '/cloud-fortis/user/index.php?report=yes';
    var dataval = 'year='+yearcurrent+'&month='+monthcurrentnameajax+'&priceonly=true&userdash='+user;
        $.ajax({
                url : url,
                type: "POST",
                data: dataval,
                cache: false,
                async: false,
                dataType: "html",
                success : function (data) {
                    
                    if (data != 'none') {
                        pricethis = parseFloat(data);
                    } 
                    
                }
            });  

        var dataval = 'year='+yearold+'&month='+monthlastnameajax+'&priceonly=true';
        $.ajax({
                url : url,
                type: "POST",
                data: dataval,
                cache: false,
                async: false,
                dataType: "html",
                success : function (data) {
                    if (data != 'none') {
                        priceold = parseFloat(data);
                    } 
                }
        });

    var prognoseprice = pricethis;

    if ( pricethis < priceold ) {
        prognoseprice = (pricethis + priceold) / 2;  
    }

   var elemento = 'donutrender';

   if (typeof(mainomaino) != 'undefined') {
   	 var elemento = 'donutrendermaino';
   }

 if (category.network != 0 || category.virtualisation !=0 || category.memory !=0 || category.storage != 0 || category.cpu != 0) {
    Morris.Donut({
                    element: elemento,
                    data: legendonut,
                    colors: [
                        '#a6c600',
                        '#177bbb',
                        '#afd2f0',
                        "#1fa67a", "#ffd055", "#39aacb", "#cc6165", "#c2d5a0", "#579575", "#839557", "#958c12", "#953579", "#4b5de4", "#d8b83f", "#ff5800", "#0085cc"
                    ],
                    resize:true
                });
} else {
    $('#donutrender').html('<p class="nodatadonut">No information available for these dates</p>');
}

console.log(priceold);
console.log(pricethis);
if (priceold !=0 || pricethis != 0) {
    Morris.Bar({
      barSizeRatio:0.3,
      element: 'barcharts',
      data: [
        { y: monthlastname, a: priceold },
        { y: monthcurrentname, a: pricethis },
        { y: 'Forecast', a: prognoseprice },
      ],
      barColors: ['#afd2f0', '#177bbb', '#a6c600'],
      xkey: 'y',
      ykeys: ['a'],
      labels: ['Price in $']
    });

} else {

    $('#barcharts').html('<p class="nodatabars">No information available for these dates</p>');
}



                        $('td.storage').text(category.storage);
                        $('td.cpu').text(category.cpu);
                        $('td.memory').text(category.memory);
                        $('td.networking').text(category.network);
                        $('td.virtualisationb').text(category.virtualisation);
                     


}
</script>                         




<div id="prenutanix">
<div class="row">
	
	<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
		<div class="panel serverpanel">
			<h2 class="dash"><i class="fa fa-server"></i> HyperTask Server</h2>
			
			<div class="iron-place" id="devicearea">
				<h2 class="litli"><i class="fa fa-cogs"></i> CPU & memory:</h2>
				<ul class="storage-list">
					<li><b>CPU:</b> {cpu}</li>
					<li><b>Memory used:</b> {memused}</li>
					<li><b>Memory total:</b> {memtotal}</li>
				</ul>

				<div class="progress memoryprogress">
				  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {mempercent}%;">
				    <span class="sr-only">{mempercent}% used</span>
				  </div>
				</div>

				<ul class="storage-list">
					<li><b>Swap used:</b> {swapused}</li>
					<li><b>Swap total:</b> {swaptotal}</li>
				</ul>

				<div class="progress memoryprogress swapprogress">
				  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {swappercent}%;">
				    <span class="sr-only">{swappercent}% used</span>
				  </div>
				</div>

				
			</div>
		</div>
		<div class="panel networkpanel">
					<script>
						var physicalpercent = {physicalpercent};
						var bridgepercent = {bridgepercent};
						var okchart = 'okkk';
					</script>

					<h2 class="dash"><i class="fa fa-signal"></i> HyperTask Network:</h2>
					<div id="networkarea">
					<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 networkpie">
					<div id="demo-sparkline-pie" class="box-inline "></div>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
					<div id="sparklineinfo">
						<div class="donut-chart-legend">
							<ul>
								<li><div class="legend-tile tooltip-l" style="background:#2d4859"><span>Physical ({physcount})</span></div></li>
								<li><div class="legend-tile tooltip-l" style="background:#fe7211"><span>Bridge ({bridgecount})</span></div></li>
							</ul>
						</div>
					</div>
					</div>
					</div>
					<!--{devicelist}-->
					</div>
			
		</div>
		<div class="panel storagespanel">
			<h2 class="dash"><i class="fa fa-hdd-o"></i> HyperTask Storage</h2>
			<div id="storageareas">
					
				<div class="onestorage">
				
				<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
					<div class="esxileft leftstorageblock">

						{sfree}<br>
						<span>free (physical)</span>
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 esxright sizeroside">
					<div class="totalinfor">
						<b>Used:</b> {sused}<br>
						<b>Total:</b> {stotal} <br>
					</div>

				</div>


					<div class="progress hddprogress nutanixprogress">
					  <div style="width: {spercent}%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar">
					    <span class="sr-only">{spercent}% used</span>
					  </div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="display:none;">
		<div class="panel" style="display:none;">
			<!-- Start: Quicklink section -->
			<!--
				{quicklinks_headline}
				{quicklinks}
			//-->
		
			<!-- Start: Datacenter load current -->		
			<h2 class="dash">
			{load_headline}
			<small>{load_current}</small>
			<!--
			<span class="pull-right">
				<a class="widget-action refresh-load-current" href="#">
					<span class="halflings-icon refresh"><i></i></span>
				</a>
			</span>
			-->
			</h2>
			<table class="table">
			<tr>
				<td class="width0">{datacenter_load_overall}</td>
				<td>
					<div class="bar-01 chart-bar ">
						<div class="bar">
							<label>0.43</label>
						</div>
						
					</div>
				</td>
			</tr>
			<tr>
				<td>{appliance_load_overall}</td>
				<td>
					<div class="bar-02 chart-bar">
						<div class="peak"></div>
						<div class="bar">
							<label>0.43</label>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td>{storage_load_overall}</td>
				<td>
					<div class="bar-03 chart-bar">
						<div class="peak"></div>
						<div class="bar">
							<label>0.43</label>
						</div>
					</div>
				</td>
			</tr>
		</table>
		</div>
	</div>

	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-9 data-center-load">

							<!--Network Line Chart-->
							<!--===================================================-->
							<div id="demo-panel-network" class="panel">
								<div class="panel-heading">
									
									<h3 class="panel-title">Datacenter Load</h3>
								</div>
					
								<!--Morris line chart placeholder-->
								<div id="morris-chart-network" class="morris-full-content"></div>
					
								<!--Chart information-->
								<div class="panel-body bg-primary" style="position:relative;z-index:2">
									<div class="row">
										<div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
											<div class="row">
												<div class="col-xs-4 col-md-4 col-sm-4 col-lg-4">
					
													<!--Datacenter stat-->
													<div class="pad-ver media">
														<div class="media-left">
															<span class="icon-wrap icon-wrap-xs">
																<i class="fa fa-building-o fa-2x"></i>
															</span>
														</div>
					
														<div class="media-body">
															<p class="h3 text-thin media-heading datacenterp"></p>
															<small class="text-uppercase signserv">Datacenter</small>
														</div>
													</div>


													<!--Progress bar-->
													<div class="progress progress-xs progress-dark-base mar-no">
														<div class="progress-bar progress-bar-light datacenterpbar"></div>
													</div>
												</div>
												<div class="col-xs-4 col-md-4 col-sm-4 col-lg-4">
													<!--Server stat-->
													<div class="pad-ver media">
														<div class="media-left">
															<span class="icon-wrap icon-wrap-xs">
																<i class="fa fa-server fa-2x"></i>
															</span>
														</div>
					
														<div class="media-body">
															<p class="h3 text-thin media-heading serverp"></p>
															<small class="text-uppercase signserv">Server Load</small>
														</div>
													</div>


													<!--Progress bar-->
													<div class="progress progress-xs progress-dark-base mar-no">
														<div class="progress-bar progress-bar-light serverpbar"></div>
													</div>
												</div> <div class="col-xs-4 col-md-4 col-sm-4 col-lg-4">
													<!--Datacenter stat-->
													<div class="pad-ver media">
														<div class="media-left">
															<span class="icon-wrap icon-wrap-xs">
																<i class="fa fa-hdd-o fa-2x"></i>
															</span>
														</div>
					
														<div class="media-body">
															<p class="h3 text-thin media-heading storagep"></p>
															<small class="text-uppercase signserv">Storage network</small>
														</div>
													</div>
					
													<!--Progress bar-->
													<div class="progress progress-xs progress-dark-base mar-no">
														<div class="progress-bar progress-bar-light storagepbar"></div>
													</div>
												</div>
												</div>
											
											</div>
					
											
										</div>
									
									</div>
								</div>
					
					
							
							<!--===================================================-->
							<!--End network line chart-->
					
		</div>
	<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
		<!--List Todo-->	


									<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
									<div class="panel panel-white panel-colorful">
										<div class="panel-heading">
											<div class="panel-control">
												
											</div>
											<h2 class="dash greeeno"><i class="fa fa-cloud"></i> Cloud Charge Back</h2>
										</div>

										<div class="mainuserdash text-center">
											<div class="col-xs-4 col-sm-4 col-lg-4 col-md-4 text-center">
											<select id="reportuserdashmain" class="shorto" >
                                                            
                                                            {hidenuser}
                                                        </select>
                                            </div>
    										
    										<div class="col-xs-4 col-sm-4 col-lg-4 col-md-4 text-center">
    										<select id="reportmonthdashmain" class="shorto " ><option value="0">January</option><option value="1">February</option><option value="2">March</option><option value="3">April</option><option value="4">May</option><option value="5">June</option><option value="6">July</option><option value="7">August</option><option value="8">September</option><option value="9">October</option><option value="10">November</option><option value="11">December</option>
                                                          </select>
                                             </div>
                                             <div class="col-xs-4 col-sm-4 col-lg-4 col-md-4 text-center">
                                                     <select id="reportyeardashmain" class="shorto">{reportyear}</select>
                                             </div>
										</div>
										<div class="maindonutrenderrr">
											<div id="donutrendermaino">
											</div>
											<div id="totalamauntmain"><b>Total Amount:</b> <span id="mval"></span> </div>
										</div>


										<!--<div class="pad-ver todolistcontent">
											<ul class="list-group bg-trans list-todo mar-no" id="alltasks">
												{tasks}
											</ul>
										</div>
										<div class="input-group pad-all">
											<input type="text" id="newtaskinput" class="form-control" placeholder="New task" autocomplete="off">
											<span class="input-group-btn">
												<button type="button" class="btn btn-success" id="addtask"><i class="fa fa-plus"></i></button>
											</span>
										</div>
									</div>
									<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
									<!--End todo list-->
	
			</div></div>
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<div class="panel" style="display:none">
		<!-- Start: Datacenter load chart -->
		<h2 class="dash">{load_headline}
			<small>{load_last_hour}</small>
			<!--
			<span class="pull-right">
				<a class="widget-action refresh-load-chart" href="#">
					<span class="halflings-icon refresh"><i></i></span>
				</a>
			</span>
			-->
		</h2>	
		<div id="chartdiv-load" style="height:220px; width:100%;"></div>
		</div>

		<div class="panel eventwindow">
		<!-- Start: Event table -->
		<h2 class="dash">{events_headline}
			<!--
			<span class="pull-right">
				<a class="widget-action refresh-events" href="#">
					<span class="halflings-icon refresh"><i></i></span>
				</a>&nbsp;
				<a class="widget-action linkto-events" href="#">
					<span class="halflings-icon log_in"><i></i></span>
				</a>
			</span>
			-->
		</h2>
		<a >
		<div id="eventsboxes">
			
			<div id="warningeventbox" class="eventbox col-xs-12 col-md-4 col-sm-4 col-lg-4">
				<span class="eventcount" id="events_active"></span> <br/>
				<i class="fa fa-envelope eventico"></i><br/>
				<span class="eventword">messages</span>
			</div>
			

			<div id="erroreventbox" class="eventbox col-xs-12 col-md-4 col-sm-4 col-lg-4">
				<span class="eventcount" id="events_critical"></span> <br/>
				<i class="fa fa-exclamation-triangle eventico"></i><br/>
				<span class="eventword">errors</span>
			</div>

			<div id="messageeventbox" class="eventbox col-xs-12 col-md-4 col-sm-4 col-lg-4">
				<span class="eventcount" id="events_messages"></span> <br/>
				<i class="fa fa-bell eventico"></i><br/>
				<span class="eventword">all events</span>
			</div>
		</div>
		</a>

	</div>
</div>

<!-- Datacenter Summary Div -->

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 botpanel">
	<div class="panel">	
		<!-- Start: Inventory overview -->
		<h2 class="dash">Datacenter Summary</h2>
		<div class="row">
			
			
			
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 withchart">
					<h3 class="dash">Hosts:</h3>
				
					<div id="chartdiv-inventory-server">
					<div class="no-data-available">
						<h3 >{no_data_available}</h3>
						{link_server_management}
					</div>
					</div>
					<div style="height: 220px; display:none" id="server-donut"></div>
				</div>

				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 withlegend">
					<div id="chartdiv-inventory-server-legend" class="donut-chart-legend"></div>	
				</div>
			
				
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 withchart vm-summary-chart">
					<div class="panel panel-bordered-primary allvmmain">
						<div class="panel-heading"><h3 class="panel-title">VM Summary </h3></div>
						<div class="panel-body">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<div class="esxileft"><b>{allvmcount}</b> <br><span>VMs</span></div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 esxiright">
									<div class="vmsside">
										<span class="roundbullet greenbullet"></span><b>{activeallvm}</b> active<br>
										<a href="index.php?report=report_inactive"><span class="roundbullet yellowbullet"></span><b>{inactiveallvm}</b> inactive</a><br>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 withlegend vm-summary-legend">
					<div id="chartdiv-inventory-server-storage" class="donut-chart-legend"></div>
				</div>

			
		</div>
			
	</div>
	</div>	

									
	</div>

<!-- Datacenter Summary Div Ends -->

</div>

<div id="nutanix">
	<h2 id="closenutanix"><i class="fa fa-close"></i> CLOSE</h2>

<div class="row">
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
	
		<div class="window esxi panel panel-bordered-primary">
			<div class="panel-heading">
									<h3 class="panel-title">Hypervisor Summary</h3>
			</div>
			<div class="panel-body">
								
			<div class="row">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<div class="esxileft">
					<b>ESXi</b> <br/>
					<span>hypervisor</span>
				</div>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 esxiright">
				<div class="esxright sizeroside ttt">
					
					<b>{esxversion}</b> <br/>
					<span>version</span>
					
				</div>
			</div>
			</div>
			</div>
		</div>
	
<div class="window storagemainwindow panel panel-bordered-primary">
	<div class="panel-heading">
			<h3 class="panel-title">Storage Summary</h3>
	</div>
			<div class="panel-body">
			
			{summary}
			</div>
		</div>



		<div class="panel panel-bordered-primary">
			<div class="panel-heading">
			<h3 class="panel-title">VM Summary </h3>
			<span class="badge badge-info bbadger"><a class="serversdetail" href="index.php?base=appliance">Servers detail</a></li>
										</span>
		</div>
			<div class="panel-body">
			
			
			<div class="row">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<div class="esxileft">
					<b>{esxvmcount}</b> <br/>
					<span>VMs</span>
				</div>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 esxiright">
				<div class="vmsside">
					<b>{esxvmactive}</b> active<br/>
					<b>{esxvminactive}</b> inactive<br/>
					<b>{esxvmimport}</b> import
					
				</div>
			</div>
			</div>
			</div>
		</div>
	

		<div class="panel panel-bordered-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Hardware Summary</h3>
				 <span class="badge badge-info bbadger"><a class="esxhostsdetail" href="index.php?plugin=vmware-esx&controller=vmware-esx-vm">Hosts detail</a></span>
			</div>
			<div class="panel-body">
			<div class="row">
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
				
				<div class="esxileft">
					<b>{esxhosts}</b> <br/>
					<span>Hosts</span>
				</div>
			</div>
			<!--
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
				<div class="esxileft">
					<b>1</b> <br/>
					<span>Block</span>
				</div>
			</div>
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 esxright">
				<div class="hardwr vmsside">
					<b>MX3050</b><br/>
					<span>model</span>
				</div>
			</div>
		-->
			</div>
			</div>
		</div>


	
	</div>

	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		

		<div class="window criticaleventwindow panel panel-bordered-pink">
			<div class="panel-heading">
									<div class="panel-control">
										<i class="fa fa-exclamation-triangle fa-lg fa-fw text-pink"></i>
										<span class="badge badge-pink">{esxerrorcount}</span>
									</div>
									<h3 class="panel-title">Critical Alerts</h3>
								</div>
			
			
				<div class="eventcontent">
					{esxeventerrors}
				</div>

			<div class="linkeventside pinko">
				<a href="index.php?base=event">View all alerts</a>
			</div>
		</div>

		<div class="window warningeventwindow panel panel-bordered-warning">
		<div class="panel-heading">
									<div class="panel-control">
										<i class="fa fa-bell fa-lg fa-fw text-warning"></i>
										<span class="badge badge-warning">{esxwarningcount}</span>
									</div>
									<h3 class="panel-title">Warning Alerts</h3>
								</div>
		
			<div class="eventcontent">
				{esxeventwarnings}
			</div>

			<div class="linkeventside warningo">
				<a href="index.php?base=event">View all alerts</a>
			</div>

			
		</div>
	</div>

	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="window alleventswindow panel panel-bordered-info">
				<div class="panel-heading">
									<div class="panel-control">
										<i class="fa fa-envelope fa-lg fa-fw text-info"></i>
										
									</div>
									<h3 class="panel-title">Events</h3>
								</div>
			<div class="eventcontent">
			{esxeventsall}
			</div>

			<div class="linkeventside evento">
				<a href="index.php?base=event">View all events</a>
			</div>
		</div>
	</div>
</div>
</div>

<script type="text/javascript">
function get_event_status() {
	$.ajax({
		url: "api.php?action=get_top_status",
		cache: false,
		async: false,
		dataType: "text",
		success: function(response) {
			if(response != '') {
				var status_array = response.split("@");
				var event_error = parseInt(status_array[6]);
				var event_active = parseInt({allcountwarnings});

				var events_messages = event_active + event_error;

				
				console.log($('#events_active'));
				$("#events_critical").html(event_error);
				$('#events_active').html(event_active);
				$("#events_messages").html(events_messages);
				
				if (event_active == 0) {
					$('.badge-warning').hide();
				} else {
					$('.badge-warning').html(event_active);
				}

				if (event_error == 0) {
					$('.badge-danger').hide();
				} else {
					$('.badge-danger').html(event_error);
				}

				if (events_messages == 0) {
					$('.badge-purple').hide();
				} else {
					$('.badge-purple').html(events_messages);
				}
			}
		}
	});
	setTimeout("get_event_status()", 5000);
}

get_event_status();
</script>

<div id="preeventsall" style="display: none">{preeventsall}</div>
<div id="preeventserror" style="display: none">{preeventserror}</div>
<div id="preeventsnotice" style="display: none">{preeventsnotice}</div>