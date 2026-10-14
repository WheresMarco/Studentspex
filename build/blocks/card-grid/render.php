<?php
/**
 * Card grid.
 *
 * @package Studentspex
 * @var array  $attributes
 * @var string $content
 */

$cols = max( 2, min( 4, (int) $attributes['columns'] ) );
?>
<div <?php echo studentspex_wrapper( 'grid grid--' . $cols, array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
