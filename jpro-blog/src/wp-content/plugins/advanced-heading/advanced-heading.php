<?php
/**
 * Plugin Name:     Advanced Heading
 * Description:     Create Advanced Heading with Title, Subtitle and Separator Controls
 * Version:         1.1.0
 * Author:          WPDeveloper
 * Author URI:      https://wpdeveloper.net
 * License:         GPL-3.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:     advanced-heading
 *
 * @package         advanced-heading
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */

require_once __DIR__ . '/includes/font-loader.php';
require_once __DIR__ . '/includes/post-meta.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/lib/style-handler/style-handler.php';

function create_block_advanced_heading_block_init()
{
	define('ADVANCEDHEADING_BLOCK_VERSION', "1.1.0");
	define('ADVANCEDHEADING_BLOCK_ADMIN_URL', plugin_dir_url(__FILE__));
	define('ADVANCEDHEADING_BLOCK_ADMIN_PATH', dirname(__FILE__));

	$script_asset_path = ADVANCEDHEADING_BLOCK_ADMIN_PATH . "/dist/index.asset.php";
	if (!file_exists($script_asset_path)) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "block/testimonial" block first.'
		);
	}
	$index_js     = ADVANCEDHEADING_BLOCK_ADMIN_URL . 'dist/index.js';
	$script_asset = require($script_asset_path);
	$all_dependencies = array_merge($script_asset['dependencies'], array(
		'wp-blocks',
		'wp-i18n',
		'wp-element',
		'wp-block-editor',
		'advancedheading-block-controls-util',
	));

	wp_register_script(
		'create-block-advancedheading-block-editor-script',
		$index_js,
		$all_dependencies,
		$script_asset['version'],
		true
	);

	$fontawesome_css = ADVANCEDHEADING_BLOCK_ADMIN_URL . 'lib/resources/css/font-awesome5.css';
	wp_register_style(
		'fontawesome-frontend-css',
		$fontawesome_css,
		array(),
		ADVANCEDHEADING_BLOCK_VERSION
	);

	wp_register_style(
		'fontpicker-default-theme',
		ADVANCEDHEADING_BLOCK_ADMIN_URL . 'lib/resources/css/fonticonpicker.base-theme.react.css',
		array(),
		ADVANCEDHEADING_BLOCK_VERSION,
		'all'
	);

	wp_register_style(
		'fontpicker-material-theme',
		ADVANCEDHEADING_BLOCK_ADMIN_URL . 'lib/resources/css/fonticonpicker.material-theme.react.css',
		array(),
		ADVANCEDHEADING_BLOCK_VERSION,
		'all'
	);

	$style_css = ADVANCEDHEADING_BLOCK_ADMIN_URL . 'dist/style.css';
	//Editor Style
	wp_register_style(
		'create-block-advancedheading-block-editor-style',
		$style_css,
		array(
			'fontawesome-frontend-css', 
			'fontpicker-default-theme',
			'fontpicker-material-theme'
		),
		ADVANCEDHEADING_BLOCK_VERSION
	);
	//Frontend Style
	wp_register_style(
		'create-block-advancedheading-block-frontend-style',
		$style_css,
		array('fontawesome-frontend-css'),
		ADVANCEDHEADING_BLOCK_VERSION
	);

	if (!WP_Block_Type_Registry::get_instance()->is_registered('essential-blocks/advanced-heading')) {
		register_block_type(
			Advanced_Heading_Helper::get_block_register_path("advanced-heading/advanced-heading", ADVANCEDHEADING_BLOCK_ADMIN_PATH),
			array(
				'editor_script'	=> 'create-block-advancedheading-block-editor-script',
				'editor_style' 	=> 'create-block-advancedheading-block-editor-style',
				'render_callback' => function ($attributes, $content) {
					if (!is_admin()) {
						wp_enqueue_style('create-block-advancedheading-block-frontend-style');
					}
					return $content;
				}
			)
		);
	}
}
add_action( 'init', 'create_block_advanced_heading_block_init' );