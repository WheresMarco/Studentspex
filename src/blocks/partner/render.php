<?php
/**
 * Partner credit (e.g. "Hemsidan drivs i samarbete med Datorföreningen Lysator").
 *
 * @package Studentspex
 * @var array $attributes
 */

$logo = $attributes['imageId'] ? wp_get_attachment_image_url( (int) $attributes['imageId'], 'medium' ) : '';
if ( ! $logo ) {
	$logo = $attributes['imageUrl'] ? $attributes['imageUrl'] : STUDENTSPEX_URI . '/assets/img/lysator.svg';
}
$link_tag = $attributes['url'] ? 'a' : 'span';
$href     = $attributes['url'] ? ' href="' . esc_url( $attributes['url'] ) . '" target="_blank" rel="noopener"' : '';
?>
<div <?php echo studentspex_wrapper( 'slim' === $attributes['variant'] ? 'partner partner--slim' : 'container partner', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<<?php echo esc_html( $link_tag ); ?> class="partner__link"<?php echo $href; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>>
		<img class="partner__logo" src="<?php echo esc_url( $logo ); ?>" alt="" width="56" height="56" decoding="async" loading="lazy">
		<span class="partner__text">
			<span class="partner__intro"><?php echo esc_html( wp_strip_all_tags( $attributes['intro'] ) ); ?></span>
			<span class="partner__name"><?php echo esc_html( wp_strip_all_tags( $attributes['name'] ) ); ?></span>
		</span>
	</<?php echo esc_html( $link_tag ); ?>>
</div>
