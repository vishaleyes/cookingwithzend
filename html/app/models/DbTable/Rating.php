<?php

/**
* 
*/
class Models_DbTable_Rating extends Zend_Db_Table_Abstract
{
	protected $_name = "ratings";
	protected $_primary = array( "recipe_id", "user_id" );

	# Primary does Auto Inc
	protected $_sequence = true;
	
	protected $_referenceMap = array(
		'User' => array(
			'columns'		=> 'user_id',
			'refTableClass' => 'Models_DbTable_User',
			'refColumns'	=> 'id'
		),
		'Recipe' => array(
			'columns'       => 'recipe_id',
			'refTableClass' => 'Models_DbTable_Recipe',
			'refColumns'    => 'id'
		)
	);
	
	/**
	 * Add user_id to insert query.
	 */
	
	public function insert($params)
	{
		$params['user_id'] = Zend_Registry::get('session')->user['id'];
		
		return parent::insert($params);
	}
}