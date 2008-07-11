<?php

class MethodItem extends Zend_Db_Table_Abstract {
	
	protected $_name = "method_items";
	protected $_primary = "id";

	# Primary does Auto Inc
	protected $_sequence = true;
	
	protected $_referenceMap = array(
		'Recipe' => array(
			'columns'		=> 'recipe_id',
			'refTableClass' => 'Recipe',
			'refColumns'	=> 'id'
		),
	);
	
	// Form elements for add/edit
	public $_form_fields_config = array(
		array( 'textarea', 'description', array(
			'label' => 'Description',
			'validators' => array(
				array( 'alnum', true, true )
			)
		) ),
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
