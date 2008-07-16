<?php

class Measurement extends Zend_Db_Table_Abstract {
	
	protected $_name = "measurements";
	protected $_primary = "id";	   

	# Primary does Auto Inc
	protected $_sequence = true;

	protected $_dependentTables = array('IngredientMeasurement');

	public $_form_fields_config = array(
		array( 'text', 'measurement_name', array(
			'id' => 'measurement-name',
			'required' => false,
			'label' => 'Measurement',
			'validators' => array(
				array( 'alnum', true )
			)
		) ),
		array( 'text', 'measurement_abbr', array(
			'id' => 'measurement-abbr',
			'required' => false,
			'label' => 'Measurement Abbr',
			'validators' => array(
				array( 'alnum', true )
			)
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
	 * Override the insert to try and not throw an exception back if it fails
	 * @param $data array associative array of vars to go in the db
	 * @return $row Zend_Db_Table_Row the row of the Ingredient table
	 */

	function insert( $data )
	{
		try {
			$parent::insert( $data );
		} catch (Exception $e) {
			// Doesnt matter if we cannot insert the ingredient
			$this->log->info( 'Measurement ' . sq_brackets( $data['name'] ) . ' already exists' );
		}

		return $this->getByName( $data['name'] );
	}

}
