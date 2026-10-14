<?php
/**
 * Title: Kontakt
 * Slug: studentspex/page-kontakt
 * Categories: studentspex-pages
 * Post Types: page
 * Viewport Width: 1280
 *
 * @package Studentspex
 */

$contact = function ( $label, $title, $text, $email ) {
	return spex_b(
		'card',
		array(
			'icon'  => '',
			'label' => $label,
			'title' => $title,
			'text'  => $text,
			'email' => $email,
			'hover' => false,
		)
	);
};
$person  = function ( $role, $name, $email ) {
	return spex_b(
		'person',
		array(
			'role'  => $role,
			'name'  => $name,
			'email' => $email,
		)
	);
};
$info    = function ( $label, $html ) {
	return spex_b(
		'card',
		array(
			'icon'  => '',
			'label' => $label,
			'title' => '',
			'text'  => $html,
			'hover' => false,
		)
	);
};

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'page-hero',
	array(
		'eyebrow' => 'Kontakt',
		'title'   => 'Ropa till oss!',
		'lead'    => 'Här hittar du rätt person – vare sig det gäller biljetter, samarbeten eller hemsidan.',
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array(),
	spex_b(
		'card-grid',
		array( 'columns' => 2 ),
		array(
			$contact( 'Biljetter', 'Biljettfrågor & gruppbokningar', 'Grupper om minst 10 personer får rabatt.', 'biljetter@studentspex.se' ),
			$contact( 'Ordförande', 'Allmänna frågor', 'Allt som inte passar någon annanstans.', 'ordf@studentspex.se' ),
			$contact( 'Vice ordförande', 'Spexkontakter & företag', 'Samarbeten, sponsring och andra spex.', 'vordf@studentspex.se' ),
			$contact( 'IT-ansvarig', 'Frågor om hemsidan', 'Hittat en bugg? Saknas ett gammalt spex?', 'it@studentspex.se' ),
		)
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array( 'variant' => 'navy' ),
	spex_b(
		'section-head',
		array(
			'eyebrow' => 'Direction',
			'title'   => 'Ledningen för varje uppsättning',
			'lead'    => 'Directionen leder årets ordinarie uppsättning. Du når hela gruppen på en adress, eller var och en direkt.',
		)
	) .
	spex_b(
		'card-grid',
		array(
			'columns'   => 2,
			'className' => 'direction-grid',
		),
		array(
			spex_b(
				'direction',
				array(
					'year'  => '2027',
					'email' => 'direction27@studentspex.se',
				),
				array(
					$person( 'Directeur', 'Marco Sommer', 'directeur27@studentspex.se' ),
					$person( 'Ekonomichef', 'Theo le Clercq', 'ekchef27@studentspex.se' ),
					$person( 'Producent', 'Lisa Alemyr', 'producent27@studentspex.se' ),
				)
			),
			spex_b(
				'direction',
				array(
					'year'  => '2026',
					'email' => 'direction26@studentspex.se',
				),
				array(
					$person( 'Directeur', 'Malin Andersson', 'directeur26@studentspex.se' ),
					$person( 'Ekonomichef', 'David Combler', 'ekchef26@studentspex.se' ),
				)
			),
		)
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array(),
	spex_b(
		'card-grid',
		array( 'columns' => 3 ),
		array(
			$info( 'Postadress', '<strong>Linköpings Studentspex</strong><br>Ryddalsgatan 8D<br>582 48 Linköping' ),
			$info( 'Organisationsnummer', '<strong>822001-7274</strong>' ),
			$info( 'Här spelar vi', '<strong>Forumteatern</strong><br>Kårhuset Kollektivet<br>Ågatan 55, Linköping' ),
		)
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array( 'variant' => 'stage' ),
	spex_b(
		'section-head',
		array(
			'eyebrow' => 'Snabbast svar',
			'title'   => 'Skriv till oss i sociala medier',
			'align'   => 'center',
		)
	) .
	spex_b( 'social' )
);
