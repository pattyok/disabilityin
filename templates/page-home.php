<?php
	include(locate_template('templates/section-home-header.php'));
	$sidebar = 'home-page-widgets-1';
	include(locate_template('templates/section-home-widgets.php'));
	$post_type = 'post';
	include(locate_template('templates/section-home-blog.php'));
	$post_type = 'our_stories';
	include(locate_template('templates/section-home-blog.php'));
	$sidebar = 'home-page-widgets-2';
	include(locate_template('templates/section-home-widgets.php'));
	$sidebar = 'home-page-widgets-3';
	include(locate_template('templates/section-home-widgets.php'));
	$content = get_the_content();
?>

