<?php

namespace Roots\Sage\Customizer;

use Roots\Sage\Assets;
use Roots\Sage\Extras;

//Include subfiles
$sage_includes = [
	'lib/customizer/customizer_general.php', // Home Page Header
	'lib/customizer/customizer_home_header.php', // Home Page Header
	'lib/customizer/customizer_home_blog.php', // Home Page Blog/Our Stories Sections
	'lib/customizer/customizer_home_widgets.php', // Home Page Flex Content Sections
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);
/**
 * Add postMessage support
 */
function customize_register($wp_customize) {
	$wp_customize->get_setting('blogname')->transport = 'postMessage';

	//Add refresh on header logo
	$wp_customize->selective_refresh->add_partial( 'custom_logo',
			array(
			'selector' => '.banner-brand-logo',
			'render_callback' => function() {
					echo brand_logo_render_callback();
			},
			'fallback_refresh' => true
		));

		$wp_customize->add_panel('home_page_panel',
			array (
				'title' => __('Home Page Sections'),
				'priority' => 50,
				'active_callback' => 'is_front_page'
			)
		);
		customize_home_header($wp_customize);
		customize_home_posts($wp_customize, 'post', 'News & Events');
		customize_home_posts($wp_customize, 'our_stories', 'Our Stories');

		//move sidebar into home page panel

		customize_home_widgets($wp_customize, 'home-page-widgets-1', 'Widgets 1', 60);
		customize_home_widgets($wp_customize, 'home-page-widgets-2', 'Widgets 2', 200);
		customize_home_widgets($wp_customize, 'home-page-widgets-3', 'Widgets 3', 210);
		customize_home_widgets($wp_customize, 'home-page-widgets-4', 'Widgets 4', 220);
		add_widget_partials($wp_customize);

		//move footer widgets into their own panel
		$wp_customize->add_panel('footer_widgets_panel',
			array (
				'title' => __('Footer Widgets'),
				'priority' => 120
			)
		);
		$widget_section = (object) $wp_customize->get_section( 'sidebar-widgets-sidebar-footer-upper' );
		$widget_section -> panel = 'footer_widgets_panel';
		$widget_section = (object) $wp_customize->get_section( 'sidebar-widgets-sidebar-footer-lower-left' );
		$widget_section -> panel = 'footer_widgets_panel';
		$widget_section = (object) $wp_customize->get_section( 'sidebar-widgets-sidebar-footer-lower-right' );
		$widget_section -> panel = 'footer_widgets_panel';



}
add_action('customize_register', __NAMESPACE__ . '\\customize_register');

function brand_logo_render_callback() {
	if ( function_exists( 'the_custom_logo' ) && has_custom_logo()) {
		$logo = get_custom_logo();
	}
	return wp_kses_post( $logo );
}

/**
 * Customizer JS
 */
function customize_preview_js() {
	//wp_enqueue_script('sage/customizer_panels', Assets\asset_path('scripts/customizer_panels.js'), ['customize-preview'], null, true);
  wp_enqueue_script('sage/customizer', Assets\asset_path('scripts/customizer.js'), ['customize-preview'], null, true);
}
add_action('customize_preview_init', __NAMESPACE__ . '\\customize_preview_js');
//add_action( 'customize_controls_enqueue_scripts', __NAMESPACE__ . '\\customize_preview_js' );


