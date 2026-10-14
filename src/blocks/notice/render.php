<?php
/**
 * Info box.
 *
 * @package Studentspex
 * @var array $attributes
 */

?>
<div <?php echo studentspex_wrapper( 'notice reveal' . ( 'gold' === $attributes['variant'] ? ' notice--gold' : '' ), array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<span class="notice__icon" aria-hidden="true"><?php echo esc_html( $attributes['icon'] ); ?></span>
	<div>
		<h3><?php echo esc_html( wp_strip_all_tags( $attributes['title'] ) ); ?></h3>
		<p><?php echo studentspex_inline( $attributes['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	</div>
</div>
