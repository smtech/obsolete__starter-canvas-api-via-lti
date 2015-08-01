{extends file="page.tpl"}
{block name="content"}

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

{if isset($selected)}
	<h1>{$selected['name']}</h1>
	
	<dl>
		{foreach $data as $datum}
			<dt>{$datum['assignment']['name']}</dt>
			{foreach $datum['submission']['submission_comments'] as $comment}
				<dd>Comment by {$comment['author_name']}: <blockquote>{$comment['comment']}</blockquote></dd>
			{/foreach}
		{/foreach}
	</dl>
{else}
	<p>Select a student for whom you would like to review your feedback.</p>
{/if}

{/block}