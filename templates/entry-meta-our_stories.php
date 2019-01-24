
<?php if (is_tax( 'story_tags' )) {
	get_template_part( 'templates/tags-list', get_post_type() == 'post' ? '' : get_post_type() );
} else { ?>
	<?php if( has_term( '', 'story_cats' ) ) { ?>
		<div class="category-list category-list-stories">
			<span class="label">Posted in: </span>
			<?php
				the_terms( get_the_ID(), 'story_cats', '', ', ', '' );
			?>
	</div>
	<?php } ?>
<?php } ?>
