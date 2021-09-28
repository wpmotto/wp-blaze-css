<?php 

namespace Motto\BlazeCss;

/**
 * Manages the creation of settings and controls
 * for this plugin. 
 */
class Settings {

    private $plugin;
	private $page;
	private $page_title;
	private $options_name;
	private $options;

    public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
		$this->options_name = $this->plugin->get_plugin_name() .'_plugin_options';
		$this->options = get_option( $this->options_name );
	}

	public function add_page( $slug, $title = null )
	{
		$this->page = implode('-', [
			$this->plugin->get_plugin_name(), $slug
		]);
		$this->page_title = $title ?? $slug;
	}

	public function get_page_name()
	{
		return $this->page;
	}

	public function get_options_name()
	{
		return $this->options_name;
	}

	public function get_option( $slug = null )
	{
		if( is_null($slug) )
			return $this->options;
		
		return $this->options[$slug] ?? null;
	}

    public function get_plugin_options(){
        return $this->options;
    }

	public function registerSettings( $config )
	{
		foreach( $config as $section => $c ) {
			add_settings_section( 
				$section, 
				$c['label'], 
				[ $this, 'renderSection' ], 
				$this->page,
			);

			foreach( $c['fields'] as $field ) {
				add_settings_field( 
					$this->plugin->get_plugin_name() . $field['name'], 
					$field['label'], 
					[ $this, 'renderSetting' ], 
					$this->page, 
					$section,
					[ 'section' => $section, 'field' => $field ]
				);
			}
		}
	}

	public function renderSetting( $args )
	{
		extract($args);
		$id = $this->field_id_from_name($field['name']);
		$name = $this->field_name_from_name($field['name']);
		$slug = strtolower($field['name']);

		require $this->plugin->get_root_path() . 
						"templates/settings/$section/$slug.php";
	}

	public function renderSection( $args )
	{
		extract($args);
		$path = $this->plugin->get_root_path() . "templates/sections/$id.php";

		if( file_exists($path) ) include $path;
	}
    
    public function destroy(){
        delete_option( $this->options_name );
    }

	public function renderPage() {
		do_action(implode('_', ['before', $this->page,'page']));
		?>
		<h2><?php echo $this->page_title ?></h2>
		<form action="options.php" method="post">
			<?php 
				settings_fields( $this->options_name );
				do_settings_sections( $this->page );
			?>
			<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
		</form>
		<?php
		do_action(implode('_', ['after', $this->page,'page']));
	}

	public function field_id_from_name( $name )
	{
		return $this->plugin->get_plugin_name() . '_' . $name;
	}
 	
	public function field_name_from_name( $name )
	{
		return "{$this->options_name}[$name]";
	}   
}