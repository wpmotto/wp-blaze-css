<?php

namespace Motto\BlazeCss\Models;

use Motto\BlazeCss\Common\Model;
use Motto\BlazeCss\Common\Logger;

class Element extends Model {
    /**
     * Name for table without prefix
     *
     * @var string
     */
    protected $table = 'blaze_elements';

    public static function fromLogger( Logger $logger )
    {
        /**
         * TODO: only add if body width and height are different
         */
        (new self)->delete(['log_id' => $logger->getLogId()]);
        foreach( $logger->getPageQuery() as $data )
            self::createFromPageElement( $data, $logger->getLogId() );
    }

    public static function createFromPageElement( $data, $logId )
    {
        $classes = (array) $data->class;

        (new self)->insert([
            'log_id' => $logId,
            'el_id' => $data->id ?? "#" . $data->id,
            'el_tag' => strtolower($data->tag),
            'el_class' => (!empty($classes) ? '.' . implode('.', $classes) : null),
            'height' => $data->coordinates->height,
            'left' => $data->coordinates->left,
            'right' => $data->coordinates->right,
            'top' => $data->coordinates->top,
            'width' => $data->coordinates->width,
            'x' => $data->coordinates->x,
            'y' => $data->coordinates->y,
        ], ['%d', '%s','%s','%s','%f','%f','%f','%f','%f','%f']);
    }

    public static function collect( Array $collection )
    {
        $elements = [];
        foreach( $collection as $data )
            $elements[] = new self($data);

        return $elements;
    }    

}