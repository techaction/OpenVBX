function join_call(conference_name){
		
	window.parent.Client.call({ 
		'conference_name' : conference_name,
		'muted' : 'false',
		'beep' : 'true',
		'Digits' : 1});
	
	return false;
}

function listen_call(conference_name){

	window.parent.Client.call({ 
		'conference_name' : conference_name,
		'muted' : 'true',
		'beep' : 'false',
		'Digits' : 1});
	
	return false;
}

$(document).ready(function(){
	jQuery(function($) {
		$(function(){
					
			var
			calls = {},
			select = $('#calls'),
			updateCalls = function() {
				$.getJSON(window.location + '/?json=1', function(data) {
					$.each(data, function(conference_name, call) {
						if(!calls[conference_name]) {
							calls[conference_name] = call;
							
							row = '<tr class="items-row" id="' + call.friendly_name + '">';
							row += '<td>' + call.date_created + '</td>';
							row += '<td>' + call.friendly_name + '</td>';
							row += '<td>' + call.status + '</td>';
							row += '<td><a onclick="join_call(\'' + call.friendly_name + '\');">Join</a></td>';
							row += '<td><a onclick="listen_call(\'' + call.friendly_name + '\');">Listen</a></td></tr>';
							
							select.append(row);
						}
					});
			
					$.each(calls, function(conference_name, call) {
					  if(!data[conference_name]) {
						delete calls[conference_name];
						$('#' + conference_name).fadeOut(250, function() {
						  $(this).remove();
						});
					  }
					});
				});
			};

			updateCalls();
			setInterval(updateCalls, 5000);
		});
	});
});