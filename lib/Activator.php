<?php

/**
 * Fired during plugin activation
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
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    BlazeCss
 * @subpackage BlazeCss/includes
 * @author     Greg Hunt <plugins@wpmotto.com>
 */
class Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$schema = new Schema((new Plugin));
		$schema->init();
	}

}
