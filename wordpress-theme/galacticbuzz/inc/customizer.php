<?php
/**
 * Appearance > Customize > Galacticbuzz: contact details, booking link,
 * where form submissions go, and client testimonials.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gb_mod_defaults() {
	return array(
		'contact_email' => 'hello@galacticbuzz.io',
		'lead_email'    => '',
		'phone'         => '',
		'linkedin'      => '',
		'booking_url'   => '',
		'hours'         => 'Mon–Fri, covering US, UK, UAE and Australian business hours',
		'reply_time'    => 'We reply within one working day.',
	);
}

add_action( 'customize_register', function ( $wp ) {
	$wp->add_panel( 'gb', array(
		'title'    => 'Galacticbuzz',
		'priority' => 30,
	) );

	$wp->add_section( 'gb_contact', array( 'title' => 'Contact & booking', 'panel' => 'gb' ) );
	$fields = array(
		'contact_email' => array( 'Public email address', 'Shown on the Contact page.', 'email' ),
		'lead_email'    => array( 'Send form submissions to', 'Leave empty to use the site admin email. Every submission is also saved under Leads.', 'email' ),
		'phone'         => array( 'Phone / WhatsApp', 'Leave empty to hide it.', 'text' ),
		'linkedin'      => array( 'Company LinkedIn URL', 'Leave empty to hide it.', 'url' ),
		'booking_url'   => array( 'Calendly or Cal.com link', 'When set, the Contact page shows your live booking calendar. When empty, visitors pick a preferred time and you confirm by email.', 'url' ),
		'hours'         => array( 'Working hours', '', 'text' ),
		'reply_time'    => array( 'Reply-time promise', 'Shown under “Talk to a person.”', 'text' ),
	);
	foreach ( $fields as $key => $f ) {
		$wp->add_setting( 'gb_' . $key, array(
			'default'           => gb_mod_defaults()[ $key ] ?? '',
			'sanitize_callback' => 'email' === $f[2] ? 'sanitize_email' : ( 'url' === $f[2] ? 'esc_url_raw' : 'sanitize_text_field' ),
		) );
		$wp->add_control( 'gb_' . $key, array(
			'label'       => $f[0],
			'description' => $f[1],
			'section'     => 'gb_contact',
			'type'        => 'url' === $f[2] ? 'url' : ( 'email' === $f[2] ? 'email' : 'text' ),
		) );
	}

	$wp->add_section( 'gb_testimonials', array(
		'title'       => 'Testimonials',
		'panel'       => 'gb',
		'description' => 'The testimonials section on the homepage appears once at least one quote is filled in. Use real quotes with the client’s permission.',
	) );
	for ( $i = 1; $i <= 3; $i++ ) {
		$parts = array(
			'quote'  => array( "Testimonial $i: quote", 'textarea' ),
			'name'   => array( "Testimonial $i: name or title", 'text' ),
			'role'   => array( "Testimonial $i: company or role", 'text' ),
			'metric' => array( "Testimonial $i: result chip (optional)", 'text' ),
		);
		foreach ( $parts as $p => $meta ) {
			$wp->add_setting( "gb_t{$i}_{$p}", array(
				'default'           => '',
				'sanitize_callback' => 'textarea' === $meta[1] ? 'sanitize_textarea_field' : 'sanitize_text_field',
			) );
			$wp->add_control( "gb_t{$i}_{$p}", array(
				'label'   => $meta[0],
				'section' => 'gb_testimonials',
				'type'    => $meta[1],
			) );
		}
	}
} );
