<?php
/**
 * Plugin Name: Aparat Elementor Single Video Player
 * Plugin URI: https://github.com/arashmoradi/aparat-elementor-single-video-player
 * Description: یک ویجت Elementor برای نمایش ویدیوهای آپارات و self-hosted
 * Version: 1.0.0
 * Author: ARASH MORADI
 * Author URI: https://github.com/arashmoradi
 * Text Domain: aparat-elementor-single-video-player
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Elementor tested up to: 3.0.0
 * Elementor Pro tested up to: 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Aparat Elementor Single Video Player Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Aparat_Elementor_Single_Video_Player {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var Aparat_Elementor_Single_Video_Player The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return Aparat_Elementor_Single_Video_Player An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );

	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'aparat-elementor-single-video-player' );

	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function on_plugins_loaded() {

		if ( $this->is_compatible() ) {
			add_action( 'elementor/init', [ $this, 'init' ] );
		}

	}

	/**
	 * Compatibility Checks
	 *
	 * Checks if the installed version of Elementor meets the plugin requirements.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function is_compatible() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return false;
		}

		// Check if ELEMENTOR_VERSION constant exists
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return false;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return false;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return false;
		}

		return true;

	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		$this->i18n();

		// Add Plugin actions
		// For Elementor 3.5.0+
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		
		// For older versions of Elementor
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {

		$widget_file = __DIR__ . '/widgets/aparat-video-player.php';
		
		if ( ! file_exists( $widget_file ) ) {
			return;
		}

		require_once( $widget_file );

		// Check if class exists
		if ( ! class_exists( '\Elementor\Aparat_Elementor_Single_Video_Player_Widget' ) ) {
			return;
		}

		// For Elementor 3.5.0+
		if ( method_exists( $widgets_manager, 'register' ) ) {
			$widgets_manager->register( new \Elementor\Aparat_Elementor_Single_Video_Player_Widget() );
		} else {
			// For older versions
			if ( method_exists( $widgets_manager, 'register_widget_type' ) ) {
				$widgets_manager->register_widget_type( new \Elementor\Aparat_Elementor_Single_Video_Player_Widget() );
			}
		}

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'aparat-elementor-single-video-player' ),
			'<strong>' . esc_html__( 'Aparat Elementor Single Video Player', 'aparat-elementor-single-video-player' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'aparat-elementor-single-video-player' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'aparat-elementor-single-video-player' ),
			'<strong>' . esc_html__( 'Aparat Elementor Single Video Player', 'aparat-elementor-single-video-player' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'aparat-elementor-single-video-player' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'aparat-elementor-single-video-player' ),
			'<strong>' . esc_html__( 'Aparat Elementor Single Video Player', 'aparat-elementor-single-video-player' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'aparat-elementor-single-video-player' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

}

Aparat_Elementor_Single_Video_Player::instance();

