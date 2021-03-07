<?php

namespace Motto\BlazeCss\Common;

class Element {

    protected $id;
    protected $tag;
    protected $class;
    protected $coordinates;

    public function __construct( Object $data )
    {
        $this->id = $data->id;
        $this->tag = $data->tag;
        $this->class = $data->class;
        $this->coordinates = $data->coordinates;
    }

    public static function collect( Array $collection )
    {
        $elements = [];
        foreach( $collection as $data )
            $elements[] = new self($data);

        return $elements;
    }
}