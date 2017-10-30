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

<style>
	#demo-set-btn {
		display: none;
	}
	#compose_tab {
		display: none;
	}
</style>

<div id="prenutanix">
	<div class="row">
		<div class="compose-header"><h2>Maestro Compose</h2></div>
		<div class="compose-button"><a id="clickButton" href="index.php?base=aa_server&controller=compose#composeModal" data-backdrop="static" data-keyboard="false" type="button" class="add btn-labeled fa fa-plus" data-toggle="modal" data-target="#composeModal">Add Composed Server</a></div>

		<div id="composed-servers">
			{compose_servers}
		</div>
	</div>
</div>

<!-- Edit Popup -->

<div id="volumepopup" class="modal-dialog">
	<div class="panel">
		<!-- Classic Form Wizard -->
		<!--===================================================-->
		<div id="demo-cls-wz">
			<!--Nav-->
			<ul class="wz-nav-off wz-icon-inline wz-classic">
				<li class="col-xs-3 bg-info active">
					<a href="#demo-cls-tab1" data-toggle="tab" aria-expanded="true"><span class="icon-wrap icon-wrap-xs bg-trans-dark"><i class="fa fa-music"></i></span> Edit Compose</a>
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

<!-- Modal -->
<div id="composeModal" class="modal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Compose Maestro Servers</h4>
			</div>
			<div class="modal-body">
					
				<ul class="compose-form-menu">
					<li class="profile active">Profile</li>
					<li class="host-type">Host type</li>
					<li class="server-type">Server type</li>
					<li class="summary">Summary</li>
				</ul>
					
				<form name="composeform" id="composeform" method="GET" action="{thisfile}">
						
					<div id="sf1" class="frm">
						<fieldset>
							<div class="form-group">
								<label class="col-lg-2 control-label" for="uname">Compose Name: </label> <br />
								<div class="col-lg-6 compose-name">
									<input type="text" placeholder="Your Name" id="uname" name="uname" class="form-control compose-name" autocomplete="off">
								</div>
							</div>
							<div class="clearfix" style="height: 10px;clear: both;"></div>
							<div class="form-group">
								<label class="col-lg-2 control-label" for="uname">Type: </label> <br />
								<div class="col-lg-6">
									<select id="host-type-selection" class="form-control required">
										<option value=""> -- </option>
										<option value="local"> Local </option>
										<option value="cloud"> Cloud </option>
									</select>
								</div>
							</div>
							<div class="clearfix" style="height: 30px;clear: both;"></div>

							<div class="form-group">
								<div class="col-lg-10 col-lg-offset-2 button-holder">
									<button class="btn btn-primary open1 btn-rounded" type="button">Next <span class="fa fa-arrow-right"></span></button> 
								</div>
							</div>
						</fieldset>
					</div>

					<div id="sf2" class="frm" style="display: none;">
						<fieldset>
							<div class="form-group">
								<h4>Local Host</h4>
								<div class="col-lg-6">
										
								</div>
							</div>
							<div class="clearfix" style="height: 30px;clear: both;"></div>
							<div class="form-group">
								<div class="col-lg-10 col-lg-offset-2 button-holder">
									<button class="btn btn-primary back2 btn-rounded" type="button"><span class="fa fa-arrow-left"></span> Back</button> 
									<button class="btn btn-primary open2 btn-rounded" type="button">Next <span class="fa fa-arrow-right"></span></button> 
								</div>
							</div>
						</fieldset>
					</div>

					<div id="sf3" class="frm" style="display: none;">
						<fieldset>
							<div class="form-group">
								<h4>Available Servers</h4>
								<div class="col-lg-6">
										
								</div>
							</div>

							<div class="clearfix" style="height: 30px;clear: both;"></div>
							<div class="form-group">
								<div class="col-lg-10 col-lg-offset-2 button-holder">
									<button class="btn btn-primary back3 btn-rounded" type="button"><span class="fa fa-arrow-left"></span> Back</button>
									<button class="btn btn-primary open3 btn-rounded" type="button">Next <span class="fa fa-arrow-right"></span></button>
								</div>
							</div>
						</fieldset>
					</div>
						
					<div id="sf4" class="frm" style="display: none;">
						<fieldset>
							<div class="form-group">
								<h4>Compose Summary</h4>
								<div class="col-lg-6">
									<table class="table table-hover summary-table">
										<tr>
											<td>Composed name</td><td>:</td><td><span id="summary-name"></span></td>
										</tr>
										<tr>
											<td>Compose type</td><td>:</td><td><span id="summary-compose-type"></span></td>
										</tr>
										<tr>
											<td>Server type</td><td>:</td><td><span id="summary-server-type"></span></td>
										</tr>
										<tr>
											<td>Total CPU</td><td>:</td><td><span id="summary-total-cpu"></span></td>
										</tr>
										<tr>
											<td>Total memory</td><td>:</td><td><span id="summary-total-memory"></span></td>
										</tr>
									</table>
								</div>
							</div>
							<div class="clearfix" style="height: 30px;clear: both;"></div>
							<div class="form-group">
								<div class="col-lg-10 col-lg-offset-2 button-holder">
									<button class="btn btn-primary back4 btn-rounded" type="button"><span class="fa fa-arrow-left"></span> Back</button> 
									<button class="btn btn-primary open4 btn-rounded" type="button">Submit </button> 
									<img src="/htvcenter/base/server/aa_server/css/spinner.gif" alt="" id="loader" style="display: none">
								</div>
							</div>
						</fieldset>
					</div>
						
				</form>
					
			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
			</div>
		</div>
	</div>
</div>

<link href="/cloud-fortis/css/vender/bootstrap/css/utilities.css" rel="stylesheet" type="text/css">
<link href="/cloud-fortis/css/vender/bootstrap/css/card.css" rel="stylesheet" type="text/css">
<link href="/cloud-fortis/designplugins/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js" type="text/javascript"></script>

<script type="text/javascript">
function showPopup(id){
	var editLink = 'index.php?base=aa_server&controller=compose&compose_action=editcompose&composeID='+id;
	$('#storageformaddn').load(editLink+" #composed-servers", function(){
		$('.lead').hide();
		$('#storageformaddn select').selectpicker();
		$('#storageformaddn select').hide();
		var heder = $('#appliance_tab0').find('h2').text();
		if (heder == 'ServerAdd a new Server') {
			$('#storageformaddn').find('#name').css('left','-20px');
		}
		$('#storageformaddn').find('#info').remove();
		$('#demo-cls-wz ul.wz-classic li.bg-info').html('<a href="#demo-cls-tab1" data-toggle="tab" aria-expanded="true"><span class="icon-wrap icon-wrap-xs bg-trans-dark"><i class="fa fa-music"></i></span> Edit Compose</a>');
		$('#volumepopupaddn').show();
	});
	event.preventDefault();
}	

function ajax_filter () {
	var value = $("#ajax_host_filter").val();
	value = value.toLowerCase();
	$(".available-hosts div.htmlobject_box").each(function () {
		if (value == ""){
			$(this).show();
		} else {
			var label_value = $(this).text();
			if (label_value.indexOf(value) >= 0 ){
				$(this).show();
			} else {
				$(this).hide();
			}
		}
	});
}

$('.modal').on('hidden.bs.modal', function(){
	$(this).find('form')[0].reset();
});

function serverlists(type, profile) {
	$.ajax({
	  url: 'index.php?base=aa_server&controller=compose&request='+type+'&profile='+profile,
	})
	.done(function(d) {
		var serverList = jQuery.parseJSON(d);
		if (serverList.length > 0) {
			var count = 1;
			var htmlTable = '<div class="servers-to-compose"><table class="table table-hover">';
			htmlTable = htmlTable + '<tr><th>Host Name</th><th>Memory (GiB)</th><th>CPU</th></tr>';
			for (var i = 0 ; i < serverList.length ; i++){
				htmlTable = htmlTable + '<tr><td><input name="appliance[]" class="form-control check-required required" id="box'+count+'" type="checkbox" value="'+serverList[i]['resource_id']+' - '+serverList[i]['resource_cpunumber']+' - '+ serverList[i]['resource_memtotal'] +'" /><label for="box'+count+'">'+serverList[i]['resource_hostname']+'</label></td>';
				htmlTable = htmlTable + '<td>'+serverList[i]['resource_memtotal']+'</td><td>'+serverList[i]['resource_cpunumber']+'</td></tr>';
				count = count + 1;
			}
			htmlTable = htmlTable + '</table></div>';
			$("#sf3 .col-lg-6").html(htmlTable);
		} else { 
			$("#sf3 .col-lg-6").html('<div class="servers-to-compose">Sorry, no host found of '+type.toUpperCase()+' type.</div>');
		}
	})
	.fail(function() {
		alert("Failed to load");
	});
}

function composeNameExists (composeName) {
	var succeed = false;
	$.ajax({
		async: false,
		url: 'index.php?base=aa_server&controller=compose&checkuname='+composeName,
		success: function(d) {
			if (d == true)
				succeed = true;
			else
				succeed = false;
		}
	});
	return succeed;
}

jQuery().ready(function() {
	var v = jQuery("#composeform").validate({
		rules: {
			uname: {
				required: true,
			}
		},
		errorElement: "span",
		errorClass: "has-error",
	});
	var cloudHostType = '<div class="cc-selector-2">'+
		'<input id="mastercard23" type="radio" name="creditcard" value="aws" />'+
		'<label class="drinkcard-cc aws" for="mastercard23">AWS</label>'+
		'<input checked="checked" id="mastercard24" type="radio" name="creditcard" value="azure" />'+
		'<label class="drinkcard-cc azure" for="mastercard24">Azure</label></div>';
	
	var localHostType = '<div class="cc-selector-2">'+
		'<input id="mastercard2" type="radio" name="creditcard" value="physical" />' +
		'<label class="drinkcard-cc mastercard" for="mastercard2">Physical&nbsp;Host</label>' +
		'<input checked="checked" id="mastercard22" type="radio" name="creditcard" value="och" />' +
		'<label class="drinkcard-cc mastercard2" for="mastercard22">OCH&nbsp;Host</label>'+
		'<input id="visa2" type="radio" name="creditcard" value="vmware" />'+
		'<label class="drinkcard-cc visa" for="visa2">VMware&nbsp;Host</label></div>';
		
	$(".open1").click(function() {
		var composeName = $("#uname").val();
		var cExists = composeNameExists(composeName);
		if(!cExists) {
			$('#sf1 .compose-name .has-error').remove();
			$("#sf1 .compose-name").append('<span class="has-error">Compose name is already in use.</span>');
			return false;
		}
		
		if (v.form() && cExists) {
			$(".frm").hide();
			$("#sf2").show();
			$(".profile").removeClass('active');
			$(".host-type").addClass('active');
			//console.log("123");
			if ($("#host-type-selection").val() == "cloud"){
				$("#sf2 h4").text("Cloud Host");
				$(".host-type").text('Cloud Type');
				$("#sf2 .col-lg-6").html(cloudHostType);
			} else {
				$("#sf2 h4").text("Local Host");
				$(".host-type").text('Host Type');
				$.ajax({
				  url: 'index.php?base=aa_server&controller=compose&enabledPlugin=1',
				})
				.done(function(d) {
					var pluginList = jQuery.parseJSON(d);
					if (pluginList.length > 0) {
						var htmlTable = '<div class="cc-selector-2">';
						
						htmlTable = htmlTable + '<input id="mastercard02" type="radio" name="creditcard" value="physical" />' +
						'<label class="drinkcard-cc mastercard02" for="mastercard02">Physical&nbsp;Host</label>';
						
						if (pluginList.indexOf('OCH') >= 0) {
							htmlTable = htmlTable + '<input checked="checked" id="mastercard22" type="radio" name="creditcard" value="och" />' +
							'<label class="drinkcard-cc mastercard2" for="mastercard22">OCH&nbsp;Host</label>';
						}
						if (pluginList.indexOf('ESX') >= 0) {
							htmlTable = htmlTable + '<input id="visa2" type="radio" name="creditcard" value="vmware" />'+
							'<label class="drinkcard-cc visa" for="visa2">ESX&nbsp;Host</label>';
						}
						if (pluginList.indexOf('VSphere') >= 0) {
							htmlTable = htmlTable + '<input id="visa22" type="radio" name="creditcard" value="vsphere" />'+
							'<label class="drinkcard-cc visa3" for="visa22">VSphere&nbsp;Host</label>';
						}
						htmlTable = htmlTable + '</div>';
						$("#sf2 .col-lg-6").html(htmlTable);
					} else { 
						$("#sf2 .col-lg-6").html('<div class="servers-to-compose">Sorry, no host found.</div>');
					}
				})
				.fail(function() {
					alert("Failed to load");
				});
			}
			
		}
	});

	$(".open2").click(function() {
		if (v.form()) {
			$(".frm").hide();
			$("#sf3").show();
			$(".host-type").removeClass('active');
			$(".server-type").addClass('active');
			var hostTypeVal = $('input[name=creditcard]:checked').val();
			//console.log(hostTypeVal);
			if (hostTypeVal == "physical") {
				$("#sf3 h4").text("Physical Hosts");
				serverlists('physical', 'local');
			} else if (hostTypeVal == "aws") {
				$("#sf3 h4").text("AWS Hosts");
				var awsServer = '<div class="servers-to-compose"><p id="load-aws-instance"><i class="fa fa-spinner fa-spin fa-lg" aria-hidden="true"></i> loading</p></div>';
				$("#sf3 .col-lg-6").html(awsServer);
				serverlists('aws', 'cloud');
			} else if (hostTypeVal == "azure") {
				$("#sf3 h4").text("Azure Hosts");
				var azServer = '<div class="servers-to-compose"><p id="load-aws-instance"><i class="fa fa-spinner fa-spin fa-lg" aria-hidden="true"></i> loading</p></div>';
				$("#sf3 .col-lg-6").html(azServer);
				serverlists('az', 'cloud');
			} else if (hostTypeVal == "och") {
				$("#sf3 h4").text("OCH Hosts");
				serverlists('och', 'local');
			} else if (hostTypeVal == "vmware") {
				$("#sf3 h4").text("VMware Hosts");
				serverlists('vmware', 'local');
			} else if (hostTypeVal == "vsphere") {
				$("#sf3 h4").text("VSphere Hosts");
				serverlists('vsphere', 'local');
			} else {
				$("#sf3 h4").text("Servers - Errors!");
				$("#sf3 .col-lg-6").html("<p>An error occured!</p>");
			}
		}
	});

	$(".open3").click(function() {
		var atLeastOneIsChecked = false;
		var count = 0;
		$('.check-required').each(function () {
			if ($(this).is(':checked')) {
				count = count + 1;
				if (count > 1) {
					atLeastOneIsChecked = true;
					return false;
				}
			}
		});
		if (atLeastOneIsChecked) {
			if (v.form()) {
				$(".frm").hide();
				$("#sf4").show();
				$(".server-type").removeClass('active');
				$(".summary").addClass('active');
			
				$("#summary-name").text($("#uname").val());
				var localCloud = $("#host-type-selection").val();
				var hostType = $('input[name=creditcard]:checked').val();
				$("#summary-compose-type").text(localCloud);
				$("#summary-server-type").text(hostType.toUpperCase());
				
				var applianceID = "";
				var totalMemory = 0;
				var totalCPU = 0;
				$('.check-required').each(function () {
					if ($(this).is(':checked')) {
						var temp = $(this).val();
						totalMemory = totalMemory + parseFloat (temp.split(" - ")[2] );
						totalCPU = totalCPU + parseInt (temp.split(" - ")[1] );
					}
				});
				$("#summary-total-cpu").text(totalCPU);
				$("#summary-total-memory").text(totalMemory);
			}
		} else {
			$('.has-error').remove();
			$("#sf3 .col-lg-6").append('<span class="has-error">Check at least two hosts to proceed.</span>');
			return false;
		}
	});

	$(".open4").click(function() {
		if (v.form()) {
			$("#loader").show();
			var maestroComposeName = $("#uname").val();
			var maestroComposeType = $("#summary-compose-type").text() + ', ' + $("#summary-server-type").text();
			var composeTotalMemory = $("#summary-total-memory").text();
			var composeTotalCpu = $("#summary-total-cpu").text();
			
			var applianceID = "";
			$('.check-required').each(function () {
				if ($(this).is(':checked')) {
					var temp = $(this).val();
					applianceID = applianceID + temp.split(" - ")[0] +',';
				}
			});
			
			$.ajax({
				url: 'index.php?base=aa_server&controller=compose&dbinsert=1&maestroComposeName='+maestroComposeName+'&maestroComposeType='+maestroComposeType+'&composeTotalMemory='+composeTotalMemory+'&composeTotalCpu='+composeTotalCpu+'&applianceID='+applianceID,
			}).done(function(d) {
				var composeProperties = jQuery.parseJSON(d);
				$("#composeform").html('<br /><h2>'+maestroComposeName+' has been created.</h2><br />');
			}).fail(function() {
				alert("Failed to load");
			});
			
			setTimeout(function(){
				$("#composeModal").modal('hide');
				$('#composeModal').on('hidden.bs.modal', function(){
					$(':input','#composeform').val("");
				});
				window.location.href = "index.php?base=aa_server&controller=compose";
			}, 2000);
			return false;
		}
	});

	$(".back2").click(function() {
		$(".frm").hide();
		$("#sf1").show();
		$(".host-type").removeClass('active');
		$(".profile").addClass('active');
	});

	$(".back3").click(function() {
		$(".frm").hide();
		$("#sf2").show();
		$(".server-type").removeClass('active');
		$(".host-type").addClass('active');
	});

	$(".back4").click(function() {
		$(".frm").hide();
		$("#sf3").show();
		$(".summary").removeClass('active');
		$(".server-type").addClass('active');
	});
	
	function formatPopOver (d) {
		var composeID = $(d[0]).text();
		var onclickHtml = '<ul class="compose-pop-over-menu">';
		onclickHtml = onclickHtml + '<li class="fa fa-edit"><a onclick="showPopup('+d[0]+');" href="index.php?base=aa_server&controller=compose&compose_action=editcompose&composeID='+d[0]+'"> Edit</a></li>';
		onclickHtml = onclickHtml + '<li class="fa fa-trash-o"><a onclick="return confirm(\'Are you sure you want to delete this compose?\');" href="index.php?base=aa_server&controller=compose&compose_action=deletecompose&composeID='+d[0]+'"> Delete</a></li>';
		onclickHtml = onclickHtml + '</ul>';
		return onclickHtml;
	}

	var dt = $("#maestro_composed_table").DataTable( {
		"columns": [
			{ "visible": false },
			null, null, null, null, null, null, null,
		],
		"order": [], "bLengthChange": false, "pageLength": 10, "search": { "regex": true }, "bAutoWidth": true, "destroy": true,
		"drawCallback": function( oSettings ) {
			$(".toggle-graph a").popover({
				html: true,
				placement: "bottom",
				content: function() {
					var tr = $(this).closest('tr');
					var row = dt.row( tr );
					return formatPopOver(row.data());
				}
			});
		}
	});
});
</script>