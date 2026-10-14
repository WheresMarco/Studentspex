<?php
/**
 * Dark footer wrapper.
 *
 * @package Studentspex
 * @var string $content
 */

?>
<footer <?php echo studentspex_wrapper( 'site-footer', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></footer>
