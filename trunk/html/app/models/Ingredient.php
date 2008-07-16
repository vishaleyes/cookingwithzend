<?php

class Ingredient extends Zend_Db_Table_Abstract {
	
	protected $_name = "ingredients";
	protected $_primary = "id";	   

	# Primary does Auto Inc
	protected $_sequence = true;
	
	// Form elements for add/edit
	// field name => array(type, required, validatorArray, filtersArray);
	public $_form_fields_config = array(
		array( 'text', 'ingredient_name', array(
			'id' => 'ingredient-name',
			'required' => true,
			'label' => 'Name',
			'validators' => array(
				array( 'NotEmpty', true ),
				array( 'alnum', true, true ),
				array( 'stringLength', false, array( 3, 255 ) ),
			),
		) )
	);

	function __construct()
	{
		$this->db = Zend_Registry::get("db");
		Zend_Db_Table_Abstract::setDefaultAdapter($this->db);
		
		$this->log = Zend_Registry::get('log');
		
		$this->_setup();
	}

	/**
	 * Fetch the row in the database that fits this particular name
	 * @param $name string The name of the ingredient you want to fetch
	 * @return $row Zend_Db_Table_Row the row of the Ingredient table
	 */

	function getByName( $name )
	{
		$ingredient = null;
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
			$this->log->info( 'Ingredient ' . sq_brackets( $data['name'] ) . ' already exists' );
		}

		return $this->getByName( $data['name'] );
	}
}
