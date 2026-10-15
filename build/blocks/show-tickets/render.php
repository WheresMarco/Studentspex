<?php
/**
 * Prices and ticket call-to-action for the current production.
 *
 * @package Studentspex
 * @var array $attributes
 */

$show    = studentspex_show( studentspex_current_show_id() );
$regular = $show ? $show['price_regular'] : '';
$reduced = $show ? $show['price_reduced'] : '';
$support = $show ? $show['price_support'] : '';
$count   = count( array_filter( array( $regular, $reduced, $support ) ) );
$url     = $show ? $show['ticket_url'] : '';
$heading = $show && $show['ticket_title'] ? $show['ticket_title'] : $attributes['fallbackTitle'];
$text    = $show && $show['ticket_text'] ? $show['ticket_text'] : $attributes['fallbackText'];
?>
<div <?php echo studentspex_wrapper( 'tickets', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( $count ) : ?>
		<div class="prices<?php echo 3 === $count ? ' prices--3' : ''; ?> reveal">
			<?php if ( $regular ) : ?>
				<div class="price price--main">
					<span class="price__label"><?php esc_html_e( 'Ordinarie', 'studentspex' ); ?></span>
					<span class="price__amount"><?php echo esc_html( $regular ); ?><small>kr</small></span>
					<span class="price__note"><?php esc_html_e( 'Fullt pris', 'studentspex' ); ?></span>
				</div>
			<?php endif; ?>
			<?php if ( $reduced ) : ?>
				<div class="price">
					<span class="price__label"><?php esc_html_e( 'Rabatterat', 'studentspex' ); ?></span>
					<span class="price__amount"><?php echo esc_html( $reduced ); ?><small>kr</small></span>
					<span class="price__note"><?php echo esc_html( $show['price_reduced_note'] ); ?></span>
				</div>
			<?php endif; ?>
			<?php if ( $support ) : ?>
				<div class="price price--support">
					<span class="price__badge"><?php esc_html_e( 'Stöd spexet', 'studentspex' ); ?></span>
					<span class="price__label"><?php esc_html_e( 'Robin Hood', 'studentspex' ); ?></span>
					<span class="price__amount"><?php echo esc_html( $support ); ?><small>kr</small></span>
					<span class="price__note"><?php esc_html_e( 'Betala lite extra så går mellanskillnaden till spexet.', 'studentspex' ); ?></span>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="ticket-cta stage reveal">
		<div>
			<h2><?php echo esc_html( $heading ); ?></h2>
			<p>
				<?php echo esc_html( $text ); ?>
				<?php if ( ! $url && $attributes['newsletterText'] ) : ?>
					<a href="<?php echo esc_url( $attributes['newsletterUrl'] ); ?>"><?php echo esc_html( $attributes['newsletterText'] ); ?></a>
				<?php endif; ?>
			</p>
		</div>
		<?php if ( $url ) : ?>
			<a class="btn" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php echo studentspex_icon( 'ticket' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $attributes['buttonText'] ); ?> <?php echo studentspex_icon( 'external' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		<?php else : ?>
			<span class="btn btn--disabled" aria-disabled="true"><?php echo studentspex_icon( 'ticket' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'Snart till salu', 'studentspex' ); ?></span>
		<?php endif; ?>
	</div>
</div>
