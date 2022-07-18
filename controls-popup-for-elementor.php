<?php
/**
* Plugin Name: Controls popup for Elementor
* Description: This plugin adds the ability to expand Elementor text controls and code editors in a popup window for better editing experience
* Plugin URI: https://github.com/webdevs-pro/controls-popup-for-elementor
* Version: 1.0
* Author: MagnificSoft
* Author URI: https://github.com/webdevs-pro/
* License: GPL 3.0
* Text Domain: controls-popup-for-elementor
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! function_exists('get_plugin_data') ){
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

define('MAE_VERSION', get_plugin_data( __FILE__ )['Version']);
define('MAE_PATH', plugin_dir_path(__FILE__));
define('MAE_URL', plugins_url('', __FILE__));
define('MAE_BASENAME', plugin_basename(__FILE__));



final class Magnific_Addons {

	const MINIMUM_ELEMENTOR_VERSION = '3.0.2';

	const MINIMUM_PHP_VERSION = '5.6';

	public function __construct() {

		// Load translation
		add_action( 'init', array( $this, 'i18n' ) );

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function i18n() {
		load_plugin_textdomain( 'controls-popup-for-elementor', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
	}

	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once( 'plugin.php' );
	}

	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'controls-popup-for-elementor' ),
			'<strong>' . esc_html__( 'Magnific Addons for Elementor', 'controls-popup-for-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'controls-popup-for-elementor' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'controls-popup-for-elementor' ),
			'<strong>' . esc_html__( 'Magnific Addons for Elementor', 'controls-popup-for-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'controls-popup-for-elementor' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'controls-popup-for-elementor' ),
			'<strong>' . esc_html__( 'Magnific Addons for Elementor', 'controls-popup-for-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'controls-popup-for-elementor' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}
new Magnific_Addons();


require 'plugin-update-checker/plugin-update-checker.php';
$cpfeUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/webdevs-pro/controls-popup-for-elementor/',
	__FILE__,
	'controls-popup-for-elementor'
);

//Set the branch that contains the stable release.
$cpfeUpdateChecker->setBranch('main');