<div class="textQuestion">
<?php echo $form->create('TextQuestion',array('url'=>array($quizId))); ?>
	<fieldset>
 		<legend><?php echo sprintf(__('Create %s', true), __('Text Question', true));?></legend>
	<?php
		echo $form->input('title');
		echo $form->input('body');
		echo $form->input('format');
		echo $form->input('Quiz.id', array('type' => 'hidden'));
	?>
	</fieldset>
<?php echo $form->end(__('Create Question',true));?>
</div>
