<?php
/**
 * https://codex.wordpress.org/Creating_Tables_with_Plugins
 */
namespace Motto\BlazeCss\Common;

use Motto\BlazeCss\Plugin;

class Schema {

    protected $tables = [
        'elements',
        'logs', 
    ];
    protected $db;
    protected $plugin;
    protected $version = '1.0';

    public function __construct( Plugin $plugin )
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->plugin = $plugin ?? (new \Motto\BlazeCss\Plugin());
        $this->version_key = $this->plugin->get_plugin_name() . "_db_version";
    }

    public function getTableName( $table )
    {
        return $this->db->prefix . $this->plugin->get_plugin_name() . '_' . $table;
    }

    private function logs_schema()
    {
        return <<<SQL
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `logged_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `host` varchar(55) NOT NULL,
        `path` varchar(55) NOT NULL,
        `query` varchar(55),
        `hash` CHAR(32) NOT NULL,
        `theme` varchar(55) NOT NULL,
        `width` mediumint(9) UNSIGNED NOT NULL,
        `height` mediumint(9) UNSIGNED NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY (`host`, `path`, `hash`)
        SQL;
    }

    private function elements_schema()
    {
        return <<<SQL
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `log_id` mediumint(9) NOT NULL,
        `el_tag` tinytext NOT NULL,
        `el_id` VARCHAR(255),
        `el_class` VARCHAR(510),
        `user_agent` VARCHAR(255),
        `height` FLOAT,
        `left` FLOAT,
        `right` FLOAT,
        `top` FLOAT,
        `width` FLOAT,
        `x` FLOAT,
        `y` FLOAT,
        PRIMARY KEY  (`id`)
        SQL;
    }

    public function init()
    {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        foreach( $this->tables as $table ) {
            $schema = $this->{$table . '_schema'}();
            $sql = "CREATE TABLE IF NOT EXISTS {$this->getTableName($table)} (
                $schema
            ) {$this->db->get_charset_collate()}";
            dbDelta( $sql );
        }

        add_option( $this->version_key, $this->version );
    }

    public function destroy()
    {
        $this->db->query("DROP TABLE IF EXISTS {$this->getTableName('logs')}");
        $this->db->query( "DROP TABLE IF EXISTS {$this->getTableName('elements')}" );
        delete_option( $this->version_key );
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