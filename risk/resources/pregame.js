var $PREGAME = (function($) {
	var $settings = {
		gid: 0,
		path: './ajax.php'
	};

	function kickPlayer($e) {
		var $this = $(this);
		$e.preventDefault();
		if(!confirm('Are you sure you want to kick this player?')) {
			return;
		}
		$.ajax({
			type: 'POST',
			url: $settings.path,
			data: {
				mode: 'pregame',
				action: 'kick',
				uid: $this.attr('data-uid'),
				gid: $settings.gid
			}
		});
		$this.hide();
	}

	function requestUpdate() {
		$.ajax({
			type: 'POST',
			url: $settings.path,
			data: {
				mode: 'pregame',
				action: 'update',
				gid: $settings.gid
			}
		}).done(updateResponse).fail(function() {
			window.setTimeout(requestUpdate,500);
			console.error('Failed to connect.');
		});
	}

	function updateResponse($responseText) {
		var $response,$sect,$players,$n,$length;
		try {
			$response = JSON.parse($responseText);
		} catch($er) {
			alert($responseText);
			location.reload();
			return;
		}
		$sect = $('section:first');
		$sect.empty();
		$sect.append($($response.page));
		$sect.find('input[data-uid][type="button"]').click(kickPlayer);
		$players = [];
		for($n=0,$length=$response.players.length;$n < $length;$n++) {
			$players[$response.players[$n].uid] = $response.players[$n];
		}
		$CHAT.updatePlayers($players);
		window.setTimeout(requestUpdate,3000);
	}

	function init($canDelete,$gid,$players,$pregame,$path,$parts) {
		$settings.gid = $gid;
		$settings.path = $path;
		$('section:first').find('input[data-uid][type="button"]').click(kickPlayer);
		window.setTimeout(requestUpdate,3000);
		$CHAT.init($canDelete,$gid,$players,$pregame,$path,$parts);
		this.init = function() {
			return 'Already initialised.';
		}
	}

	return {init: init};
})(jQuery);