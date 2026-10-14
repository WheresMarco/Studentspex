<?php
/**
 * Section heading.
 *
 * @package Studentspex
 * @var array  $attributes
 * @var string $content
 */

$level   = max( 1, min( 4, (int) $attributes['level'] ) );
$actions = trim( $content );
$classes = 'section-head';
if ( 'center' === $attributes['align'] ) {
	$classes .= ' section-head--center';
}
if ( $actions ) {
	$classes .= ' section-head--split';
}
?>
<div <?php echo studentspex_wrapper( $classes, array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div>
		<?php if ( $attributes['eyebrow'] ) : ?>
			<p class="eyebrow"><?php echo esc_html( wp_strip_all_tags( $attributes['eyebrow'] ) ); ?></p>
		<?php endif; ?>
		<?php if ( $attributes['title'] ) : ?>
			<?php printf( '<h%1$d>%2$s</h%1$d>', (int) $level, studentspex_inline( $attributes['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endif; ?>
		<?php if ( $attributes['lead'] ) : ?>
			<p class="lead"><?php echo studentspex_inline( $attributes['lead'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php endif; ?>
	</div>
	<?php if ( $actions ) : ?>
		<div class="section-head__actions"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<?php endif; ?>
</div>
