<?php

namespace Motto\BlazeCss\Common;

use Motto\BlazeCss\Models\Element;

class File {

    protected $plugin;

    public function __construct( \Motto\BlazeCss\Plugin $plugin ) {
		$this->plugin = $plugin;
	}

    public function write() 
    {   
        $plugin_options = $this->plugin->settings->get_plugin_options();
        if( isset($plugin_options['gcsv_path_file']) && !empty($plugin_options['gcsv_path_file']) ){
            $path = WP_CONTENT_DIR . '/' . $plugin_options['gcsv_path_file'];
        }else{
            $path = wp_get_upload_dir()['basedir'] . '/blaze.csv';
        }
		
        $elements = (new Element)->select('DISTINCT el_class')->where('el_class IS NOT NULL')->get();
        $classes = implode("\n", array_column($elements, 'el_class'));
        file_put_contents($path, $classes);
    }

}