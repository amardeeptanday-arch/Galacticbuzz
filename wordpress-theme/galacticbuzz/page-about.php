<?php
/**
 * About page (slug: about).
 * Generated from the approved prototype by wordpress-theme/build_theme.py.
 */
get_header();
?>
<?php $gb_team = gb_team_cards(); ?>
<section class="page on" id="p-about">
  <div class="band hero-c" style="padding-bottom:clamp(56px,7vw,88px)">
    <div class="hx-grid"></div>
    <div class="orbit-deco" style="width:760px;height:760px;right:-340px;top:-260px"></div>
    <div class="wrap">
      <span class="eyebrow">About Galacticbuzz</span>
      <h1 style="margin-top:16px;max-width:18ch">We’re the BD team you <em>didn’t have to hire.</em></h1>
      <p class="lead">Galacticbuzz is a virtual BD partner for B2B service businesses. We embed into your growth function and run outbound on your behalf, from your own identity. You get pipeline. The relationships stay yours.</p>
    </div>
  </div>

  <div class="sec">
    <div class="wrap">
      <div class="sec-head"><span class="eyebrow">How we work</span><h2>Three things we don’t compromise on.</h2></div>
      <div class="steps3">
        <div class="step3 spot"><span class="n">01</span><h3>Your name, your relationships</h3><p>We work from your profile and your domains. Nothing we build disappears when a contract ends.</p></div>
        <div class="step3 spot"><span class="n">02</span><h3>Humans, not bots</h3><p>Every connection request, email and reply is written by a person who has read the prospect’s profile.</p></div>
        <div class="step3 spot"><span class="n">03</span><h3>Consistency beats bursts</h3><p>Outbound pays off when it never stops. We keep it running while you’re busy with clients.</p></div>
      </div>
    </div>
  </div>

  <?php if ( $gb_team ) : ?>
  <div class="sec team-sec" id="team" style="background:var(--surface-2);border-block:1px solid var(--line)">
    <div class="wrap">
      <div class="sec-head" style="display:flex;justify-content:space-between;align-items:end;gap:24px;flex-wrap:wrap;max-width:none">
        <div style="display:grid;gap:14px;max-width:640px"><span class="eyebrow">Team</span><h2>The people behind your pipeline.</h2><p class="lead">Researchers, writers, callers and client success, across time zones.</p></div>
      </div>
      <div class="team-grid" id="teamGrid" data-book="<?php echo gb_url( 'contact', 'book' ); ?>"><?php echo $gb_team; // phpcs:ignore -- escaped in gb_team_cards(). ?></div>
    </div>
    <div class="cursor-dot" id="cursorDot" aria-hidden="true"><span>View profile</span></div>
  </div>

  <?php endif; ?>

  <div class="sec">
    <div class="wrap faq2">
      <div><span class="eyebrow">What we hold ourselves to</span><h2 style="margin-top:14px">Our values.</h2></div>
      <ul class="clean grid" style="gap:14px">
        <li class="card spot"><b>Say no early.</b><p class="small muted" style="margin-top:6px">If a prospect isn’t a fit, we don’t message them, however tempting the volume.</p></li>
        <li class="card spot"><b>Show the work.</b><p class="small muted" style="margin-top:6px">Weekly numbers, real conversations, and what we’re changing next.</p></li>
        <li class="card spot"><b>Your reputation first.</b><p class="small muted" style="margin-top:6px">It’s your name on every message. We write like it.</p></li>
      </ul>
    </div>
  </div>

  <div class="sec tight">
    <div class="wrap">
      <div class="cta2 band"><div class="hx-grid"></div>
        <div><h2>Want to work with us?</h2><p class="lead" style="margin-top:10px">Clients: book a call. Researchers and BD people: say hello.</p></div>
        <div class="actions"><a class="btn btn-primary btn-lg" href="<?php echo gb_url( 'contact', 'book' ); ?>">Book a call</a><a class="btn btn-ghost btn-lg" href="<?php echo gb_url( 'contact', 'message' ); ?>">Join the team</a></div>
      </div>
    </div>
  </div>
</section>
<?php get_footer();
