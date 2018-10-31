<?php
use Roots\Sage\Extras;


  $thumb = get_the_post_thumbnail();
  if ($thumb) { ?>
    <div class="responsive-img-wrapper"><?php the_post_thumbnail(); ?></div>
  <?php } ?>
  <header>
    <h3 class="entry-title"><?php the_title(); ?></h3>
    <?php if ( get_post_type() == 'post' ) :
      get_template_part('templates/entry-meta');
      endif;
    ?>
  </header>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
  </div>

