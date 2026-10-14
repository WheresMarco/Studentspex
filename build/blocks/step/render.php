<?php
/**
 * One step.
 *
 * @package Studentspex
 * @var array $attributes
 */

?>
<li class="step reveal"><h3><?php echo esc_html( wp_strip_all_tags( $attributes['title'] ) ); ?></h3><p><?php echo studentspex_inline( $attributes['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p></li>
