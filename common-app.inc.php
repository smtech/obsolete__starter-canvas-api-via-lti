<?php
	
$api = new CanvasPest($_SESSION['apiUrl'], $_SESSION['apiToken']);

if (defined('IGNORE_LTI')) {
	$cache = new \Battis\SimpleCache($sql);
} else {
	$cache = new \Battis\HierarchicalSimpleCache($sql, $toolProvider->consumer->getKey() . '/' . $toolProvider->user->getResourceLink()->settings['custom_canvas_course_id']);
	$smarty->assign('isTeacher', !$toolProvider->user->isLearner());
}
$cache->setLifetime(60*60*24*7);

?>