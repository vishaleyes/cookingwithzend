<?php

class Tag extends Zend_Db_Table_Abstract {
	
	protected $_name = "tags";
	protected $_primary = "id";
	
	# Primary does Auto Inc
	protected $_sequence = true;

	public $DELIMETER = ' ';

	// Form elements for add/edit
	// field name => array(type, required, validatorArray, filtersArray);
	public $_form_fields_config = array(
		array( 'text', 'tag_name', array(
			'id' => 'tag-name',
			'required' => true,
			'label' => 'Tags',
			'validators' => array(
				array( 'NotEmpty', true ),
				array( 'alnum', true, true )
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

	public function splitTags( $text, Zend_Db_Table_Row $row ) 
	{
		if ( empty( $text ) )
			return;

		$tags = explode( $this->DELIMETER, $text );
		foreach ( $tags as $tag ) {
			try {
				$this->insert( array( 'name' => $tag ) );
			} catch (Exception $e) {
				// Do nothing?
			}

			$select = $this->select()->where( 'name = ?', $tag );
			$tag = $this->fetchRow( $select );
			
			$params = array(
				'tag_id'        => $tag->id,
				'taggable_id'   => $row->id,
				'taggable_type' => $row->getTableClass()
			);
			
			$tg = new Taggable();
			try {
				$tg->insert( $params );
			} catch (Exception $e) {
				// Taggable insert fauils, dont really worry
			}

		}
	}

}
