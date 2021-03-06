<?php
use Roots\Sage\Extras;


  $thumb = get_the_post_thumbnail();
  if ($thumb) { ?>
    <div class="responsive-img-wrapper"><?php the_post_thumbnail(); ?></div>
  <?php } ?>
  <header>
    <h3 class="entry-title"><?php the_title(); ?></h3>
    <?php get_template_part( 'templates/entry-meta', get_post_type() == 'post' ? '' : get_post_type() ); ?>
  </header>
  <div class="entry-summary">
		<?php the_excerpt(); ?>
		<a href="<?php the_permalink(); ?>" class="read-more-link"> <?php echo __('Continue Reading', 'sage') ?> <span class="sr-only"><?php the_title(); ?></span></a>
  </div>

