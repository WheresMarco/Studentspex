<?php
/**
 * Direction card.
 *
 * @package Studentspex
 * @var array  $attributes
 * @var string $content
 */

?>
<div <?php echo studentspex_wrapper( 'direction card card--dark reveal', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<h3><?php esc_html_e( 'Direction', 'studentspex' ); ?> <span class="year"><?php echo esc_html( wp_strip_all_tags( $attributes['year'] ) ); ?></span></h3>
	<?php if ( $attributes['email'] ) : ?>
		<p class="direction__email"><?php echo studentspex_mail_link( $attributes['email'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	<?php endif; ?>
	<dl><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></dl>
</div>
