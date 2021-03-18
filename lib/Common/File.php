<?php

namespace Motto\BlazeCss\Common;

use Motto\BlazeCss\Models\Element;

class File {

    public static function write()
    {
		$path = wp_get_upload_dir()['basedir'] . '/blaze.csv';
        $elements = (new Element)->select('DISTINCT el_class')->where('el_class IS NOT NULL')->get();
        $classes = implode("\n", array_column($elements, 'el_class'));
        file_put_contents($path, $classes);
    }

}