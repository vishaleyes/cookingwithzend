<?php

class Recipe extends Zend_Db_Table_Abstract {
	
	protected $_name = "recipes";
	protected $_primary = "id";
	protected $_rowClass = "RecipeRow";

	# Primary does Auto Inc
	protected $_sequence = true;
	
	protected $_dependentTables = array('RecipeIngredient', 'MethodItem');
	
	protected $_referenceMap = array(
		'User' => array(
			'columns'       => 'creator_id',
			'refTableClass' => 'User',
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

	public function getFormElements()
	{
		$elements = array();
		$e = new Zend_Form_Element_Text( 'name' );
		$e->setLabel( 'Name' )
		  ->setRequired( true )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_StringLength( array( 3, 255 ) ) )
		  ->addFilter( new Zend_Filter_HtmlEntities() );
		$elements[] = $e;

		$e = new Zend_Form_Element_Text( 'cooking_time' );
		$e->setLabel( 'Cooking Time (in minutes)' )
		  ->setRequired( true )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_Int() );
		$elements[] = $e;

		$e = new Zend_Form_Element_Text( 'preparation_time' );
		$e->setLabel( 'Preparation Time (in minutes)' )
		  ->setRequired( true )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_Int() );
		$elements[] = $e;

		$e = new Zend_Form_Element_Text( 'serves' );
		$e->setLabel( 'Serves' )
		  ->setRequired( true )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_Int() )
		  ->addValidator( new Zend_Validate_GreaterThan(0) );
		$elements[] = $e;

		$e = new Zend_Form_Element_Text( 'difficulty' );
		$e->setLabel( 'Difficulty' )
		  ->setRequired( true )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_Int() )
		  ->addValidator( new Zend_Validate_Between( 1,10 ) );
		$elements[] = $e;
		
		$e = new Zend_Form_Element_Checkbox( 'freezable' );
		$e->setLabel( 'Freezable' );
		$elements[] = $e;

		return $elements;
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
