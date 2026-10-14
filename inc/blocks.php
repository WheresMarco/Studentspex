<?php
/**
 * Custom block registration and editor integration.
 *
 * Every block lives in src/blocks/<name>/ and is compiled to build/blocks/<name>/.
 * All blocks are dynamic (render.php) so later design changes never break saved content.
 *
 * @package Studentspex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register every compiled block.
 */
function studentspex_register_blocks() {
	$dirs = glob( STUDENTSPEX_DIR . '/build/blocks/*', GLOB_ONLYDIR );
	foreach ( (array) $dirs as $dir ) {
		if ( file_exists( $dir . '/block.json' ) ) {
			register_block_type( $dir );
		}
	}
}
add_action( 'init', 'studentspex_register_blocks' );

/**
 * Block inserter category.
 *
 * @param array $categories Categories.
 * @return array
 */
function studentspex_block_category( $categories ) {
	array_unshift(
		$categories,
		array(
			'slug'  => 'studentspex',
			'title' => __( 'Studentspex', 'studentspex' ),
			'icon'  => 'star-filled',
		)
	);
	return $categories;
}
add_filter( 'block_categories_all', 'studentspex_block_category' );

/**
 * Data the editor needs to preview blocks that read from the current production.
 *
 * @return array
 */
function studentspex_editor_data() {
	$show = studentspex_show( studentspex_current_show_id() );
	if ( $show ) {
		unset( $show['content'] );
		$show['premiere'] = $show['performances'] ? studentspex_date_parts( $show['performances'][0]['date'] )['long'] : '';
	}
	return array(
		'currentShow'  => $show,
		'defaultLisse' => studentspex_default_lisse_url(),
		'settingsUrl'  => admin_url( 'admin.php?page=studentspex' ),
		'themeUrl'     => STUDENTSPEX_URI,
	);
}

/**
 * Editor-only script: shared data, plus the production sidebar on Uppsättningar.
 */
function studentspex_editor_assets() {
	$asset_file = STUDENTSPEX_DIR . '/build/sidebar/index.asset.php';
	if ( file_exists( $asset_file ) ) {
		$asset = include $asset_file;
		wp_enqueue_script( 'studentspex-sidebar', STUDENTSPEX_URI . '/build/sidebar/index.js', $asset['dependencies'], $asset['version'], true );
		wp_set_script_translations( 'studentspex-sidebar', 'studentspex', STUDENTSPEX_DIR . '/languages' );
	}
	wp_add_inline_script( 'wp-blocks', 'window.studentspexEditor = ' . wp_json_encode( studentspex_editor_data() ) . ';', 'before' );
}
add_action( 'enqueue_block_editor_assets', 'studentspex_editor_assets' );

/**
 * Wrapper attributes helper for render.php files.
 *
 * @param string $css_class Extra classes.
 * @param array  $extra      Extra attributes.
 * @param array  $attributes Block attributes (for the anchor id).
 * @return string
 */
function studentspex_wrapper( $css_class, $extra = array(), $attributes = array() ) {
	if ( ! empty( $attributes['anchor'] ) && empty( $extra['id'] ) ) {
		$extra['id'] = sanitize_html_class( $attributes['anchor'] );
	}
	return get_block_wrapper_attributes( array_merge( array( 'class' => $css_class ), $extra ) );
}
