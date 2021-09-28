<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       https://github.com/wpmotto/wp-blaze-css
 * @since      1.1.2
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

	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * 
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin ) {		
		$this->plugin = $plugin;
		$this->settings = new Settings( $plugin );
		$this->settings->add_page('settings', 'Blaze Settings');
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

		$admin_js =  'dist/scripts/blaze-admin.js';

		\wp_enqueue_script(
			$this->plugin->get_plugin_name(),
			\plugin_dir_url( dirname( __FILE__ ) ) . $admin_js,
			array( 'jquery' ),
			filectime($this->plugin->get_root_path() . $admin_js),
			false 
		);

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
			$this->settings->get_page_name(),
			[ $this->settings, 'renderPage' ]
		);
	}

	private function getSettingsConfig() {
		return [
			'general_settings' => [
				'label' => 'General',
				'fields' => [
					[
						'name' => 'logging',
						'label' => 'Activate Logging',
					],
					[
						'name' => 'log_for_all',
						'label' => 'Log for Logged in Users',
					],
					[
						'name' => 'clean_data',
						'label' => 'Cleanup',
					],
				],
			],
			'generate_csv_settings' => [
				'label' => 'Generation results CSV Options',
				'fields' => [
					[
						'name' => 'gcsv_auto',
						'label' => 'Generate Automaticaly',
					],
					[
						'name' => 'gcsv_path_file',
						'label' => 'Path CSV File',
					],
				],
			]
		];
	}

	public function init_settings() {
		$this->add_generate_csv_after_form();

        add_option( 
			$this->settings->get_options_name(), [
				'logging' => 0, 
				'log_for_all' => 0, 
				'clean_data' => 0, 
				'gcsv_auto' => 0, 
				'gcsv_path_file' => 'uploads/blaze.csv'
			] 
		);

		register_setting( 
			$this->settings->get_options_name(), 
			$this->settings->get_options_name(),
			[ $this, 'validate' ]
		);

		$this->settings->registerSettings( $this->getSettingsConfig() );
	}

	public function generate_csv()
	{
		// check nonce
        \check_ajax_referer( 
			$this->plugin->get_ajax_nonce_name(), 
			'_ajax_nonce' 
		);
		
        $file = new File($this->plugin);
		$result = $file->write();
		echo json_encode((bool) $result); die(); 
	}

	private function add_generate_csv_after_form()
	{
		add_action('after_blaze-settings_page', function() {
			if( $this->settings->get_option('gcsv_auto') == 1 )
				return;
		?>
		<div>
			<h3>Manually Generate CSS .CSV File</h3>
			<p>Click on the following button to generate the .csv file with the results.</p>
			<button id="<?php echo $this->settings->field_id_from_name('btn_generate_csv') ?>">
				Generate CSV
			</button>
		</div>	
		<?php		
		});
	}

	public function validate( $input ) {
		$input['logging'] = boolval($input['logging'] ?? false);
		$input['clean_data'] = boolval($input['clean_data'] ?? false);
		$input['gcsv_auto'] = boolval($input['gcsv_auto'] ?? false);
		$input['gcsv_path_file'] = $this->sanitizeCsvPath($input['gcsv_path_file']);
		return $input;
    }

	private function sanitizeCsvPath( $input )
	{
		$path = trim(strval($input ?? ''));
		$dir = dirname(WP_CONTENT_DIR . "/$path");
		if( $dir && file_exists($dir) ) {
			return $path;
		} else {
			add_settings_error( 
				$this->settings->field_name_from_name('gcsv_path_file'), 'gcsv_path_file', 
				"This path does not exist." 
			);
			return $this->settings->get_option('gcsv_path_file');
		}
	}
}
