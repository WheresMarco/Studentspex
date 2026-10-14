<?php
/**
 * Interactive tillrop box.
 *
 * @package Studentspex
 * @var array  $attributes
 * @var string $content
 */

?>
<div <?php echo studentspex_wrapper( 'shout-box reveal', array( 'data-shouts' => '' ), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<h3><?php echo esc_html( wp_strip_all_tags( $attributes['title'] ) ); ?></h3>
	<p><?php echo studentspex_inline( $attributes['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	<div class="shouts" role="group" aria-label="<?php esc_attr_e( 'Exempel på tillrop', 'studentspex' ); ?>"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<div class="shout-stage" aria-live="polite">
		<span class="shout-stage__icon" aria-hidden="true">🎭</span>
		<p class="shout-stage__text"><?php echo esc_html( wp_strip_all_tags( $attributes['placeholder'] ) ); ?></p>
	</div>
</div>
