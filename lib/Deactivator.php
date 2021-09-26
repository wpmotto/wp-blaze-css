<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/wpmotto/wp-blaze-css
 * @since      1.0.0
 *
 * @package    BlazeCss
 * @subpackage BlazeCss/includes
 */

namespace Motto\BlazeCss;

use Motto\BlazeCss\Plugin;
use Motto\BlazeCss\Common\Schema;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    BlazeCss
 * @subpackage BlazeCss/includes
 * @author     Greg Hunt <plugins@wpmotto.com>
 */
class Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$plugin = new Plugin();
		if( $plugin->settings->get_option('clean_data') == 1 ) {
			$schema = new Schema($plugin);
			$schema->destroy();
			$plugin->settings->destroy();
		}
	}

}
