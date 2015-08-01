<?php

require_once('common.inc.php');

/* get enrollment list */
$course_id = $toolProvider->user->getResourceLink()->settings['custom_canvas_course_id'];
$enrollments = $cache->getCache('enrollments');
if ($enrollments === false) {
	$enrollments = $api->get("courses/$course_id/enrollments",
		array(
			'type' => 'StudentEnrollment',
			'state' => 'active'
		)
	);
	$cache->setCache('enrollments', $enrollments);
}
$smarty->assign('students', $enrollments);

/* get selected student */
$selected = null;
if (!empty($_REQUEST['user_id'])) {
	$result = $api->get("courses/$course_id/enrollments", array('user_id' => $_REQUEST['user_id']));
	$result->rewind();
	if ($result->valid()) {
		$selected = $result->current()['user'];
	}
} else {
	$enrollments->rewind();
	if ($enrollments->valid()) {
		$selected = $enrollments->current()['user'];
	}
}
$smarty->assign('selected', $selected);

/* get submissions */
$data = array();
if (!empty($selected)) {
	$assignments = $cache->getCache('assignments');
	if ($assignments === false) {
		$assignments = $api->get("courses/$course_id/assignments");
		$cache->setCache('assignments', $assignments);
	}
	foreach ($assignments as $assignment) {
		$submissionKey = "assignments/{$assignment['id']}/submissions/{$selected['id']}";
		$submission = $cache->getCache($submissionKey);
		if ($submission === false) {
			$submission = $api->get("courses/$course_id/$submissionKey", array('include' => 'submission_comments'));
			$cache->setCache($submissionKey, $submission);
		}
		$data[] = array('assignment' => $assignment, 'submission' => $submission);
	}
	$smarty->assign('data', $data);
}

$smarty->display('student-feedback-review.tpl');

?>