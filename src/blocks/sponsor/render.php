<?php
/**
 * One sponsor logo.
 *
 * @package Studentspex
 * @var array $attributes
 */

$src = $attributes['imageId'] ? wp_get_attachment_image_url( (int) $attributes['imageId'], 'medium' ) : '';
if ( ! $src ) {
	$src = $attributes['imageUrl'];
}
if ( ! $src ) {
	return;
}
$img = sprintf( '<img src="%s" alt="%s" loading="lazy">', esc_url( $src ), esc_attr( $attributes['alt'] ) );
if ( $attributes['link'] ) {
	$img = sprintf( '<a href="%s" target="_blank" rel="noopener">%s</a>', esc_url( $attributes['link'] ), $img );
}
echo '<li>' . $img . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
