<?php
/**
 * Zbauerarchitect Theme: Block Patterns
 *
 * @package Zbauerarchitect
 */
if ( ! function_exists( 'zbauerarchitect_register_block_patterns' ) ) :

	function zbauerarchitect_register_block_patterns() {

		if ( function_exists( 'register_block_pattern_category' ) ) {
			register_block_pattern_category(
				'zbauerarchitect',
				array( 'label' => __( 'Zbauerarchitect', 'zbauerarchitect' ) )
			);
		}

		if ( function_exists( 'register_block_pattern' ) ) {
			$block_patterns = array();

			foreach ( $block_patterns as $block_pattern ) {
				register_block_pattern(
					'zbauerarchitect/' . $block_pattern,
					require __DIR__ . '/patterns/' . $block_pattern . '.php'
				);
			}

			//register header templates also as patterns
			$header_patterns = array(
				'centered',
				'default',
				'linear',
				'minimal',
				'rounded-logo',
				'wide',
			);

			foreach ( $header_patterns as $header_pattern ) {
				register_block_pattern(
					'zbauerarchitect/header-' . $header_pattern,
					array(
						'title'      => __( 'Zbauerarchitect Header (' . $header_pattern . ')', 'zbauerarchitect' ),
						'categories' => array( 'header' ),
						'blockTypes' => array( 'core/template-part/header' ),
						'content'    => file_get_contents (get_theme_file_path( '/parts/header-' . $header_pattern . '.html' )),
					)
				);
			}
		}
	}
endif;

function register_all_custom_blocks() {
    // Featured Projects (dynamic)
    register_block_type_from_metadata(
        get_template_directory() . '/blocks/src/components/featured-projects',
        [
            'render_callback' => function() {
                return '<div id="featured-projects-app"></div>';
            }
        ]
    );
 	register_block_type_from_metadata(
        get_template_directory() . '/blocks/src/components/about-info',
        [
            'render_callback' => function() {
                return '<div id="about-info-app"></div>';
            }
        ]
    );
	register_block_type_from_metadata(
        get_template_directory() . '/blocks/src/pages/projects-router',
        [
            'render_callback' => function () {
                return '<div id="projects-router-app"></div>';
            }
        ]
    );

}
add_action('init', 'register_all_custom_blocks');


function enqueue_custom_blocks_assets() {
   

    $script_path =  '/blocks/build/bundle.js';
	$style_path  =  '/blocks/build/bundle.css';

    $handle = 'custom-blocks-bundle';

    wp_enqueue_script(
        $handle,
        get_template_directory_uri() . $script_path,
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'],
        filemtime(get_template_directory() . $script_path)
    );
    
    wp_enqueue_style(
        $handle,
        get_template_directory_uri() . $style_path,
        [],
        filemtime(get_template_directory() . $style_path)
    );

}

add_action('enqueue_block_editor_assets', 'enqueue_custom_blocks_assets');
add_action('wp_enqueue_scripts', 'enqueue_custom_blocks_assets'); // frontend too if needed
