<?php
/**
 * Site header: logo, navigation and call-to-action button.
 *
 * @package Studentspex
 * @var string $content
 */

?>
<header <?php echo studentspex_wrapper( 'site-header', array(), $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="container site-header__inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo studentspex_logo_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="<?php esc_attr_e( 'Öppna menyn', 'studentspex' ); ?>"><span class="nav-toggle__bars"></span></button>
		<div class="nav" id="site-nav"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	</div>
</header>
