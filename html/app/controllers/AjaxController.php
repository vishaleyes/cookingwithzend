<?php

class AjaxController extends DefaultController  
{

	public function preDispatch()
	{
		$this->loggedIn( array( 'getcomments' ) );
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

	public function getcommentsAction()
	{
		$items_per_page = ( $this->session->pagination['items_per_page'] ? $this->session->pagination['items_per_page'] : 1 );
		
		$c = new Comment();
		$rowset = $c->getComments( 1, 2, 0 );
		
		$this->view->pagination_config = array(
			'total_items'    => count( $rowset ),
		    'items_per_page' => $items_per_page,
			'style'          => 'digg_with_jquery'
		);

		echo $this->view->partial( $this->partialsFolder . '/comments.phtml', array( 'comments' => $rowset, 'pagination_config' => $this->view->pagination_config ) );
		
	}

	/**
	 * We are existing after the action is dispatched
	 *
	 */
	public function postDispatch() {
		exit;
	}
	
}
