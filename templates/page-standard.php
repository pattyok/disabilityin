<article class="entry-content">
<?php
if (get_the_content()) :
  echo '<section class="section section-page-content">';
  if (get_field('sidebar_content')) {
    echo '<div class="entry-content-aside">' . get_field('sidebar_content') . '</div>';
  }
  the_content();
  echo '</section>';
endif;
?>

<?php if( have_rows('sections') ):
  while( have_rows('sections') ): the_row();
    $row = get_row_layout();
    switch($row) {
      case 'multi_column_simple':
        include(locate_template('templates/section-multicolumn-simple.php'));
        break;
      case 'flexi_column':
        include(locate_template('templates/section-multicolumn.php'));
        break;
      case 'impact_stats':
        include(locate_template('templates/section-impactstats.php'));
        break;
    }
  endwhile;
  endif;
?>
</article>
