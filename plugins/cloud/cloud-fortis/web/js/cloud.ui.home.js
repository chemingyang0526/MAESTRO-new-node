/* handle IE missing map function */
if (!Array.prototype.map)
{
	Array.prototype.map = function(fun)
	{
		var len = this.length;
		if (typeof fun != "function")
			throw new TypeError();
		var res = new Array(len);
		var thisp = arguments[1];
		for (var i = 0; i < len; i++)
		{
			if (i in this)
			res[i] = fun.call(thisp, this[i], i, this);
		}
		return res;
	};
}

var seriesColors = [
	'#dfdfdf',
	'#41bee9',
	chartColors.red,
	chartColors.yellow,
	chartColors.green,
	chartColors.orange
	];

function get_limits() {
	var this_month = new Date();
	var last_month = new Date();
	var next_month = new Date();
	this_month.setDate(1);
	last_month.setDate(1);
	last_month.setMonth(this_month.getMonth()-1);
	next_month.setMonth(this_month.getMonth()+1);
	next_month.setDate(1);

	var last_month_deferred = get_monthly_data(parseDate(last_month,'Y'), parseDate(last_month,'mon'));
	var this_month_deferred = get_monthly_data(parseDate(this_month,'Y'), parseDate(this_month,'mon'));
	// var next_month_deferred = get_monthly_data(next_month.getFullYear(), next_month.toLocaleString("en-us", { month: "short" }));

	var last_month_data = null;
	var this_month_data = null;

	$.when( last_month_deferred, this_month_deferred).done(function ( v1, v2, v3 ) {

		last_month_data = JSON.parse(v1[0]);
		this_month_data = JSON.parse(v2[0]);
		
		last_month_cost = parseFloat(to_num(last_month_data.all));
		this_month_cost = parseFloat(to_num(this_month_data.all));
		next_month_cost = (last_month_cost > 0 && this_month_cost > 0 ? (this_month_cost + last_month_cost) / 2 : (this_month_cost > 0 ? this_month_cost : 0 ));

		var monthly_cost_response = [
			[last_month, last_month_cost], 
			[this_month, this_month_cost], 
			[next_month, next_month_cost] 
		];

		make_current_monthly_billing("monthlybilling", this_month_data, "$");
		make_monthly_chart('monthlychart', monthly_cost_response, "$");
	});

	var response_deferred = $.ajax({
		url : "api.php?action=limits",
		type: "POST",
		cache: false,
		async: false,
		dataType: "json"
	});
	
	$.when(response_deferred).done(function (v1) {
		var response = v1;
		make_doughnut('systems', response.systems_list,"");
		make_gauge('disk', response.disk_list, "");
		make_gauge('memory', response.memory_list, "");
		make_gauge('cpu' , response.cpu_list, "");
		make_gauge('network', response.network_list, "");
	});
}

function make_current_monthly_billing(binding, data, units) {
	// chart.js polar area chart
	var labels = ["cpu","storage","memory","virtualization","networking"];
	var numbers = [to_num(data.cpu), to_num(data.storage), to_num(data.memory), to_num(data.virtualization), to_num(data.networking)];
	var max = Math.max.apply(null, numbers);
	var min = max / 8;
	var normalized_numbers = [Math.max(numbers[0],min), Math.max(numbers[1],min), Math.max(numbers[2],min), Math.max(numbers[3],min), Math.max(numbers[4],min)];
	var legend_arr = [[data.cpu,"cpu"],[data.storage,"storage"],[data.memory,"memory"],[data.virtualization,"virtualization"],[data.networking,"networking"]];
	var legend = renderDonutLegend(legend_arr, binding);
	$('#chartdiv-inventory-'+binding+'-legend').html('');
	$('#chartdiv-inventory-'+binding+'-legend').append(legend);
	var color = Chart.helpers.color;
	var config = {
		data: {
			datasets: [{
				data: normalized_numbers,
				backgroundColor: [
					color(seriesColors[0]).rgbString(),
					color(seriesColors[1]).rgbString(),
					color(seriesColors[2]).rgbString(),
					color(seriesColors[3]).rgbString(),
					color(seriesColors[4]).rgbString()
				],
				label: 'dollars ($)' // for legend
			}],
			labels: labels
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			legend: {
				display: false,
			},
			title: {
				display: false,
				// text: 'Mon'
			},
			scale: {
				display: true,
				reverse: false,
				ticks: {
					callback: function(value, index, values) {
						return '';
					}
				}
			},
			animation: {
				animateRotate: true,
				animateScale: true
			},
			layout: {
				padding: {
					left: 5,
					right: 5,
					top: 5,
					bottom: 5
				}
			},
			tooltips: {
				enabled: true,
				callbacks: {
					label: function(tooltipItems, data) {
						return data.labels[tooltipItems.index] +': $' + numbers[tooltipItems.index];
					}
				} 
			}
		}
	};


	var ctx = document.getElementById("chartdiv-inventory-"+binding);
	window.myPolarArea = Chart.PolarArea(ctx, config);
	$("#monthlybilling-total").text(data.all);

} 

function make_monthly_chart(binding, monthlydata, units) {

	var bindto = "#chartdiv-inventory-" + binding; 
	var x_column = ['x'];
	var y_column = ['cost'];
	var legend_arr = []

	for (var i = 0; i < monthlydata.length; i++) {
		x_column.push(monthlydata[i][0]);
		y_column.push(monthlydata[i][1]); 
		legend_arr.push(['$'+monthlydata[i][1], parseDate(monthlydata[i][0], "mon")+' '+parseDate(monthlydata[i][0], "Y")+" "]);
	}

	var legend = renderDonutLegend(legend_arr, binding);
	$('#chartdiv-inventory-'+binding+'-legend').html('');
	$('#chartdiv-inventory-'+binding+'-legend').append(legend);

	var chart = c3.generate({
		bindto: bindto,
		data: {
		x: 'x',
			columns: [
				x_column,
				y_column
				
			],
			type: 'bar',
			color: function (color, d) {
				return seriesColors[d.index];
			}
		},
		axis: {
			x: {
				type: 'timeseries',
				tick: {
					format: '%Y-%b'
				}
			},
			y: {
				label: 'total cost ($)',
				position: 'inner-middle'
			}
		},
		bar: {
			width: {
				ratio: 0.5 // this makes bar width 50% of length between ticks
			}
		},
		legend: {
			 show: false
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

function renderValue(v,binding) {
	if (binding == "memory" || binding == "disk") {
		if(v[1] < 1000) {
			var res = Math.round(v[1]);
			size = res+ ' MB';
		} else {
			var res = Math.round(v[1]/1000);
			size = (res)+ ' GB';
		}
	} else {
		size = v[1]+'';
	}
	return size;
}


function renderDonutLegend(values,binding) {
	var legend = $('<ul>').addClass((binding == "monthlybilling" ? "center" : ""));
	var size = '';

	$.each(values, function(k,v) {

		legend.append(
			$('<li>').append(
				$('<div>').addClass('legend-tile').attr('style', 'background:' + seriesColors[k])
			).append(renderValue(v,binding) + ' ' + v[0])
		);
	});
	return legend;
}

function renderdon(values) {
	var legend = [];

	$.each(values, function(k,v) {
		var spliters = v[0].split(' ');
		var last = '';
		spliters.forEach(function(entry){
			last = entry;
		});
		
		var val = v[1];
		if(val > 1000) {
			val = val/1000;
		}

		var res = Math.round(val);
		legend.push({label: last, value: res});
	})
	return legend;
}

function make_gauge(binding, values, units) {

	var bindto = "#chartdiv-inventory-" + binding; 
	lang = 'lang_'+binding;
	var titlo = eval(lang);
	$('#chartdiv-inventory-'+binding).closest('.dashboard').find('.panel-title').text(titlo);
	var max_val = values[0][1] + values[1][1];

	var legend = renderDonutLegend([values[0],values[1]], binding);
	$('#chartdiv-inventory-'+binding+'-legend').html('');
	$('#chartdiv-inventory-'+binding+'-legend').append(legend);



	var chart = c3.generate({
		bindto: bindto,
		data: {
			columns: [ values[1] ],
			labels: true,
			type: 'gauge',
			onclick: function (d, i) { /* console.log("onclick", d, i); */ },
			onmouseover: function (d, i) { /* console.log("onmouseover", d, i); */},
			onmouseout: function (d, i) { /* console.log("onmouseout", d, i); */}
		},
		gauge: {
			max: max_val,
			label: {
				format: function(value, ratio) {
					return renderValue(values[1],binding);
				},
				show: true
			},
			units: units
		},
		color: {
			pattern: [seriesColors[1]], // the three color levels for the percentage values.
		},
		legend: {
			show: true,
			position: 'top'
		},	
		transition: {
			duration: 1500
		},
		tooltip: {
			show: true,
			format: {
				value: function (value, ratio, id, index) { return value; }
			}
		}
	});
}

function make_doughnut(binding, donutdata, units) {
	
	var bindto = "#chartdiv-inventory-" + binding; 
	lang = 'lang_'+binding;
	var titlo = eval(lang);
	$('#chartdiv-inventory-'+binding).closest('.dashboard').find('.panel-title').text(titlo);

	var legend = renderDonutLegend(donutdata, binding);
	$('#chartdiv-inventory-'+binding+'-legend').html('');
	$('#chartdiv-inventory-'+binding+'-legend').append(legend);

	var chart = c3.generate({
		bindto: bindto,
		data: {
			columns: donutdata,
			type : 'donut',
			colors: {
				free: seriesColors[0],
				active: seriesColors[1],
				paused: seriesColors[2]
			},
			onclick: function (d, i) { console.log("onclick", d, i); },
			onmouseover: function (d, i) { 
				// console.log("onmouseover", d, i); 
				d3.select(bindto+' .c3-chart-arcs-title').node().innerHTML = d['id'] + ' ' + d['value'];
			},
			onmouseout: function (d, i) {
				// console.log("onmouseout", d, i); 
				d3.select(bindto+' .c3-chart-arcs-title').node().innerHTML = donutdata[0][0] + ' ' + donutdata[0][1];
			},
		},
		donut: {
			title: donutdata[0][0] + ' ' + donutdata[0][1],
			label: {
				format: function (value, ratio, id) {
	 				return d3.format(units)(value);
				}
			}
		},
		legend: {
			show: false
		},
		transition: {
			duration: 1500
		},
		tooltip: {
			show: true,
			format: {
				value: function (value, ratio, id, index) { return value; }
			}
		}
	});
}


$(document).ready(function(){
	get_limits();
});