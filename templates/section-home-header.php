<?php use Roots\Sage\Extras; ?>
<?php $hide_header = get_theme_mod('home_page_header_hide');
if (!$hide_header) {
	$image = get_theme_mod('home_page_header_image');
	$content = get_theme_mod('home_page_header_content');
	$link = get_theme_mod('home_page_header_link');
	$link_label = get_theme_mod('home_page_header_link_label', 'Learn More About Us');
?>
	<section class="section section-home-header bg-blue-dark" >
		<div class="row">
			<div class="col col-12 col-md-6 col-left">
				<div class="col-content">
				<p class="home-header-content"><?php echo $content; ?></p>
				<?php if ($link != 0) { ?>
					<a href="<?php echo get_permalink($link) ?>" class="more-link home-header-link"><?php echo $link_label ?></a>
				<?php } ?>
				</div>
			</div>
			<div class="col col-12 col-md-6 col-right">
				<?php echo Extras\get_responsive_image($image); ?>
			</div>
		</div>
	</section>
<?php
}
?>