<?php 
	echo $form->create('Topic', array('url' => 'addTopic/'. $forum));
	echo $form->input('Topic.title');
	echo $markitup->editor('Post.text', 
		                                 array( 'set' => 'bbcode', 
		                                        'parser' => '/forums/preview/bbcode',
		                                        'label' => false));
	echo $form->end('Submit'); 
?>