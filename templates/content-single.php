<?php
use Roots\Sage\Extras;
?>
<div class="container">
<?php
while (have_posts()) : the_post(); ?>

  <?php
  $a_link = get_post_type_archive_link($post->post_type);
  $post_type = get_post_type_object( get_post_type($post) );
  $archive_link = '<ul class="archive-link list-inline">' . get_archives_link($a_link, $post_type->labels->name) . '</ul>';
  ?>
  <article <?php post_class(); ?>>
    <header>
      <?php echo $archive_link; ?>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php if ( get_post_type() == 'post' ) :
        get_template_part('templates/entry-meta');
        endif;
      ?>
    </header>
    <div class="entry-content">
      <?php Extras\get_thumb_with_caption(true); ?>
      <?php the_content(); ?>
    </div>
    <footer class="post-footer">
      <?php get_template_part('templates/social', 'share'); ?>
    </footer>

  </article>
<?php endwhile; ?>
</div>
