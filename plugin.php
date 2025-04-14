<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


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
	wp_enqueue_style('cpfe', plugin_dir_url( __FILE__ ) . '/assets/css/cpfe.css', [], CPFE_VERSION);
} );