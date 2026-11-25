<?php
/**
 * Contact page (slug: contact).
 * Generated from the approved prototype by wordpress-theme/build_theme.py.
 */
get_header();
?>
<section class="page on" id="p-contact">
  <div class="sec" style="padding-bottom:40px">
    <div class="wrap">
      <div class="sec-head" style="margin-bottom:36px"><span class="eyebrow">Contact</span><h1 style="font-size:clamp(2.2rem,4.5vw,3.4rem)">Talk to a person.</h1><p class="lead">Pick what fits. <?php echo esc_html( gb_mod( 'reply_time' ) ); ?></p></div>
      <div class="routes">
        <a class="route spot" href="<?php echo gb_url( 'contact', 'book' ); ?>"><span class="ico-b"><svg><use href="#i-cal"/></svg></span><div><span class="xs mono muted">GROW YOUR PIPELINE</span><h3>Book a call</h3><p>30 minutes on your buyers, your channels and what month one looks like.</p></div><span class="go">Pick a time →</span></a>
        <a class="route spot" href="<?php echo gb_url( 'find-a-partner' ); ?>"><span class="ico-b"><svg><use href="#i-search"/></svg></span><div><span class="xs mono muted">HIRING A VENDOR?</span><h3>Find a partner</h3><p>Looking for an accounting, data, AI or software firm? We can introduce you.</p></div><span class="go">Start a brief →</span></a>
        <a class="route spot" href="<?php echo gb_url( 'contact', 'message' ); ?>"><span class="ico-b"><svg><use href="#i-mail"/></svg></span><div><span class="xs mono muted">ANYTHING ELSE</span><h3>Send a message</h3><p>Partnerships, press, careers or a quick question.</p></div><span class="go">Write to us →</span></a>
      </div>
    </div>
  </div>

  <div class="sec" id="book" style="background:var(--surface-2);border-block:1px solid var(--line)">
    <div class="wrap split" style="align-items:start">
      <div>
        <span class="eyebrow">Book a call</span>
        <h2 style="margin-top:14px">Let’s plan your outbound.</h2>
        <p class="lead" style="margin-top:14px">We’ll look at who you sell to, which channels fit, and what your first 90 days would look like.</p>
        <?php $gb_booking = gb_mod( 'booking_url' ); if ( $gb_booking ) : ?>
        <div class="card book-embed"><iframe src="<?php echo esc_url( $gb_booking ); ?>" title="Book a call" loading="lazy"></iframe></div>
        <?php else : ?>
        <div class="card" style="margin-top:28px;display:grid;gap:16px">
          <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap"><b>Pick a preferred time</b><span class="xs muted">We confirm by email</span></div>
          <div class="days" id="cDays"></div>
          <div class="slots" id="cSlots"></div>
        </div>
        <?php endif; ?>
      </div>
      <div>
        <form class="card raised" id="callForm" style="display:grid;gap:16px" novalidate><input type="text" name="gb_hp" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
          <h3>About your firm</h3>
          <div class="row2">
            <div class="field"><label for="v-firm">Firm name</label><input class="input" id="v-firm" placeholder="Ledgerline Partners" data-req></div>
            <div class="field"><label for="v-web">Website</label><input class="input" id="v-web" placeholder="ledgerline.in"></div>
          </div>
          <div class="field"><label for="v-svc">Your industry</label><select class="input" id="v-svc" data-req><option value="">Choose…</option><option>Offshore accounting</option><option>IT services &amp; software</option><option>SaaS</option><option>Fractional CFO / finance</option><option>Design studio</option><option>Data engineering</option><option>AI / ML</option><option>PropTech</option><option>Other B2B service</option></select></div>
          <div class="field"><span class="lbl">Where do you want clients?</span>
            <div class="chips-pick">
              <div class="pick"><input type="checkbox" id="vr1"><label for="vr1">USA</label></div>
              <div class="pick"><input type="checkbox" id="vr2"><label for="vr2">UK</label></div>
              <div class="pick"><input type="checkbox" id="vr3"><label for="vr3">UAE</label></div>
              <div class="pick"><input type="checkbox" id="vr4"><label for="vr4">Australia</label></div>
            </div></div>
          <div class="field"><span class="lbl">Channels you’re interested in</span>
            <div class="chips-pick">
              <div class="pick"><input type="checkbox" id="ch1" checked><label for="ch1">LinkedIn</label></div>
              <div class="pick"><input type="checkbox" id="ch2"><label for="ch2">Cold email</label></div>
              <div class="pick"><input type="checkbox" id="ch3"><label for="ch3">Cold calling</label></div>
              <div class="pick"><input type="checkbox" id="ch4"><label for="ch4">Branding</label></div>
              <div class="pick"><input type="checkbox" id="ch5"><label for="ch5">Not sure yet</label></div>
            </div></div>
          <div class="row2">
            <div class="field"><label for="v-name">Your name</label><input class="input" id="v-name" placeholder="Priya Shah" data-req></div>
            <div class="field"><label for="v-email">Work email</label><input class="input" type="email" id="v-email" placeholder="priya@ledgerline.in" data-req></div>
          </div>
          <p class="err" hidden></p>
          <button class="btn btn-primary btn-lg" type="submit"><?php echo $gb_booking ? 'Send details' : 'Request my call'; ?> <svg width="18" height="18"><use href="#i-arrow"/></svg></button>
        </form>
        <div class="card raised done-panel" id="callDone" hidden>
          <span class="pill pass" style="justify-self:start;font-size:.78rem;padding:6px 12px"><svg width="14" height="14"><use href="#i-check"/></svg>Request sent</span>
          <h3 style="font-size:1.8rem">Thanks. We’ll be in touch.</h3>
          <p class="muted"><?php if ( $gb_booking ) : ?>If you haven’t picked a time in the calendar yet, do that now.<?php else : ?>We’ll confirm <span id="callSlot">a time</span> by email.<?php endif; ?> If you can, send us your LinkedIn profile and your three best clients before the call.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="sec" id="message">
    <div class="wrap split" style="align-items:start">
      <div>
        <form class="card raised" id="msgForm" style="display:grid;gap:16px" novalidate><input type="text" name="gb_hp" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
          <span class="eyebrow">Send a message</span>
          <h3>How can we help?</h3>
          <div class="field"><span class="lbl">Topic</span>
            <div class="chips-pick">
              <div class="pick"><input type="radio" name="topic" id="tp1" value="Partnership" checked><label for="tp1">Partnership</label></div>
              <div class="pick"><input type="radio" name="topic" id="tp2" value="Careers"><label for="tp2">Careers</label></div>
              <div class="pick"><input type="radio" name="topic" id="tp3" value="Press"><label for="tp3">Press</label></div>
              <div class="pick"><input type="radio" name="topic" id="tp4" value="Something else"><label for="tp4">Something else</label></div>
            </div></div>
          <div class="row2">
            <div class="field"><label for="g-name">Name</label><input class="input" id="g-name" data-req autocomplete="name"></div>
            <div class="field"><label for="g-email">Email</label><input class="input" type="email" id="g-email" data-req autocomplete="email"></div>
          </div>
          <div class="field"><label for="g-msg">Message</label><textarea class="input" id="g-msg" data-req></textarea></div>
          <p class="err" hidden></p>
          <button class="btn btn-primary" type="submit" style="justify-self:start">Send message</button>
        </form>
        <div class="card raised done-panel" id="msgDone" hidden>
          <span class="pill pass" style="justify-self:start;font-size:.78rem;padding:6px 12px"><svg width="14" height="14"><use href="#i-check"/></svg>Message sent</span>
          <h3 style="font-size:1.6rem">Thanks. <?php echo esc_html( gb_mod( 'reply_time' ) ); ?></h3>
          <button class="btn btn-ghost" type="button" id="msgAgain" style="justify-self:start">Send another</button>
        </div>
      </div>
      <div class="contact-card">
        <h3>Contact details</h3>
        <ul class="clean cdl">
          <?php $gb_email = gb_mod( 'contact_email' ); if ( $gb_email ) : ?>
          <li><span class="mono xs muted">EMAIL</span><div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap"><a class="mono" style="font-weight:600;text-decoration:none" href="mailto:<?php echo esc_attr( antispambot( $gb_email ) ); ?>"><?php echo esc_html( antispambot( $gb_email ) ); ?></a><button class="btn btn-ghost btn-sm" type="button" id="copyEmail" data-email="<?php echo esc_attr( $gb_email ); ?>">Copy</button></div></li>
          <?php endif; ?>
          <?php $gb_phone = gb_mod( 'phone' ); if ( $gb_phone ) : ?>
          <li><span class="mono xs muted">PHONE / WHATSAPP</span><a class="mono" style="font-weight:600;text-decoration:none" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $gb_phone ) ); ?>"><?php echo esc_html( $gb_phone ); ?></a></li>
          <?php endif; ?>
          <?php $gb_li = gb_mod( 'linkedin' ); if ( $gb_li ) : ?>
          <li><span class="mono xs muted">LINKEDIN</span><a class="link-arrow" href="<?php echo esc_url( $gb_li ); ?>" target="_blank" rel="noopener">Galacticbuzz <svg width="14" height="14"><use href="#i-arrow"/></svg></a></li>
          <?php endif; ?>
          <li><span class="mono xs muted">HOURS</span><b><?php echo esc_html( gb_mod( 'hours' ) ); ?></b></li>
        </ul>
      </div>
    </div>
  </div>
</section>
<?php get_footer();
