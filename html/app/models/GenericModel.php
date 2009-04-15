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
	 * @param $field string The field you want to search by
	 * @param $value mixed The value you want the field to be
	 * @return $rows Zend_Db_Table_Rowset
	 */

	public function getByField( $field, $value )
	{
		$select = $this->table->select()->where( $field . ' = ?', $value );
		$rowSet = $this->table->fetchAll( $select );
		return $rowSet;
	}

	/**
	 * Derive the table name from the current Model
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
	 * Find the record we request in the current table
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
}


?>