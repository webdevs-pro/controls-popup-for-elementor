<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use \Elementor\Core\Settings\Manager as SettingsManager;

// enqueue editor js
add_action( 'elementor/editor/after_enqueue_scripts', function() {
	wp_enqueue_script( 'cpfe-script', plugin_dir_url( __FILE__ ) . '/assets/js/cpfe.js' );

	$plugin_settings = array(
		'cpfe_plugin_url' => plugin_dir_url( __FILE__ ),
	);

	wp_localize_script('cpfe-script', 'ControlsPopupForElementor', $plugin_settings);
});



// enqueue css
add_action( 'elementor/editor/after_enqueue_styles', function() {

	$ui_theme = SettingsManager::get_settings_managers( 'editorPreferences' )->get_model()->get_settings( 'ui_theme' );
	if ( 'light' !== $ui_theme ) {
		$ui_theme_media_queries = 'all';
		if ( 'auto' === $ui_theme ) {
			$ui_theme_media_queries = '(prefers-color-scheme: dark)';
		}
		wp_enqueue_style(
			'-dark-mode',
			plugin_dir_url( __FILE__ ) . 'assets/css/cpfe-dark-mode.css',
			'',
			'',
			$ui_theme_media_queries
		);
	}
	
	wp_enqueue_style('cpfe', plugin_dir_url( __FILE__ ) . '/assets/css/cpfe.css', [], CPFE_VERSION);
} );