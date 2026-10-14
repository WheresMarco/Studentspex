<?php
/**
 * One card (group, benefit, contact…).
 *
 * @package Studentspex
 * @var array $attributes
 */

$classes = array( 'card', 'reveal' );
if ( 'default' !== $attributes['variant'] ) {
	$classes[] = 'card--' . sanitize_html_class( $attributes['variant'] );
}
if ( $attributes['hover'] ) {
	$classes[] = 'group-card';
}
if ( $attributes['label'] || $attributes['email'] ) {
	$classes[] = 'contact-card';
}
?>
<article class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<?php if ( $attributes['icon'] ) : ?>
		<div class="card__icon" aria-hidden="true"><?php echo esc_html( $attributes['icon'] ); ?></div>
	<?php endif; ?>
	<?php if ( $attributes['label'] ) : ?>
		<span class="contact-card__role"><?php echo esc_html( wp_strip_all_tags( $attributes['label'] ) ); ?></span>
	<?php endif; ?>
	<?php if ( $attributes['title'] ) : ?>
		<h3 class="<?php echo $attributes['label'] ? 'contact-card__title' : ''; ?>"><?php echo esc_html( wp_strip_all_tags( $attributes['title'] ) ); ?></h3>
	<?php endif; ?>
	<?php if ( $attributes['text'] ) : ?>
		<p><?php echo studentspex_inline( $attributes['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	<?php endif; ?>
	<?php echo studentspex_mail_link( $attributes['email'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</article>
