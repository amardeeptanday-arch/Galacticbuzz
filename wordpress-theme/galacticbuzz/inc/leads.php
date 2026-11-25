<?php
/**
 * Form submissions. Every form on the site posts here; each submission is
 * saved under "Leads" in the dashboard and emailed to the address set in
 * Appearance > Customize > Galacticbuzz.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'gb_lead', array(
		'labels'        => array(
			'name'          => 'Leads',
			'singular_name' => 'Lead',
			'all_items'     => 'All leads',
			'edit_item'     => 'Lead',
		),
		'public'        => false,
		'show_ui'       => true,
		'menu_icon'     => 'dashicons-email-alt',
		'menu_position' => 22,
		'supports'      => array( 'title', 'editor' ),
		'capabilities'  => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'  => true,
	) );
} );

add_filter( 'manage_gb_lead_posts_columns', function ( $cols ) {
	return array(
		'cb'       => $cols['cb'],
		'title'    => 'Lead',
		'gb_form'  => 'Form',
		'date'     => 'Received',
	);
} );

add_action( 'manage_gb_lead_posts_custom_column', function ( $col, $post_id ) {
	if ( 'gb_form' === $col ) {
		echo esc_html( get_post_meta( $post_id, 'gb_form', true ) );
	}
}, 10, 2 );

add_action( 'wp_ajax_gb_lead', 'gb_handle_lead' );
add_action( 'wp_ajax_nopriv_gb_lead', 'gb_handle_lead' );

function gb_handle_lead() {
	if ( ! check_ajax_referer( 'gb_lead', 'nonce', false ) ) {
		wp_send_json_error( array( 'message' => 'Please reload the page and try again.' ), 403 );
	}

	// Bots fill the hidden field; people never see it.
	if ( ! empty( $_POST['gb_hp'] ) ) {
		wp_send_json_success();
	}

	// At most 8 submissions per visitor every 10 minutes.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key = 'gb_rl_' . md5( $ip );
	$n   = (int) get_transient( $key );
	if ( $n >= 8 ) {
		wp_send_json_error( array( 'message' => 'Too many messages. Please email us instead.' ), 429 );
	}
	set_transient( $key, $n + 1, 10 * MINUTE_IN_SECONDS );

	$forms = array(
		'call'    => 'Book a call',
		'message' => 'Message',
		'brief'   => 'Partner brief',
	);
	$form  = isset( $_POST['form'] ) ? sanitize_key( wp_unslash( $_POST['form'] ) ) : '';
	$label = isset( $forms[ $form ] ) ? $forms[ $form ] : 'Website form';

	$raw    = isset( $_POST['fields'] ) ? json_decode( wp_unslash( $_POST['fields'] ), true ) : array(); // phpcs:ignore -- each value sanitized below.
	$fields = array();
	if ( is_array( $raw ) ) {
		foreach ( array_slice( $raw, 0, 40 ) as $pair ) {
			if ( ! is_array( $pair ) || count( $pair ) < 2 ) {
				continue;
			}
			$k = sanitize_text_field( (string) $pair[0] );
			$v = sanitize_textarea_field( (string) $pair[1] );
			if ( '' !== $k && '' !== $v ) {
				$fields[ substr( $k, 0, 80 ) ] = substr( $v, 0, 4000 );
			}
		}
	}
	if ( ! $fields ) {
		wp_send_json_error( array( 'message' => 'The form was empty.' ), 400 );
	}

	$email = '';
	$name  = '';
	foreach ( $fields as $k => $v ) {
		if ( ! $email && is_email( $v ) ) {
			$email = $v;
		}
		if ( ! $name && preg_match( '/name/i', $k ) && ! preg_match( '/firm|company/i', $k ) ) {
			$name = $v;
		}
	}

	$lines = array();
	foreach ( $fields as $k => $v ) {
		$lines[] = $k . ': ' . $v;
	}
	$body = implode( "\n", $lines );

	$who     = $name ? $name : ( $email ? $email : 'Website visitor' );
	$post_id = wp_insert_post( array(
		'post_type'    => 'gb_lead',
		'post_status'  => 'private',
		'post_title'   => $label . ' · ' . $who,
		'post_content' => $body,
	) );
	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, 'gb_form', $label );
	}

	$to      = gb_mod( 'lead_email' );
	$to      = is_email( $to ) ? $to : get_option( 'admin_email' );
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( $email ) {
		$headers[] = 'Reply-To: ' . ( $name ? str_replace( array( "\r", "\n", '<', '>' ), '', $name ) . ' ' : '' ) . '<' . $email . '>';
	}
	wp_mail( $to, '[Galacticbuzz] ' . $label . ' from ' . $who, $body . "\n\nSaved in WordPress under Leads.", $headers );

	wp_send_json_success();
}
