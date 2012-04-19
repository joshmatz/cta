
<?php 
	echo $form->create('Post', array('url' => 'reply/'. $topic));
	echo $markitup->editor('Post.text', 
                                 array( 'set' => 'bbcode', 
                                        'parser' => '/forums/preview/bbcode'));
	echo $form->end('Submit'); 
?>