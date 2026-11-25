<?php
/**
 * Site header. Generated from the approved prototype by wordpress-theme/build_theme.py.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#06071A">
<?php if ( is_front_page() ) : ?><meta name="description" content="Galacticbuzz is a virtual BD team for B2B service firms. LinkedIn outreach, cold email, calling and branding, run from your own profile. You get the meetings; the relationships stay yours."><?php endif; ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to content</a>
<?php get_template_part( 'template-parts/sprite' ); ?>
<header class="topbar">
  <div class="wrap">
    <a class="logo" href="<?php echo gb_url( '' ); ?>" aria-label="Galacticbuzz home"><svg><use href="#i-logo"/></svg><span>galactic<b>buzz</b></span></a>
    <nav class="nav" aria-label="Main">
      <?php foreach ( array( 'services' => 'Services', 'who-we-serve' => 'Who we serve', 'about' => 'About', 'contact' => 'Contact' ) as $gb_slug => $gb_label ) : ?>
      <a href="<?php echo gb_url( $gb_slug ); ?>"<?php echo gb_current() === $gb_slug ? ' class="on" aria-current="page"' : ''; ?>><?php echo esc_html( $gb_label ); ?></a>
      <?php if ( 'who-we-serve' === $gb_slug ) : ?><a href="<?php echo gb_url( '', 'how' ); ?>">How it works</a><?php endif; ?>
      <?php endforeach; ?>
    </nav>
    <div class="top-cta">
      <a class="btn btn-light btn-sm" href="<?php echo gb_url( 'contact', 'book' ); ?>">Book a call <svg width="16" height="16"><use href="#i-arrow"/></svg></a>
    </div>
    <button class="menu-btn" type="button" id="menuBtn" aria-label="Open menu" aria-expanded="false"><svg width="20" height="20" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg></button>
  </div>
  <nav class="mnav" id="mnav" aria-label="Mobile">
    <a href="<?php echo gb_url( '' ); ?>">Home</a>
    <a href="<?php echo gb_url( 'services' ); ?>">Services</a>
    <a href="<?php echo gb_url( 'who-we-serve' ); ?>">Who we serve</a>
    <a href="<?php echo gb_url( '', 'how' ); ?>">How it works</a>
    <a href="<?php echo gb_url( 'about' ); ?>">About</a>
    <a href="<?php echo gb_url( 'contact' ); ?>">Contact</a>
    <a href="<?php echo gb_url( 'contact', 'book' ); ?>">Book a call</a>
  </nav>
</header>

<main id="main">
