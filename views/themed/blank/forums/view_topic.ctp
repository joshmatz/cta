<script language=javascript>
function quotePost(id) {
	var post = $("#PostText");
	post.insertAtCaret('[quote="' + $('#post-' + id + '-author').text() + '"]' + $('#post-' + id).text() + '[/quote]');
	var position = post.position();
	window.scrollTo(position.left,position.top);
}

$.fn.extend({
insertAtCaret: function(myValue){
  return this.each(function(i) {
    if (document.selection) {
      this.focus();
      sel = document.selection.createRange();
      sel.text = myValue;
      this.focus();
    }
    else if (this.selectionStart || this.selectionStart == '0') {
      var startPos = this.selectionStart;
      var endPos = this.selectionEnd;
      var scrollTop = this.scrollTop;
      this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
      this.focus();
      this.selectionStart = startPos + myValue.length;
      this.selectionEnd = startPos + myValue.length;
      this.scrollTop = scrollTop;
    } else {
      this.value += myValue;
      this.focus();
    }
  })
}});
</script>
<?php $this->Html->addCrumb('Public Forums', '/forums/'); ?>
<?php $this->Html->addCrumb($forum['Forum']['name'], '/forums/viewForum/' . $forum['Forum']['id']); ?>
<?php $this->Html->addCrumb($topic['Topic']['title']); ?>

<div class="topic-content">
	<div id="posts-container">
		<span class="bigger"><?php echo $topic['Topic']['title']; ?></span> <?php if($topic['Topic']['locked']): ?>&nbsp;<span class="big"> (Locked) </span><?php endif; ?>
		<div class="pageNav">
			<!-- Shows the page numbers -->
			<?php echo $this->Paginator->numbers(); ?>
			<!-- Shows the next and previous links -->
			<?php echo $this->Paginator->prev('Ç Previous', array(), null, array('class' => 'disabled')); ?>
			<?php echo $this->Paginator->next('Next È', array(), null, array('class' => 'disabled')); ?> 
			<!-- prints X of Y, where X is current page and Y is number of pages -->
			<?php echo $this->Paginator->counter(); ?>
		</div>
		<table id="posts">
		<tr>
			<th>Author</th>
			<th>Post</th>
		</tr>
		<?php foreach($posts as $post): ?>
			<?php if($post['Post']['is_deleted']): ?>
			<tr>
				<td colspan="3" style="text-align:center;">This post by <?php echo $post['Post']['author'] ?> has been removed.</td>
			</tr>
			<?php else: ?>
			<tr>
				<td class="post-author" style="padding-bottom: 50px;">
					<a href="/accounts/profile/<?php echo $post['Post']['account_id']; ?>"><img src="/img/noavatar.gif"></a>
					<div class="author-description">
						<a href="/accounts/profile/<?php echo $post['Post']['account_id']; ?>" id="post-<?php echo $post['Post']['id']; ?>-author"><?php echo $post['Post']['author'] ?></a>
						<?php if(isset($post['details']['User'])): ?><a href="/users/profile/<?php echo $post['details']['User']['id']; ?>" class="current-ruler"><?php echo $post['details']['User']['ruler'] ?></a>
						<?php if(isset($post['details']['alliance'])): ?><a href="/alliances/profile/<?php echo $post['details']['User']['alliance_id'] ?>" class="current-alliance"><?php echo $post['details']['alliance']['Alliance']['short_name'] ?></a><?php endif; ?>
						<span class="current-rank"><?php echo number_format($post['details']['User']['score']); ?></span><?php endif; ?>
					</div>
				</td>
				<td class="post-content">
					<div style="float: left; width: 250px; font-size: 11px; ">
						Posted <time class="timeago" style="font-size: 11px;" datetime="<?php echo $post['Post']['time'] ?>"><?php echo $post['Post']['time'] ?></time>
					</div>
					<?php if($account): ?>
					<div class="options" style="float: right;">
						<?php if($account['Account']['id'] == $post['Post']['account_id'] || $account['Account']['role'] == "admin" || $account['Account']['role'] == "council"): ?>
							<?php echo $html->link('Edit', array( 'controller' => 'forums', 'action' => 'editPost', $post['Post']['id'], $post['Post']['topic_id']), array('escape' => false,'class' => 'action')); ?>
						<?php endif; ?>
						<?php echo $html->link('Quote', 'javascript: ;', array('onclick' => 'quotePost(' . $post['Post']['id'] . ')', 'class' => "action")); ?>
						<?php if($account['Account']['role'] == "admin" || $account['Account']['role'] == "council"): ?>
							<?php echo $html->link('X', array( 'controller' => 'forums', 'action' => 'deletePost', $post['Post']['id'], $post['Post']['topic_id']), array('escape' => false,'class' => 'action'), "Are you sure you wish to delete this post?"); ?>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<div style="clear:both;"></div>
					<div id="post-<?php echo $post['Post']['id']; ?>" style="margin: 15px 0;"><?php echo  $markitup->parse($post['Post']['text'], 'bbcode'); ?></div>
					<div style="float: left; width: 350px; font-size: 11px; margin-botton: 10px;">
						<?php if($post['Post']['edit_count']): ?>
						Edited <?php echo $post['Post']['edit_count']; ?> time<?php if($post['Post']['edit_count'] > 1): echo "s"; endif; ?>; 
						Last edit by <?php echo $html->link($post['details']['editor'], '/accounts/profile/' . $post['Post']['edit_account_id'], array()); ?> <time class="timeago" style="font-size: 11px;" datetime="<?php echo $post['Post']['edit_time'] ?>"><?php echo $post['Post']['edit_time'] ?></time><br />
						Reason: <?php echo $post['Post']['edit_reason']; ?>
						<?php endif; ?>
					</div>
				</td>
			</tr>
			<?php endif; ?>
		<?php endforeach; ?>
		</table>
	</div>
	<div id="forum-info">
		<span><?php echo $this->Html->getCrumbs(' > ', false); ?></span>
		<div class="pageNav">
			<!-- Shows the page numbers -->
			<?php echo $this->Paginator->numbers(); ?>
			<!-- Shows the next and previous links -->
			<?php echo $this->Paginator->prev('Ç Previous', array(), null, array('class' => 'disabled')); ?>
			<?php echo $this->Paginator->next('Next È', array(), null, array('class' => 'disabled')); ?> 
			<!-- prints X of Y, where X is current page and Y is number of pages -->
			<?php echo $this->Paginator->counter(); ?>
		</div>
	</div>
</div>
<?php if($account['Account']['role'] == "admin" || $account['Account']['role'] == "council"): ?>
<div>
	<?php 
	echo $html->link('Delete Topic', array( 'controller' => 'forums', 'action' => 'deleteTopic', $topic['Topic']['id'], $topic['Topic']['forum_id']), array('escape' => false,'class' => 'action'), "Are you sure you wish to delete this topic?"); 
	if($topic['Topic']['locked']): 
		echo $html->link('Unlock Topic', array( 'controller' => 'forums', 'action' => 'lockTopic', $topic['Topic']['id'], $topic['Topic']['forum_id']), array('escape' => false,'class' => 'action')); 
	else:
		echo $html->link('Lock Topic', array( 'controller' => 'forums', 'action' => 'lockTopic', $topic['Topic']['id'], $topic['Topic']['forum_id']), array('escape' => false,'class' => 'action')); 
	endif;
	?>
</div>
<?php endif; ?>
<?php if(isset($account) && $topic['Topic']['locked'] == 0): ?>
<div class="reply-topic">
	<div style="width: 252px; float: left; padding-left: 8px;">
		<h4 style="line-height: 60px;">Reply to thread</h4>
		<a href="/accounts/profile/<?php echo $account['Account']['id']; ?>" style="float: left;"><img src="/img/noavatar.gif" style="foat: left; width: 80px;"></a>
		<div class="author-description">
			<a href="/accounts/profile/<?php echo $account['Account']['id']; ?>"><?php echo $account['Account']['username'] ?></a>
			<?php if(!empty($user)): ?><a href="/users/profile/<?php echo $user['User']['id']; ?>" class="current-ruler"><?php echo $user['User']['ruler'] ?></a>
			<?php if($user['User']['alliance_id']): ?><a href="/alliances/profile/<?php echo $user['User']['alliance_id'] ?>" class="current-alliance"><?php echo $user['details']['Alliance']['short_name'] ?></a><?php endif; ?>
			<span class="current-rank"><?php echo number_format($user['User']['score']); ?></span><?php endif; ?>
		</div>
	</div>
	<div style="width: 560px; float: left;">
		<?php 
			echo $form->create('Post', array('url' => 'reply/'. $topic['Topic']['id']));
			echo $markitup->editor('Post.text', 
		                                 array( 'set' => 'bbcode', 
		                                        'parser' => '/forums/preview/bbcode',
		                                        'label' => false));
			echo $form->end('Submit'); 
		?>
	</div>
</div>
<?php elseif(!isset($account)): ?>
<div class="reply-topic">
<?php echo $html->link('Login to Reply', array( 'controller' => 'accounts', 'action' => 'login'), array('escape' => false,'class' => 'action'));  ?>
</div>
<?php endif; ?>