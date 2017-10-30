<!--
/*
    htvcenter Enterprise developed by htvcenter Enterprise GmbH.

    All source code and content (c) Copyright 2014, htvcenter Enterprise GmbH unless specifically noted otherwise.

    This source code is released under the htvcenter Enterprise Server and Client License, unless otherwise agreed with htvcenter Enterprise GmbH.
    The latest version of this license can be found here: http://htvcenter-enterprise.com/license

    By using this software, you acknowledge having read this license and agree to be bound thereby.

                http://htvcenter-enterprise.com

    Copyright 2014, htvcenter Enterprise GmbH <info@htvcenter-enterprise.com>
*/
//-->

<link href="/cloud-fortis/css/vender/bootstrap/css/utilities.css" rel="stylesheet" type="text/css">
<link href="/cloud-fortis/css/vender/bootstrap/css/card.css" rel="stylesheet" type="text/css">
<link href="/cloud-fortis/designplugins/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js" type="text/javascript"></script>
<style>
	#project_tab_ui { display: none; }
	table.dataTable .thead-default th { background-color: rgb(255,255,255); }
	table.dataTable tbody td { padding-left: 18px; padding-right: 18px; } /* for aligning with thead */
	table.dataTable tbody td i { display: block; text-align: center; }
	table.dataTable tbody td.hide { display: none; } 
	table.dataTable.table-hover tbody tr:hover { background-color: rgb(189,199,231); }


	/*table.dataTable tbody tr.even { background-color: rgb(228,233,240); }  */
	table.dataTable tbody td section.card { margin-bottom: 0; }
	table.dataTable tbody td.status.active { color: rgb(112,173,71); }
	table.dataTable tbody td.status.inactive { color: red; }
	table.dataTable .dropdown ul.dropdown-menu { padding: 3px 8px; min-width: 5em; }
	.c3-graph { height: 179px; }
	.d-inline.pull-left { margin: 3px 0; padding: 0; text-align: left; background: inherit; }
	.d-inline-block.pull-left { margin: 0; padding: 0; }
	.card-header .d-inline-block span { margin: 0 15px; }
	.card-header i { display: inline !important; }
	.op-button{
		display: none;
	}
</style>

<span id="storagekvmid">{storagekvmid}</span>

<h2>{label}<span class="pull-right" id="servadddd">{add}</span></h2>

<div id="serverpanel" class="row">
	
	<div class="col-sm-12 col-md-4 col-lg-4 col-sm-4">
		<a href="/htvcenter/base/index.php?base=image">
		<div class="panel media pad-all ">
			<div class="media-body">
				<p class="text-2x mar-no text-thin"><span class="icon-wrap icon-wrap-sm icon-circle bg-success"><i class="fa fa-upload"></i></span>Images</p>
			</div>
		</div>
		</a>
	</div>
	
	<div class="col-sm-12 col-md-4 col-lg-4 col-sm-4">
		<a href="/htvcenter/base/index.php?base=resource">
		<div class="panel media pad-all ">
			<div class="media-body">
				<p class="text-2x mar-no text-thin"><span class="icon-wrap icon-wrap-sm icon-circle bg-warning"><i class="fa fa-database"></i></span>Resource</p>
			</div>
		</div>
		</a>
	</div>

	<div class="col-sm-12 col-md-4 col-lg-4 col-sm-4">
		<a href="/htvcenter/base/index.php?base=storage">
		<div class="panel media pad-all ">
			<div class="media-body">
				<p class="text-2x mar-no text-thin"><span class="icon-wrap icon-wrap-sm icon-circle bg-danger"><i class="fa fa-hdd-o"></i></span>Storage</p>
			</div>
		</div>
		</a>
	</div>
</div>

<div id="form">
	<form action="{thisfile}" method="POST">
		{form}
		<!-- {resource_filter}
		{resource_type_filter}-->
		<div class="search-elements">
			{resource_type_filter}
			<!-- <div id="pagination"> {pagerContainer} </div> -->
		</div>
		<div id="storagekvmid" style="display: none;">
			{storagekvmid}
		</div>
		<div class="divTable">
			{div_html}
		</div>
		
	</form>
</div>

<div id="volumepopup" class="modal-dialog">
	<div class="panel">
		<!-- Classic Form Wizard -->
		<!--===================================================-->
		<div id="demo-cls-wz">
			<!--Nav-->
			<ul class="wz-nav-off wz-icon-inline wz-classic">
				<li class="col-xs-3 bg-info active">
					<a href="#demo-cls-tab1" data-toggle="tab" aria-expanded="true"><span class="icon-wrap icon-wrap-xs bg-trans-dark"><i class="fa fa-server"></i></span> Server Alert</a>
				</li>
				<div class="volumepopupclass"><a id="volumepopupclose"><i class="fa fa-icon fa-close"></i></a></div>
			</ul>
			
			<!--Progress bar-->
			<div class="progress progress-sm progress-striped active">
				<div class="progress-bar progress-bar-info" style="width: 100%;"></div>
			</div>
			
			<!--Form-->
			<div class="form-horizontal mar-top">
				<div class="panel-body">
					<div class="tab-content">
						<!--First tab-->
						<div class="tab-pane active in" id="demo-cls-tab1">
							<div id="storageform"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--===================================================-->
		<!-- End Classic Form Wizard -->
	</div>
</div>



<div id="volumepopupvmf" class="modal-dialog">
	<div class="panel">
		<!-- Classic Form Wizard -->
		<!--===================================================-->
		<div id="demo-cls-wz">
			<!--Nav-->
			<ul class="wz-nav-off wz-icon-inline wz-classic">
				<li class="col-xs-3 bg-info active"><a href="#demo-cls-tab1" data-toggle="tab" aria-expanded="true"><span class="icon-wrap icon-wrap-xs bg-trans-dark"><i class="fa fa-server"></i></span> Server Action</a></li>
				<div class="volumepopupclass"><a id="volumepopupclosevmf"><i class="fa fa-icon fa-close"></i></a></div>
			</ul>
			<!--Progress bar-->
			<div class="progress progress-sm progress-striped active">
				<div class="progress-bar progress-bar-info" style="width: 100%;"></div>
			</div>
			<!--Form-->
			<div class="form-horizontal mar-top">
				<div class="panel-body">
					<div class="tab-content">
						<!--First tab-->
						<div class="tab-pane active in" id="demo-cls-tab1">
							<div id="actionvmf"></div>
							<div id="storageformvmf"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--===================================================-->
		<!-- End Classic Form Wizard -->
	</div>
</div>


<script>
$(document).ready(function() {
	function formatPopOver (d) {
		var img_text = jQuery(d[3]).text();
		var onclickHtml = '<ul class="appliance-pop-over-menu">';
		if (d[10]){
			if (d[10].indexOf('stop') !== -1){
				onclickHtml = onclickHtml + '<li class="fa fa-stop app-stop"> '+d[10]+'</li>';
			} else {
				onclickHtml = onclickHtml + '<li class="fa fa-play app-start"> '+d[10]+'</li>';
			}
		}
		if (d[11]){
			onclickHtml = onclickHtml + '<li class="fa fa-edit"> '+d[11]+'</li>';
		}
		if (d[12]) {
			onclickHtml = onclickHtml + '<li class="fa fa-refresh"> '+d[12]+'</li>';
		}
		if (d[15] && d[15] != 1) {
			onclickHtml = onclickHtml + '<li class="fa fa-trash"><span class="remove-server" onclick=\'removeServer("'+d[15]+'");\'> Remove</span></li>'; //<a href="index.php?base=appliance&appliance_action=remove&appliance_id='+d[15]+'">
		}
		if (d[13]) {
			onclickHtml = onclickHtml + '<li class="fa fa-clone"><span class="clonera" onclick=\'cloneImage("'+img_text+'");\'> Clone</span></li> <li class="fa fa-files-o"><span class="protera" onclick=\'snapShot("'+img_text+'");\'> Snapshot</span></li>';
		}
		if (d[14]) {
			onclickHtml = onclickHtml + '<li> '+d[14]+'</li>';
		}
		onclickHtml = onclickHtml + '</ul>';
		return onclickHtml;
	}

	var dt = $("#cloud_appliances_table").DataTable( {
		"stateSave": true,
		"columns": [
				{ "visible": false },
				null, null, null, null, null, null, null, null,
				{ "orderable": false },
				{ "visible": false },
				{ "visible": false },
				{ "visible": false },
				{ "visible": false },
				{ "visible": false },
				{ "visible": false },
		],
		"order": [], "bLengthChange": false, "pageLength": 10, "search": { "regex": true }, "bAutoWidth": true, "destroy": true,
		"fnDrawCallback": function( oSettings ) {
			$(".toggle-graph a").popover({
				html: true,
				placement: "bottom",
				content: function() {
					var tr = $(this).closest('tr');
					var row = dt.row( tr );
					return formatPopOver(row.data()); //$('#popover-content').html();
				}
			});
		}
	});
	
	var delay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();
	
	$("#search-app").keyup(function() {
			var txt = $(this).val();

			delay(function() {
				searchApp(txt);
			}, 300);
		});
	});
	
	function refreshCPU() {
		$.ajax({
		  url: 'index.php?base=appliance&cpuload=1',
		})
		.done(function(d) {
			var cpuLoad = jQuery.parseJSON(d);
			var count = 0;
			var table = $('#cloud_appliances_table').DataTable();
			table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
				var data = this.data();
				data[7] = cpuLoad[data[0]];
				this.data(data).draw();
				count = count + 1;
			});
		})
		.fail(function() {
			alert("Failed to load");
		});
	}
	
	setInterval(refreshCPU,10000);

	function getParameterByName(name, url) {
		if (!url) url = window.location.href;
		name = name.replace(/[\[\]]/g, "\\$&");
		var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"), results = regex.exec(url);
		if (!results) return null;
		if (!results[2]) return '';
		return decodeURIComponent(results[2].replace(/\+/g, " "));
	}
	
	function noVNCPOPUP(url) {
		//alert(url);
		var id = getParameterByName('appliance_id', url) ;
		var l = '/htvcenter/base/api.php?action=plugin&plugin=novnc&controller=novnc&novnc_action=console&appliance_id='+id;
		noVncWindow = window.open(l, "noVnc_{port}", "titlebar=no, location=no, scrollbars=yes, width=800, height=500, top=50");
		noVncWindow.focus();
	}
	$('a.novnc-popup').click(function(){
		var storagelink = $(this).attr('href');
		noVNCPOPUP(storagelink);
		return false;
	});
	$('#volumepopupvncclose').click(function(){
		$('#volumepopupvnc').hide();
	});
	
	$(".divRow").click(function(){
		var showClass = 'child-div-'+$(this).attr('id');
		if($("."+showClass).is(":visible")){
			$("."+showClass).hide();
		} else {
			$("."+showClass).css('display', 'block');
		}
	});
	$('#servadddd').click(function(e){
		e.preventDefault();
		$('.lead').hide();
	  		var storagelink = $(this).find('a.add').attr('href');
	 		$('#storageformaddn').load(storagelink+" #step1", function(){
	  			$('.lead').hide();
	  			$('#storageformaddn select').selectpicker();
	  			$('#storageformaddn select').hide();
	  			var heder = $('#appliance_tab0').find('h2').text();

				if (heder == 'ServerAdd a new Server') {
					$('#storageformaddn').find('#name').css('left','-20px');
				}
				$('#storageformaddn').find('#info').remove();
  				$('#volumepopupaddn').show();
	  		});  			
	});
	$('#volumepopupcloseaddn').click(function(){
		$('#volumepopupaddn').hide();
	});
	
	function startServer(id){
		var id = id;
		var urlstring = 'index.php?base=appliance&resource_filter=&appliance_action=start&appliance_identifier[]='+id;
		$('#storageformvmf').load(urlstring+" form#app-start-form", function(){
			$('.lead').hide();
			$('#actionvmf').html('<div class="start-stop-ction"><label>Action</label> <div class="alcontent"><i class="fa fa-play"></i> Start </div><br/></div>');
			$('#storageformvmf select').selectpicker();
			$('#storageformvmf select').hide();
			$('#volumepopupvmf').show();
		}); 
	}
	
	function stopServer(id){
		var id = id;
		var urlstring = 'index.php?base=appliance&resource_filter=&appliance_action=stop&appliance_identifier[]='+id;
		$('#storageformvmf').load(urlstring+" form#app-stop-form", function(){
			$('.lead').hide();
			$('#actionvmf').html('<div class="start-stop-ction"><label>Action:</label> <div class="alcontent"><i class="fa fa-stop"></i> Stop </div><br/></div>');
			$('#storageformvmf select').selectpicker();
			$('#storageformvmf select').hide();
			$('#volumepopupvmf').show();
		}); 
	}
	
	function validateServerName(){
		$(".name-error").remove();
		var serverName = $("div#step1 #name").val();
		if (serverName == ""){
			$("div#storageformaddn div#step1 div#name_box div.right").append("<span class='name-error'>Server name can not be empty</span>");
			return(false);
		} else if (serverName.indexOf(' ') >= 0) {
			$("div#storageformaddn div#step1 div#name_box div.right").append("<span class='name-error'>Server name can not have space</span>");
			return(false);
		}
		else {
			document.getElementById('#server-add-step-1').submit();
			return(true);
		}
	}
	
	function cloneImage(d3){
		var img = d3;
		var storageid = $('#storagekvmid').text();
		var urlstring = 'index.php?base=storage&storage_filter=&splugin=kvm&scontroller=kvm&storage_action=load&storage_id='+storageid+'&volgroup=storage1&kvm_action=clone&lvol='+img;
		$('#storageformvmf').load(urlstring+" form", function(){
			$('.lead').hide();
			$('#actionvmf').html('<div class="alaction"><label>Action:</label> <div class="alcontent"><i class="fa fa-clone"></i> Clone </div><br/></div>');
			$('#storageformvmf select').selectpicker();
			$('#storageformvmf select').hide();
			$('#volumepopupvmf').show();
		});
	}
	
	function snapShot(d3){
		var storageid = $('#storagekvmid').text();
		var img = d3;		
		var urlstring = 'index.php?base=storage&storage_filter=&splugin=kvm&scontroller=kvm&storage_action=load&storage_id='+storageid+'&volgroup=storage1&kvm_action=snap&lvol='+img;
		$('#storageformvmf').load(urlstring+" form", function(){
			$('.lead').hide();
			$('#actionvmf').html('<div class="alaction"><label>Action:</label> <div class="alcontent"><i class="fa fa-files-o"></i> Snapshot </div><br/></div>');
			$('#storageformvmf select').selectpicker();
			$('#storageformvmf select').hide();
			$('#volumepopupvmf').show();
		}); 
	}

	function removeServer(id){
		var id = id;
		var url = 'index.php?base=appliance&resource_filter=&appliance_action=select&resource_filter=&resource_type_filter=&appliance%5Bsort%5D=appliance_id&appliance%5Border%5D=ASC&appliance%5Boffset%5D=0&appliance%5Blimit%5D=20&appliance_identifier%5B%5D='+id+'&appliance_action%5Bremove%5D=remove';
		$('#storageform').load(url+" form", function(){
			$('#storageform select').selectpicker();
			$('#storageform select').hide();
			$('#storageform form.form-horizontal').remove();
			$('#storageform .selectpicker')
			$('#volumepopup').show();
		});
	}
	
	function formatAWSPopOver (d) {
		var onclickHtml = '<ul class="appliance-pop-over-menu">';
			onclickHtml = onclickHtml + '<li class="fa fa-cogs"> '+d[6]+'</li>';
			onclickHtml = onclickHtml + '<li class="fa fa-cog"> '+d[7]+'</li>';
			onclickHtml = onclickHtml + '<li class="fa fa-history"> '+d[8]+'</li>';
			onclickHtml = onclickHtml + '</ul>';
		return onclickHtml;
	}
	
	if($('#load-aws-instance').length){
		$.ajax({
		  url: 'index.php?base=appliance&awsec2=list',
		})
		.done(function(data) {
			$("#aws-resources-instance").html(data);
			var aws_dt = $("#aws_instance_table").DataTable( {
				"columns": [
						{ "visible": false }, null, null, null, null, null, { "visible": false }, { "visible": false }, { "visible": false },
				],
				"order": [], "bLengthChange": false, "pageLength": 10, "search": { "regex": true }, "bAutoWidth": true,
				"fnDrawCallback": function( oSettings ) {
					$(".aws-toggle-graph a").popover({
						html: true,
						placement: "bottom",
						content: function() {
							var tr = $(this).closest('tr');
							var row = aws_dt.row( tr );
							return formatAWSPopOver(row.data()); //$('#popover-content').html();
						}
					});
				}
			});
		})
		.fail(function() {
			alert("Failed to load instances from AWS");
		});
	}
	
	$('#load-aws-instance').click(function(e){
		$.ajax({
		  url: 'index.php?base=appliance&awsec2=list',
		})
		.done(function(data) {
			$("#aws-resources-instance").html(data);
			var aws_dt = $("#aws_instance_table").DataTable( {
				"columns": [
						{ "visible": false }, null, null, null, null, null, { "visible": false }, { "visible": false }, { "visible": false },
				],
				"order": [], "bLengthChange": false, "pageLength": 10, "search": { "regex": true }, "bAutoWidth": true,
				"fnDrawCallback": function( oSettings ) {
					$(".aws-toggle-graph a").popover({
						html: true,
						placement: "bottom",
						content: function() {
							var tr = $(this).closest('tr');
							var row = aws_dt.row( tr );
							return formatAWSPopOver(row.data());
						}
					});
				}
			});
		})
		.fail(function() {
			alert("Failed to load instances from AWS");
		});
		return false;
	});
	
	if($('#load-azure-instance').length){
		$.ajax({
		  url: 'index.php?base=appliance&azurevm=list',
		})
		.done(function(data) {
			$("#azure-resources-vms").html(data);
			var azure_dt = $("#azure_vm_table").DataTable( {
				"columns": [
						{ "visible": false }, null, null, null, null, null, { "visible": false }, { "visible": false }, { "visible": false },
				],
				"order": [], "bLengthChange": false, "pageLength": 10, "search": { "regex": true }, "bAutoWidth": true,
				"fnDrawCallback": function( oSettings ) {
					$(".azure-toggle-graph a").popover({
						html: true,
						placement: "bottom",
						content: function() {
							var tr = $(this).closest('tr');
							var row = azure_dt.row( tr );
							return formatAWSPopOver(row.data());
						}
					});
				}
			});
		})
		.fail(function() {
			alert("Failed to load VMs from Azure");
		});
	}
	
	$('#load-azure-instance').click(function(e){
		$.ajax({
		  url: 'index.php?base=appliance&azurevm=list',
		})
		.done(function(data) {
			$("#azure-resources-vms").html(data);
			var azure_dt = $("#azure_vm_table").DataTable( {
				"columns": [
						{ "visible": false }, null, null, null, null, null, { "visible": false }, { "visible": false }, { "visible": false },
				],
				"order": [], "bLengthChange": false, "pageLength": 10, "search": { "regex": true }, "bAutoWidth": true,
				"fnDrawCallback": function( oSettings ) {
					$(".azure-toggle-graph a").popover({
						html: true,
						placement: "bottom",
						content: function() {
							var tr = $(this).closest('tr');
							var row = azure_dt.row( tr );
							return formatAWSPopOver(row.data());
						}
					});
				}
			});
		})
		.fail(function() {
			alert("Failed to load VMs from Azure");
		});
		return false;
	});
	
	function formatComposedPopOver (d) {
		var composeID = $(d[0]).text();
		var onclickHtml = '<ul class="compose-pop-over-menu">';
		onclickHtml = onclickHtml + '<li class="fa fa-edit"><a href="index.php?base=aa_server&controller=compose&compose_action=editcompose&composeID='+d[0]+'"> Edit</a></li>';
		onclickHtml = onclickHtml + '<li class="fa fa-trash-o"><a onclick="return confirm(\'Are you sure you want to delete this compose?\');" href="index.php?base=aa_server&controller=compose&compose_action=deletecompose&composeID='+d[0]+'"> Delete</a></li>';
		onclickHtml = onclickHtml + '</ul>';
		return onclickHtml;
	}
	
	function formatComposeHost(d) {
		var host = d[8];
		if (host != "") {
			return host;
		} else {
			return "Did not find information";
		}
	}
	
	if($('#load-composed-server').length){
		$.ajax({
		  url: 'index.php?base=appliance&compose=list',
		})
		.done(function(data) {
			$("#composed-servers").html(data);
			var composed_servers = $("#maestro_composed_table").DataTable( {
				"columns": [
					{ "visible": false },
					null, null, null, null, null, null, null, { "visible": false },
				],
				"order": [], "bLengthChange": false, "pageLength": 10, "search": { "regex": true }, "bAutoWidth": true, "destroy": true,
				"drawCallback": function( oSettings ) {
					$(".toggle-graph a").popover({
						html: true,
						placement: "bottom",
						content: function() {
							var tr = $(this).closest('tr');
							var row = composed_servers.row( tr );
							return formatComposedPopOver(row.data()); //$('#popover-content').html();
						}
					});
					$(".toggle-host a").popover({
						html: true,
						placement: "bottom",
						content: function() {
							var tr = $(this).closest('tr');
							var row = composed_servers.row( tr );
							return formatComposeHost(row.data()); //$('#popover-content').html();
						}
					});
				}
			});
		})
		.fail(function() {
			alert("Failed to load Composed Servers");
		});
	}
</script>