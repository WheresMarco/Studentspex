<?php
/**
 * Plot + poster of the current production.
 *
 * @package Studentspex
 * @var array $attributes
 */

$show = studentspex_show( studentspex_current_show_id() );
?>
<div <?php echo studentspex_wrapper( 'show-top', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div>
		<?php if ( $attributes['eyebrow'] ) : ?>
			<p class="eyebrow"><?php echo esc_html( $attributes['eyebrow'] ); ?></p>
		<?php endif; ?>
		<?php if ( $attributes['title'] ) : ?>
			<h2><?php echo esc_html( $attributes['title'] ); ?></h2>
		<?php endif; ?>
		<div class="prose show-plot">
			<?php
			if ( $show && trim( $show['content'] ) ) {
				echo wp_kses_post( do_blocks( $show['content'] ) );
			} else {
				echo '<p class="lead">' . esc_html__( 'Handlingen avslöjas snart.', 'studentspex' ) . '</p>';
			}
			if ( $attributes['outro'] ) {
				echo '<p>' . studentspex_inline( $attributes['outro'] ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</div>
	</div>
	<?php if ( $show && $show['poster_id'] ) : ?>
		<div class="poster poster--image reveal">
			<?php echo wp_get_attachment_image( $show['poster_id'], 'studentspex-poster', false, array( 'alt' => sprintf( /* translators: %s: show title */ __( 'Affisch för %s', 'studentspex' ), $show['title'] ) ) ); ?>
		</div>
	<?php else : ?>
		<div class="poster reveal">
			<img src="<?php echo esc_url( $show ? $show['lisse_url'] : studentspex_default_lisse_url() ); ?>" alt="">
			<span class="poster__year"><?php echo esc_html( $show && $show['year'] ? $show['year'] : '' ); ?></span>
			<span class="poster__title"><?php esc_html_e( 'Affischen kommer snart', 'studentspex' ); ?></span>
		</div>
	<?php endif; ?>
</div>
