<?php

namespace Motto\BlazeCss\Common;

use Motto\BlazeCss\Common\Element;

class Logger {

    protected $elements;
    protected $url;

    public function __construct( Array $postData )
    {
        $this->url = parse_url($postData['url']);
        $this->elements = Element::collect( $this->parseRequest( $postData['log'] ) );
    }

    private function parseRequest( $string )
    {
        return (Array) json_decode(
            html_entity_decode(
                stripslashes(
                    \sanitize_text_field( $string ) 
                )
            )
        );
    }
}