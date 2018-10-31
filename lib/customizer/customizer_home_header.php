<?php

namespace Roots\Sage\Customizer;

use Roots\Sage\Assets;

function customize_home_header($wp_customize) {
	make_customizer_section($wp_customize, 'home_page_header' , array (
		'title'      => __( 'Home Page Header', 'sage' ),
		'panel'			=> 'home_page_panel',
		'priority'	=> 20
	) );
	//header image
	$wp_customize->add_setting('home_page_header_image', array(
		'default' => Assets\asset_path('images/home_page_hero.jpg'),
		'transport' => 'postMessage',
		'sanitize_calllback' => 'absint'
	));

	$wp_customize->add_control( new \WP_Customize_Cropped_Image_Control( $wp_customize, 'home_page_header_image', array(
		'label' 		=> __( 'Featured Home Page Image', 'sage' ),
		'section' 	=> 'home_page_header',
		'mime_type' => 'image',
		'active_callback' => 'is_front_page',
		'width'	=> 900,
		'height' => 600
	) ) );

	$wp_customize->selective_refresh->add_partial( 'home_page_header_image',
   array(
      'selector' => '.responsive-img-wrapper',
      'render_callback' => function() {
         echo header_image_render_callback();
      },
      'fallback_refresh' => true
		));

	//Header Content
	$wp_customize->add_setting('home_page_header_content', array(
		'default' => 'Set your intro in the customizer',
		'transport' => 'postMessage',
		'sanitize_calllback' => 'wp_kses_post'
	));

	$wp_customize->add_control( 'home_page_header_content', array(
		'type'			=> 'textarea',
		'label' 		=> __( 'Header content', 'sage' ),
		'section' 	=> 'home_page_header',
		'active_callback' => 'is_front_page',
	));

	$wp_customize->selective_refresh->add_partial( 'home_page_header_content',
   array(
      'selector' => '.home-header-content',
      'render_callback' => function() {
         echo header_content_render_callback();
      },
      'fallback_refresh' => true
		));

	//Header Link Label
	$wp_customize->add_setting( 'home_page_header_link_label', array(
		'default'           => 'Learn more about us',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	) );

	$wp_customize->add_control( 'home_page_header_link_label', array(
		'label'    => __( 'Link Label', 'sage' ),
		'section'  => 'home_page_header',
		'type'     => 'text'
	) );


	$wp_customize->selective_refresh->add_partial( 'home_page_header_link_label',
			array(
			'selector' => '.home-header-link',
			'render_callback' => function() {
					echo header_link_render_callback();
			},
			'fallback_refresh' => true
		));
	//Header Link
	$default_link = get_page_by_title('About Us');
	$wp_customize->add_setting( 'home_page_header_link', array(
		'default'           => $default_link->ID,
		'sanitize_callback' => 'absint'
	) );

	$wp_customize->add_control( 'home_page_header_link', array(
		'label'    => __( 'Linked Page', 'sage' ),
		'description' => __( 'Set to --Select-- to remove the link', 'sage'),
		'section'  => 'home_page_header',
		'type'     => 'dropdown-pages'
	) );

}


function header_image_render_callback() {
	$header_image = get_theme_mod('home_page_header_image');

	$output = Extras\get_responsive_image($header_image);

	return wp_kses_post( $output );
}

function header_content_render_callback() {
	$header_content = get_theme_mod('home_page_header_content');

	return wp_kses_post( $header_content );
}

function header_link_render_callback() {
	$link = get_theme_mod('home_page_header_link');
	$link_label = get_theme_mod('home_page_header_link_label');

	$output = '';

	if ($link != 0) {
		$output = '<a href="' . get_permalink($link) . '" class="more-link home-header-link">' . $link_label . '</a>';
	}

	return wp_kses_post( $output );
}



