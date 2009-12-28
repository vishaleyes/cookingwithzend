<?php

class Recipe_View_Helper_LoggedIn extends Zend_View_Helper_Abstract
{

	public $view;

	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
	
	public function loggedIn()
	{
		return ( $this->view->identity ? true : false );
	}

}