<?php
/**
 * Title: Om spexet
 * Slug: studentspex/page-om-spexet
 * Categories: studentspex-pages
 * Post Types: page
 * Viewport Width: 1280
 *
 * @package Studentspex
 */

$glossary = function ( $word, $kind, $def ) {
	return spex_b(
		'term',
		array(
			'term'       => $word,
			'kind'       => $kind,
			'definition' => $def,
		)
	);
};
$card     = function ( $icon, $title, $text ) {
	return spex_b(
		'card',
		array(
			'icon'    => $icon,
			'title'   => $title,
			'text'    => $text,
			'variant' => 'dark',
			'hover'   => false,
		)
	);
};
$mini     = function ( $title, $text ) {
	return spex_b(
		'card',
		array(
			'icon'  => '',
			'title' => $title,
			'text'  => $text,
		)
	);
};
$hist     = function ( $year, $title, $text ) {
	return spex_b(
		'history-item',
		array(
			'year'  => $year,
			'title' => $title,
			'text'  => $text,
		)
	);
};

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'page-hero',
	array(
		'eyebrow'    => 'Om spexet',
		'title'      => 'Vad är egentligen ett spex?',
		'lead'       => 'Kort svar: Broadway möter studentkultur. Längre svar: läs vidare.',
		'spotlights' => 'both',
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array(),
	spex_columns(
		spex_p( 'Spex, förklarat', 'eyebrow' ) .
		spex_h( 'En föreställning där publiken får ropa in' ) .
		spex_p( 'Ett spex är en berättelse med musiknummer, dans och stora shownummer. Låtarna känner du igen – texterna har vi skrivit själva – och allt framförs med liveorkester.', 'lead' ) .
		spex_p( 'Handlingen är löst baserad på en historisk händelse som vi driver med. Historieböckerna får ursäkta: hos oss kan en påve råka hittas död i 890-talets Rom, eller kapten Ching ärva makten över Röda Fanans flotta.' ) .
		spex_p( 'Det som gör spexet unikt är att <strong>publiken kan påverka vad skådespelarna gör på scen genom tillrop.</strong> Ropar någon <em>”Omstart!”</em> spelas scenen en gång till, fast lite annorlunda. Ropar någon <em>”Större hatt!”</em>, <em>”På tyska!”</em> eller <em>”Golvet är lava!”</em> – ja, då blir det så. Du behöver aldrig själv upp på scen; det är ensemblen som får svettas.' ) .
		spex_block( 'core/quote', array(), '<blockquote class="wp-block-quote">' . spex_p( 'Därför är ingen föreställning den andra lik.' ) . '</blockquote>' ),
		$glossary( 'Omstart!', 'Tillrop', 'En gång till, fast lite annorlunda.' ) .
		$glossary( 'Baklänges!', 'Tillrop', 'Gör om det där, fast i omvänd ordning.' ) .
		$glossary( 'Ondare!', 'Tillrop', 'Mer smärta. Eller elakare. Det beror helt på omständigheterna.' ) .
		$glossary( 'Studentikos', 'Adjektiv', 'Humor av studenter, för studenter: ordvitsar, rim, anakronismer och en kärleksfull respektlöshet mot historien.' )
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array( 'variant' => 'navy' ),
	spex_b(
		'section-head',
		array(
			'eyebrow' => 'Bakom ridån',
			'title'   => 'Tramsigt på scen. På fullt allvar bakom.',
			'lead'    => 'Humorn är lättsam, men produktionen är det inte. Ett spex är en stor ensemble, en lång tradition och otroligt många nedlagda timmar.',
		)
	) .
	spex_b(
		'card-grid',
		array( 'columns' => 3 ),
		array(
			$card( '👥', '~70 spexare', 'På scen, i orkesterdiket och bakom kulisserna – alla på sin fritid, vid sidan av jobb och studier.' ),
			$card( '🎻', 'Egen orkester', 'Egna arrangemang, flerstämmig a cappella och en orkester som spelar live varje kväll.' ),
			$card( '🧵', 'Allt görs själva', 'Manus på rim, handsydda kostymer, gigantisk rekvisita, finurliga sceneffekter och en och annan pyroteknisk överraskning.' ),
		)
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array(),
	spex_columns(
		spex_image( STUDENTSPEX_URI . '/assets/img/lisse.png', 'Lisse Spexlund', 'lisse-frame' ),
		spex_p( 'Föreningen', 'eyebrow' ) .
		spex_h( 'En ideell förening sedan 1979' ) .
		spex_p( 'Linköpings Studentspex bildades den 28 november 1979 och har sedan dess ett enda huvudsyfte: att sätta upp studentspex. Initiativet kom från LinTek:s kårstyrelse, men spexare från alla fakulteter anslöt tidigt. Två år senare, 1981, gick det första Linköpingsspexet – <em>Göta kanal</em> – upp på scen.' ) .
		spex_h( 'Lisse Spexlund', 3 ) .
		spex_p( 'Spexets maskot heter Lisse Spexlund. Varje år klär Lisse upp sig efter uppsättningens tema och målas av till programbladet och, inte minst, affischen.' ) .
		spex_p( '<a href="' . esc_url( home_url( '/gamla-spex/' ) ) . '">Se Lisses alla roller i Gamla spex →</a>' )
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array( 'variant' => 'paper' ),
	spex_b(
		'section-head',
		array(
			'eyebrow' => 'Grupperna',
			'title'   => 'Vem gör vad?',
		),
		spex_buttons( array( array( 'Läs mer & sök', studentspex_page_url( 'sok-spexet', 'grupper' ), 'blue' ) ) )
	) .
	spex_b(
		'card-grid',
		array( 'columns' => 4 ),
		array(
			$mini( '✍️ Manus', 'Handling, karaktärer, skämt och rim.' ),
			$mini( '🎼 Musik & orkester', 'Låtval, arrangemang, texter och liveorkester.' ),
			$mini( '🎭 Skådis & dans', 'Scenen, sången och koreografin.' ),
			$mini( '💡 Ljud & ljus', 'Teknik som lyfter hela föreställningen.' ),
			$mini( '🎨 Dekor & rekvisita', 'Kulisser och gigantiska saker.' ),
			$mini( '🧵 Kostym, smink, peruk', 'Allt ensemblen bär på scen.' ),
			$mini( '📣 PR', 'Affischer, sociala medier och jippon.' ),
			$mini( '🍲 KM', 'Mat, fester och gemenskap.' ),
		)
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array( 'variant' => 'stage' ),
	spex_columns(
		spex_p( 'Historia', 'eyebrow' ) .
		spex_h( 'Från Uppsalas studentfester till Forumteatern' ) .
		spex_p( 'Spextraditionen föddes ur de komiska sånger och sketcher – ”gyckel” eller ”spektakel” – som uppfördes på studentfester i tidigt 1800-tals Uppsala. ”Spektakel” krympte så småningom till ”spex”.', 'lead' ) .
		spex_p( 'Linköpings Studentspex är i dag ett av de stora spexen i landet. Vi följer i stort den klassiska traditionen – parodierad historisk handling, rimmad dialog, kupletter till stulna melodier, anakronismer och ordvitsar – men har länge profilerat oss med ganska modern musik. Vi var dessutom tidigt ute med att låta alla, oavsett kön, stå på scen.' ) .
		spex_buttons( array( array( 'Bläddra bland gamla spex', home_url( '/gamla-spex/' ), 'gold' ) ) ),
		spex_b(
			'history',
			array(),
			array(
				$hist( 'ca 1850', 'Trollflaskan', 'En parodi på Mozarts Trollflöjten uppförs i Uppsala – ofta räknat som ett av de första riktiga spexen.' ),
				$hist( '1979', 'Föreningen bildas', 'Den 28 november bildas den ideella föreningen Linköpings Studentspex.' ),
				$hist( '1981', 'Göta kanal', 'Linköpings första studentspex går upp på scen efter två års förberedelser.' ),
				$hist( '1989', 'Spexiadsegrare', 'Med ”Den gula faran” vinner vi den första Spexiaden i Stockholms Konserthus – och den åtråvärda vandringsöltunnan.' ),
				$hist( 'Nu', 'Nästa kapitel', 'Ett nytt spex, nya tillrop. <a href="' . esc_url( studentspex_page_url( 'nasta-spex' ) ) . '">Se vad som händer →</a>' ),
			)
		)
	)
);
