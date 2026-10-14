<?php
/**
 * One timeline entry.
 *
 * @package Studentspex
 * @var array $attributes
 */

?>
<li><span class="history__year"><?php echo esc_html( wp_strip_all_tags( $attributes['year'] ) ); ?></span><h3><?php echo esc_html( wp_strip_all_tags( $attributes['title'] ) ); ?></h3><p><?php echo studentspex_inline( $attributes['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p></li>
