<?php if (is_tag()) {
	get_template_part( 'templates/tags-list', get_post_type() == 'post' ? '' : get_post_type() );
}  else { ?>

	<?php if( has_category( ) ) { ?>
	<div class="category-list">
		<span class="label">Posted in: </span>
		<?php if ( get_post_type() == 'post' ) {
				the_category(', ');
		} else {
			the_terms( get_the_ID(), 'story_cats', '', ', ', '' );
		}
		?>
	</div>
	<?php } ?>
<?php } ?>

