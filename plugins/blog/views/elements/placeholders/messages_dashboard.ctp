<div id="blog-messages" class="boxed dashboard-element">
	<strong class="title"><?php __d('blog','Blog Comments'); ?></strong>
	<p>
		<?php
			__d('blog','These are the last comments received in your posts');
		?>
	</p>
<?php
if (!empty($data)) :
?>
	<ul>
	<?php
		foreach ($data as $i => $comment) :
	?>
		<li>
			<span class="author">
				<?php
					__d('blog','Comment by');
				?>
				<cite><?php echo $comment['Member']['full_name']; ?></cite>
				<?php
					echo $html->link(
						'#',
						array(
							'plugin'		=> 'blog',
							'controller'	=> 'posts',
							'action'		=> 'view',
							$comment['Post']['slug'],
							'#'				=> 'comment-' . $comment['Comment']['id']
						)
					);
				?>
			</span>
			<q><?php echo $text->truncate($comment['Comment']['comment'], 120); ?></q>
			<span class="date">&mdash; <?php echo $time->format('d.m.Y', $comment['Comment']['created']); ?></span>
		</li>
	<?php
		endforeach;
	?>
	</ul>
<?php
else :
	echo '<p>' . __d('blog','No comments in your blog', true) . '</p>';
endif;
?>
	<p class="go">
		<?php
			echo $html->link(
				__d('blog','Go to your blog', true),
				array(
					'controller' => 'blogs',
					'action'	=> 'view',
					'plugin'	=> 'blog',
					'member_id' => $Osmosis['active_member']['id']
				)
			);
		?>
	</p>
</div>