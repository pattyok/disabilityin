<?php
$post_type = get_post_type();
$intro = 'sidebar-news-intro';
$sidebar = 'sidebar-primary';
if ($post_type == 'our_stories') {
	$intro = 'sidebar-our_stories-intro';
	$sidebar = 'sidebar-stories';
}

?>
<?php
  get_template_part('templates/page', 'header');
 ?>

<div class="container">

	<div class="news-intro">
		<?php dynamic_sidebar($intro); ?>
	</div>
	<?php if (is_active_sidebar($sidebar)) { ?>
		<div class="row">
			<div class="col col-12 col-md-9">
	<?php } ?>
	<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'sage'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>
	<?php while (have_posts()) : the_post(); ?>

		<?php get_template_part('templates/content', get_post_type() != 'post' ? get_post_type() : get_post_format()); ?>
	<?php endwhile; ?>
	<?php if (is_active_sidebar($sidebar)) { ?>
			</div>
			<div class="col col-12 col-md-3 col-sidebar-primary">
	<?php } ?>
	<?php dynamic_sidebar($sidebar); ?>
	<?php if (is_active_sidebar($sidebar)) { ?>
			</div>
		</div>
	<?php } ?>

<?php the_posts_navigation(); ?>
</div>
