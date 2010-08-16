<?php

class Models_DbTable_Recipe extends Zend_Db_Table_Abstract
{
	protected $_name = "recipes";
	protected $_primary = "id";
	protected $_rowClass = "Models_DbTable_RecipeRow";

	# Primary does Auto Inc
	protected $_sequence = true;

	protected $_dependentTables = array(
		'Models_DbTable_RecipeIngredient', 
		'Models_DbTable_Method',
		'Models_DbTable_Comment'
	);

	protected $_referenceMap = array(
		'User' => array(
			'columns'       => 'user_id',
			'refTableClass' => 'Models_DbTable_User',
			'refColumns'	=> 'id'
		)
	);
	
	/**
	 * Overide the insert function to ensure the relevant fields get filled in
	 *
	 * @param array $params
	 * @return unknown
	 */
	
	public function insert( array $params )
	{
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		$params['user_id'] = $identity->id;
		$params['created'] = new Zend_Db_Expr('NOW()');
		$params['updated'] = new Zend_Db_Expr('NOW()');
		$params['total_time'] = $params['preparation_time'] + $params['cooking_time'];
		
		return parent::insert( $params );
	}

	public function update( array $params, $where )
	{
		$params['updated'] = new Zend_Db_Expr('NOW()');
		$params['total_time'] = $params['preparation_time'] + $params['cooking_time'];
		return parent::update( $params, $where );
	}
	
}
