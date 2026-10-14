<?php
/**
 * Newsletter signup (double opt-in, see inc/newsletter/).
 *
 * @package Studentspex
 * @var array $attributes
 */

$footer   = 'footer' === $attributes['variant'];
$uid      = wp_unique_id( 'nl-' );
$heading  = wp_strip_all_tags( $attributes['title'] );
$form_cls = 'form' . ( $footer ? ' form--dark' : '' );
$btn_cls  = 'btn' . ( $footer ? '' : ' btn--blue' );

ob_start();
?>
<form class="<?php echo esc_attr( $form_cls ); ?>" data-newsletter novalidate aria-labelledby="<?php echo esc_attr( $uid ); ?>-title">
	<div class="form__fields">
		<div class="form__row">
			<div class="field">
				<label for="<?php echo esc_attr( $uid ); ?>-email"><?php esc_html_e( 'E-postadress', 'studentspex' ); ?></label>
				<input class="input" id="<?php echo esc_attr( $uid ); ?>-email" name="email" type="email" autocomplete="email" inputmode="email" placeholder="namn@exempel.se" aria-describedby="<?php echo esc_attr( $uid ); ?>-email-err" required>
			</div>
			<div class="form__submit"><button class="<?php echo esc_attr( $btn_cls ); ?>" type="submit"><?php echo esc_html( wp_strip_all_tags( $attributes['buttonText'] ) ); ?></button></div>
		</div>
		<p class="form__error" id="<?php echo esc_attr( $uid ); ?>-email-err" data-error="email" aria-live="polite"></p>
		<div class="form__hp" aria-hidden="true"><label for="<?php echo esc_attr( $uid ); ?>-website"><?php esc_html_e( 'Lämna tomt', 'studentspex' ); ?></label><input id="<?php echo esc_attr( $uid ); ?>-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
		<label class="check" for="<?php echo esc_attr( $uid ); ?>-consent">
			<input id="<?php echo esc_attr( $uid ); ?>-consent" name="consent" type="checkbox" value="1" aria-describedby="<?php echo esc_attr( $uid ); ?>-consent-err" required>
			<span><?php echo esc_html( wp_strip_all_tags( $attributes['consent'] ) ); ?></span>
		</label>
		<input type="hidden" name="consent_text" value="<?php echo esc_attr( wp_strip_all_tags( $attributes['consent'] ) ); ?>">
		<p class="form__error" id="<?php echo esc_attr( $uid ); ?>-consent-err" data-error="consent" aria-live="polite"></p>
		<p class="form__error" data-error="server" aria-live="polite"></p>
	</div>
	<div class="form__success" role="status">
		<span class="big" aria-hidden="true">🎟️</span>
		<h3><?php esc_html_e( 'Nästan klart!', 'studentspex' ); ?></h3>
		<p><?php esc_html_e( 'Vi har skickat ett bekräftelsemejl till', 'studentspex' ); ?> <strong data-echo></strong>. <?php esc_html_e( 'Klicka på länken i mejlet så är du med.', 'studentspex' ); ?></p>
	</div>
</form>
<?php
$form = ob_get_clean();

if ( $footer ) :
	?>
	<div <?php echo studentspex_wrapper( 'footer-news', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<h2 id="<?php echo esc_attr( $uid ); ?>-title"><?php echo esc_html( $heading ); ?></h2>
		<p><?php echo studentspex_inline( $attributes['lead'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php echo $form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<?php
	return;
endif;
?>
<div <?php echo studentspex_wrapper( 'newsletter reveal', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div>
		<?php if ( $attributes['eyebrow'] ) : ?>
			<p class="eyebrow"><?php echo esc_html( wp_strip_all_tags( $attributes['eyebrow'] ) ); ?></p>
		<?php endif; ?>
		<h2 id="<?php echo esc_attr( $uid ); ?>-title"><?php echo esc_html( $heading ); ?></h2>
		<?php if ( $attributes['lead'] ) : ?>
			<p class="lead"><?php echo studentspex_inline( $attributes['lead'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php endif; ?>
		<?php if ( $attributes['promise'] ) : ?>
			<p class="newsletter__promise"><span aria-hidden="true">✦</span><span><?php echo esc_html( wp_strip_all_tags( $attributes['promise'] ) ); ?></span></p>
		<?php endif; ?>
	</div>
	<?php echo $form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
