<style>
	#project_tab_ui { display: none; }  /* hack for tabmenu issue */
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
<script src="/cloud-fortis/js/jquery.steps.min.js" type="text/javascript"></script>
<script>
	var budgetpage = true;
	var datepickeryep = true;

function submitallprice() {

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

	var url = '/cloud-fortis/user/index.php?budget=yes';
	var dataval = 'create=1&name='+name+'&limit='+perval+'&date_start="'+date_start+'"&date_end="'+date_end+'"&cpu='+cpu+'&memory='+memory+'&storage='+storage+'&networking='+networking+'&vm='+vm;
	
	$.ajax({
		url : url,
		type: "POST",
		data: dataval,
		cache: false,
		async: false,
		dataType: "html",
		success : function (data) {
			if (data != 'none') {
				if (data == 'works') {
					location.href='index.php?report=report_budget';
				} else {
					alert(data);
				}
			} else {
				alert('Something wrong');
			}
		}
	});
}



$(document).ready(function() {
	var form = $("#create-budget-form");

	form.validate({
		errorPlacement: function errorPlacement(error, element) { element.before(error); },
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
		},
		onStepChanging: function (event, currentIndex, newIndex)
		{
			// form.validate().settings.ignore = ":disabled,:hidden";
			// return form.valid();
			return true;
		},
		onFinished: function (event, currentIndex)
		{
			submitallprice();
		}
	});

	$("#create-budget-modal").on('hidden.bs.modal', function () {
		$(this).find("input[type=text]").val("");
	});

	$('#alertprice').click(function(){
		var percent = parseInt($('#percentbudg').val());
		
		if ( (percent != 'NaN') && (percent <= 100) ) {
			var matched = $("#table-alerts td.perval").filter(function () {
				return $(this).text() == $('#percentbudg').val();
			});

			if (matched.length > 0) {
				alert('The '+percent+"% alert was added previously.");
			} else {
				var row = '<tr><td class="perval">'+percent+'</td><td> <a class="remove-row"><i class="fa fa-close"></i> Remove</a></td></tr>';
				$('#table-alerts').append(row);

				$('.remove-row').on('click', function(){
					$(this).closest('tr').remove();
					var rows =  $("#table-alerts tr");

					if (rows.length <= 1) {
						$('#table-alerts').hide();
					}
				});
			}
		} else {
			alert('Only integer number of percent value and not bigger, than 100, please');
		}
		$('#table-alerts').show();
	});
});


</script>
<div class="cat__content">
	<cat-page>
	<div class="row" id="chart-row">
		<div class="col-sm-12">
			<section class="card">  
				<div class="card-header">
					<span class="cat__core__title d-inline-block" style="min-width: 500px;">
						<label class="d-inline"><strong>Budget Planning</strong></label>
						<!-- <a class="d-inline" id="prev-budget" style="padding: 0 1rem;"><i class="fa fa-backward disabled"></i></a> 
						<h5 class="d-inline" id="budget-name" style="padding: 0 2rem; text-align: center;">BUDGET NAME</h5>
						<a class="d-inline" id="next-budget" style="padding: 0 1rem;"><i class="fa fa-forward"></i></a> -->
						<select id="budgets" class="form-control d-inline" style="max-width: 19rem;"></select>
					</span>
					<div class="pull-right d-inline-block">
					<a id="create-budget" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#create-budget-modal"><i class="fa fa-plus"></i>&nbsp;Create Budget</a>
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
											 <h3 class="panel-title">Spent vs Budget</h3>
										</div>
									</div>
									<div>
										<div id="budget-vs-spent-chart" style="height: 34.7rem;">
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
				<h3>Name & Timeframe</h3>
					<section>
						<div class="col-lg-12">
							<div class="form-group">
								<label for="budgetname">Budget Name</label>
								<input type="text" class="form-control required" id="budgetname">
							</div>
							<div class="form-group">
								<label for="budgetdatestart">Budget Start Date</label>
								<div class="input-group date">
									<input type="text" class="form-control required" id="budgetdatestart">
									<span class="input-group-addon">
										<i class="fa fa-calendar" aria-hidden="true"></i>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label for="budgetdateend">Budget End Date</label>
								<div class="input-group date">
									<input type="text" class="form-control required" id="budgetdateend">
									<span class="input-group-addon">
										<i class="fa fa-calendar" aria-hidden="true"></i>
									</span>
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
												<input type="text" name="input" id="budgetcpu" class="required number">
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
												<input type="text" name="input" id="budgetmemory" class="required number">
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
												<input type="text" name="input" id="budgetstorage" class="required number">
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
												<input type="text" name="input" id="budgetnetwork" class="required number">
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
												<input type="text" name="input" id="budgetvm" class="required number">
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
										<span>Notify me when costs exceed&nbsp;</span><input type="text" id="percentbudg" class="number"><span>&nbsp;% of budgeted costs&nbsp;</span>
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

