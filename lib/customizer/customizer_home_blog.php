<?php

namespace Roots\Sage\Customizer;

use Roots\Sage\Assets;

function customize_home_posts($wp_customize, $posttype, $label) {
	$defaults = get_home_page_posts_defaults();
	$section = 'home_page_' . $posttype;
	make_customizer_section( $wp_customize, $section , array (
    'title'      	=> __( $label, 'sage' ),
		'panel'				=> 'home_page_panel',
		'description' => __('Displays your most recent posts in ' . $label )
	) );

//Title
	$wp_customize->add_setting($section . '_title', array(
		'default' => $defaults[$posttype]['title'],
		'transport' => 'postMessage',
		'sanitize_calllback' => 'wp_filter_nohtml_kses'
	));

	$wp_customize->add_control( $section . '_title', array(
		'type'			=> 'text',
		'label' 		=> __( 'Section Title', 'sage' ),
		'section' 	=> $section
	));

	$wp_customize->add_setting($section . '_link_label', array(
		'default' => $defaults[$posttype]['link_label'],
		'transport' => 'postMessage',
		'sanitize_calllback' => 'wp_filter_nohtml_kses'
	));

	$wp_customize->add_control( $section . '_link_label', array(
		'type'			=> 'text',
		'label' 		=> __( 'View All Link Label', 'sage' ),
		'description' => 'Leave blank to remove link',
		'section'		=> $section
	));

	$wp_customize->add_setting($section . '_number_posts', array(
		'default' => 3,
		'transport' => 'refresh',
		'sanitize_calllback' => 'absint'
	));

	$wp_customize->add_control( $section . '_number_posts', array(
		'type'			=> 'number',
		'label' 		=> __( 'Number of Posts', 'sage' ),
		'section' 	=> $section
	));

//Layout
$wp_customize->add_setting( $section . '_background', array (
	'default' => $defaults[$posttype]['background'],
	'sanitize_callback' =>  __NAMESPACE__ . '\\sanitize_radio',
	'transport' => 'postMessage'
));

$wp_customize->add_control ( $section . '_background', array(
	'label' => esc_html__( 'Background Color', 'sage' ),
	'section' => $section,
	'type' => 'radio',
	'choices' => array(
			'white' => esc_html__('White','sage'),
			'gray-light' => esc_html__('Gray','sage')
	))
);


//add setting to your section
$wp_customize->add_setting( $section . '_layout', array (
	'default' => $defaults[$posttype]['layout'],
	'sanitize_callback' =>  __NAMESPACE__ . '\\sanitize_radio',
));

$wp_customize->add_control ( $section . '_layout', array(
	'label' => esc_html__( 'Section Layout', 'sage' ),
	'section' => $section,
	'type' => 'radio',
	'choices' => array(
			'columns' => esc_html__('Columns','sage'),
			'stacked' => esc_html__('Stacked','sage')
	))
);
}

function get_home_page_posts_defaults() {
	return [
		'post' => [
			'hide'  			=> false,
			'title' 			=> 'News & Events',
			'layout' 			=> 'stacked',
			'background' 	=> 'gray-light',
			'number_posts'=> 3,
			'link_label'		=> 'View All News & Events'
		],
		'our_stories' => [
			'hide'  			=> false,
			'title' 			=> 'Our Stories',
			'layout' 			=> 'columns',
			'background' 	=> 'white',
			'number_posts'=> 3,
			'link_label'		=> 'View All of Our Stories'
		]];
}

function get_home_page_posts_args($type) {
	$vars = get_home_page_posts_defaults();
	$output = array();
	$defaults = $vars[$type];
	foreach ($defaults as $var => $default) {
		$output[$var] = get_theme_mod('home_page_' . $type . '_' . $var, $default);
	}
	return $output;
}

//radio box sanitization function
function sanitize_radio( $input, $setting ){

	//get the list of possible radio box options
	$choices = $setting->manager->get_control( $setting->id )->choices;

	//return input if valid or return default option
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

}

