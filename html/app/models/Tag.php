<?php

class Tag extends Zend_Db_Table_Abstract {
	
	protected $_name = "tags";
	protected $_primary = "id";
	
	# Primary does Auto Inc
	protected $_sequence = true;
	
	protected $_dependentTables = array('Tagging');

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

	/**
	 * Returns an associative array putting each object into the tag it
	 * belongs to
	 * @param $tags array The tag names you want to find
	 */

	public function getTagObjects( $tags = array() )
	{
		if ( count( $tags ) == 0 )
			return null;

		$select = $this->getAdapter()->select();
		$select->from( 'tags' )
		       ->join( 'taggings', 'tags.id = taggings.tag_id' )
		       ->where( 'name IN (?)', $tags );

		$stmt = $this->getAdapter()->query( $select );
		$rowset = $stmt->fetchAll();
		$output = array();
		foreach( $rowset as $row )
		{
			$class = new $row['taggable_type'];
			$object = $class->find( $row['taggable_id'] )->current();
			$output[$row['name']][]= $object;
		}
		return $output;
	}

	/**
	 * Splits the tags into an array and places them in the relevant tables
	 * @param $text string The text you want to split
	 * @param $row Zend_Db_Table_Row The row you want to associate the tag with
	 * @todo Nail down how I ensure we are passing a Zend_Db_Table_Row object
	 */

	public function splitTags( $text, $row ) 
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

			$select = $this->select()->where( 'name = ?', strtolower( $tag ) );
			$tag = $this->fetchRow( $select );
			
			$params = array(
				'tag_id'        => $tag->id,
				'taggable_id'   => $row->id,
				'taggable_type' => $row->getTableClass()
			);
			
			$tg = new Tagging();
			try {
				$tg->insert( $params );
			} catch (Exception $e) {
				// Taggable insert fauils, dont really worry
			}

		}
	}

	/**
	 * Retrieves the tags associated with this particulaar object
	 * @param $row Zend_Db_Table_Row The row you want to associate the tag with
	 * @todo Nail down how I ensure we are passing a Zend_Db_Table_Row object
	 */

	public function getTags( $row )
	{
		$select = $this->getAdapter()->select()
		             ->from( 'taggings' )
					 ->join( 'tags', 'tags.id = taggings.tag_id' )
		             ->where( 'taggable_type = ?', $row->getTableClass() )
					 ->where( 'taggable_id = ?', $row->id );

		$stmt = $this->getAdapter()->query( $select );
		$rowset = $stmt->fetchAll();

		return $rowset;

	}

}
