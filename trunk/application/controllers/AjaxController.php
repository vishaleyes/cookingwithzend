<?php

class AjaxController extends Zend_Controller_Action
{

	private $_text;
	
	private $_db;
	
	public function init() {
		$this->_db = Zend_Registry::get('db');
	}
	
	/**
	 * Ajax controller requires no layout and no view as it mostly sends back ajax
	 * we do this in preDispatch so that we dont have to repeat ourselves
	 */
	public function preDispatch()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$this->_text = $this->_getParam('q');
		
		if ( !$this->_text )
			return false;
	}
	
	/**
	 * Ajax call to return the Ingredients that fit the criteria
	 */

	public function getIngredientsAction()
	{	
		$i = new Recipe_Model_Ingredient();
		$select = $i->table->select()
			->where( 'LOWER(name) LIKE ?', '%'.strtolower($this->_text).'%' )
			->limit(5);
		
		$rowset = $i->table->fetchAll( $select );
		if ( $rowset )
			echo json_encode( $rowset->toArray() );
	}

	/**
	 * Ajax call to return the Measurements that fit the criteria
	 */

	public function getMeasurementsAction()
	{
		$text = $this->_getParam('q');
		
		$m = new Recipe_Model_Measurement();
		$select = $m->table->select()
			->where( 'LOWER(name) LIKE ?', '%'.strtolower($this->_text).'%' )
			->orWhere( 'LOWER(abbreviation) LIKE ?', '%'.strtolower($this->_text).'%' )
			->limit(5);

		$rowset = $m->table->fetchAll( $select );
		if ( $rowset )
			echo json_encode( $rowset->toArray() );
	}

	/**
	 * Check the username is not taken since usernames need to be unique
	 */

	public function userLookupAction()
	{
		$u = new Recipe_Model_User();
		$select = $u->table->select()
			->where( 'name = ?', $this->_text );
		
		$row = $u->table->fetchRow( $select );
		if ( $row ) {
			echo json_encode( 'is not free' );
			exit;
		}

		echo json_encode( 'is free' );
	}

	public function methodSortAction() {
		// Update the db to now meet the array
		$position = 1;
		foreach( $this->_getParam('method') as $method )
		{
			$data = array('position' => $position++);
			$this->_db->update('method_items', $data, 'id = '.$method);
		}
	}
	
	/**
	 * Ajax call to return the comments for the relevant recipe
	 */

	public function getCommentsAction()
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
	
	public function sendratingsAction()
	{
		echo 'foo';
		$params = array(
			'recipe_id'   => $this->recipe->id,
			'value'       => $this->_getParam('value'),
		);

		/*	Try and insert them into DB	*/
		try {
			$r = new Rating();
			$r->insert($params);
			
			/* Incease ratings counters */
			$this->db->update("users",array("ratings_count" => new Zend_Db_Expr("(ratings_count + 1)")),"id = " . $this->session->user['id']);
			$this->db->update("recipes",array("ratings_count" => new Zend_Db_Expr("(ratings_count + 1)")),"id = " . $params['recipe_id']);
			
			/*	If successful go back to last recipe		*/
			$this->_redirect( '/recipe/view/recipe_id/' . $params['recipe_id'] );
			
			exit;
			
		} catch(Exception $e) {
			/*	Broken constraints = throws exception. Display error and return to recipe		*/
			$this->log->debug( $e->getMessage() );
			$this->message->setNamespace( 'error' );
			$this->message->addMessage( 'You have already rated this recipe.' );
			$this->_redirect( '/recipe/view/recipe_id/' . $params['recipe_id'] );
		}
		
		
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
	
}
