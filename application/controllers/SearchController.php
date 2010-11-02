<?php

class SearchController extends Recipe_Model_Controller
{
	public function init()
	{
		parent::init();
		$this->_model = new Recipe_Model_AclResource();
	}
	
	public function ingredientAction()
	{
		
	}
	
	public function recipeAction()
	{
		
	}
}