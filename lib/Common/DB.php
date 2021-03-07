<?php
/**
 * https://codex.wordpress.org/Creating_Tables_with_Plugins
 */
namespace Motto\BlazeCss\Common;

use Motto\BlazeCss\Plugin;

class DB {

    protected $table_log;
    protected $wpdb;
    protected $plugin;
    protected $version = '1.0';

    public function __construct( Plugin $plugin )
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->plugin = $plugin ?? (new \Motto\BlazeCss\Plugin());
        $this->table_log = $wpdb->prefix . $this->plugin->get_plugin_name() . '_log';
        $this->version_key = $this->plugin->get_plugin_name() . "_db_version";
    }

    public function init()
    {
        $sql = "CREATE TABLE {$this->table_log} (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          logged_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
          name tinytext NOT NULL,
          `log` text NOT NULL,
          url varchar(55) DEFAULT '' NOT NULL,
          PRIMARY KEY  (id)
        ) {$this->wpdb->get_charset_collate()};";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        add_option( $this->version_key, $this->version );
    }

    public function destroy()
    {
        $this->wpdb->query( "DROP TABLE IF EXISTS {$this->table_log}" );
        delete_option( $this->version_key );
    }

    public function add()
    {
        $this->wpdb->insert( 
            $this->table_log, 
            array( 
                'logged_at' => current_time( 'mysql' ), 
                'name' => 'Mr. WordPress', 
                'text' => 'Congratulations, you just completed the installation!', 
            ) 
        );        
    }

    public function upgrade()
    {
        // global $wpdb;
        // $installed_ver = get_option( "jal_db_version" );
        
        // if ( $installed_ver != $jal_db_version ) {
        
        //     $table_name = $wpdb->prefix . 'liveshoutbox';
        
        //     $sql = "CREATE TABLE $table_name (
        //         id mediumint(9) NOT NULL AUTO_INCREMENT,
        //         time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        //         name tinytext NOT NULL,
        //         text text NOT NULL,
        //         url varchar(100) DEFAULT '' NOT NULL,
        //         PRIMARY KEY  (id)
        //     );";
        
        //     require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        //     dbDelta( $sql );
        
        //     update_option( "jal_db_version", $jal_db_version );
        // }        
    }
}