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
        $csv_file_path = $this->plugin->settings->get_option('gcsv_path_file');
        $path = WP_CONTENT_DIR . '/' . $csv_file_path;
        $elements = (new Element)->select('DISTINCT el_class')
                                ->where('el_class IS NOT NULL')
                                ->get();

        $classes = implode(
            "\n", array_column($elements, 'el_class')
        );
        
        return file_put_contents($path, $classes);
    }

    public static function writeCss( $content )
    {
		$path = wp_get_upload_dir()['basedir'] . '/blaze.css';
        file_put_contents($path, $content);
    }

}