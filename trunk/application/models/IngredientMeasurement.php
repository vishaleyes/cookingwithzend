<?php

class IngredientMeasurement extends Zend_Db_Table_Abstract {
	
	protected $_name = "ingredient_measurements";
	protected $_primary = "id";	   

	# Primary does Auto Inc
	protected $_sequence = true;

	protected $_referenceMap = array(
		'Ingredient' => array(
			'columns'		=> 'ingredient_id',
			'refTableClass' => 'Ingredient',
			'refColumns'	=> 'id'
		),
		'Measurement' => array(
			'columns'		=> 'measurement_id',
			'refTableClass' => 'Measurement',
			'refColumns'	=> 'id'
		)
	);

	// May be able to delete this
	function __construct( $prefetch = true )
	{
		if ( ! $prefetch === false ) unset( $_rowClass );
		$this->db = Zend_Registry::get("db");
		Zend_Db_Table_Abstract::setDefaultAdapter($this->db);
		
		$this->log = Zend_Registry::get('log');
		
		$this->_setup();
	}

}
