<?php
/**
 * Social media tiles or mini buttons. URLs come from Spexet → Inställningar.
 *
 * @package Studentspex
 * @var array $attributes
 */

$networks = array(
	'ig' => array(
		'url'    => studentspex_setting( 'instagram_url' ),
		'label'  => 'Instagram',
		'handle' => studentspex_setting( 'instagram_handle' ),
		'text'   => $attributes['igText'],
		'icon'   => 'instagram',
	),
	'fb' => array(
		'url'    => studentspex_setting( 'facebook_url' ),
		'label'  => 'Facebook',
		'handle' => get_bloginfo( 'name' ),
		'text'   => $attributes['fbText'],
		'icon'   => 'facebook',
	),
	'tt' => array(
		'url'    => studentspex_setting( 'tiktok_url' ),
		'label'  => 'TikTok',
		'handle' => studentspex_setting( 'instagram_handle' ),
		'text'   => $attributes['ttText'],
		'icon'   => 'tiktok',
	),
);
$networks = array_filter(
	$networks,
	function ( $n ) {
		return ! empty( $n['url'] );
	}
);
if ( ! $networks ) {
	return;
}

if ( 'mini' === $attributes['variant'] ) {
	echo '<div ' . studentspex_wrapper( 'social-mini-row', array(), $attributes ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	foreach ( $networks as $key => $n ) {
		printf(
			'<a class="social-mini social-mini--%1$s" href="%2$s" target="_blank" rel="noopener"><span>%3$s</span>%4$s</a>',
			esc_attr( $key ),
			esc_url( $n['url'] ),
			studentspex_icon( $n['icon'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html( $n['label'] )
		);
	}
	echo '</div>';
	return;
}

$cols = count( $networks ) >= 3 ? ' social--3' : '';
echo '<div ' . studentspex_wrapper( 'social' . $cols, array(), $attributes ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
$delay = 0;
foreach ( $networks as $key => $n ) {
	printf(
		'<a class="social-tile social-tile--%1$s reveal" style="--reveal-delay:%7$ss" href="%2$s" target="_blank" rel="noopener"><span class="social-tile__icon">%3$s</span><span><span class="social-tile__label">%4$s</span><span class="social-tile__handle">%5$s</span><span class="social-tile__desc">%6$s</span></span></a>',
		esc_attr( $key ),
		esc_url( $n['url'] ),
		studentspex_icon( $n['icon'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		esc_html( $n['label'] ),
		esc_html( $n['handle'] ),
		esc_html( $n['text'] ),
		esc_attr( (string) $delay )
	);
	$delay += 0.1;
}
echo '</div>';
