<?php
namespace libraries;

class Model extends \CI_Model {
	
	/**
	 * @const String Replace table name and ignore value
	 */
	const REPLACE_TABLE = "/_Model\z/i";

	/**
	 * 
	 */
	private static $_instance   = null;
	
	/**
	 * 
	 */
	private $_tablename  = '';
	
	/**
	 * 
	 */
	private $CI = null;
	
	/**
	 * 
	 */
	private $_data = null;

	/**
	 * type = ROW
	 * type = ARRAY
	 */
	private $_data_type = '';

	
	public function __construct( $tableName = null )
	{
		$this->CI =  &get_instance();

		if(!$tableName)
		{
			$tableName = basename( str_replace("\\","/", get_class( $this ) ) );
		}

		if(self::REPLACE_TABLE)
		{
			$tableName = strtolower(
				preg_replace(self::REPLACE_TABLE, "", $tableName  )
			);
		}

		$this->_tablename = $tableName;
	}

	/**
	 * 
	 */
	public static function init()
	{

		if(self::$_instance == null)
		{

			$calledClass = get_called_class();
			
			$parentModel = basename( str_replace("\\","/", $calledClass ) );
			
			$instance    = new Model( $parentModel );

			self::$_instance = $instance;

		}

		return self::$_instance;
	}

	/**
	 * 
	 */
	public static function setTable( String $tableName  )
	{
		self::init();
		
		self::$_instance->_tablename = $tableName;
		
		return self::$_instance;
	}

	public static function getTable()
	{
		self::init();

		return self::$_instance->_tablename;
	}

	/**
	 * @return QueryBuilder
	 */
	public static function getQueryBuilder()
	{
		self::init();

		return self::$_instance->CI->db;
	}
	
	/**
	 * @param Array $params
	 * 
	 * @return ModelCore
	 */
	public static function insert( $params )
	{
		self::init();
		
		$query = self::getQueryBuilder();
		
		$query->insert( self::getTable() , $params );
		
		return self::get( $query->insert_id() );
	}

	/**
	 * @param Array|Int $filter
	 * 
	 * @return ModelCore
	 */
	public static function get( $filter = null, $columns = '*' )
	{
		self::init();

		self::$_instance->_data_type = 'ROW';

		$query = self::getQueryBuilder();
		
		$query->select(  $columns )
			->from( self::getTable() );
		
		if(is_numeric($filter))
		{
			$query->where(['id' => $filter]);
		}
		else if( is_array($filter) )
		{
			$query->where($filter);
		}

		self::$_instance->setResult(  $query->get()->row_array() );
		
		return self::$_instance;
	}

	/**
	 * @param Clousure $callBack 
	 * @param String $resultType ('ARRAY' | 'ROW')
	 * @example
	 *  Patient::retrieve( function( $qb ){ return $qb->order_by('id'); })->result();
	 * 
	 * @return Array
	 */
	public static function retrieve( $callBack = null , $resultType = 'ARRAY')
	{
		self::init();

		self::$_instance->_data_type = in_array($resultType,['ROW','ARRAY']) ? $resultType : 'ARRAY';

		$query = self::getQueryBuilder();
		$query->from( self::getTable() );
		if( is_callable($callBack) )
		{
			$queryCallBack = $callBack( $query );
			if(get_class($queryCallBack) === 'CI_DB_pdo_mysql_driver')
			{
				$query = $queryCallBack;
			}
		}
		
		$result = ($resultType === 'ARRAY') ? $query->get()->result_array() : $query->get()->row_array();

		self::$_instance->setResult(  $result );

		return self::$_instance;
	}


	/**
	 * @param Array $filter
	 * 
	 * @return Model
	 */
	public static function getAll( $filter = [], $columns = '*' )
	{
		self::init();

		self::$_instance->_data_type = 'ARRAY';

		$query = self::getQueryBuilder();

		$query->select( $columns )->from( self::getTable() );
		
		if( is_array($filter) )
		{
			$query->where( $filter );
		}
		
		self::$_instance->setResult(  $query->get()->result_array() );

		return self::$_instance;
	}

	/**
	 * @param Array $result
	 * 
	 * @return avoid
	 */
	private static function setResult( $result = null  )
	{
		self::init();
		
		self::$_instance->_data = $result;
	}

	/**
	 * @return Array
	 */
	public static function result()
	{
		self::init();

		return self::$_instance->_data;
	}
	
	/**
	 * $patient = Patient::get(1);
	 * $patient->update([
	 * 	'name' => 'my new name'
	 * ]);
	 * 
	 * @param Array $params
	 * 
	 * @return Integer
	 */
	public function update( Array $params = [] , $id = false )
	{

		$query = $this->getQueryBuilder();
		
		if(!count($params))
		{
			return false;
		}

		$query->set($params);
		
		$this->_updateData( $params );

		$query = $this->_queryWhere( $query, $id );
		
		$query->update( $this->getTable());

		PR($query->last_query());
	}
	
	/**
	 * 
	 */
	public function delete( $id = false )
	{
		$query = $this->getQueryBuilder();
		
		if( $query = $this->_queryWhere( $query, $id ) )
		{
			return $query->delete( $this->getTable() );
		}

		return 0;
	}

	/**
	 * 
	 */
	private function _queryWhere( $query, $id = FALSE )
	{
		$data = $this->result();
		
		if($id)
		{
			$query->where(['id' => $id ]);
		}
		else if(  self::$_instance->_data_type === 'ROW' && isset($data['id']) )
		{
			$query->where(['id' => (int)$data['id']]);
		}
		else if(  self::$_instance->_data_type === 'ARRAY' && count($data)>0 )
		{
			foreach ($data as $value) {
				$query->or_where( ['id' => $value['id']] );
			}
		}
		else
		{
			return null;
		}
		return $query;
	}

	/**
	 * 
	 */
	private function _updateData( $valueData = null )
	{
		$data = $this->result();

		if(  $this->_data_type === 'ROW' )
		{
			$this->_data = array_merge( $data , $valueData );
		}
		else if(  $this->_data_type === 'ARRAY' && count($data)>0 )
		{
			foreach ($data as $key => $value) {
				$this->_data[$key] = array_merge($value, $valueData );
			}
		}
	}

}

