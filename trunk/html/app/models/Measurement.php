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
				array( 'alnum', true, true )
			)
		) )
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
