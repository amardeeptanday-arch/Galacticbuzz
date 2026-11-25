<?php
/**
 * Site content shared by several templates.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Industries we serve. Edit this list to add, remove or reword one.
 */
function gb_verticals() {
	return array(
		array( 'n' => 'Offshore accounting firms', 'ic' => 'ledger', 'to' => 'US CPA firms and UK accountancy practices', 'mix' => array( 'LinkedIn', 'Email' ), 'angle' => 'Tax-season capacity, and month-end close done by day 5.' ),
		array( 'n' => 'IT services & software', 'ic' => 'code', 'to' => 'CTOs and VPs of Engineering at US and UK mid-market companies', 'mix' => array( 'LinkedIn', 'Email', 'Calling' ), 'angle' => 'Delivery capacity with real time-zone overlap.' ),
		array( 'n' => 'SaaS founders', 'ic' => 'layers', 'to' => 'Buyers found by role, company size and the tools they already use', 'mix' => array( 'LinkedIn', 'Branding' ), 'angle' => 'Founder-led selling, without the founder spending every evening on it.' ),
		array( 'n' => 'Fractional CFOs', 'ic' => 'chart', 'to' => 'Founders of seed to Series B startups', 'mix' => array( 'LinkedIn', 'Branding' ), 'angle' => 'Board-ready numbers before the next raise.' ),
		array( 'n' => 'Design studios', 'ic' => 'spark', 'to' => 'Heads of Marketing and founders planning a launch or rebrand', 'mix' => array( 'LinkedIn', 'Branding' ), 'angle' => 'Work that speaks for itself, put in front of the right people.' ),
		array( 'n' => 'Data engineering consultants', 'ic' => 'data', 'to' => 'Heads of Data and CTOs', 'mix' => array( 'LinkedIn', 'Email' ), 'angle' => 'Warehouse migrations and pipelines that stop breaking.' ),
		array( 'n' => 'AI / ML startups', 'ic' => 'target', 'to' => 'Heads of Operations and CTOs with manual workflows', 'mix' => array( 'LinkedIn', 'Email', 'Calling' ), 'angle' => 'A pilot on one painful workflow, live in weeks.' ),
		array( 'n' => 'PropTech', 'ic' => 'briefcase', 'to' => 'Property managers, brokerages and developers', 'mix' => array( 'Email', 'Calling' ), 'angle' => 'Less admin per unit, without adding headcount.' ),
	);
}

function gb_vertical_tiles() {
	foreach ( gb_verticals() as $v ) {
		printf(
			'<a class="vtile" href="%s"><span class="ico-b">%s</span>%s</a>',
			gb_url( 'who-we-serve' ),
			gb_icon( $v['ic'] ),
			esc_html( $v['n'] )
		);
	}
}

function gb_marquee() {
	$items = '';
	foreach ( gb_verticals() as $v ) {
		$items .= '<span>' . gb_icon( $v['ic'] ) . esc_html( $v['n'] ) . '</span>';
	}
	// Printed twice so the ribbon can scroll without a gap.
	echo $items . $items; // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts.
}

function gb_industry_cards() {
	foreach ( gb_verticals() as $v ) {
		$mix = '';
		foreach ( $v['mix'] as $m ) {
			$mix .= '<span>' . esc_html( $m ) . '</span>';
		}
		printf(
			'<div class="ind spot"><div class="ind-top"><span class="ico-b">%1$s</span><h3>%2$s</h3></div><dl><dt>Sells to</dt><dd>%3$s</dd><dt>Channel mix</dt><dd><span class="chmix">%4$s</span></dd></dl><div class="angle"><span class="xs mono muted">AN ANGLE THAT WORKS · EXAMPLE</span><div style="margin-top:4px">%5$s</div></div></div>',
			gb_icon( $v['ic'] ),
			esc_html( $v['n'] ),
			esc_html( $v['to'] ),
			$mix, // phpcs:ignore -- escaped above.
			esc_html( $v['angle'] )
		);
	}
}

/**
 * Client testimonials from Appearance > Customize. The section only shows
 * once at least one real quote has been entered.
 */
function gb_testimonials() {
	$colors = array( '#7C6CFF', '#0F8A63', '#E89A12' );
	$cards  = '';
	for ( $i = 1; $i <= 3; $i++ ) {
		$quote = trim( (string) gb_mod( "t{$i}_quote" ) );
		if ( '' === $quote ) {
			continue;
		}
		$name   = trim( (string) gb_mod( "t{$i}_name" ) );
		$role   = trim( (string) gb_mod( "t{$i}_role" ) );
		$metric = trim( (string) gb_mod( "t{$i}_metric" ) );
		$cards .= sprintf(
			'<figure class="gc tw spot"><blockquote>“%1$s”</blockquote><figcaption><span class="av" style="background:%2$s">%3$s</span><div><b>%4$s</b><small>%5$s</small></div>%6$s</figcaption></figure>',
			esc_html( $quote ),
			esc_attr( $colors[ ( $i - 1 ) % 3 ] ),
			esc_html( gb_initials( $name ) ),
			esc_html( $name ),
			esc_html( $role ),
			$metric ? '<span class="mchip">' . esc_html( $metric ) . '</span>' : ''
		);
	}
	if ( '' === $cards ) {
		return;
	}
	echo '<div class="sec"><div class="wrap"><div class="sec-head center"><span class="eyebrow">Clients</span><h2>Founders who stopped doing outbound on Sundays.</h2></div><div class="twall">' . $cards . '</div></div></div>'; // phpcs:ignore -- escaped above.
}

function gb_initials( $name ) {
	$parts = preg_split( '/\s+/', trim( (string) $name ) );
	$out   = '';
	foreach ( array_slice( array_filter( $parts ), 0, 2 ) as $p ) {
		$out .= function_exists( 'mb_substr' ) ? mb_substr( $p, 0, 1 ) : substr( $p, 0, 1 );
	}
	return strtoupper( $out ? $out : 'GB' );
}
