<?php
/**
 * One tillrop button.
 *
 * @package Studentspex
 * @var array $attributes
 */

if ( '' === trim( wp_strip_all_tags( $attributes['label'] ) ) ) {
	return;
}
printf(
	'<button class="shout" type="button" aria-pressed="false" data-icon="%1$s" data-result="%2$s">%3$s</button>',
	esc_attr( $attributes['icon'] ),
	esc_attr( wp_strip_all_tags( $attributes['result'] ) ),
	esc_html( wp_strip_all_tags( $attributes['label'] ) )
);
