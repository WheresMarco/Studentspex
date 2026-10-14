<?php
/**
 * "Spexet" admin menu and the theme settings page.
 *
 * @package Studentspex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin menu: Spexet → Inställningar (+ Nyhetsbrev and Kom igång added elsewhere).
 */
function studentspex_admin_menu() {
	add_menu_page(
		__( 'Spexet', 'studentspex' ),
		__( 'Spexet', 'studentspex' ),
		'manage_options',
		'studentspex',
		'studentspex_settings_page',
		'dashicons-star-filled',
		3
	);
	add_submenu_page( 'studentspex', __( 'Inställningar', 'studentspex' ), __( 'Inställningar', 'studentspex' ), 'manage_options', 'studentspex', 'studentspex_settings_page' );
}
add_action( 'admin_menu', 'studentspex_admin_menu' );

/**
 * Register the option and its fields.
 */
function studentspex_register_settings() {
	register_setting(
		'studentspex',
		'studentspex_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'studentspex_sanitize_settings',
			'default'           => array(),
		)
	);

	add_settings_section(
		'spex_show',
		__( 'Aktuell uppsättning', 'studentspex' ),
		function () {
			echo '<p>' . esc_html__( 'Uppsättningen som visas på startsidan och under Nästa spex. Alla andra publicerade uppsättningar visas i Gamla spex.', 'studentspex' ) . '</p>';
		},
		'studentspex'
	);
	add_settings_field( 'current_show', __( 'Nästa spex', 'studentspex' ), 'studentspex_field_current_show', 'studentspex', 'spex_show', array( 'label_for' => 'spex-current_show' ) );

	add_settings_section( 'spex_links', __( 'Länkar', 'studentspex' ), '__return_false', 'studentspex' );
	$links = array(
		'instagram_url'    => array( __( 'Instagram (länk)', 'studentspex' ), 'url' ),
		'instagram_handle' => array( __( 'Instagram (namn som visas)', 'studentspex' ), 'text' ),
		'facebook_url'     => array( __( 'Facebook (länk)', 'studentspex' ), 'url' ),
		'tiktok_url'       => array( __( 'TikTok (länk, valfri)', 'studentspex' ), 'url' ),
		'application_url'  => array( __( 'Ansökningsformulär (länk)', 'studentspex' ), 'url' ),
		'ticket_email'     => array( __( 'Biljettmejl', 'studentspex' ), 'email' ),
	);
	foreach ( $links as $key => $def ) {
		add_settings_field(
			$key,
			$def[0],
			'studentspex_field_input',
			'studentspex',
			'spex_links',
			array(
				'key'         => $key,
				'type'        => $def[1],
				'label_for'   => 'spex-' . $key,
				'description' => 'application_url' === $key
					? __( 'Om fältet är tomt döljs knapparna "Till ansökan" och "Sök spexet" som leder till formuläret.', 'studentspex' )
					: '',
			)
		);
	}

	add_settings_section(
		'spex_newsletter',
		__( 'Nyhetsbrev', 'studentspex' ),
		function () {
			echo '<p>' . esc_html__( 'Avsändare för bekräftelsemejlet som skickas när någon prenumererar.', 'studentspex' ) . '</p>';
		},
		'studentspex'
	);
	add_settings_field(
		'newsletter_from_name',
		__( 'Avsändarnamn', 'studentspex' ),
		'studentspex_field_input',
		'studentspex',
		'spex_newsletter',
		array(
			'key'       => 'newsletter_from_name',
			'type'      => 'text',
			'label_for' => 'spex-newsletter_from_name',
		)
	);
	add_settings_field(
		'newsletter_from_email',
		__( 'Avsändaradress', 'studentspex' ),
		'studentspex_field_input',
		'studentspex',
		'spex_newsletter',
		array(
			'key'         => 'newsletter_from_email',
			'type'        => 'email',
			'label_for'   => 'spex-newsletter_from_email',
			'description' => __( 'Lämna tomt för WordPress standard. Använd en adress på din egen domän så hamnar mejlen inte i skräpposten.', 'studentspex' ),
		)
	);
}
add_action( 'admin_init', 'studentspex_register_settings' );

/**
 * Sanitise all settings.
 *
 * @param array $input Raw input.
 * @return array
 */
function studentspex_sanitize_settings( $input ) {
	$input = (array) $input;
	$out   = studentspex_setting();
	if ( array_key_exists( 'current_show', $input ) ) {
		$out['current_show'] = absint( $input['current_show'] );
	}
	foreach ( array( 'instagram_url', 'facebook_url', 'tiktok_url', 'application_url' ) as $key ) {
		if ( array_key_exists( $key, $input ) ) {
			$out[ $key ] = esc_url_raw( trim( (string) $input[ $key ] ) );
		}
	}
	foreach ( array( 'instagram_handle', 'newsletter_from_name' ) as $key ) {
		if ( array_key_exists( $key, $input ) ) {
			$out[ $key ] = sanitize_text_field( (string) $input[ $key ] );
		}
	}
	foreach ( array( 'ticket_email', 'newsletter_from_email' ) as $key ) {
		if ( array_key_exists( $key, $input ) ) {
			$out[ $key ] = sanitize_email( (string) $input[ $key ] );
		}
	}
	return $out;
}

/**
 * Current-show select.
 */
function studentspex_field_current_show() {
	$current = (int) studentspex_setting( 'current_show' );
	$shows   = get_posts(
		array(
			'post_type'      => 'spex_uppsattning',
			'post_status'    => array( 'publish', 'draft', 'future', 'private' ),
			'posts_per_page' => 60,
			'meta_key'       => 'year',
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
		)
	);
	echo '<select id="spex-current_show" name="studentspex_settings[current_show]">';
	echo '<option value="0">' . esc_html__( '— Ingen —', 'studentspex' ) . '</option>';
	foreach ( $shows as $show ) {
		$label = get_post_meta( $show->ID, 'year', true ) . ' – ' . get_the_title( $show );
		if ( 'publish' !== $show->post_status ) {
			$label .= ' (' . __( 'utkast', 'studentspex' ) . ')';
		}
		printf( '<option value="%d" %s>%s</option>', (int) $show->ID, selected( $current, $show->ID, false ), esc_html( $label ) );
	}
	echo '</select>';
	echo '<p class="description">' . esc_html__( 'Tips: den aktuella uppsättningen får gärna vara ett utkast tills titeln släpps – startsidan visar ändå datum, plats och priser.', 'studentspex' ) . '</p>';
}

/**
 * Generic text/url/email input.
 *
 * @param array $args Field args.
 */
function studentspex_field_input( $args ) {
	$key = $args['key'];
	printf(
		'<input class="regular-text" id="spex-%1$s" type="%2$s" name="studentspex_settings[%1$s]" value="%3$s">',
		esc_attr( $key ),
		esc_attr( $args['type'] ),
		esc_attr( (string) studentspex_setting( $key ) )
	);
	if ( ! empty( $args['description'] ) ) {
		echo '<p class="description">' . esc_html( $args['description'] ) . '</p>';
	}
}

/**
 * Settings page output.
 */
function studentspex_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	echo '<div class="wrap"><h1>' . esc_html__( 'Spexet – inställningar', 'studentspex' ) . '</h1><form method="post" action="options.php">';
	settings_fields( 'studentspex' );
	do_settings_sections( 'studentspex' );
	submit_button();
	echo '</form></div>';
}
