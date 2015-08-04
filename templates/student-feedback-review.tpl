{extends file="page.tpl"}
{block name="content"}


{if isset($selected)}
	<h1>
		<form action="app.php" method="post" id="student-selector">
			<select name="user_id" onchange="document.getElementById('student-selector').submit()">
				{foreach $students as $student}
					<option value="{$student['user']['id']}"
						{if isset($selected) && $student['user']['id'] == $selected['id']}
							selected
						{/if}>{$student['user']['name']}</option>
				{/foreach}
			</select>
		</form>
	</h1>

	<div id="assignments">
	{foreach $data as $datum}

		<div class="assignment{if empty($datum['submission']['submission_comments'])} no-feedback{/if}">
			<p class="title">{$datum['assignment']['name']} <a class="preview" href="{array_shift(explode('?', $datum['submission']['preview_url']))}" target="_top">&#x1f50d; More</a></p>
			<p class="due_date">due {date('l, F j',strtotime($datum['assignment']['due_at']))}{if !empty($datum['submission']['submitted_at'])}, submitted {date('l, F j',strtotime($datum['submission']['submitted_at']))}{/if}</p>
		
			{foreach $datum['submission']['submission_comments'] as $comment}			
			<p class="message{if array_search($comment['author_id'], $teachers) !== false} teacher{/if}"><span class="commenter">On {date('l, F j', strtotime($comment['created_at']))}, {$comment['author_name']} wrote:</span> <span class="comment">{$comment['comment']}</span></p>
			{/foreach}
		</div>

	{/foreach}
	</div>

{else}
	<p>Select a student for whom you would like to review your feedback.</p>
{/if}

{/block}