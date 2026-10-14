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
$url     = $show ? $show['ticket_url'] : '';
$heading = $show && $show['ticket_title'] ? $show['ticket_title'] : $attributes['fallbackTitle'];
$text    = $show && $show['ticket_text'] ? $show['ticket_text'] : $attributes['fallbackText'];
?>
<div <?php echo studentspex_wrapper( 'tickets', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( $regular || $reduced ) : ?>
		<div class="prices reveal">
			<?php if ( $regular ) : ?>
				<div class="price price--main"><span class="price__amount"><?php echo esc_html( $regular ); ?><small>kr</small></span><span class="price__who"><?php esc_html_e( 'Ordinarie', 'studentspex' ); ?></span></div>
			<?php endif; ?>
			<?php if ( $reduced ) : ?>
				<div class="price"><span class="price__amount"><?php echo esc_html( $reduced ); ?><small>kr</small></span><span class="price__who"><?php esc_html_e( 'Rabatterat', 'studentspex' ); ?><small><?php echo esc_html( $show['price_reduced_note'] ); ?></small></span></div>
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
