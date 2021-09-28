<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       https://github.com/wpmotto/wp-blaze-css
 * @since      1.1.2
 *
 * @package    BlazeCss
 * @subpackage BlazeCss/includes
 */

namespace Motto\BlazeCss;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.1.2
 * @package    BlazeCss
 * @subpackage BlazeCss/includes
 * @author     Greg Hunt <plugins@wpmotto.com>
 */
class Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.1.2
	 * @access   protected
	 * @var      Motto\BlazeCss\Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	public $settings;

	protected $db;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $BlazeCss    The string used to uniquely identify this plugin.
	 */
	protected $BlazeCss = 'blaze';

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version = '1.1.2';

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->loader = new Loader();
		$this->settings = new Settings($this);
	}

	public function get_root_path()
	{
		return plugin_dir_path( dirname( __FILE__ ) );		
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new I18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );
		$plugin_i18n->load_plugin_textdomain();

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Admin( $this );

		// $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_settings_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'init_settings' );
		$this->loader->add_action( 'wp_ajax_blaze_generate_csv', $plugin_admin, 'generate_csv' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_frontend_hooks() {

		$plugin_frontend = new Frontend( $this );

		if( $this->should_log() ) {
			$this->loader->add_action(
				'wp_enqueue_scripts', $plugin_frontend, 'enqueue_scripts' 
			);
			$this->loader->add_action( 
				'get_header', $plugin_frontend, 'debug' 
			);
		}

		/**
		 * CSS Purging
		 */
		// $this->loader->add_action( 'get_footer', $plugin_frontend, 'generateCSS' );
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_frontend, 'removeQueued', 99 );

		// to logged in users
        $this->loader->add_action( 'wp_ajax_blaze_ajax', $plugin_frontend, 'save_page_elements' );
		
        // to not logged in users or users without permissions
        $this->loader->add_action( 
			'wp_ajax_nopriv_blaze_ajax', $plugin_frontend, 'save_page_elements' 
		);
	}

	private function should_log()
	{
		$logging = (bool) $this->settings->get_option('logging');
		$log_for_all = (bool) $this->settings->get_option('log_for_all');
		if( !$logging )
			return false;

		if( !$log_for_all && is_user_logged_in() )
			return false;

		return true;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_frontend_hooks();
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->BlazeCss;
	}

	/**
	 * @since     1.0.0
	 * @return    string    The Ajax nonce name
	 */
	public function get_ajax_nonce_name() {
		return $this->BlazeCss . '_ajax_nonce';
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    BlazeCss_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function log( $log )
	{
		if (true === WP_DEBUG) {
			if (is_array($log) || is_object($log)) {
				error_log(print_r($log, true));
			} else {
				error_log($log);
			}
		}
	}
}
