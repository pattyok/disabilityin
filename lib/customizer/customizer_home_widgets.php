<?php

namespace Roots\Sage\Customizer;

use Roots\Sage\Assets;
use Roots\Sage\Extras;


// adding settings sections just above the widgets sidebar,
// TODO if time allows combine the settings with the widget sections
function customize_home_widgets($wp_customize, $sidebar_id, $sidebar_title, $priority) {
	$defaults = get_home_page_widget_defaults();
	//move widget panel to home_page_panel
	$widget_section = (object) $wp_customize->get_section( 'sidebar-widgets-' . $sidebar_id );
	$widget_section -> panel = 'home_page_panel';
	$widget_section -> priority = $priority;
	$widget_section -> description = __('Add/Edit widgets for this section, for layout settings open ' . $sidebar_title . ' Settings', 'sage');

	$section = 'meta-section-' . $sidebar_id;

	make_customizer_section( $wp_customize, $section , array (
    'title'      	=> __( $sidebar_title . ' Settings', 'sage' ),
		'panel'				=> 'home_page_panel',
		'description' => __('Visual controls for ' . $sidebar_title . '. Open ' . $sidebar_title . ' to manage the  content.'),
		'priority'		=> $priority + 1,
	), false );

	//Title
	$wp_customize->add_setting($sidebar_id . '_title', array(
		'default' => $defaults[$sidebar_id]['title'],
		'transport' => 'postMessage',
		'sanitize_calllback' => 'wp_filter_nohtml_kses'
	));

	$wp_customize->add_control( $sidebar_id . '_title', array(
		'type'			=> 'text',
		'label' 		=> __( 'Section Title', 'sage' ),
		'description' => __('Leave blank to remove the section title'),
		'section' 	=> $section
	));

	//Layout
	$wp_customize->add_setting( $sidebar_id . '_background', array (
		'default' => $defaults[$sidebar_id]['background'],
		'sanitize_callback' =>  __NAMESPACE__ . '\\sanitize_radio',
		'transport' => 'postMessage'
	));

	$wp_customize->add_control ( $sidebar_id . '_background', array(
		'label' => esc_html__( 'Background Color', 'sage' ),
		'section' => $section,
		'type' => 'radio',
		'choices' => array(
				'white' => esc_html__('White','sage'),
				'gray-light' => esc_html__('Gray','sage')
		))
	);


// Layout
$wp_customize->add_setting( $sidebar_id . '_layout', array (
	'default' => $defaults[$sidebar_id]['layout'],
	'sanitize_callback' =>  __NAMESPACE__ . '\\sanitize_radio',
));

$wp_customize->add_control ( $sidebar_id . '_layout', array(
	'label' => esc_html__( 'Section Layout', 'sage' ),
	'section' => $section,
	'type' => 'radio',
	'choices' => array(
			'columns' => esc_html__('Columns','sage'),
			'stacked' => esc_html__('Stacked','sage')
	))
);

}

//TODO CANT FIGURE OUT HOW TO DO THIS DYNAMICALLY (probably can do it if switch to Class Architecture)
function add_widget_partials($wp_customize) {
	$wp_customize->selective_refresh->add_partial( 'home-page-widgets-3_title',
		array(
		'selector' => '#section-home-page-widgets-3 .title-row',
		'render_callback' => function() {
			echo widget_title_render_callback('home-page-widgets-3'); //no luck on assigning this parameter dynamically
		},
		'fallback_refresh' => true
	));

	$wp_customize->selective_refresh->add_partial( 'home-page-widgets-2_title',
		array(
		'selector' => '#section-home-page-widgets-2 .title-row',
		'render_callback' => function() {
			echo widget_title_render_callback('home-page-widgets-2');
		},
		'fallback_refresh' => true
	));

	$wp_customize->selective_refresh->add_partial( 'home-page-widgets-1_title',
		array(
		'selector' => '#section-home-page-widgets-1 .title-row',
		'render_callback' => function() {
			echo widget_title_render_callback('home-page-widgets-1');
		},
		'fallback_refresh' => true
	));

	'home_page_' . $posttype;

	$wp_customize->selective_refresh->add_partial( 'home_page_our_stories_title',
		array(
		'selector' => '#section-home-page-our_stories .title-row',
		'render_callback' => function() {
			echo widget_title_render_callback('home_page_our_stories');
		},
		'fallback_refresh' => true
	));

	$wp_customize->selective_refresh->add_partial( 'home_page_post_title',
		array(
		'selector' => '#section-home-page-post .title-row',
		'render_callback' => function() {
			echo widget_title_render_callback('home_page_post');
		},
		'fallback_refresh' => true
	));

}

function widget_title_render_callback($sidebar_id) {
	$title = get_theme_mod($sidebar_id . '_title');
	$title_content = '';

	Extras\write_log($title);

	if (!empty($title)) {
		$title_content = '<div class="col col-12"><h2 class="section-tag">' . $title . '</h2></div>';
	}
	Extras\write_log($title_content);

	return wp_kses_post( $title_content );
}

function get_home_page_widget_defaults() {
	return [
		'home-page-widgets-1' => [
			'hide'  			=> false,
			'title' 			=> '',
			'layout' 			=> 'stacked',
			'background' 	=> 'white',
		],
		'home-page-widgets-2' => [
			'hide'  			=> false,
			'title' 			=> 'Widgets 2',
			'layout' 			=> 'columns',
			'background' 	=> 'gray-light',
		],
		'home-page-widgets-3' => [
			'hide'  			=> false,
			'title' 			=> 'Widgets 3',
			'layout' 			=> 'stacked',
			'background' 	=> 'white',
		],
	];
}

function get_home_page_widget_args($sidebar) {
	$vars = get_home_page_widget_defaults();
	$output = array();
	$defaults = $vars[$sidebar];
	foreach ($defaults as $var => $default) {
		$output[$var] = get_theme_mod($sidebar . '_' . $var, $default);
	}
	return $output;
}