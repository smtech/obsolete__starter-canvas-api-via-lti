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

$teachers = $cache->getCache('teachers');
if ($teachers === false) {
	$_teachers = $api->get("courses/$course_id/enrollments",
		array(
			'type' => 'TeacherEnrollment',
			'state' => 'active'
		)
	);
	$teachers =  array();
	foreach($_teachers as $teacher) {
		$teachers[] = $teacher['user']['id'];
	}
	$cache->setCache('teachers', $teachers);
}
$smarty->assign('teachers', $teachers);

/* get selected student */
$selected = null;
$user_id = null;
if ($toolProvider->user->isLearner()) {
	$user_id = $toolProvider->user->getResourceLink()->settings['custom_canvas_user_id'];
} elseif (!empty($_REQUEST['user_id'])) {
	$user_id = $_REQUEST['user_id'];
}
if (!empty($user_id)) {
	$result = $api->get("courses/$course_id/enrollments", array('user_id' => $user_id));
	$result->rewind();
	if ($result->valid()) {
		$selected = $result->current()['user'];
	}
} elseif (!$toolProvider->user->isLearner()) {
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
		/* ignore unpublished assignments */
		if ($assignment['published']) {
			$submissionKey = "assignments/{$assignment['id']}/submissions/{$selected['id']}";
			$submission = $cache->getCache($submissionKey);
			if ($submission === false) {
				$submission = $api->get("courses/$course_id/$submissionKey", array('include' => 'submission_comments'));
				$submission = $submission->getArrayCopy();
				foreach ($submission['submission_comments'] as $key => $comment) {
					$submission['submission_comments'][$key]['comment'] = \Michelf\Markdown::defaultTransform($comment['comment']);
				}
				$cache->setCache($submissionKey, $submission);
			}
			$data[] = array('key' => $cache->getHierarchicalKey($submissionKey), 'assignment' => $assignment, 'submission' => $submission);
		}
	}
	$smarty->assign('data', $data);
}

$smarty->display('student-feedback-review.tpl');

?>