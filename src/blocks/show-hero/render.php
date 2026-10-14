<?php
/**
 * Front-page hero for the current production.
 *
 * @package Studentspex
 * @var array $attributes
 */

$show      = studentspex_show( studentspex_current_show_id() );
$show_year = $show && $show['year'] ? $show['year'] : (int) gmdate( 'Y' ) + 1;
$premiere  = $show && $show['performances'] ? studentspex_date_parts( $show['performances'][0]['date'] ) : null;

$kicker = $attributes['kickerText'] ? wp_strip_all_tags( $attributes['kickerText'] ) : ( $premiere ? 'Premiär ' . $premiere['long'] : '' );
$date   = $attributes['dateText'] ? wp_strip_all_tags( $attributes['dateText'] ) : ( $premiere ? 'Premiär ' . $premiere['long'] : '' );
$venue  = wp_strip_all_tags( $attributes['venueText'] );
$url1   = $attributes['btn1Url'] ? $attributes['btn1Url'] : studentspex_page_url( 'nasta-spex', 'biljetter' );
$url2   = $attributes['btn2Url'] ? $attributes['btn2Url'] : studentspex_page_url( 'sok-spexet' );
$lisse  = $show ? $show['lisse_url'] : studentspex_default_lisse_url();
$cover  = $show ? $show['cover_url'] : '';

$extra = array( 'aria-label' => wp_strip_all_tags( $attributes['headline'] ) );
if ( $cover ) {
	$extra['style'] = '--hero-cover:url(' . esc_url( $cover ) . ')';
}
?>
<section <?php echo studentspex_wrapper( 'stage hero' . ( $cover ? ' hero--cover' : '' ), $extra, $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="spotlight spotlight--left" aria-hidden="true"></div>
	<div class="spotlight spotlight--right" aria-hidden="true"></div>
	<div class="stage__floor" aria-hidden="true"></div>
	<div class="container hero__grid">
		<div>
			<p class="hero__kicker">
				<strong><?php echo esc_html( sprintf( /* translators: %s: year */ __( 'Spex %s', 'studentspex' ), $show_year ) ); ?></strong>
				<?php if ( $kicker ) : ?>
					<span><?php echo esc_html( $kicker ); ?></span>
				<?php endif; ?>
				<?php if ( $show ) : ?>
					<span class="hide-sm">· <?php echo esc_html( $show['venue'] ); ?></span>
				<?php endif; ?>
			</p>
			<h1><?php echo studentspex_inline( $attributes['headline'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
			<?php if ( $attributes['sub'] ) : ?>
				<p class="hero__sub"><?php echo esc_html( wp_strip_all_tags( $attributes['sub'] ) ); ?></p>
			<?php endif; ?>
			<ul class="hero__meta">
				<?php if ( $date ) : ?>
					<li><?php echo studentspex_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $date ); ?></li>
				<?php endif; ?>
				<?php if ( $venue ) : ?>
					<li><?php echo studentspex_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $venue ); ?></li>
				<?php endif; ?>
			</ul>
			<?php
			$show_btn1 = $attributes['btn1Text'] && studentspex_tickets_on_sale();
			$show_btn2 = $attributes['showBtn2'] && $attributes['btn2Text'];
			?>
			<?php if ( $show_btn1 || $show_btn2 ) : ?>
			<div class="btn-row">
				<?php if ( $show_btn1 ) : ?>
					<a class="btn" href="<?php echo esc_url( $url1 ); ?>"><?php echo studentspex_icon( 'ticket' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( wp_strip_all_tags( $attributes['btn1Text'] ) ); ?></a>
				<?php endif; ?>
				<?php if ( $show_btn2 ) : ?>
					<a class="btn btn--ghost" href="<?php echo esc_url( $url2 ); ?>"><?php echo esc_html( wp_strip_all_tags( $attributes['btn2Text'] ) ); ?> <?php echo studentspex_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
		<div class="hero__art" tabindex="0">
			<img class="hero__lisse" src="<?php echo esc_url( $lisse ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( $attributes['tagTitle'] ) ); ?>">
			<?php if ( $attributes['tagTitle'] ) : ?>
				<p class="hero__art-tag"><?php echo esc_html( wp_strip_all_tags( $attributes['tagTitle'] ) ); ?><span><?php echo esc_html( wp_strip_all_tags( $attributes['tagText'] ) ); ?></span></p>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
echo studentspex_scallop( 'cream' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
