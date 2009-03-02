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