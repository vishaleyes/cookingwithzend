<?php

class Tag extends Zend_Db_Table_Abstract {
	
	protected $_name = "tags";
	protected $_primary = "id";
	
	# Primary does Auto Inc
	protected $_sequence = true;

	// Form elements for add/edit
	// field name => array(type, required, validatorArray, filtersArray);
	public $_form_fields_config = array(
		array( 'text', 'tag_name', array(
			'id' => 'tag-name',
			'required' => true,
			'label' => 'Tags',
			'validators' => array(
				array( 'NotEmpty', true ),
				array( 'alnum', true, true ),
				array( 'stringLength', false, array( 3, 255 ) ),
			),
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
