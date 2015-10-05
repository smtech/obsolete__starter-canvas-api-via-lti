<?php
	
require_once('common.inc.php');

if (isset($_REQUEST['key'])) {
	$cache->resetCache($_REQUEST['key']);
}

?>
