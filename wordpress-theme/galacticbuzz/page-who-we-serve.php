<?php
/**
 * Who we serve page (slug: who-we-serve).
 * Generated from the approved prototype by wordpress-theme/build_theme.py.
 */
get_header();
?>
<section class="page on" id="p-industries">
  <div class="band phero">
    <div class="hx-grid"></div>
    <div class="wrap" style="position:relative">
      <div class="crumbs"><a href="<?php echo gb_url( '' ); ?>">Home</a><span>/</span><span>Who we serve</span></div>
      <h1 style="max-width:18ch">Built for B2B service firms that sell to <span class="grad">decision-makers.</span></h1>
      <p class="lead" style="margin-top:16px">Who our clients sell to, and the channel mix that usually works.</p>
    </div>
  </div>
  <div class="sec">
    <div class="wrap">
      <div class="ind-grid" id="indGrid"><?php gb_industry_cards(); ?></div>
      <p class="small muted" style="margin-top:20px">Selling a different B2B service? <a href="<?php echo gb_url( 'contact', 'book' ); ?>">Ask us</a>.</p>
    </div>
  </div>
  <div class="sec band">
    <div class="hx-grid"></div>
    <div class="wrap" style="position:relative">
      <div class="sec-head"><span class="eyebrow">Markets</span><h2>Four markets, worked in their own time zones.</h2></div>
      <div class="orbit-wrap">
        <div class="orbit" id="orbit" data-book="<?php echo gb_url( 'contact', 'book' ); ?>">
          <div class="ring" style="width:56%;height:56%"></div>
          <div class="ring" style="width:84%;height:84%"></div>
          <div class="core">Galactic<br>buzz</div>
        </div>
        <div class="region-card" id="regionCard"></div>
      </div>
    </div>
  </div>
  <div class="sec tight">
    <div class="wrap"><div class="cta2 band"><div class="hx-grid"></div><div><h2>Let’s map your buyers.</h2><p class="lead" style="margin-top:10px">Bring your three best clients. We’ll find more like them.</p></div><div class="actions"><a class="btn btn-primary btn-lg" href="<?php echo gb_url( 'contact', 'book' ); ?>">Book a call</a></div></div></div>
  </div>
</section>
<?php get_footer();
