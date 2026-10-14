<?php
/**
 * One stat.
 *
 * @package Studentspex
 * @var array $attributes
 */

?>
<div class="stat"><span class="stat__num"><?php echo esc_html( wp_strip_all_tags( $attributes['num'] ) ); ?></span><span class="stat__label"><?php echo esc_html( wp_strip_all_tags( $attributes['label'] ) ); ?></span></div>
