<?php

class Measurement extends Zend_Db_Table_Abstract {
	
	protected $_name = "measurements";
	protected $_primary = "id";	   

	# Primary does Auto Inc
	protected $_sequence = true;

	protected $_dependentTables = array('IngredientMeasurement');

	function __construct()
	{
		$this->db = Zend_Registry::get("db");
		Zend_Db_Table_Abstract::setDefaultAdapter($this->db);
		
		$this->log = Zend_Registry::get('log');
		
		$this->_setup();
	}

	/**
     * This is to replace the _form_fields_config, I find it easier to follow - CL
     */

	public function getFormElements()
	{
		$elements = array();
		$e = new Zend_Form_Element( 'text' );
		$e->setName( 'measurement_name')
		  ->setAttrib( 'id', 'measurement-name' )
		  ->setLabel( 'Measurement' )
		  ->addValidator( new Zend_Validate_Alnum(), true );
		$elements[] = $e;

		$e = new Zend_Form_Element( 'text' );
		$e->setName( 'measurement_abbr')
		  ->setAttrib( 'id', 'measurement-abbr' )
		  ->setLabel( 'Measurement Abbr' )
		  ->addValidator( new Zend_Validate_Alnum(), true );
		$elements[] = $e;
		
		return $elements;
	}


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
	 * Override the insert to try and not throw an exception back if it fails
	 * @param $data array associative array of vars to go in the db
	 * @return $row Zend_Db_Table_Row the row of the Ingredient table
	 */

	function insert( $data )
	{
		try {
			parent::insert( $data );
		} catch (Exception $e) {
			// Doesnt matter if we cannot insert the ingredient
			$this->log->info( 'Measurement ' . sq_brackets( $data['name'] ) . ' already exists' );
		}

		return $this->getByName( $data['name'] );
	}

}