{block name="navigation-menu"}
	{if $isTeacher}
		<ul class="nav navbar-nav">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					Students <span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					{foreach $students as $student}
						<li {if isset($selected) && $student['user']['id'] == $selected['id']}
								class="active"
							{/if}>
							<a href="?user_id={$student['user']['id']}">{$student['user']['name']}</a>
						</li>
					{/foreach}
				</ul>
			</li>
		</ul>
	{/if}
{/block}