<?php 

namespace Motto\BlazeCss;

class Settings {

    private $plugin;

    public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

    public function show_general_text() {
		echo '<p>Enable/Disable options.</p>';
    }
    
    public function show_generate_csv_text() {
		echo '<p>Generate CSV options.</p>';
	}

	public function show_general_logging() {
		$plugin_options = get_option( $this->plugin->get_plugin_name() . '_plugin_options' );
		$html = '<input type="checkbox" id="'. $this->plugin->get_plugin_name() . '_setting_logging" name="'. $this->plugin->get_plugin_name() . '_plugin_options[logging]" value="1"' . checked( 1, $plugin_options['logging'], false ) . '/>';
		$html .= '<label for="'. $this->plugin->get_plugin_name() . '_setting_logging">Check options to activate logging on frontent page.</label>';
		echo $html;
	}

	public function show_general_clean_datas() {
		$plugin_options = get_option( $this->plugin->get_plugin_name() . '_plugin_options' );
		$html = '<input type="checkbox" id="'. $this->plugin->get_plugin_name() . '_setting_clean_datas" name="'. $this->plugin->get_plugin_name() .'_plugin_options[clean_datas]" value="1"' . checked( 1, $plugin_options['clean_datas'], false ) . '/>';
		$html .= '<label for="'. $this->plugin->get_plugin_name() . '_setting_clean_datas">Check options to delete all plugin datas when desactivating plugin.</label>';
		echo $html;
    }
    
    public function show_gcsv_auto() {
		$plugin_options = get_option( $this->plugin->get_plugin_name() . '_plugin_options' );
		$html = '<input type="checkbox" id="'. $this->plugin->get_plugin_name() . '_setting_gcsv_auto" name="'. $this->plugin->get_plugin_name() . '_plugin_options[gcsv_auto]" value="1"' . checked( 1, $plugin_options['gcsv_auto'], false ) . '/>';
		$html .= '<label for="'. $this->plugin->get_plugin_name() . '_setting_gcsv_auto">Check options to activate logging on frontent page.</label>';
		echo $html;
	}

	public function show_gcsv_path_file() {
		$plugin_options = get_option( $this->plugin->get_plugin_name() . '_plugin_options' );
		$html =  '<input id="'. $this->plugin->get_plugin_name() . '_setting_gcsv_path_file" name="'. $this->plugin->get_plugin_name() .'_plugin_options[gcsv_path_file]" type="text" value="' . esc_attr( $plugin_options['gcsv_path_file'] ) . '" />';
		$html .=  '<p>Example: file.csv or uploads/file.csv</p>';
		echo $html;
	}

	public function validate_plugin_options( $input ) {
		if( !isset($input['logging']) ||  ( isset($input['logging']) && $input['logging'] != 1 ) ){
			$newinput['logging'] = 0;
		}
		else{
			$newinput['logging'] = 1;
		}
		if( !isset($input['clean_datas']) ||  ( isset($input['clean_datas']) && $input['clean_datas'] != 1 ) ){
			$newinput['clean_datas'] = 0;
		}
		else{
			$newinput['clean_datas'] = 1;
        }
        if( !isset($input['gcsv_auto']) ||  ( isset($input['gcsv_auto']) && $input['gcsv_auto'] != 1 ) ){
			$newinput['gcsv_auto'] = 0;
		}
		else{
			$newinput['gcsv_auto'] = 1;
        }
        if( !isset($input['gcsv_path_file']) ||  ( isset($input['gcsv_path_file']) && trim($input['gcsv_path_file']) == '' ) ){
			$newinput['gcsv_path_file'] = '';
		}
		else{
			$newinput['gcsv_path_file'] = $input['gcsv_path_file'];
		}
		return $newinput;
    }


	public function html_settings_page() {
		?>
		<h2>Blaze CSS Settings</h2>
		<form action="options.php" method="post">
			<?php 
			settings_fields( $this->plugin->get_plugin_name() . '_plugin_options' );
            do_settings_sections( $this->plugin->get_plugin_name() . '-seetings' );
            ?>
			<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
		</form>
		<?php
			$plugin_options = $this->get_plugin_options();
            if( !isset($plugin_options['gcsv_auto']) || ( isset($plugin_options['gcsv_auto']) && $plugin_options['gcsv_auto'] !=1 ) ){
				?>
				<div>
					<h3>Manually Generate CSS .CSV File</h3>
					<p>Click on the following button to generate the .csv file with the results.</p>
                	<button id="<?php echo $this->plugin->get_plugin_name() .'_btn_generate_csv'?>" >Generate CSV</button>
				</div>
				<?php
            }

    }

    public function get_plugin_options(){
        return get_option( $this->plugin->get_plugin_name() . '_plugin_options' );
    }
    
    public function destroy(){
        delete_option( $this->plugin->get_plugin_name() .'_plugin_options' );
    }
    
}