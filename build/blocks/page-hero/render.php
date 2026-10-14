<?php
/**
 * Dark page hero.
 *
 * @package Studentspex
 * @var array  $attributes
 * @var string $content
 */

$level   = max( 1, min( 2, (int) $attributes['level'] ) );
$heading = studentspex_inline( $attributes['title'] );
$sub     = '';
if ( 'show' === $attributes['source'] ) {
	$show = studentspex_show( studentspex_current_show_id() );
	if ( $show ) {
		$heading = esc_html( $show['title'] );
		$sub     = $show['subtitle'];
	}
}
$image = '';
if ( $attributes['imageId'] ) {
	$image = wp_get_attachment_image_url( (int) $attributes['imageId'], 'large' );
}
if ( ! $image && $attributes['imageUrl'] ) {
	$image = $attributes['imageUrl'];
}
?>
<section <?php echo studentspex_wrapper( 'stage page-hero', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php echo studentspex_spotlights( $attributes['spotlights'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<div class="container page-hero__grid">
		<div>
			<?php if ( $attributes['eyebrow'] ) : ?>
				<p class="eyebrow"><?php echo esc_html( wp_strip_all_tags( $attributes['eyebrow'] ) ); ?></p>
			<?php endif; ?>
			<?php printf( '<h%1$d>%2$s</h%1$d>', (int) $level, $heading ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php if ( $sub ) : ?>
				<p class="hero__sub"><?php echo esc_html( $sub ); ?></p>
			<?php endif; ?>
			<?php if ( $attributes['lead'] ) : ?>
				<p class="lead"><?php echo studentspex_inline( $attributes['lead'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
			<?php if ( trim( $content ) ) : ?>
				<div class="page-hero__actions"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</div>
		<?php if ( $image ) : ?>
			<img class="page-hero__art<?php echo $attributes['imageWide'] ? ' page-hero__art--wide' : ''; ?>" src="<?php echo esc_url( $image ); ?>" alt="">
		<?php endif; ?>
	</div>
</section>
<?php
echo studentspex_scallop( $attributes['scallopColor'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
