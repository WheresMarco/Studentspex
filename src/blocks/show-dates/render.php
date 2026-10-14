<?php
/**
 * Performances, venue, times and group bookings for the current production.
 *
 * @package Studentspex
 * @var array $attributes
 */

$show    = studentspex_show( studentspex_current_show_id() );
$perfs   = $show ? $show['performances'] : array();
$venue   = $show ? $show['venue'] : 'Forumteatern';
$addr    = $show ? $show['venue_address'] : 'Kårhuset Kollektivet, Ågatan 55, Linköping';
$map_url = 'https://maps.google.com/?q=' . rawurlencode( $addr );
?>
<div <?php echo studentspex_wrapper( 'two-col', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div>
		<h3 class="dates-title"><?php echo esc_html( $attributes['datesTitle'] ); ?></h3>
		<?php if ( $perfs ) : ?>
			<?php
			// Site-local "today": earlier dates are greyed out, the first upcoming one is highlighted.
			$today     = (string) apply_filters( 'studentspex_today', current_time( 'Y-m-d' ) );
			$next_date = '';
			foreach ( $perfs as $perf ) {
				if ( $perf['date'] >= $today ) {
					$next_date = $perf['date'];
					break;
				}
			}
			?>
			<ul class="dates">
				<?php foreach ( $perfs as $i => $perf ) : ?>
					<?php
					$parts    = studentspex_date_parts( $perf['date'] );
					$premier  = 0 === $i;
					$date_tag = $perf['label'] ? $perf['label'] : ( $premier ? __( 'Premiär', 'studentspex' ) : $perf['time'] );
					if ( $perf['label'] && $perf['time'] ) {
						$date_tag = $perf['label'] . ' · ' . $perf['time'];
					}
					?>
					<?php
					$state = '';
					if ( $perf['date'] < $today ) {
						$state = ' date--past';
					} elseif ( $perf['date'] === $next_date ) {
						$state = ' date--next';
					}
					?>
					<li class="date reveal<?php echo esc_attr( $state ); ?>">
						<span class="date__day"><?php echo esc_html( $parts['day'] ); ?></span>
						<time class="date__num" datetime="<?php echo esc_attr( $perf['date'] ); ?>"><?php echo esc_html( $parts['short'] ); ?></time>
						<span class="date__tag"><?php echo esc_html( $date_tag ); ?></span>
						<?php if ( ' date--next' === $state && $today === $perf['date'] ) : ?>
							<span class="date__badge"><?php esc_html_e( 'Ikväll!', 'studentspex' ); ?></span>
						<?php elseif ( ' date--next' === $state ) : ?>
							<span class="date__badge"><?php esc_html_e( 'Nästa', 'studentspex' ); ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<p><?php esc_html_e( 'Datumen släpps snart.', 'studentspex' ); ?></p>
		<?php endif; ?>
	</div>
	<ul class="facts">
		<li class="fact reveal">
			<span class="fact__icon"><?php echo studentspex_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<span><span class="fact__label"><?php esc_html_e( 'Plats', 'studentspex' ); ?></span><span class="fact__value"><?php echo esc_html( $venue ); ?><small><?php echo esc_html( $addr ); ?> · <a href="<?php echo esc_url( $map_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Visa karta', 'studentspex' ); ?></a></small></span></span>
		</li>
		<?php if ( $attributes['timesTitle'] ) : ?>
			<li class="fact reveal">
				<span class="fact__icon"><?php echo studentspex_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span><span class="fact__label"><?php esc_html_e( 'Tider', 'studentspex' ); ?></span><span class="fact__value"><?php echo esc_html( $attributes['timesTitle'] ); ?><small><?php echo esc_html( $attributes['timesText'] ); ?></small></span></span>
			</li>
		<?php endif; ?>
		<?php if ( $attributes['groupsTitle'] ) : ?>
			<li class="fact reveal">
				<span class="fact__icon"><?php echo studentspex_icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span><span class="fact__label"><?php esc_html_e( 'Grupper', 'studentspex' ); ?></span><span class="fact__value"><?php echo esc_html( $attributes['groupsTitle'] ); ?><small><?php echo esc_html( $attributes['groupsText'] ); ?> <?php echo studentspex_mail_link( studentspex_setting( 'ticket_email' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></small></span></span>
			</li>
		<?php endif; ?>
	</ul>
</div>
