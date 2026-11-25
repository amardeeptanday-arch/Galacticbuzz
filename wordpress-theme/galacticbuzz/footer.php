<?php
/**
 * Site footer. Generated from the approved prototype by wordpress-theme/build_theme.py.
 */
?>
</main>

<footer>
  <div class="wrap">
    <div class="foot-grid" style="grid-template-columns:minmax(0,1.6fr) repeat(3,minmax(0,1fr))">
      <div class="foot-brand">
        <a class="logo" href="<?php echo gb_url( '' ); ?>"><svg><use href="#i-logo"/></svg><span>galactic<b>buzz</b></span></a>
        <p class="small muted" style="margin-top:14px;max-width:34ch">Your virtual BD team. LinkedIn, cold email, calling and branding, run from your own profile.</p>
      </div>
      <div><h5>Services</h5><ul><li><a href="<?php echo gb_url( 'services', 'svc-li' ); ?>">LinkedIn outreach</a></li><li><a href="<?php echo gb_url( 'services', 'svc-email' ); ?>">Cold email</a></li><li><a href="<?php echo gb_url( 'services', 'svc-call' ); ?>">Cold calling</a></li><li><a href="<?php echo gb_url( 'services', 'svc-brand' ); ?>">Branding &amp; content</a></li></ul></div>
      <div><h5>Company</h5><ul><li><a href="<?php echo gb_url( 'about' ); ?>">About</a></li><li><a href="<?php echo gb_url( 'who-we-serve' ); ?>">Who we serve</a></li><li><a href="<?php echo gb_url( '', 'how' ); ?>">How it works</a></li><li><a href="<?php echo gb_url( 'contact' ); ?>">Contact</a></li></ul></div>
      <div><h5>More</h5><ul><li><a href="<?php echo gb_url( 'find-a-partner' ); ?>">Hiring a vendor?</a></li><?php if ( gb_mod( 'linkedin' ) ) : ?><li><a href="<?php echo esc_url( gb_mod( 'linkedin' ) ); ?>" target="_blank" rel="noopener">LinkedIn</a></li><?php endif; ?><li><a href="<?php echo gb_url( 'privacy-policy' ); ?>">Privacy</a></li><li><a href="<?php echo gb_url( 'terms' ); ?>">Terms</a></li></ul></div>
    </div>
    <div class="foot-bottom"><span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> Galacticbuzz</span><span class="mono xs">galacticbuzz.io</span></div>
  </div>
</footer>

<?php if ( gb_current() !== 'contact' ) : ?><div class="m-sticky"><a class="btn btn-primary" href="<?php echo gb_url( 'contact', 'book' ); ?>">Book a call</a></div><?php endif; ?>

<div class="modal" id="profileModal" hidden role="dialog" aria-modal="true" aria-labelledby="pmName">
  <div class="modal-box"><button class="modal-x" type="button" data-close aria-label="Close">×</button><div id="profileBody"></div></div>
</div>
<div class="toast" id="toast" hidden role="status"></div>
<?php wp_footer(); ?>
</body>
</html>
