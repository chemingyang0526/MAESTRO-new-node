<style>
	#project_tab_ui { display: none; }  /* hack for tabmenu issue */
	/*
	#budgets {
		font-size: 1.25rem;
	}
	*/
	h5 {
		font-size: 1.1rem;
		margin: 1.3rem 0;
	}
	/*
	tbody {
		display: inline-block;
		width: 100%;
		overflow: auto;
		/* height: 15.4rem; 
	}
	tr {
		width: 100%;
		display: table;
	}
	::-webkit-scrollbar {
	width: 5px;
	}
	 
	::-webkit-scrollbar-track {
		-webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.3); 
		border-radius: 3px;
	}
	 
	::-webkit-scrollbar-thumb {
		border-radius: 3px;
		-webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.5); 
	}
	.plan .plan-title {
		font-size: 1.5em;
		font-weight: 100;
	}
	.plan .plan-icon {
		padding: 0.5em 0;
		opacity: 0.4;
	}
	*/
</style>
<link href="/cloud-fortis/css/jquery.steps.css" rel="stylesheet" type="text/css">
<script src="/cloud-fortis/js/c3/d3.v3.min.js" type="text/javascript"></script>
<script src="/cloud-fortis/js/c3/c3.min.js" type="text/javascript"></script>
<script src="/cloud-fortis/js/chartjs/Chart.bundle.min.js" type="text/javascript"></script>
<script src="/cloud-fortis/js/chartjs/utils.js" type="text/javascript"></script>
<script src="/cloud-fortis/js/fetch-report.js" type="text/javascript"></script>
<script src="/cloud-fortis/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/cloud-fortis/js/jquery.steps.js" type="text/javascript"></script>
<script>
	var budgetpage = true;
	var datepickeryep = true;

function submitallprice() {

	var id = $('#budgetid').val();
	var name = $('#budgetname').val();
	var date_start = $('#budgetdatestart').val();
	var date_end = $('#budgetdateend').val();
	var cpu = $('#budgetcpu').val();
	var memory = $('#budgetmemory').val();
	var storage = $('#budgetstorage').val();
	var networking = $('#budgetnetwork').val();
	var vm = $('#budgetvm').val();
	var perval = Array();

	$('.perval').each(function(i){
		perval[i] = $(this).text();
	});

	cpu = parseInt(cpu);
	if (cpu == 'NaN') {
		cpu = 0;
	}

	memory = parseInt(memory);
	if (memory == 'NaN') {
		memory = 0;
	}

	storage = parseInt(storage);
	if (storage == 'NaN') {
		storage = 0;
	}

	networking = parseInt(networking);
	if (networking == 'NaN') {
		networking = 0;
	}

	vm = parseInt(vm);
	if (vm == 'NaN') {
		vm = 0;
	}

	id = parseInt(id);
	if (id == 'NaN') {
		id = 0;
	}

	var action = (id > 0 ? "update" : "create");
	var url = '/cloud-fortis/user/index.php?budget=yes';
	var dataval = action+'=1&name='+name+'&limit='+perval+'&date_start="'+date_start+'"&date_end="'+date_end+'"&cpu='+cpu+'&memory='+memory+'&storage='+storage+'&networking='+networking+'&vm='+vm+(id > 0 ? '&globalid='+id : '');

	var deferred = $.ajax({
		url : url,
		type: "POST",
		data: dataval,
		cache: false,
		async: false,
		dataType: "html" /*,
		success : function (data) {
			if (data.indexOf("Success") > -1) {
				location.reload();
			} else {
				alert('Failed to create or update a budget.');
			}
		} */
	});

	$.when(deferred).done(function (data) { 
		if (data.indexOf("Success") > -1) {
			var url = window.location.href;
			var base_url = url.split('?')[0];
			
			if (action == "update") {
				window.location.href = base_url + "?report=report_budget&budgetid="+id;
			} else {
				window.location.href = base_url + "?report=report_budget&budgetid=-1";
			}
		} else {
			alert('Failed to create or update a budget.');
		}
	});
}

function update_budget(place, budgetid, newval) {
	var url = '/cloud-fortis/user/index.php?budget=yes';
	var dataval = 'editbudgets=1&place='+place+'&globalid='+budgetid+'&editval='+newval;
	// var bds = '';

	var rtrn = $.ajax({
		url : url,
		type: "POST",
		data: dataval,
		cache: false,
		async: false,
		dataType: "html"
	});
	return rtrn;
}

$(document).ready(function() {

	var url = '/cloud-fortis/user/index.php?budget=yes';
	var dataval = 'getbudgets=1';
	var bds = '';

	$.ajax({
		url : url,
		type: "POST",
		data: dataval,
		cache: false,
		async: false,
		dataType: "html",
		success : function (data) {
			if (data != 'none') { 
				bds = JSON.parse(data);
				var i = 0;

				$.each(bds, function(key, serv) {
					$('#budgets').append('<option key="' + key + '" value="' + serv.id +  '">&nbsp;<strong>' + serv.name + '</strong>&nbsp;(' + serv.date_start + '~' + serv.date_end + ')&nbsp;</option>');

					var html =	'<table id="resources-' +  key + '" class="table table-hover table-stripped budget-resources"><tbody>';
					html	+=		'<tr><td style="width: 45%"><strong>' + "CPU:" +			'</strong></td><td style="width: 45%" class="cpu">'	+ serv.cpu +	'</td><td style="width: 10%">$/mon.</td></tr>';
					html	+=		'<tr><td style="width: 45%"><strong>' + "Memory:" + 		'</strong></td><td style="width: 45%" class="memory">'	+ serv.memory +	'</td><td style="width: 10%">$/mon.</td></tr>';
					html	+=		'<tr><td style="width: 45%"><strong>' + "Storage:" + 		'</strong></td><td style="width: 45%" class="storage">'+ serv.storage+	'</td><td style="width: 10%">$/mon.</td></tr>';
					html	+=		'<tr><td style="width: 45%"><strong>' + "Virtualization:" +	'</strong></td><td style="width: 45%" class="vm">'		+ serv.vm +		'</td><td style="width: 10%">$/mon.</td></tr>';
					html	+=		'<tr><td style="width: 45%"><strong>' + "Network:" +		'</strong></td><td style="width: 45%" class="network">'+ serv.network +'</td><td style="width: 10%">$/mon.</td></tr>';
					html	+=		'<tr style="display: none"><td></td><td class="datestart">'	+ serv.date_start	+	'</td><tr>';
					html	+=		'<tr style="display: none"><td></td><td class="dateend">'	+ serv.date_end 	+	'</td><tr>';
					html	+=		'<tr style="display: none"><td></td><td class="id">'		+ serv.id			+	'</td><tr>';
					html	+=		'<tr style="display: none"><td></td><td class="name">'		+ serv.name			+	'</td><tr>';
					html	+=	'</tbody></table>';

					$("#budgets-setting").append(html);

					var html_alerts = '';
					html_alerts +=	'<div class="budget-alerts-box" id="alerts-' +  key + '">';
					html_alerts +=	'<table class="table table-hover table-stripped budget-alerts"' + (serv.havealerts ? '' : ' style="display:none;"') + '>';
					html_alerts +=		'<tbody><tr><td style="width: 50%"><strong>' + "% of Budget" + '</strong></td><td><strong>' + "Action" + '</strong></td></tr>';

					if (serv.havealerts) {
						for (j = 0; j < serv.alerts.length; j++) {
							html_alerts +=	'<tr class="valpercrow"><td style="width: 50%" class="valperc">' + serv.alerts[j] + '</td><td><a href="#" class="removepercent text-danger"><i class="fa fa-minus-circle" aria-hidden="true" style="color:red"></i><u>&nbsp;Remove</u></a></td></tr>';
						}
					}
					html_alerts	+=	'</tbody></table>';
					html_alerts +=	'<h5' + (serv.havealerts ? ' style="display:none;" ' : '') + ' class="budget-alerts">- No alert found for this budget -</h5>';
					html_alerts +=	'</div>';

					$("#budgets-alert").append(html_alerts);
					i++;
				});

				// initial selection of budgets on load
				$(".budget-resources").hide();
				$(".budget-alerts-box").hide();
				var budgetid_on_load = $("#budgetid-on-load").val();
				var elem = null;
				var target_elem = $("#budgets option[value='" + budgetid_on_load + "']");

				if ($(target_elem).length > 0) {
					elem = target_elem;
				} else {
					elem = $("#budgets option:first-of-type");
				}
				
				$(elem).prop("selected", true);
				var key = $(elem).attr("key");
				$("#resources-" + key).show();
				$("#alerts-" + key).show();
				// end initial selection of budgets on load

				$("#budgets-alert").append('<a href="#" id="addpercent" class="text-primary"><i class="fa fa-plus-circle" aria-hidden="true" style="color:blue"></i><u>&nbsp;Add Alert</u></a>');

				$("#budgets").on("change", function () {
					var key = $(this).find("option:selected").attr("key");

					$(".budget-resources").hide();
					$(".budget-alerts-box").hide();
					$("#resources-"+key).show();
					$("#alerts-"+key).show();
					// remove temporary new alert input field row and show add alert button when switching budgets midway through adding new alert
					$("table.budget-alerts").find("tr.new-alert-row").remove();
					$("#addpercent").show();
					$("#chart-type option[value='total']").prop("selected", true);
					plot_budget(bds[key], 'total');
				});

				$("table.budget-alerts").on("click", ".removepercent", function() {

					var budgetid = $('#budgets').val();
					var row = $(this).closest('tr');
					var table = $(this).closest("table.budget-alerts");
					var box = $(this).closest('div.budget-alerts-box');
					var h5 = $(box).find('h5.budget-alerts');
					var percval = $(row).find('.valperc').text();
					$(row).removeClass("valpercrow");
					var deferred = update_budget('percremove', budgetid, percval);

					$.when(deferred).done(function (data) {

						if (data.indexOf("Updated") > -1) {
							$(row).remove();

							if ($(table).find('tr.valpercrow').length == 0) {
								$(table).hide();
								$(h5).show();
							}
						} else {
							alert('Failed to delete this alert from the budget.');
						}
					});
				});

				$("#addpercent").on("click", function() {
					var key = $('#budgets option:selected').attr('key');
					var budgetid = $("#budgets").val();
					var table = $('#alerts-'+key+' table.budget-alerts');
					var h5 = $('#alerts-'+key+' h5.budget-alerts');

					$(table).show().find('tbody').append('<tr class="new-alert-row"><td style="width: 50%"><input class="form-control new-alert"></td><td><a href="#" rel="' + budgetid + '" class="confirm-add-alert btn btn-sm btn-primary" style="margin-top: 0.2rem;">Confirm Alert</a></td></tr>');
					$(h5).hide();
					$(this).hide();
				
					$(".confirm-add-alert").on("click", function () {
						var key = $('#budgets option:selected').attr('key');
						var budgetid = $('#budgets').val();
						var row = $(this).closest('tr');
						var table = $('#alerts-'+key+' table.budget-alerts');
						var newalert = $(this).closest('tr').find("input.new-alert").eq(0);
						var newval = parseInt($(newalert).val());

						if (isNaN(newval) || newval < 0 || newval > 100) {
							// add check for duplicated newval
							alert("Please enter a valid number between 0 to 100");
							$(newalert).val('').focus();
						} else {
							var deferred = update_budget('percadd', budgetid, newval);

							$.when(deferred).done(function (data) {

								if (data.indexOf("Updated") > -1) { 
									$(row).remove();
									$(table).append('<tr class="valpercrow"><td style="width: 50%" class="valperc">' + newval + '</td><td><a href="#" class="removepercent text-danger"><i class="fa fa-minus-circle" aria-hidden="true" style="color:red"></i><u>&nbsp;Remove</u></a></td></tr>').hide().fadeIn('show');
									$("#addpercent").show();
								} else {
									alert('Failed to add this alert for the budget.');
								}
							});
						}
					});
				});

				$("#chart-type").on('change', function () {
					var key = $("#budgets").find("option:selected").attr("key");
					plot_budget(bds[key], $(this).val());
				});

				plot_budget(bds[1], 'total');

			} else {
				$("#budgets-setting").append("<span><strong>No Budget has been set up.</strong></span>");
			}
		}
	});

	var form = $("#create-budget-form");

	form.validate({
		errorPlacement: function errorPlacement(error, element) { element.after(error); },
		rules: {
			field: {
				required: true,
				number: true
			}
		}
	}); 

	form.steps({
		headerTag: "h3",
		bodyTag: "section",
		transitionEffect: "slideLeft",
		onInit: function(event, currentIndex)
		{
			$(".date").datepicker({
				icons: {
					date: "fa fa-calendar",
				}
			});
			$("#budgetname").focus();
		},
		onStepChanging: function (event, currentIndex, newIndex)
		{
			form.validate().settings.ignore = ":disabled,:hidden";
			return $("#create-budget-form input").valid();
		},
		onStepChanged: function (event, currentIndex, priorIndex)
		{
			if (currentIndex == 0) {
				$("#budgetname").focus();
			}
			if (currentIndex == 1) {
				$("#budgetcpu").focus();
			}
			if (currentIndex == 2) {
				$("#percentbudg").focus();
			}
		},
		onFinished: function (event, currentIndex)
		{
			submitallprice();
		}
	});

	$("#create-budget-modal").on('hidden.bs.modal', function () {
		$(this).find("input[type=text]").val("");
		$(this).find("input[type=hidden]").val("");
		/* hacky reset steps */
		$("#create-budget-form-t-0").trigger("click");
		$("#create-budget-form .steps ul li:not(:first)").removeClass("done").addClass("disabled");
		/* end hacky reset steps */
	});

	$("#delete-budget-modal").on('shown.bs.modal', function () {
		$("#confirm-budget-name").text($("#budgets option:selected").text());
	});

	function refresh_table_alerts() {
		var rows =  $("#table-alerts tr.perval-row");

		if (rows.length < 1) {
			$('#table-alerts').hide();
		} else {
			$('#table-alerts').show();
		}
	}

	$('#alertprice').click(function(){
		var percent = parseInt($('#percentbudg').val());
		
		if ( (percent != 'NaN') && (percent <= 100) ) {
			var matched = $("#table-alerts td.perval").filter(function () {
				return $(this).text() == $('#percentbudg').val();
			});

			if (matched.length > 0) {
				alert('The '+percent+"% alert was added previously.");
			} else {
				var row = '<tr class="perval-row"><td class="perval">'+percent+'</td><td> <a class="remove-row"><i class="fa fa-close"></i> Remove</a></td></tr>';
				$('#table-alerts').append(row);
			}
		} else {
			alert('Please input an integer between 0 to 100.');
		}
		$('#percentbudg').val('').focus();
		refresh_table_alerts();
	});

	function plot_budget(serv, type) {
		var budgeted =0;
		if (type == 'total') {
			budgeted = parseInt(serv.cpu) + parseInt(serv.storage) + parseInt(serv.memory) + parseInt(serv.vm) + parseInt(serv.network);
		} else if (type == 'cpu') {
			budgeted = parseInt(serv.cpu)
		} else if (type == 'storage') {
			budgeted = parseInt(serv.storage)
		} else if (type == 'memory') {
			budgeted = parseInt(serv.memory)
		} else if (type == 'networking') {
			budgeted = parseInt(serv.network)
		} else if (type == 'virtualization') {
			budgeted = parseInt(serv.vm)
		}

		var column_x = ['x'];
		var y_budgeted = ['budgeted'];
		var y_spent = ['spent'];
		var date_loop	= new Date(serv.date_start);
		var date_end	= new Date(serv.date_end);
		var today 		= new Date();
		var deferred = [];

		while (date_loop <= date_end) {
			if (date_loop <= today) {
				deferred.push(get_daily_data(parseDate(date_loop, "Y"), parseDate(date_loop, "mon"), parseDate(date_loop, "d"), type));
			}
			column_x.push(parseDate(date_loop, "Y-M-D"));
			y_budgeted.push(budgeted);
			date_loop.setDate(date_loop.getDate() + 1);
		}

		cur_month = '';
		cur_year = '';

		$.when.apply($, deferred).done(function () {
			var objects=arguments;
			var cumulative_cost = 0.0;

			for (var j = 0; j < objects.length; j++) {
				var json = JSON.parse(objects[j][0]);
				// console.log(json.cost, json.date);
				date_loop = new Date(json.date);

				if (cur_year == parseDate(date_loop, "Y") && cur_month == parseDate(date_loop, "mon")) {
					cumulative_cost += parseFloat(json.cost);
				} else {
					cur_year = parseDate(date_loop, "Y");
					cur_month = parseDate(date_loop, "mon");
					cumulative_cost = parseFloat(json.cost);
				}

				y_spent.push(cumulative_cost);
			}
			time_series_chart("#budget-vs-spent-chart", [column_x, y_budgeted, y_spent], type);
		});
	}

	function time_series_chart(bindto, data, type) {

		var chart = c3.generate({
			bindto: bindto,
			data: {
				x: 'x',
				columns: data,
				type: 'spline',
				colors: {
					budgeted: seriesColors[2],
					spent: seriesColors[1]
				},
			},
			axis: {
				x:  {
					type: 'timeseries',
					tick: {
						format: '%Y-%m-%d'
					}
				},
				y:  {
					label: {
						text: type + ' cost ($)'
					}
				}
			},
			grid: {
				y: {
					show: true
				}
			},
			legend: {
				show: true
			},
			tooltip: {
				show: true,
				format: {
					value: function (value, ratio, id) {
						var formatDecimalComma = d3.format(",.2f")
						return "$" + formatDecimalComma(value); 
					}
				}
			}
		});
	}

	$("#create-budget").click(function() {
		$("#create-budget-modal .modal-header h3").text("Create A New Budget");
		$("#create-budget-modal").modal("show");
	});

	$("#edit-budget").click(function() {
		var resources_key = "resources-" + $("#budgets option:selected").attr("key");
		var alerts_key = "alerts-" + $("#budgets option:selected").attr("key");

		$("#" + resources_key + " tr td:nth-of-type(2)").each( function (){
			$("#budget" + $(this).attr('class')).val($(this).text());
		});

		$("#table-alerts").find("tbody tr.perval-row").remove();
		$("#" + alerts_key + " tr td.valperc").each( function (){
			var row = '<tr class="perval-row"><td class="perval">'+$(this).text()+'</td><td> <a class="remove-row"><i class="fa fa-close"></i> Remove</a></td></tr>';
			$("#table-alerts").find("tbody").eq(0).append(row);
		});
		refresh_table_alerts();

		$("#create-budget-modal .modal-header h3").text("Edit Budget");
		$("#create-budget-modal").modal("show");
	});

	$('#delete-budget-confirm').click(function(){
		$('#delete-budget-modal').modal("hide");
		var budget_id = $("#budgets").val();
		var url = '/cloud-fortis/user/index.php?budget=yes';
		var dataval = 'rembudgets=1&remid='+budget_id;
		// var bds = '';

		$.ajax({
			url : url,
			type: "POST",
			data: dataval,
			cache: false,
			async: true,
			dataType: "html",
			success : function (data) {
				if (data == 'remdone') {
					alert('Budget deleted succesfully.');
					location.reload();
				} 
			}
		});
	});

	$('#table-alerts').on('click', '.remove-row', function(){
		$(this).closest('tr').remove();
	});
});
</script>

<div class="cat__content">
	<cat-page>
	<div class="row" id="chart-row">
		<div class="col-sm-12">
			<section class="card">  
				<div class="card-header">
					<span class="cat__core__title">
						<strong>Budget Planning</strong>
						<select id="budgets" class="form-control d-inline" style="max-width: 20rem;"></select>
					</span>
					<div class="pull-right d-inline-block" style="margin-top: 6px;">
						<a href="#" id="delete-budget" data-toggle="modal" data-target="#delete-budget-modal"><span class="text-danger"><u><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Delete Budget</u></span></a>&nbsp;
						<a href="#" id="edit-budget"><span><u><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Budget</u></span></a>&nbsp;
						<a href="#" id="create-budget" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>&nbsp;Create Budget</a>
					</div>
				</div>
				<div class="card-block">
					<div class="row">
						<div class="col-sm-4 dashboard">
							<section class="card">  
								<div class="card-block">
									<div class="panel-heading">
										<div class="panel-control">
											<h3 class="panel-title">Budget Resources</h3>
										</div>
									</div>
									<div>
										<div id="budgets-setting" style="height: 15rem;">
										</div>
									</div>
								</div>
							</section>
							<section class="card">  
								<div class="card-block">
									<div class="panel-heading">
										<div class="panel-control">
											<h3 class="panel-title">Budget Alerts</h3>
										</div>
									</div>
									<div>
										<div id="budgets-alert" style="height: 15rem;">
										</div>
									</div>
								</div>
							</section>
						</div>
						<div class="col-sm-8 dashboard">
							<section class="card">  
								<div class="card-block">
									<div class="panel-heading">
										<div class="panel-control">
											<h3 class="panel-title d-inline">Budgeted vs Spent</h3>
											<select id="chart-type" class="form-control d-inline" style="max-width: 10rem;">
												<option name="total" value="total">Total</option>
												<option name="cpu" value="cpu">CPU</option>
												<option name="memory" value="memory">Memory</option>
												<option name="storage" value="storage">Storage</option>
												<option name="networking" value="networking">Networking</option>
												<option name="virtualization" value="virtualization">Virtualization</option>
											</select>
										</div>
									</div>
									<div>
										<div id="budget-vs-spent-chart" style="height: 35.35rem;">
										</div>
									</div>
								</div>
							</section>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
	</cat-page>
	<input type="hidden" id="budgetid-on-load" value="{budgetid}">
</div>

<div id="delete-budget-modal" class="modal" data-backdrop="static" style="display: none;" aria-hidden="true">
	<div class="modal-content">
		<div class="modal-header">
			<h3 class="text-black">Confirm Deleting A Budget</h3>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to delete this budget?&nbsp;<span id="confirm-budget-name" class="text-danger"><strong></strong></span></p>
		</div>
		<div class="modal-footer">
			<button data-dismiss="modal" class="btn btn-sm btn-default" type="button">Close</button>
			<button id="delete-budget-confirm" class="btn btn-sm btn-danger" type="button">Confirm Delete</button>
		</div>
	</div>
</div>


<div id="create-budget-modal" class="modal" data-backdrop="static" style="display: none;" aria-hidden="true">
	<div class="modal-content">
		<div class="modal-header">
			<h3 class="text-black">Create A New Budget</h3>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<form id="create-budget-form">
				<input type="hidden" id="budgetid">
				<h3>Name & Timeframe</h3>
					<section>
						<div class="col-lg-12">
							<div class="form-group">
								<label for="budgetname">Budget Name</label>
								<input type="text" class="form-control" id="budgetname" required>
							</div>
							<div class="form-group">
								<label for="budgetdatestart">Budget Start Date</label>
								<div class="input-group date">
									<span class="input-group-addon">
										<i class="fa fa-calendar" aria-hidden="true"></i>
									</span>
									<input type="text" class="form-control" id="budgetdatestart" required>
								</div>
							</div>
							<div class="form-group">
								<label for="budgetdateend">Budget End Date</label>
								<div class="input-group date">
									<span class="input-group-addon">
										<i class="fa fa-calendar" aria-hidden="true"></i>
									</span>
									<input type="text" class="form-control" id="budgetdateend" required>
								</div>
							</div>
						</div>
					</section>
				<h3>Budget Limits</h3>
					<section>
						<div class="col-lg-12">
							<div class="row">
								<div class="col-sm-4 col-md-4 col-lg-4 col-xs-4 selectype">
									<div class="panel plan">
										<div class="panel-body">
											<span class="plan-title">CPU</span>
											<div class="plan-icon">
												<!-- <i class="fa fa-desktop"></i> -->
												<img src="/cloud-fortis/img/cpu_s.png" style="max-width: 72px; height: auto;" /> 
											</div>
											<p class="text-muted pad-btm">
												<label for="budgetcpu">Monthly Price Limit in $: </label>
												<input type="text" name="input" id="budgetcpu" required class="number">
											</p>
										</div>
									</div>
								</div>

								<div class="col-sm-4 col-md-4 col-lg-4 col-xs-4 selectype">
									<div class="panel plan">
										<div class="panel-body">
											<span class="plan-title">Memory</span>
											<div class="plan-icon">
											<img src="/cloud-fortis/img/memory_s.png" style="max-width: 72px; height: auto;" /> 
												<!-- <i class="fa fa-database"></i>  -->
											</div>
											<p class="text-muted pad-btm">
												<label for="budgetmemory">Monthly Price Limit in $: </label>
												<input type="text" name="input" id="budgetmemory" required class="number">
											</p>
										</div>
									</div>
								</div>

								<div class="col-sm-4 col-md-4 col-lg-4 col-xs-4 selectype">
									<div class="panel plan">
										<div class="panel-body">
											<span class="plan-title">Storage</span>
											<div class="plan-icon">
												<i class="fa fa-hdd-o"></i>
											</div>
											<p class="text-muted pad-btm">
												<label for="budgetstorage">Monthly Price Limit in $: </label>
												<input type="text" name="input" id="budgetstorage" required class="number">
											</p>
										</div>
									</div>
								</div>

								<div class="col-sm-4 col-md-4 col-lg-4 col-xs-4 selectype">
									<div class="panel plan">
										<div class="panel-body">
											<span class="plan-title">Networking</span>
											<div class="plan-icon">
												<!-- <i class="fa fa-globe"></i> -->
												<img src="/cloud-fortis/img/network_s.png" style="max-width: 72px; height: auto;" />
											</div>
											<p class="text-muted pad-btm">
												<label for="budgetnetwork">Monthly Price Limit in $: </label>
												<input type="text" name="input" id="budgetnetwork" required class="number">
											</p>
										</div>
									</div>
								</div>

								<div class="col-sm-4 col-md-4 col-lg-4 col-xs-4 selectype">
									<div class="panel plan">
										<div class="panel-body">
											<span class="plan-title">Virtualization</span>
											<div class="plan-icon">
												<!-- <i class="fa fa-cloud"></i> -->
												<img src="/cloud-fortis/img/virtualization_s.png" style="max-width: 72px; height: auto;" />
											</div>
											<p class="text-muted pad-btm">
												<label for="budgetvm">Monthly Price Limit in $: </label>
												<input type="text" name="input" id="budgetvm" required class="number">
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
				<h3>Alerts</h3>
					<section>
						<div class="col-lg-12">
							<div class="panel plan">
								<div class="panel-body">
									<div class="form-group">
										<span>Notify me when costs exceed&nbsp;</span><input type="text" id="percentbudg" number><span>&nbsp;% of the budgeted costs.&nbsp;</span>
										<div class="d-inline pull-right"><a class="btn btn-sm btn-primary" id="alertprice">Create Alert</a></div>
									</div>
								</div>
								<div class="panel-body">
									<table class="table table-bordered table-stripped table-hover" id="table-alerts" style="display: none;">
										<tbody>
											<tr class="header"><th>% of budget</th><th>Action</th></tr>
										</tbody>
									</table>
								</div>
								<div style="display: none;">
									<a href="#" class="submitallprice">Submit</a> 
								</div>
							</div>
						</div>
					</section>
			</form>
		</div>
	</div>
</div>