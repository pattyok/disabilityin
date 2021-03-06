<?php
use Roots\Sage\Customizer;

$vars = Customizer\get_home_page_posts_args($post_type);
if ($vars['number_posts'] > 0) {
	$args = array(
		'posts_per_page'  => $vars['number_posts'],
		'post_type'       => $post_type,
	);
	$posts = get_posts($args);
}
$vars['link_url'] = get_permalink( get_option( 'page_for_posts' ));
if ($post_type == 'our_stories') {
	$vars['link_url'] = get_post_type_archive_link('our_stories');
}
?>

<?php if (!$vars['hide'] && !empty($posts)) { ?>
<section class="section section-home-post bg-<?php echo $vars['background'] ?> section-<?php echo $vars['layout'] ?>" id="section-home-page-<?php echo $post_type ?>">
	<div class="container">
		<?php if (!empty($vars['title'])) { ?>
		<div class="row title-row">
    	<div class="col col-12 ">
				<h2 class="section-tag"><?php echo $vars['title'] ?></h2>
    	</div>
		</div>
		<?php } ?>
		<div class="row content-row">
			<?php
				$count = count($posts);
				$col_class = 'col col-12';
				foreach( $posts as $post) {
					setup_postdata( $post );
					if ($count > 1 && $count < 5) {
						$n = 12/$count;
						$col_class .= ' col-md-' . $n;
					} else if ($count >= 5) {
						$col_class .= ' col-md-4';
					}
					if ($vars['layout'] == 'columns') {
					?>
				<article <?php post_class($col_class); ?>>
				<?php } ?>
					<?php get_template_part('templates/content', $vars['layout'] == 'columns' ? 'column' : get_post_format()); ?>
					<?php
					if ($vars['layout'] == 'columns') { ?>
				</article>
					<?php } ?>
				<?php
				}
				wp_reset_postdata(); ?>
		</div>
		<?php if (!empty($vars['link_label'])) { ?>
		<div class="row footer-row">
			<a class="more-link" href="<?php echo $vars['link_url']; ?>"><?php echo $vars['link_label'] ?></a>
		</div>
		<?php } ?>
	</div>
</section>
<?php } ?>