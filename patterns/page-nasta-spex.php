<?php
/**
 * Title: Nästa spex
 * Slug: studentspex/page-nasta-spex
 * Categories: studentspex-pages
 * Post Types: page
 * Viewport Width: 1280
 *
 * @package Studentspex
 */

$notice = function ( $icon, $title, $text, $variant = 'blue' ) {
	return spex_b(
		'notice',
		array(
			'icon'    => $icon,
			'title'   => $title,
			'text'    => $text,
			'variant' => $variant,
		)
	);
};

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'page-hero',
	array(
		'eyebrow' => 'Nästa spex',
		'source'  => 'show',
		'lead'    => 'Musiknummer, liveorkester, dans och en historisk händelse vi inte tar det minsta på allvar. Du ropar – vi spelar.',
	),
	spex_buttons(
		array(
			array( 'Biljetter', '#biljetter', 'gold' ),
			array( 'Praktisk info', '#praktiskt', 'ghost' ),
		)
	)
);

echo spex_b( 'section', array(), spex_b( 'show-plot' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array( 'variant' => 'paper' ),
	spex_b(
		'section-head',
		array(
			'eyebrow' => 'När & var',
			'title'   => 'Föreställningar',
		)
	) . spex_b( 'show-dates' )
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array( 'anchor' => 'biljetter' ),
	spex_b(
		'section-head',
		array(
			'eyebrow' => 'Biljetter',
			'title'   => 'Vad kostar det?',
			'lead'    => 'Alla är välkomna. Rabatterat pris för studerande, pensionärer och unga till och med 17 år.',
		)
	) .
	spex_b( 'show-tickets', array( 'newsletterUrl' => '#nyhetsbrev' ) )
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array(
		'variant' => 'paper',
		'anchor'  => 'praktiskt',
	),
	spex_b(
		'section-head',
		array(
			'eyebrow' => 'Bra att veta',
			'title'   => 'Praktisk information',
		)
	) .
	spex_b(
		'card-grid',
		array(
			'columns'   => 2,
			'className' => 'notice-grid',
		),
		array(
			$notice( '⏰', 'Ingen akademisk kvart!', 'Dörrarna till salongen öppnar kvart i och föreställningen börjar prick. Kom i tid – kiosken i foajén med godis och dryck öppnar en timme innan.', 'gold' ),
			$notice( '🎒', 'Forumteaterns väskpolicy', 'Små väskor är okej i salongen – tänk ungefär A4-storlek, som en datorväska eller mindre handväska. Resväskor, ryggsäckar och gymbags får stanna utanför. Medicinväskor är givetvis alltid okej.' ),
			$notice( '🎟️', 'Byte & återköp', 'Vi kan tyvärr inte göra återköp. Behöver du byta datum? Hör av dig till oss via kontaktsidan. Gruppbokningar hämtas ut i foajén innan föreställningen.' ),
			$notice( '💡', 'Ljuseffekter', 'Föreställningen kan innehålla blinkande ljus i vissa scener. Vill du veta exakt när? Mejla oss eller fråga i foajén.' ),
		)
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array(
		'anchor' => 'nyhetsbrev',
		'tight'  => true,
	),
	spex_b(
		'newsletter',
		array(
			'eyebrow' => 'Missa inte biljettsläppet',
			'title'   => 'Vi mejlar när biljetterna släpps',
			'lead'    => 'Så hinner du välja föreställning innan de populäraste datumen fylls.',
			'promise' => '',
		)
	)
);
