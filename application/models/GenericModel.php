<?php

/**
* 
*/
abstract class Recipe_Model_GenericModel implements ArrayAccess
{
	/**
	 * @var $table
	 */
	public $table;
	
	/**
	 * @var $db
	 */
	public $db;
	
	/**
	 * @var log
	 */
	public $log;

	protected $_data;

	const PREFIX = 'Recipe_Model_';

	public function __construct()
	{
		$this->table = $this->_getTable();
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$this->log = Zend_Registry::get('log');
	}

	/**
	 * ArrayAccess functions
	 */

	/**
	 *
	 * @param string $offset
	 * @param mixed $value
	 */

	public function offsetSet($offset, $value)
	{
		if (is_null($offset))
			$this->_data[] = $value;
        else
			$this->_data[$offset] = $value;
    }

	/**
	 *
	 * @param string $offset
	 * @return bool
	 */

	public function offsetExists($offset)
	{
		return isset($this->_data[$offset]);
	}

	/**
	 *
	 * @param string $offset
	 */

	public function offsetUnset($offset)
	{
		unset($this->_data[$offset]);
    }

	/**
	 *
	 * @param string $offset
	 * @return mixed
	 */

	public function offsetGet($offset)
	{
		return isset($this->_data[$offset]) ? $this->_data[$offset] : null;
    }

	/**
	 * Derive the table name from the current Model
	 * 
	 * @return Zend_Db_Table_Abstract
	 */

	protected function _getTable()
	{
		$tableName = substr( get_class($this), strlen(self::PREFIX) );
		$tableClass = self::PREFIX . 'DbTable_' . $tableName;
		$table = new $tableClass();
		return $table;
	}

	/**
	 * Generic function to aquire data by any of the fields in the table
	 * 
	 * @param string $field The field you want to search by
	 * @param mixed $value The value you want the field to be
	 * @return $rows Zend_Db_Table_Rowset
	 */

	public function fetchByField( $field, $value )
	{
		$select = $this->table->select()->where( $field . ' = ?', $value );

		$rowSet = $this->table->fetchAll( $select );

		return $rowSet->toArray();
	}
	
	/**
	 * Generic function to aquire data by any of the fields in the table, 
	 * returns a single row
	 *
	 * @param string $field
	 * @param mixed $value
	 * @return array
	 */
	
	public function fetchSingleByField( $field, $value )
	{
		$select = $this->table->select()->where( $field . ' = ?', $value );
		
		if (! $row = $this->table->fetchRow( $select ))
			return false;
	
		$this->_dataMerge($row);

		return $row->toArray();
	}

	/**
	 * Takes the data from the db row and stores it in the model
	 *
	 * @param array|Zend_Db_Table_Row $row
	 */

	protected function _dataMerge( $row )
	{
		if ( $row instanceof Zend_Db_Table_Row )
			$row = $row->toArray();
		
		$this->_data = array_merge($this->_data, $row);
		
		if (array_key_exists('user_id', $this->_data))
			$this->ownerUserId = $this->_data['user_id'];
	}
	
	/**
	 * Function to return an array of values from the DB to be used in multiSelect
	 *  
	 * @param string $key
	 * @param string $value
	 * @return array
	 */
	
	public function fetchForMultiSelect($key, $value)
	{
		$select = $this->table->select()
			->from($this->table, array('key' => $key, 'value' => $value));
		$results = $this->table->fetchAll($select);
	
		return $results->toArray();
	}

	/* MAGIC METHODS */
	
	/**
	 * Magic method sleep, used to store minimal info in the DB
	 * @return array
	 */
	
	public function __sleep()
	{
		return array('_data');
	}
}


?>
