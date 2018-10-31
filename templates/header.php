<?php
use Roots\Sage\Assets;

?>

<header class="banner clearfix">
  <div class="row">
    <div class="col-12 header-main">
      <div class="container">
        <div class="banner-brand">
					<?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo()) {
						the_custom_logo();
					} else {
					?>	<a class="custom-logo-link default" href="<?= esc_url(home_url('/')); ?>" ><img src="<?php echo Assets\asset_path('images/logo-2x.png') ?>" src="<?php echo Assets\asset_path('images/logo-2x.png') ?>" alt="<?php bloginfo('name'); ?>" /></a>
					<?php } ?>
        </div>
        <div id="header-nav-wrapper" class="header-nav-wrapper collapse">
          <nav class="navbar navbar-primary" role="navigation">
              <?php
              if (has_nav_menu('primary_navigation')) :
                wp_nav_menu([
                  'theme_location' => 'primary_navigation',
                  'depth' => 2,
                  'menu_class'        => 'nav nav-primary',
                  'echo'              => true
                  ]);
              endif;
              ?>
          </nav>
          <div class="navbar navbar-secondary">
            <?php
              if (has_nav_menu('secondary_navigation')) :
                wp_nav_menu([
                  'theme_location' => 'secondary_navigation',
                  'depth' => 1,
                  'menu_class'        => 'nav nav-secondary',
                  'echo'              => true
                  ]);
              endif;
              ?>
            <?php
              if (has_nav_menu('header_buttons')) :
                wp_nav_menu([
                  'theme_location' => 'header_buttons',
                  'depth' => 1,
                  'menu_class'        => 'nav nav-header-buttons',
                  'echo'              => true
                  ]);
              endif;
              ?>
          </div>
        </div>
      </div>
      <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#header-nav-wrapper" aria-controls="collapsingNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa fa-bars" aria-hidden="true"></i>
        <span class="icon-bar-label"> Menu</span>
  </button>
      <div class="search-bar">
        <div class="container">
          <form role="search" method="get" class="search-form" action="/">
            	<label for="s">
							<span class="screen-reader-text">Search for:</span>
							</label>
							<input type="search" class="search-field" placeholder="Search &hellip;" value="" name="s" />
							<button type="submit" class="search-submit" value="Search"><span class="icon"><i class="fa fa-search"></i></span><span class="sr-only">Submit Search</span></button>

            </form>
          <div id="wp-accessibility-tools"></div>
            </div>
      </div>
  </div>

</header>

