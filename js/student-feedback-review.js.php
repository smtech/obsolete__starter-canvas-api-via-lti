<?php

header('Content-Type: text/javascript');

require_once('common.inc.php');

?>

"use strict";
var studentFeedbackReview = {
	reloadIframe: function() {
		document.location.href = document.location.href;
	},
	
	refreshCache: function(key) {
		var url = '<?= $metadata['APP_URL'] ?>/api/refresh_cache';
		var http = new XMLHttpRequest();
		var params = 'key=' + encodeURIComponent(key);
		http.open('POST', url);
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		http.addEventListener('load', this.reloadIframe);
		http.send(params);
	}
};