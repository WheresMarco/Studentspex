<?php
/**
 * Title: Sök spexet
 * Slug: studentspex/page-sok-spexet
 * Categories: studentspex-pages
 * Post Types: page
 * Viewport Width: 1280
 *
 * @package Studentspex
 */

$apply = '#ansokan'; // Replaced with the form URL from Spexet → Inställningar when rendered.
$card  = function ( $icon, $title, $text, $extra = array() ) {
	return spex_b(
		'card',
		array_merge(
			array(
				'icon'  => $icon,
				'title' => $title,
				'text'  => $text,
			),
			$extra
		)
	);
};
$step  = function ( $title, $text ) {
	return spex_b(
		'step',
		array(
			'title' => $title,
			'text'  => $text,
		)
	);
};

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'page-hero',
	array(
		'eyebrow'    => 'Sök spexet',
		'title'      => 'Det finns en plats för dig här.',
		'lead'       => 'Du behöver inte ha stått på en scen, hållit i en symaskin eller skrivit ett rim förut. De flesta av oss hade inte det heller när vi började. Det viktigaste är att du tycker det låter roligt.',
		'imageUrl'   => STUDENTSPEX_URI . '/assets/img/lisse.png',
		'spotlights' => 'left',
	),
	spex_buttons(
		array(
			array( 'Till ansökan', $apply, 'gold' ),
			array( 'Se alla grupper', '#grupper', 'ghost' ),
		)
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array(),
	spex_columns(
		spex_p( 'Vad gör vi?', 'eyebrow' ) .
		spex_h( 'Ett helt spex, från första idé till sista applåd' ) .
		spex_p( 'Varje år skriver, bygger, syr, repar och spelar vi en helt ny föreställning. Omkring 70 personer gör det på sin fritid, vid sidan av jobb och studier.', 'lead' ) .
		spex_p( 'Vissa står på scen. Många fler gör allt annat: skriver manus, arrangerar musik, spelar i orkestern, bygger dekor, syr kostymer, riggar ljus och ser till att ingen går hungrig. Det är ett ganska stort kompisgäng som råkar sätta upp en show.' ),
		spex_b(
			'card-grid',
			array( 'columns' => 2 ),
			array(
				$card( '🤝', 'Nya vänner', 'Från hela universitetet och utanför det.', array( 'hover' => false ) ),
				$card( '🛠️', 'Lär dig något nytt', 'Vi lär varandra – ingen förkunskap krävs.', array( 'hover' => false ) ),
				$card( '🎉', 'Fester & fika', 'KM ser till att ingen går hungrig.', array( 'hover' => false ) ),
				$card( '👏', 'Premiärkänslan', 'Svår att beskriva. Lätt att bli beroende av.', array( 'hover' => false ) ),
			)
		)
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array(
		'variant' => 'paper',
		'anchor'  => 'grupper',
	),
	spex_b(
		'section-head',
		array(
			'eyebrow' => 'Grupperna',
			'title'   => 'Hitta din grupp',
			'lead'    => 'Låter något lockande? Du kan söka till flera grupper, och vet du inte vilken som passar – skriv det i ansökan så hjälper vi dig.',
		)
	) .
	spex_b(
		'card-grid',
		array( 'columns' => 3 ),
		array(
			$card( '✍️', 'Manus', 'Spåna idéer, utveckla karaktärer, hitta på skämt och rimma – tillsammans med andra kreativa människor.' ),
			$card( '🎼', 'Musik', 'Musikproducent, arrangörer och textförfattare väljer låtarna, skriver nya texter och arrangerar dem för orkestern.' ),
			$card( '🎺', 'Orkester & dirigent', 'Den största gruppen, med skön stämning. Piano, trummor, stråkar, blås – och en och annan vuvuzela.' ),
			$card( '🎭', 'Skådis', 'Älskar du att sjunga eller få folk att skratta? Våga ta chansen och utvecklas – både som person och skådespelare.' ),
			$card( '💃', 'Dans & koreografi', 'Som koreograf får du skådis att följa din minsta vink. Gränslös konstnärlig frihet (inom vissa gränser).' ),
			$card( '🎚️', 'Ljud', 'Har du en förkärlek till ljud? Med din finstämda hörsel lyfter du skådisar och orkester till nya nivåer.' ),
			$card( '💡', 'Ljus', 'Designa ditt eget ormbo av sladdar och moving heads ovanför scen – och lys upp hela föreställningen.' ),
			$card( '🎨', 'Dekor & rekvisita', 'Rita, måla, bygga, klä dig i svart och fika. Från gigantisk rekvisita till kulisser – en stor grupp med många händiga människor.' ),
			$card( '🧵', 'Kostym, smink & peruk', 'Sy, sminka och peta på skådisar. Här får du leka med en livs levande nåldyna.' ),
			$card( '📣', 'PR', 'Rita affischer, prata med företag, sköt Instagram och Facebook och genomför PR-jippon. Blir du sur av Comic Sans? Perfekt.' ),
			$card( '🍲', 'KM', 'Sett Sveriges Mästerkock? Laga mat till glada spexare och ordna fester i en mysig grupp.' ),
			$card( '🤷', 'Vet inte än?', 'Helt okej! Skriv vad du är nyfiken på så hittar vi något tillsammans.', array( 'variant' => 'gold' ) ),
		)
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array( 'variant' => 'stage' ),
	spex_b(
		'section-head',
		array(
			'eyebrow' => 'Så går ett spexår',
			'title'   => 'Från ansökan till premiär',
		)
	) .
	spex_b(
		'steps',
		array(),
		array(
			$step( 'Du söker', 'Fyll i formuläret – det tar ett par minuter. Ansökan öppnar på våren.' ),
			$step( 'Vi hörs', 'Någon från spexet hör av sig och berättar mer. Söker du skådis blir det en lättsam audition.' ),
			$step( 'Vi skapar', 'Under hösten skriver, bygger, syr och repar vi – i din grupp och tillsammans med alla andra.' ),
			$step( 'Premiär!', 'I februari står vi på Forumteatern och publiken ropar in. Det är då det blir magi.' ),
		)
	)
);

echo spex_b( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'section',
	array( 'width' => 'narrow' ),
	spex_b(
		'section-head',
		array(
			'eyebrow' => 'Vanliga frågor',
			'title'   => 'Men tänk om…',
		)
	) .
	spex_details( '…jag aldrig har gjort något sånt här förut?', 'Då är du i gott sällskap. Många börjar i spexet utan någon erfarenhet alls. Vi lär varandra, och de som varit med länge minns hur det var att vara ny.' ) .
	spex_details( '…jag inte vill stå på scen?', 'Då gör du inte det! De flesta i spexet jobbar bakom scenen – med musik, dekor, kostym, ljus, ljud, PR eller mat.' ) .
	spex_details( 'Vem kan söka?', 'Alla som vill vara med och skapa spex är välkomna att söka – oavsett program, ålder eller bakgrund.' ) .
	spex_details( 'Hur mycket tid tar det?', 'Det varierar mellan grupperna och över året. Mest intensivt blir det närmare premiären i februari. Skriv i ansökan om du har mycket annat på gång, så hittar vi ett upplägg som funkar.' ) .
	spex_details( 'Jag har fler frågor!', 'Hör av dig via <a href="' . esc_url( studentspex_page_url( 'kontakt' ) ) . '">kontaktsidan</a> eller skriv till oss på Instagram.' ) .
	spex_buttons( array( array( 'Sök spexet', $apply, 'blue' ) ) )
);
