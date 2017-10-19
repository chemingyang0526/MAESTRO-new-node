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
		<div class="compose-header"><h2>Edit Composed Server</h2></div>
		<!-- <div class="compose-button"><a id="clickButton" href="index.php?base=aa_server&controller=compose#composeModal" data-backdrop="static" data-keyboard="false" type="button" class="add btn-labeled fa fa-plus" data-toggle="modal" data-target="#composeModal">Add Composed Server</a></div> -->
		<div id="composed-servers">
			<p class="compose name">Compose server name: {compose_name}</p>
			<form action="{thisfile}" method="GET">
				<input type="hidden" name="compose_id" value="{compose_id}">
				{compose_status}
				<p class="compose">Hosts included:</p>
				{form}
				<p class="compose">Hosts available:</p>
				<input class="text" onkeyup="ajax_filter()" ;="" id="ajax_host_filter" name="host_filter" type="text" placeholder="Search host ..." />
				<div class="available-hosts pre-scrollable">
					{form_f}
				</div>
				<div id="buttons">{submit}&#160;{cancel}</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	function ajax_filter () {
		//alert ("Key Pressed");
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
</script>