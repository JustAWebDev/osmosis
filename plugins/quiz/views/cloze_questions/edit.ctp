<div class="clozeQuestion">
<?php echo $form->create('ClozeQuestion');?>
	<fieldset>
 		<legend><?php echo sprintf(__('Edit %s', true), __('ClozeQuestion', true));?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('title');
		echo $form->input('body');
		 echo $form->input('Quiz');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('ClozeQuestion.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('ClozeQuestion.id'))); ?></li>
		<li><?php echo $html->link(sprintf(__('List %s', true), __('ClozeQuestions', true)), array('action'=>'index'));?></li>
		<li><?php echo $html->link(sprintf(__('List %s', true), __('Quizzes', true)), array('controller'=> 'quizzes', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(sprintf(__('New %s', true), __('Quiz', true)), array('controller'=> 'quizzes', 'action'=>'add')); ?> </li>
	</ul>
</div>