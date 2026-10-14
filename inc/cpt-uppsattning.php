<?php
/**
 * "Uppsättning" custom post type: one entry per production year.
 *
 * The production chosen as current (Spexet → Inställningar) drives the front
 * page hero and Nästa spex; every other published production appears in Gamla spex.
 *
 * @package Studentspex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the post type and its meta.
 */
function studentspex_register_uppsattning() {
	register_post_type(
		'spex_uppsattning',
		array(
			'labels'        => array(
				'name'                  => __( 'Uppsättningar', 'studentspex' ),
				'singular_name'         => __( 'Uppsättning', 'studentspex' ),
				'add_new'               => __( 'Lägg till ny', 'studentspex' ),
				'add_new_item'          => __( 'Lägg till uppsättning', 'studentspex' ),
				'edit_item'             => __( 'Redigera uppsättning', 'studentspex' ),
				'new_item'              => __( 'Ny uppsättning', 'studentspex' ),
				'view_item'             => __( 'Visa uppsättning', 'studentspex' ),
				'search_items'          => __( 'Sök uppsättningar', 'studentspex' ),
				'not_found'             => __( 'Inga uppsättningar hittades', 'studentspex' ),
				'all_items'             => __( 'Alla uppsättningar', 'studentspex' ),
				'featured_image'        => __( 'Affisch', 'studentspex' ),
				'set_featured_image'    => __( 'Välj affisch', 'studentspex' ),
				'remove_featured_image' => __( 'Ta bort affisch', 'studentspex' ),
				'menu_name'             => __( 'Uppsättningar', 'studentspex' ),
			),
			'public'        => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-tickets-alt',
			'menu_position' => 5,
			'has_archive'   => 'gamla-spex',
			'rewrite'       => array(
				'slug'       => 'gamla-spex',
				'with_front' => false,
			),
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),
			'template'      => array( array( 'core/paragraph', array( 'placeholder' => __( 'Handlingen i korthet…', 'studentspex' ) ) ) ),
		)
	);

	$auth = function ( $allowed, $meta_key, $post_id ) {
		return current_user_can( 'edit_post', $post_id );
	};

	$string_fields = array(
		'subtitle',
		'year_label',
		'venue',
		'venue_address',
		'price_regular',
		'price_reduced',
		'price_reduced_note',
		'ticket_title',
		'ticket_text',
	);
	foreach ( $string_fields as $key ) {
		register_post_meta(
			'spex_uppsattning',
			$key,
			array(
				'type'              => 'string',
				'single'            => true,
				'default'           => '',
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => $auth,
			)
		);
	}
	register_post_meta(
		'spex_uppsattning',
		'ticket_url',
		array(
			'type'              => 'string',
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => true,
			'sanitize_callback' => 'esc_url_raw',
			'auth_callback'     => $auth,
		)
	);
	foreach ( array( 'year', 'cover_image', 'lisse_image' ) as $key ) {
		register_post_meta(
			'spex_uppsattning',
			$key,
			array(
				'type'              => 'integer',
				'single'            => true,
				'default'           => 0,
				'show_in_rest'      => true,
				'sanitize_callback' => 'absint',
				'auth_callback'     => $auth,
			)
		);
	}
	register_post_meta(
		'spex_uppsattning',
		'performances',
		array(
			'type'              => 'array',
			'single'            => true,
			'default'           => array(),
			'auth_callback'     => $auth,
			'show_in_rest'      => array(
				'schema' => array(
					'type'  => 'array',
					'items' => array(
						'type'                 => 'object',
						'additionalProperties' => false,
						'properties'           => array(
							'date'  => array( 'type' => 'string' ),
							'time'  => array( 'type' => 'string' ),
							'label' => array( 'type' => 'string' ),
						),
					),
				),
			),
			'sanitize_callback' => 'studentspex_sanitize_performances',
		)
	);
}
add_action( 'init', 'studentspex_register_uppsattning' );

/**
 * Sanitise the performance list.
 *
 * @param mixed $value Raw value.
 * @return array
 */
function studentspex_sanitize_performances( $value ) {
	$out = array();
	foreach ( (array) $value as $row ) {
		$row  = (array) $row;
		$date = isset( $row['date'] ) ? sanitize_text_field( $row['date'] ) : '';
		if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
			continue;
		}
		$out[] = array(
			'date'  => $date,
			'time'  => isset( $row['time'] ) ? sanitize_text_field( $row['time'] ) : '',
			'label' => isset( $row['label'] ) ? sanitize_text_field( $row['label'] ) : '',
		);
	}
	return $out;
}

/**
 * Archive: every production except the current one, newest year first.
 *
 * @param WP_Query $query Query.
 */
function studentspex_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'spex_uppsattning' ) ) {
		return;
	}
	$query->set( 'posts_per_page', -1 );
	$query->set( 'meta_key', 'year' );
	$query->set(
		'orderby',
		array(
			'meta_value_num' => 'DESC',
			'title'          => 'ASC',
		)
	);
	$current = studentspex_current_show_id();
	if ( $current ) {
		$query->set( 'post__not_in', array( $current ) );
	}
}
add_action( 'pre_get_posts', 'studentspex_archive_query' );

/**
 * Admin list: year column, sortable, and a "Aktuell" marker.
 *
 * @param array $cols Columns.
 * @return array
 */
function studentspex_uppsattning_columns( $cols ) {
	$new = array();
	foreach ( $cols as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['spex_year'] = __( 'År', 'studentspex' );
		}
	}
	return $new;
}
add_filter( 'manage_spex_uppsattning_posts_columns', 'studentspex_uppsattning_columns' );

/**
 * Render custom admin columns.
 *
 * @param string $col     Column.
 * @param int    $post_id Post.
 */
function studentspex_uppsattning_column( $col, $post_id ) {
	if ( 'spex_year' !== $col ) {
		return;
	}
	$label = get_post_meta( $post_id, 'year_label', true );
	echo esc_html( $label ? $label : (string) get_post_meta( $post_id, 'year', true ) );
	if ( studentspex_current_show_id() === (int) $post_id ) {
		echo ' <strong style="color:#2653d9">' . esc_html__( '· Aktuell', 'studentspex' ) . '</strong>';
	}
}
add_action( 'manage_spex_uppsattning_posts_custom_column', 'studentspex_uppsattning_column', 10, 2 );

add_filter(
	'manage_edit-spex_uppsattning_sortable_columns',
	function ( $cols ) {
		$cols['spex_year'] = 'spex_year';
		return $cols;
	}
);

/**
 * Sort the admin list by year by default.
 *
 * @param WP_Query $query Query.
 */
function studentspex_admin_sort( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'spex_uppsattning' !== $query->get( 'post_type' ) ) {
		return;
	}
	$orderby = $query->get( 'orderby' );
	if ( ! $orderby || 'spex_year' === $orderby ) {
		$query->set( 'meta_key', 'year' );
		$query->set( 'orderby', 'meta_value_num' );
		if ( ! $query->get( 'order' ) || ! $orderby ) {
			$query->set( 'order', 'DESC' );
		}
	}
}
add_action( 'pre_get_posts', 'studentspex_admin_sort' );

/**
 * Flush rewrite rules once after activation so /gamla-spex/ works immediately.
 */
function studentspex_flush_rewrites() {
	studentspex_register_uppsattning();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'studentspex_flush_rewrites' );

/**
 * The public archive is called "Gamla spex", not "Uppsättningar".
 *
 * @param string $title     Archive title.
 * @param string $post_type Post type.
 * @return string
 */
function studentspex_archive_title( $title, $post_type = '' ) {
	return 'spex_uppsattning' === $post_type ? __( 'Gamla spex', 'studentspex' ) : $title;
}
add_filter( 'post_type_archive_title', 'studentspex_archive_title', 10, 2 );
