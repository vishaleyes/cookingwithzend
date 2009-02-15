<?php

/**
* 
*/
class Models_DbTable_Ingredient extends Zend_Db_Table_Abstract
{
	protected $_name = "ingredients";
	protected $_primary = "id";	   

	# Primary does Auto Inc
	protected $_sequence = true;
	
	/**
	 * Fetch the row in the database that fits this particular name
	 * @param $name string The name of the ingredient you want to fetch
	 * @return $row Zend_Db_Table_Row the row of the Ingredient table
	 */

	function getByName( $name )
	{
		$select = $this->select()->where( 'name = ?', $name );
		return $this->fetchRow( $select );
	}

	/**
	 * Override the insert to try and not throw an exception back if it fails, also re-using
	 * getByName code
	 * @param $data array associative array of vars to go in the db
	 * @return $row Zend_Db_Table_Row the row of the Ingredient table
	 */

	function insert( $data )
	{
		try {
			parent::insert( $data );
		} catch (Exception $e) {
			// Doesnt matter if we cannot insert the ingredient
		}

		return $this->getByName( $data['name'] );
	}
}
