<?php
/**
 * Shared helpers used by templates, blocks and admin screens.
 *
 * @package Studentspex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Inline SVG icon. Output is static markup from this file only.
 *
 * @param string $name Icon key.
 * @return string
 */
function studentspex_icon( $name ) {
	$s     = 'fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"';
	$icons = array(
		'ticket'    => '<svg viewBox="0 0 24 24" ' . $s . ' aria-hidden="true" focusable="false"><path d="M3 9a3 3 0 0 0 0 6v3a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-3a3 3 0 0 1 0-6V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1z"/><path d="M14 5v2M14 11v2M14 17v2"/></svg>',
		'calendar'  => '<svg viewBox="0 0 24 24" ' . $s . ' aria-hidden="true" focusable="false"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>',
		'pin'       => '<svg viewBox="0 0 24 24" ' . $s . ' aria-hidden="true" focusable="false"><path d="M12 21s-7-6.2-7-11a7 7 0 1 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>',
		'clock'     => '<svg viewBox="0 0 24 24" ' . $s . ' aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
		'users'     => '<svg viewBox="0 0 24 24" ' . $s . ' aria-hidden="true" focusable="false"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M16 4.5a3.5 3.5 0 0 1 0 7M21.5 20a6.5 6.5 0 0 0-4-6"/></svg>',
		'arrow'     => '<svg viewBox="0 0 24 24" ' . $s . ' aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>',
		'search'    => '<svg viewBox="0 0 24 24" ' . $s . ' aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>',
		'mail'      => '<svg viewBox="0 0 24 24" ' . $s . ' aria-hidden="true" focusable="false"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>',
		'external'  => '<svg viewBox="0 0 24 24" ' . $s . ' aria-hidden="true" focusable="false"><path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/></svg>',
		'instagram' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>',
		'facebook'  => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M13.5 21v-7.5h2.6l.4-3h-3V8.6c0-.9.3-1.5 1.5-1.5h1.6V4.4c-.3 0-1.2-.1-2.3-.1-2.3 0-3.9 1.4-3.9 4v2.2H7.8v3h2.6V21z"/></svg>',
		'tiktok'    => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M16.6 3c.4 2.2 1.8 3.7 4.1 3.9v3.1a7.6 7.6 0 0 1-4-1.2v6.4a6 6 0 1 1-6-6h.6v3.2a2.9 2.9 0 1 0 2.2 2.8V3z"/></svg>',
	);
	return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
}

/**
 * Theme settings with defaults.
 *
 * @param string|null $key Optional single key.
 * @return mixed
 */
function studentspex_setting( $key = null ) {
	$defaults = array(
		'current_show'          => 0,
		'instagram_url'         => 'https://www.instagram.com/studentspex/',
		'instagram_handle'      => '@studentspex',
		'facebook_url'          => 'https://www.facebook.com/studentspex',
		'tiktok_url'            => '',
		'application_url'       => '',
		'ticket_email'          => 'biljetter@studentspex.se',
		'newsletter_from_name'  => 'Linköpings Studentspex',
		'newsletter_from_email' => '',
	);
	$settings = wp_parse_args( (array) get_option( 'studentspex_settings', array() ), $defaults );
	if ( null === $key ) {
		return $settings;
	}
	return isset( $settings[ $key ] ) ? $settings[ $key ] : null;
}

/**
 * ID of the production marked as current ("Nästa spex"), or 0.
 *
 * Falls back to the newest published or draft production when nothing is chosen.
 *
 * @return int
 */
function studentspex_current_show_id() {
	$id = (int) studentspex_setting( 'current_show' );
	if ( $id && 'spex_uppsattning' === get_post_type( $id ) && 'trash' !== get_post_status( $id ) ) {
		return $id;
	}
	return 0;
}

/**
 * Normalised data for one production.
 *
 * @param int $id Post ID.
 * @return array|null
 */
function studentspex_show( $id ) {
	$post = $id ? get_post( $id ) : null;
	if ( ! $post || 'spex_uppsattning' !== $post->post_type ) {
		return null;
	}
	$m    = function ( $key ) use ( $id ) {
		return get_post_meta( $id, $key, true );
	};
	$year = (int) $m( 'year' );
	$perf = $m( 'performances' );
	$perf = is_array( $perf ) ? array_values(
		array_filter(
			$perf,
			function ( $p ) {
				return ! empty( $p['date'] );
			}
		)
	) : array();
	usort(
		$perf,
		function ( $a, $b ) {
			return strcmp( $a['date'], $b['date'] );
		}
	);

	$cover_id = (int) $m( 'cover_image' );
	$lisse_id = (int) $m( 'lisse_image' );
	$poster   = (int) get_post_thumbnail_id( $id );

	return array(
		'id'                 => $id,
		'title'              => get_the_title( $id ),
		'subtitle'           => (string) $m( 'subtitle' ),
		'year'               => $year,
		'year_label'         => $m( 'year_label' ) ? (string) $m( 'year_label' ) : ( $year ? (string) $year : '' ),
		'excerpt'            => has_excerpt( $id ) ? get_the_excerpt( $id ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 40 ),
		'content'            => $post->post_content,
		'permalink'          => get_permalink( $id ),
		'performances'       => $perf,
		'venue'              => $m( 'venue' ) ? (string) $m( 'venue' ) : 'Forumteatern',
		'venue_address'      => $m( 'venue_address' ) ? (string) $m( 'venue_address' ) : 'Kårhuset Kollektivet, Ågatan 55, Linköping',
		'price_regular'      => (string) $m( 'price_regular' ),
		'price_reduced'      => (string) $m( 'price_reduced' ),
		'price_reduced_note' => $m( 'price_reduced_note' ) ? (string) $m( 'price_reduced_note' ) : 'Studerande, pensionär, ung t.o.m. 17 år',
		'price_support'      => (string) $m( 'price_support' ),
		'ticket_url'         => (string) $m( 'ticket_url' ),
		'ticket_title'       => $m( 'ticket_title' ) ? (string) $m( 'ticket_title' ) : '',
		'ticket_text'        => (string) $m( 'ticket_text' ),
		'poster_id'          => $poster,
		'cover_id'           => $cover_id,
		'cover_url'          => $cover_id ? (string) wp_get_attachment_image_url( $cover_id, 'studentspex-cover' ) : '',
		'lisse_id'           => $lisse_id,
		'lisse_url'          => $lisse_id ? (string) wp_get_attachment_image_url( $lisse_id, 'large' ) : studentspex_default_lisse_url(),
	);
}

/**
 * Whether the current production has a ticket link (controls the "Biljetter" buttons).
 *
 * @return bool
 */
function studentspex_tickets_on_sale() {
	$show = studentspex_show( studentspex_current_show_id() );
	return $show && '' !== $show['ticket_url'];
}

/**
 * Bundled default Lisse illustration.
 *
 * @return string
 */
function studentspex_default_lisse_url() {
	return STUDENTSPEX_URI . '/assets/img/lisse.png';
}

/**
 * Swedish date parts for a Y-m-d string.
 *
 * @param string $ymd Date.
 * @return array{day:string,short:string,long:string}
 */
function studentspex_date_parts( $ymd ) {
	$ts = strtotime( $ymd . ' 12:00:00' );
	if ( ! $ts ) {
		return array(
			'day'   => '',
			'short' => $ymd,
			'long'  => $ymd,
		);
	}
	$days   = array( 'Söndag', 'Måndag', 'Tisdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lördag' );
	$months = array( 'januari', 'februari', 'mars', 'april', 'maj', 'juni', 'juli', 'augusti', 'september', 'oktober', 'november', 'december' );
	$m      = $months[ (int) gmdate( 'n', $ts ) - 1 ];
	return array(
		'day'   => $days[ (int) gmdate( 'w', $ts ) ],
		'short' => gmdate( 'j', $ts ) . ' ' . mb_substr( $m, 0, 3 ),
		'long'  => gmdate( 'j', $ts ) . ' ' . $m . ' ' . gmdate( 'Y', $ts ),
	);
}

/**
 * Spam-resistant mail link: the address is assembled by JavaScript.
 *
 * @param string $address E-mail address.
 * @param string $css_class   Extra classes.
 * @return string
 */
function studentspex_mail_link( $address, $css_class = '' ) {
	$address = sanitize_email( $address );
	if ( ! $address || false === strpos( $address, '@' ) ) {
		return '';
	}
	list( $user, $domain ) = explode( '@', $address, 2 );
	return sprintf(
		'<a class="mail %1$s" href="#" data-u="%2$s" data-d="%3$s">%2$s [at] %3$s</a>',
		esc_attr( $css_class ),
		esc_attr( $user ),
		esc_attr( $domain )
	);
}

/**
 * Allowed inline HTML for RichText attributes rendered on the front end.
 *
 * @param string $html Value.
 * @return string
 */
function studentspex_inline( $html ) {
	return wp_kses(
		(string) $html,
		array(
			'strong' => array(),
			'b'      => array(),
			'em'     => array(),
			'i'      => array(),
			'br'     => array(),
			'span'   => array( 'class' => true ),
			'a'      => array(
				'href'   => true,
				'target' => true,
				'rel'    => true,
				'class'  => true,
			),
			'mark'   => array(
				'class' => true,
				'style' => true,
			),
			'code'   => array(),
			's'      => array(),
		)
	);
}

/**
 * Logo markup used by the header block.
 *
 * @return string
 */
function studentspex_logo_html() {
	$logo_id = (int) get_theme_mod( 'custom_logo' );
	$src     = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : STUDENTSPEX_URI . '/assets/img/logo-white.png';
	return sprintf(
		'<img class="brand__logo" src="%1$s" alt="%2$s" width="1560" height="829">',
		esc_url( $src ),
		esc_attr( sprintf( /* translators: %s: site name */ __( '%s – till startsidan', 'studentspex' ), get_bloginfo( 'name' ) ) )
	);
}

/**
 * Curtain edge between a dark section and the light one below.
 *
 * @param string $color cream|paper.
 * @return string
 */
function studentspex_scallop( $color = 'cream' ) {
	$color = in_array( $color, array( 'cream', 'paper', 'ink' ), true ) ? $color : 'cream';
	return '<svg class="scallop scallop--' . $color . '" viewBox="0 0 1200 28" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path fill="currentColor" d="M0 28V14C50 14 50 0 100 0s50 14 100 14S250 0 300 0s50 14 100 14S450 0 500 0s50 14 100 14S650 0 700 0s50 14 100 14S850 0 900 0s50 14 100 14S1050 0 1100 0s50 14 100 14v14Z"/></svg>';
}

/**
 * Spotlight beams for dark stage sections.
 *
 * @param string $which none|left|right|both.
 * @return string
 */
function studentspex_spotlights( $which ) {
	$out = '';
	if ( 'left' === $which || 'both' === $which ) {
		$out .= '<div class="spotlight spotlight--left" aria-hidden="true"></div>';
	}
	if ( 'right' === $which || 'both' === $which ) {
		$out .= '<div class="spotlight spotlight--right" aria-hidden="true"></div>';
	}
	return $out;
}

/**
 * URL of a page by slug, with a fallback.
 *
 * @param string $slug     Page slug.
 * @param string $fragment Optional #fragment.
 * @return string
 */
function studentspex_page_url( $slug, $fragment = '' ) {
	$page = get_page_by_path( $slug );
	$url  = $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
	return $url . ( $fragment ? '#' . $fragment : '' );
}
