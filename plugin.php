<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use \Elementor\Core\Settings\Manager as SettingsManager;

// enqueue editor js
add_action( 'elementor/editor/after_enqueue_scripts', function() {
	wp_enqueue_script( 'mae-editor', plugin_dir_url( __FILE__ ) . '/assets/js/magnific-addons.js' );

	$plugin_settings = array(
		'mae_plugin_url' => plugin_dir_url( __FILE__ ),
	);

	$plugin_settings['mae_text_popup_enabled'] = '1';
	$plugin_settings['mae_code_popup_enabled'] = '1';
	$plugin_settings['mae_textarea_popup_enabled'] = '1';

	wp_localize_script('mae-editor', 'MagnificAddons', $plugin_settings);
});

// enqueue frontend js
add_action( 'elementor/frontend/after_enqueue_scripts', function() {
	wp_enqueue_script( 'mae-editor-frontend', plugin_dir_url( __FILE__ ) . '/assets/js/magnific-addons-frontend.js', [ 'jquery' ], MAE_VERSION );
});
add_action( 'elementor/preview/enqueue_scripts', function() {
	wp_enqueue_style('magnific-addons-preview-preview', plugin_dir_url( __FILE__ ) . '/assets/css/magnific-addons-preview.css', [], MAE_VERSION);
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
			plugin_dir_url( __FILE__ ) . 'assets/css/magnific-addons-dark-mode.css',
			'',
			'',
			$ui_theme_media_queries
		);
	}
	
	wp_enqueue_style('magnific-addons', plugin_dir_url( __FILE__ ) . '/assets/css/magnific-addons.css', [], MAE_VERSION);
} );

























// // this will work for single template condition
// add_action( 'elementor/theme/register_conditions', function( $conditions_manager ) {
// 	class Page_Template_Condition extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {
// 		public static function get_type() {
// 			return 'singular';
// 		}

// 		public static function get_priority() {
// 			return 100;
// 		}

// 		public function get_name() {
// 			return 'page_template';
// 		}

// 		public function get_label() {
// 			return __( 'Page Template' );
// 		}

// 		public function check( $args ) {
// 			return isset( $args['id'] ) && is_page_template( $args['id'] );
// 		}

// 		protected function _register_controls() {
// 			$this->add_control(
// 				'page_template',
// 				[
// 					'section' => 'settings',
// 					'label' => __( 'Page Template' ),
// 					'type' => \Elementor\Controls_Manager::SELECT,
// 					'options' => array_flip( get_page_templates() ),
// 				]
// 			);
// 		}
// 	}

// 	$conditions_manager->get_condition( 'singular' )->register_sub_condition( new Page_Template_Condition() );
// }, 100 );








add_action( 'elementor/theme/register_conditions', function( $conditions_manager ) {
	class MAE_Mobile extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {
		public static function get_type() {
			return 'singular';
		}
		public static function get_priority() {
			return 100;
		}
		public function get_name() {
			return 'mobile';
		}
		public function get_label() {
			return __( 'Mobile', 'elementor-pro' );
		}
		public function check( $args ) {
			return wp_is_mobile();
		}
	}
	$conditions_manager->get_condition('singular')->register_sub_condition( new MAE_Mobile() );
 },100);

 add_action( 'elementor/theme/register_conditions', function( $conditions_manager ) {
	class MAE_Desktop extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {
		public static function get_type() {
			return 'singular';
		}
		public static function get_priority() {
			return 20;
		}
		public function get_name() {
			return 'desktop';
		}
		public function get_label() {
			return __( 'Desktop', 'elementor-pro' );
		}
		public function check( $args ) {
			return !wp_is_mobile();
		}
	}
	$conditions_manager->get_condition('singular')->register_sub_condition( new MAE_Desktop() );
 },100);



add_action( 'elementor/theme/register_conditions', function( $conditions_manager ) {
	class MAE_Device_Type  extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {
		protected $sub_conditions = [];
		public static function get_type() {
			return 'device_type';
		}
		public function get_name() {
			return 'device_type';
		}
		public static function get_priority() {
			return 60;
		}
		public function get_label() {
			return __( 'Device Type', 'ele-custom-skin' );
		}
		public function get_all_label() {
			return __( 'All', 'ele-custom-skin' );
		}
		public function register_sub_conditions() {
			$this->sub_conditions[] = 'desktop';
			$this->sub_conditions[] = 'mobile';
		}
		public function check( $args ) {
			return true;
		}
	}
  	$conditions_manager->get_condition('general')->register_sub_condition( new MAE_Device_Type() );
},100);