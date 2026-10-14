<?php
/**
 * Sponsor strip ("Tack till").
 *
 * @package Studentspex
 * @var array  $attributes
 * @var string $content
 */

if ( ! trim( $content ) ) {
	return;
}
$uid = wp_unique_id( 'sponsors-' );
?>
<section <?php echo studentspex_wrapper( 'sponsors', array( 'aria-labelledby' => $uid ), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="container">
		<h2 class="sponsors__title" id="<?php echo esc_attr( $uid ); ?>"><?php echo esc_html( $attributes['title'] ); ?></h2>
		<ul class="sponsors__list"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ul>
	</div>
</section>
