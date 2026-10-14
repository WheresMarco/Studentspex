<?php
/**
 * Tiny serialisers for writing patterns in PHP.
 *
 * Output matches what the editor itself saves, so patterns and setup-created pages
 * open without "unexpected content" warnings.
 *
 * @package Studentspex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Any block: comment delimiters + inner markup.
 *
 * @param string $name  Block name (core/ prefix optional for core blocks).
 * @param array  $attrs Attributes.
 * @param string $inner Inner markup.
 * @return string
 */
function spex_block( $name, $attrs = array(), $inner = '' ) {
	return get_comment_delimited_block_content( $name, $attrs, $inner ) . "\n";
}

/**
 * Studentspex block.
 *
 * @param string       $name  Block name without namespace.
 * @param array        $attrs Attributes.
 * @param string|array $inner Inner blocks.
 * @return string
 */
function spex_b( $name, $attrs = array(), $inner = '' ) {
	return spex_block( 'studentspex/' . $name, $attrs, is_array( $inner ) ? implode( '', $inner ) : $inner );
}

/**
 * Paragraph.
 *
 * @param string $html  Inline HTML.
 * @param string $style Block style name (eyebrow, lead, pitch-big) or ''.
 * @return string
 */
function spex_p( $html, $style = '' ) {
	$attrs = $style ? array( 'className' => 'is-style-' . $style ) : array();
	$open  = $style ? '<p class="is-style-' . esc_attr( $style ) . '">' : '<p>';
	return spex_block( 'core/paragraph', $attrs, $open . $html . '</p>' );
}

/**
 * Heading.
 *
 * @param string $html  Text.
 * @param int    $level Level.
 * @return string
 */
function spex_h( $html, $level = 2 ) {
	$attrs = 2 === $level ? array() : array( 'level' => $level );
	return spex_block( 'core/heading', $attrs, sprintf( '<h%1$d class="wp-block-heading">%2$s</h%1$d>', $level, $html ) );
}

/**
 * Button group.
 *
 * @param array $buttons List of [text, url, style].
 * @return string
 */
function spex_buttons( $buttons ) {
	$inner = '';
	foreach ( $buttons as $b ) {
		$style     = isset( $b[2] ) ? $b[2] : '';
		$attrs     = $style ? array( 'className' => 'is-style-' . $style ) : array();
		$css_class = 'wp-block-button' . ( $style ? ' is-style-' . $style : '' );
		$inner    .= spex_block( 'core/button', $attrs, sprintf( '<div class="%s"><a class="wp-block-button__link wp-element-button" href="%s">%s</a></div>', esc_attr( $css_class ), esc_url( $b[1] ), esc_html( $b[0] ) ) );
	}
	return spex_block( 'core/buttons', array(), '<div class="wp-block-buttons">' . $inner . '</div>' );
}

/**
 * FAQ entry (core Details).
 *
 * @param string $q Question.
 * @param string $a Answer HTML (one paragraph).
 * @return string
 */
function spex_details( $q, $a ) {
	return spex_block( 'core/details', array(), '<details class="wp-block-details"><summary>' . esc_html( $q ) . '</summary>' . spex_p( $a ) . '</details>' );
}

/**
 * Image without attachment ID (theme asset).
 *
 * @param string $src   URL.
 * @param string $alt   Alt text.
 * @param string $style Block style name or ''.
 * @return string
 */
function spex_image( $src, $alt, $style = '' ) {
	$attrs     = array(
		'sizeSlug'        => 'full',
		'linkDestination' => 'none',
	);
	$css_class = 'wp-block-image size-full';
	if ( $style ) {
		$attrs['className'] = 'is-style-' . $style;
		$css_class         .= ' is-style-' . $style;
	}
	return spex_block( 'core/image', $attrs, sprintf( '<figure class="%s"><img src="%s" alt="%s"/></figure>', esc_attr( $css_class ), esc_url( $src ), esc_attr( $alt ) ) );
}

/**
 * Two vertically centred columns.
 *
 * @param string $left  Inner blocks.
 * @param string $right Inner blocks.
 * @return string
 */
function spex_columns( $left, $right ) {
	$col = function ( $inner ) {
		return spex_block( 'core/column', array( 'verticalAlignment' => 'center' ), '<div class="wp-block-column is-vertically-aligned-center">' . $inner . '</div>' );
	};
	return spex_block( 'core/columns', array( 'verticalAlignment' => 'center' ), '<div class="wp-block-columns are-vertically-aligned-center">' . $col( $left ) . $col( $right ) . '</div>' );
}

/**
 * Group with a class and default (flow) layout.
 *
 * @param string $css_class Class name.
 * @param string $inner Inner blocks.
 * @param string $tag   Tag name.
 * @return string
 */
function spex_group( $css_class, $inner, $tag = 'div' ) {
	$attrs = array( 'layout' => array( 'type' => 'default' ) );
	if ( $css_class ) {
		$attrs['className'] = $css_class;
	}
	if ( 'div' !== $tag ) {
		$attrs['tagName'] = $tag;
	}
	return spex_block( 'core/group', $attrs, sprintf( '<%1$s class="wp-block-group%2$s">%3$s</%1$s>', $tag, $css_class ? ' ' . esc_attr( $css_class ) : '', $inner ) );
}

/**
 * Plain list of links.
 *
 * @param array  $links [text => url].
 * @param string $css_class Class name.
 * @return string
 */
function spex_link_list( $links, $css_class ) {
	$items = '';
	foreach ( $links as $text => $url ) {
		$items .= spex_block( 'core/list-item', array(), sprintf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $text ) ) );
	}
	return spex_block( 'core/list', array( 'className' => $css_class ), '<ul class="wp-block-list ' . esc_attr( $css_class ) . '">' . $items . '</ul>' );
}
