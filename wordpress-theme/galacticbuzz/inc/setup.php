<?php
/**
 * One-time setup when the theme is activated: creates the site's pages,
 * sets the homepage, turns on clean URLs and adds the founder to Team.
 * Running it again never duplicates anything.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_switch_theme', 'gb_setup_site' );

function gb_setup_site() {
	$pages = array(
		'home'           => array( 'Home', '' ),
		'services'       => array( 'Services', '' ),
		'who-we-serve'   => array( 'Who we serve', '' ),
		'about'          => array( 'About', '' ),
		'contact'        => array( 'Contact', '' ),
		'find-a-partner' => array( 'Find a partner', '' ),
		'privacy-policy' => array( 'Privacy policy', gb_privacy_text() ),
		'terms'          => array( 'Terms of use', gb_terms_text() ),
	);
	$ids = array();
	foreach ( $pages as $slug => $p ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$ids[ $slug ] = $existing->ID;
			// WordPress ships an unpublished privacy draft; publish ours in its place.
			if ( 'privacy-policy' === $slug && 'publish' !== $existing->post_status ) {
				wp_update_post( array(
					'ID'           => $existing->ID,
					'post_status'  => 'publish',
					'post_title'   => $p[0],
					'post_content' => $p[1],
				) );
			}
			continue;
		}
		$ids[ $slug ] = wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $p[0],
			'post_name'    => $slug,
			'post_content' => $p[1],
		) );
	}

	if ( ! empty( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', (int) $ids['home'] );
	}
	if ( ! empty( $ids['privacy-policy'] ) ) {
		update_option( 'wp_page_for_privacy_policy', (int) $ids['privacy-policy'] );
	}

	// Clean URLs such as /services/ instead of /?page_id=12.
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	// Start the team with the founder; add everyone else under Team.
	$has_team = get_posts( array( 'post_type' => 'gb_team', 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
	if ( ! $has_team ) {
		$id = wp_insert_post( array(
			'post_type'    => 'gb_team',
			'post_status'  => 'publish',
			'post_title'   => 'Amardeep Tanday',
			'post_content' => 'Started Galacticbuzz to give B2B service firms the outbound engine that big sales teams have, without losing the relationships.',
			'menu_order'   => 0,
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, 'gb_role', 'Founder' );
			update_post_meta( $id, 'gb_ask', 'Outbound strategy, Client playbooks' );
		}
	}

	// Remove WordPress's untouched sample content so it doesn't show up in search results.
	$sample = get_page_by_path( 'sample-page' );
	if ( $sample && false !== strpos( $sample->post_content, 'This is an example page' ) ) {
		wp_trash_post( $sample->ID );
	}
	$hello = get_posts( array( 'name' => 'hello-world', 'post_type' => 'post', 'post_status' => 'publish', 'numberposts' => 1 ) );
	if ( $hello && false !== strpos( $hello[0]->post_content, 'Welcome to WordPress' ) ) {
		wp_trash_post( $hello[0]->ID );
	}

	flush_rewrite_rules();
}

function gb_privacy_text() {
	return '<!-- wp:paragraph --><p><em>Last updated: ' . esc_html( date_i18n( 'j F Y' ) ) . '. This is a starting draft. Please review it with your legal adviser before relying on it.</em></p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Who we are</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Galacticbuzz provides virtual business development services for B2B companies. This policy explains what personal data we collect through galacticbuzz.io and how we use it.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>What we collect</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>When you fill in a form on this site we collect the details you enter, such as your name, work email, company, website and message. Our hosting provider also records standard technical data such as IP addresses in server logs.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>How we use it</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>We use your details to reply to you, arrange calls and provide the services you ask about. If you send a partner brief, we share it only with the firms on your shortlist, and only after you agree.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>How long we keep it</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>We keep enquiries for as long as needed to respond and to run any work that follows, then delete them.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Your rights</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>You can ask us to see, correct or delete the personal data we hold about you. Email us and we will respond within 30 days.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Contact</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Questions about this policy: ' . esc_html( gb_mod( 'contact_email' ) ) . '</p><!-- /wp:paragraph -->';
}

function gb_terms_text() {
	return '<!-- wp:paragraph --><p><em>Last updated: ' . esc_html( date_i18n( 'j F Y' ) ) . '. This is a starting draft. Please review it with your legal adviser before relying on it.</em></p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Using this site</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>This website describes the services of Galacticbuzz. Content is for general information and may change without notice.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Services</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Any engagement with Galacticbuzz is governed by a separate written agreement. Nothing on this site is an offer or a guarantee of results.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Liability</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>To the extent permitted by law, Galacticbuzz is not liable for losses arising from use of this website.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Contact</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>' . esc_html( gb_mod( 'contact_email' ) ) . '</p><!-- /wp:paragraph -->';
}
