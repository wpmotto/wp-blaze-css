<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://motto.ca
 * @since             1.1.3
 * @package           Blaze_CSS
 *
 * @wordpress-plugin
 * Plugin Name:       Blaze CSS
 * Plugin URI:        https://github.com/wpmotto/wp-blaze-css
 * Description:       WordPress plugin to render your CSS as fast as possible. Crush your lighthouse scores by simply activating the plugin.
 * Version:           1.1.3
 * Author:            Motto
 * Author URI:        https://motto.ca
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-blaze-css
 * Domain Path:       /languages
 */

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in lib/Activator.php
 */
\register_activation_hook( __FILE__, '\Motto\BlazeCss\Activator::activate' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in lib/Deactivator.php
 */
\register_deactivation_hook( __FILE__, '\Motto\BlazeCss\Deactivator::deactivate' );

if (!function_exists('write_log')) {
    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}


/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
\add_action( 'plugins_loaded', function () {
    $plugin = new \Motto\BlazeCss\Plugin();
    $plugin->run();
} );
