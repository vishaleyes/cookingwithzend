<?php

class AjaxController extends DefaultController  
{

	public function preDispatch()
	{
		$this->loggedIn();
	}

	public function getingredientsAction()
	{
		$text = $this->_getParam('q');
		
		$i = new Ingredient();
		$select = $i->select()
			->where( 'LOWER(name) LIKE ?', '%'.strtolower($text).'%' )
			->limit(5);
		
		$rowset = $i->fetchAll();
		$this->log->info( var_export( $rowset->toArray(), true ) );
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
