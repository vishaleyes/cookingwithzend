<?php

class Recipe_Model_DbTable_Comment extends Zend_Db_Table_Abstract {
	
	protected $_name = "comments";
	protected $_primary = "id";

	# Primary does Auto Inc
	protected $_sequence = true;
	
	protected $_referenceMap = array(
		'User' => array(
			'columns'		=> 'user_id',
			'refTableClass' => 'User',
			'refColumns'	=> 'id'
		),
		'Recipe' => array(
			'columns'		=> 'recipe_id',
			'refTableClass' => 'Recipe',
			'refColumns'	=> 'id'
		)
			
	);
	
	public function insert(array $params)
	{
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		$params['user_id'] = $identity->id;
		$params['created'] = new Zend_Db_Expr('NOW()');
		
		return parent::insert($params);
	}

}
