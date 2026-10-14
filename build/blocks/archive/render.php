<?php
/**
 * Gamla spex: every published production except the current one, grouped by decade.
 *
 * @package Studentspex
 * @var array $attributes
 */

$current = studentspex_current_show_id();
$items   = get_posts(
	array(
		'post_type'      => 'spex_uppsattning',
		'posts_per_page' => -1,
		'post__not_in'   => $current ? array( $current ) : array(),
		'meta_key'       => 'year',
		'orderby'        => array(
			'meta_value_num' => 'DESC',
			'title'          => 'ASC',
		),
	)
);
$decades = array();
foreach ( $items as $p ) {
	$show = studentspex_show( $p->ID );
	if ( ! $show || ! $show['year'] ) {
		continue;
	}
	$decades[ (int) floor( $show['year'] / 10 ) * 10 ][] = $show;
}
krsort( $decades );
$total = array_sum( array_map( 'count', $decades ) );
$uid   = wp_unique_id( 'archive-' );
?>
<div <?php echo studentspex_wrapper( 'archive', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="archive-tools">
		<div class="archive-tools__inner">
			<label class="search" for="<?php echo esc_attr( $uid ); ?>-search">
				<span class="visually-hidden"><?php esc_html_e( 'Sök bland gamla spex', 'studentspex' ); ?></span>
				<?php echo studentspex_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<input class="input" id="<?php echo esc_attr( $uid ); ?>-search" type="search" data-archive-search placeholder="<?php echo esc_attr( $attributes['searchPlaceholder'] ); ?>" autocomplete="off">
			</label>
			<div class="decades" role="group" aria-label="<?php esc_attr_e( 'Filtrera på årtionde', 'studentspex' ); ?>">
				<button class="chip" type="button" data-decade-filter="all" aria-pressed="true"><?php esc_html_e( 'Alla år', 'studentspex' ); ?></button>
				<?php foreach ( array_keys( $decades ) as $dec ) : ?>
					<button class="chip" type="button" data-decade-filter="<?php echo esc_attr( $dec ); ?>" aria-pressed="false"><?php echo esc_html( substr( (string) $dec, 2 ) . '-tal' ); ?></button>
				<?php endforeach; ?>
			</div>
			<p class="archive-count" aria-live="polite"><span data-archive-count><?php echo esc_html( sprintf( /* translators: %d: number */ _n( '%d uppsättning', '%d uppsättningar', $total, 'studentspex' ), $total ) ); ?></span></p>
		</div>
	</div>
	<div class="timeline" data-archive>
		<?php foreach ( $decades as $dec => $shows ) : ?>
			<section class="decade" data-decade="<?php echo esc_attr( $dec ); ?>" aria-labelledby="<?php echo esc_attr( $uid . '-' . $dec ); ?>">
				<h2 class="decade__title" id="<?php echo esc_attr( $uid . '-' . $dec ); ?>"><?php echo esc_html( $dec . '-talet' ); ?></h2>
				<div class="shows">
					<?php foreach ( $shows as $show ) : ?>
						<?php $haystack = implode( ' ', array( $show['year_label'], $show['title'], $show['subtitle'], $show['excerpt'] ) ); ?>
						<article class="show reveal" data-search="<?php echo esc_attr( $haystack ); ?>">
							<a class="show__poster" href="<?php echo esc_url( $show['permalink'] ); ?>" tabindex="-1" aria-hidden="true">
								<?php
								if ( $show['poster_id'] ) {
									echo wp_get_attachment_image(
										$show['poster_id'],
										'studentspex-poster',
										false,
										array(
											'alt'     => '',
											'loading' => 'lazy',
										)
									);
								} else {
									echo '<span class="show__mask">🎭</span>';
								}
								?>
								<span class="show__year"><?php echo esc_html( $show['year_label'] ); ?></span>
							</a>
							<div class="show__body">
								<h3><a href="<?php echo esc_url( $show['permalink'] ); ?>"><?php echo esc_html( $show['title'] ); ?></a></h3>
								<?php if ( $show['subtitle'] ) : ?>
									<p class="show__sub"><?php echo esc_html( $show['subtitle'] ); ?></p>
								<?php endif; ?>
								<?php if ( $show['excerpt'] ) : ?>
									<details><summary><?php esc_html_e( 'Om uppsättningen', 'studentspex' ); ?></summary><p><?php echo esc_html( $show['excerpt'] ); ?></p></details>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endforeach; ?>
		<div class="archive-empty<?php echo $total ? '' : ' is-visible'; ?>">
			<p class="archive-empty__icon" aria-hidden="true">🔍</p>
			<h2><?php echo $total ? esc_html__( 'Inget spex matchar', 'studentspex' ) : esc_html__( 'Inga gamla spex ännu', 'studentspex' ); ?></h2>
			<?php if ( $total ) : ?>
				<p><?php esc_html_e( 'Prova ett annat ord – eller ropa ”Omstart!” och rensa sökningen.', 'studentspex' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>
