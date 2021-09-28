<?php

namespace Motto\BlazeCss\Common;

use Motto\BlazeCss\Plugin;
use Motto\BlazeCss\Models\Log;
use Motto\BlazeCss\Models\Element;

class Logger {

    protected $plugin;
    protected $url;
    protected $viewport;
    protected $pageQuery;
    protected $log = null;

    public function __construct( Array $postData, Plugin $plugin )
    {
        $this->plugin = $plugin;
        $this->url = (object) parse_url($postData['url']);
        $this->viewport = [
            'width' => $postData['width'],
            'height' => $postData['height'],
        ];
        if( !isset($this->url->query) )
            $this->url->query = null;

        $this->pageQuery = $postData['log'];
    }

    public function getLog()
    {
        return $this->log;
    }

    public function getLogId()
    {
        return $this->log->getId();
    }

    public function getPageQuery()
    {
        return (Array) json_decode(
            html_entity_decode(
                stripslashes(
                    \sanitize_text_field( $this->pageQuery ) 
                )
            )
        );
    }

    public function save()
    {
        if( $this->cacheMiss() ) {
            $this->create();

            /**
             * Log elements for each user-agent
             */
            Element::fromLogger( $this );

            // change for event driven do_action
            if( (bool) $this->plugin->settings->get_option('gcsv_auto') ) {
                $file = new File($this->plugin);
                $file->write();
            }            
        }
    }

    public function cacheMiss()
    {
        if( $found = $this->findByHash() )
            $this->log = $found;

        return is_null($this->log);
    }

    public function create()
    {
        $logs = (new Log)->select('*')->where("
            host = %s
            AND path = %s
            AND query = %s
        ", [
            $this->url->host, 
            $this->url->path, 
            $this->url->query,
        ])->get();
        
        if( !empty($logs) ) {
            $this->log = (new Log)->find('id', $logs[0]->id);

            $this->log->update([
                'logged_at' => date('Y-m-d H:i:s'),
                'hash' => $this->hash(),
            ]);
        } else {
            $current_theme = wp_get_theme();

            $this->log = new Log;
            $this->log->insert([
                'host' => $this->url->host,
                'path' => $this->url->path,
                'query' => $this->url->query,
                'hash' => $this->hash(),
                'theme' => $current_theme->get( 'Name' ),
                'width' => $this->viewport['width'],
                'height' => $this->viewport['height'],
            ]);
        }

        return $this;
    }

    public function findByHash()
    {
        $log = (new Log)->find('hash', $this->hash());
        if( $log->getId() )
            return $log;

        return false;
    }

    public function hash()
    {
        return md5(
            $this->url->host . 
            $this->url->path . 
            $this->url->query . 
            json_encode($this->getPageQueryWithoutCoodinates())
        );
    }

    public function getClasses()
    {
        return array_map(function($el) {
            unset($el->coordinates);
            return $el;
        }, $this->getPageQuery());
    }

    public function getPageQueryWithoutCoodinates()
    {
        return array_map(function($el) {
            unset($el->coordinates);
            return $el;
        }, $this->getPageQuery());
    }
}