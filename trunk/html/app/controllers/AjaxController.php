<?php

class AjaxController extends DefaultController  
{

	public function preDispatch()
	{
	//	$this->loggedIn( array( 'getcomments', 'userlookup' ) );
	}

	/**
	 * Ajax call to return the Ingredients that fit the criteria
	 */

	public function getingredientsAction()
	{
		$text = $this->_getParam('q');
		
		$i = new Ingredient();
		$select = $i->select()
			->where( 'LOWER(name) LIKE ?', '%'.strtolower($text).'%' )
			->limit(5);
		
		$rowset = $i->fetchAll( $select );
		if ( $rowset )
			echo json_encode( $rowset->toArray() );
	}

	/**
	 * Ajax call to return the Measurements that fit the criteria
	 */

	public function getmeasurementsAction()
	{
		$text = $this->_getParam('q');
		
		$m = new Measurement();
		$select = $m->select()
			->where( 'LOWER(name) LIKE ?', '%'.strtolower($text).'%' )
			->orWhere( 'LOWER(abbreviation) LIKE ?', '%'.strtolower($text).'%' )
			->limit(5);

		$rowset = $m->fetchAll( $select );
		if ( $rowset )
			echo json_encode( $rowset->toArray() );
	}

	/**
	 * Check the username is not taken since usernames need to be unique
	 */

	public function userlookupAction()
	{
		$text = $this->_getParam('q');

		$u = new User();
		$select = $u->select()
			->where( 'name = ?', $text );
		
		$row = $u->fetchRow( $select );
		if ( $row ) {
			echo json_encode( 'is not free' );
			exit;
		}

		echo json_encode( 'is free' );
	}

	/**
	 * Ajax call to return the comments for the relevant recipe
	 */

	public function getcommentsAction()
	{
		$items_per_page = ( $this->session->pagination['items_per_page'] ? $this->session->pagination['items_per_page'] : 5 );

		$page = $this->_getParam( 'page' );
		$offset = $items_per_page * ($page - 1);

		$c = new Comment();
		$rowset = $c->getComments( $this->recipe->id, $items_per_page, $offset );

		$this->view->pagination_config = array(
			'total_items'    => $this->recipe->comments_count,
		    'items_per_page' => $items_per_page,
			'style'          => 'digg_with_jquery'
		);

		echo $this->view->partial( $this->partialsFolder . '/comments.phtml', array( 'comments' => $rowset, 'pagination_config' => $this->view->pagination_config ) );
		
	}

	/**
	 *  Suggest tags to the user via a like match
	 *  @todo Not finished
	 */

	public function suggesttagAction()
	{
		$text = $this->_getParam('q');
		$textArray = split( ' ', strtolower( $text ) );
		$this->log->debug( 'TextArray: ' . var_export( $textArray, true ) );
		$text = array_pop( $textArray );
		$this->log->debug( 'Text: ' . $text );
		
		$select = $this->db->select()
		  ->from( 'tags', array( 'name' ) )
		  ->where( 'name LIKE ?', '%'.$text.'%' )
		  ->limit(5);

		$stmt = $select->query();
		$rowset = $stmt->fetchAll();

		if ( $rowset )
			echo json_encode( $rowset ); 
	
	}

	/**
	 * Sorts the method items how the user wants them
	 */

	public function sortmethodsAction()
	{
		$methods = $this->_getParam( 'method' );
		$this->log->info( var_export( $methods, true ) );
		$count = 0;
		foreach( $methods as $item )
		{
			$this->db->update( 'method_items', array( 'position' => $count ), 'id = '. $item );
			$count++;
		}
	}

	/**
	 * We are existing after the action is dispatched
	 *
	 */
	public function postDispatch() {
		exit;
	}
	
}
