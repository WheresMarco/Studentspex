<?php
/**
 * Title: Sidfot
 * Slug: studentspex/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 *
 * @package Studentspex
 */

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'site-footer',
	array(),
	array(
		spex_b( 'sponsors', array( 'title' => __( 'Tack till', 'studentspex' ) ) ),
		spex_group(
			'footer-top',
			spex_b(
				'newsletter',
				array(
					'variant' => 'footer',
					'title'   => __( 'Missa inte nästa spex', 'studentspex' ),
					'lead'    => __( 'Få ett mejl när vi avslöjar nästa föreställning och när biljetterna släpps. Inget annat.', 'studentspex' ),
				)
			) .
			spex_group(
				'footer-contact',
				spex_h( __( 'Kontakt', 'studentspex' ), 2 ) .
				spex_p( '<strong>' . __( 'Biljetter &amp; gruppbokning', 'studentspex' ) . '</strong><br><a href="' . esc_url( studentspex_page_url( 'kontakt' ) ) . '">' . __( 'Alla kontaktuppgifter', 'studentspex' ) . '</a>' ) .
				spex_p( '<strong>Forumteatern</strong><br>Kårhuset Kollektivet, Ågatan 55, Linköping' ) .
				spex_b( 'social', array( 'variant' => 'mini' ) )
			)
		),
		spex_b( 'footer-bottom', array(), spex_b( 'partner', array( 'variant' => 'slim' ) ) ),
	)
);
