var $CHAT = (function($) {
	var $settings = {
		msg: 0,
		interval: 1000,
		scroll: $EEstore.getBoolean('chatScrolling',false),
		gid: 0,
		sync: 0,
		submitting: false,
		input: null,
		path: './ajax.php',
		chatDiv: null,
		canDelete: false,
		scrollInput: null,
		players: {}
	};

	function init($canDelete,$gid,$players,$pregame,$path,$parts) {
		if($path !== undefined) {
			$settings.path = $path;
		}
		if($parts !== undefined) {
			if($parts.input !== undefined) {
				$settings.input = $parts.input;
			}
			if($parts.chatDiv !== undefined) {
				$settings.chatDiv = $parts.chatDiv;
			}
			if($parts.scrollInput !== undefined) {
				$settings.scrollInput = $parts.scrollInput;
			}
		}
		if($gid !== undefined && $gid > 0) {
			$settings.gid = $gid;
			if($players !== undefined) {
				updatePlayers($players);
			}
			if($pregame !== undefined && $pregame) {
				createChat();
			}
		} else {
			createChat();
		}
		$settings.canDelete = $canDelete;
		chatBind();
		chatInterval();
		this.init = function() {
			return 'Chat has already been initialised.';
		};
	}

	function updatePlayers($players) {
		for(var $uid in $players) {
			$settings.players[$uid] = $players[$uid].color;
		}
	}

	function createChat() {
		var $chat = $('<section id="chat"><h2>Chat <label class="small" title="Enable this if you want the chat to automatically scroll down."><input type="checkbox" /> Chat scrolling</label></h2><div class="window"></div><form method="post"><input type="submit" value="Submit" name="chat" /><div class="input"><input type="text" maxlength="255" value="" /></div></form></section>');
		$('footer').before($chat);
		$settings.input = $('#chat input:last');
		$settings.scrollInput = $('#chat input:first');
		$settings.chatDiv = $('#chat div:first');
	}

	function chatBind() {
		$settings.scrollInput[0].checked = $settings.scroll;
		$settings.scrollInput.change(chatScrollingBox);
		$('#chat form:first').submit(chatSubmit);
	}

	function chatSubmit($e) {
		$e.preventDefault();
		if(!$settings.submitting) {
			var $msg = $settings.input.val();
			while($msg.indexOf(/\s\s/) != -1) {
				$msg = $msg.replace(/\s\s/,' ');
			}
			$msg = $msg.trim();
			if($msg !== '') {
				$settings.submitting = true;
				$.ajax({
					type: 'POST',
					url: $settings.path,
					data: {
						mode: 'chat',
						gid: $settings.gid,
						msg: $msg
					}
				}).done(chatResponse).fail(function() {
					window.alert('Failed to submit.');
				});
			}
		}
	}

	function chatResponse($responseText) {
		if($responseText != 'SUCCESS') {
			window.alert($responseText);
		} else {
			$settings.input.val('');
		}
		$settings.submitting = false;
	}

	function chatInterval() {
		if($settings.sync <= 0) {
			$settings.msg = 0;
			$settings.sync = 10;
		}
		$.ajax({
			type: 'POST',
			url: $settings.path,
			data: {
				mode: 'chat',
				gid: $settings.gid,
				update: $settings.msg
			}
		}).done(chatUpdate).fail(function() {
			console.log('Failed to get chat data.');
			window.setTimeout(chatInterval,$settings.interval/2);
		});
	}

	function chatUpdate($responseText) {
		if($responseText !== '') {
			var $msg,$n,$length;
			try {
				$msg = JSON.parse($responseText);
			} catch($e) {
				alert($responseText);
				location.reload();
				return;
			}
			if($settings.sync == 10) {
				$settings.chatDiv.empty();
			}
			for($n=0,$length=$msg.length;$n < $length;$n++) {
				chatAdd($msg[$n]);
			}
			if(typeof $CHAT.addHook == 'function') {
				$CHAT.addHook($msg,$settings.sync == 10);
			}
			if($length > 0) {
				updateScroll();
			}
		}
		window.setTimeout(chatInterval,$settings.interval);
		$settings.sync--;
	}

	function chatAdd($msg) {
		var
			$time = new Date($msg.time),
			$p = $('<p>'),
			$del,
			$t = $('<time title="'+$time.toLocaleString()+'" datetime="'+$msg.time+'">'+$time.toLocaleTimeString()+'</time>'),
			$u = $('<a href="'+$msg.url+'"> [ '+$msg.name+' ] </a>'),
			$m = $('<span>'+$msg.message+'</span>');
		$settings.msg++;
		if($settings.canDelete) {
			$del = $('<button title="Delete" data-uid="'+$msg.uid+'" data-time="'+Math.round($time.getTime()/1000)+'">X</button>');
			$p.append($del);
			$del.click(chatDel);
		}
		if($settings.players[$msg.uid] !== undefined) {
			$u.css('color','#'+$settings.players[$msg.uid]);
		}
		$p.append($t,$u,$m);
		$settings.chatDiv.append($p);
	}

	function chatDel($e) {
		$e.preventDefault();
		if(window.confirm('Are you sure you wish to remove this post?')) {
			var $m = $(this);
			$.ajax({
				type: 'POST',
				url: $settings.path,
				data: {
					mode: 'chat',
					gid: $settings.gid,
					uid: $m.attr('data-uid'),
					time: $m.attr('data-time')
				}
			});
			$(this.parentNode).remove();
			$settings.msg--;
		}
	}

	function updateScroll() {
		if($settings.scroll) {
			$settings.chatDiv.scrollTop($settings.chatDiv[0].scrollHeight);
		}
	}

	function chatScrollingBox() {
		$settings.scroll = $settings.scrollInput.prop('checked');
		$EEstore.setItem('chatScrolling',$settings.scroll);
	}

	function getScroll() {
		return $settings.scroll;
	}

	return {init: init, updatePlayers: updatePlayers, updateScroll: updateScroll, getScroll: getScroll, addHook: null};
})(jQuery);