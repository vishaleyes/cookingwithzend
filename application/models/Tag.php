<?php

class Recipe_Model_Tag extends Zend_Db_Table_Abstract {
	
	protected $_name = "tags";
	protected $_primary = "id";
	
	# Primary does Auto Inc
	protected $_sequence = true;
	
	protected $_dependentTables = array('Tagging');

	public $DELIMETER = ' ';

	/**
	 * Returns an associative array putting each object into the tag it
	 * belongs to
	 * @param $tags array The tag names you want to find
	 */

	public function getTagObjects( $tags = null )
	{
		if ( $tags === null )
			return null;

		$tagArray = explode( ' ', $tags );
		sort( $tagArray );

		$select = $this->getAdapter()->select();

		$select->from( 'tags', array( 'mytags' => 'GROUP_CONCAT(tags.name SEPARATOR " ")' )  )
		       ->joinLeft( 'taggings', 'tags.id = taggings.tag_id' )
			   ->group( 'taggings.taggable_id' );
	
		foreach ( $tagArray as $tag ) {
			$select->having( 'GROUP_CONCAT(tags.name) LIKE ?', '%'.$tag.'%');
		}

		$select->order( 'mytags' );

		$this->log->debug( $select->__toString() );

		$stmt = $this->getAdapter()->query( $select );
		$rowset = $stmt->fetchAll();
		$output = array();
		foreach( $rowset as $row )
		{
			$class = new $row['taggable_type'];
			$object = $class->find( $row['taggable_id'] )->current();
			$output[]= $object->toArray();
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

			// If the tag is "" then skip to the next one
			if ( empty($tag) )
				continue;

			try {
				$this->insert( array( 'name' => $tag ) );
			} catch (Exception $e) {
				// Do nothing?
				$this->log->info( 'Inserting Tag failed, tag was '.$tag );
			}

			$select = $this->select()->where( 'name = ?', strtolower( $tag ) );
			$tag = $this->fetchRow( $select );
			
			$params = array(
				'tag_id'        => $tag->id,
				'taggable_id'   => $row->id,
				'taggable_type' => $row->getTableClass()
			);
			
			$tg = new Tagging();
			$this->log->info( 'Tag id ' . $tag->id );
			try {
				$this->log->info( var_export( $params, true ) );
				$rowid = $tg->insert( $params );

				$this->log->info( 'Id: '.$rowid );
			} catch (Exception $e) {
				// Taggable insert fails, dont really worry
				$this->log->info( 'Inserting Taggable failed, params were '.var_export( $params, true ) );
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

	/**
	 * This uses getTags and then compiles the tags as a string
	 */

	public function getTagsAsString( $row )
	{
		$names = array();
		$rowset = $this->getTags( $row );
		foreach( $rowset as $row )
		{
			$names[] = $row['name'];
		}
		$string = join( $this->DELIMETER, $names );

		return $string;

	}

	public function delete( $row )
	{
		$where[] = $this->getAdapter()->quoteInto( 'taggable_type = ?', $row->getTableClass() );
		$where[] = $this->getAdapter()->quoteInto( 'taggable_id = ?', $row->id );
		
		$this->getAdapter()->delete( 'taggings', $where );
	}

}
