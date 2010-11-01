<?php

/**
* 
*/
abstract class Models_GenericModel
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

	const PREFIX = 'Models_';

	public function __construct()
	{
		$this->table = $this->__getTable();
		$this->db = $this->table->getDefaultAdapter();
		$this->log = Zend_Registry::get('log');
	}

	/**
	 * Loads the relevant form in the forms directory
	 * 
	 * @param string $form Name of the Form
	 * @return Zend_Form
	 */

	public function getForm($form)
	{
		$formClass = 'Forms_'.$form;
		Zend_Loader::loadClass( 'Forms_' . $form );
		return new $formClass();
	}

	/**
	 * Generic function to aquire data by any of the fields in the table
	 * 
	 * @param string $field The field you want to search by
	 * @param mixed $value The value you want the field to be
	 * @return $rows Zend_Db_Table_Rowset
	 */

	public function getByField( $field, $value )
	{
		$select = $this->table->select()->where( $field . ' = ?', $value );
		$rowSet = $this->table->fetchAll( $select );
		if ($rowSet)
			return $rowSet->toArray();
		return false;
	}
	
	/**
	 * Generic function to aquire data by any of the fields in the table, 
	 * returns a single row
	 *
	 * @param string $field
	 * @param mixed $value
	 * @return array
	 */
	
	public function getSingleByField( $field, $value )
	{
		$select = $this->table->select()->where( $field . ' = ?', $value );

		if ( !$row = $this->table->fetchRow( $select ))
			return false;
	
		$this->_dataMerge($row);

		return $row;
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
	 * Derive the table name from the current Model
	 * 
	 * @return Zend_Db_Table_Abstract
	 */

	private function __getTable()
	{
		$tableName = substr( get_class($this), strlen(self::PREFIX) );
		$tableClass = self::PREFIX . 'DbTable_' . $tableName;
		Zend_Loader::loadClass($tableClass);
		$table = new $tableClass();
		return $table;
	}
	
	/**
	 * Runs a fetch all on the table and returns an array
	 *
	 * @param Zend_Db_Select|string $select
	 * @return array
	 */
	public function fetchAll($select)
	{
		$results = $this->table->fetchAll($select);
		if ($results)
			return $results->toArray();
	}
	
	/**
	 * Find the record we request in the current table
	 * 
	 * @param int $id
	 * @todo This breaks the extended functionality of find but is that a bad thing?
	 */

	public function fetchSingleByPrimary($id)
	{
		// Fetch the recipe being requested
		$rowSet = $this->table->find( $id );
		
		// Couldnt find it?  Oh dear we best throw an error
		// @todo Move this test to somwhere else when I figure out the best place for it
		if (!$rowSet)
			return false;
		
		$row = $rowSet->current();
		return $row;
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
		if ($results)
			return $results->toArray();
		return array();
	}

	/**
	 * Increments a field (used for db increments)
	 * @param string $field
	 */

	public function incrementField( $field )
	{
		$this->_data[$field]++;
	}

	/**
	 * Returns everything in _data to an array
	 * @return array
	 */

	public function toArray()
	{
		return $this->_data;
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
	
	/**
	 * Return the relevant attribute
	 * @param string $key
	 */
	
	public function __get($key)
	{
		if(array_key_exists($key, $this->_data))
			return $this->_data[$key];

		return false;
	}
}


?>
