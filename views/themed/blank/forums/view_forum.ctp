
<div class="forum-content">
	<div id="topics-container">
		<span class="bigger"><?php echo $forum['Forum']['name']; ?></span>
		<?php $this->Html->addCrumb('Public Forums', '/forums/'); ?>
		<?php $this->Html->addCrumb($forum['Forum']['name']); ?>
		<div class="pageNav">
			<!-- Shows the page numbers -->
			<?php echo $this->Paginator->numbers(); ?>
			<!-- Shows the next and previous links -->
			<?php echo $this->Paginator->prev('< Previous', array(), null, array('class' => 'disabled')); ?>
			<?php echo $this->Paginator->next('Next >', array(), null, array('class' => 'disabled')); ?> 
			<!-- prints X of Y, where X is current page and Y is number of pages -->
			<?php echo $this->Paginator->counter(); ?>
		</div>
		<table id="topics">
		<tr>
			<th>Topic</th>
			<th>Reply</th>
			<th>Views</th>
			<th>Last Post</th>
		</tr>
		<?php foreach($topics as $topic): ?>
			<tr>
				<td class="topic-description <?php if($topic['Topic']['is_read']) { echo "read"; } ?>">
					<a href="/forums/viewTopic/<?php echo $topic['Topic']['id']; ?>" class="topic-title"><?php echo $topic['Topic']['title'] ?></a>&nbsp;<?php if($topic['Topic']['locked']): echo " ( Locked )"; endif; ?>
					<span>
						by <a href="/accounts/profile/<?php echo $topic['FirstAuthor']['Account']['id']; ?>"><?php echo $topic['FirstAuthor']['Account']['username']; ?></a>
						<time class="timeago" datetime="<?php echo $topic['Topic']['time']; ?>"><?php echo $topic['Topic']['time']; ?></time>
					</span>
				</td>
				<td class="topic-reply-count"><?php echo $topic['Topic']['replies'] ?></td>
				<td class="topic-view-count"><?php echo $topic['Topic']['views'] ?></td>
				<td class="topic-last-post"><span>
						by <a href="/accounts/profile/<?php echo $topic['LastAuthor']['Account']['id']; ?>"><?php echo $topic['LastAuthor']['Account']['username']; ?></a>
						<a href="/forums/viewTopic/<?php echo $topic['Topic']['id']; ?>/page:<?php echo max(1, ceil($topic['Topic']['replies'] / 27)); ?>"><img src="/img/view_last_post.png" width="10px"/></a>
					</span>
					<time class="timeago" datetime="<?php echo $topic['Topic']['last_post_time']; ?>"><?php echo $topic['Topic']['last_post_time']; ?></time>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	</div>
	<div id="forum-info">
		<a href="/forums/addTopic/<?php echo $forum['Forum']['id']; ?>">Create a Topic</a>
		<div class="pageNav">
			<!-- Shows the page numbers -->
			<?php echo $this->Paginator->numbers(); ?>
			<!-- Shows the next and previous links -->
			<?php echo $this->Paginator->prev('< Previous', array(), null, array('class' => 'disabled')); ?>
			<?php echo $this->Paginator->next('Next >', array(), null, array('class' => 'disabled')); ?> 
			<!-- prints X of Y, where X is current page and Y is number of pages -->
			<?php echo $this->Paginator->counter(); ?>
		</div>
	</div>
</div>