<?php
/**
 * Glossary entry.
 *
 * @package Studentspex
 * @var array $attributes
 */

?>
<dl <?php echo studentspex_wrapper( 'term reveal', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<dt><?php echo esc_html( wp_strip_all_tags( $attributes['term'] ) ); ?>
	<?php
	if ( $attributes['kind'] ) :
		?>
		<small><?php echo esc_html( wp_strip_all_tags( $attributes['kind'] ) ); ?></small><?php endif; ?></dt>
	<dd><?php echo studentspex_inline( $attributes['definition'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></dd>
</dl>
