<?php
if ( ! function_exists( 'zbauerarchitect_support' ) ) :
	function zbauerarchitect_support() {
		// Make theme available for translation.
		load_theme_textdomain( 'zbauerarchitect' );
		if ( ! 'zbauerarchitect' === wp_get_theme()->get( 'TextDomain' ) ) {
			load_theme_textdomain( wp_get_theme()->get( 'TextDomain' ) );
		}

		// Alignwide and alignfull classes in the block editor.
		add_theme_support( 'align-wide' );

		// Add support for link color control.
		add_theme_support( 'link-color' );

		// Add support for responsive embedded content.
		// https://github.com/WordPress/gutenberg/issues/26901
		add_theme_support( 'responsive-embeds' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for post thumbnails.
		add_theme_support( 'post-thumbnails' );

		// Experimental support for adding blocks inside nav menus
		add_theme_support( 'block-nav-menus' );

		// Enqueue editor styles.
		add_editor_style(
			array(
				'/assets/ponyfill.css',
			)
		);

		register_nav_menus(
			array(
				'primary' => __( 'Primary Navigation', 'zbauerarchitect' ),
				'social'  => __( 'Social Navigation', 'zbauerarchitect' ),
				'footer' => __( 'Footer Navigation', 'zbauerarchitect' ),
			)
		);

		add_filter(
			'block_editor_settings_all',
			function( $settings ) {
				$settings['defaultBlockTemplate'] = '<!-- wp:group {"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:post-content /--></div><!-- /wp:group -->';
				return $settings;
			}
		);

		// Add support for core custom logo.
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 192,
				'width'       => 192,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

	}
endif;
add_action( 'after_setup_theme', 'zbauerarchitect_support', 9 );

/**
 *
 * Enqueue scripts and styles.
 */
function zbauerarchitect_editor_styles() {
	// Add the child theme CSS if it exists.
	if ( file_exists( get_stylesheet_directory() . '/assets/theme.css' ) ) {
		add_editor_style(
			'/assets/theme.css'
		);
	}
}
add_action( 'admin_init', 'zbauerarchitect_editor_styles' );

/**
 *
 * Enqueue scripts and styles.
 */
function zbauerarchitect_scripts() {
	wp_enqueue_style( 'zbauerarchitect-ponyfill', get_template_directory_uri() . '/assets/ponyfill.css', array() );

	if ( file_exists( get_stylesheet_directory() . '/assets/theme.css' ) ) {
		wp_enqueue_style( 'zbauerarchitect-child-styles', get_stylesheet_directory_uri() . '/assets/theme.css', array( 'zbauerarchitect-ponyfill' ) );
	}
}

add_action( 'wp_enqueue_scripts', 'zbauerarchitect_scripts' );

// Force menus to reload
add_action(
	'customize_controls_enqueue_scripts',
	static function () {
		wp_enqueue_script(
			'wp-customize-nav-menu-refresh',
			get_template_directory_uri() . '/inc/customizer/wp-customize-nav-menu-refresh.js',
			array( 'customize-nav-menus' ),
			wp_get_theme()->get( 'Version' ),
			true
		);
	}
);

/**
 * Block Patterns.
 */
require get_template_directory() . '/inc/block-patterns.php';

// Add the child theme patterns if they exist.
if ( file_exists( get_stylesheet_directory() . '/inc/block-patterns.php' ) ) {
	require_once get_stylesheet_directory() . '/inc/block-patterns.php';
}

function enqueue_styles() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', [], '6.5.0');
    wp_enqueue_style('theme-style', get_stylesheet_uri(), ['font-awesome'], null);
}
add_action('wp_enqueue_scripts', 'enqueue_styles');

// -----Projects------

require_once get_template_directory() . '/projects.php';
