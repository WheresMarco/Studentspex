<?php
/**
 * First-run setup for a clean install: pages, menu, front page, the 2027
 * production and (optionally) the archive of past productions.
 *
 * Every step is idempotent – re-running never duplicates content.
 *
 * @package Studentspex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Pages created by setup: slug => [title, pattern slug or '' for none].
 *
 * @return array
 */
function studentspex_setup_pages() {
	return array(
		'hem'        => array( __( 'Hem', 'studentspex' ), 'studentspex/page-hem' ),
		'nasta-spex' => array( __( 'Nästa spex', 'studentspex' ), 'studentspex/page-nasta-spex' ),
		'sok-spexet' => array( __( 'Sök spexet', 'studentspex' ), 'studentspex/page-sok-spexet' ),
		'om-spexet'  => array( __( 'Om spexet', 'studentspex' ), 'studentspex/page-om-spexet' ),
		'kontakt'    => array( __( 'Kontakt', 'studentspex' ), 'studentspex/page-kontakt' ),
		'nyheter'    => array( __( 'Nyheter', 'studentspex' ), '' ),
	);
}

/**
 * Menu under Spexet.
 */
function studentspex_setup_menu() {
	add_submenu_page( 'studentspex', __( 'Kom igång', 'studentspex' ), __( 'Kom igång', 'studentspex' ), 'manage_options', 'studentspex-setup', 'studentspex_setup_page' );
}
add_action( 'admin_menu', 'studentspex_setup_menu', 30 );

/**
 * Nudge after activation until setup has run.
 */
function studentspex_setup_notice() {
	if ( get_option( 'studentspex_setup_done' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'spexet_page_studentspex-setup' === $screen->id ) {
		return;
	}
	printf(
		'<div class="notice notice-info"><p><strong>%1$s</strong> %2$s <a class="button button-primary" href="%3$s">%4$s</a></p></div>',
		esc_html__( 'Studentspex-temat är aktiverat.', 'studentspex' ),
		esc_html__( 'Skapa sidorna, menyn och årets uppsättning med ett klick.', 'studentspex' ),
		esc_url( admin_url( 'admin.php?page=studentspex-setup' ) ),
		esc_html__( 'Kom igång', 'studentspex' )
	);
}
add_action( 'admin_notices', 'studentspex_setup_notice' );

/**
 * Pattern content by slug.
 *
 * @param string $slug Pattern slug.
 * @return string
 */
function studentspex_pattern_content( $slug ) {
	$pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( $slug );
	return $pattern && ! empty( $pattern['content'] ) ? $pattern['content'] : '';
}

/**
 * Create (or find) the pages, then fill them from patterns.
 *
 * @param bool $overwrite Replace content of existing pages too.
 * @return array Log lines.
 */
function studentspex_setup_create_pages( $overwrite = false ) {
	$log   = array();
	$ids   = array();
	$pages = studentspex_setup_pages();

	// Pass 1: make sure every page exists, so patterns can link to each other.
	foreach ( $pages as $slug => $def ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			$ids[ $slug ] = $page->ID;
			continue;
		}
		$ids[ $slug ]           = wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => $def[0],
				'post_name'   => $slug,
				'menu_order'  => count( $ids ),
			)
		);
		$log[]                  = sprintf( /* translators: %s: page title */ __( 'Skapade sidan %s.', 'studentspex' ), $def[0] );
		$ids[ $slug . '__new' ] = true;
	}

	// Pass 2: content from patterns + the block template.
	foreach ( $pages as $slug => $def ) {
		if ( ! $def[1] || ! $ids[ $slug ] ) {
			continue;
		}
		$is_new = ! empty( $ids[ $slug . '__new' ] );
		if ( $is_new || $overwrite ) {
			wp_update_post(
				array(
					'ID'           => $ids[ $slug ],
					'post_content' => wp_slash( studentspex_pattern_content( $def[1] ) ),
				)
			);
			update_post_meta( $ids[ $slug ], '_wp_page_template', 'page-blocks' );
			if ( ! $is_new ) {
				$log[] = sprintf( /* translators: %s: page title */ __( 'Återställde innehållet på %s (den gamla versionen finns under Revisioner).', 'studentspex' ), $def[0] );
			}
		}
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $ids['hem'] );
	update_option( 'page_for_posts', $ids['nyheter'] );
	$log[] = __( 'Hem är startsida och Nyheter är inläggssida.', 'studentspex' );

	return array( $log, $ids );
}

/**
 * Header navigation (wp_navigation post) referenced by the header pattern.
 *
 * @param array $ids Page IDs by slug.
 * @return array Log lines.
 */
function studentspex_setup_navigation( $ids ) {
	$existing = (int) get_option( 'studentspex_nav_id' );
	if ( $existing && get_post( $existing ) ) {
		return array( __( 'Menyn fanns redan – lämnades orörd.', 'studentspex' ) );
	}
	$items = '';
	foreach ( array( 'hem', 'nasta-spex', 'sok-spexet', 'om-spexet' ) as $slug ) {
		$items .= spex_block(
			'core/navigation-link',
			array(
				'label' => get_the_title( $ids[ $slug ] ),
				'type'  => 'page',
				'id'    => (int) $ids[ $slug ],
				'url'   => get_permalink( $ids[ $slug ] ),
				'kind'  => 'post-type',
			)
		);
	}
	$items .= spex_block(
		'core/navigation-link',
		array(
			'label' => __( 'Gamla spex', 'studentspex' ),
			'url'   => get_post_type_archive_link( 'spex_uppsattning' ),
			'kind'  => 'custom',
		)
	);
	$items .= spex_block(
		'core/navigation-link',
		array(
			'label' => get_the_title( $ids['kontakt'] ),
			'type'  => 'page',
			'id'    => (int) $ids['kontakt'],
			'url'   => get_permalink( $ids['kontakt'] ),
			'kind'  => 'post-type',
		)
	);
	$nav_id = wp_insert_post(
		array(
			'post_type'    => 'wp_navigation',
			'post_status'  => 'publish',
			'post_title'   => __( 'Huvudmeny', 'studentspex' ),
			'post_content' => wp_slash( $items ),
		)
	);
	update_option( 'studentspex_nav_id', $nav_id );
	return array( __( 'Skapade huvudmenyn.', 'studentspex' ) );
}

/**
 * The upcoming production with placeholder content, set as current.
 *
 * @return array Log lines.
 */
function studentspex_setup_current_show() {
	if ( studentspex_current_show_id() ) {
		return array( __( 'Det fanns redan en aktuell uppsättning – lämnades orörd.', 'studentspex' ) );
	}
	$year                     = (int) gmdate( 'n' ) >= 3 ? (int) gmdate( 'Y' ) + 1 : (int) gmdate( 'Y' );
	$id                       = wp_insert_post(
		array(
			'post_type'    => 'spex_uppsattning',
			'post_status'  => 'draft',
			/* translators: %d: year */
			'post_title'   => sprintf( __( 'Spex %d', 'studentspex' ), $year ),
			'post_content' => wp_slash(
				spex_p( 'Följ med oss till en tid och plats som vi avslöjar till jul. Där väntar intriger, maktspel och minst en person som borde ha stannat hemma den dagen.' )
				. spex_p( 'Här kommer 2–3 meningar om handlingen: vilka huvudpersonerna är, vad som står på spel och vilken historisk händelse vi driver med i år.' )
			),
			'meta_input'   => array(
				'year'          => $year,
				'subtitle'      => 'Undertiteln avslöjas i jul',
				'venue'         => 'Forumteatern',
				'venue_address' => 'Kårhuset Kollektivet, Ågatan 55, Linköping',
				'price_regular' => '190',
				'price_reduced' => '130',
				'ticket_title'  => 'Biljettsläpp i december',
				'ticket_text'   => 'Biljettkassan öppnar i december. Vi säljer via Biljettkiosken – du skickas vidare dit när du klickar.',
				'performances'  => array(
					array(
						'date'  => $year . '-02-06',
						'time'  => '19.00',
						'label' => 'Premiär',
					),
					array(
						'date'  => $year . '-02-10',
						'time'  => '19.00',
						'label' => '',
					),
					array(
						'date'  => $year . '-02-12',
						'time'  => '19.00',
						'label' => '',
					),
					array(
						'date'  => $year . '-02-13',
						'time'  => '15.00 & 19.00',
						'label' => '',
					),
					array(
						'date'  => $year . '-02-18',
						'time'  => '19.00',
						'label' => '',
					),
					array(
						'date'  => $year . '-02-20',
						'time'  => '19.00',
						'label' => 'Sista föreställningen',
					),
				),
			),
		)
	);
	$settings                 = (array) get_option( 'studentspex_settings', array() );
	$settings['current_show'] = $id;
	update_option( 'studentspex_settings', $settings );
	/* translators: %d: year */
	return array( sprintf( __( 'Skapade Spex %d som utkast och gjorde den aktuell. Fyll i titel, datum och bilder under Uppsättningar.', 'studentspex' ), $year ) );
}

/**
 * Import past productions from data/gamla-spex.json (text only; posters added later).
 *
 * @return array Log lines.
 */
function studentspex_setup_import_archive() {
	$file = STUDENTSPEX_DIR . '/data/gamla-spex.json';
	$rows = file_exists( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : array(); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$made = 0;
	foreach ( (array) $rows as $row ) {
		$dupe = get_posts(
			array(
				'post_type'      => 'spex_uppsattning',
				'post_status'    => 'any',
				'title'          => $row['title'],
				'meta_key'       => 'year',
				'meta_value'     => (int) $row['year'], // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		if ( $dupe ) {
			continue;
		}
		wp_insert_post(
			array(
				'post_type'    => 'spex_uppsattning',
				'post_status'  => 'publish',
				'post_title'   => $row['title'],
				'post_excerpt' => $row['description'],
				'post_content' => wp_slash( spex_p( esc_html( $row['description'] ) ) ),
				'post_date'    => sprintf( '%04d-02-01 12:00:00', (int) $row['year'] ),
				'meta_input'   => array(
					'year'       => (int) $row['year'],
					'year_label' => isset( $row['year_label'] ) ? $row['year_label'] : '',
					'subtitle'   => $row['subtitle'],
				),
			)
		);
		++$made;
	}
	/* translators: %d: number of productions */
	return array( sprintf( _n( 'Importerade %d gammal uppsättning.', 'Importerade %d gamla uppsättningar.', $made, 'studentspex' ), $made ) );
}

/**
 * Handle the setup form.
 */
function studentspex_setup_handle() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Du har inte behörighet.', 'studentspex' ) );
	}
	check_admin_referer( 'studentspex_setup' );
	$log = array();

	list( $page_log, $ids ) = studentspex_setup_create_pages( ! empty( $_POST['overwrite'] ) );
	$log                    = array_merge( $log, $page_log, studentspex_setup_navigation( $ids ), studentspex_setup_current_show() );
	if ( ! empty( $_POST['import'] ) ) {
		$log = array_merge( $log, studentspex_setup_import_archive() );
	}
	flush_rewrite_rules();
	update_option( 'studentspex_setup_done', time() );
	set_transient( 'studentspex_setup_log', $log, 300 );
	wp_safe_redirect( admin_url( 'admin.php?page=studentspex-setup&done=1' ) );
	exit;
}
add_action( 'admin_post_studentspex_setup', 'studentspex_setup_handle' );

/**
 * Setup page.
 */
function studentspex_setup_page() {
	$log = get_transient( 'studentspex_setup_log' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Kom igång med Studentspex', 'studentspex' ); ?></h1>
		<?php if ( $log ) : ?>
			<div class="notice notice-success"><ul style="list-style:disc;padding-left:1.4em">
				<?php foreach ( $log as $line ) : ?>
					<li><?php echo esc_html( $line ); ?></li>
				<?php endforeach; ?>
			</ul>
			<p><a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Visa webbplatsen', 'studentspex' ); ?></a>
			<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=spex_uppsattning' ) ); ?>"><?php esc_html_e( 'Till Uppsättningar', 'studentspex' ); ?></a></p></div>
			<?php delete_transient( 'studentspex_setup_log' ); ?>
		<?php endif; ?>
		<div class="card" style="max-width:720px">
			<p><?php esc_html_e( 'Ett klick skapar allt du behöver på en ny WordPress-installation:', 'studentspex' ); ?></p>
			<ul style="list-style:disc;padding-left:1.4em">
				<li><?php esc_html_e( 'Sidorna Hem, Nästa spex, Sök spexet, Om spexet, Kontakt och Nyheter – med innehållet från designen.', 'studentspex' ); ?></li>
				<li><?php esc_html_e( 'Huvudmenyn, startsida och inläggssida.', 'studentspex' ); ?></li>
				<li><?php esc_html_e( 'Nästa års uppsättning som utkast, vald som aktuell.', 'studentspex' ); ?></li>
			</ul>
			<p><?php esc_html_e( 'Befintliga sidor och uppsättningar dupliceras aldrig.', 'studentspex' ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'studentspex_setup' ); ?>
				<input type="hidden" name="action" value="studentspex_setup">
				<p><label><input type="checkbox" name="import" value="1" checked> <?php esc_html_e( 'Importera de 47 tidigare uppsättningarna 1981–2026 till Gamla spex (titel, år och beskrivning – affischer lägger du till själv).', 'studentspex' ); ?></label></p>
				<p><label><input type="checkbox" name="overwrite" value="1"> <?php esc_html_e( 'Återställ innehållet på sidor som redan finns (den gamla versionen sparas som revision).', 'studentspex' ); ?></label></p>
				<?php submit_button( __( 'Skapa allt', 'studentspex' ), 'primary', 'submit', false ); ?>
			</form>
		</div>
		<h2><?php esc_html_e( 'Nästa steg', 'studentspex' ); ?></h2>
		<ol>
			<li><?php esc_html_e( 'Spexet → Inställningar: lägg in länken till ansökningsformuläret och kontrollera sociala medier.', 'studentspex' ); ?></li>
			<li><?php esc_html_e( 'Uppsättningar: fyll i årets titel, datum, omslagsbild och Lisse.', 'studentspex' ); ?></li>
			<li><?php esc_html_e( 'Utseende → Redigerare → Mönster → Sidfot: lägg till sponsorernas logotyper i "Tack till".', 'studentspex' ); ?></li>
		</ol>
	</div>
	<?php
}
