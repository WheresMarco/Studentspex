<?php
/**
 * Last footer line: copyright text, with an optional partner credit on the right.
 *
 * @package Studentspex
 * @var array  $attributes
 * @var string $content
 */

?>
<div <?php echo studentspex_wrapper( 'container footer-bottom', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<p><?php echo esc_html( wp_strip_all_tags( $attributes['text'] ) ); ?></p>
	<?php if ( trim( $content ) ) : ?>
		<div class="footer-bottom__aside"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<?php endif; ?>
</div>
