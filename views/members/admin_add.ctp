<div class="members form">
<?php echo $form->create('Member');?>
	<fieldset class="twocol">
 		<legend><?php __('Add Member');?></legend>
		<fieldset class="col">
			<legend><?php __('Personal Data')?></legend>
			<?php
				echo $form->input('full_name');
				echo $form->input('email');
				echo $form->input('phone');
				echo $form->input('age');
				echo $form->input('sex');
			?>
		</fieldset>
		<fieldset class="col">
			<legend><?php __('Location'); ?></legend>
			<?php
				echo $form->input('institution_id');
				echo $form->input('country');
				echo $form->input('city');
			
			?>
		</fieldset>
		<fieldset class="full">
			<legend><?php __('Access Information')?></legend>
			<?php
				echo $form->input('username');
				echo $form->input('password');
				echo $form->input('password_confirm', array('type' => 'password'));
				echo $form->input('admin', array('label' => __('Give this user administrative access', true)));
			?>
		</fieldset>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>