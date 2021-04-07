<?php 

namespace Motto\BlazeCss\Common;

abstract class Model {

    protected $id;
    protected $query;
    protected $attributes = [];
    protected $table;
    protected $db;

    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTable()
    {
        return $this->db->prefix . $this->table;
    }

    public function update( $data, $format = [] )
    {
        if( empty($format) )
            $format = array_fill(0, count($data), '%s');
        
        $where = ['id' => $this->getId()];

        // dd(
        //     [
        //         $this->getTable(), $data, $where, $format
        //     ]
        // );
        $this->db->update( 
            $this->getTable(), $data, $where, $format
        );
    }

    public function insert( $data, $format = [] )
    {
        if( empty($format) )
            $format = array_fill(0, count($data), '%s');
        
        $this->db->insert( 
            $this->getTable(), $data, $format
        );

        $this->id = $this->db->insert_id;
        $this->find('id', $this->id);
    }

    public function delete( $data, $format = [] )
    {
        if( empty($format) )
            $format = array_fill(0, count($data), '%s');
        
        $this->db->delete( 
            $this->getTable(), $data, $format
        );
    }

    public function select( $select )
    {
        $this->query['select'] = $select;
        return $this;
    }

    public function where( $where )
    {
        $this->query['where'] = $where;
        return $this;
    }

    public function get()
    {
        $sql = <<<SQL
SELECT {$this->query['select']} 
FROM {$this->getTable()}            
WHERE {$this->query['where']} 
SQL;
        return $this->db->get_results( $this->db->prepare( $sql ) );        
    }

    public function find( $prop, $value )
    {
        $sql = <<<SQL
SELECT * FROM {$this->getTable()}
WHERE $prop = %s
SQL;

        $row = $this->db->get_row( 
            $this->db->prepare( $sql, $value ), OBJECT
        );

        $this->id = $row->id;
        $this->attributes = $row;
        return $this;
    }
}