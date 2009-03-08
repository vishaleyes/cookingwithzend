<?php

/**
* 
*/
abstract class GenericModel
{
	/**
	 * @var $table
	 */
	public $table;

	const PREFIX = 'Models_';

	public function __construct()
	{
		$this->table = $this->__getTable();
	}

	/**
	 * Loads the relevant form in the forms directory
	 * @param string $form Name of the Form
	 * @return Zend_Form
	 */

	public function getForm($form)
	{
		$form = 'Forms_'.$form;
		Zend_Loader::loadClass( $form );
		return new $form();
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
		$user = $this->table->fetchAll( $select );
		return $user;
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
}


?>