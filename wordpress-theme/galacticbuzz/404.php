<?php
/**
 * Page not found.
 */
get_header();
?>
<section class="page on">
  <div class="band" style="position:relative">
    <div class="hx-grid"></div>
    <div class="wrap not-found" style="position:relative">
      <span class="eyebrow">404</span>
      <h1 style="font-size:clamp(2.4rem,5vw,3.8rem)">This page drifted out of orbit.</h1>
      <p class="lead" style="margin-inline:auto">The link may be old or mistyped.</p>
      <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap"><a class="btn btn-light btn-lg" href="<?php echo gb_url(); ?>">Go to the homepage</a><a class="btn btn-glass btn-lg" href="<?php echo gb_url( 'contact' ); ?>">Contact us</a></div>
    </div>
  </div>
</section>
<?php
get_footer();
