<?php
/**
 * Latest posts as cards.
 *
 * @package Studentspex
 * @var array $attributes
 */

$items = get_posts(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => max( 1, min( 9, (int) $attributes['count'] ) ),
		'ignore_sticky_posts' => true,
	)
);
if ( ! $items ) {
	echo '<p ' . studentspex_wrapper( 'news-empty', array(), $attributes ) . '>' . esc_html__( 'Inga nyheter än.', 'studentspex' ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}
$delay = 0;
?>
<div <?php echo studentspex_wrapper( 'grid grid--3', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php foreach ( $items as $item ) : ?>
		<?php
		$thumb = get_the_post_thumbnail(
			$item,
			'medium_large',
			array(
				'alt'     => '',
				'loading' => 'lazy',
			)
		);
		?>
		<a class="news-card reveal" style="--reveal-delay:<?php echo esc_attr( $delay ); ?>s" href="<?php echo esc_url( get_permalink( $item ) ); ?>">
			<div class="news-card__media<?php echo $thumb ? '' : ' news-card__media--contain'; ?>">
				<?php
				echo $thumb ? $thumb : '<img src="' . esc_url( studentspex_default_lisse_url() ) . '" alt="" loading="lazy">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
			<div class="news-card__body">
				<p class="news-card__date"><time datetime="<?php echo esc_attr( get_the_date( 'c', $item ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y', $item ) ); ?></time></p>
				<h3><?php echo esc_html( get_the_title( $item ) ); ?></h3>
				<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $item ), 24 ) ); ?></p>
				<span class="news-card__more"><?php esc_html_e( 'Läs mer', 'studentspex' ); ?></span>
			</div>
		</a>
		<?php $delay += 0.08; ?>
	<?php endforeach; ?>
</div>
