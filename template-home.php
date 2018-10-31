<?php
/**
 * Template Name: Home Page Template
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'home'); ?>
<?php endwhile; ?>
