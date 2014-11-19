(function($) {
	$(document).ready(
		function() {
			$('.spoiler').each(
				function() {
					var $button = $('<button>Show</button>'),
					$h3 = $(this).find('h3');
					$h3.append($button);
					$button.click(spoiler);
					$h3.next().hide();
				}
			);
		}
	);

	function spoiler() {
		var $elem = $(this).parent().next();
		if($elem.css('display') != 'none') {
			$(this).text('Show');
			$elem.hide();
		} else {
			$(this).text('Hide');
			$elem.show();
		}
	}
})(jQuery);