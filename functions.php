<?php
/**
 * Studentspex theme bootstrap.
 *
 * @package Studentspex
 */

defined( 'ABSPATH' ) || exit;

define( 'STUDENTSPEX_VERSION', '1.0.0' );
define( 'STUDENTSPEX_DIR', get_template_directory() );
define( 'STUDENTSPEX_URI', get_template_directory_uri() );

require STUDENTSPEX_DIR . '/inc/helpers.php';
require STUDENTSPEX_DIR . '/inc/pattern-helpers.php';
require STUDENTSPEX_DIR . '/inc/settings.php';
require STUDENTSPEX_DIR . '/inc/cpt-uppsattning.php';
require STUDENTSPEX_DIR . '/inc/blocks.php';
require STUDENTSPEX_DIR . '/inc/newsletter/newsletter.php';
require STUDENTSPEX_DIR . '/inc/setup.php';

/**
 * Theme supports and translations.
 */
function studentspex_setup() {
	load_theme_textdomain( 'studentspex', STUDENTSPEX_DIR . '/languages' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 140,
			'width'       => 260,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_editor_style( 'assets/css/style.css' );
	add_image_size( 'studentspex-poster', 520, 728, true );
	add_image_size( 'studentspex-cover', 2000, 1200, false );
}
add_action( 'after_setup_theme', 'studentspex_setup' );

/**
 * Front-end assets.
 */
function studentspex_enqueue_assets() {
	wp_enqueue_style( 'studentspex', STUDENTSPEX_URI . '/assets/css/style.css', array(), STUDENTSPEX_VERSION );
	wp_enqueue_script(
		'studentspex',
		STUDENTSPEX_URI . '/assets/js/main.js',
		array(),
		STUDENTSPEX_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
	wp_localize_script(
		'studentspex',
		'studentspexData',
		array(
			'subscribeUrl' => esc_url_raw( rest_url( 'studentspex/v1/subscribe' ) ),
			'nonce'        => wp_create_nonce( 'studentspex_subscribe' ),
			'restNonce'    => wp_create_nonce( 'wp_rest' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'studentspex_enqueue_assets' );

/**
 * Mark <html> as JS-capable before paint so reveal animations don't flash.
 */
function studentspex_js_class() {
	echo "<script>document.documentElement.classList.add('js');</script>\n";
}
add_action( 'wp_head', 'studentspex_js_class', 1 );

/**
 * Block styles on core blocks used by the patterns.
 */
function studentspex_register_block_styles() {
	$styles = array(
		'core/paragraph' => array(
			'eyebrow'   => __( 'Överrubrik', 'studentspex' ),
			'lead'      => __( 'Ingress', 'studentspex' ),
			'pitch-big' => __( 'Stor slogan', 'studentspex' ),
		),
		'core/button'    => array(
			'gold'  => __( 'Guld', 'studentspex' ),
			'blue'  => __( 'Blå', 'studentspex' ),
			'ghost' => __( 'Kontur', 'studentspex' ),
		),
		'core/image'     => array(
			'lisse-frame' => __( 'Lisse-ram', 'studentspex' ),
		),
		'core/group'     => array(
			'prose'     => __( 'Brödtext', 'studentspex' ),
			'post-card' => __( 'Inläggskort', 'studentspex' ),
		),
	);
	foreach ( $styles as $block => $list ) {
		foreach ( $list as $name => $label ) {
			register_block_style(
				$block,
				array(
					'name'  => $name,
					'label' => $label,
				)
			);
		}
	}
}
add_action( 'init', 'studentspex_register_block_styles' );

/**
 * Pattern category for the theme's sections and pages.
 */
function studentspex_pattern_categories() {
	register_block_pattern_category( 'studentspex-pages', array( 'label' => __( 'Studentspex – hela sidor', 'studentspex' ) ) );
	register_block_pattern_category( 'studentspex', array( 'label' => __( 'Studentspex – sektioner', 'studentspex' ) ) );
}
add_action( 'init', 'studentspex_pattern_categories' );

/**
 * Buttons whose visibility follows the settings:
 * - "#ansokan" points at the application form (Spexet → Inställningar), hidden when none is set.
 * - "#biljetter" (header and hero ticket buttons) is hidden while the current production has no ticket link.
 *
 * @param string $html Block HTML.
 * @return string
 */
function studentspex_dynamic_buttons( $html ) {
	if ( false !== strpos( $html, 'href="#ansokan"' ) ) {
		$url = studentspex_setting( 'application_url' );
		if ( ! $url ) {
			return '';
		}
		return str_replace( 'href="#ansokan"', 'href="' . esc_url( $url ) . '" target="_blank" rel="noopener"', $html );
	}
	if ( false !== strpos( $html, '#biljetter"' ) && ! studentspex_tickets_on_sale() ) {
		return '';
	}
	return $html;
}
add_filter( 'render_block_core/button', 'studentspex_dynamic_buttons' );
