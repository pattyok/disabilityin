<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;
use Roots\Sage\Extras;

/**
 * Theme setup
 */
function setup() {
  // Enable features from Soil when plugin is activated
  // https://roots.io/plugins/soil/
  // https://github.com/roots/soil

  add_theme_support('soil-clean-up');
  add_theme_support('soil-disable-asset-versioning');
  add_theme_support('soil-disable-trackbacks');
  //add_theme_support('soil-google-analytics', 'UA-XXXXX-Y');
  add_theme_support('soil-jquery-cdn');//this was altered to drop it in the header to support several plugins
  add_theme_support('soil-nav-walker');
  add_theme_support('soil-nice-search');
  add_theme_support('soil-relative-urls');

  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/sage-translations
  load_theme_textdomain('sage', get_template_directory() . '/lang');

  // Enable plugins to manage the document title
  // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
	add_theme_support('title-tag');

	//Enable custom logo in customizer
	add_theme_support( 'custom-logo' );

  // Register wp_nav_menu() menus
	// http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'sage'),
    'secondary_navigation' => __('Secondary Navigation', 'sage'),
  ]);

  // Enable post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails');

  // Enable post formats
  // http://codex.wordpress.org/Post_Formats
  add_theme_support('post-formats', []);

  // Enable HTML5 markup support
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

  //Add image sizes
  add_image_size( 'x-large', 1230, 630);
  add_image_size( '600x400', 600, 400);
  update_option( 'medium_size_w', 400 );
  update_option( 'medium_size_h', 400 );



  // Use main stylesheet for visual editor
  // To add custom styles edit /assets/styles/layouts/_tinymce.scss
  add_editor_style(Assets\asset_path('styles/main.css'));
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');


//Add image sizes to UI
add_filter( 'image_size_names_choose', __NAMESPACE__ . '\\my_custom_sizes' );

function my_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        '600x400' => __( '600x400' ),
        'medium_large' => __( 'Medium Large' ),
        'x-large' => __('X-Large'),
    ) );
}

//add post title to alt tag on featured image
/* Register callback function for post_thumbnail_html filter hook */
add_filter( 'post_thumbnail_html', __NAMESPACE__ . '\\post_thumbnail_alt_change', 10, 5 );
/* Function which will replace alt atribute to post title */
function post_thumbnail_alt_change( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
  if (!is_single()) {
    $post_title = get_the_title();
    $html = preg_replace( '/(alt=")(.*?)(")/i', '$1'.esc_attr( $post_title ).'$3', $html );
  }
  return $html;
}



//Move Gravity Forms Scripts to the Footer
function init_scripts() {
  return true;
}
add_filter('gform_init_scripts_footer', __NAMESPACE__ . '\\init_scripts');

/**
 * Register sidebars
 */
function widgets_init() {
	register_sidebar([
    'name'          => __('Home Widgets 1', 'sage'),
    'id'            => 'home-page-widgets-1',
    'before_widget' => '<div class="widget %1$s %2$s col" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	]);
	register_sidebar([
    'name'          => __('Home Widgets 2', 'sage'),
    'id'            => 'home-page-widgets-2',
    'before_widget' => '<div class="widget %1$s %2$s col" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	]);
	register_sidebar([
    'name'          => __('Home Widgets 3', 'sage'),
    'id'            => 'home-page-widgets-3',
    'before_widget' => '<div class="widget %1$s %2$s col" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
		'after_title'   => '</h2>',
  ]);
  register_sidebar([
    'name'          => __('Footer Upper', 'sage'),
    'id'            => 'sidebar-footer-upper',
    'before_widget' => '<div class="widget %1$s %2$s col" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ]);
  register_sidebar([
    'name'          => __('Footer Lower Left', 'sage'),
    'id'            => 'sidebar-footer-lower-left',
    'before_widget' => '<div class="widget %1$s %2$s" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="font-large widget-title">',
    'after_title'   => '</div>'
	]);
	register_sidebar([
    'name'          => __('Footer Lower Right', 'sage'),
    'id'            => 'sidebar-footer-lower-right',
    'before_widget' => '<div class="widget %1$s %2$s" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="large-font widget-title">',
    'after_title'   => '</div>'
	]);
	register_sidebar([
    'name'          => __('News & Events Intro', 'sage'),
    'id'            => 'sidebar-news-intro',
    'before_widget' => '<div class="new-intro-widget widget %1$s %2$s" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>'
	]);
	register_sidebar([
		'name'          => __('News & Events Sidebar', 'sage'),
		'description' 	=> 'Displays on News & Events landing page and all News & Events Posts, remove all Widgets to hide',
    'id'            => 'sidebar-primary',
    'before_widget' => '<div class="widget %1$s %2$s col" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	]);
	register_sidebar([
    'name'          => __('Our Stories Intro', 'sage'),
    'id'            => 'sidebar-our_stories-intro',
    'before_widget' => '<div class="widget %1$s %2$s" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>'
	]);
	register_sidebar([
		'name'          => __('Our Stories Sidebar', 'sage'),
		'description' 	=> 'Displays on Our Stories landing page and all Our Stories Posts, remove all Widgets to hide',
    'id'            => 'sidebar-stories',
    'before_widget' => '<div class="widget %1$s %2$s col" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	]);
}
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');

//Add options page (to be used with Advanced Custom Fields)
if( function_exists('acf_add_options_page') ) {
  acf_add_options_page(array(
    'page_title'  => 'Theme General Settings',
    'menu_title'  => 'Theme Settings',
    'menu_slug'   => 'theme-general-settings',
    'capability'  => 'edit_posts',
    'redirect'    => false
  ));
}

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
  static $display;

  isset($display) || $display = in_array(true, [
    // The sidebar will be displayed if ANY of the following return true.
    // @link https://codex.wordpress.org/Conditional_Tags

  ]);

  return apply_filters('sage/display_sidebar', $display);
}

/**
 * Theme assets
 */
function assets() {
  wp_enqueue_style('sage/css', Assets\asset_path('styles/main.css'), false, null);

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_enqueue_script('sage/js', Assets\asset_path('scripts/main.js'), ['jquery'], null, false);

  // $google_api = 'https://maps.googleapis.com/maps/api/js?key=' . get_field('google_maps_api_key', 'option');
  // wp_enqueue_script('sage/googleMaps', $google_api,  ['jquery'], null, false);

}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);

/**
 * Admin Assets
 */
function admin_enqueue($hook) {

	wp_enqueue_script('sage/admijs', Assets\asset_path('scripts/admin.js'), ['jquery'], null, false);
}

add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\admin_enqueue');


/**
  ** Remove "Category:" from archive titles
**/
// Simply remove anything that looks like an archive title prefix ("Archive:", "Foo:", "Bar:").
add_filter('get_the_archive_title', function ($title) {
    return preg_replace('/^\w+: /', '', $title);
});


/**
  ** Add styles to editor
**/

function wpb_mce_buttons_2($buttons) {
  array_unshift($buttons, 'styleselect');
  return $buttons;
}
add_filter('mce_buttons_2', __NAMESPACE__ . '\\wpb_mce_buttons_2');


/*
* Callback function to filter the MCE settings
*/

function my_mce_before_init_insert_formats( $init_array ) {

// Define the style_formats array

  $style_formats = array(
/*
* Each array child is a format with it's own settings
* Notice that each array has title, block, classes, and wrapper arguments
* Title is the label which will be visible in Formats menu
* Block defines whether it is a span, div, selector, or inline style
* Classes allows you to define CSS classes
* Wrapper whether or not to add a new block-level element around any selected elements
*/
    array(
      'title' => 'Arrow Link',
      'classes' => 'more-link',
      'block' => 'a',
      'selected' => 'a',
      'wrapper' => false,
    ),
    array(
      'title' => 'Large Font',
      'classes' => 'large-font',
      'block' => 'span',
      'selected' => 'span',
      'wrapper' => false,
    ),
    array(
      'title' => 'Large Bold',
      'classes' => 'large-bold-font',
      'block' => 'span',
      'selected' => 'span',
      'wrapper' => false,
    ),
    array(
      'title' => 'Large Italics',
      'classes' => 'large-italic-font',
      'block' => 'span',
      'selected' => 'span',
      'wrapper' => false,
    ),
    array(
      'title' => 'No Bullets',
      'selected' => 'ul',
      'block' => 'ul',
      'classes' => 'no-bullets',
      'wrapper' => false,
    ),
    array(
      'title' => 'Subhead',
      'selected' => 'div',
      'block' => 'div',
      'classes' => 'subhead',
      'wrapper' => false,
      )
  );
  // Insert the array, JSON ENCODED, into 'style_formats'
  $init_array['style_formats'] = json_encode( $style_formats );

  return $init_array;

}
// Attach callback to 'tiny_mce_before_init'
add_filter( 'tiny_mce_before_init', __NAMESPACE__ . '\\my_mce_before_init_insert_formats' );


//Google maps api key for ACF

function my_acf_init() {
  $api_key = get_field('google_maps_api_key', 'options');
  acf_update_setting('google_api_key', $api_key);
}

add_action('acf/init', __NAMESPACE__ . '\\my_acf_init');

// Register Custom Post Type
function create_custom_post_stories() {

  $labels = array(
    'name'                  => _x( 'Our Stories', 'Post Type General Name', 'sage' ),
    'singular_name'         => _x( 'Story', 'Post Type Singular Name', 'sage' ),
    'menu_name'             => __( 'Our Stories', 'sage' ),
    'name_admin_bar'        => __( 'Our Stories', 'sage' ),
    'archives'              => __( 'Item Archives', 'sage' ),
    'attributes'            => __( 'Item Attributes', 'sage' ),
    'parent_item_colon'     => __( 'Parent Item:', 'sage' ),
    'all_items'             => __( 'All Items', 'sage' ),
    'add_new_item'          => __( 'Add New Item', 'sage' ),
    'add_new'               => __( 'Add New', 'sage' ),
    'new_item'              => __( 'New Item', 'sage' ),
    'edit_item'             => __( 'Edit Item', 'sage' ),
    'update_item'           => __( 'Update Item', 'sage' ),
    'view_item'             => __( 'View Item', 'sage' ),
    'view_items'            => __( 'View Items', 'sage' ),
    'search_items'          => __( 'Search Item', 'sage' ),
    'not_found'             => __( 'Not found', 'sage' ),
    'not_found_in_trash'    => __( 'Not found in Trash', 'sage' ),
    'featured_image'        => __( 'Featured Image', 'sage' ),
    'set_featured_image'    => __( 'Set featured image', 'sage' ),
    'remove_featured_image' => __( 'Remove featured image', 'sage' ),
    'use_featured_image'    => __( 'Use as featured image', 'sage' ),
    'insert_into_item'      => __( 'Insert into item', 'sage' ),
    'uploaded_to_this_item' => __( 'Uploaded to this item', 'sage' ),
    'items_list'            => __( 'Items list', 'sage' ),
    'items_list_navigation' => __( 'Items list navigation', 'sage' ),
    'filter_items_list'     => __( 'Filter items list', 'sage' ),
  );
  $args = array(
    'label'                 => __( 'Story', 'sage' ),
    'description'           => __( 'Post Type Description', 'sage' ),
    'labels'                => $labels,
    'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', ),
    'taxonomies'            => array( 'post_tag' ),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 5,
    'menu_icon'             => 'dashicons-id-alt',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    'has_archive'           => true,
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'capability_type'       => 'page',
    'rewrite'               => array('slug' => 'our-stories')
  );
  register_post_type( 'our_stories', $args );

}
add_action( 'init', __NAMESPACE__ . '\\create_custom_post_stories', 0 );

function change_post_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'News & Events';
    $submenu['edit.php'][5][0] = 'News & Events';
    $submenu['edit.php'][10][0] = 'Add News & Events';
    $submenu['edit.php'][16][0] = 'Tags';
}
function change_post_object() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'News & Events';
    $labels->singular_name = 'News & Events';
    $labels->add_new = 'Add News & Events';
    $labels->add_new_item = 'Add News & Events';
    $labels->edit_item = 'Edit Post';
    $labels->new_item = 'News & Events';
    $labels->view_item = 'View News & Events';
    $labels->search_items = 'Search News & Events';
    $labels->not_found = 'No News & Events found';
    $labels->not_found_in_trash = 'No News & Events found in Trash';
    $labels->all_items = 'All News & Events';
    $labels->menu_name = 'News & Events';
    $labels->name_admin_bar = 'News & Events';
}

add_action( 'admin_menu', __NAMESPACE__ . '\\change_post_label' );
add_action( 'init', __NAMESPACE__ . '\\change_post_object' );


/**
 * Setup suggested plugin system.
 *
 * Include the DisabilityIN_Custom_Theme_Plugin_Manager class and add
 * an interface for users to to manage suggested
 * plugins.
 *
 * @since x.x.x
 *
 * @see  DisabilityIN_Custom_Theme_Plugin_Manager
 * @link http://mypluginmanager.com
 */
function din_plugin_manager() {

	if ( ! is_admin() ) {
		return;
	}

	/**
	 * Include plugin manager class.
	 *
	 * No other includes are needed. The DisabilityIN_Custom_Theme_Plugin_Manager
	 * class will handle including any other files needed.
	 *
	 * If you want to move the "plugin-manager" directory to
	 * a different location within your theme, that's totally
	 * fine; just make sure you adjust this include path to
	 * the plugin manager class accordingly.
	 */
	require_once( get_parent_theme_file_path( '/plugin-manager/class-disabilityin-custom-theme-plugin-manager.php' ) );

	/*
	 * Setup suggested plugins.
	 *
	 * It's a good idea to have a filter applied to this so your
	 * loyal users running child themes have a way to easily modify
	 * which plugins show as suggested for the site they're setting
	 * up for a client.
	 */
	$plugins = apply_filters( 'din_plugins', array(
		array(
			'name'    => 'WP Accessibility',
			'slug'    => 'wp-accessibility',
			'version' => '1.6+',
		),
		array(
			'name'    => 'Yoast SEO',
			'slug'    => 'wordpress-seo',
			'version' => '7.9+',
		),
		array(
			'name'    => 'Gutenberg',
			'slug'    => 'gutenberg',
			'version' => '3.9+',
		),
		array(
			'name'		=> 'Widget CSS Classes',
			'slug'		=> 'widget-css-classes',
			'version' => '1.5+',
		),
		array(
			'name'    => 'Classic Editor',
			'slug'    => 'classic-editor',
			'version' => '0.5+',
		),
		array(
			'name'    => 'Jetpack by WordPress.com',
			'slug'    => 'jetpack',
			'version' => '6.6.1+',
		),
		array(
			'name'    => 'Contact Form 7',
			'slug'    => 'contact-form-7',
			'version' => '5.0.5+',
		),
		array (
			'name'		=> 'Contact Form 7 Accessible Defaults',
			'slug'		=> 'contact-form-7-accessible-defaults',
			'version'	=> '1.1.4+'
		),
		array(
			'name'    => 'Simple Social Icons',
			'slug'    => 'simple-social-icons',
			'version' => '3.0.0+',
		),
	));

	/*
	 * Setup optional arguments for plugin manager interface.
	 *
	 * See the set_args() method of the DisabilityIN_Custom_Theme_Plugin_Manager
	 * class for full documentation on what you can pass in here.
	 */
	$args = array(
		'page_title' => __( 'Suggested Plugins', 'din' ),
		'menu_slug'  => 'din-suggested-plugins',
	);

	/*
	 * Create plugin manager object, passing in the suggested
	 * plugins and optional arguments.
	 */
	$manager = new \DisabilityIN_Custom_Theme_Plugin_Manager( $plugins, $args );

}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\din_plugin_manager' );

