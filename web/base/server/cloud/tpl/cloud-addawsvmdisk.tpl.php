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


<h2 class="inner">{label}</h2>

<div class="row">
	
	<div id="add-virtual-machine">
		<div class='multi-cloud'>
			<form action="{thisfile}" method="GET">
			{form}
			{base}
			{memory}
			{operating_system}
			{vcpu}
			{disk_volume}
			{disk_volume_type}
			{aws_package}
			{vm_monthly_price}
			<div id="buttons">
				<a class="add btn-labeled fa fa-calculator" onclick="createvm(); return false;" href="#"><span class="halflings-icon white plus-sign"><i></i></span>Calculate price</a> {submit} {cancel}
			</div>
			</form>
		</div>
	</div>
</div>

<div id="volumepopup" class="modal-dialog volumepopup3">
	<div class="panel">
		<!-- Classic Form Wizard -->
		<!--===================================================-->
		<div id="demo-cls-wz">
			<!--Nav-->
			<ul class="wz-nav-off wz-icon-inline wz-classic">
				<li class="col-xs-3 bg-info active"><a href="#demo-cls-tab1" data-toggle="tab" aria-expanded="true"><span class="icon-wrap icon-wrap-xs bg-trans-dark"><i class="fa fa-hdd-o"></i></span> New Storage </a></li>
				<div class="volumepopupclass"><a id="volumepopupclose"><i class="fa fa-icon fa-close"></i></a></div>
			</ul>
			<!--Progress bar-->
			<div class="progress progress-sm progress-striped active">
				<div class="progress-bar progress-bar-info" style="width: 100%;"></div>
			</div>

			<!--Form-->
			<form class="form-horizontal mar-top">
				<div class="panel-body">
					<div class="tab-content">
						<!--First tab-->
						<div class="tab-pane active in" id="demo-cls-tab1"><div id="storageform"></div></div>
					</div>
				</div>
			</form>
		</div>
		<!--===================================================-->
		<!-- End Classic Form Wizard -->
	</div>
</div>
<script type="text/javascript">
	function createvm(){
		var base						= $('#base').find(":selected").text();
		var memory 						= $('#memory').val();
		var operating_system 			= $('#operating_system').val();
		var vcpu 						= $('#vcpu').val();
		var disk_volume 				= $('#disk_volume').find(":selected").val();
		var disk_volume_type			= $('#disk_volume_type').find(":selected").val();
		var vm_monthly_price			= $('#vm_monthly_price').val();
		
		var price = 0;
		price = price + parseFloat (vm_monthly_price);
		
		if (disk_volume == ""){
			alert ("Select a disk size");
			return false;
		} else if (disk_volume_type == "") {
			alert ("Select a disk type");
			return false;
		} else {
			if (disk_volume_type == "sc1") {
				price = price + (disk_volume * 0.025);
			} else if (disk_volume_type == "st1") {
				price = price + (disk_volume * 0.045);
			} else if (disk_volume_type == "io1") {
				price = price + (disk_volume * 0.125);
			} else if (disk_volume_type == "gp2") {
				price = price + (disk_volume * 0.1);
			}
			$('#vm_monthly_price').val(price);
			//alert ("Based on your selection monthly cost for the virtual machine is $" + price + " (price is calculated in USD)");
		}
		return false;
	}
	
	$(document).ready(function(){ });
</script>