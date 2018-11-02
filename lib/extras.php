<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;

if (!function_exists('write_log')) {

	function write_log($log) {
			//if (true === WP_DEBUG) {
					if (is_array($log) || is_object($log)) {
							error_log(print_r($log, true));
					} else {
							error_log($log);
					}
			//}
	}

}

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (Setup\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '" class="read-more-link">' . __('Continue Reading', 'sage') . '<span class="sr-only">' . get_the_title() . '</span></a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');


function get_id_by_slug($page_slug) {
	$page = get_page_by_path($page_slug);
	if ($page) {
			return $page->ID;
	} else {
			return null;
	}
}

/**
 * Make Excerpt
  @params: content - string
  @params: count - word count
  returns: shortened content
 */
function make_excerpt($text, $count = 55) {
  $arr = explode(' ', $text, $count + 1);
  if (count($arr) > $count) {
    $remove_last = array_pop($arr);
  }
  return implode(' ', $arr);
}


function get_thumb_with_caption($echo = true) {
  $html = '';
  $thumb = get_the_post_thumbnail();
  if ($thumb){
    $html .= '<div class="post-featured-img">';
    $html .= $thumb;
    $caption = get_post(get_post_thumbnail_id())->post_excerpt;
    if ($caption) {
      $html .= '<div class="wp-caption-text">' . $caption . '</div>';
    }
    $html .= '</div>';
  }
  if ($echo) {
    echo $html;
  } else {
    return $html;
  }
}

function get_responsive_image( $id, $size='x-large', $echo = true) {
  $img = wp_get_attachment_image( $id, $size );
  if ($img){
    $html = '<div class="responsive-img-wrapper">';
    $html .= $img;
    $html .= '</div>';
    return $html;
  } else {
    //error_log('img is empty');
    return;
  }
}