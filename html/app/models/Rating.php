<?php

class Rating extends Zend_Db_Table_Abstract {
	
	protected $_name = "ratings";
	protected $_primary = "id";	   

	# Primary does Auto Inc
	protected $_sequence = true;
	
	protected $_referenceMap = array(
		'User' => array(
			'columns'		=> 'user_id',
			'refTableClass' => 'User',
			'refColumns'	=> 'id'
		),
		'Recipe' => array(
			'columns'       => 'recipe_id',
			'refTableClass' => 'Recipe',
			'refColumns'    => 'id'
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
