<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    BlazeCss
 * @subpackage BlazeCss/Frontend
 */

namespace Motto\BlazeCss;

use Motto\BlazeCss\Common\Logger;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/Frontend
 * @author     Your Name <email@example.com>
 */
class Frontend {

	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin $plugin This plugin's instance.
	 */
	private $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * 
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		\wp_enqueue_script(
			$this->plugin->get_plugin_name(),
			\plugin_dir_url( dirname( __FILE__ ) ) . 'dist/scripts/blaze.js',
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

    public function save_page_elements() {
        // check nonce
        \check_ajax_referer( 
			$this->plugin->get_ajax_nonce_name(), 
			'_ajax_nonce' 
		);

        $logger = new Logger($_POST);
        print_r(
			$logger
		);
        die(); 
    }
}
