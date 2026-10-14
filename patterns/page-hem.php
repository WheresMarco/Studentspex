<?php
/**
 * Title: Startsida
 * Slug: studentspex/page-hem
 * Categories: studentspex-pages
 * Post Types: page
 * Viewport Width: 1280
 *
 * @package Studentspex
 */

$shout = function ( $label, $icon, $result ) {
	return spex_b(
		'shout',
		array(
			'label'  => $label,
			'icon'   => $icon,
			'result' => $result,
		)
	);
};
$stat  = function ( $num, $label ) {
	return spex_b(
		'stat',
		array(
			'num'   => $num,
			'label' => $label,
		)
	);
};

echo spex_b( 'show-hero' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array(),
	spex_columns(
		spex_p( 'Vad är ett spex?', 'eyebrow' ) .
		spex_p( 'Broadway möter <strong>studentkultur.</strong>', 'pitch-big' ) .
		spex_p( 'Ett spex är en berättelse med musiknummer, egenskrivna texter till låtar du känner igen, liveorkester, mycket dans och stora shownummer – löst baserad på en historisk händelse som vi driver med.', 'lead' ) .
		spex_p( 'Och det bästa: <strong>publiken kan påverka vad skådespelarna gör på scen genom tillrop.</strong> Därför är ingen föreställning den andra lik.' ) .
		spex_buttons( array( array( 'Mer om spex', studentspex_page_url( 'om-spexet' ), 'blue' ) ) ),
		spex_b(
			'tillrop',
			array(),
			array(
				$shout( 'Större hatt!', '🎩', 'Kungen byter genast till en hatt i storlek XXL – och fortsätter scenen som om inget hänt.' ),
				$shout( 'På tyska!', '🇩🇪', 'Jawohl! Hela repliken tas om – på tyska, med exakt så mycket tyska som skådespelaren kan.' ),
				$shout( 'Golvet är lava!', '🔥', 'Golvet är lava! Ensemblen klättrar på rekvisitan resten av scenen.' ),
				$shout( 'Omstart!', '🔁', 'Omstart! Scenen spelas en gång till – fast lite annorlunda.' ),
				$shout( 'Baklänges!', '⏪', 'Baklänges! Samma scen, fast i omvänd ordning. Lycka till.' ),
			)
		)
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array(
		'variant' => 'navy',
		'tight'   => true,
	),
	spex_b(
		'stats',
		array(),
		array(
			$stat( '1981', 'Första spexet på scen' ),
			$stat( '~70', 'Spexare på och bakom scen' ),
			$stat( 'Live', 'Orkester varje föreställning' ),
			$stat( '0', 'Föreställningar som är den andra lik' ),
		)
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array(),
	spex_b(
		'section-head',
		array(
			'eyebrow' => 'Senaste nytt',
			'title'   => 'Från kulisserna',
		),
		spex_buttons( array( array( 'Alla nyheter', studentspex_page_url( 'nyheter' ), 'ghost' ) ) )
	) .
	spex_b( 'latest-news', array( 'count' => 3 ) )
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array( 'variant' => 'stage' ),
	spex_b(
		'section-head',
		array(
			'eyebrow' => 'Följ spexet',
			'title'   => 'Häng med bakom ridån',
			'lead'    => 'Repetitioner, kostymprovningar, rebusar och biljettsläpp – det händer först i våra kanaler.',
			'align'   => 'center',
		)
	) .
	spex_b( 'social' )
);

echo spex_b( 'section', array( 'anchor' => 'nyhetsbrev' ), spex_b( 'newsletter' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
