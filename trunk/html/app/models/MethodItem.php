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
			'required' => true,
			'label'    => 'Description'
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

	public function getFormElements()
	{
		$elements = array();
		$e = new Zend_Form_Element_Textarea( 'description' );

		$stripTags = new Zend_Filter_StripTags();
		$stripTags->setTagsAllowed( array( 'p', 'a', 'img', 'strong', 'b', 'i', 'em', 's', 'del' ) );
		$stripTags->setAttributesAllowed( array( 'href', 'target', 'rel', 'name', 'src', 'width', 'height', 'alt', 'title' ) );

		$e->setRequired( true )
		  ->setLabel( 'Description' )
		  ->setAttrib( 'class', 'fck' )
		  ->addFilter( $stripTags );
		$elements[] = $e;

		return $elements;
	}

}
