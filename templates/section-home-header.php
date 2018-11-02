<?php use Roots\Sage\Extras;
use Roots\Sage\Customizer;
use Roots\Sage\Assets;

$vars = Customizer\get_home_page_header_args();

if (!$vars['hide']) {
?>
	<section class="section section-home-header bg-blue-dark" >
		<div class="row">
			<div class="col col-12 col-md-6 col-left">
				<div class="col-content">
				<p class="home-header-content"><?php echo $vars['content']; ?></p>
				<?php if ($vars['link'] != 0) { ?>
					<a href="<?php echo get_permalink($vars['link']) ?>" class="more-link home-header-link"><?php echo $vars['link_label'] ?></a>
				<?php } ?>
				</div>
			</div>
			<div class="col col-12 col-md-6 col-right">
				<?php if ($vars['image'] == null) { ?>
					<div class="responsive-img-wrapper">
					<img src="<?php echo Assets\asset_path('images/home_page_hero.jpg') ?>" class="attachment-x-large size-x-large crop-height" alt="A photo of business team communicating at workplace. Female sitting on wheelchair in meeting. They are in brightly lit n creative office." >
				<?php } else { ?>
				<?php echo Extras\get_responsive_image($vars['image']); ?>
				<?php } ?>
			</div>
		</div>
	</section>
<?php
}
?>