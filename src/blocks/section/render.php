<?php
/**
 * Section wrapper.
 *
 * @package Studentspex
 * @var array  $attributes
 * @var string $content
 */

$variant  = in_array( $attributes['variant'], array( 'cream', 'paper', 'navy', 'stage' ), true ) ? $attributes['variant'] : 'cream';
$is_stage = 'stage' === $variant;
$classes  = array( 'section', $is_stage ? 'stage section--dark' : 'section--' . $variant );
if ( ! empty( $attributes['tight'] ) ) {
	$classes[] = 'section--tight';
}
if ( empty( $attributes['padTop'] ) ) {
	$classes[] = 'section--flush-top';
}
$container = 'container' . ( 'narrow' === $attributes['width'] ? ' container--narrow' : '' );

echo '<section ' . studentspex_wrapper( implode( ' ', $classes ), array(), $attributes ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
if ( $is_stage ) {
	echo studentspex_spotlights( $attributes['spotlights'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
echo '<div class="' . esc_attr( $container ) . '">' . $content . '</div></section>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
if ( $is_stage && ! empty( $attributes['scallop'] ) ) {
	echo studentspex_scallop( $attributes['scallopColor'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
