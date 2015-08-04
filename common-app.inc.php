<?php
	
$api = new CanvasPest($_SESSION['apiUrl'], $_SESSION['apiToken']);

$cache = new \Battis\HiearchicalSimpleCache($sql, $toolProvider->consumer->getKey() . '/' . $toolProvider->user->getResourceLink()->settings['custom_canvas_course_id']);
$cache->setLifetime(60*60*24*7);

$smarty->assign('isTeacher', !$toolProvider->user->isLearner());

?>