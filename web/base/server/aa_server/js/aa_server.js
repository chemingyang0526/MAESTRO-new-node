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

$(document).ready(function(){

	function updateEventSection() {
		var events = htvcenter.get_event_list();
		
		
		if(events) {
			// delete tbody content 
			$('.eventtable tbody').html('');
			
			// add updated events
			$.each(events, function(k,event){
				var evento = 'null';
				
				if (event['event_source'] == 'htvcenter_lock_queue') {
					evento = 'htvcenter_lock_queue';
				} else {
					evento = event['event_source'];
					var newString = evento.replace('htvcenter', 'htvcenter');
					evento = newString;
				}

				
				var event_time = new Date((parseInt(event['event_time'])*1000));
				$('.eventtable tbody').append(
					$('<tr>')
						.append($('<td>').html(htvcenter.formatDate(event_time, '%Y/%M/%d %H:%m:%s')))
						.append($('<td>').html(
							$('<span>').attr('class','pill ' + htvcenter.getEventStatus(event['event_priority']))
						))
						.append($('<td>').html(evento))
						.append(
							$('<td>')
								.attr('title', event['event_description'])
								.html(
									htvcenter.crop(event['event_description'], 50)
								)
						)
				);
			});
		}
	}

	function updateLoadSection() {
		var status = htvcenter.get_datacenter_status();
		
		if(status != null) {
			$('.bar-01 .bar').attr('style','width:' + (status[0]*10) + '%');
			$('.bar-01 .bar label').html(status[0]);
			
			$('.bar-02 .bar').attr('style','width:' + (status[3]*10) + '%');
			$('.bar-02 .bar label').html(status[3]);
			$('.bar-02 .peak').attr({'style' : 'left: ' + (status[4]*10) + '%'});
	
			$('.bar-03 .bar').attr('style','width:' + (status[1]*10) + '%');
			$('.bar-03 .bar label').html(status[1]);
			$('.bar-03 .peak').attr({'style' : 'left: ' + (status[2]*10) + '%'});
		}
	}
	
	// add refresh events to widget buttons
	$('.refresh-load-current').click( function() {
		updateLoadSection();
	});
	//$('.refresh-load-chart').click( function() {
	//	updateLoadChart();
	//});
	$('.refresh-events').click( function() {
		updateEventSection();
	});
});
