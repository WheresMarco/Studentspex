<?php
/**
 * Built-in newsletter: double opt-in subscriptions stored in a custom table.
 *
 * Flow: form → POST /wp-json/studentspex/v1/subscribe → pending row + confirmation
 * mail → ?spex_confirm=<token> marks it confirmed. Every subscriber has a personal
 * unsubscribe link (?spex_unsub=<id>.<hmac>), included in the confirmation mail and
 * in the CSV export so it can be merged into announcement mails.
 *
 * @package Studentspex
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/admin.php';

const STUDENTSPEX_NL_DB_VERSION = '1';

/**
 * Table name.
 *
 * @return string
 */
function studentspex_nl_table() {
	global $wpdb;
	return $wpdb->prefix . 'spex_subscribers';
}

/**
 * Create or upgrade the table.
 */
function studentspex_nl_install() {
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	$table   = studentspex_nl_table();
	$charset = $wpdb->get_charset_collate();
	dbDelta(
		"CREATE TABLE {$table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			email varchar(190) NOT NULL,
			status varchar(20) NOT NULL DEFAULT 'pending',
			token_hash char(64) NOT NULL DEFAULT '',
			token_expires datetime NULL DEFAULT NULL,
			consent_text text NOT NULL,
			created_at datetime NOT NULL,
			confirmed_at datetime NULL DEFAULT NULL,
			unsubscribed_at datetime NULL DEFAULT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY email (email),
			KEY status (status),
			KEY token_hash (token_hash)
		) {$charset};"
	);
	update_option( 'studentspex_nl_db_version', STUDENTSPEX_NL_DB_VERSION );
}
add_action( 'after_switch_theme', 'studentspex_nl_install' );

/**
 * Install lazily if the theme was activated before the table existed.
 */
function studentspex_nl_maybe_install() {
	if ( get_option( 'studentspex_nl_db_version' ) !== STUDENTSPEX_NL_DB_VERSION ) {
		studentspex_nl_install();
	}
}
add_action( 'init', 'studentspex_nl_maybe_install', 5 );

/**
 * Hash a confirmation token for storage.
 *
 * @param string $token Raw token.
 * @return string
 */
function studentspex_nl_hash( $token ) {
	return hash_hmac( 'sha256', $token, wp_salt( 'nonce' ) );
}

/**
 * Personal unsubscribe URL (stateless HMAC, never expires).
 *
 * @param int    $id    Subscriber ID.
 * @param string $email Address.
 * @return string
 */
function studentspex_nl_unsubscribe_url( $id, $email ) {
	$sig = substr( hash_hmac( 'sha256', 'unsub|' . $id . '|' . strtolower( $email ), wp_salt( 'auth' ) ), 0, 32 );
	return add_query_arg( 'spex_unsub', $id . '.' . $sig, home_url( '/' ) );
}

/**
 * REST route.
 */
function studentspex_nl_routes() {
	register_rest_route(
		'studentspex/v1',
		'/subscribe',
		array(
			'methods'             => 'POST',
			'permission_callback' => '__return_true',
			'callback'            => 'studentspex_nl_subscribe',
			'args'                => array(
				'email'        => array(
					'type'     => 'string',
					'required' => true,
				),
				'consent'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'consent_text' => array(
					'type'    => 'string',
					'default' => '',
				),
				'website'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'nonce'        => array(
					'type'    => 'string',
					'default' => '',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'studentspex_nl_routes' );

/**
 * Error helper.
 *
 * @param string $code    Code.
 * @param string $message Message shown to the visitor.
 * @param int    $status  HTTP status.
 * @return WP_Error
 */
function studentspex_nl_error( $code, $message, $status = 400 ) {
	return new WP_Error( $code, $message, array( 'status' => $status ) );
}

/**
 * Handle a signup.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function studentspex_nl_subscribe( WP_REST_Request $request ) {
	global $wpdb;

	// Honeypot: pretend success so bots learn nothing.
	if ( '' !== trim( (string) $request['website'] ) ) {
		return rest_ensure_response( array( 'ok' => true ) );
	}
	if ( ! wp_verify_nonce( (string) $request['nonce'], 'studentspex_subscribe' ) ) {
		return studentspex_nl_error( 'bad_nonce', __( 'Sidan har legat öppen länge – ladda om den och försök igen.', 'studentspex' ), 403 );
	}

	// Rate limit: 5 attempts per 10 minutes per (hashed) IP. Filterable for busy shared networks.
	$limit   = (int) apply_filters( 'studentspex_nl_rate_limit', 5 );
	$ip      = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$rl_key  = 'spex_nl_rl_' . substr( hash_hmac( 'sha256', $ip, wp_salt( 'nonce' ) ), 0, 20 );
	$attempt = (int) get_transient( $rl_key );
	if ( $attempt >= $limit ) {
		return studentspex_nl_error( 'rate_limited', __( 'För många försök. Vänta en stund och försök igen.', 'studentspex' ), 429 );
	}
	set_transient( $rl_key, $attempt + 1, 10 * MINUTE_IN_SECONDS );

	$raw   = strtolower( trim( (string) $request['email'] ) );
	$email = strtolower( sanitize_email( $raw ) );
	if ( ! $email || ! is_email( $email ) ) {
		return studentspex_nl_error( 'bad_email', __( 'Det där ser inte ut som en e-postadress – kolla gärna stavningen.', 'studentspex' ) );
	}
	// sanitize_email() silently drops characters such as å/ä/ö; never store a different address than the one typed.
	if ( $email !== $raw ) {
		return studentspex_nl_error( 'bad_email', __( 'Adressen innehåller tecken som inte fungerar i e-post (t.ex. å, ä, ö). Kolla stavningen.', 'studentspex' ) );
	}
	if ( ! $request['consent'] ) {
		return studentspex_nl_error( 'no_consent', __( 'Kryssa i rutan så vi vet att det är okej att mejla dig.', 'studentspex' ) );
	}
	$consent_text = sanitize_text_field( (string) $request['consent_text'] );
	if ( '' === $consent_text ) {
		$consent_text = __( 'Samtycke till e-post när ett nytt spex annonseras.', 'studentspex' );
	}

	$table    = studentspex_nl_table();
	$existing = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE email = %s", $email ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery

	// Already confirmed: respond exactly like a new signup (no account enumeration), send nothing.
	if ( $existing && 'confirmed' === $existing->status ) {
		return rest_ensure_response( array( 'ok' => true ) );
	}

	$token   = bin2hex( random_bytes( 24 ) );
	$now     = current_time( 'mysql', true );
	$expires = gmdate( 'Y-m-d H:i:s', time() + 7 * DAY_IN_SECONDS );
	$data    = array(
		'status'          => 'pending',
		'token_hash'      => studentspex_nl_hash( $token ),
		'token_expires'   => $expires,
		'consent_text'    => $consent_text,
		'unsubscribed_at' => null,
	);

	if ( $existing ) {
		$wpdb->update( $table, $data, array( 'id' => $existing->id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$id = (int) $existing->id;
	} else {
		$wpdb->insert(
			$table,
			array_merge(
				$data,
				array(
					'email'      => $email,
					'created_at' => $now,
				)
			)
		); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$id = (int) $wpdb->insert_id;
	}
	if ( ! $id ) {
		return studentspex_nl_error( 'db', __( 'Något gick fel. Försök igen om en stund.', 'studentspex' ), 500 );
	}

	$sent = studentspex_nl_send_confirmation( $id, $email, $token );
	if ( ! $sent ) {
		return studentspex_nl_error( 'mail', __( 'Vi kunde inte skicka bekräftelsemejlet just nu. Försök igen senare.', 'studentspex' ), 500 );
	}
	return rest_ensure_response( array( 'ok' => true ) );
}

/**
 * From header for newsletter mails.
 *
 * @return array
 */
function studentspex_nl_headers() {
	$name    = studentspex_setting( 'newsletter_from_name' );
	$address = studentspex_setting( 'newsletter_from_email' );
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( $address ) {
		$headers[] = sprintf( 'From: %s <%s>', $name ? $name : get_bloginfo( 'name' ), $address );
	}
	return $headers;
}

/**
 * Send the double opt-in mail.
 *
 * @param int    $id    Subscriber ID.
 * @param string $email Address.
 * @param string $token Raw token.
 * @return bool
 */
function studentspex_nl_send_confirmation( $id, $email, $token ) {
	$confirm = add_query_arg( 'spex_confirm', rawurlencode( $token ), home_url( '/' ) );
	$unsub   = studentspex_nl_unsubscribe_url( $id, $email );
	$site    = get_bloginfo( 'name' );
	/* translators: %s: site name */
	$subject = sprintf( __( 'Bekräfta din prenumeration – %s', 'studentspex' ), $site );
	$body    = implode(
		"\n\n",
		array(
			__( 'Hej!', 'studentspex' ),
			__( 'Någon (förhoppningsvis du) vill få ett mejl när vi annonserar nästa spex. Klicka på länken för att bekräfta:', 'studentspex' ),
			$confirm,
			__( 'Länken gäller i 7 dagar. Om det inte var du kan du ignorera det här mejlet – då händer ingenting.', 'studentspex' ),
			__( 'Vill du avregistrera dig senare? Använd den här länken:', 'studentspex' ) . "\n" . $unsub,
			'— ' . $site,
		)
	);
	return wp_mail( $email, $subject, $body, studentspex_nl_headers() );
}

/**
 * Handle confirm and unsubscribe links.
 */
function studentspex_nl_handle_links() {
	global $wpdb;
	$table = studentspex_nl_table();

	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- links from e-mail; secured by token / HMAC.
	if ( isset( $_GET['spex_confirm'] ) ) {
		$token = sanitize_text_field( wp_unslash( $_GET['spex_confirm'] ) );
		$row   = $token ? $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE token_hash = %s", studentspex_nl_hash( $token ) ) ) : null; // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery
		$state = 'invalid';
		if ( $row && 'confirmed' === $row->status ) {
			$state = 'confirmed';
		} elseif ( $row && $row->token_expires && strtotime( $row->token_expires . ' UTC' ) > time() ) {
			$wpdb->update(
				$table,
				array(
					'status'        => 'confirmed',
					'confirmed_at'  => current_time( 'mysql', true ),
					'token_hash'    => '',
					'token_expires' => null,
				),
				array( 'id' => $row->id )
			); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$state = 'confirmed';
		} elseif ( $row ) {
			$state = 'expired';
		}
		wp_safe_redirect( add_query_arg( 'nyhetsbrev', $state, home_url( '/' ) ) );
		exit;
	}

	if ( isset( $_GET['spex_unsub'] ) ) {
		$raw   = sanitize_text_field( wp_unslash( $_GET['spex_unsub'] ) );
		$state = 'invalid';
		if ( preg_match( '/^(\d+)\.([a-f0-9]{32})$/', $raw, $m ) ) {
			$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", (int) $m[1] ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery
			if ( $row && hash_equals( substr( studentspex_nl_unsubscribe_url( $row->id, $row->email ), -32 ), $m[2] ) ) {
				$wpdb->update(
					$table,
					array(
						'status'          => 'unsubscribed',
						'unsubscribed_at' => current_time( 'mysql', true ),
						'token_hash'      => '',
					),
					array( 'id' => $row->id )
				); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$state = 'unsubscribed';
			}
		}
		wp_safe_redirect( add_query_arg( 'nyhetsbrev', $state, home_url( '/' ) ) );
		exit;
	}
	// phpcs:enable
}
add_action( 'template_redirect', 'studentspex_nl_handle_links', 1 );

/**
 * Toast after confirm / unsubscribe redirects.
 */
function studentspex_nl_toast() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$state    = isset( $_GET['nyhetsbrev'] ) ? sanitize_key( $_GET['nyhetsbrev'] ) : '';
	$messages = array(
		'confirmed'    => array( '🎟️', __( 'Tack! Du får ett mejl när nästa spex annonseras.', 'studentspex' ) ),
		'unsubscribed' => array( '👋', __( 'Du är avregistrerad och får inga fler mejl från oss.', 'studentspex' ) ),
		'expired'      => array( '⏰', __( 'Länken har gått ut. Prenumerera igen så skickar vi en ny.', 'studentspex' ) ),
		'invalid'      => array( '🤔', __( 'Länken fungerade inte. Den kan redan ha använts.', 'studentspex' ) ),
	);
	if ( ! isset( $messages[ $state ] ) ) {
		return;
	}
	printf(
		'<div class="toast" role="status"><span aria-hidden="true">%1$s</span><p>%2$s</p><button type="button" class="toast__close" aria-label="%3$s" onclick="this.parentNode.remove()">×</button></div>',
		esc_html( $messages[ $state ][0] ),
		esc_html( $messages[ $state ][1] ),
		esc_attr__( 'Stäng', 'studentspex' )
	);
}
add_action( 'wp_footer', 'studentspex_nl_toast' );

/**
 * Daily cleanup: drop unconfirmed signups older than 30 days (data minimisation).
 */
function studentspex_nl_cleanup() {
	global $wpdb;
	$table = studentspex_nl_table();
	$wpdb->query( $wpdb->prepare( "DELETE FROM {$table} WHERE status = 'pending' AND created_at < %s", gmdate( 'Y-m-d H:i:s', time() - 30 * DAY_IN_SECONDS ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery
}
add_action( 'studentspex_nl_cleanup', 'studentspex_nl_cleanup' );
add_action(
	'init',
	function () {
		if ( ! wp_next_scheduled( 'studentspex_nl_cleanup' ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'studentspex_nl_cleanup' );
		}
	}
);
add_action(
	'switch_theme',
	function () {
		wp_clear_scheduled_hook( 'studentspex_nl_cleanup' );
	}
);

/**
 * GDPR: personal data exporter.
 *
 * @param array $exporters Exporters.
 * @return array
 */
function studentspex_nl_register_exporter( $exporters ) {
	$exporters['studentspex-newsletter'] = array(
		'exporter_friendly_name' => __( 'Studentspex nyhetsbrev', 'studentspex' ),
		'callback'               => function ( $email ) {
			global $wpdb;
			$table = studentspex_nl_table();
			$row   = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE email = %s", strtolower( $email ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery
			$data  = array();
			if ( $row ) {
				$data[] = array(
					'group_id'    => 'studentspex-newsletter',
					'group_label' => __( 'Nyhetsbrev', 'studentspex' ),
					'item_id'     => 'subscriber-' . $row->id,
					'data'        => array(
						array(
							'name'  => __( 'E-post', 'studentspex' ),
							'value' => $row->email,
						),
						array(
							'name'  => __( 'Status', 'studentspex' ),
							'value' => $row->status,
						),
						array(
							'name'  => __( 'Samtycke', 'studentspex' ),
							'value' => $row->consent_text,
						),
						array(
							'name'  => __( 'Registrerad', 'studentspex' ),
							'value' => $row->created_at,
						),
						array(
							'name'  => __( 'Bekräftad', 'studentspex' ),
							'value' => (string) $row->confirmed_at,
						),
					),
				);
			}
			return array(
				'data' => $data,
				'done' => true,
			);
		},
	);
	return $exporters;
}
add_filter( 'wp_privacy_personal_data_exporters', 'studentspex_nl_register_exporter' );

/**
 * GDPR: personal data eraser.
 *
 * @param array $erasers Erasers.
 * @return array
 */
function studentspex_nl_register_eraser( $erasers ) {
	$erasers['studentspex-newsletter'] = array(
		'eraser_friendly_name' => __( 'Studentspex nyhetsbrev', 'studentspex' ),
		'callback'             => function ( $email ) {
			global $wpdb;
			$deleted = (bool) $wpdb->delete( studentspex_nl_table(), array( 'email' => strtolower( $email ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			return array(
				'items_removed'  => $deleted,
				'items_retained' => false,
				'messages'       => array(),
				'done'           => true,
			);
		},
	);
	return $erasers;
}
add_filter( 'wp_privacy_personal_data_erasers', 'studentspex_nl_register_eraser' );
