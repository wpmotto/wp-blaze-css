<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       https://github.com/wpmotto/wp-blaze-css
 * @since      1.0.0
 *
 * @package    BlazeCss
 * @subpackage BlazeCss/admin
 */

namespace Motto\BlazeCss;

use Motto\BlazeCss\Common\File;

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    BlazeCss
 * @subpackage BlazeCss/admin
 * @author     Greg Hunt <plugins@wpmotto.com>
 */
class Admin {

	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin $plugin This plugin's instance.
	 */
	private $plugin;
	// private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * 
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
		// $this->settings = new Settings($plugin);
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in BlazeCss_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The BlazeCss_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		\wp_enqueue_style(
			$this->plugin->get_plugin_name(),
			\plugin_dir_url( dirname( __FILE__ ) ) . 'dist/styles/blaze-admin.css',
			array(),
			$this->plugin->get_version(),
			'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in BlazeCss_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The BlazeCss_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		\wp_enqueue_script(
			$this->plugin->get_plugin_name(),
			\plugin_dir_url( dirname( __FILE__ ) ) . 'dist/scripts/blaze-admin.js',
			array( 'jquery' ),
			$this->plugin->get_version(),
			false );

		\wp_localize_script( 
			$this->plugin->get_plugin_name(), 
			$this->plugin->get_plugin_name() . '_ajax_object', [
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 
				$this->plugin->get_ajax_nonce_name() 
			)
		]);

	}

	public function add_settings_page() {
		add_options_page( 
			__( 'Blaze CSS Settings', 'blazecss' ),
			__( 'Blaze', 'blazecss' ),
			'manage_options',
			$this->plugin->get_plugin_name().'-seetings',
			[$this->plugin->settings, 'html_settings_page']
		);
	}

	public function init_settings() {
        add_option( $this->plugin->get_plugin_name() .'_plugin_options', ['logging' => 0, 'clean_datas' => 0, 'gcsv_auto' => 0, 'gcsv_path_file' => ''] );

		register_setting( $this->plugin->get_plugin_name() .'_plugin_options', $this->plugin->get_plugin_name().'_plugin_options', [$this->plugin->settings, 'validate_plugin_options']);

		add_settings_section( 'general_settings', 'General', [$this->plugin->settings, 'show_general_text'], $this->plugin->get_plugin_name().'-seetings' );

		add_settings_field( $this->plugin->get_plugin_name() .'_setting_logging', 'Activate Logging', [$this->plugin->settings, 'show_general_logging'], $this->plugin->get_plugin_name().'-seetings', 'general_settings');
		add_settings_field( $this->plugin->get_plugin_name() .'_setting_clean_datas', 'Clean Datas', [$this->plugin->settings, 'show_general_clean_datas'], $this->plugin->get_plugin_name().'-seetings', 'general_settings' );

		add_settings_section( 'generate_csv_settings', 'Generation results CSV Options', [$this->plugin->settings, 'show_generate_csv_text'], $this->plugin->get_plugin_name().'-seetings' );

		add_settings_field( $this->plugin->get_plugin_name() .'_setting_gcsv_auto', 'Generate Automaticaly', [$this->plugin->settings, 'show_gcsv_auto'], $this->plugin->get_plugin_name().'-seetings', 'generate_csv_settings');
		add_settings_field( $this->plugin->get_plugin_name() .'_setting_gcsv_path_file', 'Path CSV File', [$this->plugin->settings, 'show_gcsv_path_file'], $this->plugin->get_plugin_name().'-seetings', 'generate_csv_settings' );
	}

	public function generate_csv()
	{
		// check nonce
        \check_ajax_referer( 
			$this->plugin->get_ajax_nonce_name(), 
			'_ajax_nonce' 
		);

        $file = new File($this->plugin);
		$file->write();
        die(); 
	}
}
