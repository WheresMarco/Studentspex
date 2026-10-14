<?php
/**
 * Hero for a single production page.
 *
 * @package Studentspex
 * @var array    $attributes
 * @var WP_Block $block
 */

$pid  = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();
$show = studentspex_show( $pid );
if ( ! $show ) {
	return;
}
$archive = get_post_type_archive_link( 'spex_uppsattning' );
$cover   = $show['cover_url'];
$extra   = $cover ? array( 'style' => '--hero-cover:url(' . esc_url( $cover ) . ')' ) : array();
?>
<section <?php echo studentspex_wrapper( 'stage page-hero' . ( $cover ? ' hero--cover' : '' ), $extra, $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="spotlight spotlight--right" aria-hidden="true"></div>
	<div class="container page-hero__grid">
		<div>
			<p class="eyebrow"><a href="<?php echo esc_url( $archive ); ?>"><?php esc_html_e( 'Gamla spex', 'studentspex' ); ?></a> · <?php echo esc_html( $show['year_label'] ); ?></p>
			<h1><?php echo esc_html( $show['title'] ); ?></h1>
			<?php if ( $show['subtitle'] ) : ?>
				<p class="hero__sub"><?php echo esc_html( $show['subtitle'] ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $show['lisse_id'] ) : ?>
			<img class="page-hero__art page-hero__art--wide" src="<?php echo esc_url( $show['lisse_url'] ); ?>" alt="">
		<?php endif; ?>
	</div>
</section>
<?php
echo studentspex_scallop( 'cream' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
