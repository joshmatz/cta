<div class="forum-content">
	<div id="forums-container">
		<table id="forums">
		<tr>
			<th>Forum</th>
			<th>Topics</th>
			<th>Posts</th>
			<th>Last Post</th>
		</tr>
		<?php foreach($forums as $forum): 
			if($forum['Forum']['access'] == 0 || $forum['Forum']['access'] == 1 || ($forum['Forum']['access'] == 2 && ($account['Account']['role'] == "admin" || $account['Account']['role'] == "council"))):
		?>
		
		<tr>
			<td class="forum-description <?php if($forum['Forum']['is_read']) { echo "read"; } ?>">
				<a href="/forums/viewForum/<?php echo $forum['Forum']['id']; ?>" class="forum-title"><?php echo $forum['Forum']['name'] ?></a>
				<span><?php echo $forum['Forum']['description']; ?></span>
			</td>
			<td class="forum-topic-count"><?php echo $forum['Forum']['topics'] ?></td>
			<td class="forum-post-count"><?php echo $forum['Forum']['posts'] ?></td>
			<td class="forum-last-post">
				<span>
					by <a href="/accounts/profile/<?php echo $forum['Post']['account_id']; ?>"><?php echo $forum['Author']['Account']['username']; ?></a>
					<a href="/forums/viewTopic/<?php echo $forum['Post']['topic_id']; ?>"><img src="/img/view_last_post.png" width="10" alt="view last post" /></a>
				</span>
				<time class="timeago" datetime="<?php echo $forum['Post']['time']; ?>"><?php echo $forum['Post']['time']; ?></time>
			</td>
		</tr>
		<?php endif; endforeach; ?>
		</table>
	</div>
</div>