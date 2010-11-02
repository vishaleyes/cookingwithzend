<?php

/**
* 
*/
class Recipe_Model_DbTable_Rating extends Zend_Db_Table_Abstract
{
	protected $_name = "ratings";
	protected $_primary = array( "recipe_id", "user_id" );

	# Primary does Auto Inc
	protected $_sequence = true;
	
	protected $_referenceMap = array(
		'User' => array(
			'columns'		=> 'user_id',
			'refTableClass' => 'Recipe_Model_DbTable_User',
			'refColumns'	=> 'id'
		),
		'Recipe' => array(
			'columns'       => 'recipe_id',
			'refTableClass' => 'Recipe_Model_DbTable_Recipe',
			'refColumns'    => 'id'
		)
	);
	
	/**
	 * Add user_id to insert query.
	 */
	
	public function insert(array $params)
	{
		$auth = Zend_Auth::getInstance();
		$identity = $auth->getIdentity();
		$params['user_id'] = $identity->id;
		$params['created'] = new Zend_Db_Expr('NOW()');
		
		return parent::insert($params);
	}
}
