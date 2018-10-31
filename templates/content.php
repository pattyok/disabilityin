<?php
use Roots\Sage\Extras;
?>
<article <?php post_class(); ?>>
<?php
  $thumb = get_the_post_thumbnail();
  if ($thumb) { ?>
    <div class="row"><div class="col col-12 col-md-4">
    <div class="thumb-wrapper"><?php the_post_thumbnail(); ?></div>
    </div><div class="col col-12 col-md-8">
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
  <?php if ($thumb) { ?>
    </div></div>
  <?php } ?>
</article>
