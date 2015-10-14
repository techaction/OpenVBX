<?php

if(isset($_GET['json'])):

	$account = OpenVBX::getAccount();
	$conferences = $account->conferences->getIterator(0, 50, array('Status' => 'in-progress'));

	$res = array();
	
	foreach($conferences as $call) {
		$res[$call->friendly_name] = array(
			'date_created' => date("F j, Y, g:i a",strtotime($call->date_created)),
			'friendly_name' => $call->friendly_name,
			'status' => $call->status,
			'duration' => $call->duration
		);		
	}
	
	header('Content-type: application/json');
	echo json_encode($res);
	exit;
	
endif;

?>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
<script>

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
							select.append('<tr class="message-row recording-type" id="' + call.friendly_name + '"><td class="recording-date">' + call.date_created + '</td><td class="recording-duration">' + call.friendly_name + '</td><td class="recording-duration">' + call.status + '</td><td class="recording-duration" ><a id="join" onclick="join_call(\'' + call.friendly_name + '\');">Join</a></td><td class="recording-duration" ><a id="listen" onclick="listen_call(\'' + call.friendly_name + '\');">Listen</a></td></tr>');
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
		
	
</script>

<div class="vbx-content-main">
	<div class="vbx-content-menu vbx-content-menu-top">
		<h2 class="vbx-content-heading">Conference Calls In Progress</h2>
	</div><!-- .vbx-content-menu -->
		<div class="vbx-content-container">
		<div class="vbx-content-section">
			<table class="vbx-items-grid" border="0" id="calls">
				<tr class="items-head recording-head"><th>Start Time</th><th>Conference Name</th><th>Status</th><th>Join</th><th>Listen</th></tr>

			</table>
		</div><!-- .vbx-content-section -->
	</div><!-- .vbx-content-container -->
</div><!-- .vbx-content-main -->
