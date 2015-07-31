<?php

require_once('../common.inc.php');

/* this file handles the entire OAuth API token negotiation for a user token --
   update it to include a better explanation, pertinent to your app, for why the
   user is about to be asked to log into Canvas in the middle of Canvas */

try {
	$oauth = new OAuthNegotiator();
} catch (OAuthNegotiator_Exception $e) {}

if (isset($_REQUEST['oauth'])) {
	switch ($_REQUEST['oauth']) {
		case 'request': {
			$smarty->assign('content', '<h1>Before we begin&hellip;</h1>
		<p>' . $metadata['APP_NAME'] . ' will need to access the Canvas API to collect information for you. You will need to explicitly give this application permission to do so. This application will change none of your course data.</p>
		<p>The next thing you see will be Canvas asking you to give this permission by &ldquo;logging in.&rdquo; Don&rsquo;t panic. The phrasing is bad, but it&rsquo;s just asking for your permission.</p>
		<p>Click the blue <span style="background: #0080D6; color: white; border: #0080D6 solid 1px; border-radius: 3px; padding: 0.25em 1em;">Log in</span> button (on the next page, not this one).</p>
		<p><form action="' . $_SERVER['PHP_SELF'] . '" method="post"><button type="submit" name="oauth" value="process">Continue</button></form></p>');
			$smarty->display();
			exit;
		}
		case 'process': {
			$oauth = new OAuthNegotiator(
				'https://' . $toolProvider->user->getResourceLink()->settings['custom_canvas_api_domain'] . '/login/oauth2',
				(string) $secrets->oauth->id,
				(string) $secrets->oauth->key,
				"{$_SERVER['PHP_SELF']}?oauth=complete",
				(string) $secrets->app->name
			);
			break;
		}
		case 'complete': {
			$user = new UserAPIToken($_SESSION['user_consumer_key'], $_SESSION['user_id'], $sql);
			$user->setToken($oauth->getToken());
			$user->setAPIUrl("{$metadata['CANVAS_INSTANCE_URL']}/api/v1");
			
			$_SESSION['apiToken'] = $user->getToken();
			$_SESSION['apiUrl'] = $user->getAPIUrl();
			$_SESSION['isUserToken'] = true;
			
			header("Location: {$metadata['APP_URL']}/app.php");
			exit;
		}
	}
}

?>