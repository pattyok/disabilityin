<?php
use Roots\Sage\Customizer;

if (is_active_sidebar( $sidebar )) {
	$vars = Customizer\get_home_page_widget_args($sidebar);
?>

<?php if (!$vars['hide']) { ?>
<section class="section section-home-widgets bg-<?php echo $vars['background'] ?> section-<?php echo $vars['layout'] ?>" id="section-<?php echo $sidebar; ?>">
	<div class="container">
		<div class="row title-row">
		<?php if (!empty($vars['title'])) { ?>
    	<div class="col col-12">
				<h2 class="section-tag"><?php echo $vars['title'] ?></h2>
			</div>
			<?php } ?>
		</div>
		<div class="row content-row">
		<?php dynamic_sidebar($sidebar); ?>
		</div>
		<div class="row footer-row">
		</div>
	</div>
</section>
<?php }
}
?>