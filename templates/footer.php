<?php
use Roots\Sage\Assets;
use Roots\Sage\Extras;

?>

<footer class="footer-main">
  <div class="container to-top-wrapper">
    <div class="to-top" aria-label="Back to Top"><i class="fa fa-chevron-up" aria-hidden="true"></i></div>
	</div>
	<?php if (is_active_sidebar( 'sidebar-footer-upper')) { ?>
  <section class="section footer-upper">
    <div class="container">
      <div class="row">
        <?php dynamic_sidebar('sidebar-footer-upper'); ?>
      </div>
    </div>
	</section>
	<?php } ?>
  <section class="section footer-lower">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-8 col-footer-main">
          <?php dynamic_sidebar('sidebar-footer-lower-left'); ?>
        </div>
        <div class="col-12 col-md-4 text-md-right">
					<?php dynamic_sidebar('sidebar-footer-lower-right'); ?>
          </div>
      </div>
      <div class="row">
        <div class="col-12 text-center text-md-right">
          <div class="copyright">
            Copyright &copy; <?php echo date("Y"); ?> - <?php bloginfo('name'); ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</footer>
