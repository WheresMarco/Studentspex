<?php
/**
 * One person in a Direction card.
 *
 * @package Studentspex
 * @var array $attributes
 */

?>
<div class="person"><dt><?php echo esc_html( wp_strip_all_tags( $attributes['role'] ) ); ?></dt><dd><?php echo esc_html( wp_strip_all_tags( $attributes['name'] ) ); ?><?php echo $attributes['email'] ? '<br>' . studentspex_mail_link( $attributes['email'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></dd></div>
