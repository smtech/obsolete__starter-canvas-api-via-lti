{extends file="page.tpl"}
{block name="content"}

<div class="container page-header">
	<h1>Feedback Review
		<small>
			{foreach $students as $student}
				{if isset($selected) && $student['user']['id'] == $selected['id']}
					{$student['user']['name']}
				{/if}
			{/foreach}
		</small>
	</h1>
</div>

{if isset($selected)}
	<div class="container">
		{foreach $data as $datum}
			{if empty($datum['submission']['submission_comments'])}
				{assign var="panelColor" value="default"}
				{assign var="commentsAvailable" value=false}
			{else}
				{assign var="panelColor" value="primary"}
				{assign var="commentsAvailable" value=true}
			{/if}
			<div class="panel panel-{$panelColor}">
				<div class="panel-heading">
					{$datum['assignment']['name']}
					<small>
						due {date('l, F j',strtotime($datum['assignment']['due_at']))}{if $commentsAvailable},
							submitted {date('l, F j',strtotime($datum['submission']['submitted_at']))}
						{/if}
					</small>
					<div class="pull-right">
						<button class="btn btn-xs btn-{$panelColor} has-spinner" value="{$datum['key']}" onclick="studentFeedbackReview.refreshCache(this.value);">Refresh <span class="spinner"><i class="fa fa-refresh fa-spin"></i></span></button>
						<a class="btn btn-xs btn-{$panelColor}" href="{array_shift(explode('?', $datum['submission']['preview_url']))}" target="_top">
							<span class="glyphicon glyphicon-zoom-in"></span> Details
						</a>
					</div>
				</div>
		
				{if !empty($datum['submission']['submission_comments'])}
					<div class="panel-body">
						{foreach $datum['submission']['submission_comments'] as $comment}			
							<div class="alert {if array_search($comment['author_id'], $teachers) !== false}alert-warning{/if}">
								<p><small>On {date('l, F j', strtotime($comment['created_at']))}, {$comment['author_name']} wrote:</small></p>
								{$comment['comment']}
							</div>
						{/foreach}
					</div>
				{/if}
			</div>
		{/foreach}
	</div>

{else}
	<p>Select a student for whom you would like to review your feedback.</p>
{/if}

<script src="{$metadata['APP_URL']}/js/student-feedback-review.js.php"></script>

{/block}