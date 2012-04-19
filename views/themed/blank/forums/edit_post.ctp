
<?php 
	echo $form->create('Post', array('url' => 'editPost/'. $postId));
	echo $markitup->editor('Post.text', 
                                 array( 'set' => 'bbcode', 
                                        'parser' => '/forums/preview/bbcode'));
	echo $form->input('edit_reason');
	echo $form->end('Submit'); 
?>