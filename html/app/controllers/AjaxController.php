<?php

class AjaxController extends DefaultController  
{

	public function preDispatch()
	{
		$this->loggedIn();
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
	 * We are existing after the action is dispatched
	 *
	 */
	public function postDispatch() {
		exit;
	}
	
}
