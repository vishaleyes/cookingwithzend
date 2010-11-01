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
	 * Returns the record you asked for by name
	 * @param string $string
	 * @return Zend_Db_Table_Row
	 */
	
	public function getByName( $string )
	{
		$select = $this->select()
			->from($this->_name)
			->where('name = ?', $string);
		return $this->fetchRow($select);
	}
	
	/**
	 * Override the insert to try and not throw an exception back if it fails, also re-using
	 * getByName code
	 * @param $data array associative array of vars to go in the db
	 * @return $row Zend_Db_Table_Row the row of the Ingredient table
	 */

	function insert( array $data )
	{
		try {
			parent::insert( $data );
		} catch (Exception $e) {
			// Doesnt matter if we cannot insert the ingredient
		}

		return $this->getByName( $data['name'] );
	}
}
