<?php

/**
* 
*/
abstract class GenericModel
{
	public $table;
	
	const PREFIX = 'Models_';
	
	public function __construct()
	{
		$this->table = $this->__getTable();
	}
	
	public function getForm($form)
	{
		$form = 'Forms_'.$form;
		Zend_Loader::loadClass( $form );
		return new $form();
	}

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