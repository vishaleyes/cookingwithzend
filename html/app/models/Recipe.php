<?php

class Recipe extends Zend_Db_Table_Abstract {
	
	protected $_name = "recipes";
	protected $_primary = "id";

	# Primary does Auto Inc
	protected $_sequence = true;
	
	protected $_dependentTables = array('RecipeIngredient', 'MethodItem');
	
	protected $_referenceMap = array(
		'User' => array(
			'columns'		=> 'user_id',
			'refTableClass' => 'User',
			'refColumns'	=> 'id'
		)
	);

	// Form elements for add/edit
	// field name => array(type, required, validatorArray, filtersArray);
	public $_form_fields_config = array(
		array( 'text', 'name', array(
			'required' => true,
			'label' => 'Name',
			'validators' => array(
				array( 'NotEmpty', true ),
				array( 'alnum', true, true ),
				array( 'stringLength', false, array( 3, 255 ) ),
			)
		) ),
		array( 'text', 'cooking_time', array(
			'required' => true,
			'label' => 'Cooking Time',
			'validators' => array(
				array( 'NotEmpty', true ),
				array( 'int' )
			)
		) ),
		array( 'text', 'preparation_time', array(
			'required' => true,
			'label' => 'Preparation Time',
			'validators' => array(
				array( 'NotEmpty', true ),
				array( 'int' )
			)
		) ),
		array( 'text', 'serves', array(
			'required' => true,
			'label' => 'Serves',
			'validators' => array(
				array( 'NotEmpty', true ),
				array( 'int' ),
				array( 'GreaterThan', true, array(0) ),
			)
		) ),
		array( 'text', 'difficulty', array(
			'required' => true,
			'label' => 'Difficulty',
			'validators' => array(
				array( 'NotEmpty', true ),
				array( 'int' ),
				array( 'Between', true, array(1,10) )
			)
		) ),
		array( 'checkbox', 'freezable', array(
			'label' => 'Freezable'
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

	public function insert( $params )
	{
		$params['creator_id'] = Zend_Registry::get( 'session')->user['id'];
		$params['created'] = new Zend_Db_Expr('NOW()');
		$params['updated'] = new Zend_Db_Expr('NOW()');
		
		return parent::insert( $params );
	}

	public function update( $params, $where )
	{
		$params['updated'] = new Zend_Db_Expr('NOW()');
		return parent::update( $params, $where );
	}

}
