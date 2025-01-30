<?php
class APP_Model extends CI_Model {
    
    /**
    * connection
    */
    public $db     = null;

    /**
    * data to save or update
    */
    protected $data   = array();

    /**
    * table default is name file
    */
    protected $table   = null;      
    
    /**
    * id is required all tables
    */  
    protected $primarykey   = 'id';      
    
    /**
     * default 
     */
    protected $list_fields = [];

    /**
     * you have active fields [ create_at, update_at ]
     * @var boolean
     */
    protected $timestamp = FALSE;

    function __construct(){
        $ci       =& get_instance();

        $this->db = $ci->db;     
        
        $this->table = (is_null($this->table)) ? 
            strtolower(preg_replace("/_Model\z/i", "", get_class($this) )) : 
            $this->table;
            
        //$this->primarykey = (is_null($this->primarykey)) ? 'id' : $this->primarykey;
        
        //$this->list_fields = $this->db->list_fields($this->table);
    }
        
    function __set( $name, $value ){         
        $this->data[$name] = $value;
    }
    
    function __get($name) {  
        return isset($this->data[$name]) ? $this->data[$name] : '';
    }
    
    public function get( $primaryKey ){
        
        $this->db
            ->select( $this->list_fields )
            ->where( $this->primarykey , $primaryKey ); 
        return $this->db->get( $this->table )->row();
    }
    
    public function getRowBy( $filters ){
        $this->db
            ->select( $this->list_fields )
            ->where( $filters ); 
        return $this->db->get( $this->table )->row();
    }

    public function getResultsBy( $filters ){
        $this->db
            ->select( $this->list_fields )
            ->where( $filters );
        return $this->db->get( $this->table )->result();  
    }
        
    public function getColumns()
    {
        $fields = $this->db->list_fields( $this->table );
        $data_tmp = new StdClass;
        foreach ($fields as $key => $value) {
            $data_tmp->$value = '';
        }
        return $data_tmp;
    }

    public function update( $where )
    {
        if( $this->timestamp )
        {
            $this->data['update_at'] = date('Y-m-d H:i:s');
        }
        $this->db->where( $where )
            ->update($this->table, $this->data );
    }

    public function save( $id = 0)
    {

        if( $id > 0)
        {
            if( $this->timestamp )
            {
                $this->data['update_at'] = date('Y-m-d H:i:s');
            }

            $this->db->where($this->primarykey , $id )
                ->update($this->table, $this->data );
            return $id;
        }
        else
        {   
            if( $this->timestamp )
            {
                $this->data['create_at'] = date('Y-m-d H:i:s');
            }
            
            $this->db->insert($this->table, $this->data );
            return $this->db->insert_id();
        }
    }
    
    public function existID( $id_value, $id_name = 'id')
    {
        $this->db->select( $id_name );
        $this->db->where( $id_name, $id_value );
        
        if( $this->db->get( $this->table )->row() )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getAll( $filters = null ){
        $this->db->select( $this->list_fields );
        if( !is_null($filters) ){
            $this->db->where( $filters );
        }
        return $this->db->get( $this->table )->result();  
    }

    public function delete( $id = ''){
        $id = (isset($this->data[$this->primarykey])) ? $this->data[$this->primarykey]  : $id;
        return $this->db->delete( $this->table , [$this->primarykey => $id ] );
    }
    
    public function deleteBy( $where ){
        $this->db->where( $where ); 
        return $this->db->delete( $this->table );
    }

    public function resetData(){
        $this->data = array();
    }
        
    public function setData( $data ){
        $data = (is_object($data)) ? (Array) $data : $data;
        return $this->data = $data;
    }
    
    public function getData()
    {   
        return $this->data;
    }

    public function tableName()
    {
        return $this->table;
    }
    
}