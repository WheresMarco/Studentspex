<?php
/**
 * Title: Sidhuvud
 * Slug: studentspex/header
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 *
 * @package Studentspex
 */

$nav_attrs = array(
	'overlayMenu' => 'never',
	'layout'      => array(
		'type'           => 'flex',
		'justifyContent' => 'right',
	),
);
$nav_id    = (int) get_option( 'studentspex_nav_id' );
if ( $nav_id && get_post( $nav_id ) ) {
	$nav_attrs = array( 'ref' => $nav_id ) + $nav_attrs;
}

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- serialized block markup.
echo spex_b(
	'site-header',
	array(),
	spex_block( 'core/navigation', $nav_attrs ) .
	spex_buttons( array( array( __( 'Biljetter', 'studentspex' ), studentspex_page_url( 'nasta-spex', 'biljetter' ), 'gold' ) ) )
);
