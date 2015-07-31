<?php

require_once('common.inc.php');

/* get enrollment list */
$course_id = $toolProvider->user->getResourceLink()->settings['custom_canvas_course_id'];
$enrollments = $api->get("courses/$course_id/enrollments",
	array(
		'type' => 'StudentEnrollment',
		'state' => 'active'
	)
);
$smarty->assign('students', $enrollments);

/* get selected student */
$selected = null;
if (isset($_REQUEST['user_id'])) {
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
	$assignments = $api->get("courses/$course_id/assignments");
	foreach ($assignments as $assignment) {
		$submission = $api->get("courses/$course_id/assignments/{$assignment['id']}/submissions/{$selected['id']}", array('include' => 'submission_comments'));
		$data[] = array('assignment' => $assignment, 'submission' => $submission);
	}
	$smarty->assign('data', $data);
}

$smarty->display('student-feedback-review.tpl');

?>