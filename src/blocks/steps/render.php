<?php
/**
 * Numbered steps.
 *
 * @package Studentspex
 * @var string $content
 */

?>
<ol <?php echo studentspex_wrapper( 'steps', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ol>
