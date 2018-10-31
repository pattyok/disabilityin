<?php use Roots\Sage\Titles; ?>

<?php

  $title_block = '';
  //if (!get_field('hide_page_title')){
    $title_block = '<h1 class="page-title">' . Titles\title() . '</h1>';
  //}
  echo '<div class="page-header-no-image"><div class="container">' . $title_block . '</div></div>';
  ?>
