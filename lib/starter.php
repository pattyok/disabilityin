<?php

namespace Roots\Sage\Starter;

use Roots\Sage\Assets;
use Roots\Sage\Extras;



class startWorker {
	function __construct() {
		//Only create  content if this is the first time applying the theme.
		$theme_version = get_option('disabilityin_version');

		if (empty($theme_version)) {
			//create pages
			add_action( 'after_switch_theme', array($this, 'make_sample_pages'), 5 );
			//create menu
			add_action( 'after_switch_theme', array($this, 'make_top_menu'), 10 ); //cant set location of an empty menu so this must come after pages
			// //manipulate blog/home pages
			add_action( 'after_switch_theme',  array($this, 'make_post'), 15 );
			add_action( 'after_switch_theme', array($this, 'setup_post_categories'), 30 );
			// //make pages
			add_action( 'after_switch_theme', array($this, 'set_widgets'),650 );

			add_action( 'after_switch_theme', array($this, 'set_version'),650 );

			$this->menu_name = 'DIN Top Menu';
			$this->menu_id = '';
			$this->menu_items = array();
		}
	}

	public function set_version() {
		$my_theme = wp_get_theme();
		update_option('disabilityin_version', $my_theme->get( 'Version' ));
	}

	public function make_top_menu() {

		// Check if the menu exists
		$menu_exists = wp_get_nav_menu_object( $this->menu_name );

		// If it doesn't exist, let's create it.
		if ( !$menu_exists ) {
				$this->menu_id = wp_create_nav_menu($this->menu_name);
		} else {
				$menu_header = get_term_by('name', $this->menu_name, 'nav_menu');
				if ( is_wp_error( $menu_header ) ) {
				// something went wrong
				//Extras\write_log( $menu_header->get_error_message());
				}
				$this->menu_id = $menu_header->term_id;
		}
		//cant set location on an empty menu...
		$this->populate_menu($this->menu_id, $this->menu_items);
		//$locations = get_theme_mod('nav_menu_locations');
		$locations = get_nav_menu_locations();
		$locations['primary_navigation'] = $this->menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
		$locations = get_theme_mod('nav_menu_locations');
	}

	public function make_sample_pages(){
		$file = Assets\asset_path('data/pages.csv');
		$pages = array_map('str_getcsv', file($file));
		array_walk($pages, function(&$a) use ($pages) {
			$a = array_combine($pages[0], $a);
		});
		array_shift($pages); # remove column header

		foreach ($pages as &$settings) {
			if (!empty($settings['post_type'])) {
				$this->make_new_page($settings);
			} else {
				$this->make_menu_item_link($settings);
			}
		}
	}

	//convert hello-world to sample post
		public function make_post() {
			$post = get_page_by_path('hello-world', OBJECT, 'post');
			if ($post){
					$my_post = array(
						'ID'           => $post->ID,
						'post_title'   => 'Welcome to our new look',
						'post_content' => 'This is a sample blog post, you may want to write about your name and branding change here',
						'post_status'   => 'publish',
				);

				wp_update_post( $my_post );
			}
		}

	private function make_new_page( $settings ) {
		$slug = $settings['slug'];
		$new_page_title = $settings['new_page_title'];
		$new_page_content = $settings['new_page_content'];
		$image = $settings['image'];
		$menu = $settings['menu'];
		$special = $settings['special'];
		$post_type = $settings['post_type'];
		$new_page_template = $settings['new_page_template'];


		$page_check = get_page_by_title($new_page_title);   // Check if the page already exists
		$new_page = array(
						'post_type'     => $post_type,
						'post_title'    => $new_page_title,
						'post_content'  => $new_page_content,
						'post_status'   => 'publish',
						'post_author'   => 1,
						'post_slug'     => $slug
		);
		// If the page doesn't already exist, create it
		if ( !isset($page_check->ID) ) {
				//make page
				$new_page_id = wp_insert_post($new_page);
				//add featured image
				if (!empty($image)){
						$this->add_image($new_page_id, $image);
				}
		} else {
				$new_page_id = $page_check->ID;
				//Extras\write_log($new_page_title . ' exists, skipping');
		}
		//make menu item
		if ( $menu == 'TRUE' ) {
			$this->make_menu_item($new_page_id, $new_page_title, $slug);
		} else {
			//Extras\write_log($new_page_title . ' no menu');
		}

		//assign page template
		if ( !empty($new_page_template) ) {
			update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
		}

		if ($special == 'page_for_posts') {
			update_option( 'page_for_posts', $new_page_id );
		} elseif ($special == 'front_page') {
			update_option( 'page_on_front', $new_page_id );
			update_option( 'show_on_front', 'page' );
		}

	}

	private function make_menu_item_link($settings) {
		// Set up custom links
		$this->menu_items[] = array (
			'menu-item-title' =>  __($settings['new_page_title']),
			'menu-item-classes' => $settings['slug'],
			'menu-item-url' => home_url($settings['slug'], 'relative'),
			'menu-item-status' => 'publish');
	}


	private function make_menu_item($post_id, $title, $slug) {
		$this->menu_items[] = array (
					'menu-item-title' => $title,
					'menu-item-object-id' => $post_id,
					'menu-item-object' => 'page',
					'menu-item-status' => 'publish',
					'menu-item-type' => 'post_type',
					'menu-item-classes' => $slug,
			);
	}

	private function populate_menu($menu_id, $menu_items) {
		foreach ($menu_items as $item) {
			//Extras\write_log($menu_id . ' menu id ' . $post_id);
			if ( $this->item_is_in_menu( $menu_id, $item['menu-item-title'] ) ) {
					//Extras\write_log( $menu_id . ' already has ' . $item['menu-item-title']);
			} else {
				//Extras\write_log( 'Adding to ' . $menu_id . ' : ' . $item['menu-item-title']);
				wp_update_nav_menu_item($menu_id, 0, $item);
			}
		}
	}


	private function item_is_in_menu( $menu = null, $item_title = null ) {

		// get menu object
		$menu_object = wp_get_nav_menu_items( esc_attr( $menu ) );

		// stop if there isn't a menu
		if( ! $menu_object )
				return false;

		// get the title field out of the menu object
		$menu_items = [];
		foreach ($menu_object as $item) {
			$menu_items[] = wp_specialchars_decode($item->title);
		}

		// test if the specified page is in the menu or not. return true or false.
		return in_array( wp_specialchars_decode($item_title), $menu_items );

	}

	public function setup_post_categories() {
		// Uncategorized ID is always 1
		wp_update_term(1, 'category', array(
			'name' => 'News',
			'slug' => 'news',
			'description' => ''
		));

		wp_insert_term('Events', 'category', array(
			'name' => 'Events',
			'slug' => 'events',
			'description' => ''
		));
	}

	public function set_widgets() {
		//This removes all widgets, we only run this function when theme is activated for the first time
		update_option('sidebars_widgets', array());
		//
		$this->pre_add_widget( 'home-page-widgets-1', 'custom_html',
			array(
					'title' => '',
					'content' => '<h2 class="sans-serif blue">Add a Custom Banner to your home page</h2><a class="link-learn-more" href="#">Learn More</a>',
					'classes' => 'align-center',
					'filter' => false,
			)
		);
		$this->pre_add_widget( 'home-page-widgets-2', 'text',
			array(
					'title' => 'Widget Section 2',
					'text' => 'Add or Edit Widgets in the Customizer',
					'filter' => false,
			)
		);
		$this->pre_add_widget( 'home-page-widgets-2', 'text',
			array(
					'title' => 'Widget Section 2',
					'text' => 'Add or Edit Widgets in the Customizer',
					'filter' => false,
			)
		);
		$this->pre_add_widget( 'home-page-widgets-3', 'text',
			array(
					'title' => 'Widget Section 3',
					'text' => 'Add or Edit Widgets in the Customizer',
					'filter' => false,
			)
		);
		$this->pre_add_widget( 'sidebar-footer-upper', 'text',
			array(
					'title' => 'Upper Footer Widget',
					'text' => 'You can add a contact form here or other content that you would like on every page. Delete this widget to remove the section',
					'filter' => false,
			)
		);

		$this->pre_add_widget( 'sidebar-footer-lower-left', 'custom_html',
			array(
					'title' => 'Disability:IN Your State Here',
					'content' => '<p>A State Chapter of&nbsp;<a href="https://disabilityin.org/">Disability:IN</a></p>
					<address>Address line 1<br>
					Address line 1</address>',
					'filter' => false,
			)
		);

		$this->pre_add_widget( 'sidebar-footer-lower-right', 'text',
			array(
					'title' => 'Lower Footer Right Widget',
					'text' => 'Replace with Social Icons Widget or other Widget that you would like to see on every page',
					'filter' => false,
			)
		);
	}

	private function pre_add_widget( $sidebar, $name, $args = array() ) {
		if ( ! $sidebars = get_option( 'sidebars_widgets' ) )
				$sidebars = array();

		// Create the sidebar if it doesn't exist.
		if ( ! isset( $sidebars[ $sidebar ] ) )
				$sidebars[ $sidebar ] = array();

		// Check for existing saved widgets.
		if ( $widget_opts = get_option( "widget_$name" ) ) {
				// Get next insert id.
				ksort( $widget_opts );
				end( $widget_opts );
				$insert_id = key( $widget_opts );
				if (!is_int($insert_id)) {
					$insert_id = 0;
				}
		} else {
				// None existing, start fresh.
				$widget_opts = array( '_multiwidget' => 1 );
				$insert_id = 0;
		}

		// Add our settings to the stack.
		$widget_opts[ ++$insert_id ] = $args;
		// Add our widget!
		$sidebars[ $sidebar ][] = "$name-$insert_id";

		update_option( 'sidebars_widgets', $sidebars );
		update_option( "widget_$name", $widget_opts );
	}
}

new StartWorker();




function add_image($post, $image){
	//Extras\write_log('start image ' . $image . ' page is ' .  $post);
	$file = Assets\asset_path('/images/' . $image);
	$filename = basename($file);
	$upload_file = wp_upload_bits($filename, null, file_get_contents($file));

	if (!$upload_file['error']) {
			$wp_filetype = wp_check_filetype($filename, null );
			$attachment = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_parent' => $post,
					'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
					'post_content' => '',
					'post_status' => 'inherit'
			);
			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $post );
			set_post_thumbnail($post, $attachment_id);
			//Extras\write_log('Done ' . $image . ' page is ' .  $post);
			if (!is_wp_error($attachment_id)) {
					require_once(ABSPATH . "wp-admin" . '/includes/image.php');
					$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
					wp_update_attachment_metadata( $attachment_id,  $attachment_data );
			}else {
					//Extras\write_log('ERROR' . $post . ' page is ' . is_wp_error($attachment_id) . $upload_file['error']);
			}
	}else{
			//Extras\write_log('ERROR' . $post . ' page is ' .  $upload_file['error']);
	}
}


//-------------------------------------NOT USING BELOW LINE-----------------------------------------------//









function tfc_make_homepage(){

    $new_page_title     = __('Front Page',''); // Page's title
    $front_page_check = get_page_by_title($new_page_title);// Check if the page already exists

    if (!isset($front_page_check->ID)){//we don't have this page already
        $new_page_content   = '';                           // Content goes here
        $page_check = get_page_by_title('Sample Page');  //See if there's a Sample Page we can update
        // Store the above data in an array
        $new_page = array(
                'post_type'     => 'page',
                'post_title'    => $new_page_title,
                'post_content'  => $new_page_content,
                'post_status'   => 'publish',
                'post_author'   => 1,
        );
        // If the page doesn't already exist, create it
        if (!isset($page_check->ID)){//we don't have a Sample Page
            $new_page_id = wp_insert_post($new_page);
            //Extras\write_log($new_page_title . ' page is created ' . $new_page_id);
        }else {
            wp_update_post( array(
                'ID' => $page_check->ID,
                'post_title'    => $new_page_title,
                'post_content'  => $new_page_content,'post_status'   => 'publish'
            )
        );
        }
    }
    $homepage = get_page_by_title( 'Front Page' );
    //even if Front Page already exists, let's make sure it's got the right settings
    if ( $homepage ){
        update_option( 'page_on_front', $homepage->ID );
        update_option( 'show_on_front', 'page' );
    }
}

function tfc_fix_blog(){

    $new_page_title     = __('Latest News',''); // Page's title
    $page_check = get_page_by_title($new_page_title);// Check if the page already exists

    $blog_id = get_option( 'page_for_posts' );  //See if there's a blog page to remove
    wp_delete_post($blog_id);

    if (!isset($page_check->ID)){//we don't have this page already
        tfc_make_new_page("news","Latest News","Page that shows posts","flag.jpg","TRUE","TRUE","");

    }
    $news = get_page_by_title($new_page_title);// get the page id
    //set the page as page for posts and make menu items
    if ( $news ){
        update_option( 'page_for_posts', $news->ID );
    }

}






function tfc_set_widgets(){
$sidebar_id = 'footer-bottom-widgets';
$add_to_sidebar[$sidebar_id] = array(
     array(
         'id_base'=> 'tfc_paid_for_widget',
         'instance' => array(
         )
     )
 );

auto_add_sidebar_widgets($add_to_sidebar[$sidebar_id]);

$sidebar_id = 'footer-one-widgets';
$add_to_sidebar[$sidebar_id] = array(
     array(
         'id_base'=> 'recent-posts',
         'instance' => array(
                 'title' => 'Latest News',
         )
     )
 );
auto_add_sidebar_widgets($add_to_sidebar);
}

function auto_add_sidebar_widgets( $add_to_sidebar = array(), $ignore_sidebar_with_content = true ){

    if(empty($add_to_sidebar)){
        return;
    }

    $sidebar_options = get_option('sidebars_widgets');


    foreach($add_to_sidebar as $sidebar_id => $widgets){

        //** do not add widgets if sidebar already has content
        if ( ! empty( $sidebar_options[$sidebar_id] ) && $ignore_sidebar_with_content) {
            continue;
        }

        foreach ($widgets as $index => $widget){
            $widget_id_base      = $widget['id_base'];
            $widget_instance  = $widget['instance'];

            $widget_instances = get_option('widget_'.$widget_id_base);

            if(!is_array($widget_instances)){
                $widget_instances = array();
            }

            $count = count($widget_instances)+1;


            $sidebar_options[$sidebar_id][] = $widget_id_base.'-'.$count;

            $widget_instances[$count] = $widget_instance;

            //** save widget options
            update_option('widget_'.$widget_id_base,$widget_instances);
        }
    }

    //** save sidebar options:
    update_option('sidebars_widgets',$sidebar_options);
}

?>