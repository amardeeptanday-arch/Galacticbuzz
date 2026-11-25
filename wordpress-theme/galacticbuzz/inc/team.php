<?php
/**
 * Team members: a "Team" section in the dashboard. Each member has a name
 * (title), a bio (the main text box), a photo (featured image) and the
 * fields in the "Member details" box.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'gb_team', array(
		'labels'       => array(
			'name'          => 'Team',
			'singular_name' => 'Team member',
			'add_new_item'  => 'Add team member',
			'edit_item'     => 'Edit team member',
			'all_items'     => 'All team members',
			'featured_image'     => 'Photo',
			'set_featured_image' => 'Set photo',
		),
		'public'       => false,
		'show_ui'      => true,
		'menu_icon'    => 'dashicons-groups',
		'menu_position' => 21,
		'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'show_in_rest' => false,
	) );
} );

add_filter( 'enter_title_here', function ( $text, $post ) {
	return 'gb_team' === $post->post_type ? 'Full name' : $text;
}, 10, 2 );

function gb_team_fields() {
	return array(
		'role'     => array( 'Role', 'e.g. Head of Research' ),
		'tz'       => array( 'Time zone', 'e.g. IST, ET, GMT' ),
		'linkedin' => array( 'LinkedIn URL', 'https://www.linkedin.com/in/…' ),
		'ask'      => array( 'Ask me about', 'Two or three topics, separated by commas' ),
	);
}

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'gb_team_details', 'Member details', function ( $post ) {
		wp_nonce_field( 'gb_team_save', 'gb_team_nonce' );
		echo '<p style="color:#666">The name goes in the title, the bio in the main text box, and the photo under “Photo” on the right. Use the “Order” box to sort members.</p>';
		foreach ( gb_team_fields() as $key => $f ) {
			$val = get_post_meta( $post->ID, 'gb_' . $key, true );
			printf(
				'<p><label for="gb_%1$s"><strong>%2$s</strong></label><br><input type="text" class="widefat" id="gb_%1$s" name="gb_%1$s" value="%3$s" placeholder="%4$s"></p>',
				esc_attr( $key ),
				esc_html( $f[0] ),
				esc_attr( $val ),
				esc_attr( $f[1] )
			);
		}
	}, 'gb_team', 'normal', 'high' );
} );

add_action( 'save_post_gb_team', function ( $post_id ) {
	if ( ! isset( $_POST['gb_team_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gb_team_nonce'] ) ), 'gb_team_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array_keys( gb_team_fields() ) as $key ) {
		if ( isset( $_POST[ 'gb_' . $key ] ) ) {
			$raw = wp_unslash( $_POST[ 'gb_' . $key ] );
			$val = 'linkedin' === $key ? esc_url_raw( $raw ) : sanitize_text_field( $raw );
			update_post_meta( $post_id, 'gb_' . $key, $val );
		}
	}
} );

/**
 * Placeholder portrait used until a photo is uploaded.
 */
function gb_portrait_svg( $color, $key ) {
	$id = 'g' . preg_replace( '/[^a-z0-9]/i', '', (string) $key );
	return '<svg viewBox="0 0 400 500" preserveAspectRatio="xMidYMid slice" aria-hidden="true"><defs><linearGradient id="' . $id . '" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="' . esc_attr( $color ) . '"/><stop offset="1" stop-color="#0A0F27"/></linearGradient></defs><rect width="400" height="500" fill="url(#' . $id . ')"/><ellipse cx="200" cy="230" rx="260" ry="80" fill="none" stroke="rgba(255,255,255,.14)" stroke-width="2" transform="rotate(-18 200 230)"/><circle cx="200" cy="190" r="74" fill="rgba(255,255,255,.30)"/><path d="M58 500C58 372 132 300 200 300S342 372 342 500Z" fill="rgba(255,255,255,.22)"/></svg>';
}

/**
 * Team cards for the About page. Returns '' when there are no members.
 */
function gb_team_cards() {
	$members = get_posts( array(
		'post_type'      => 'gb_team',
		'posts_per_page' => 24,
		'post_status'    => 'publish',
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	) );
	if ( ! $members ) {
		return '';
	}
	$colors = array( '#4A3AFF', '#0F8A63', '#E89A12', '#C93743', '#2B7FD8', '#7A4BD8' );
	$out    = '';
	foreach ( $members as $i => $m ) {
		$name  = get_the_title( $m );
		$role  = get_post_meta( $m->ID, 'gb_role', true );
		$tz    = get_post_meta( $m->ID, 'gb_tz', true );
		$li    = get_post_meta( $m->ID, 'gb_linkedin', true );
		$ask   = array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $m->ID, 'gb_ask', true ) ) ) );
		$bio   = wp_strip_all_tags( $m->post_content );
		$photo = get_the_post_thumbnail_url( $m, 'gb-portrait' );
		$color = $colors[ $i % count( $colors ) ];
		$pic   = $photo ? '<img src="' . esc_url( $photo ) . '" alt="" loading="lazy">' : gb_portrait_svg( $color, 'm' . $m->ID );
		$tags  = '';
		foreach ( $ask as $a ) {
			$tags .= '<span>' . esc_html( $a ) . '</span>';
		}
		$out .= sprintf(
			'<button class="tcard" type="button" aria-label="%1$s" data-name="%2$s" data-role="%3$s" data-tz="%4$s" data-li="%5$s" data-ask="%6$s" data-bio="%7$s" data-photo="%8$s" data-color="%9$s"><span class="photo">%10$s</span><span class="glow"></span>%11$s%12$s<span class="meta"><span class="nm">%13$s</span><span class="rl">%14$s</span><span class="bio"><p>%15$s</p><span class="tags">%16$s</span></span></span></button>',
			esc_attr( $name . ( $role ? ', ' . $role : '' ) . '. View profile' ),
			esc_attr( $name ),
			esc_attr( $role ),
			esc_attr( $tz ),
			esc_url( $li ),
			esc_attr( implode( '|', $ask ) ),
			esc_attr( $bio ),
			esc_url( $photo ? $photo : '' ),
			esc_attr( $color ),
			$pic, // phpcs:ignore -- built from escaped parts.
			$tz ? '<span class="badge">' . esc_html( $tz ) . '</span>' : '',
			$li ? '<span class="li">in</span>' : '',
			esc_html( $name ),
			esc_html( $role ),
			esc_html( wp_trim_words( $bio, 30 ) ),
			$tags // phpcs:ignore -- escaped above.
		);
	}
	return $out;
}
